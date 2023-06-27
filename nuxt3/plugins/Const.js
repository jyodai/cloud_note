export default defineNuxtPlugin(() => {
  return {
    provide : {
      const : {
        NOTE_TYPE_NORMAL : 'App\\Models\\NoteContent',
        NOTE_TYPE_TASK   : 'App\\Models\\Task',

        USER_TYPE_ADMIN : 1,

        MODAL_CLOSE_TYPE_SAVE  : 1,
        MODAL_CLOSE_TYPE_CLOSE : 2,
      }
    },
  };
});
