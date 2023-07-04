<template>
  <div class="user-edit">
    <modal
      :modal-name="modalName"
      :modal-option="modalOption"
    >
      <template #modalTitle>
        パスワード編集
      </template>
      <template #modalContent>
        <div class="g-table-edit">
          <table>
            <tbody>
              <tr>
                <th>
                  パスワード
                </th>
                <td>
                  <input
                    v-model="user.password"
                    type="text"
                  >
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
      modalName   : 'UserPasswordEdit',
      modalOption : {
        beforeOpen : this.beforeOpen,
      },
      user : {},
    };
  },
  methods : {
    beforeOpen () {
      this.load();
    },
    async load () {
      this.user = {
        password : '',
      };
    },
    close (closeType = this.$const.MODAL_CLOSE_TYPE_CLOSE) {
      this.$vfm.close('UserPasswordEdit', closeType);
    },
    async save () {
      const url    = this.$config.public.apiUrl + `/users/${this.user.id}/password`;
      const params = this.user;
      await this.$axios.put(url, params)
        .then(() => {
          this.close(this.$const.MODAL_CLOSE_TYPE_SAVE);
        })
        .catch(() => { return; })
      ;
    },
  },
};
</script>

<style lang="scss" scoped>
.user-edit {
  height: 100%;
  width: 100%;
}
</style>
