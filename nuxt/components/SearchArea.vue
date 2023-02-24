<template>
  <div id="serch-area">
    <input
      id="search_area"
      class="search_area"
      type="text"
      size="20"
      placeholder="Search"
      @keydown.enter="search()"
      @keydown.esc="searchRemove()"
    >
    <input id="search_left_box" class="search_box" type="checkbox" value="1" checked>L
    <input id="search_right_box" class="search_box" type="checkbox" value="1" checked>R
  </div>
</template>

<script>

export default {
  methods: {
    search () {
      this.searchRemove()

      const rightBox = document.getElementById('search_right_box')
      const leftBox = document.getElementById('search_left_box')
      const nodeIds = []
      if (rightBox.checked) {
        nodeIds.push('rightMarkdownarea')
      }
      if (leftBox.checked) {
        nodeIds.push('leftMarkdownarea')
      }
      for (let i = 0; i < nodeIds.length; i++) {
        const node = document.getElementById(nodeIds[i])
        this.searchExec(node)
      }

      if (!document.getElementsByClassName('serch_highlight').length) {
        alert('見つかりませんでした')
      }
    },
    searchExec (node) {
      const searchString = document.getElementById('search_area').value
      const att = 'serch_highlight'
      const start = '!!!start_highlight!!!'
      const end = '!!!end_hithlight!!!'

      if (node.nodeType === 3) {
        const RegularExp = new RegExp(searchString, 'g')
        const ReplaceString = start + searchString + end
        node.textContent = node.textContent.replace(RegularExp, ReplaceString)
      }

      for (let i = 0; i < node.childNodes.length; i++) {
        this.searchExec(node.childNodes[i]) // 再帰的な呼び出し
      }

      if (node.id === 'rightMarkdownarea' || node.id === 'leftMarkdownarea') {
        const RegularExp = new RegExp(start + searchString + end, 'g')
        const ReplaceString = '<span class="' + att + '">' + searchString + '</span>'
        node.innerHTML = node.innerHTML.replace(RegularExp, ReplaceString)
      }
    },
    searchRemove () {
      const nodeIds = [
        'rightMarkdownarea',
        'leftMarkdownarea',
      ]
      for (let i = 0; i < nodeIds.length; i++) {
        const node = document.getElementById(nodeIds[i])
        this.searchRemoveExec(node)
      }
    },
    searchRemoveExec (node) {
      const searchString = '<span class="serch_highlight">(.*?)</span>'
      const RegularExp = new RegExp(searchString, 'g')
      node.innerHTML = node.innerHTML.replace(RegularExp, '$1')
    },
  },
}

</script>

<style>

.search_area {
  margin-left : 10px;
  color : white;
  border-bottom:1px solid #cccccc;
  border-right:none;
  border-left:none;
  border-top:none;
  border-radius:0px;
}

.search_box {
  margin-left : 5px;
}

.serch_highlight {
  background-color: #ff00ff;
}
</style>
