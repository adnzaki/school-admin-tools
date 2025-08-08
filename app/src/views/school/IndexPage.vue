<script setup>
import { useSchoolStore } from '@/stores/school-store'
import { useToast } from 'primevue/usetoast'
import { useI18n } from 'vue-i18n'
// import FormDialog from './FormDialog.vue'
import SchoolData from './SchoolData.vue'

const { t } = useI18n()

const toast = useToast()
const store = useSchoolStore()
</script>

<template>
  <SchoolData />
</template>
