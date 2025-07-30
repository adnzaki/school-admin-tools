<template>
  <Dialog :header="store.formTitle" v-model:visible="store.showForm" @hide="onDialogHide" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
    <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-2">
        <label for="nama">{{ t('student.name') }}</label>
        <InputText type="text" v-model="store.formData.nama" />
        <p class="text-red-500">{{ store.errors.nama }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="tempat_lahir">{{ t('student.placeOfBirth') }}</label>
        <InputText type="text" v-model="store.formData.tempat_lahir" />
        <p class="text-red-500">{{ store.errors.tempat_lahir }}</p>
      </div>

      <!-- Date Of Birth -->
      <div class="flex flex-col gap-2">
        <label for="tgl_lahir">{{ t('student.dateOfBirth') }}</label>
        <DatePicker name="date" fluid date-format="dd/mm/yy" v-model="store.formData.tgl_lahir" />
        <p class="text-red-500">{{ store.errors.tgl_lahir }}</p>
      </div>
      <!-- #END Date Of Birth -->

      <div class="flex flex-col gap-2">
        <label for="no_induk">{{ t('student.studentId') }}</label>
        <InputText type="text" v-model="store.formData.no_induk" minlength="9" maxlength="9" />
        <p class="text-red-500">{{ store.errors.no_induk }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="nisn">{{ t('student.nisn') }}</label>
        <InputText type="text" v-model="store.formData.nisn" minlength="10" maxlength="10" />
        <p class="text-red-500">{{ store.errors.nisn }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="jenis_kelamin">{{ t('student.gender') }}</label>
        <div class="flex items-center gap-2">
          <RadioButton v-model="store.formData.jenis_kelamin" inputId="gender1" name="gender" value="L" />
          <label for="ingredient1">{{ t('student.boy') }}</label>
        </div>
        <div class="flex items-center gap-2">
          <RadioButton v-model="store.formData.jenis_kelamin" inputId="gender2" name="gender" value="P" />
          <label for="ingredient2">{{ t('student.girl') }}</label>
        </div>
        <p class="text-red-500">{{ store.errors.jenis_kelamin }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="nama_ayah">{{ t('student.fatherName') }}</label>
        <InputText type="text" v-model="store.formData.nama_ayah" />
        <p class="text-red-500">{{ store.errors.nama_ayah }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="pekerjaan_ayah">{{ t('student.fatherOccupation') }}</label>
        <InputText type="text" v-model="store.formData.pekerjaan_ayah" />
        <p class="text-red-500">{{ store.errors.pekerjaan_ayah }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="nama_ibu">{{ t('student.motherName') }}</label>
        <InputText type="text" v-model="store.formData.nama_ibu" />
        <p class="text-red-500">{{ store.errors.nama_ibu }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="pekerjaan_ibu">{{ t('student.motherOccupation') }}</label>
        <InputText type="text" v-model="store.formData.pekerjaan_ibu" />
        <p class="text-red-500">{{ store.errors.pekerjaan_ibu }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="alamat">{{ t('student.address') }}</label>
        <InputText type="text" v-model="store.formData.alamat" />
        <p class="text-red-500">{{ store.errors.alamat }}</p>
      </div>
      <div class="flex flex-row gap-4">
        <div class="flex-1 flex flex-col gap-2">
          <label for="rt">{{ t('student.rt') }}</label>
          <InputText type="text" v-model="store.formData.rt" minlength="1" maxlength="3" />
          <p class="text-red-500">{{ store.errors.rt }}</p>
        </div>
        <div class="flex-1 flex flex-col gap-2">
          <label for="rw">{{ t('student.rw') }}</label>
          <InputText type="text" v-model="store.formData.rw" minlength="1" maxlength="3" />
          <p class="text-red-500">{{ store.errors.rw }}</p>
        </div>
      </div>
      <div class="flex flex-col gap-2">
        <label for="kelurahan">{{ t('student.subDistrict') }}</label>
        <InputText type="text" v-model="store.formData.kelurahan" />
        <p class="text-red-500">{{ store.errors.kelurahan }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="kecamatan">{{ t('student.district') }}</label>
        <InputText type="text" v-model="store.formData.kecamatan" />
        <p class="text-red-500">{{ store.errors.kecamatan }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="kab_kota">{{ t('student.city') }}</label>
        <InputText type="text" v-model="store.formData.kab_kota" />
        <p class="text-red-500">{{ store.errors.kab_kota }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="provinsi">{{ t('student.province') }}</label>
        <InputText type="text" v-model="store.formData.provinsi" />
        <p class="text-red-500">{{ store.errors.provinsi }}</p>
      </div>
    </div>
    <template #footer>
      <Button :label="$t('common.buttons.save')" @click="save" />
    </template>
  </Dialog>
</template>

<script setup>
import { useStudentStore } from '@/stores/student-store'
import { useToast } from 'primevue/usetoast'
import { useI18n } from 'vue-i18n'

const store = useStudentStore()
const toast = useToast()
const { t } = useI18n()

const onDialogHide = () => {
  if (store.formEvent === 'edit') store.resetForm()
}

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

const save = () => {
  toast.add({ severity: 'info', summary: t('common.processing'), detail: t('common.saving') })
  store.save(onSave, onSaveError)
}
</script>
