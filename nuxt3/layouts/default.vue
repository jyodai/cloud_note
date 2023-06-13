<template>
  <v-app dark>
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant="miniVariant"
      :clipped="clipped"
      fixed
      app
    >
      <dir-frame />
    </v-navigation-drawer>
    <v-app-bar
      :clipped-left="clipped"
      fixed
      app
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-btn
        icon
        @click.stop="miniVariant = !miniVariant"
      >
        <v-icon>mdi-{{ `chevron-${miniVariant ? 'right' : 'left'}` }}</v-icon>
      </v-btn>
      <v-btn
        icon
        @click.stop="clipped = !clipped"
      >
        <v-icon>mdi-application</v-icon>
      </v-btn>
      <v-btn
        icon
        @click.stop="fixed = !fixed"
      >
        <v-icon>mdi-minus</v-icon>
      </v-btn>
      <v-toolbar-title v-text="title" />
      <div style="margin-left : 10px">
        <search-area />
      </div>
      <v-spacer />
      <v-btn
        icon
        @click.stop="rightDrawer = !rightDrawer"
      >
        <v-icon>mdi-cog</v-icon>
      </v-btn>
    </v-app-bar>
    <v-main>
      <nuxt />
    </v-main>
    <v-navigation-drawer
      v-model="rightDrawer"
      :right="right"
      temporary
      fixed
    >
      <v-list>
        <v-list-item href="/memo-web/public">
          <v-list-item-action>
            <v-icon>
              mdi-chart-bubble
            </v-icon>
          </v-list-item-action>
          <v-list-item-title>note</v-list-item-title>
        </v-list-item>
        <v-list-item @click="openLibrary()">
          <v-list-item-action>
            <v-icon>
              mdi-file-image
            </v-icon>
          </v-list-item-action>
          <v-list-item-title>library</v-list-item-title>
        </v-list-item>
        <v-list-item href="/memo-web/public/logout">
          <v-list-item-action>
            <v-icon>
              mdi-arrow-collapse-right
            </v-icon>
          </v-list-item-action>
          <v-list-item-title>logout</v-list-item-title>
        </v-list-item>
        <v-list-item @click.native="right = !right">
          <v-list-item-action>
            <v-icon light>
              mdi-repeat
            </v-icon>
          </v-list-item-action>
          <v-list-item-title>Switch drawer</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-footer
      :fixed="fixed"
      app
    >
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script>
import DirFrame from '../components/directory/DirFrame.vue'
import SearchArea from '../components/SearchArea.vue'

export default {
  components: {
    DirFrame,
    SearchArea,
  },
  data () {
    return {
      clipped     : true,
      drawer      : true,
      fixed       : false,
      miniVariant : false,
      right       : true,
      rightDrawer : false,
      title       : 'Memo',
    }
  },
  mounted () {
  },
  methods: {
    openLibrary () {
      const url = '/memo-web/public/library'
      window.open(url, 'sub', 'top=100,left=300,width=600,height=500')
    },
  },
}
</script>
