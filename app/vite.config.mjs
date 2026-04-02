import { fileURLToPath, URL } from 'node:url'

import { PrimeVueResolver } from '@primevue/auto-import-resolver'
import vue from '@vitejs/plugin-vue'
import Components from 'unplugin-vue-components/vite'
import { defineConfig } from 'vite'
import markdown from 'vite-plugin-md'

// https://vitejs.dev/config/
export default defineConfig({
  optimizeDeps: {
    noDiscovery: true
  },
  plugins: [
    vue({
      include: [/\.vue$/, /\.vue\?vue/, /\.md$/] // allow Vue to parse .md files
    }),
    markdown(),
    Components({
      resolvers: [PrimeVueResolver()]
    })
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  }
})
