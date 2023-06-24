import MarkdownIt from 'markdown-it';

export default defineNuxtPlugin(() => {
  const instance = new MarkdownIt({
    html       : true, // HTML タグを有効にする
    breaks     : false, // 改行コードを<br>に変換する
    linkify    : false, // URLに似たテキストをリンクに自動変換する
    typography : false, // 言語に依存しないきれいな 置換 + 引用符 を有効にします。
  });
  return {
    provide : {
      md : instance,
    },
  };
});
