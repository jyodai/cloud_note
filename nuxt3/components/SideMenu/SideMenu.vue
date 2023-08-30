<template>
  <div class="side-menu">
    <div class="header">
      <div
        class="item g-pointer"
        @click="showHeaderDialog()"
      >
        <v-icon size="16">
          mdi-account-outline
        </v-icon>
        {{ user.name }}
      </div>

      <dialog
        ref="headerDialog"
        class="header-dialog"
      >
        <div class="header-dialog-content">
          <div
            class="g-pointer header-dialog-item"
            @click="logout"
          >
            ログアウト
          </div>
        </div>
      </dialog>
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
        @click="openModal('Setting')"
      >
        <v-icon size="16">
          mdi-cog
        </v-icon>
        設定
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
    showHeaderDialog() {
      const dialog = this.$refs.headerDialog;
      dialog.showModal();
      dialog.addEventListener('click', (event) => {
        if(event.target.closest('.header-dialog-content') === null) {
          dialog.close();
        }
      });
    },
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
  min-width: 200px;
  overflow : auto;
  border-bottom: 1px solid $color-border;

  .header {
    height: 40px;
    padding: 10px;
    position: relative;
    .item {
      font-weight: bold;
      color: $color-text-primary;
      &:hover {
        background: $color-hover;
      }
    }
    .header-dialog {
      color: $color-text-primary-dark;
      background: $color-primary-dark;
      z-index: 1;
      border: 1px solid $color-border;
      padding : 5px;
      width : 220px;
      border-radius: 0.25rem;
      position: absolute;
      left : 10px;
      top : 40px;
      &:focus-visible {
        outline: none;
      }
      .header-dialog-item {
        &:hover {
          background: $color-hover;
        }
      }
    }
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
        background: $color-hover;
      }
    }
  }
}
</style>
