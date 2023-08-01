<template>
  <draggable
    :list="taskElement"
    item-key="id"
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

defineProps({
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

</script>
