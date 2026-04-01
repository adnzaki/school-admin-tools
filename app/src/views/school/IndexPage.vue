<script setup>
import FormDialog from './FormDialog.vue'
import SchoolData from './SchoolData.vue'
import UpdatePassword from './UpdatePassword.vue'
import { appVersion } from '@/composables/utils'

</script>

<template>
  <SchoolData />
  <div class="col-span-12 xl:col-span-12">
    <UpdatePassword />
  </div>
  <div class="col-span-12 xl:col-span-12 mt-5">
    <p class="text-muted-color text-center">{{ $t('common.app.version') }}: <strong>{{ appVersion }}</strong></p>
  </div>
  <FormDialog />
</template>
