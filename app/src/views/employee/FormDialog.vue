<template>
  <Dialog :header="store.formTitle" v-model:visible="store.showForm" @show="onDialogShow" @hide="onDialogHide" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
    <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-2">
        <label for="name1">{{ $t('employee.name') }}</label>
        <InputText type="text" v-model="store.formData.nama" />
        <p class="text-red-500">{{ store.errors.nama }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="name1">NIP</label>
        <InputText type="text" v-model="store.formData.nip" minlength="18" maxlength="18" />
        <p class="text-red-500">{{ store.errors.nip }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="name1">{{ $t('employee.position') }}</label>
        <InputText type="text" v-model="store.formData.jabatan" />
        <p class="text-red-500">{{ store.errors.jabatan }}</p>
      </div>
      <div class="flex flex-wrap gap-2 w-full">
        <label for="state">{{ $t('employee.type') }}</label>
        <Select id="state" @update:model-value="onTypeSelected" v-model="employeeType" :options="employeeTypes" optionLabel="name" placeholder="" class="w-full"></Select>
        <p class="text-red-500">{{ store.errors.jenis_pegawai }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="name1">Email</label>
        <InputText type="text" v-model="store.formData.email" />
        <p class="text-red-500">{{ store.errors.email }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="name1">{{ $t('employee.phone') }}</label>
        <InputText type="text" v-model="store.formData.telepon" minlength="11" maxlength="15" />
        <p class="text-red-500">{{ store.errors.telepon }}</p>
      </div>
    </div>
    <template #footer>
      <Button :label="$t('common.buttons.save')" :disabled="store.disableButton" @click="save" />
    </template>
  </Dialog>
</template>

<script setup>
import { useEmployeeStore } from '@/stores/employee-store'
import { useToast } from 'primevue/usetoast'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const store = useEmployeeStore()
const toast = useToast()
const { t } = useI18n()

const employeeTypes = ref([
  { name: 'PNS', code: 'PNS' },
  { name: 'PPPK', code: 'PPPK' },
  { name: 'Honorer', code: 'Honorer' }
])

const employeeType = ref(null)

const onDialogHide = () => {
  if (store.formEvent === 'edit') store.resetForm()
  employeeType.value = null
}

const onDialogShow = () => {
  if (store.formEvent === 'edit') {
    employeeType.value = { name: store.formData.jenis_pegawai, code: store.formData.jenis_pegawai }
  }
}

const onTypeSelected = (value) => {
  store.formData.jenis_pegawai = value.code
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
