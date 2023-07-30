export default interface TaskElement {
    id: number;
    task_id: number;
    parent_task_element_id: number;
    name: string;
    content: string;
    display_num: number;
    hierarchy: number;
    completion_flag: number;
    register_date: string;
    start_date: string | null;
    due_date: string | null;
    completion_date: string | null;
    children: TaskElement[];
}
