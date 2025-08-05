<template>
  <Dialog :header="store.formTitle" v-model:visible="store.showForm" @show="onDialogShow" @hide="onDialogHide" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
    <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-2">
        <label for="name1">{{ $t('letterArchive.number') }}</label>
        <InputText type="text" v-model="store.formData.nomor_surat" />
        <p class="text-red-500">{{ store.errors.nomor_surat }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="name1">{{ $t('letterArchive.destination') }}</label>
        <InputText type="text" v-model="store.formData.tujuan_surat" />
        <p class="text-red-500">{{ store.errors.asal_surat }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="name1">{{ $t('letterArchive.subject') }}</label>
        <InputText type="text" v-model="store.formData.perihal" />
        <p class="text-red-500">{{ store.errors.perihal }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="tgl_lahir">{{ t('letterArchive.date') }}</label>
        <DatePicker name="date" fluid date-format="dd/mm/yy" v-model="store.formData.tgl_surat" />
        <p class="text-red-500">{{ store.errors.tgl_surat }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="name1">{{ $t('letterArchive.info') }}</label>
        <InputText type="text" v-model="store.formData.keterangan" />
        <p class="text-red-500">{{ store.errors.keterangan }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <p v-if="store.formData.berkas !== ''">
          {{ $t('letterArchive.file') }}: <br />
          <a :href="filepath" target="_blank" class="text-blue-500"
            ><strong>{{ store.formData.berkas }}</strong></a
          >
        </p>
        <label for="name1">{{ $t('letterArchive.upload') }}</label>
        <FileUpload ref="fileUpload" name="upload[]" :file-limit="1" :show-upload-button="false" :show-cancel-button="false" auto @uploader="onUpload" accept=".pdf,application/pdf" :maxFileSize="4000000" customUpload />
      </div>
    </div>
    <template #footer>
      <Button :label="$t('common.buttons.save')" @click="save" />
    </template>
  </Dialog>
</template>
<script setup>
import { useOutLetterStore } from '@/stores/out-letter-store'
import { useToast } from 'primevue/usetoast'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import conf from '../../../admins.config'

const store = useOutLetterStore()
const toast = useToast()
const { t } = useI18n()
const fileUpload = ref()

const filepath = computed(() => `${conf.apiPublicPath}uploads/surat/masuk/${store.formData.berkas}`)

const onDialogHide = () => {
  if (store.formEvent === 'edit') store.resetForm()

  if (store.formData.berkas !== '' && !store.submitted) {
    store.removeUploadedFile()
  }

  if (store.submitted) store.submitted = false
}

const onDialogShow = () => {}

const onSave = (status, message) => {
  toast.removeAllGroups()
  if (status === 'error') {
    toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.incorrectForm'), life: 5000 })
  } else {
    toast.add({ severity: 'success', summary: t('common.success'), detail: message, life: 6000 })
  }
}

const onSaveError = (reason) => {
  toast.removeAllGroups()
  toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.networkError'), life: 5000 })
  console.error(reason)
}

const onUpload = (event) => {
  const file = event.files[0]
  const formData = new FormData()
  formData.append('surat', file)
  toast.add({ severity: 'info', summary: t('common.processing'), detail: t('common.uploading') })

  store.upload(formData, (status) => {
    toast.removeAllGroups()
    if (status === 'error') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: t('letterArchive.uploadError'), life: 5000 })
    } else if (status === 'success') {
      toast.add({ severity: 'success', summary: t('common.success'), detail: t('letterArchive.uploadSuccess'), life: 4000 })
      fileUpload.value.clear()
      fileUpload.value.uploadedFileCount = 0
    } else if (status === 'failed') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.networkError'), life: 5000 })
    }
  })
}

const save = () => {
  toast.add({ severity: 'info', summary: t('common.processing'), detail: t('common.saving') })
  store.save(onSave, onSaveError)
}
</script>
