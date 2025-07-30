<script setup>
import { useStudentStore } from '@/stores/student-store'
import { useToast } from 'primevue/usetoast'
import { useI18n } from 'vue-i18n'
import ButtonMenu from './ButtonMenu.vue'
import DataList from './DataList.vue'
import FormDialog from './FormDialog.vue'
// import ImportDialog from './ImportDialog.vue'

const { t } = useI18n()

const toast = useToast()
const store = useStudentStore()

const confirmDelete = () => {
  toast.add({ severity: 'info', summary: t('common.processing'), detail: t('common.deleting') })
  store.delete((status, message) => {
    toast.removeAllGroups()
    if (status === 'error') {
      toast.add({ severity: 'error', summary: 'Error', detail: message, life: 5000 })
    } else if (status === 'success') {
      toast.add({ severity: 'success', summary: 'Success', detail: message, life: 5000 })
    } else if (status === 'failed') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: message, life: 5000 })
    }
  })
}
</script>

<template>
  <div class="card">
    <div class="font-semibold text-xl mb-4">{{ $t('menu.student') }}</div>
    <ButtonMenu />
    <DataList />
    <FormDialog />
    <!-- <ImportDialog /> -->
    <!-- <DeleteConfirmation v-model:display="store.showDeleteDialog" @delete="confirmDelete" /> -->
  </div>
</template>
