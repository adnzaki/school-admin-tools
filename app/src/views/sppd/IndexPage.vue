<script setup>
import DeleteConfirmation from '@/components/DeleteConfirmation.vue'
import { useSppdStore } from '@/stores/sppd-store'
import { useToast } from 'primevue/usetoast'
import { useI18n } from 'vue-i18n'
import ButtonMenu from './ButtonMenu.vue'
import DataList from './DataList.vue'
import FormDialog from './FormDialog.vue'

const { t } = useI18n()

const toast = useToast()
const store = useSppdStore()

const confirmDelete = () => {
  toast.add({ severity: 'info', summary: t('common.processing'), detail: t('common.deleting') })
  store.delete((status, message) => {
    toast.removeAllGroups()
    if (status === 'error') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: message, life: 5000 })
    } else if (status === 'success') {
      toast.add({ severity: 'success', summary: t('common.success'), detail: message, life: 5000 })
    } else if (status === 'failed') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: message, life: 5000 })
    }
  })
}
</script>

<template>
  <div class="card">
    <div class="font-semibold text-xl mb-4">{{ $t('menu.taskLetter') }}</div>
    <ButtonMenu />
    <DataList />
    <FormDialog />
    <DeleteConfirmation v-model:display="store.showDeleteDialog" @delete="confirmDelete" />
  </div>
</template>
