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
                    <span class="font-medium block mb-2">{{ $t('letterArchive.filterDate') }}</span>
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
      <div class="md:w-2/3">
        <div class="flex flex-col gap-4">
          <div class="flex flex-wrap">
            <SearchBox :paging="paging" v-model="paging.state.search" class="w-full" :placeholder="$t('letterArchive.searchOutLetter')" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { useOutLetterStore } from '@/stores/out-letter-store'
import { useToast } from 'primevue/usetoast'
import { usePagingStore } from 'ss-paging-vue'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const toast = useToast()

const store = useOutLetterStore()
const paging = usePagingStore()
const po = ref()
const dates = ref()

const toggleFilter = (event) => {
  po.value.toggle(event)
}

const applyDateFilter = () => {
  store.dateFilter.start = dates.value[0].toLocaleDateString('en-CA')
  store.dateFilter.end = dates.value[1] === null ? store.dateFilter.start : dates.value[1].toLocaleDateString('en-CA')
  store.getData()
}

const resetDateFilter = () => {
  store.dateFilter.start = ''
  store.dateFilter.end = ''
  dates.value = null
  store.getData()
}

const showForm = () => {
  store.formTitle = t('letterArchive.addNewOutLetter')
  store.formEvent = 'add'
  store.showForm = true
}

const showDeleteDialog = () => {
  store.showDeleteConfirmation(() => {
    toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.unableToDelete'), life: 5000 })
  })
}
</script>
