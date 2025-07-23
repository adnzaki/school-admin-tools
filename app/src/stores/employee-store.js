import { api, createFormData, localeForPaging } from '@/composables/utils'
import Cookies from 'js-cookie'
import { defineStore } from 'pinia'
import { usePagingStore as paging } from 'ss-paging-vue'
import conf from '../../admins.config'

export const useEmployeeStore = defineStore('employee', {
  state: () => ({
    endpoint: 'pegawai/',
    current: 1,
    selected: [],
    showForm: false,
    errors: {},
    formTitle: '',
    formData: {
      nama: '',
      nip: '',
      jabatan: '',
      jenis_pegawai: '',
      email: '',
      telepon: ''
    },
    formEvent: 'add' // add | edit
  }),
  actions: {
    save(action, error) {
      api
        .post(`${this.endpoint}save`, this.formData, {
          transformRequest: [
            (data) => {
              return createFormData(data)
            }
          ]
        })
        .then(({ data }) => {
          if (data.status === 'success') {
            this.showForm = false
            this.getData(error)
            this.formData = {
              nama: '',
              nip: '',
              jabatan: '',
              jenis_pegawai: '',
              email: '',
              telepon: ''
            }
            this.errors = {}
          } else {
            this.errors = data.message
          }

          action(data.status, data.message)
        })
        .catch((error) => {
          action(error)
        })
    },
    getData(errorHandler) {
      const limit = 25
      paging().state.rows = limit

      paging().getData({
        lang: localeForPaging,
        limit,
        offset: this.current - 1,
        orderBy: 'nama',
        searchBy: 'nama',
        sort: 'ASC',
        search: '',
        usePost: true,
        url: `${conf.apiPublicPath}${this.endpoint}get-data`,
        autoReset: 500,
        beforeRequest: () => {
          paging().state.token = `Bearer ${Cookies.get(conf.cookieName)}`
        },
        onError: () => {
          //errorNotif()
          errorHandler()
        },
        debug: true
      })
    }
  }
})
