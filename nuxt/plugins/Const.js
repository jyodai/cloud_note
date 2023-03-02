export default (context, inject) => {
  inject('NOTE_TYPE_NORMAL', 'App\\Models\\NoteContent')
  inject('NOTE_TYPE_TASK', 'App\\Models\\Task')
}
