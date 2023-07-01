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
                  <textarea
                    v-model="setting.editor_option"
                    rows="3"
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

export default {
  components : {
    Modal,
    ModalFooterButton,
  },
  data () {
    return {
      modalName   : 'NoteSetting',
      modalOption : {
        beforeOpen : this.beforeOpen,
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
      await this.$axios.put(url, params);
      this.close(this.$const.MODAL_CLOSE_TYPE_SAVE);
    },
    close (closeType = this.$const.MODAL_CLOSE_TYPE_CLOSE) {
      this.$vfm.close('NoteSetting', closeType);
    },
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
    textarea {
      width: 100%;
      color: #fff;
    }
  }
}
</style>
