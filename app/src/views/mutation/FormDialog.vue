<template>
  <Dialog :header="store.formTitle" v-model:visible="store.showForm" @show="onDialogShow" @hide="onDialogHide" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
    <div class="flex flex-col gap-4">
      <!-- Siswa (Autocomplete Select) -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('mutation.selectStudent') }}</label>
        <Select v-model="selectedStudent" @filter="onStudentFilter" @update:model-value="onStudentChange" :options="store.studentOptions" optionLabel="nama" optionValue="id" filter :placeholder="$t('mutation.findStudent')" class="w-full" />
        <p class="text-red-500">{{ store.errors.siswa_id }}</p>
      </div>

      <!-- Nomor Surat -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('mutation.letterNumber') }}</label>
        <InputText type="text" v-model="store.formData.no_surat" />
        <p class="text-red-500">{{ store.errors.no_surat }}</p>
      </div>

      <!-- (Select Kelas) -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('mutation.grade') }}</label>
        <Select v-model="selectedGrade" @change="onGradeChange" :options="store.classLevels[store.schoolLevel]" optionLabel="name" :placeholder="$t('mutation.selectGrade')" class="w-full" />
        <p class="text-red-500">{{ store.errors.kelas }}</p>
      </div>

      <!-- sekolah Tujuan -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('mutation.destination') }}</label>
        <InputText type="text" v-model="store.formData.sd_tujuan" />
        <p class="text-red-500">{{ store.errors.sd_tujuan }}</p>
      </div>

      <!-- Kelurahan -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('student.subDistrict') }}</label>
        <InputText type="text" v-model="store.formData.kelurahan" />
        <p class="text-red-500">{{ store.errors.kelurahan }}</p>
      </div>

      <!-- Kecamatan -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('student.district') }}</label>
        <InputText type="text" v-model="store.formData.kecamatan" />
        <p class="text-red-500">{{ store.errors.kecamatan }}</p>
      </div>

      <!-- Kabupaten/Kota -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('student.city') }}</label>
        <InputText type="text" v-model="store.formData.kab_kota" />
        <p class="text-red-500">{{ store.errors.kab_kota }}</p>
      </div>

      <!-- Provinsi -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('student.province') }}</label>
        <InputText type="text" v-model="store.formData.provinsi" />
        <p class="text-red-500">{{ store.errors.provinsi }}</p>
      </div>

      <!-- Alasan -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('mutation.reason') }}</label>
        <InputText type="text" v-model="store.formData.alasan" />
        <p class="text-red-500">{{ store.errors.alasan }}</p>
      </div>

      <!-- Tanggal Pindah -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('mutation.moveDate') }}</label>
        <DatePicker name="tgl_pindah" fluid date-format="dd/mm/yy" v-model="store.formData.tgl_pindah" />
        <p class="text-red-500">{{ store.errors.tgl_pindah }}</p>
      </div>

      <!-- Pindah Rayon -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('mutation.rayon') }}</label>
        <ToggleSwitch v-model="checked" @update:model-value="onPindahRayonChange" />
        <p class="text-red-500">{{ store.errors.pindah_rayon }}</p>
      </div>

      <!-- Nomor Surat Rayon -->
      <div class="flex flex-col gap-2" v-if="parseInt(store.formData.pindah_rayon) === 1">
        <label>{{ $t('mutation.rayonNumber') }}</label>
        <InputText type="text" v-model="store.formData.no_surat_rayon" />
        <p class="text-red-500">{{ store.errors.no_surat_rayon }}</p>
      </div>
    </div>
    <template #footer>
      <Button :label="$t('common.buttons.save')" :disabled="store.disableButton" @click="save" />
    </template>
  </Dialog>
</template>
<script setup>
import { findStudent } from '@/composables/utils'
import { useMutationStore } from '@/stores/mutation-store'
import { useToast } from 'primevue/usetoast'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const store = useMutationStore()
const toast = useToast()
const { t } = useI18n()
const checked = ref(false)
const selectedGrade = ref()
const selectedStudent = ref()

const onGradeChange = (event) => {
  store.formData.kelas = event.value.code
}

const onStudentChange = (value) => {
  store.formData.siswa_id = value
}

const onStudentFilter = (event) => {
  if (event.value.length > 2) {
    findStudent(event.value, (results) => {
      store.studentOptions = results
    })
  }
}

const onPindahRayonChange = (v) => {
  store.formData.pindah_rayon = v ? 1 : 0
}

const onDialogHide = () => {
  if (store.formEvent === 'edit') {
    store.resetForm()
    store.studentOptions = []
    checked.value = false
    store.formData.pindah_rayon = 0
    selectedGrade.value = null

    if (store.formData.siswa_nama) store.formData.siswa_nama = ''
  }

  if (store.submitted) store.submitted = false
}

const onDialogShow = () => {
  if (store.formEvent === 'edit') {
    checked.value = parseInt(store.formData.pindah_rayon) === 1
    store.studentOptions = []
    store.studentOptions.push({ nama: store.formData.siswa_nama, id: store.formData.siswa_id })
    selectedStudent.value = store.formData.siswa_id

    const grade = store.classLevels[store.schoolLevel].find((g) => g.code === parseInt(store.formData.kelas))
    selectedGrade.value = { name: grade.name, code: grade.code }
  }
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
  store.disableButton = true
  toast.add({ severity: 'info', summary: t('common.processing'), detail: t('common.saving') })
  store.save(onSave, onSaveError)
}
</script>
