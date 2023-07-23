import Note from '~/types/models/note';

export default {
  convertPath(note: Note): string {
    return note.path.join(" > ");
  },
};
