<template>
  <div class="user-edit">
    <modal
      :modal-name="modalName"
      :modal-option="modalOption"
    >
      <template #modalTitle>
        ユーザー編集
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
                    type="text"
                    v-model="user.name"
                    :disabled="isAdminUser(user)"
                  >
                </td>
              </tr>
              <tr>
                <th>
                  メールアドレス
                </th>
                <td>
                  <input
                    type="text"
                    v-model="user.email"
                    :disabled="isAdminUser(user)"
                  >
                </td>
              </tr>
              <tr>
                <th>
                  パスワード
                </th>
                <td>
                  <input type="text" v-model="user.password">
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
import Modal from '../Modal/ModalWrapper.vue'
import ModalFooterButton from '~/commonComponents/ModalFooterButton.vue'

export default {
  components: {
    Modal,
    ModalFooterButton,
  },
  data () {
    return {
      modalName   : 'UserEdit',
      modalOption : {
        beforeOpen : this.beforeOpen,
      },
      user : {},
    }
  },
  methods: {
    beforeOpen () {
      this.loadParams()
    },
    async loadParams () {
      const modal = this.$vfm.get('UserEdit')
      const params = modal.params
      const id = params.user.id

      const url = this.$config.public.apiUrl + '/users/' + id;
      const response = await this.$axios.get(url)
      this.user = response.data
    },
    close () {
      this.$vfm.close('UserEdit')
    },
    async save () {
      const url = this.$config.public.apiUrl + '/users/' + this.user.id;
      const params = this.user;
      await this.$axios.put(url, params)
      this.close()
    },
    isAdminUser (user) {
      return user.user_type === this.$const.USER_TYPE_ADMIN;
    },
  },
}
</script>

<style lang="scss" scoped>
.user-edit {
  height: 100%;
  width: 100%;
}
</style>
