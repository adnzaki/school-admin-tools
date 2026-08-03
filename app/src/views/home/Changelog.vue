<script setup>
import { useSchoolStore } from '@/stores/school-store';
import markdownit from 'markdown-it';
import UpdateList from '@/composables/changelog/1.0.0-alpha.1.md?raw';
import Alpha2 from '@/composables/changelog/1.0.0-alpha.2.md?raw';
import Beta1 from '@/composables/changelog/1.0.0-beta.1.md?raw';

const md = markdownit({
  html: true,        // Mengizinkan tag HTML mentah di dalam file markdown
  linkify: true,     // Otomatis mengubah URL teks menjadi link klik
  typographer: true, // Mengaktifkan penggantian tanda kutip dan simbol lainnya
})
const updateListHtml = md.render(UpdateList)
const alpha2Html = md.render(Alpha2)
const beta1Html = md.render(Beta1)

const store = useSchoolStore()
</script>

<template>
  <Dialog class="px-3" :header="$t('common.app.changelog')" v-model:visible="store.showChangelog" :breakpoints="{ '1280px': '80vw', '960px': '90vw', '575px': '95vw' }" :style="{ width: '70vw', maxHeight: '70vh' }" :modal="true">
    <div class="flex flex-col gap-4">
      <div v-html="beta1Html"></div>
      <div v-html="alpha2Html"></div>
      <div v-html="updateListHtml"></div>
    </div>
  </Dialog>
</template>
