<template>
  <div class="flex flex-col md:flex-row gap-8">
    <div class="md:w-2/6">
      <div class="flex flex-col gap-4">
        <div class="flex flex-wrap">
          <Button :label="$t('common.buttons.add')" @click="showForm" icon="pi pi-plus" class="mr-2 mb-2"></Button>
          <Button :label="$t('common.buttons.delete')" icon="pi pi-trash" severity="secondary" class="mr-2 mb-2"></Button>
        </div>
      </div>
    </div>
    <div class="md:w-1/6">
      <div class="flex flex-col gap-4">
        <div class="flex flex-wrap">
          <PagingRows :selected-row="25" />
        </div>
      </div>
    </div>
    <div class="md:w-3/6">
      <div class="flex flex-col gap-4">
        <div class="flex flex-wrap">
          <SearchBox :paging="paging" v-model="paging.state.search" class="w-full" :placeholder="$t('common.buttons.searchByName')" />
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { useEmployeeStore } from '@/stores/employee-store'
import { usePagingStore } from 'ss-paging-vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const store = useEmployeeStore()
const paging = usePagingStore()

const showForm = () => {
  store.formTitle = t('employee.add')
  store.showForm = true
}
</script>
