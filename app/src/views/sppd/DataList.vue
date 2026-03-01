<script setup>
import { useSppdStore } from '@/stores/sppd-store'
import { useToast } from 'primevue'
import { usePagingStore } from 'ss-paging-vue'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import conf from '../../../admins.config'
const { t } = useI18n()

const store = useSppdStore()
const paging = usePagingStore()
const toast = useToast()

const tableColumns = ref([
  { key: 'no_surat', label: t('sppd.form.letterNumber'), sortable: true },
  { key: 'pegawai_nama', label: t('sppd.form.employee'), sortable: true },
  { key: 'tujuan', label: t('sppd.form.purpose'), sortable: true },
  { key: 'tgl_berangkat', label: t('sppd.form.departureDate'), sortable: true }
])

const cm = ref()
const menu = ref()
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
    label: t('sppd.print.task'),
    icon: 'pi pi-file-pdf',
    command: () => {
      window.open(`${conf.apiPublicPath}surat-tugas/cetak-surat-tugas?id=${store.selectedSingle.id}&user=${userId.value}`, '_blank')
    }
  },
  {
    label: t('sppd.print.travel'),
    icon: 'pi pi-file-pdf',
    command: () => {
      window.open(`${conf.apiPublicPath}surat-tugas/cetak-sppd?id=${store.selectedSingle.id}&user=${userId.value}`, '_blank')
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
