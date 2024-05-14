import { defineStore } from 'pinia';
import type { Editor } from "codemirror";
import { CreateComponentPublicInstance } from 'vue';
import { Ref } from 'vue';

interface State {
  editor: Editor | null,
  isSyncingPreview: boolean,
  isSyncingEditor: boolean,
}

export const useSyncMarkdown = defineStore({
  id    : 'syncMarkdown',
  state : (): State => ({
    editor           : null,
    isSyncingPreview : false,
    isSyncingEditor  : false,
  }),
  getters : {},
  actions : {
    init() {
      this.editor = null;
    },
    setEditor(editorInstance: Editor) {
      this.editor = editorInstance;
    },
    syncEditor(element: HTMLElement) {
      const viewportTop = element.scrollTop;
      const elements    = Array.from(element.querySelectorAll<HTMLElement>('[data-source-line]'));
      
      const closestElement = findClosestElementToViewportTop(elements, viewportTop);

      if (closestElement !== null) {
        const sourceLine = closestElement.getAttribute('data-source-line') as string;
        const lineNumber = parseInt(sourceLine, 10);
        this.syncEditorToLine(lineNumber);
      }
    },
    syncEditorToLine(lineNumber: number) {
      if (this.isSyncingPreview) {
        return;
      }

      if (!this.editor) {
        return;
      }

      const isFocused = this.editor.hasFocus();
      if (isFocused) {
        return;
      }

      this.isSyncingEditor = true;

      const coords = this.editor.charCoords({line : lineNumber - 1, ch : 0}, "local");
      this.editor.scrollTo(null, coords.top);

      setTimeout(() => {
        this.isSyncingEditor = false;
      }, 100);
    },
    syncPreview(markdownViewRef: Ref<CreateComponentPublicInstance>) {
      if (this.isSyncingEditor) {
        return;
      }

      if (!this.editor) {
        return;
      }

      if (this.editor.hasFocus()) {
        return;
      }

      this.isSyncingPreview = true;

      const scrollInfo = this.editor.getScrollInfo();
      const from       = this.editor.coordsChar(
        {left : 0, top : scrollInfo.top},
        "local"
      );
      this.syncPreviewToLine(from.line, markdownViewRef);

      setTimeout(() => {
        this.isSyncingPreview = false;
      }, 100);
    },
    syncPreviewToLine(line: number, markdownViewRef: Ref<CreateComponentPublicInstance>) {
      const markdownView = markdownViewRef.value;
      if (!markdownView) return;
      const view = markdownView.$el;

      // 編集中の行に対応するプレビュー内の要素を見つける
      const lineElement = view.querySelector(`[data-source-line="${line}"]`);
      if (lineElement) {
        view.scrollTop = 0; // スクロール位置をリセット
        view.scrollTop = lineElement.offsetTop - 100;
      }
    },
  },
});

/**
 * ビューポートのトップに最も近い要素を取得する
 */
function findClosestElementToViewportTop(elements: HTMLElement[], viewportTop: number): HTMLElement | null {
  let closestElementTop: HTMLElement | null = null;
  let minTopDifference                      = Number.MAX_VALUE;

  elements.forEach(htmlElement => {
    const elementTop = htmlElement.getBoundingClientRect().top + viewportTop;

    if (elementTop >= viewportTop && (elementTop - viewportTop < minTopDifference)) {
      closestElementTop = htmlElement;
      minTopDifference  = elementTop - viewportTop;
    }
  });

  return closestElementTop;
}
