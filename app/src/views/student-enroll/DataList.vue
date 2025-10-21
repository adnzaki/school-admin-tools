<script setup>
import { useStudentEnrollStore } from '@/stores/student-enroll-store'
import { useToast } from 'primevue'
import { usePagingStore } from 'ss-paging-vue'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import conf from '../../../admins.config'
const { t } = useI18n()

const store = useStudentEnrollStore()
const paging = usePagingStore()
const toast = useToast()

const tableColumns = ref([
  { key: 'no_surat', label: t('letterArchive.number'), sortable: true },
  { key: 'siswa_nama', label: t('student.name'), sortable: true },
  { key: 'siswa_nisn', label: t('student.nisn'), sortable: true },
  { key: 'kelas', label: t('mutation.grade'), sortable: true },
  { key: 'tgl_surat', label: t('letterArchive.date'), sortable: true }
])

const cm = ref()
const userId = ref(JSON.parse(localStorage.getItem('sakola_user')).id)

const contextMenu = ref([
  {
    label: t('common.buttons.edit'),
    icon: 'pi pi-pencil',
    command: () => {
      store.getDetail()
    }
  },
  {
    label: t('studentEnroll.print'),
    icon: 'pi pi-file-pdf',
    command: () => {
      window.open(`${conf.apiPublicPath}sekolah-disini/cetak-surat-keterangan-sekolah?id=${store.selectedSingle.id}&user=${userId.value}`, '_blank')
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
    v-model:contextMenuSelection="store.selectedSingle"
    v-model:selection="store.selected"
    selectionMode="multiple"
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
