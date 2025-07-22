<script setup>
import { useEmployeeStore } from '@/stores/employee-store'
import { useToast } from 'primevue'
import { usePagingStore } from 'ss-paging-vue'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const store = useEmployeeStore()
const paging = usePagingStore()
const toast = useToast()

const tableColumns = ref([
  { key: 'nama', label: t('employee.name'), sortable: true },
  { key: 'nip', label: 'NIP', sortable: true },
  { key: 'jabatan', label: t('employee.position'), sortable: true },
  { key: 'telepon', label: t('employee.phone') }
])

store.getData(() => {
  toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.networkError'), life: 5000 })
})

const data = computed(() => paging.state.data)
</script>
<template>
  <DataTable v-model:selection="store.selected" selectionMode="multiple" dataKey="id" :value="data" scrollable scrollHeight="55vh" class="mt-6">
    <Column v-for="col of tableColumns" :key="col.key" :field="col.key" :header="col.label" :sortable="col.sortable"></Column>
  </DataTable>
  <Navigator v-model="store.current" />
</template>
