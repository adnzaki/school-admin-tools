<script setup>
import { useMutationStore } from '@/stores/mutation-store'
import { useToast } from 'primevue'
import { usePagingStore } from 'ss-paging-vue'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const store = useMutationStore()
const paging = usePagingStore()
const toast = useToast()

const tableColumns = ref([
  { key: 'no_surat', label: t('letterArchive.number') },
  { key: 'siswa_nama', label: t('student.name'), sortable: true },
  { key: 'kelas', label: t('common.grade') },
  { key: 'sd_tujuan', label: t('mutation.destination') },
  { key: 'tgl_pindah', label: t('mutation.moveDate'), sortable: true }
])

const cm = ref()

const contextMenu = ref([
  {
    label: t('common.buttons.edit'),
    icon: 'pi pi-pencil',
    command: () => {
      store.getDetail()
    }
  },
  {
    label: t('common.buttons.delete'),
    icon: 'pi pi-trash',
    command: () => {
      // clear first before pushing selected data
      store.selected = []

      // push selected data
      store.selected.push(store.selectedSingle)
      store.showDeleteConfirmation(() => {
        toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.unableToDelete'), life: 5000 })
      })
    }
  }
])

const onContextMenuClick = (event) => {
  cm.value.show(event.originalEvent)
}

store.getData(() => {
  toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.networkError'), life: 5000 })
})

const data = computed(() => paging.state.data)
</script>
<template>
  <DataTable
    contextMenu
    @rowContextmenu="onContextMenuClick"
    selectionMode="single"
    v-model:contextMenuSelection="store.selected"
    v-model:selection="store.selected"
    metaKeySelection
    dataKey="id"
    :value="data"
    scrollable
    scrollHeight="55vh"
    class="mt-6"
  >
    <Column v-for="col of tableColumns" :key="col.key" :field="col.key" :header="col.label" :sortable="col.sortable"></Column>
  </DataTable>
  <ContextMenu ref="cm" :model="contextMenu" @hide="store.selectedSingle = null" />
  <Navigator v-model="store.current" />
</template>
