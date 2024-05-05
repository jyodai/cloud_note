<template>
  <div
    id="markdownArea"
    class="g-markdown-area line-numbers"
    v-html="$md.render(markdown)"
  />
</template>

<script>
import Mermaid from 'mermaid/dist/mermaid';
import { useUserStore } from '~/store/User';

export default {
  props : {
    content : {
      type    : Object,
      default : () => { return {}; },
    },
  },
  data () {
    return {
      userStore : useUserStore(),
      markdown  : '',
    };
  },
  computed : {
    changeNote () {
      return this.content;
    },
  },
  watch : {
    changeNote (newVal) {
      this.markdown = this.beforeMarkdown(newVal.content);
    },
  },
  created () {
    this.loadInlineCSS();
    this.initializeMermaid();
    this.markdown = this.beforeMarkdown(this.content.content);
  },
  updated () {
    this.afterMarkdown();
  },
  mounted () {
    this.afterMarkdown();
  },
  methods : {
    loadInlineCSS() {
      const user        = this.userStore.getUser;
      const style       = document.createElement('style');
      const content     = user.note_setting.editor_css;
      style.textContent = content;
      document.head.appendChild(style);
    },
    initializeMermaid () {
      Mermaid.initialize({
        theme          : 'dark',
        themeVariables : {
          darkMode : true,
          fontSize : '14px',
        },
      });
    },
    // markdownItでのHTMLレンダリングの前段階で独自のMarkdown処理を行う
    beforeMarkdown (content) {
      content = this.convertIndent(content);
      content = this.convertMermaid(content);
      content = this.convertStyle(content);
      content = this.convertImageToken(content);
      content = this.convertToc(content);
      return content;
    },
    convertIndent (content) {
      const lines = content.split('\n');
      return lines.map((line, index) => this.processIndentLine(line, lines[index - 1])).join('\n');
    },
    processIndentLine(line, previousLine) {
      // リスト要素内の行はそのまま返す
      const prevPattern = /^( {4})+(\s*\*|\s*\d+\.\s)/;
      if (previousLine && prevPattern.test(previousLine)) {
        return line;
      }

      // リスト要素以外の場合、インデントを変換する
      // 先頭が4の倍数のスペースで始まり、その直後に'*'や数値+ピリオド+スペースが続かない行
      const currentPattern = /^( {4})+(?!\s*\*|\s*\d+\.\s)/;
      if (currentPattern.test(line)) {
        const leadingSpaces = line.match(/^( {4})+/)[0].length;
        const indent        = '&nbsp;'.repeat(leadingSpaces);
        return line.replace(/^( {4})+/, indent);
      }

      return line;
    },
    convertMermaid (content) {
      const regex = /```mermaid\n([\s\S]*?)```/g;

      const convertContent = content.replace(regex, (match, innerText) => {
        return `<p class="mermaid">${innerText.trim()}</p>`;
      });

      return convertContent;
    },
    convertStyle (content) {
      return content
        .replace(/\(```(.*?)`(.*?)\)/g, '<p class="$1" style="$2">')
        .replace(/\(```\/\)/g, '</p>');
    },
    convertImageToken (content) {
      const token = this.userStore.getToken;
      return content.replace(/%cn_api_token%/g, token);
    },
    convertToc (content) {
      return content.replace(/\[TOC\]/g, '<div id="toc"></div>');
    },
    afterMarkdown () {
      this.revertPreCode();
      this.toc();
      Mermaid.init();
      this.$prism.highlightAll();
    },
    toc () {
      const area         = 'markdownArea';
      const tocMark      = '<div id="toc"></div>';
      const markdonwHtml = document.getElementById(area);

      const elements     = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
      const headElements = [];
      elements.forEach(function (element) {
        if (element.parentElement.id === area) {
          headElements.push(element);
        }
      });

      if (!headElements || !markdonwHtml.innerHTML.match(tocMark)) {
        return;
      }

      const tocElement = document.createElement('div');
      tocElement.setAttribute('id', 'toc');
      tocElement.classList.add('toc');
      tocElement.appendChild(document.createElement('ul'));

      let i = 1;
      headElements.forEach(function (headElement) {
        let headNum     = headElement.outerHTML.match(/<h([1-6])([^>]*)>/);
        headNum         = headNum[1];
        const headTitle = headElement.innerText;
        const headClass = 'head_content_' + headNum;
        const headId    = 'head_link_' + i;

        const anchor = document.createElement('li');
        anchor.classList.add(headClass);
        anchor.innerHTML = `<span onclick="document.getElementById('${headId}').scrollIntoView({behavior: 'smooth'})">${headTitle}</span>`;

        markdonwHtml.innerHTML = markdonwHtml.innerHTML.replace(headElement.outerHTML, `<h${headNum} id="${headId}">${headTitle}</h${headNum}>`);

        tocElement.children[0].appendChild(anchor);
        i++;
      });
      markdonwHtml.innerHTML = markdonwHtml.innerHTML.replace(tocMark, tocElement.outerHTML);
    },
    // preタグ内はbeforeMarkdown()で変換したコードを元の値に戻す
    revertPreCode () {
      const element = document.getElementsByTagName('code');
      const pattern = '&lt;span class="replace_space"&gt;    &lt;/span&gt;'; // <span class="replace_space">    </span>
      for (let i = 0; i < element.length; i++) {
        element[i].innerHTML = element[i].innerHTML.replace(new RegExp(pattern, 'g'), '    ');
      }
    },
  },
};
</script>
