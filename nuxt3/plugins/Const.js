export default defineNuxtPlugin(() => {
  return {
      provide: {
        const : {
          NOTE_TYPE_NORMAL : 'App\\Models\\NoteContent',
          NOTE_TYPE_TASK : 'App\\Models\\Task',
        }
      },
    };
});