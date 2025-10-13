/**
 * Global Configuration is like a magic file that changes everything in the app
 * Incorrect setting in this configuration will cause the app does not run as expected
 * Please read the instruction in order to set up this configuration correctly.
 *
 * @author      Adnan Zaki
 * @copyright   ZettaDev (c) 2025
 * @since       March 2021
 * @package     Configuration
 */

// There are 3 modes available to run:
// -----------------------------------------------------------------------------------
// "development" mode means you are in truly development environment,
// you are using a dev server from node.js, non-minified UI files, etc.
// -----------------------------------------------------------------------------------
// "build" mode means you are using minified/build version of UI files. It still runs
// on your local web server, but it can be called as "production mode on local server"
// -----------------------------------------------------------------------------------
// "production" mode means you have deployed YesStudy Admin in cloud hosting or
// production server, etc. It will use different URL from your local development
// and of course using HTTPS.

// ------------------ CAUTION! -------------------------------------
// ----- Switch to "production" mode first before create -----------
// ----- build setup for production server/cloud hosting, ----------
// ----- as the bundler will use this mode for bundling the UI files. --
const apiPort = ''

const uiPort = ':5173'

const mode = 'production' // development, build, production

// ------ WARNING! Do not touch below this line ------
/**
 * Host of API
 *
 * @returns string
 */
const host = () => {
  const path = window.location.hostname

  const protocol = mode === 'production' ? 'https' : 'http'

  return `${protocol}://${path}`
}

/**
 * Path to user interface URL
 *
 * @returns string
 */
const uiPath = () => {
  switch (mode) {
    case 'production': // production mode using available web server in the cloud hosting
      return `${host()}/`
    case 'development': // development mode using Node.js
      return `${host()}${uiPort}/`
    case 'build': // build mode using localhost
      return `${host()}${apiPort}/school-admin-tools/`
  }
}

/**
 * API Base URL
 *
 * @returns string
 */
const baseUrl = () => {
  return mode === 'production' ? `${host()}/surpress/api/public/` : `${host()}${apiPort}/school-admin-tools/api/public/`
}

export { baseUrl, host, mode, uiPath, uiPort }
