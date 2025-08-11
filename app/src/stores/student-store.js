import { api, createFormData, localeForPaging, t } from '@/composables/utils'
import Cookies from 'js-cookie'
import { defineStore } from 'pinia'
import { usePagingStore as paging } from 'ss-paging-vue'
import conf from '../../admins.config'

export const useStudentStore = defineStore('student', {
  state: () => ({
    endpoint: 'siswa/',
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
      tempat_lahir: '',
      tgl_lahir: '',
      no_induk: '',
      nisn: '',
      jenis_kelamin: '',
      nama_ayah: '',
      pekerjaan_ayah: '',
      nama_ibu: '',
      pekerjaan_ibu: '',
      alamat: '',
      rt: '',
      rw: '',
      kelurahan: '',
      kecamatan: '',
      kab_kota: '',
      provinsi: ''
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
      api.get(`${this.endpoint}detail/${this.selectedSingle.id}`).then(({ data }) => {
        const detail = data.data
        this.formData = {
          id: detail.id,
          nama: detail.nama,
          tempat_lahir: detail.tempat_lahir,
          tgl_lahir: new Date(detail.tgl_lahir),
          no_induk: detail.no_induk ?? '',
          nisn: detail.nisn ?? '',
          jenis_kelamin: detail.jenis_kelamin,
          nama_ayah: detail.nama_ayah ?? '',
          pekerjaan_ayah: detail.pekerjaan_ayah ?? '',
          nama_ibu: detail.nama_ibu,
          pekerjaan_ibu: detail.pekerjaan_ibu,
          alamat: detail.alamat,
          rt: detail.rt,
          rw: detail.rw,
          kelurahan: detail.kelurahan,
          kecamatan: detail.kecamatan,
          kab_kota: detail.kab_kota,
          provinsi: detail.provinsi
        }

        this.formTitle = `${t('student.edit')}: ${detail.nama}`
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
      // format the date into yyyy-mm-dd
      if (this.formData.tgl_lahir !== '') {
        this.formData.tgl_lahir = this.formData.tgl_lahir.toLocaleDateString('en-CA')
      }

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
      this.formData = {
        nama: '',
        tempat_lahir: '',
        tgl_lahir: '',
        no_induk: '',
        nisn: '',
        jenis_kelamin: '',
        nama_ayah: '',
        pekerjaan_ayah: '',
        nama_ibu: '',
        pekerjaan_ibu: '',
        alamat: '',
        rt: '',
        rw: '',
        kelurahan: '',
        kecamatan: '',
        kab_kota: '',
        provinsi: ''
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
