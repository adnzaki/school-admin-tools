<template>
  <div class="max-w-md mx-auto" v-if="store.graduatedStudents.length === 0">
    <p class="text-lg font-medium mb-4 text-center">Silakan impor data dengan menggunakan format berikut untuk meluluskan  siswa tingkat akhir:</p>
    <div class="flex items-center justify-center mb-4">
      <Button :label="$t('common.buttons.downloadFormat')" @click="download()" icon="pi pi-download" severity="secondary" class="mr-2 mb-2 text-center"></Button>
    </div>
    <FileUpload
      ref="fu"
      name="import[]"
      customUpload
      @uploader="onUpload"
      :auto="false"
      :multiple="false"
      accept=".xls, .xlsx, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
      :maxFileSize="1000000"
      mode="advanced"
      :pt="{ root: { class: 'border-0! bg-transparent!' }, header: { class: 'hidden!' }, content: { class: 'border-2! border-dashed! border-surface-200! dark:border-surface-700! rounded-xl! p-8!' } }"
    >
      <template #content="{ files, removeFileCallback, messages }">
        <div v-if="messages?.length" class="flex flex-col gap-2">
          <Message v-for="msg of messages" :key="msg" severity="error">{{ msg }}</Message>
        </div>
        <div v-if="files.length" class="flex flex-col gap-4">
          <div class="flex items-center justify-between">
            <span class="text-sm text-muted-color">{{ files.length }} file(s) selected</span>
            <div class="flex items-center gap-2">
              <Button size="small" @click="onUpload(files)" style="font-weight: bold;">{{ $t('common.buttons.upload') }}</Button>
              <!-- <Button variant="text" size="small" severity="danger" @click="onClear">{{ $t('common.buttons.clearAll') }}</Button> -->
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <div v-for="(file, index) of files" :key="file.name + file.size" class="flex items-center justify-between p-3 rounded-lg bg-surface-50 dark:bg-surface-800">
              <div class="flex items-center gap-3">
                <!-- <CloudUpload class="text-primary shrink-0" /> -->
                <div class="flex flex-col">
                  <span class="text-sm font-medium">{{ file.name }}</span>
                  <span class="text-xs text-muted-color">{{ formatSize(file.size) }}</span>
                </div>
              </div>
              <Button type="button" icon="pi pi-trash" variant="text" severity="secondary" size="small" rounded @click="removeFileCallback(index)"> </Button>
            </div>
          </div>
        </div>
      </template>
      <template #empty>
        <div class="flex flex-col items-center justify-center gap-3 py-8 cursor-pointer" @click="onChoose">
          <!-- <CloudUpload :size="48" class="text-muted-color" /> -->
          <Upload style="width: 50px; height: 50px;" />
          <div class="text-center">
            <p class="text-lg font-medium mt-0 mb-1">{{ $t('common.dropFile') }}</p>
            <p class="text-sm text-muted-color m-0">{{ $t('common.browseFile') }}</p>
          </div>
        </div>
      </template>
    </FileUpload>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useToast } from 'primevue'
import conf from '../../../admins.config'
import Upload from '@primevue/icons/upload'
import { useStudentStore } from '@/stores/student-store'

const store = useStudentStore()
const fu = ref()
const toast = useToast()
const { t } = useI18n()

const download = () => {
  const url = conf.apiPublicPath + 'template/TEMPLATE-IMPOR-TINGKATAKHIR.xlsx'
  window.open(url, '_blank')
}

const onChoose = () => {
  fu.value.choose()
}

const onUpload = (files) => {
  const formData = new FormData()
  formData.append('file', files[0])
  toast.add({ severity: 'info', summary: t('common.processing'), detail: t('common.importing') })

  store.importGraduation(formData, (status, message) => {
    toast.removeAllGroups()
    if (status === 'error') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: message, life: 5000 })
    } else if (status === 'success') {
      toast.add({ severity: 'success', summary: t('common.success'), detail: message, life: 4000 })
    } else if (status === 'failed') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.networkError'), life: 5000 })
    }

    onClear()
    fu.value.uploadedFileCount = 0
  })
}

const onClear = () => {
  fu.value.clear()
}

const formatSize = (bytes) => {
  if (bytes === 0) return '0 B'

  const k = 1024
  const sizes = ['B', 'KB', 'MB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))

  return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i]
}
</script>
