import TaskElement from '~/types/models/taskElement';

export interface TaskMovement {
    moved: TaskMoved;
}

export interface TaskMoved {
    oldIndex: number;
    newIndex: number;
    element :  TaskElement;
}

export interface TaskMovedInfo {
  movement : TaskMovement;
  type : string,
  targetTaskElement : TaskElement;
}
