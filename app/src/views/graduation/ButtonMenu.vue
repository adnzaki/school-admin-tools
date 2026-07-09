<template>
  <div class="flex flex-col gap-4 md:flex-col lg:flex-row">
    <div class="w-full flex flex-wrap gap-2">
      <div class="flex flex-col gap-4">
        <div class="flex flex-wrap">
          <Button v-if="store.graduatedStudents.length > 0" :label="$t('common.buttons.graduate')" @click="showGraduateDialog" icon="pi pi-check" class="mr-2 mb-2"></Button>
          <Button v-if="store.graduatedStudents.length > 0" :label="$t('common.buttons.cancelGraduation')" @click="showCancelGraduation" icon="pi pi-times" severity="danger" class="mr-2 mb-2"></Button>
        </div>
      </div>
    </div>
    <ConfirmationDialog
      v-model:display="store.showCancelGraduationDialog"
      :header="$t('student.cancelGraduationConfirmation')"
      :message="$t('student.cancelGraduationConfirm')"
      :buttonLabel="$t('common.buttons.cancelConfirm')"
      @action="cancelGraduation"
    />
    <ConfirmationDialog
      v-model:display="store.showGraduateDialog"
      :header="$t('student.graduateConfirmation')"
      :message="$t('student.graduateConfirmMessage', { count: store.graduatedStudents.length })"
      :buttonLabel="$t('student.graduateConfirm')"
      @action="graduate"
    />
  </div>
</template>
<script setup>
import { useStudentStore } from '@/stores/student-store'
import { useToast } from 'primevue/usetoast'
import { usePagingStore } from 'ss-paging-vue'
import { useI18n } from 'vue-i18n'
import { useConfirm } from 'primevue/useconfirm'

const { t } = useI18n()
const toast = useToast()
const confirm = useConfirm()

const store = useStudentStore()
const paging = usePagingStore()

const showForm = () => {
  store.formTitle = t('student.add')
  store.formEvent = 'add'
  store.showForm = true
}

const showCancelGraduation = () => {
  store.showCancelGraduationDialog = true
}

const cancelGraduation = () => {
  store.graduatedStudents = []
  store.showCancelGraduationDialog = false
}

const showGraduateDialog = () => {
  store.showGraduateDialog = true
}

const graduate = () => {
  store.graduate((status, message) => {
    if (status === 'error') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: message, life: 5000 })
    } else if(status === 'success') {
      toast.add({ severity: 'success', summary: t('common.success'), detail: t('student.graduationSuccess', { count: store.graduatedStudents.length }), life: 4000 })
    } else if (status === 'failed') {
      toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.networkError'), life: 5000 })
    }
  })

}
</script>
