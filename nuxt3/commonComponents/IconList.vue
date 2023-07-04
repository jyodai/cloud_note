<template>
  <template
    v-for="(icon, index) in icons"
    :key="index"
  >
    <v-icon
      v-if="showIcons.includes(icon.key)"
      size="20"
      class="icon"
      @click="icon.event()"
    >
      {{ icon.icon }}
    </v-icon>
  </template>
</template>

<script>

export default {
  props : {
    showIcons : {
      type    : Array,
      default : () => [],
    },
  },
  emits : [
    "edit",
    "copy",
    "trash",
  ],
  data () {
    return {
      icons : [
        {
          key   : 'copy',
          icon  : 'mdi-content-copy',
          event : () => { this.copy(); },
        },
        {
          key   : 'edit',
          icon  : 'mdi-pencil-outline',
          event : () => { this.edit(); },
        },
        {
          key   : 'trash',
          icon  : 'mdi-trash-can-outline',
          event : () => { this.trash(); },
        },
      ],
    };
  },
  methods : {
    copy () {
      this.$emit('copy');
    },
    edit () {
      this.$emit('edit');
    },
    trash () {
      this.$emit('trash');
    },
  },
};
</script>

<style lang="scss" scoped>
.icon {
  margin-right : 5px;
}
</style>
