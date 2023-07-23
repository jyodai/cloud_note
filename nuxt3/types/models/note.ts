export default interface Note {
  id: number;
  parent_note_id: number;
  user_id: number;
  note_type: string;
  title: string;
  path : Array<string>;
  display_num: number;
  hierarchy: number;
  created_at: string;
  updated_at: string;
  invalidation_flag: number;
  children: Note[];
  path: string;
  hasChild: boolean;
}
