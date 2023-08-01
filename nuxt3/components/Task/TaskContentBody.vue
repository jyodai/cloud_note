<template>
  <task-element-wrapper
    :task="task"
    :task-element="taskElement"
    @add="add"
    @edit="edit"
    @delete-item="deleteItem"
  />
</template>

<script setup lang="ts">
import { ref, Ref } from 'vue';
import Note from '~/types/models/note';
import Task from '~/types/models/task';
import TaskElement from '~/types/models/taskElement';
import AddTaskElement from '~/types/models/addTaskElement';
import TaskElementWrapper from '~/components/Task/TaskElementWrapper.vue';

const nuxtApp = useNuxtApp();

const props = defineProps({
  note : {
    type     : Object as () => Note,
    required : true,
  },
});

const task:Ref<Task>                 = ref(await loadTask());
const taskElement:Ref<TaskElement[]> = ref(await loadTaskElement());

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
  const url      = nuxtApp.$config.public.apiUrl + `/tasks/${task.value.id}/tree`;
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

</script>
