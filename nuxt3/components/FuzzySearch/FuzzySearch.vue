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
          @close="closeModal()"
        />
      </template>
    </modal>
  </div>
</template>

<script>
import Modal from '../Modal/ModalWrapper.vue';
import ModalFooterButton from '~/commonComponents/ModalFooterButton.vue';
import Fuse from 'fuse.js';
import { useNoteTabStore } from '~/store/NoteTab';

export default {
  components : {
    Modal,
    ModalFooterButton,
  },
  data () {
    return {
      noteTabStore : useNoteTabStore(),
      modalName    : 'FuzzySearch',
      modalOption  : {
        beforeOpen : this.beforeOpen,
      },
      visible      : false,
      searchList   : [],
      searchResult : [],
      fuse         : null,
    };
  },
  methods : {
    async beforeOpen () {
      this.visible = false;

      await this.initData();
      this.initFuse();

      this.visible = true;
    },
    closeModal () {
      this.$vfm.close('FuzzySearch', this.$const.MODAL_CLOSE_TYPE_CLOSE);
    },
    async initData() {
      this.searchResult = [];
      if (this.searchList.length === 0) {
        await this.setSearchList();
      }
    },
    async setSearchList() {
      const url      = this.$config.public.apiUrl + '/notes?fields=id,path';
      const response = await this.$axios.get(url);
      const notes    = this.convert(response.data);

      this.searchList = notes;
    },
    initFuse() {
      const options = {
        keys : [
          "path",
        ]
      };
      this.fuse     = new Fuse(this.searchList, options);
    },
    search(value) {
      this.searchResult = this.fuse.search(value);
    },
    async select(noteId) {
      const url      = this.$config.public.apiUrl + `/notes/${noteId}`;
      const response = await this.$axios.get(url);
      const note     = response.data;

      this.noteTabStore.setNoteTab(Object.assign({}, note));
      this.noteTabStore.setSelectNote(note);

      this.closeModal();
    },
    convert(notes) {
      notes.forEach((note) => {
        note.path = this.$util.note.convertPath(note);
      });
      return notes;
    },
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
