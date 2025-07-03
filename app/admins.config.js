/**
 * Configuration for School Admins User Interface
 *
 * @author  Adnan Zaki | Wolestech DevTeam
 */

import { mode, host, uiPath, baseUrl } from './build.config'

function getEndpoint(path) {
  let endpoint = ''
  switch (mode) {
    case 'development':
      endpoint = `/#/${path}`
      break
    case 'build':
      endpoint = `/app/build/#/${path}`
      break
    case 'production':
      endpoint = `/app/#/${path}`
      break
    default:
      endpoint = 'incorrect-mode'
      break
  }

  return endpoint
}

export default {
  // API Url for admin section
  // adminAPI: `${baseUrl()}admin/`,

  // original API public path
  apiPublicPath: `${baseUrl()}`,

  // This URL will be used to redirect from
  // SisaUang authentication page into main
  // application page
  homeUrl: () => {
    return `${uiPath()}${getEndpoint('dashboard')}`
  },
  loginUrl: () => {
    return `${uiPath()}${getEndpoint('login')}`
  },

  host,
  uiPath,
  mode,

  // Cookie key name
  cookieName: 'school_admins_token',

  // Cookie expiration time in miliseconds (120 in minutes)
  cookieExp: 120 * 60 * 1000,
}
