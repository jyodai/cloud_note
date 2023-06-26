<template>
  <!-- eslint-disable -->
  <div
    id="markdownArea"
    class="g-markdown-area line-numbers"
    v-html="$md.render(markdown)"
  />
  <!-- eslint-enable -->
</template>

<script>
import Mermaid from 'mermaid/dist/mermaid';

export default {
  props : {
    content : {
      type    : Object,
      default : () => { return {}; },
    },
  },
  data () {
    return {
      markdown : '',
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
    initializeMermaid () {
      Mermaid.initialize({
        theme          : 'dark',
        themeVariables : {
          darkMode : true,
        },
      });
    },
    // markdownItでのHTMLレンダリングの前段階で独自のMarkdown処理を行う
    beforeMarkdown (content) {
      content = this.convertMermaid(content);
      content = this.convertIndent(content);
      content = this.convertStyle(content);
      content = this.convertImageToken(content);
      return content;
    },
    convertMermaid (content) {
      content = this.removeSpaceMermaid(content, /\(```*?mermaid[\s\S]*?\(```\/\)/g);
      content = this.removeSpaceMermaid(content, /```*?mermaid[\s\S]*?```/g);
      return content;
    },
    removeSpaceMermaid (content, pattern) {
      const str = content.match(pattern);
      if (!str) {
        return content;
      }

      for (let i = 0; i < str.length; i++) {
        // レンダリング時に空行があると<p>タグに変換され、mermaidがエラーとなる
        let replaceStr = str[i].replace(/^\n/gm, '').replace(/^\s+/g, '');
        // mermaid内は見た目を整えている4スペースは必要ない
        replaceStr = replaceStr.replace(/\u0020\u0020\u0020\u0020/gm, '');

        content = content.replace(str[i], replaceStr);
      }
      return content;
    },
    // markdownItで連続スペースが1つの扱いになるので防ぐ
    convertIndent (content) {
      return content.replace(/\u0020\u0020\u0020\u0020/g, '<span class="replace_space">    </span>');
    },
    convertStyle (content) {
      return content
        .replace(/\(```(.*?)`(.*?)\)/g, '<p class="$1" style="$2">')
        .replace(/\(```\/\)/g, '</p>');
    },
    convertImageToken (content) {
      const token = this.$store.getters['User/getToken'];
      return content.replace(/%cn_api_token%/g, token);
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
        let headNum     = headElement.outerHTML.match(/<h(.*?)>/);
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
