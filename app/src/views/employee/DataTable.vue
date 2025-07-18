<script setup>
import { useEmployeeStore } from '@/stores/employee-store'
import { useToast } from 'primevue'
import { usePagingStore } from 'ss-paging-vue'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const store = useEmployeeStore()
const paging = usePagingStore()
const toast = useToast()

const tableColumns = ref([
  { key: 'nama', label: 'Nama', sortable: true },
  { key: 'nip', label: 'NIP', sortable: true },
  { key: 'jabatan', label: 'Jabatan', sortable: true }
])

store.getData(() => {
  toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.networkError'), life: 5000 })
})
</script>
<template>
  <!-- <DataTable :value="data" scrollable scrollHeight="400px" class="mt-6">
    <Column v-for="col of tableColumns" :key="col.key" :field="col.key" :header="col.label" :sortable="col.sortable"></Column>
  </DataTable> -->
</template>
