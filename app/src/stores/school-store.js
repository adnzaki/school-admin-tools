import { api, createFormData, t } from '@/composables/utils'
import { defineStore } from 'pinia'

export const useSchoolStore = defineStore('school', {
  state: () => ({
    endpoint: 'institusi/',
    showForm: false,
    errors: {},
    formTitle: '',
    formData: {
      kepala_sekolah: '',
      nip_kepala_sekolah: '',
      wakil_kepala_sekolah: '',
      nip_wakil_kepala_sekolah: '',
      bendahara_bos: '',
      nip_bendahara_bos: '',
      bendahara_barang: '',
      nip_bendahara_barang: '',
      alamat: '',
      kelurahan: '',
      kecamatan: '',
      kab_kota: '',
      provinsi: '',
      file_kop: '',
      file_kop_path: ''
    },
    showInput: false,
    schoolName: '',
    submitted: false // whether the form has been submitted and submitted to the database or not
  }),
  actions: {
    getDetail() {
      api.get(`${this.endpoint}detail`).then(({ data }) => {
        const detail = data.data
        this.formData = {
          id: detail.id,
          kepala_sekolah: detail.kepala_sekolah,
          nip_kepala_sekolah: detail.nip_kepala_sekolah,
          wakil_kepala_sekolah: detail.wakil_kepala_sekolah,
          nip_wakil_kepala_sekolah: detail.nip_wakil_kepala_sekolah,
          bendahara_bos: detail.bendahara_bos,
          nip_bendahara_bos: detail.nip_bendahara_bos,
          bendahara_barang: detail.bendahara_barang,
          nip_bendahara_barang: detail.nip_bendahara_barang,
          alamat: detail.alamat,
          kelurahan: detail.kelurahan,
          kecamatan: detail.kecamatan,
          kab_kota: detail.kab_kota,
          provinsi: detail.provinsi,
          file_kop: detail.file_kop,
          file_kop_path: detail.kop_path
        }

        this.schoolName = detail.nama_sekolah
        this.formTitle = t('school.edit')
        this.showForm = true
      })
    },
    upload(file, action) {
      try {
        api
          .post(`${this.endpoint}upload-kop`, file, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
          .then(({ data }) => {
            action(data.status)
            this.formData.file_kop = data.uploaded[0].filename
            this.formData.file_kop_path = data.uploaded[0].url
          })
      } catch {
        action('failed')
      }
    },
    removeUploadedFile() {
      api
        .post(
          `${this.endpoint}delete-kop`,
          { filename: this.formData.file_kop },
          {
            transformRequest: [(data) => createFormData(data)]
          }
        )
        .then(({ data }) => {
          this.formData.file_kop = ''
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

          action(data.status, data.message)
        })
        .catch((error) => {
          action(error)
        })
    }
  }
})
