<template>
  <div>
    <span
      v-for="(button, index) in buttonLists"
      :key="index"
    >
      <template v-if="isVisible(button.name)">
        <v-btn
          class="modal-footer-button"
          @click="button.event()"
        >
          {{ button.label }}
        </v-btn>
      </template>
    </span>
  </div>
</template>

<script>

export default {
  emit : [
    'save',
    'delete',
    'close',
  ],
  props : {
    visibleLists : {
      type    : Array,
      default : () => [],
    },
  },
  data () {
    return {
      buttonLists : [
        {
          name  : 'save',
          label : '保存',
          event : () => this.save(),
        },
        {
          name  : 'delete',
          label : '削除',
          event : () => this.delete(),
        },
        {
          name  : 'close',
          label : '閉じる',
          event : () => this.close(),
        },
      ],
    }
  },
  methods : {
    save () {
      this.$emit('save')
    },
    delete () {
      this.$emit('delete')
    },
    close () {
      this.$emit('close')
    },
    isVisible (name) {
      return this.visibleLists.includes(name)
    },
  },
}
</script>

<style lang="scss" scoped>
.modal-footer-button {
  margin-right : 10px;
}
</style>
