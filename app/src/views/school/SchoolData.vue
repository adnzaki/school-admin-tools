<script setup>
import { useSchoolStore } from '@/stores/school-store'
import { useToast } from 'primevue/usetoast'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const toast = useToast()
const store = useSchoolStore()
const menu = ref(null)

store.getDetail()
const detail = computed(() => store.formData)

const sanitizeFields = (fields, formData) => {
  fields.forEach((field) => {
    if (formData[field]) {
      formData[field] = formData[field].replace(/\s+/g, '')
    }
  })
}

const showForm = () => {
  sanitizeFields(['nip_kepala_sekolah', 'nip_wakil_kepala_sekolah', 'nip_bendahara_bos', 'nip_bendahara_barang'], store.formData)
  store.showForm = true
}

const items = ref([{ label: t('common.buttons.edit'), icon: 'pi pi-fw pi-pencil', command: () => showForm() }])
</script>

<template>
  <div class="card">
    <div class="flex justify-between items-center mb-6">
      <div class="font-semibold text-xl">{{ store.schoolName }}</div>
      <div>
        <Button icon="pi pi-ellipsis-v" class="p-button-text p-button-plain p-button-rounded" @click="$refs.menu.toggle($event)"></Button>
        <Menu ref="menu" popup :model="items" class="!min-w-40"></Menu>
      </div>
    </div>
    <ul class="list-none p-0 m-0">
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
          <div class="mt-1 text-muted-color">{{ $t('school.kepala_sekolah') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mr-2 mb-1 md:mb-0">{{ detail.kepala_sekolah }}</span>
        </div>
        <div class="md:text-right">
          <div class="mt-1 text-muted-color">{{ $t('school.employeeId') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mb-1 md:mb-0">{{ detail.nip_kepala_sekolah }}</span>
        </div>
      </li>
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6" v-if="detail.wakil_kepala_sekolah !== ''">
        <div>
          <div class="mt-1 text-muted-color">{{ $t('school.wakil_kepala_sekolah') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mr-2 mb-1 md:mb-0">{{ detail.wakil_kepala_sekolah }}</span>
        </div>
        <div class="md:text-right">
          <div class="mt-1 text-muted-color">{{ $t('school.employeeId') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mb-1 md:mb-0">{{ detail.nip_wakil_kepala_sekolah }}</span>
        </div>
      </li>
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
          <div class="mt-1 text-muted-color">{{ $t('school.bendahara_bos') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mr-2 mb-1 md:mb-0">{{ detail.bendahara_bos }}</span>
        </div>
        <div class="md:text-right">
          <div class="mt-1 text-muted-color">{{ $t('school.employeeId') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mb-1 md:mb-0">{{ detail.nip_bendahara_bos }}</span>
        </div>
      </li>
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
          <div class="mt-1 text-muted-color">{{ $t('school.bendahara_barang') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mr-2 mb-1 md:mb-0">{{ detail.bendahara_barang }}</span>
        </div>
        <div class="md:text-right">
          <div class="mt-1 text-muted-color">{{ $t('school.employeeId') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mb-1 md:mb-0">{{ detail.nip_bendahara_barang }}</span>
        </div>
      </li>
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
          <div class="mt-1 text-muted-color">{{ $t('school.alamat') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mr-2 mb-1 md:mb-0">{{ detail.alamat }}</span>
        </div>
        <div class="md:text-right">
          <div class="mt-1 text-muted-color">{{ $t('school.kelurahan') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mb-1 md:mb-0">{{ detail.kelurahan }}</span>
        </div>
      </li>
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
          <div class="mt-1 text-muted-color">{{ $t('school.kecamatan') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mr-2 mb-1 md:mb-0">{{ detail.kecamatan }}</span>
        </div>
        <div class="md:text-right">
          <div class="mt-1 text-muted-color">{{ $t('school.kab_kota') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mb-1 md:mb-0">{{ detail.kab_kota }}</span>
        </div>
      </li>
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mb-1 md:mb-0">{{ $t('school.file_kop') }}:</span>
        </div>
      </li>
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 bg-white p-[10px] rounded-[10px]">
        <Image :src="detail.file_kop_path" alt="Image" style="width: 100%" />
      </li>
    </ul>
  </div>
</template>
