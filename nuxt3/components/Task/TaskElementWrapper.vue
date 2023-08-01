<template>
  <div class="wrapper">
    <div class="header">
      <div class="mb-5">
        <input
          v-model="addTaskElement.name"
          type="text"
          class="name mr-3"
        >
        <v-btn
          @click="add(addTaskElement)"
        >
          追加
        </v-btn>
      </div>

      <div class="element-header">
        <div class="left">
          <div class="item-name" />
        </div>
        <div class="right">
          <div class="item-date">
            登録日
          </div>
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
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, Ref } from 'vue';
import Task from '~/types/models/task';
import TaskElementModel from '~/types/models/taskElement';
import AddTaskElement from '~/types/models/addTaskElement';
import TaskElementList from '~/components/Task/TaskElementList.vue';

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
}>();

const addTaskElement: Ref<AddTaskElement> = ref({
  task_id                : props.task.id,
  parent_task_element_id : 0,
  name                   : '',
  content                : '',
  start_date             : null,
  due_date               : null,
});


function add(addTaskElement: AddTaskElement): void {
  emit('add', addTaskElement);
}

function edit(editedTaskElement: TaskElementModel): void {
  emit('edit', editedTaskElement);
}

function deleteItem(id: number): void {
  emit('deleteItem', id);
}

</script>

<style lang="scss" scoped>
.wrapper {
  min-width: 1200px;
  width: 100%;
  height: 100%;
  font-size: 14px;
  .header {
    height : 90px;
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
          width : 100px;
        }
      }
    }
  }
  .body {
    height: calc(100% - 90px);
    overflow: auto;
  }
}
</style>
