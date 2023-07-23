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
        v-if="visible"
        #modalContent
      >
        <div class="content-header">
          <div>
            <input
              type="text"
              @change="search($event.target.value)"
            >
          </div>
        </div>

        <div class="content-body">
          <div
            v-for="result in searchResult"
            :key="result.item.id"
            class="search-result g-pointer"
            @click="select(result.item.id)"
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

<script>
import { ref } from 'vue';
import Modal from '../Modal/ModalWrapper.vue';
import ModalFooterButton from '~/commonComponents/ModalFooterButton.vue';
import Fuse from 'fuse.js';
import { useNoteTabStore } from '~/store/NoteTab';

export default {
  components : {
    Modal,
    ModalFooterButton,
  },
  setup() {
    const nuxtApp = useNuxtApp();

    const modalName = 'FuzzySearch';
    let   fuse      = null;

    const noteTabStore = useNoteTabStore();
    const visible      = ref(false);
    const searchList   = ref([]);
    const searchResult = ref([]);

    const modalOption = {
      beforeOpen : beforeOpen,
    };

    async function beforeOpen() {
      visible.value = false;

      await init();

      visible.value = true;
    }

    function closeModal() {
      nuxtApp.$vfm.close(modalName, nuxtApp.$const.MODAL_CLOSE_TYPE_CLOSE);
    }

    async function init() {
      searchResult.value = [];
      if (searchList.value.length === 0) {
        await setSearchList();
        initFuse();
      }
    }

    async function setSearchList() {
      const url      = nuxtApp.$config.public.apiUrl + '/notes?fields=id,path';
      const response = await nuxtApp.$axios.get(url);
      const notes    = convert(response.data);

      searchList.value = notes;
    }

    function initFuse() {
      const options = {
        keys : [
          "path",
        ]
      };
      fuse          = new Fuse(searchList.value, options);
    }

    function search(value) {
      searchResult.value = fuse.search(value);
    }

    async function select(noteId) {
      const url      = nuxtApp.$config.public.apiUrl + `/notes/${noteId}`;
      const response = await nuxtApp.$axios.get(url);
      const note     = response.data;

      noteTabStore.setNoteTab(Object.assign({}, note));
      noteTabStore.setSelectNote(note);

      closeModal();
    }

    function convert(notes) {
      notes.forEach((note) => {
        note.path = nuxtApp.$util.note.convertPath(note);
      });
      return notes;
    }

    return {
      modalName,
      modalOption,
      visible,
      searchResult,
      beforeOpen,
      closeModal,
      search,
      select,
    };
  },
};
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

