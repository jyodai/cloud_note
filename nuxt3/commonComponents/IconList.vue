<template>
  <template
    v-for="(icon, index) in icons"
    :key="index"
  >
    <v-icon
      v-if="showIcons.includes(icon.key)"
      size="20"
      @click='icon.event()'
      class='icon'
    >
    {{ icon.icon }}
    </v-icon>
  </template>
</template>

<script>

export default {
  emit: [
    "edit",
    "trash",
  ],
  props: {
    showIcons: {
      type : Array,
      default : () => [],
    },
  },
  data () {
    return {
      icons : [
        {
            key : 'edit',
            icon  : 'mdi-pencil-outline',
            event : () => { this.edit() },
        },
        {
            key : 'trash',
            icon  : 'mdi-trash-can-outline',
            event : () => { this.trash() },
        },
      ],
    }
  },
  methods: {
    edit () {
      this.$emit('edit')
    },
    trash () {
      this.$emit('trash')
    },
  },
}
</script>

<style lang="scss" scoped>
.icon {
  margin-right : 5px;
}
</style>
