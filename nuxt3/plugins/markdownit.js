import MarkdownIt from 'markdown-it'

export default defineNuxtPlugin(() => {
  const instance = new MarkdownIt({})
  return {
      provide: {
        md : instance,
      },
    };
});
