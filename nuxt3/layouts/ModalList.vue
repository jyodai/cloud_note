<template>
  <div>
    <library-list/>
    <library-add/>
    <library-edit/>
    <user-list/>
    <user-add/>
    <user-edit/>
  </div>
</template>

<script>
import LibraryList from '~/components/LibraryList/LibraryList.vue'
import LibraryAdd from '~/components/LibraryAdd/LibraryAdd.vue'
import LibraryEdit from '~/components/LibraryEdit/LibraryEdit.vue'
import UserList from '~/components/UserList/UserList.vue'
import UserAdd from '~/components/UserAdd/UserAdd.vue'
import UserEdit from '~/components/UserEdit/UserEdit.vue'

export default {
  components: {
    LibraryList,
    LibraryAdd,
    LibraryEdit,
    UserList,
    UserAdd,
    UserEdit,
  },
}
</script>

<style lang="scss" scoped>
</style>
