import { api, createFormData, localeForPaging, t } from '@/composables/utils'
import Cookies from 'js-cookie'
import { defineStore } from 'pinia'
import { usePagingStore as paging } from 'ss-paging-vue'
import conf from '../../admins.config'

export const useMutationStore = defineStore('mutation', {
  state: () => ({
    endpoint: 'pindah-sekolah/',
    current: 1,
    selected: null,
    // selectedSingle: null,
    showForm: false,
    showDeleteDialog: false,
    errors: {},
    formTitle: '',
    formData: {
      siswa_id: '', // ID siswa yang dimutasi
      no_surat: '', // Nomor surat pindah sekolah
      kelas: '', // Kelas terakhir siswa
      sd_tujuan: '', // Nama sekolah tujuan
      kelurahan: '', // Kelurahan tujuan
      kecamatan: '', // Kecamatan tujuan
      kab_kota: '', // Kabupaten/Kota tujuan
      provinsi: '', // Provinsi tujuan
      alasan: '', // Alasan pindah
      tgl_pindah: '', // Tanggal pindah
      pindah_rayon: 0, // 0 = tidak pindah rayon, 1 = pindah rayon
      no_surat_rayon: '' // Nomor surat pindah rayon (wajib jika pindah_rayon = 1)
    },
    dateFilter: {
      start: '',
      end: ''
    },
    studentOptions: [],
    schoolLevel: '',
    classLevels: {
      SD: [
        { name: `I (${t('common.spelledNumbers.one')})`, code: 1 },
        { name: `II (${t('common.spelledNumbers.two')})`, code: 2 },
        { name: `III (${t('common.spelledNumbers.three')})`, code: 3 },
        { name: `IV (${t('common.spelledNumbers.four')})`, code: 4 },
        { name: `V (${t('common.spelledNumbers.five')})`, code: 5 },
        { name: `VI (${t('common.spelledNumbers.six')})`, code: 6 }
      ],
      SMP: [
        { name: `VII (${t('common.spelledNumbers.seven')})`, code: 7 },
        { name: `VIII (${t('common.spelledNumbers.eight')})`, code: 8 },
        { name: `IX (${t('common.spelledNumbers.nine')})`, code: 9 }
      ],
      SLTA: [
        { name: `X (${t('common.spelledNumbers.ten')})`, code: 10 },
        { name: `XI (${t('common.spelledNumbers.eleven')})`, code: 11 },
        { name: `XII (${t('common.spelledNumbers.twelve')})`, code: 12 }
      ]
    },
    classFilter: { name: '', code: '' },
    formEvent: 'add', // add | edit
    submitted: false, // whether the form has been submitted and submitted to the database or not
    disableButton: false
  }),
  actions: {
    findStudent(search) {
      api.post(`${this.endpoint}find-student`, { search }, { transformRequest: [(data) => createFormData(data)] }).then(({ data }) => {
        this.studentOptions = data.result
      })
    },
    delete(action) {
      api
        .post(
          `${this.endpoint}delete`,
          {
            id: this.selected.id
          },
          {
            transformRequest: [(data) => createFormData(data)]
          }
        )
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
    save(action, error) {
      if (this.formData.tgl_pindah) {
        this.formData.tgl_pindah = this.formData.tgl_pindah.toLocaleDateString('en-CA')
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
            this.studentOptions = []
          } else {
            if (this.formData.tgl_pindah) {
              this.formData.tgl_pindah = new Date(this.formData.tgl_pindah)
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
        siswa_id: '', // ID siswa yang dimutasi
        no_surat: '', // Nomor surat pindah sekolah
        kelas: '', // Kelas terakhir siswa
        sd_tujuan: '', // Nama sekolah tujuan
        kelurahan: '', // Kelurahan tujuan
        kecamatan: '', // Kecamatan tujuan
        kab_kota: '', // Kabupaten/Kota tujuan
        provinsi: '', // Provinsi tujuan
        alasan: '', // Alasan pindah
        tgl_pindah: '', // Tanggal pindah
        pindah_rayon: 0, // 0 = tidak pindah rayon, 1 = pindah rayon
        no_surat_rayon: '' // Nomor surat pindah rayon (wajib jika pindah_rayon = 1)
      }
    },
    getData(errorHandler) {
      const limit = 25
      paging().state.rows = limit

      const kelas = this.classFilter.code || ''
      const start = this.dateFilter.start || ''
      const end = this.dateFilter.end || ''

      // Susun param dinamis
      const paramParts = []
      if (kelas) paramParts.push(kelas)
      if (start) paramParts.push(start)
      if (end) paramParts.push(end)

      const param = paramParts.length > 0 ? '/' + paramParts.join('_') : ''

      paging().getData({
        lang: localeForPaging,
        limit,
        offset: this.current - 1,
        orderBy: 'tgl_pindah',
        searchBy: ['nama', 'no_surat', 'sd_tujuan'],
        sort: 'DESC',
        search: '',
        usePost: true,
        url: `${conf.apiPublicPath}${this.endpoint}get-data${param}`,
        autoReset: 500,
        beforeRequest: () => {
          paging().state.token = `Bearer ${Cookies.get(conf.cookieName)}`
        },
        afterRequest: () => {
          this.schoolLevel = paging().state.rawResponse.schoolLevel
        },
        onError: () => {
          if (errorHandler) errorHandler()
        }
      })
    }
  }
})
