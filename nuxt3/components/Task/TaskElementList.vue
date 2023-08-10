<template>
  <draggable
    :list="taskElement"
    handle=".g-draggable-handle"
    item-key="id"
    @change="prepareChangeSort"
  >
    <template #item="{ element }">
      <div
        :class="{'pl-5' : element.hierarchy !== 1, 'mb-8' : element.hierarchy === 1}"
      >
        <task-element-item
          :task="task"
          :task-element="element"
          @add="add"
          @edit="edit"
          @delete-item="deleteItem"
        />

        <div v-if="element.children && element.children.length > 0">
          <task-element-list
            :task="task"
            :task-element="element.children"
            @add="add"
            @edit="edit"
            @delete-item="deleteItem"
            @change-sort="changeSort"
          />
        </div>
      </div>
    </template>
  </draggable>
</template>

<script setup lang="ts">
import Draggable from 'vuedraggable';
import Task from '~/types/models/task';
import TaskElement from '~/types/models/taskElement';
import AddTaskElement from '~/types/models/addTaskElement';
import TaskElementItem from '~/components/Task/TaskElementItem.vue';
import TaskElementList from '~/components/Task/TaskElementList.vue';
import { TaskMovement, TaskMovedInfo } from '~/types/libraries/draggable';

const props = defineProps({
  task : {
    type     : Object as () => Task,
    required : true,
  },
  taskElement : {
    type     : Object as () => TaskElement[],
    required : true,
  },
});

const emit = defineEmits<{
  add: [addTaskElement: AddTaskElement],
  edit: [editedTaskElement: TaskElement],
  deleteItem: [id: number],
  changeSort: [movedInfo: TaskMovedInfo],
}>();

function add(element: AddTaskElement): void {
  emit('add', element);
}

function edit(element: TaskElement): void {
  emit('edit', element);
}

function deleteItem(id: number): void {
  emit('deleteItem', id);
}

function prepareChangeSort(movement: TaskMovement) {
  if (!movement.moved) { 
    return;
  }

  const index           = movement.moved.newIndex;
  let targetTaskElement = null;
  let type              = null;
  if (props.taskElement[index + 1]) {
    targetTaskElement = props.taskElement[index + 1];
    type              = 'before';
  } else {
    targetTaskElement = props.taskElement[index - 1];
    type              = 'after';
  }

  const movedInfo: TaskMovedInfo = {
    movement,
    type,
    targetTaskElement,
  };

  changeSort(movedInfo);
}

function changeSort(movedInfo: TaskMovedInfo): void {
  emit('changeSort', movedInfo);
}

</script>
