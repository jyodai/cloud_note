<template>
  <draw-canvas
    :canvas-model="canvasModel"
    @update="update"
  />
</template>

<script setup lang="ts">
import { ref, Ref } from 'vue';
import DrawCanvas from '~/commonComponents/DrawCanvas.vue';
import Note from '~/types/models/note';
import Canvas from '~/types/models/canvas';
import { CustomAxiosRequestConfig } from '~/types/libraries/axios';


const nuxtApp = useNuxtApp();

const props = defineProps({
  note : {
    type     : Object as () => Note,
    required : true
  }
});

const canvasModel: Ref<Canvas> = ref(await get());

async function get(): Promise<Canvas> {
  const noteId   = props.note.id;
  const url      = nuxtApp.$config.public.apiUrl + `/notes/${noteId}/content`;
  const response = await nuxtApp.$axios.get(url);
  return response.data as Canvas;
}

async function update(canvasState: string) {
  const id                                     = canvasModel.value.id;
  const url                                    = nuxtApp.$config.public.apiUrl + `/canvas/${id}`;
  const params                                 = {
    content : canvasState,
  };
  const customConfig: CustomAxiosRequestConfig = {
    loadingScreen : false,
  };
  await nuxtApp.$axios.put(url, params, customConfig)
    .then(() => {
      canvasModel.value.content = canvasState;
    })
    .catch(() => {
      alert('保存に失敗しました');
    });
}
</script>
