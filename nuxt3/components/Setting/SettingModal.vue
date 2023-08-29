<template>
  <div class="datepicker">
    <modal
      :modal-name="modalName"
      :modal-option="modalOption"
    >
      <template
        #modalSidebar
      >
        <div class="sidebar">
          <div
            class="item g-pointer"
            :class="{select : selectMenu === 'noteSetting'}"
            @click="select('noteSetting')"
          >
            <v-icon size="16">
              mdi-comment-edit-outline
            </v-icon>
            ノート
          </div>
          <div
            v-if="userStore.getIsAdminUser"
            class="item g-pointer"
            :class="{select : selectMenu === 'userList'}"
            @click="select('userList')"
          >
            <v-icon size="16">
              mdi-account-edit
            </v-icon>
            ユーザー
          </div>
        </div>
      </template>
      <template
        #modalContent
      >
        <template v-if="selectMenu === 'noteSetting'">
          <note-setting />
        </template>
        <template v-else-if="selectMenu === 'userList'">
          <user-list />
        </template>
      </template>
    </modal>
  </div>
</template>

<script setup lang="ts">
import { ref, Ref } from 'vue';
import Modal from '../Modal/ModalWrapper.vue';
import { useUserStore } from '~/store/User';
import NoteSetting from '~/components/NoteSetting/NoteSetting.vue';
import UserList from '~/components/UserList/UserList.vue';

const userStore = useUserStore();

const modalName = 'Setting';

const modalOption = {
  width      : '80%',
  height     : '70%',
  layoutType : 'split'
};

const selectMenu:Ref<string> = ref('noteSetting');

function select(menu: string): void {
  selectMenu.value = menu;
}

</script>

<style lang="scss" scoped>
.sidebar {
  .item {
    margin-bottom: 3px;
    &:last-child {
      margin-bottom: 0px;
    }
    &:hover {
      background: $color-hover;
    }
  }
  .select {
    background: $color-select;
  }
}
</style>
