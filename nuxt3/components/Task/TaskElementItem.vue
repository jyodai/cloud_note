<template>
  <div class="task-element">
    <div class="header">
      <div class="left">
        <input
          v-model="completionFlag"
          type="checkbox"
          class="mr-2"
          @change="finishEditing"
        >
        <span
          v-if="!isEdigingName"
          class="mr-2 item-name"
          :class="{'font-weight-bold' : taskElement.hierarchy === 1}"
          @dblclick="startEditingName"
        >
          {{ editedName }}
        </span>
        <input
          v-else
          ref="nameInput"
          v-model="editedName"
          class="mr-2 item-name"
          type="text"
          @blur="finishEditing"
          @keyup.enter="finishEditing"
        >
      </div>

      <div class="right">
        <div class="item-date">
          <icon-list
            :show-icons="['calendar']"
          />
          {{ taskElement.register_date }}
        </div>
        <div class="item-date">
          <icon-list
            :show-icons="['calendar']"
          />
          {{ taskElement.start_date }}
        </div>
        <div class="item-date">
          <icon-list
            :show-icons="['calendar']"
          />
          {{ taskElement.due_date }}
        </div>
        <div class="item-date">
          <icon-list
            :show-icons="['calendar']"
          />
          {{ taskElement.completion_date }}
        </div>

        <div class="icon-list">
          <icon-list
            :show-icons="['add', 'commentEdit', 'trash']"
            @add="add()"
            @comment-edit="startEditingContent()"
            @trash="deleteItem()"
          />
        </div>
      </div>
    </div>


    <div class="item-content pl-5">
      <template v-if="!isEdigingContent && editedContent">
        <div
          class="mr-2"
          @dblclick="startEditingContent()"
        >
          {{ editedContent }}
        </div>
      </template>

      <textarea
        v-if="isEdigingContent"
        ref="contentTextarea"
        v-model="editedContent"
        type="text"
        rows="4"
        @blur="finishEditing"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, Ref, nextTick } from 'vue';
import Task from '~/types/models/task';
import TaskElement from '~/types/models/taskElement';
import AddTaskElement from '~/types/models/addTaskElement';
import IconList from '~/commonComponents/IconList.vue';

const nuxtApp = useNuxtApp();

const props = defineProps({
  task : {
    type     : Object as () => Task,
    required : true,
  },
  taskElement : {
    type     : Object as () => TaskElement,
    required : true,
  },
});

const emit = defineEmits<{
  add: [addTaskElement: AddTaskElement],
  edit: [editedTaskElement: TaskElement],
  deleteItem: [id: number],
}>();

const addTaskElement: Ref<AddTaskElement> = ref({
  task_id                : props.task.id,
  parent_task_element_id : 0,
  name                   : 'task',
  content                : '',
  start_date             : null,
  due_date               : null,
});
const completionFlag:Ref<boolean>         = ref(props.taskElement.completion_flag);
const isEdigingName:Ref<boolean>          = ref(false);
const editedName:Ref<string>              = ref(props.taskElement.name);
const isEdigingContent:Ref<boolean>       = ref(false);
const editedContent:Ref<string>           = ref(props.taskElement.content);

const nameInput: Ref<HTMLInputElement | null>       = ref(null);
const contentTextarea: Ref<HTMLInputElement | null> = ref(null);

function add(): void {
  addTaskElement.value.parent_task_element_id = props.taskElement.id;
  emit('add', addTaskElement.value);
}

function deleteItem(): void {
  emit('deleteItem', props.taskElement.id);
}

function startEditingName(): void {
  isEdigingName.value = true;
  nextTick(() => {
    if (nameInput.value) {
      nameInput.value.focus();
    }
  });
}

function startEditingContent(): void {
  isEdigingContent.value = true;
  nextTick(() => {
    if (contentTextarea.value) {
      contentTextarea.value.focus();
    }
  });
}

function finishEditing(): void {
  isEdigingName.value    = false;
  isEdigingContent.value = false;

  const editedTaskElement = {
    ...props.taskElement,
    name            : editedName.value,
    content         : editedContent.value,
    completion_flag : completionFlag.value,
    completion_date : completionFlag.value ? nuxtApp.$util.date.getToday() : null,
  };
  emit('edit', editedTaskElement);
}
</script>

<style lang="scss" scoped>
.task-element {
  padding: 5px 0px;
  border-bottom: 2px solid #2E2E2E;
  .header {
    display: flex;
    justify-content: space-between;
    .left {
      display: flex;
      align-items: center;
      .item-name {
        width : 600px;
        word-wrap: break-word;
      }
    }
    .right {
      display: flex;
      align-items: right;
      .item-date {
        width : 120px;
      }
      .icon-list {
        width : 100px;
      }
    }
  }

  .item-content {
    width : 600px;
    white-space: pre-wrap;
  }
}
</style>

