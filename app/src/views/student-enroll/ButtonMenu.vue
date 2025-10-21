<template>
  <div class="flex flex-col gap-4 md:flex-col lg:flex-row">
    <div class="w-full lg:w-2/4 flex flex-wrap gap-2">
      <div class="flex flex-col gap-4">
        <div class="flex flex-wrap">
          <Button :label="$t('common.buttons.add')" @click="showForm" icon="pi pi-plus" class="mr-2 mb-2"></Button>
          <Button :label="$t('common.buttons.delete')" @click="showDeleteDialog" icon="pi pi-trash" severity="secondary" class="mr-2 mb-2"></Button>
        </div>
      </div>
    </div>
    <div class="w-full lg:w-2/4 flex flex-col md:flex-row gap-4">
      <div class="md:w-1/3">
        <div class="flex flex-col gap-4">
          <div class="flex flex-wrap">
            <PagingRows :selected-row="25" />
          </div>
        </div>
      </div>
      <div class="md:w-2/3">
        <div class="flex flex-col gap-4">
          <div class="flex flex-wrap">
            <SearchBox :paging="paging" v-model="paging.state.search" class="w-full" :placeholder="$t('studentEnroll.search')" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { useStudentEnrollStore } from '@/stores/student-enroll-store'
import { useToast } from 'primevue/usetoast'
import { usePagingStore } from 'ss-paging-vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const toast = useToast()

const store = useStudentEnrollStore()
const paging = usePagingStore()

const showForm = () => {
  store.formTitle = t('studentEnroll.add')
  store.formEvent = 'add'
  store.showForm = true
}

const showDeleteDialog = () => {
  store.showDeleteConfirmation(() => {
    toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.unableToDelete'), life: 5000 })
  })
}
</script>
