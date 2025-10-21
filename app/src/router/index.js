import { validatePage } from '@/composables/utils'
import AppLayout from '@/layout/AppLayout.vue'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      component: AppLayout,
      children: [
        {
          path: '/',
          name: 'dashboard',
          meta: { title: 'Dashboard' },
          component: () => import('@/views/home/Dashboard.vue'),
          beforeEnter: () => validatePage()
        },
        {
          path: '/sekolah',
          name: 'sekolah',
          component: () => import('@/views/school/IndexPage.vue')
        },
        {
          path: '/pegawai',
          name: 'pegawai',
          component: () => import('@/views/employee/IndexPage.vue')
        },
        {
          path: '/siswa',
          name: 'siswa',
          component: () => import('@/views/student/IndexPage.vue')
        },
        {
          path: '/surat-masuk',
          name: 'SuratMasuk',
          component: () => import('@/views/in-letter/IndexPage.vue')
        },
        {
          path: '/surat-keluar',
          name: 'SuratKeluar',
          component: () => import('@/views/out-letter/IndexPage.vue')
        },
        {
          path: '/pindah-sekolah',
          name: 'PindahSekolah',
          component: () => import('@/views/mutation/IndexPage.vue')
        },
        {
          path: '/pengantar-nisn',
          name: 'PengantarNisn',
          component: () => import('@/views/nisn/IndexPage.vue')
        },
        {
          path: '/suket-siswa',
          name: 'SuketSiswa',
          component: () => import('@/views/student-enroll/IndexPage.vue')
        }
      ]
    },
    {
      path: '/auth/login',
      name: 'login',
      component: () => import('@/views/pages/auth/Login.vue'),
      beforeEnter: () => validatePage(true)
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'notfound',
      component: () => import('@/views/pages/NotFound.vue')
    }
  ]
})

export default router
