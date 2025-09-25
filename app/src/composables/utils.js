import i18n from '@/locales/i18n'
import axios from 'axios'
import Cookies from 'js-cookie'
import conf from '../../admins.config'

const t = (key) => i18n.global.t(key)
const api = axios.create({ baseURL: conf.apiPublicPath })
const msgPrefix = '[Sakola] '
const localeForPaging = i18n.global.locale.value === 'id' ? 'indonesia' : 'english'

api.interceptors.request.use(
  (config) => {
    if (config.url !== 'auth/login') {
      if (Cookies.get(conf.cookieName) !== null) {
        config.headers.Authorization = `Bearer ${Cookies.get(conf.cookieName)}`
      }
    }

    const selectedLang = localStorage.getItem('language') || 'id'
    config.headers['X-Language'] = selectedLang

    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

function redirect() {
  window.location.reload()
  // when the page is accessed with full reload
  // we have to wait for until the entire page is fully loaded
  window.onload = () => {
    window.location.href = conf.loginUrl()
  }
  // just go back to login page
  window.location.href = conf.loginUrl()
  // and we use this way for SPA routing,
  // because the entire page has been fully loaded
  if (document.readyState === 'complete') {
    window.location.href = conf.loginUrl()
  }
}

const validatePage = (isLoginPage = false) => {
  if (Cookies.get(conf.cookieName) !== undefined) {
    const doValidation = () => {
      api
        .get('auth/validate-page')
        .then(({ data }) => {
          if (data.status === 503) {
            console.warn(msgPrefix + 'Connection to API failed. Any request will be rejected and redirected to Login page.')
            redirect()
          } else {
            console.info(msgPrefix + 'Successfully established connection to Sakola API.')

            if (isLoginPage) {
              window.location.href = conf.homeUrl()
            }
          }
        })
        .catch((error) => {
          console.error(msgPrefix + 'Error:', error)
          if (!isLoginPage) redirect()
        })
    }

    window.addEventListener('load', doValidation)

    if (document.readyState === 'complete') {
      doValidation()
    }
  } else {
    console.warn(msgPrefix + 'Please provide a valid token')
    if (!isLoginPage) redirect()
  }
}

const createFormData = (obj) => {
  let formData = new FormData()

  for (let item in obj) {
    formData.append(item, obj[item])
  }

  return formData
}

const findStudent = (search, callback) => {
  api
    .post(
      `pindah-sekolah/find-student`,
      { search },
      {
        transformRequest: [
          (data) => {
            return createFormData(data)
          }
        ]
      }
    )
    .then(({ data }) => {
      callback(data.result)
    })
}

export { api, createFormData, findStudent, localeForPaging, t, validatePage }
