<script setup>
import { useLayout } from '@/layout/composables/layout'
import router from '@/router'
import { useLoginStore } from '@/stores/login-store'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import AppConfigurator from './AppConfigurator.vue'

const { t } = useI18n()
const store = useLoginStore()

const { toggleMenu, toggleDarkMode, isDarkTheme } = useLayout()

const menu = ref(null)
const accountMenu = ref([
  {
    label: t('menu.profile'),
    icon: 'pi pi-user'
  },
  {
    label: t('menu.schoolData'),
    icon: 'pi pi-building',
    command: () => {
      router.push('/sekolah')
    }
  },
  {
    command: () => {
      store.logout()
    },
    label: 'Log Out',
    icon: 'pi pi-sign-out'
  }
])

function showAccountMenu(event) {
  menu.value.toggle(event)
}
</script>

<template>
  <div class="layout-topbar">
    <div class="layout-topbar-logo-container">
      <button class="layout-menu-button layout-topbar-action" @click="toggleMenu">
        <i class="pi pi-bars"></i>
      </button>
      <router-link to="/" class="layout-topbar-logo">
        <Image src="/android-chrome-192x192.png" alt="Image" width="30" />

        <span>Surpress</span>
      </router-link>
    </div>

    <div class="layout-topbar-actions">
      <div class="layout-config-menu">
        <button type="button" class="layout-topbar-action" @click="toggleDarkMode">
          <i :class="['pi', { 'pi-moon': isDarkTheme, 'pi-sun': !isDarkTheme }]"></i>
        </button>
        <div class="relative">
          <button
            v-styleclass="{ selector: '@next', enterFromClass: 'hidden', enterActiveClass: 'animate-scalein', leaveToClass: 'hidden', leaveActiveClass: 'animate-fadeout', hideOnOutsideClick: true }"
            type="button"
            class="layout-topbar-action layout-topbar-action-highlight"
          >
            <i class="pi pi-palette"></i>
          </button>
          <AppConfigurator />
        </div>
      </div>

      <button
        class="layout-topbar-menu-button layout-topbar-action"
        v-styleclass="{ selector: '@next', enterFromClass: 'hidden', enterActiveClass: 'animate-scalein', leaveToClass: 'hidden', leaveActiveClass: 'animate-fadeout', hideOnOutsideClick: true }"
      >
        <i class="pi pi-ellipsis-v"></i>
      </button>

      <div class="layout-topbar-menu hidden lg:block">
        <div class="layout-topbar-menu-content">
          <button type="button" class="layout-topbar-action py-5">
            <i class="pi pi-user"></i>
            <span>{{ t('menu.profile') }}</span>
          </button>
          <button type="button" class="layout-topbar-action" @click="$router.push('/sekolah')">
            <i class="pi pi-building"></i>
            <span>{{ t('menu.schoolData') }}</span>
          </button>
          <button type="button" class="layout-topbar-action" @click="store.logout()">
            <i class="pi pi-sign-out"></i>
            <span>Log Out</span>
          </button>
          <Menu ref="menu" class="account-menu" :model="accountMenu" :popup="true" />
          <!-- <Popover ref="op" id="overlay_panel" style="width: 150px"></Popover> -->
        </div>
      </div>
    </div>
  </div>
</template>
