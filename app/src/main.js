import { createPinia } from 'pinia'
import { createApp } from 'vue'
import App from './App.vue'
import i18n from './locales/i18n'
import router from './router'

import Navigator from '@/components/SSPagingNav.vue'
import PagingRows from '@/components/SSPagingRows.vue'
import Aura from '@primeuix/themes/aura'
import PrimeVue from 'primevue/config'
import ConfirmationService from 'primevue/confirmationservice'
import ToastService from 'primevue/toastservice'
import { SearchBox, Table as SsTable } from 'ss-paging-vue/components'

import '@/assets/styles.scss'

const pinia = createPinia()

const app = createApp(App)

app.use(router)
app.use(PrimeVue, {
  theme: {
    preset: Aura,
    options: {
      darkModeSelector: '.app-dark'
    }
  }
})
app.use(ToastService)
app.use(ConfirmationService)
app.use(i18n)
app.use(pinia)
app.component('SsTable', SsTable)
app.component('SearchBox', SearchBox)
app.component('PagingRows', PagingRows)
app.component('Navigator', Navigator)

app.mount('#app')
