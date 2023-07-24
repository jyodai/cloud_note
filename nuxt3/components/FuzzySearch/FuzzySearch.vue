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
          <div
            v-for="result in searchResult"
            :key="result.item.noteId"
            class="search-result g-pointer"
            @click="select(result.item.noteId)"
          >
            {{ result.item.path }}
          </div>
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
import { ref, Ref } from 'vue';
import Modal from '../Modal/ModalWrapper.vue';
import ModalFooterButton from '~/commonComponents/ModalFooterButton.vue';
import Fuse from 'fuse.js';
import { useNoteTabStore } from '~/store/NoteTab';
import Note from '~/types/models/note';

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

const modalOption = {
  opened : opened,
  width  : '90%',
  height : '90%',
};

async function opened(): Promise<void> {
  await init();
  if (searchInputRef.value) {
    searchInputRef.value.focus();
  }
}

function closeModal(): void {
  nuxtApp.$vfm.close(modalName, nuxtApp.$const.MODAL_CLOSE_TYPE_CLOSE);
}

async function init(): Promise<void> {
  searchResult.value = [];
  if (searchList.value.length === 0) {
    await setSearchList();
    initFuse();
  }
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

async function select(noteId: number): Promise<void> {
  const url      = nuxtApp.$config.public.apiUrl + `/notes/${noteId}`;
  const response = await nuxtApp.$axios.get(url);
  const note     = response.data;

  noteTabStore.setNoteTab(Object.assign({}, note));
  noteTabStore.setSelectNote(note);

  closeModal();
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
    overflow: auto;
    .search-result {
      &:hover {
        background: #666666;
      }
    }
  }
}
</style>

