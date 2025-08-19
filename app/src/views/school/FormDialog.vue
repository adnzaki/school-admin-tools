<template>
  <Dialog :header="store.formTitle" v-model:visible="store.showForm" @show="onDialogShow" @hide="onDialogHide" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
    <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-2">
        <label>{{ $t('school.kepala_sekolah') }}</label>
        <InputText type="text" v-model="store.formData.kepala_sekolah" />
        <p class="text-red-500">{{ store.errors.kepala_sekolah }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.nip_kepala_sekolah') }}</label>
        <InputText type="text" v-model="store.formData.nip_kepala_sekolah" minlength="18" maxlength="18" />
        <p class="text-red-500">{{ store.errors.nip_kepala_sekolah }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.wakil_kepala_sekolah') }}</label>
        <InputText type="text" v-model="store.formData.wakil_kepala_sekolah" />
        <p class="text-red-500">{{ store.errors.wakil_kepala_sekolah }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.nip_wakil_kepala_sekolah') }}</label>
        <InputText type="text" v-model="store.formData.nip_wakil_kepala_sekolah" minlength="18" maxlength="18" />
        <p class="text-red-500">{{ store.errors.nip_wakil_kepala_sekolah }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.bendahara_bos') }}</label>
        <InputText type="text" v-model="store.formData.bendahara_bos" />
        <p class="text-red-500">{{ store.errors.bendahara_bos }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.nip_bendahara_bos') }}</label>
        <InputText type="text" v-model="store.formData.nip_bendahara_bos" minlength="18" maxlength="18" />
        <p class="text-red-500">{{ store.errors.nip_bendahara_bos }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.bendahara_barang') }}</label>
        <InputText type="text" v-model="store.formData.bendahara_barang" />
        <p class="text-red-500">{{ store.errors.bendahara_barang }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.nip_bendahara_barang') }}</label>
        <InputText type="text" v-model="store.formData.nip_bendahara_barang" minlength="18" maxlength="18" />
        <p class="text-red-500">{{ store.errors.nip_bendahara_barang }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.alamat') }}</label>
        <Textarea rows="2" v-model="store.formData.alamat" />
        <p class="text-red-500">{{ store.errors.alamat }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.kelurahan') }}</label>
        <InputText type="text" v-model="store.formData.kelurahan" />
        <p class="text-red-500">{{ store.errors.kelurahan }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.kecamatan') }}</label>
        <InputText type="text" v-model="store.formData.kecamatan" />
        <p class="text-red-500">{{ store.errors.kecamatan }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.kab_kota') }}</label>
        <InputText type="text" v-model="store.formData.kab_kota" />
        <p class="text-red-500">{{ store.errors.kab_kota }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.provinsi') }}</label>
        <InputText type="text" v-model="store.formData.provinsi" />
        <p class="text-red-500">{{ store.errors.provinsi }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <label>{{ $t('school.file_kop') }}</label>
        <FileUpload ref="fileUpload" name="upload[]" :file-limit="1" :show-upload-button="false" :show-cancel-button="false" auto @uploader="onUpload" accept=".jpg,.png,.jpeg" :maxFileSize="2048 * 1024" customUpload />
        <label
          ><i>{{ $t('school.recommendation') }}</i></label
        >
        <div class="bg-white p-[10px] rounded-[10px]">
          <Image :src="filepath" alt="Kop" style="width: 100%" />
        </div>
      </div>
    </div>
    <template #footer>
      <Button :label="$t('common.buttons.save')" :disabled="store.disableButton" @click="save" />
    </template>
  </Dialog>
</template>
<script setup>
import { useSchoolStore } from '@/stores/school-store'
import { useToast } from 'primevue/usetoast'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import conf from '../../../admins.config'

const store = useSchoolStore()
const toast = useToast()
const { t } = useI18n()
const fileUpload = ref()

const filepath = computed(() => `${conf.apiPublicPath}uploads/kop/${store.formData.file_kop}`)

const onDialogHide = () => {
  if (store.hasNewUpload && !store.submitted) {
    store.removeUploadedFile()
  }

  if (store.submitted) store.submitted = false
  if (store.hasNewUpload) store.hasNewUpload = false
  store.getDetail()
  store.errors = {}
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
  store.disableButton = true
  const file = event.files[0]
  const formData = new FormData()
  formData.append('kop', file)
  toast.add({ severity: 'info', summary: t('common.processing'), detail: t('common.uploading') })

  store.upload(formData, (status) => {
    toast.removeAllGroups()
    if (status === 'error') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: t('school.uploadError'), life: 5000 })
    } else if (status === 'success') {
      toast.add({ severity: 'success', summary: t('common.success'), detail: t('school.uploadSuccess'), life: 4000 })
      fileUpload.value.clear()
      fileUpload.value.uploadedFileCount = 0
    } else if (status === 'failed') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.networkError'), life: 5000 })
    }

    store.disableButton = false
  })
}

const save = () => {
  store.disableButton = true
  toast.add({ severity: 'info', summary: t('common.processing'), detail: t('common.saving') })
  store.save(onSave, onSaveError)
}
</script>
