<template>
  <!-- eslint-disable -->
  <div
    id="markdownArea"
    class="markdown-area line-numbers"
    v-html="$md.render(markdown)"
  />
  <!-- eslint-enable -->
</template>

<script>
import Mermaid from 'mermaid/dist/mermaid'

export default {
  props: {
    content: {
      type    : Object,
      default : () => {},
    },
  },
  data () {
    return {
      markdown: '',
    }
  },
  computed: {
    changeNote () {
      return this.content
    },
  },
  watch: {
    changeNote (newVal) {
      this.markdown = this.beforeMarkdown(newVal.content)
    },
  },
  created () {
    this.initializeMermaid()
    this.markdown = this.beforeMarkdown(this.content.content)
  },
  updated () {
    this.afterMarkdown()
  },
  mounted () {
    this.afterMarkdown()
  },
  methods: {
    initializeMermaid () {
      Mermaid.initialize({
        theme          : 'dark',
        themeVariables : {
          darkMode: true,
        },
      })
    },
    // markdownItでのHTMLレンダリングの前段階で独自のMarkdown処理を行う
    beforeMarkdown (content) {
      content = this.convertMermaid(content)
      content = this.convertIndent(content)
      content = this.convertStyle(content)
      content = this.convertImageToken(content)
      return content
    },
    convertMermaid (content) {
      content = this.removeSpaceMermaid(content, /\(```*?mermaid[\s\S]*?\(```\/\)/g)
      content = this.removeSpaceMermaid(content, /```*?mermaid[\s\S]*?```/g)
      return content
    },
    removeSpaceMermaid (content, pattern) {
      const str = content.match(pattern)
      if (!str) {
        return content
      }

      for (let i = 0; i < str.length; i++) {
        // レンダリング時に空行があると<p>タグに変換され、mermaidがエラーとなる
        let replaceStr = str[i].replace(/^\n/gm, '').replace(/^\s+/g, '')
        // mermaid内は見た目を整えている4スペースは必要ない
        replaceStr = replaceStr.replace(/\u0020\u0020\u0020\u0020/gm, '')

        content = content.replace(str[i], replaceStr)
      }
      return content
    },
    // markdownItで連続スペースが1つの扱いになるので防ぐ
    convertIndent (content) {
      return content.replace(/\u0020\u0020\u0020\u0020/g, '<span class="replace_space">    </span>')
    },
    convertStyle (content) {
      return content
        .replace(/\(```(.*?)`(.*?)\)/g, '<p class="$1" style="$2">')
        .replace(/\(```\/\)/g, '</p>')
    },
    convertImageToken (content) {
      const token = this.$store.getters['User/getToken']
      return content.replace(/%cn_api_token%/g, token)
    },
    afterMarkdown () {
      this.revertPreCode()
      this.toc()
      Mermaid.init()
      this.$prism.highlightAll()
    },
    toc () {
      const area = 'markdownArea'
      const tocMark = '<div id="toc"></div>'
      const markdonwHtml = document.getElementById(area)

      const elements = document.querySelectorAll('h1, h2, h3, h4, h5, h6')
      const headElements = []
      elements.forEach(function (element) {
        if (element.parentElement.id === area) {
          headElements.push(element)
        }
      })

      if (!headElements || !markdonwHtml.innerHTML.match(tocMark)) {
        return
      }

      const tocElement = document.createElement('div')
      tocElement.setAttribute('id', 'toc')
      tocElement.classList.add('toc')
      tocElement.appendChild(document.createElement('ul'))

      let i = 1
      headElements.forEach(function (headElement) {
        let headNum = headElement.outerHTML.match(/<h(.*?)>/)
        headNum = headNum[1]
        const headTitle = headElement.innerText
        const headClass = 'head_content_' + headNum
        const headId = 'head_link_' + i

        const anchor = document.createElement('li')
        anchor.classList.add(headClass)
        anchor.innerHTML = `<span onclick="document.getElementById('${headId}').scrollIntoView({behavior: 'smooth'})">${headTitle}</span>`

        markdonwHtml.innerHTML = markdonwHtml.innerHTML.replace(headElement.outerHTML, `<h${headNum} id="${headId}">${headTitle}</h${headNum}>`)

        tocElement.children[0].appendChild(anchor)
        i++
      })
      markdonwHtml.innerHTML = markdonwHtml.innerHTML.replace(tocMark, tocElement.outerHTML)
    },
    // preタグ内はbeforeMarkdown()で変換したコードを元の値に戻す
    revertPreCode () {
      const element = document.getElementsByTagName('code')
      const pattern = '&lt;span class="replace_space"&gt;    &lt;/span&gt;' // <span class="replace_space">    </span>
      for (let i = 0; i < element.length; i++) {
        element[i].innerHTML = element[i].innerHTML.replace(new RegExp(pattern, 'g'), '    ')
      }
    },
  },
}
</script>

<style lang="scss">
.markdown-area {
  font-family: 'Noto Sans JP', sans-serif;
  padding: 5px;
  height: 100%;
  font-size:14px;
  font-weight: 500;
  letter-spacing: 0.5px;
  line-height: 18px;
  text-align: left;
  color:#ffffff;
  overflow: auto;
  white-space : pre;
  h1 {
    width: 100%;
    font-size:25px;
    margin:0px;
    padding:3px;
  }

  h2 {
    font-size:20px;
    margin:0px;
    border-left: 6px solid #ccc;
    padding: .25em 0 .25em .75em;
    background-color: #2E2E2E;
  }

  h3 {
    font-size:18px;
    margin:0px;
    border-left: 4px solid #ccc;
    padding: .25em 0 .25em .75em;
  }

  h4 {
    font-size:14px;
    margin:0px;
    border-left: 2px solid #ccc;
    padding: .25em 0 .25em .75em;
  }

  table {
  padding: 5px;
  border-collapse: collapse;
  text-align: left;
  line-height: 1.5;
  border: 1px solid #ccc;
  }

  table th {
  padding: 5px;
  font-weight: bold;
  border-top: 1px solid #ccc;
  border-right: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
  background: #2C7CFF;
  }

  table td {
  padding: 5px;
  border-top: 1px solid #ccc;
  border-right: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
  }

  p {
    margin-bottom:0px;
  }

  ul {
    white-space : normal;
  }

  ul li {
    white-space : pre;
    margin-bottom : 5px;
  }

  ol {
    white-space : normal;
  }

  pre {
    font-family: 'Lucida Console', monospace;
    margin:10px;
    padding: 8px;
    font-size:14px;
    background-color: #121212;
    border-radius:10px;
    overflow: auto;
  }

  .toc {
    border-top: solid 1px #888888;
    border-bottom: solid 1px #888888;
    ul {
      color: #777777;
      list-style: none;
      padding : 10px;
      .head_content_1 {
        margin-left: 0px;
      }
      .head_content_2 {
        margin-left: 15px;
      }
      .head_content_3 {
        margin-left: 30px;
      }
      .head_content_4 {
        margin-left: 45px;
      }
      .head_content_5 {
        margin-left: 60px;
      }
      .head_content_6 {
        margin-left: 75px;
      }
      li:hover {
        text-decoration: underline;
      }
    }
  }
}
</style>
