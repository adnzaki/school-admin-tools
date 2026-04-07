<script setup>
import { appVersion } from '@/composables/utils';
import { useSchoolStore } from '@/stores/school-store';

const store = useSchoolStore()
const showChangelog = () => {
  store.showChangelog = true
}

</script>

<template>
  <div class="card">
    <div class="flex justify-between items-center mb-6">
      <div class="font-semibold text-xl">{{ $t('common.app.info') }}</div>
    </div>
    <ul class="list-none p-0 m-0">
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
          <div class="mt-1 mb-2 text-muted-color">{{ $t('common.app.version') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mr-2 mb-1 md:mb-0">{{ appVersion }}</span>
        </div>
      </li>
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
          <div class="mt-1 mb-2 text-muted-color">{{ $t('common.app.releaseDate') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mr-2 mb-1 md:mb-0">07-04-2026</span>
        </div>
      </li>
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
          <div class="mt-1 mb-2 text-muted-color">{{ $t('common.app.copyright') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mr-2 mb-1 md:mb-0">© 2026 - Adnan Zaki. All rights reserved.</span>
        </div>
      </li>
      <li class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
          <div class="mt-1 mb-2 text-muted-color">{{ $t('common.app.poweredBy') }}</div>
          <span class="text-surface-900 dark:text-surface-0 font-medium mr-2 mb-1 md:mb-0">SDN Pengasinan VII - Rawalumbu, Kota Bekasi</span>
        </div>
      </li>
    </ul>

    <Button class="w-full" :label="$t('common.app.changelog')" @click="showChangelog" />
  </div>

</template>
