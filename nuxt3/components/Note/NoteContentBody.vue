<template>
  <template v-if="visible">
    <div
      v-if="content !== null && !showMarkdown"
      class="content"
    >
      <markdown-edit
        class="content-edit"
        :content="content"
        @save-note="saveNote"
        @blur="blur"
      />
      <markdown-view
        ref="markdownViewRef"
        class="content-preview"
        :content="content"
      />
    </div>
    <div
      v-else-if="content !== null && showMarkdown"
      class="content"
    >
      <markdown-view
        ref="markdownViewRef"
        :content="content"
      />
    </div>
  </template>
</template>

<script setup lang="ts">
import { ref, Ref, watch, onMounted, onBeforeUnmount, computed } from 'vue';
import { CreateComponentPublicInstance } from 'vue';
import MarkdownView from '~/commonComponents/MarkdownView.vue';
import MarkdownEdit from '~/commonComponents/MarkdownEdit.vue';
import Html2Pdf from '~/libraries/html2Pdf';
import ShortcutKey from '~/libraries/shortcutKey';
import { useKeydown } from '~/composable/useKeydown';
import Note from '~/types/models/note';
import NoteContent from '~/types/models/noteContent';
import { useNoteContentStore } from '~/store/NoteContent';

const props = defineProps({
  note : {
    type     : Object as () => Note,
    required : true
  }
});

const noteContentStore = useNoteContentStore();

const visible                                                    = ref(false);
const showMarkdown                                               = ref(true);
const markdownViewRef: Ref<CreateComponentPublicInstance | null> = ref(null);

const content = computed(() => noteContentStore.getSelectContent);

await load(props.note);

watch(() => props.note, async (newVal) => {
  await load(newVal);
});

const changeEditorKey = new ShortcutKey('e', changeEditor);
const outputPdfKey    = new ShortcutKey('p', outputPdf);
useKeydown(changeEditorKey.handleKeyDown);
useKeydown(outputPdfKey.handleKeyDown);

async function load(note: Note) {
  visible.value = false;
  await noteContentStore.loadSelectContent(note);
  visible.value = true;
}

function changeEditor() {
  if (noteContentStore.getSelectNoteId === null) {
    alert('ファイルが選択されていません');
    return;
  }
  showMarkdown.value = !showMarkdown.value;
}

function blur(data: NoteContent) {
  saveNote(data);
}

function saveNote(data: NoteContent) {
  noteContentStore.updateSelectContent(data);
}

function outputPdf() {
  if (markdownViewRef.value === null) {
    return;
  }
  const element: HTMLElement = markdownViewRef.value.$el.parentNode;
  const fileName             = props.note.title;
  const pdf                  = new Html2Pdf(element, fileName);
  pdf.setCssClass('g-markdown-print');
  pdf.output();
}
</script>

<style lang="scss" scoped>
.content {
  width: 100%;
  height: 100%;
  flex-grow: 1;
  .content-edit {
    width: 50%;
    float: left;
  }
  .content-preview {
    width: 50%;
  }
}
</style>
