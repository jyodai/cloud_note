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
      const params = modal[0].params
      const id = params.user.id

      const queryStr = '?token=' + this.$store.getters['User/getToken'];
      const response = await this.$axios.get(this.$config.public.apiUrl + '/users/' + id + queryStr)
      this.user = response.data
    },
    close () {
      this.$vfm.hide('UserEdit')
      this.$emit('reloadModal', 'userEdit')
    },
    async save () {
      const queryStr = '?token=' + this.$store.getters['User/getToken'];
      const url = this.$config.public.apiUrl + '/users/' + this.user.id + queryStr
      const params = this.user;
      await this.$axios.put(url, params)
      this.close()
    }
  },
}
</script>

<style lang="scss" scoped>
.user-edit {
  height: 100%;
  width: 100%;
}
</style>
