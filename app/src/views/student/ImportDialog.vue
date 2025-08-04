<template>
  <Dialog :header="$t('student.import')" v-model:visible="store.showImportDialog" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
    <p>{{ $t('student.importNote') }}</p>
    <Button :label="$t('common.buttons.download')" @click="download" icon="pi pi-download" severity="primary" outlined class="mr-2 mb-2"></Button>
    <h5>{{ $t('common.buttons.upload') }}</h5>
    <FileUpload name="import[]" :file-limit="1" @uploader="onUpload" accept=".xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" :maxFileSize="1000000" customUpload />
  </Dialog>
</template>

<script setup>
import { useStudentStore } from '@/stores/student-store'
import { useToast } from 'primevue'
import { useI18n } from 'vue-i18n'
import conf from '../../../admins.config'

const store = useStudentStore()
const toast = useToast()
const { t } = useI18n()

const download = () => {
  const url = conf.apiPublicPath + 'template/TEMPLATE-IMPOR-SISWA.xlsx'
  window.open(url, '_blank')
}

const onUpload = (event) => {
  const file = event.files[0]
  const formData = new FormData()
  formData.append('file', file)
  toast.add({ severity: 'info', summary: t('common.processing'), detail: t('common.importing') })

  store.import(formData, (status, message) => {
    toast.removeAllGroups()
    if (status === 'error') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: message, life: 5000 })
    } else if (status === 'success') {
      toast.add({ severity: 'success', summary: t('common.success'), detail: message, life: 4000 })
    } else if (status === 'failed') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.networkError'), life: 5000 })
    }
  })
}
</script>
