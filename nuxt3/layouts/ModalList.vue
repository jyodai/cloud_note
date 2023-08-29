<template>
  <div>
    <library-list />
    <library-add />
    <library-edit />
    <user-add />
    <user-edit />
    <user-password-edit />
    <fuzzy-search />
    <datepicker-modal />
    <setting-modal />
  </div>
</template>

<script setup lang="ts">
import LibraryList from '~/components/LibraryList/LibraryList.vue';
import LibraryAdd from '~/components/LibraryAdd/LibraryAdd.vue';
import LibraryEdit from '~/components/LibraryEdit/LibraryEdit.vue';
import UserAdd from '~/components/UserAdd/UserAdd.vue';
import UserEdit from '~/components/UserEdit/UserEdit.vue';
import UserPasswordEdit from '~/components/UserPasswordEdit/UserPasswordEdit.vue';
import FuzzySearch from '~/components/FuzzySearch/FuzzySearch.vue';
import DatepickerModal from '~/components/Datepicker/DatepickerModal.vue';
import SettingModal from '~/components/Setting/SettingModal.vue';
</script>
