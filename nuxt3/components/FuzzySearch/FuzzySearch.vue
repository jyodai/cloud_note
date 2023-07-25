<template>
  <div class="fuzzy-search">
    <modal
      :modal-name="modalName"
      :modal-option="modalOption"
    >
      <template #modalTitle>
        検索
      </template>
      <template
        #modalContent
      >
        <div class="content-header">
          <div>
            <input
              ref="searchInputRef"
              type="text"
              @change="search(($event.target as HTMLInputElement).value)"
            >
          </div>
        </div>

        <div class="content-body">
          <splitpanes>
            <pane
              size="50"
              class="search-result-area"
            >
              <div
                v-for="result in searchResult"
                :key="result.item.noteId"
                class="search-result g-pointer"
              >
                <span :class="{'select': noteContent && noteContent.note_id === result.item.noteId}">
                  <icon-list
                    :show-icons="['preview']"
                    @preview="select(result.item.noteId)"
                  />
                </span>
                <span @click="open(result.item.noteId)">
                  {{ result.item.path }}
                </span>
              </div>
            </pane>
            <pane class="search-preview-area">
              <markdown-view
                v-if="noteContent !== null"
                :content="noteContent"
              />
            </pane>
          </splitpanes>
        </div>
      </template>
      <template #modalAction>
        <modal-footer-button
          :visible-lists="['close']"
          @close="closeModal"
        />
      </template>
    </modal>
  </div>
</template>

<script setup lang="ts">
import { Splitpanes, Pane } from 'splitpanes';
import { ref, Ref } from 'vue';
import Modal from '../Modal/ModalWrapper.vue';
import ModalFooterButton from '~/commonComponents/ModalFooterButton.vue';
import Fuse from 'fuse.js';
import { useNoteTabStore } from '~/store/NoteTab';
import Note from '~/types/models/note';
import NoteContent from '~/types/models/noteContent';
import MarkdownView from '~/commonComponents/MarkdownView.vue';
import IconList from '~/commonComponents/IconList.vue';

interface SearchList {
  noteId : number;
  path : string;
}

const nuxtApp = useNuxtApp();

const modalName = 'FuzzySearch';
let   fuse: Fuse<SearchList>;

const noteTabStore                                     = useNoteTabStore();
const searchList:Ref<SearchList[]>                     = ref([]);
const searchResult: Ref<Fuse.FuseResult<SearchList>[]> = ref([]);
const searchInputRef: Ref<HTMLInputElement | null>     = ref(null);
const noteContent: Ref<NoteContent | null>             = ref(null);

const modalOption = {
  opened : opened,
  closed : closed,
  width  : '95%',
  height : '95%',
};

async function opened(): Promise<void> {
  await load();
  if (searchInputRef.value) {
    searchInputRef.value.focus();
  }
}

function closed(): void {
  init();
}

function closeModal(): void {
  nuxtApp.$vfm.close(modalName, nuxtApp.$const.MODAL_CLOSE_TYPE_CLOSE);
}

async function load(): Promise<void> {
  if (searchList.value.length === 0) {
    await setSearchList();
    initFuse();
  }
}

async function init(): Promise<void> {
  searchResult.value = [];
  noteContent.value  = null;
}

async function setSearchList(): Promise<void> {
  const url          = nuxtApp.$config.public.apiUrl + '/notes?fields=id,path';
  const response     = await nuxtApp.$axios.get(url);
  const note: Note[] = response.data;
  searchList.value   = convert(note);
}

function initFuse(): void {
  const options = {
    keys : [
      "path",
    ]
  };
  fuse          = new Fuse(searchList.value, options);
}

function search(value: string): void {
  searchResult.value = fuse.search(value);
}

async function open(noteId: number): Promise<void> {
  const url      = nuxtApp.$config.public.apiUrl + `/notes/${noteId}`;
  const response = await nuxtApp.$axios.get(url);
  const note     = response.data;

  noteTabStore.setNoteTab(Object.assign({}, note));
  noteTabStore.setSelectNote(note);

  closeModal();
}

async function select(noteId: number): Promise<void> {
  const url         = nuxtApp.$config.public.apiUrl + `/notes/${noteId}/content`;
  const response    = await nuxtApp.$axios.get(url);
  noteContent.value = response.data as NoteContent;
}

function convert(notes: Note[]) {
  const list: SearchList[] = [];
  notes.forEach((note) => {
    list.push({
      'noteId' : note.id,
      'path'   : nuxtApp.$util.note.convertPath(note),
    });
  });
  return list;
}
</script>

<style lang="scss" scoped>
.fuzzy-search {
  height: 100%;
  width: 100%;

  .content-header {
    height: 50px;
    text-align: right;
  }

  .content-body {
    height: calc(100% - 50px);
    .search-result-area {
      overflow: auto;
      .search-result {
        font-size: 12px;
        &:hover {
          background: #666666;
        }
        .select {
          opacity: 0.6;
        }
      }
    }
    .search-preview-area {
      overflow: auto;
    }
  }
}
</style>

