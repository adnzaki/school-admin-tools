/**
 * Configuration for School Admins User Interface
 *
 * @author  Adnan Zaki | Wolestech DevTeam
 */

import { baseUrl, host, mode, uiPath } from './build.config'

function getEndpoint(path = '') {
  let endpoint = ''
  switch (mode) {
    case 'development':
      endpoint = `${path}`
      break
    case 'build':
      endpoint = `build${path === '' ? path : `/${path}`}`
      break
    case 'production':
      endpoint = `${path}`
      break
    default:
      endpoint = 'incorrect-mode'
      break
  }

  return endpoint
}

const basePath = '/surpress-app/'

export default {
  // API Url for admin section
  // adminAPI: `${baseUrl()}admin/`,

  // original API public path
  apiPublicPath: `${baseUrl()}`,

  // This URL will be used to redirect from
  // SisaUang authentication page into main
  // application page
  homeUrl: () => {
    return `${uiPath()}${getEndpoint(basePath)}`
  },
  loginUrl: () => {
    return `${uiPath()}${getEndpoint(`${basePath}auth/login`)}`
  },

  host,
  uiPath,
  mode,
  basePath,

  // Cookie key name
  cookieName: 'sakola_token',

  // Cookie expiration time in miliseconds (120 in minutes)
  cookieExp: 120 * 60 * 1000
}
