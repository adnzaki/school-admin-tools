<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

import AppMenuItem from './AppMenuItem.vue'

const { t } = useI18n()

const model = ref([
  {
    label: 'Home',
    items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/' }]
  },
  {
    label: 'Data',
    items: [
      { label: t('menu.employee'), icon: 'pi pi-fw pi-address-book', to: '/pegawai' },
      { label: t('menu.student'), icon: 'pi pi-fw pi-users', to: '/siswa' }
    ]
  },
  {
    label: t('menu.administration'),
    items: [
      { label: t('menu.inLetter'), icon: 'pi pi-fw pi-file-import', to: '/surat-masuk' },
      { label: t('menu.outLetter'), icon: 'pi pi-fw pi-file-export', to: '/surat-keluar' }
    ]
  },
  {
    label: t('menu.studentAdm'),
    items: [
      { label: t('menu.moveSchool'), icon: 'pi pi-fw pi-directions', to: '/pindah-sekolah' },
      { label: t('menu.printNISN'), icon: 'pi pi-fw pi-id-card', to: '/pengantar-nisn' },
      { label: t('menu.letterOfStudent'), icon: 'pi pi-fw pi-clipboard', to: '/suket-siswa' }
    ]
  },
  {
    label: t('menu.employeeAdm'),
    items: [
      { label: t('menu.letterOfEmployee'), icon: 'pi pi-fw pi-file-check', to: '/suket-pegawai' },
      { label: t('menu.taskLetter'), icon: 'pi pi-fw pi-hourglass', to: '/surat-tugas' },
      { label: t('menu.travelLetter'), icon: 'pi pi-fw pi-truck', to: '/perjalanan-dinas' },
      { label: t('menu.permitRequest'), icon: 'pi pi-fw pi-folder-plus', to: '/surat-izin' }
    ]
  },
  {
    label: t('menu.finance'),
    items: [
      { label: t('menu.bankStatement'), icon: 'pi pi-fw pi-receipt', to: '/rekening-koran' }
      // { label: 'Standing Instruction', icon: 'pi pi-fw pi-wallet', to: '/#' }
    ]
  }
])
</script>

<template>
  <ul class="layout-menu">
    <template v-for="(item, i) in model" :key="item">
      <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
      <li v-if="item.separator" class="menu-separator"></li>
    </template>
  </ul>
</template>

<style lang="scss" scoped></style>
