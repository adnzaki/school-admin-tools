<template>
  <Dialog :header="store.formTitle" v-model:visible="store.showForm" @show="onDialogShow" @hide="onDialogHide" :breakpoints="{ '960px': '75vw' }" :style="{ width: '40vw' }" :modal="true">
    <div class="flex flex-col gap-4">
      <!-- Pegawai (Autocomplete Select) -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.employee') }}</label>
        <Select v-model="selectedEmployee" @filter="onEmployeeFilter" @update:model-value="onEmployeeChange" :options="store.pegawaiOptions" optionLabel="nama" optionValue="id" filter :placeholder="$t('sppd.search')" class="w-full" />
        <p class="text-red-500">{{ store.errors.pegawai_id }}</p>
      </div>

      <!-- Nomor Surat -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.letterNumber') }}</label>
        <InputText type="text" v-model="store.formData.nomor_surat" />
        <p class="text-red-500">{{ store.errors.nomor_surat }}</p>
      </div>

      <!-- Perjalanan Dinas -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.workTrip') }}</label>
        <ToggleSwitch v-model="checked" @update:model-value="onSppdChange" />
        <p class="text-red-500">{{ store.errors.sppd }}</p>
      </div>

      <!-- Nomor Surat Perjalanan Dinas -->
      <div class="flex flex-col gap-2" v-if="parseInt(store.formData.sppd) === 1">
        <label>{{ $t('sppd.form.workTripNumber') }}</label>
        <InputText type="text" v-model="store.formData.no_sppd" />
        <p class="text-red-500">{{ store.errors.no_sppd }}</p>
      </div>

      <!-- Tanggal Surat -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.letterDate') }}</label>
        <DatePicker fluid date-format="dd/mm/yy" v-model="store.formData.tgl_surat" />
        <p class="text-red-500">{{ store.errors.tgl_surat }}</p>
      </div>

      <!-- Tingkat Biaya -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.costLevel') }}</label>
        <InputText type="text" v-model="store.formData.tingkat_biaya" />
        <p class="text-red-500">{{ store.errors.tingkat_biaya }}</p>
      </div>

      <!-- Maksud Perjalanan Dinas -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.purpose') }}</label>
        <Textarea v-model="store.formData.tujuan" rows="3" />
        <p class="text-red-500">{{ store.errors.tujuan }}</p>
      </div>

      <!-- Transportasi -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.transport') }}</label>
        <Select v-model="selectedTransport" @update:model-value="onTransportChange" :options="transportOptions" optionLabel="label" optionValue="value" :placeholder="$t('sppd.form.transport')" class="w-full" />
        <p class="text-red-500">{{ store.errors.transportasi }}</p>
      </div>

      <!-- Tempat Berangkat (Lokasi) -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.location') }}</label>
        <InputText type="text" v-model="store.formData.lokasi" />
        <p class="text-red-500">{{ store.errors.lokasi }}</p>
      </div>

      <!-- Durasi -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.duration') }}</label>
        <InputNumber v-model="store.formData.durasi" :min="1" :max="100" />
        <p class="text-red-500">{{ store.errors.durasi }}</p>
      </div>

      <!-- Tanggal Berangkat -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.departureDate') }}</label>
        <DatePicker fluid date-format="dd/mm/yy" v-model="store.formData.tgl_berangkat" />
        <p class="text-red-500">{{ store.errors.tgl_berangkat }}</p>
      </div>

      <!-- Tanggal Kembali -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.returnDate') }}</label>
        <DatePicker fluid date-format="dd/mm/yy" v-model="store.formData.tgl_kembali" disabled />
        <p class="text-red-500">{{ store.errors.tgl_kembali }}</p>
      </div>

      <!-- Kepala SKPD -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.headOfSkpd') }}</label>
        <InputText type="text" v-model="store.formData.kepala_skpd" />
        <p class="text-red-500">{{ store.errors.kepala_skpd }}</p>
      </div>

      <!-- NIP Kepala SKPD -->
      <div class="flex flex-col gap-2">
        <label>{{ $t('sppd.form.nipHeadOfSkpd') }}</label>
        <InputText type="text" v-model="store.formData.nip_kepala_skpd" />
        <p class="text-red-500">{{ store.errors.nip_kepala_skpd }}</p>
      </div>
    </div>
    <template #footer>
      <Button :label="$t('common.buttons.save')" :disabled="store.disableButton" @click="save" />
    </template>
  </Dialog>
</template>
<script setup>
import { findEmployee } from '@/composables/utils'
import { useSppdStore } from '@/stores/sppd-store'
import { useToast } from 'primevue/usetoast'
import { ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'

const store = useSppdStore()
const toast = useToast()
const { t } = useI18n()
const checked = ref(false)
const selectedEmployee = ref()
const selectedTransport = ref()

const transportOptions = ref([
  { label: t('sppd.transportType.pribadi'), value: 'pribadi' },
  { label: t('sppd.transportType.umum'), value: 'umum' },
  { label: t('sppd.transportType.kantor'), value: 'kantor' },
  { label: t('sppd.transportType.lainnya'), value: 'lainnya' }
])

watch([() => store.formData.tgl_berangkat, () => store.formData.durasi], ([newDate, newDuration]) => {
  if (newDate && newDuration) {
    const startDate = new Date(newDate)
    const endDate = new Date(startDate)
    endDate.setDate(startDate.getDate() + parseInt(newDuration) - 1)
    store.formData.tgl_kembali = endDate
  }
})

const onSppdChange = (value) => {
  store.formData.sppd = value ? 1 : 0
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

const onEmployeeChange = (value) => {
  store.formData.pegawai_id = value
}

const onEmployeeFilter = (event) => {
  if (event.value.length > 2) {
    findEmployee(event.value, (results) => {
      store.pegawaiOptions = results
    })
  }
}

const onTransportChange = (value) => {
  store.formData.transportasi = value
}

const onDialogShow = () => {
  if (store.formEvent === 'edit') {
    store.pegawaiOptions = []
    checked.value = parseInt(store.formData.sppd) === 1
    store.pegawaiOptions.push({ nama: store.formData.pegawai_nama, id: store.formData.pegawai_id })
    selectedEmployee.value = store.formData.pegawai_id
    selectedTransport.value = store.formData.transportasi
  }

  if (store.formEvent === 'add') {
    selectedTransport.value = 'pribadi'
    store.formData.transportasi = selectedTransport.value
  }
}

const onDialogHide = () => {
  if (store.formEvent === 'edit') {
    store.resetForm()
    store.pegawaiOptions = []
    selectedEmployee.value = null
    selectedTransport.value = 'pribadi'
  }

  if (store.submitted) store.submitted = false
}
</script>
