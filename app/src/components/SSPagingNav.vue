<template>
  <div class="flex flex-col md:flex-row gap-8 mt-4">
    <div class="md:w-1/2">
      <p class="mt-5">{{ paging.rowRange() }}</p>
    </div>
    <div class="md:w-1/2 md:flex md:justify-end">
      <Button icon="pi pi-refresh" text rounded aria-label="Reload" @click="paging.reloadData()" style="margin-top: 8px" />
      <Navigator :paging="paging" :model-value="modelValue" use-input :dark="layoutConfig.darkTheme" />
    </div>
  </div>
</template>

<script setup>
import { useLayout } from '@/layout/composables/layout'
import { usePagingStore } from 'ss-paging-vue'
import { Navigator } from 'ss-paging-vue/components'

const { layoutConfig } = useLayout()

const paging = usePagingStore()
const props = defineProps({
  modelValue: Number
})
</script>
