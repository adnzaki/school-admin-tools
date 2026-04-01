<script setup>
import { useSchoolStore } from '@/stores/school-store'
import { useToast } from 'primevue/usetoast'
import { useI18n } from 'vue-i18n'
import FormDialog from './FormDialog.vue'
import SchoolData from './SchoolData.vue'
import UpdatePassword from './UpdatePassword.vue'

const { t } = useI18n()

const toast = useToast()
const store = useSchoolStore()
</script>

<template>
  <SchoolData />
  <div class="col-span-12 xl:col-span-12">
    <UpdatePassword />
  </div>
  <FormDialog />
</template>
