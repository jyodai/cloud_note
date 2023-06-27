<template>
  <div class="side">
    <div class="side-icon">
      <div class="header g-center">
        <v-icon size="25">
          mdi-book-open-blank-variant
        </v-icon>
      </div>
      <div class="content">
        <div
          class="icon-box g-center g-pointer"
          @click="openNote"
        >
          <v-icon size="25">
            mdi-file-tree-outline
          </v-icon>
        </div>
        <div
          class="icon-box g-center g-pointer"
          @click="changeMenu('edit')"
        >
          <v-icon size="25">
            mdi-application-edit-outline
          </v-icon>
        </div>
        <div
          class="icon-box g-center g-pointer"
          @click="changeMenu('setting')"
        >
          <v-icon size="25">
            mdi-cog
          </v-icon>
        </div>
      </div>
    </div>
    <div class="side-content">
      <div class="header g-up-down-center">
        CloudNote
      </div>
      <div class="content">
        <template v-if="menu.edit.show">
          <div
            class="g-pointer"
            @click="openModal('LibraryList')"
          >
            <v-icon size="14">
              mdi-checkbox-blank-circle
            </v-icon>
            ライブラリ
          </div>
        </template>
        <template v-if="menu.setting.show">
          <div
            v-if="isAdminUser"
            class="g-pointer"
            @click="openModal('UserList')"
          >
            <v-icon size="14">
              mdi-checkbox-blank-circle
            </v-icon>
            ユーザー
          </div>
          <div
            class="g-pointer"
            @click="logout"
          >
            <v-icon
              size="14"
            >
              mdi-checkbox-blank-circle
            </v-icon>
            ログアウト
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  components : {
  },
  data () {
    return {
      isAdminUser : this.$store.getters['User/isAdminUser'],
      menu        : {
        edit : {
          show : false,
        },
        setting : {
          show : false,
        },
      },
    };
  },
  methods : {
    openNote () {
      this.$router.push('/');
    },
    changeMenu (menu) {
      if (this.menu[menu].show) {
        this.closePanel();
        this.allCloseMenu();
        return;
      }
      this.allCloseMenu();
      this.menu[menu].show = true;
      this.openPanel();
    },
    allCloseMenu () {
      Object.keys(this.menu).forEach(function (key) {
        this.menu[key].show = false;
      }.bind(this));
    },
    openPanel () {
      const sidePanel       = document.getElementById('sidePanel');
      sidePanel.style.width = '100%';
    },
    closePanel () {
      const sidePanel       = document.getElementById('sidePanel');
      sidePanel.style.width = '0%';
      const mainPanel       = document.getElementById('mainPanel');
      mainPanel.style.width = '100%';
    },
    openModal(key) {
      this.$vfm.open(key);
    },
    async logout () {
      await this.$store.dispatch('User/logout');
      window.location.href = '/login';
    },
  },
};
</script>

<style lang="scss" scoped>
.side {
  height: 100vh;
  display: flex;
  .side-icon {
    height: 100vh;
    background: #333333;
    .header {
      width: 50px;
      height: 40px;
      border-bottom: 1px solid #444444;
    }
    .content {
      height: calc(100% - 4px);
    }
    .icon-box {
      width: 50px;
      height: 40px;
    }
  }
  .side-content {
    height: 100%;
    width: 100%;
    background: #121212;
    border-right: 0.1px solid #222222;
    box-shadow: inset 3px 0px 20px 1px rgb(0 0 0);
    .header {
      height: 40px;
      color: #EEEEEE;
      border-bottom: 1px solid #444444;
      font-weight: bold;
      font-size: 20px;
      padding: 10px;
    }
    .content {
      height: calc(100% - 40px);
      padding: 10px;
      font-size: 14px;
    }
  }
}
</style>
