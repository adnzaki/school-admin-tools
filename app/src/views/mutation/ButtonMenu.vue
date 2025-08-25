<template>
  <div class="flex flex-col gap-4 md:flex-col lg:flex-row">
    <div class="w-full lg:w-2/4 flex flex-wrap gap-2">
      <div class="flex flex-col gap-4">
        <div class="flex flex-wrap">
          <Button :label="$t('common.buttons.add')" @click="showForm" icon="pi pi-plus" class="mr-2 mb-2"></Button>
          <Button :label="$t('common.buttons.delete')" @click="showDeleteDialog" icon="pi pi-trash" severity="secondary" class="mr-2 mb-2"></Button>
        </div>
      </div>
    </div>
    <div class="w-full lg:w-2/4 flex flex-col md:flex-row gap-4">
      <div class="md:w-2/6">
        <div class="w-full flex flex-col md:flex-row">
          <div class="md:w-1/4">
            <div class="flex flex-wrap">
              <Button icon="pi pi-filter" size="large" class="mr-2 mb-2" @click="toggleFilter"></Button>
              <Popover ref="po">
                <div class="flex flex-col gap-4 w-[25rem]">
                  <div>
                    <span class="font-medium block mb-2">{{ $t('mutation.selectGrade') }}</span>
                    <Select v-model="store.classFilter" :options="store.classLevels[store.schoolLevel]" optionLabel="name" :placeholder="$t('mutation.selectGrade')" class="w-full mb-5" />
                    <span class="font-medium block mb-2">{{ $t('common.buttons.filterDate') }}</span>
                    <DatePicker v-model="dates" showIcon fluid iconDisplay="input" class="w-full" selectionMode="range" :manualInput="false" />
                  </div>
                  <div class="flex flex-wrap justify-end">
                    <Button label="Reset" icon="pi pi-refresh" @click="resetDateFilter" severity="secondary" class="mr-2 mb-2"></Button>
                    <Button :label="$t('common.buttons.apply')" @click="applyDateFilter" icon="pi pi-arrow-up-right" class="mr-2 mb-2"></Button>
                  </div>
                </div>
              </Popover>
            </div>
          </div>
          <div class="md:w-3/4">
            <div class="flex flex-wrap">
              <PagingRows :selected-row="25" />
            </div>
          </div>
        </div>
      </div>
      <div class="md:w-4/6">
        <div class="flex flex-col gap-4">
          <div class="flex flex-wrap">
            <SearchBox :paging="paging" v-model="paging.state.search" class="w-full" :placeholder="$t('mutation.search')" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { useMutationStore } from '@/stores/mutation-store'
import { useToast } from 'primevue/usetoast'
import { usePagingStore } from 'ss-paging-vue'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const toast = useToast()

const store = useMutationStore()
const paging = usePagingStore()
const po = ref()
const dates = ref()

const toggleFilter = (event) => {
  po.value.toggle(event)
}

const applyDateFilter = () => {
  if (dates.value) {
    store.dateFilter.start = dates.value[0].toLocaleDateString('en-CA')
    store.dateFilter.end = dates.value[1] === null ? store.dateFilter.start : dates.value[1].toLocaleDateString('en-CA')
  }

  store.getData()
  setTimeout(() => {
    po.value.hide()
  }, 500)
}

const resetDateFilter = () => {
  store.dateFilter.start = ''
  store.dateFilter.end = ''
  store.classFilter = { code: '', name: '' }
  dates.value = null
  store.getData()
  setTimeout(() => {
    po.value.hide()
  }, 500)
}

const showForm = () => {
  store.formTitle = t('mutation.add')
  store.formEvent = 'add'
  store.showForm = true
}

const showDeleteDialog = () => {
  if (store.selected === null) {
    toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.unableToDelete'), life: 5000 })
  } else {
    store.showDeleteDialog = true
  }
}
</script>
