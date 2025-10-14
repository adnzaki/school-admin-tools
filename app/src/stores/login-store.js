import { api, createFormData, t } from '@/composables/utils'
import axios from 'axios'
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
      this.cookieOptions = {
        expires: 360,
        path: '/',
        sameSite: 'None',
        secure: true
      }
    },
    logout() {
      // do a logout request with axios: auth/logout
      Cookies.remove(conf.cookieName)
      window.location.href = conf.loginUrl()
      axios('auth/logout').then(({ data }) => {
        if (data.status === 'success') {
          Cookies.remove(conf.cookieName)
          Cookies.remove('surpress_session')
          window.location.href = conf.loginUrl()
        }
      })
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
            if (data.status !== 'success') {
              this.message = t('auth.signIn')
              this.disableButton = false
            } else {
              this.setCookieOptions()
              Cookies.set(conf.cookieName, data.token, this.cookieOptions)

              localStorage.setItem('sakola_user', JSON.stringify(data.user))

              // redirect to dashboard
              setTimeout(() => {
                window.location.href = conf.homeUrl()
              }, 500)
            }
            action(data.status, data.reason)
          })
      }
    }
  }
})
