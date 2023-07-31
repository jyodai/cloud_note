export default interface AddTaskElement {
    task_id: number;
    parent_task_element_id: number;
    name: string;
    content: string;
    start_date: string | null;
    due_date: string | null;
}
