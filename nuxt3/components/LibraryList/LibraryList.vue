<template>
  <div class="library-list">
    <modal
      :modal-name="modalName"
      :modal-option="modalOption"
    >
      <template #modalTitle>
        ライブラリ一覧
      </template>
      <template
        v-if="visible"
        #modalContent
      >
        <div class="content-header">
          <primary-button
            :label="'追加'"
            @click="openAddImageLibrary()"
          />
        </div>

        <div class="content-body g-table-list">
          <table>
            <thead>
              <tr>
                <th>
                  ファイル名
                </th>
                <th class="table-header-icon" />
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="file in fileList"
                :key="file.fileName"
              >
                <td>
                  {{ file.fileName }}
                </td>
                <td>
                  <icon-list
                    :show-icons="['copy', 'edit', 'trash']"
                    @copy="getFileHtml(file.fileHtml)"
                    @edit="openEdit(file.fileName)"
                    @trash="deleteFile(file.fileName)"
                  />
                </td>
              </tr>
            </tbody>
          </table>
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
import IconList from '~/commonComponents/IconList.vue';
import PrimaryButton from '~/commonComponents/PrimaryButton.vue';

export default {
  components : {
    Modal,
    ModalFooterButton,
    IconList,
    PrimaryButton,
  },
  data () {
    return {
      modalName   : 'LibraryList',
      modalOption : {
        beforeOpen : this.beforeOpen,
      },
      visible  : false,
      fileList : null,
    };
  },
  methods : {
    beforeOpen () {
      this.getFileList();
    },
    closeModal () {
      this.$vfm.close('LibraryList', this.$const.MODAL_CLOSE_TYPE_CLOSE);
    },
    async getFileList () {
      const url      = this.$config.public.apiUrl + '/libraries';
      const response = await this.$axios.get(url);
      this.fileList  = response;
      this.visible   = true;
    },
    getFileHtml(str) {
      navigator.clipboard.writeText(str)
        .then(() => {
          alert('HTMLをコピーしました');
        })
        .catch((error) => {
          console.error('クリップボードへのコピーに失敗しました:', error);
        });
    },
    openAddImageLibrary () {
      this.$vfm.open('LibraryAdd');
      this.$vfm.setClosedCallback('LibraryAdd', () => { this.beforeOpen(); });
    },
    openEdit (fileName) {
      this.$vfm.open('LibraryEdit', {fileName, });
      this.$vfm.setClosedCallback('LibraryEdit', () => { this.beforeOpen(); });
    },
    async deleteFile (fileName) {
      if (!confirm(fileName + 'を削除します')) {
        return;
      }

      const url      = this.$config.public.apiUrl + '/libraries';
      const params   = {
        originFileName : fileName,
      };
      const config   = {
        headers : {
          'X-HTTP-Method-Override' : 'DELETE',
          'Content-Type'           : 'application/x-www-form-urlencoded',
        },
      };
      const response = await this.$axios.post(url, params, config);
      alert(response.message);

      this.beforeOpen();
    },
  },
};
</script>

<style lang="scss" scoped>
.library-list {
  height: 100%;
  width: 100%;

  .content-header {
    height: 50px;
    text-align: right;
  }

  .content-body {
    height: calc(100% - 50px);
    overflow: auto;
    .table-header-icon {
      text-align: center;
      width : 100px;
    }
  }
}
</style>
