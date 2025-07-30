import { api, createFormData, localeForPaging, t } from '@/composables/utils'
import Cookies from 'js-cookie'
import { defineStore } from 'pinia'
import { usePagingStore as paging } from 'ss-paging-vue'
import conf from '../../admins.config'

export const useEmployeeStore = defineStore('employee', {
  state: () => ({
    endpoint: 'pegawai/',
    current: 1,
    selected: [],
    selectedSingle: null,
    showForm: false,
    showImportDialog: false,
    showDeleteDialog: false,
    errorImport: '',
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
    delete(action) {
      api
        .delete(`${this.endpoint}delete`, {
          data: {
            id: this.selected.map((item) => item.id)
          }
        })
        .then(({ data }) => {
          if (data.status === 'success') {
            this.selected = []
            this.getData()
            this.showDeleteDialog = false
          }

          action(data.status, data.message)
        })
        .catch(() => {
          action('failed', t('common.networkError'))
        })
    },
    showDeleteConfirmation(action) {
      if (this.selected.length > 0) this.showDeleteDialog = true
      else action()
    },
    getDetail() {
      api.get(`${this.endpoint}detail/${this.selected.id}`).then(({ data }) => {
        const detail = data.data
        this.formData = {
          id: detail.id,
          nama: detail.nama,
          nip: detail.nip ?? '',
          jabatan: detail.jabatan,
          jenis_pegawai: detail.jenis_pegawai,
          email: detail.email,
          telepon: detail.telepon
        }
        this.formTitle = t('employee.edit')
        this.formEvent = 'edit'
        this.showForm = true
      })
    },
    import(file, action) {
      try {
        api
          .post(`${this.endpoint}import-data`, file, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
          .then(({ data }) => {
            if (data.status === 'success') {
              this.showImportDialog = false
            }

            action(data.status, data.message)
            this.getData()
          })
      } catch {
        action('failed', '')
      }
    },
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
            this.resetForm()
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
    resetForm() {
      if (this.formEvent === 'edit') {
        this.formData = {
          nama: '',
          nip: '',
          jabatan: '',
          jenis_pegawai: '',
          email: '',
          telepon: ''
        }
      }
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
          if (errorHandler !== undefined) {
            errorHandler()
          }
        }
      })
    }
  }
})
