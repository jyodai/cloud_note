import { onMounted, onBeforeUnmount } from 'vue';

export function useKeydown(callback: (event: KeyboardEvent) => void) {
  onMounted(() => {
    document.addEventListener('keydown', callback);
  });

  onBeforeUnmount(() => {
    document.removeEventListener('keydown', callback);
  });
}
