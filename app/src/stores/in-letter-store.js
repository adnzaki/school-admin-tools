import { api, createFormData, localeForPaging, t } from '@/composables/utils'
import Cookies from 'js-cookie'
import { defineStore } from 'pinia'
import { usePagingStore as paging } from 'ss-paging-vue'
import conf from '../../admins.config'

export const useInLetterStore = defineStore('in-letter', {
  state: () => ({
    endpoint: 'surat-masuk/',
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
      nomor_surat: '',
      asal_surat: '',
      perihal: '',
      tgl_surat: '',
      tgl_diterima: '',
      keterangan: '',
      berkas: '',
      berkas_url: ''
    },
    dateFilter: {
      start: '',
      end: ''
    },
    formEvent: 'add', // add | edit
    submitted: false, // whether the form has been submitted and submitted to the database or not
    disableButton: false,
    hasNewUpload: false
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
          nomor_surat: detail.nomor_surat,
          asal_surat: detail.asal_surat,
          perihal: detail.perihal,
          tgl_surat: new Date(detail.tgl_surat),
          tgl_diterima: new Date(detail.tgl_diterima),
          keterangan: detail.keterangan,
          berkas: data.lampiran === null ? '' : data.lampiran.nama_file,
          berkas_url: data.lampiran === null ? '' : data.lampiran.file_url
        }

        this.formTitle = t('letterArchive.editInLetter')
        this.formEvent = 'edit'
        this.showForm = true
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
      if (this.formData.tgl_surat !== '') {
        this.formData.tgl_surat = this.formData.tgl_surat.toLocaleDateString('en-CA')
      }

      if (this.formData.tgl_diterima !== '') {
        this.formData.tgl_diterima = this.formData.tgl_diterima.toLocaleDateString('en-CA')
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
            this.submitted = true
          } else {
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
        nomor_surat: '',
        asal_surat: '',
        perihal: '',
        tgl_surat: '',
        tgl_diterima: '',
        keterangan: '',
        berkas: '',
        berkas_url: ''
      }
    },
    getData(errorHandler) {
      const limit = 25
      paging().state.rows = limit

      let param = ''
      if (this.dateFilter.start !== '' && this.dateFilter.end !== '') {
        param = `/${this.dateFilter.start}/${this.dateFilter.end}`
      }

      paging().getData({
        lang: localeForPaging,
        limit,
        offset: this.current - 1,
        orderBy: 'tgl_surat',
        searchBy: ['perihal', 'asal_surat'],
        sort: 'DESC',
        search: '',
        usePost: true,
        url: `${conf.apiPublicPath}${this.endpoint}get-data${param}`,
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
