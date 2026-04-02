<script setup>
import { useSchoolStore } from '@/stores/school-store';
import markdownit from 'markdown-it';
import UpdateList from '@/composables/changelog/1.0.0-alpha.1.md';

const md = markdownit()


const store = useSchoolStore()
</script>

<template>
  <Dialog :header="$t('common.app.changelog')" v-model:visible="store.showChangelog" :breakpoints="{ '960px': '75vw' }" :style="{ width: '50vw' }" :modal="true">
    <div class="flex flex-col gap-4">
      <UpdateList />
    </div>
  </Dialog>
</template>
