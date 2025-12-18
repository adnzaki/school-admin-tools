import { api, createFormData, localeForPaging, t } from '@/composables/utils'
import Cookies from 'js-cookie'
import { defineStore } from 'pinia'
import { usePagingStore as paging } from 'ss-paging-vue'
import conf from '../../admins.config'

export const useSppdStore = defineStore('sppd', {
  state: () => ({
    endpoint: 'sppd/',
    current: 1,
    selected: [],
    selectedSingle: null,
    showForm: false,
    showDeleteDialog: false,
    pegawaiOptions: [],
    errors: {},
    formTitle: '',
    formData: {
      //   id: '',
      pegawai_id: '',
      nomor_surat: '',
      tgl_surat: '',
      tingkat_biaya: '',
      tujuan: '',
      transportasi: '',
      lokasi: '',
      durasi: '',
      tgl_berangkat: '',
      tgl_kembali: '',
      kepala_skpd: '',
      nip_kepala_skpd: ''
    },
    disableForm: false,
    formEvent: 'add', // add | edit
    submitted: false, // whether the form has been submitted and submitted to the database or not
    disableButton: false
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
          pegawai_id: detail.pegawai_id,
          pegawai_nama: detail.pegawai_nama,
          nomor_surat: detail.surat_nomor_surat,
          tgl_surat: new Date(detail.surat_tgl_surat),
          tingkat_biaya: detail.tingkat_biaya,
          tujuan: detail.tujuan,
          transportasi: detail.transportasi,
          lokasi: detail.lokasi,
          durasi: detail.durasi,
          tgl_berangkat: new Date(detail.tgl_berangkat),
          tgl_kembali: new Date(detail.tgl_kembali),
          kepala_skpd: detail.kepala_skpd,
          nip_kepala_skpd: detail.nip_kepala_skpd
        }
        this.formTitle = t('sppd.edit')
        this.formEvent = 'edit'
        this.showForm = true
      })
    },
    save(action, error) {
      if (this.formData.tgl_surat) {
        this.formData.tgl_surat = new Date(this.formData.tgl_surat).toLocaleDateString('en-CA')
      }
      if (this.formData.tgl_berangkat) {
        this.formData.tgl_berangkat = new Date(this.formData.tgl_berangkat).toLocaleDateString('en-CA')
      }
      if (this.formData.tgl_kembali) {
        this.formData.tgl_kembali = new Date(this.formData.tgl_kembali).toLocaleDateString('en-CA')
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
            this.pegawaiOptions = []
          } else {
            if (this.formData.tgl_surat) this.formData.tgl_surat = new Date(this.formData.tgl_surat)
            if (this.formData.tgl_berangkat) this.formData.tgl_berangkat = new Date(this.formData.tgl_berangkat)
            if (this.formData.tgl_kembali) this.formData.tgl_kembali = new Date(this.formData.tgl_kembali)

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
        pegawai_id: '',
        nomor_surat: '',
        tgl_surat: '',
        tingkat_biaya: '',
        tujuan: '',
        transportasi: '',
        lokasi: '',
        durasi: '',
        tgl_berangkat: '',
        tgl_kembali: '',
        kepala_skpd: '',
        nip_kepala_skpd: ''
      }
    },
    getData(errorHandler) {
      const limit = 25
      paging().state.rows = limit

      paging().getData({
        lang: localeForPaging,
        limit,
        offset: this.current - 1,
        orderBy: 'id',
        searchBy: ['tujuan', 'nomor_surat'],
        sort: 'DESC',
        search: '',
        usePost: true,
        url: `${conf.apiPublicPath}${this.endpoint}get-data`,
        autoReset: 500,
        beforeRequest: () => {
          paging().state.token = `Bearer ${Cookies.get(conf.cookieName)}`
        },
        afterRequest: () => {},
        onError: () => {
          if (errorHandler !== undefined) {
            errorHandler()
          }
        }
      })
    }
  }
})
