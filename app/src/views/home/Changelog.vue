<script setup>
import { useSchoolStore } from '@/stores/school-store';
import markdownit from 'markdown-it';
import UpdateList from '@/composables/changelog/1.0.0-alpha.1.md';
import Alpha2 from '@/composables/changelog/1.0.0-alpha.2.md';

const md = markdownit()


const store = useSchoolStore()
</script>

<template>
  <Dialog class="px-3" :header="$t('common.app.changelog')" v-model:visible="store.showChangelog" :breakpoints="{ '1280px': '80vw', '960px': '90vw', '575px': '95vw' }" :style="{ width: '70vw', maxHeight: '70vh' }" :modal="true">
    <div class="flex flex-col gap-4">
      <Alpha2 />
      <UpdateList />
    </div>
  </Dialog>
</template>
