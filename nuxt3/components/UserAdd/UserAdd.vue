<template>
  <div class="user-add">
    <modal
      :modal-name="modalName"
    >
      <template #modalTitle>
        ユーザー追加
      </template>
      <template #modalContent>
        <div class="g-table-edit">
          <table>
            <tbody>
              <tr>
                <th>
                  ユーザー名
                </th>
                <td>
                  <input
                    v-model="user.name"
                    type="text"
                  >
                </td>
              </tr>
              <tr>
                <th>
                  メールアドレス
                </th>
                <td>
                  <input
                    v-model="user.email"
                    type="text"
                  >
                </td>
              </tr>
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
      modalName : 'UserAdd',
      user      : {
        name     : '',
        email    : '',
        password : '',
      },
    };
  },
  methods : {
    close (closeType = this.$const.MODAL_CLOSE_TYPE_CLOSE) {
      this.$vfm.close('UserAdd', closeType);
    },
    async save () {
      const url    = this.$config.public.apiUrl + '/users';
      const params = this.user;
      await this.$axios.post(url, params);
      this.close(this.$const.MODAL_CLOSE_TYPE_SAVE);
    }
  },
};
</script>

<style lang="scss" scoped>
.user-add {
  height: 100%;
  width: 100%;
}
</style>
