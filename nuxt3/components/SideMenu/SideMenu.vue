<template>
  <div class="side-menu">
    <div class="header">
      <v-icon size="16">
        mdi-account-outline
      </v-icon>
      {{ user.name }}
    </div>
    <div class="body">
      <div
        class="item g-pointer"
        @click="openModal('FuzzySearch')"
      >
        <v-icon size="16">
          mdi-magnify
        </v-icon>
        検索
      </div>
      <div
        class="item g-pointer"
        @click="openModal('LibraryList')"
      >
        <v-icon size="16">
          mdi-image
        </v-icon>
        ライブラリ
      </div>
      <div
        class="item g-pointer"
        @click="openModal('NoteSetting')"
      >
        <v-icon size="16">
          mdi-comment-edit-outline
        </v-icon>
        ノート
      </div>
      <div
        v-if="userStore.getIsAdminUser"
        class="item g-pointer"
        @click="openModal('UserList')"
      >
        <v-icon size="16">
          mdi-account-edit
        </v-icon>
        ユーザー
      </div>
      <div
        class="item g-pointer"
        @click="logout"
      >
        <v-icon
          size="16"
        >
          mdi-logout
        </v-icon>
        ログアウト
      </div>
    </div>
  </div>
</template>

<script>
import { useUserStore } from '~/store/User';

export default {
  components : {
  },
  data () {
    return {
      userStore : useUserStore(),
      user      : null,
    };
  },
  created () {
    this.user = this.userStore.getUser;
  },
  methods : {
    openModal(key) {
      this.$vfm.open(key);
    },
    async logout () {
      await this.userStore.logout();
      window.location.href = this.$config.public.severAlias + '/login';
    },
  },
};
</script>

<style lang="scss" scoped>
.side-menu {
  height: 100%;
  width: 100%;
  overflow : auto;
  border-bottom: 1px solid #444444;
  .header {
    height: 40px;
    font-weight: bold;
    padding: 10px;
  }
  .body {
    font-size: 14px;
    padding-left : 10px;
    .item {
      margin-bottom: 3px;
      &:last-child {
        margin-bottom: 0px;
      }
      &:hover {
        background: #666666;
      }
    }
  }
}
</style>
