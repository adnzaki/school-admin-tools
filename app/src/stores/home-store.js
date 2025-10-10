import { api } from '@/composables/utils'
import { defineStore } from 'pinia'

export const useHomeStore = defineStore('home', {
  state: () => ({
    totalStudents: 0,
    totalEmployees: 0,
    totalIncomingLetter: 0,
    totalOutgoingLetter: 0
  }),
  actions: {
    getSummary() {
      api.get('home/summary').then(({ data }) => {
        this.totalStudents = data.siswa
        this.totalEmployees = data.pegawai
        this.totalIncomingLetter = data.suratMasuk
        this.totalOutgoingLetter = data.suratKeluar
      })
    }
  }
})
