import { api, createFormData, t } from '@/composables/utils'
import Cookies from 'js-cookie'
import { defineStore } from 'pinia'
import conf from '../../admins.config'

export const useLoginStore = defineStore('login', {
  state: () => ({
    username: '',
    password: '',
    message: '',
    messageClass: '',
    showMessage: false,
    cookieOptions: {},
    disableButton: false
  }),
  actions: {
    setCookieOptions() {
      conf.cookieExp *= 12 * 360 // keep cookie valid for 360 days
      const dt = new Date()
      let now = dt.getTime()
      let expMs = now + conf.cookieExp
      let exp = new Date(expMs)
      this.cookieOptions = {
        expires: 360,
        path: '/',
        sameSite: 'None',
        secure: true
      }
    },
    validate(action) {
      if (this.username === '' || this.password === '') {
        action('empty')
      } else {
        this.disableButton = true
        this.message = t('auth.loading')
        const postData = {
          username: this.username,
          password: this.password
        }
        api
          .post('auth/login', postData, {
            transformRequest: [(data) => createFormData(data)]
          })
          .then(({ data }) => {
            if (data.status === 'failed') {
              this.message = t('auth.signIn')
              this.disableButton = false
            } else {
              this.setCookieOptions()
              Cookies.set(conf.cookieName, data.token, this.cookieOptions)

              // redirect to dashboard
              setTimeout(() => {
                window.location.href = conf.homeUrl()
              }, 500)
            }
            action(data.status)
          })
      }
    }
  }
})
