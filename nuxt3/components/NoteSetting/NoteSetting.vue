<template>
  <div class="note-setting">
    <modal
      :modal-name="modalName"
      :modal-option="modalOption"
    >
      <template #modalTitle>
        ノート設定
      </template>
      <template
        v-if="visible"
        #modalContent
      >
        <div class="g-table-edit">
          <table>
            <tbody>
              <tr>
                <th>
                  エディタオプション
                </th>
                <td>
                  <code-mirror
                    :content="setting.editor_option"
                    @changes="changeEditorOption"
                  />
                </td>
              </tr>
              <tr>
                <th>
                  CSS
                </th>
                <td>
                  <code-mirror
                    :content="setting.editor_css"
                    @changes="changeEditorCss"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </template>
      <template #modalAction>
        <modal-footer-button
          :visible-lists="['save', 'close']"
          @save="save()"
          @close="close()"
        />
      </template>
    </modal>
  </div>
</template>

<script>
import Modal from '../Modal/ModalWrapper.vue';
import ModalFooterButton from '~/commonComponents/ModalFooterButton.vue';
import CodeMirror from '~/commonComponents/CodeMirror.vue';

export default {
  components : {
    Modal,
    ModalFooterButton,
    CodeMirror,
  },
  data () {
    return {
      modalName   : 'NoteSetting',
      modalOption : {
        beforeOpen : this.beforeOpen,
        width      : '90%',
      },
      visible : false,
      setting : null,
    };
  },
  methods : {
    beforeOpen () {
      this.load();
    },
    async load () {
      const url      = this.$config.public.apiUrl + '/notes_settings';
      const response = await this.$axios.get(url);
      this.setting   = response.data;
      this.visible   = true;
    },
    async save() {
      if (!this.$util.json.isJSON(this.setting.editor_option)) {
        alert('エディタオプションがJSONになっていません。');
        return;
      }
      const url    = this.$config.public.apiUrl + '/notes_settings/' + this.setting.id;
      const params = this.setting;
      await this.$axios.put(url, params)
        .then(() => {
          this.close(this.$const.MODAL_CLOSE_TYPE_SAVE);
        })
        .catch(() => { return; })
      ;
    },
    close (closeType = this.$const.MODAL_CLOSE_TYPE_CLOSE) {
      this.$vfm.close('NoteSetting', closeType);
    },
    changeEditorOption (content) {
      this.setting.editor_option = content;
    },
    changeEditorCss (content) {
      this.setting.editor_css = content;
    }
  },
};
</script>

<style lang="scss" scoped>
.note-setting {
  height: 100%;
  width: 100%;
  table {
    th {
      width : 200px;
    }
  }
}
</style>
