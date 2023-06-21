<template>
  <div>
    <library-list
      v-if="reload.libraryList"
      @reloadModal="reloadModal"
    />
    <library-add
      v-if="reload.libraryAdd"
      @reloadModal="reloadModal"
    />
    <library-edit
      v-if="reload.libraryEdit"
      @reloadModal="reloadModal"
    />
    <user-list
      v-if="reload.userList"
      @reloadModal="reloadModal"
    />
  </div>
</template>

<script>
import LibraryList from '~/components/LibraryList/LibraryList.vue'
import LibraryAdd from '~/components/LibraryAdd/LibraryAdd.vue'
import LibraryEdit from '~/components/LibraryEdit/LibraryEdit.vue'
import UserList from '~/components/UserList/UserList.vue'

export default {
  components: {
    LibraryList,
    LibraryAdd,
    LibraryEdit,
    UserList,
  },
  data () {
    return {
      reload: {
        libraryList : true,
        libraryAdd  : true,
        libraryEdit : true,
        userList    : true,
      },
    }
  },
  mounted () {},
  methods: {
    reloadModal (key) {
      this.reload[key] = false
      this.$nextTick(() => (this.reload[key] = true))
    },
  },
}
</script>

<style lang="scss" scoped>
</style>
