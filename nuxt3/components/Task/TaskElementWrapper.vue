<template>
  <div class="wrapper">
    <div class="header">
      <div class="toggle-tree mb-5">
        <label class="mr-2">
          <input
            v-model="treeStatus"
            type="radio"
            :value="nuxtApp.$const.TASK_TREE_STATUS_UNFINISHED"
            @change="toggleTree(nuxtApp.$const.TASK_TREE_STATUS_UNFINISHED)"
          >
          未完了
        </label>
        <label class="mr-2">
          <input
            v-model="treeStatus"
            type="radio"
            :value="nuxtApp.$const.TASK_TREE_STATUS_FINISHED"
            @change="toggleTree(nuxtApp.$const.TASK_TREE_STATUS_FINISHED)"
          >
          完了済み
        </label>
        <label>
          <input
            v-model="treeStatus"
            type="radio"
            :value="nuxtApp.$const.TASK_TREE_STATUS_ALL"
            @change="toggleTree(nuxtApp.$const.TASK_TREE_STATUS_ALL)"
          >
          全て
        </label>
      </div>

      <div class="mb-5">
        <input
          v-model="addTaskElement.name"
          type="text"
          class="name mr-3"
        >
        <primary-button
          :label="'追加'"
          @click="add(addTaskElement); init();"
        />
      </div>

      <div class="element-header">
        <div class="left">
          <div class="item-name" />
        </div>
        <div class="right">
          <div class="item-date">
            開始日
          </div>
          <div class="item-date">
            期日
          </div>
          <div class="item-date">
            完了日
          </div>
          <div class="icon-list" />
        </div>
      </div>
    </div>

    <div class="body">
      <task-element-list
        :task="task"
        :task-element="taskElement"
        @add="add"
        @edit="edit"
        @delete-item="deleteItem"
        @change-sort="changeSort"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, Ref } from 'vue';
import PrimaryButton from '~/commonComponents/PrimaryButton.vue';
import Task from '~/types/models/task';
import TaskElementModel from '~/types/models/taskElement';
import AddTaskElement from '~/types/models/addTaskElement';
import TaskElementList from '~/components/Task/TaskElementList.vue';
import { TaskMovedInfo } from '~/types/libraries/draggable';

const nuxtApp = useNuxtApp();

const props = defineProps({
  task : {
    type     : Object as () => Task,
    required : true,
  },
  taskElement : {
    type     : Array as () => TaskElementModel[],
    required : true,
  },
});

const emit = defineEmits<{
  add: [addTaskElement: AddTaskElement],
  edit: [editedTaskElement: TaskElementModel],
  deleteItem: [id: number],
  changeSort: [movedInfo: TaskMovedInfo],
  toggleTree: [status: number],
}>();

const addTaskElement: Ref<AddTaskElement> = ref({
  task_id                : props.task.id,
  parent_task_element_id : 0,
  name                   : '',
  content                : '',
  start_date             : null,
  due_date               : null,
});
const treeStatus: Ref<number>             = ref(nuxtApp.$const.TASK_TREE_STATUS_UNFINISHED);

function init () {
  addTaskElement.value.name = '';
}

function add(addTaskElement: AddTaskElement): void {
  emit('add', addTaskElement);
}

function edit(editedTaskElement: TaskElementModel): void {
  emit('edit', editedTaskElement);
}

function deleteItem(id: number): void {
  emit('deleteItem', id);
}

function changeSort(movedInfo: TaskMovedInfo): void {
  emit('changeSort', movedInfo);
}

function toggleTree(status: number): void {
  emit('toggleTree', status);
}

</script>

<style lang="scss" scoped>
.wrapper {
  min-width: 1200px;
  width: 100%;
  height: 100%;
  font-size: 14px;
  .header {
    height : 130px;
    .toggle-tree {
    }
    .name {
      width : 600px;
    }
    .element-header {
      border-bottom: 3px solid #3E3E3E;
      display: flex;
      justify-content: space-between;
      .left {
        display: flex;
        align-items: center;
        .item-name {
          width : 600px;
        }
      }
      .right {
        display: flex;
        align-items: right;
        .item-date {
          width : 130px;
        }
        .icon-list {
          width : 110px;
        }
      }
    }
  }
  .body {
    height: calc(100% - 130px);
    overflow: auto;
  }
}
</style>
