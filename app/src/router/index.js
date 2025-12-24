import { validatePage } from '@/composables/utils'
import AppLayout from '@/layout/AppLayout.vue'
import { createRouter, createWebHistory } from 'vue-router'
import conf from '../../admins.config'

const router = createRouter({
  history: createWebHistory(conf.basePath),
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
          component: () => import('@/views/school/IndexPage.vue'),
          beforeEnter: () => validatePage()
        },
        {
          path: '/pegawai',
          name: 'pegawai',
          component: () => import('@/views/employee/IndexPage.vue'),
          beforeEnter: () => validatePage()
        },
        {
          path: '/siswa',
          name: 'siswa',
          component: () => import('@/views/student/IndexPage.vue'),
          beforeEnter: () => validatePage()
        },
        {
          path: '/surat-masuk',
          name: 'SuratMasuk',
          component: () => import('@/views/in-letter/IndexPage.vue'),
          beforeEnter: () => validatePage()
        },
        {
          path: '/surat-keluar',
          name: 'SuratKeluar',
          component: () => import('@/views/out-letter/IndexPage.vue'),
          beforeEnter: () => validatePage()
        },
        {
          path: '/pindah-sekolah',
          name: 'PindahSekolah',
          component: () => import('@/views/mutation/IndexPage.vue'),
          beforeEnter: () => validatePage()
        },
        {
          path: '/pengantar-nisn',
          name: 'PengantarNisn',
          component: () => import('@/views/nisn/IndexPage.vue'),
          beforeEnter: () => validatePage()
        },
        {
          path: '/suket-siswa',
          name: 'SuketSiswa',
          component: () => import('@/views/student-enroll/IndexPage.vue'),
          beforeEnter: () => validatePage()
        },
        {
          path: '/surat-tugas',
          name: 'SuratTugas',
          component: () => import('@/views/sppd/IndexPage.vue'),
          beforeEnter: () => validatePage()
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
