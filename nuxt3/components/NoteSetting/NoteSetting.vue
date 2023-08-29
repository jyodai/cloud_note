<template>
  <div class="note-setting">
    <template
      v-if="visible"
    >
      <div class="content g-table-edit">
        <table>
          <tbody>
            <tr>
              <th>
                エディタオプション
              </th>
              <td>
                <code-mirror
                  :content="setting.editor_option"
                  @changes="changeEditorOption"
                />
              </td>
            </tr>
            <tr>
              <th>
                CSS
              </th>
              <td>
                <code-mirror
                  :content="setting.editor_css"
                  @changes="changeEditorCss"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="footer g-center">
        <primary-button
          :label="'保存'"
          @click="save()"
        />
      </div>
    </template>
  </div>
</template>

<script>
import CodeMirror from '~/commonComponents/CodeMirror.vue';
import PrimaryButton from '~/commonComponents/PrimaryButton.vue';

export default {
  components : {
    CodeMirror,
    PrimaryButton,
  },
  data () {
    return {
      visible : false,
      setting : null,
    };
  },
  created () {
    this.load();
  },
  methods : {
    async load () {
      const url      = this.$config.public.apiUrl + '/notes_settings';
      const response = await this.$axios.get(url);
      this.setting   = response.data;
      this.visible   = true;
    },
    async save() {
      if (!this.$util.json.isJSON(this.setting.editor_option)) {
        alert('エディタオプションがJSONになっていません。');
        return;
      }
      const url    = this.$config.public.apiUrl + '/notes_settings/' + this.setting.id;
      const params = this.setting;
      await this.$axios.put(url, params);
      location.reload();
    },
    changeEditorOption (content) {
      this.setting.editor_option = content;
    },
    changeEditorCss (content) {
      this.setting.editor_css = content;
    }
  },
};
</script>

<style lang="scss" scoped>
.note-setting {
  height: 100%;
  width: 100%;
  display: flex;
  flex-direction: column;
  position: relative;
  .content {
    flex-grow: 1;
    overflow-y: auto;
    table {
      th {
        width : 200px;
      }
    }
  }
}
</style>
