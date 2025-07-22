<template>
  <SelectRow :paging="store" :label="$t('common.buttons.selectRow')" :dark="layoutConfig.darkTheme" custom-select-class="w-full" :row-label="$t('common.buttons.row')" :selected="selectedRow" />
</template>

<script setup>
import { useLayout } from '@/layout/composables/layout'
import { usePagingStore } from 'ss-paging-vue'
import { SelectRow } from 'ss-paging-vue/components'

const { layoutConfig } = useLayout()

const store = usePagingStore()
const props = defineProps({
  selectedRow: Number
})
</script>
