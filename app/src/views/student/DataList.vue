<script setup>
import { useStudentStore } from '@/stores/student-store'
import { useToast } from 'primevue'
import { usePagingStore } from 'ss-paging-vue'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const store = useStudentStore()
const paging = usePagingStore()
const toast = useToast()

const tableColumns = ref([
  { key: 'nama', label: t('student.name'), sortable: true },
  { key: 'tempat_lahir', label: t('student.placeOfBirth'), sortable: true },
  { key: 'tgl_lahir', label: t('student.dateOfBirth'), sortable: true },
  { key: 'no_induk', label: t('student.studentId'), sortable: true },
  { key: 'nisn', label: t('student.nisn'), sortable: true },
  { key: 'jenis_kelamin', label: t('student.gender'), sortable: true }
  // { key: 'nama_ayah', label: t('student.fatherName'), sortable: true },
  // { key: 'pekerjaan_ayah', label: t('student.fatherOccupation'), sortable: true },
  // { key: 'nama_ibu', label: t('student.motherName'), sortable: true },
  // { key: 'pekerjaan_ibu', label: t('student.motherOccupation'), sortable: true },
  // { key: 'alamat', label: t('student.address'), sortable: true },
  // { key: 'rt', label: t('student.rt'), sortable: true },
  // { key: 'rw', label: t('student.rw'), sortable: true },
  // { key: 'kelurahan', label: t('student.subDistrict'), sortable: true },
  // { key: 'kecamatan', label: t('student.district'), sortable: true },
  // { key: 'kabupaten_kota', label: t('student.city'), sortable: true },
  // { key: 'provinsi', label: t('student.province'), sortable: true },
  // { key: 'cpd', label: t('student.cpd'), sortable: true },
  // { key: 'mutasi', label: t('student.mutation'), sortable: true }
])

const cm = ref()
const menu = ref()

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

const menuClick = (data, event) => {
  menu.value.toggle(event)
  store.selectedSingle = data
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
    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
    <Column v-for="col of tableColumns" :key="col.key" :field="col.key" :header="col.label" :sortable="col.sortable"></Column>
    <Column field="action">
      <template #header>{{ t('common.action') }}</template>
      <template #body="{ data }">
        <Button type="button" icon="pi pi-ellipsis-v" @click="menuClick(data, $event)" variant="text" />
        <Menu ref="menu" id="overlay_menu" :model="contextMenu" :popup="true" />
      </template>
    </Column>
  </DataTable>
  <ContextMenu ref="cm" :model="contextMenu" @hide="store.selectedSingle = null" />
  <Navigator v-model="store.current" />
</template>
