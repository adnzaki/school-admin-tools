<template>
  <Dialog :header="store.formTitle" v-model:visible="store.showForm" @show="onDialogShow" @hide="onDialogHide" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
    <div class="flex flex-col gap-4">
      <!-- Siswa (Autocomplete Select) -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('mutation.selectStudent') }}</label>
        <Select v-model="selectedStudent" @filter="onStudentFilter" @update:model-value="onStudentChange" :options="store.studentOptions" optionLabel="nama" optionValue="id" filter :placeholder="$t('mutation.findStudent')" class="w-full" />
        <p class="text-red-500">{{ store.errors.siswa_id }}</p>
      </div>

      <!-- (Select Kelas) -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('mutation.grade') }}</label>
        <Select v-model="selectedGrade" @change="onGradeChange" :options="mutationStore.classLevels[store.schoolLevel]" optionLabel="name" :placeholder="$t('mutation.selectGrade')" class="w-full" />
        <p class="text-red-500">{{ store.errors.kelas }}</p>
      </div>

      <!-- (Select Keperluan) -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('studentEnroll.letterPurpose') }}</label>
        <Select v-model="selectedPurpose" @change="onPurposeChange" :options="purposes" optionLabel="name" :placeholder="$t('studentEnroll.selectPurpose')" class="w-full" />
        <p class="text-red-500">{{ store.errors.keperluan }}</p>
      </div>

      <!-- Deskripsi untuk peringkat -->
      <div class="flex flex-col gap-2" v-if="store.formData.keperluan === 'peringkat'">
        <label>{{ $t('common.description') }}</label>
        <InputText type="text" v-model="store.formData.deskripsi" :placeholder="$t('studentEnroll.purpose.ranking')" />
        <p class="text-red-500">{{ store.errors.deskripsi }}</p>
      </div>

      <!-- Nomor Surat -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('letterArchive.number') }}</label>
        <InputText type="text" v-model="store.formData.nomor_surat" />
        <p class="text-red-500">{{ store.errors.nomor_surat }}</p>
      </div>

      <!-- Tanggal Surat -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('letterArchive.date') }}</label>
        <DatePicker name="tgl_pindah" fluid date-format="dd/mm/yy" v-model="store.formData.tgl_surat" />
        <p class="text-red-500">{{ store.errors.tgl_surat }}</p>
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
import { useStudentEnrollStore } from '@/stores/student-enroll-store'
import { useToast } from 'primevue/usetoast'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const store = useStudentEnrollStore()
const mutationStore = useMutationStore()
const toast = useToast()
const { t } = useI18n()
const selectedGrade = ref()
const selectedStudent = ref()
const selectedPurpose = ref({ name: t('studentEnroll.purpose.normal'), code: 'biasa' })

const purposes = ref([
  { name: t('studentEnroll.purpose.normal'), code: 'biasa' },
  { name: t('studentEnroll.purpose.pip'), code: 'pip' },
  { name: t('studentEnroll.purpose.achievement'), code: 'peringkat' }
])

const onPurposeChange = (event) => {
  store.formData.keperluan = event.value.code
}

const onGradeChange = (event) => {
  store.formData.kelas = event.value.code
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

const onDialogShow = () => {
  if (store.formEvent === 'edit') {
    store.studentOptions = []
    store.studentOptions.push({ nama: store.formData.siswa_nama, id: store.formData.siswa_id })
    selectedStudent.value = store.formData.siswa_id

    const grade = mutationStore.classLevels[store.schoolLevel].find((g) => g.code === parseInt(store.formData.kelas))
    selectedGrade.value = { name: grade.name, code: grade.code }

    const purpose = purposes.value.find((p) => p.code === store.formData.keperluan)
    selectedPurpose.value = { name: purpose.name, code: purpose.code }
  }
}

const onDialogHide = () => {
  if (store.formEvent === 'edit') {
    store.resetForm()
    store.studentOptions = []
    selectedGrade.value = null
    selectedPurpose.value = { name: t('studentEnroll.purpose.normal'), code: 'biasa' }

    if (store.formData.siswa_nama) store.formData.siswa_nama = ''
  }

  if (store.submitted) store.submitted = false
}
</script>
