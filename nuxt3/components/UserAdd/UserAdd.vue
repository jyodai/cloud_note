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
                  <input type="text" v-model="user.name">
                </td>
              </tr>
              <tr>
                <th>
                  メールアドレス
                </th>
                <td>
                  <input type="text" v-model="user.email">
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
      modalName   : 'UserAdd',
      user : {
        name : '',
        email : '',
        password : '',
      },
    }
  },
  methods: {
    close () {
      this.$vfm.close('UserAdd')
      this.$emit('reloadModal', 'userAdd')
    },
    async save () {
      const url = this.$config.public.apiUrl + '/users';
      const params = this.user;
      await this.$axios.post(url, params)
      this.close()
    }
  },
}
</script>

<style lang="scss" scoped>
.user-add {
  height: 100%;
  width: 100%;
}
</style>
