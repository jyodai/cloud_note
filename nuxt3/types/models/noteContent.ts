export default interface NoteContent {
  id: number;
  note_id: number;
  note_type: string;
  content: string;
  created_at: string;
  updated_at: string;
  invalidation_flag: number;
}
