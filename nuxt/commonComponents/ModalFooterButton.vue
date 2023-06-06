<template>
  <div>
    <v-btn
      v-for="(button, index) in buttonLists"
      :key="index"
      class="modal-footer-button"
      @click="button.event()"
    >
      {{ button.label }}
    </v-btn>
  </div>
</template>

<script>

export default {
  emit: [
    'save',
    'close',
  ],
  props: {
    content: {
      type    : Object,
      default : () => {},
    },
  },
  data () {
    return {
      buttonLists: [
        {
          label : '保存',
          event : () => this.save(),
        },
        {
          label : '閉じる',
          event : () => this.close(),
        },
      ],
    }
  },
  methods: {
    save () {
      this.$emit('save')
    },
    close () {
      this.$emit('close')
    },
  },
}
</script>

<style lang="scss" scoped>
.modal-footer-button {
  margin-right : 10px;
}
</style>
