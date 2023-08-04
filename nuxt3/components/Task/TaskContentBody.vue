<template>
  <task-element-wrapper
    :task="task"
    :task-element="taskElement"
    @add="add"
    @edit="edit"
    @delete-item="deleteItem"
    @change-sort="changeSort"
    @toggle-tree="toggleTree"
  />
</template>

<script setup lang="ts">
import { ref, Ref, watch } from 'vue';
import Note from '~/types/models/note';
import Task from '~/types/models/task';
import TaskElement from '~/types/models/taskElement';
import AddTaskElement from '~/types/models/addTaskElement';
import TaskElementWrapper from '~/components/Task/TaskElementWrapper.vue';
import { TaskMovedInfo } from '~/types/libraries/draggable';

const nuxtApp = useNuxtApp();

const props = defineProps({
  note : {
    type     : Object as () => Note,
    required : true,
  },
});

const treePath:Ref<string>           = ref('/unfinished');
const task:Ref<Task>                 = ref(await loadTask());
const taskElement:Ref<TaskElement[]> = ref(await loadTaskElement());

watch(
  () => props.note,
  async (newNote: Note, oldNote: Note) => {
    if (newNote !== oldNote) {
      await reload();
    }
  }
);

async function reload(): Promise<void> {
  task.value        = await loadTask();
  taskElement.value = await loadTaskElement();
}

async function loadTask(): Promise<Task> {
  const url      = nuxtApp.$config.public.apiUrl + `/notes/${props.note.id}/content`;
  const response = await nuxtApp.$axios.get(url);
  return response.data as Task;
}

async function loadTaskElement(): Promise<TaskElement[]> {
  const url      = nuxtApp.$config.public.apiUrl + `/tasks/${task.value.id}/tree${treePath.value}`;
  const response = await nuxtApp.$axios.get(url);
  return response.data as TaskElement[];
}

async function add(params: AddTaskElement): Promise<void> {
  const url = nuxtApp.$config.public.apiUrl + `/tasks/elements`;
  await nuxtApp.$axios.post(url, params);
  reload();
}

async function edit(params: TaskElement): Promise<void> {
  const url = nuxtApp.$config.public.apiUrl + `/tasks/elements/${params.id}`;
  await nuxtApp.$axios.put(url, params);
  reload();
}

async function deleteItem(taskElementId: number): Promise<void> {
  if (!confirm('削除しますか？')) {
    return;
  }
  const url = nuxtApp.$config.public.apiUrl + `/tasks/elements/${taskElementId}`;
  await nuxtApp.$axios.delete(url);
  reload();
}

async function changeSort(movedInfo: TaskMovedInfo): Promise<void> {
  const params = {
    'target_task_element_id' : movedInfo.targetTaskElement.id,
    'type'                   : movedInfo.type,
  };
  const url    = nuxtApp.$config.public.apiUrl + `/tasks/elements/${movedInfo.movement.moved.element.id}/move`;
  await nuxtApp.$axios.put(url, params);
  reload();
}

async function toggleTree(status: number): Promise<void> {
  switch (status) {
  case nuxtApp.$const.TASK_TREE_STATUS_ALL:
    treePath.value = '';
    break;
  case nuxtApp.$const.TASK_TREE_STATUS_FINISHED:
    treePath.value = '/finished';
    break;
  case nuxtApp.$const.TASK_TREE_STATUS_UNFINISHED:
    treePath.value = '/unfinished';
    break;
  }
  console.log(treePath.value);
  await reload();
}

</script>
