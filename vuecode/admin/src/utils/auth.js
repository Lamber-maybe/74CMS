import Cookies from 'js-cookie'

const TokenKey = 'qscms_token'
const AccessKey = 'qscms_access'
const AccessExportKey = 'qscms_access_export'
const AccessDeleteKey = 'qscms_access_delete'

export function getToken () {
  return Cookies.get(TokenKey)
}

export function setToken (token) {
  return Cookies.set(TokenKey, token)
}

export function removeToken () {
  return Cookies.remove(TokenKey)
}
export function getAccess () {
  return Cookies.get(AccessKey)
}
export function getAccessExport () {
  return Cookies.get(AccessExportKey)
}
export function getAccessDelete () {
  return Cookies.get(AccessDeleteKey)
}

export function setAccess (access) {
  return Cookies.set(AccessKey, access)
}
export function setAccessExport (access) {
  return Cookies.set(AccessExportKey, access)
}
export function setAccessDelete (access) {
  return Cookies.set(AccessDeleteKey, access)
}

export function removeAccess () {
  return Cookies.remove(AccessKey)
}
export function removeAccessExport () {
  return Cookies.remove(AccessExportKey)
}
export function removeAccessDelete () {
  return Cookies.remove(AccessDeleteKey)
}
