<template>
  <div>
    <library-list />
    <library-add />
    <library-edit />
    <user-list />
    <user-add />
    <user-edit />
    <user-password-edit />
    <note-setting />
    <fuzzy-search />
  </div>
</template>

<script>
import LibraryList from '~/components/LibraryList/LibraryList.vue';
import LibraryAdd from '~/components/LibraryAdd/LibraryAdd.vue';
import LibraryEdit from '~/components/LibraryEdit/LibraryEdit.vue';
import UserList from '~/components/UserList/UserList.vue';
import UserAdd from '~/components/UserAdd/UserAdd.vue';
import UserEdit from '~/components/UserEdit/UserEdit.vue';
import UserPasswordEdit from '~/components/UserPasswordEdit/UserPasswordEdit.vue';
import NoteSetting from '~/components/NoteSetting/NoteSetting.vue';
import FuzzySearch from '~/components/FuzzySearch/FuzzySearch.vue';

export default {
  components : {
    LibraryList,
    LibraryAdd,
    LibraryEdit,
    UserList,
    UserAdd,
    UserEdit,
    UserPasswordEdit,
    NoteSetting,
    FuzzySearch,
  },
};
</script>

<style lang="scss" scoped>
</style>
