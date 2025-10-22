import { api, createFormData, localeForPaging, t } from '@/composables/utils'
import Cookies from 'js-cookie'
import { defineStore } from 'pinia'
import { usePagingStore as paging } from 'ss-paging-vue'
import conf from '../../admins.config'

export const useStudentEnrollStore = defineStore('student-enroll', {
  state: () => ({
    endpoint: 'sekolah-disini/',
    current: 1,
    selected: [],
    selectedSingle: null,
    showForm: false,
    showImportDialog: false,
    showDeleteDialog: false,
    studentOptions: [],
    errorImport: '',
    errors: {},
    formTitle: '',
    schoolLevel: '',
    formData: {
      siswa_id: '',
      nomor_surat: '',
      tgl_surat: '',
      kelas: '',
      keperluan: '',
      deskripsi: ''
      // id and surat_id for detail data required when editing
      // id: '',
      // surat_id: ''
    },
    dateFilter: {
      start: '',
      end: ''
    },
    disableForm: false,
    formEvent: 'add', // add | edit
    submitted: false, // whether the form has been submitted and submitted to the database or not
    disableButton: false,
    hasNewUpload: false
  }),
  actions: {
    delete(action) {
      const ids = this.selected.map((item) => item.id)

      api
        .delete(`${this.endpoint}delete`, {
          data: ids
        })
        .then(({ data }) => {
          if (data.status === 'success') {
            this.selected = []
            paging().reloadData()
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
        const detail = data
        this.formData = {
          id: detail.id,
          surat_id: detail.surat_id,
          siswa_id: detail.siswa_id,
          siswa_nama: detail.siswa_nama,
          nomor_surat: detail.no_surat,
          kelas: detail.kelas,
          keperluan: detail.keperluan,
          deskripsi: detail.deskripsi,
          tgl_surat: new Date(detail.tgl_surat)
        }
        this.formTitle = t('letterArchive.editInLetter')
        this.formEvent = 'edit'
        this.showForm = true
        this.disableForm = data.editable === 1 ? false : true
      })
    },
    upload(file, action) {
      try {
        api
          .post(`${this.endpoint}upload`, file, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
          .then(({ data }) => {
            action(data.status)
            this.formData.berkas = data.uploaded[0].filename
            this.formData.berkas_url = data.url

            // set new upload flag
            this.hasNewUpload = true
          })
      } catch {
        action('failed')
      }
    },
    removeUploadedFile() {
      api
        .post(
          `${this.endpoint}delete-berkas`,
          { filename: this.formData.berkas },
          {
            transformRequest: [(data) => createFormData(data)]
          }
        )
        .then(({ data }) => {
          // OK
        })
    },
    save(action, error) {
      if (this.formData.tgl_surat) {
        this.formData.tgl_surat = this.formData.tgl_surat.toLocaleDateString('en-CA')
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
            paging().reloadData()
            this.resetForm()
            this.errors = {}
            this.submitted = true
            this.studentOptions = []
          } else {
            if (this.formData.tgl_surat) {
              this.formData.tgl_surat = new Date(this.formData.tgl_surat)
            }
            this.errors = data.message
          }

          this.disableButton = false

          action(data.status, data.message)
        })
        .catch((error) => {
          action(error)
        })
    },
    resetForm() {
      this.formData = {
        siswa_id: '',
        nomor_surat: '',
        tgl_surat: ''
      }
    },
    getData(errorHandler) {
      const limit = 25
      paging().state.rows = limit

      paging().getData({
        lang: localeForPaging,
        limit,
        offset: this.current - 1,
        orderBy: 'tgl_surat',
        searchBy: ['nama', 'nisn', 'nomor_surat'],
        sort: 'DESC',
        search: '',
        usePost: true,
        url: `${conf.apiPublicPath}${this.endpoint}get-data`,
        autoReset: 500,
        beforeRequest: () => {
          paging().state.token = `Bearer ${Cookies.get(conf.cookieName)}`
        },
        afterRequest: () => {
          this.schoolLevel = paging().state.rawResponse.schoolLevel
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
