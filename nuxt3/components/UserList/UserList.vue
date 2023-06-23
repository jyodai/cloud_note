<template>
  <div class="user-list">
    <modal
      :modal-name="modalName"
      :modal-option="modalOption"
    >
      <template #modalTitle>
        ユーザー一覧
      </template>
      <template
        #modalContent
        v-if="visible"
      >
        <div class="content-header">
          <v-btn class="mb-2" @click="openAdd()">
            追加
          </v-btn>
        </div>

        <div class="content-body g-table-list">
          <table>
            <thead>
              <tr>
                <th>
                  ID
                </th>
                <th>
                  ユーザー名
                </th>
                <th>
                  メールアドレス
                </th>
                <th class="table-header-icon" />
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id">
                <td>
                  {{ user.id }}
                </td>
                <td>
                  {{ user.name }}
                </td>
                <td>
                  {{ user.email }}
                </td>
                <td>
                  <icon-list
                    :show-icons="getShowIcons(user)"
                    @edit="openEdit(user)"
                    @trash="deleteUser(user)"
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
import Modal from '../Modal/ModalWrapper.vue'
import ModalFooterButton from '~/commonComponents/ModalFooterButton.vue'
import IconList from '~/commonComponents/IconList.vue'

export default {
  components: {
    Modal,
    ModalFooterButton,
    IconList,
  },
  data () {
    return {
      modalName : 'UserList',
      modalOption : {
        beforeOpen : this.beforeOpen,
      },
      visible : false,
      users  : null,
    }
  },
  methods: {
    beforeOpen () {
      this.load();
    },
    async load () {
      const url = this.$config.public.apiUrl + '/users';
      const response = await this.$axios.get(url);
      this.users = response.data
      this.visible = true;
    },
    closeModal () {
      this.$vfm.close('UserList', this.$config.MODAL_CLOSE_TYPE_CLOSE);
    },
    openAdd () {
      this.$vfm.open('UserAdd')
    },
    openEdit (user) {
      this.$vfm.open('UserEdit', {user, })
    },
    async deleteUser (user) {
      if (!confirm(user.name + 'を削除します')) {
        return
      }

      const url = this.$config.public.apiUrl + '/users/' + user.id;
      const response = await this.$axios.delete(url)

      this.load();
    },
    getShowIcons (user) {
      const icons = ['edit'];
      if (!this.isAdminUser(user)) {
        icons.push('trash')
      }
      return icons;
    },
    isAdminUser (user) {
      return user.user_type === this.$const.USER_TYPE_ADMIN;
    },
  },
}
</script>

<style lang="scss" scoped>
.user-list {
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
      width : 80px;
    }
  }
}
</style>
