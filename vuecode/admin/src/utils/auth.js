import Cookies from 'js-cookie'

const TokenKey = 'qscms_token'
const AccessKey = 'qscms_access'
const AccessExportKey = 'qscms_access_export'
const AccessDeleteKey = 'qscms_access_delete'
const AccessSetServiceKey = 'qscms_access_set_service'

export function getToken () {
  return localStorage.getItem(TokenKey)
  // return Cookies.get(TokenKey)
}

export function setToken (token) {
  return localStorage.setItem(TokenKey, token)
  // return Cookies.set(TokenKey, token)
}

export function removeToken () {
  return localStorage.removeItem(TokenKey)
}
export function getAccess () {
  // return Cookies.get(AccessKey)
  const access = localStorage.getItem(AccessKey)
  return access == 'all' ? access : JSON.parse(access)
  // console.log(111)
  // return '123'
}
export function getAccessExport () {
  return Cookies.get(AccessExportKey)
}
export function getAccessDelete () {
  return Cookies.get(AccessDeleteKey)
}
export function getAccessSetService () {
  return Cookies.get(AccessSetServiceKey)
}

export function setAccess (access) {
  // return Cookies.set(AccessKey, access)
  access = access == 'all' ? access : JSON.stringify(access)
  return localStorage.setItem(AccessKey, access)
}
export function setAccessExport (access) {
  return Cookies.set(AccessExportKey, access)
}
export function setAccessDelete (access) {
  return Cookies.set(AccessDeleteKey, access)
}
export function setAccessSetService (access) {
  return Cookies.set(AccessSetServiceKey, access)
}

export function removeAccess () {
  // return Cookies.remove(AccessKey)
  return localStorage.removeItem(AccessKey)
}
export function removeAccessExport () {
  return Cookies.remove(AccessExportKey)
}
export function removeAccessDelete () {
  return Cookies.remove(AccessDeleteKey)
}
export function removeAccessSetService () {
  return Cookies.remove(AccessSetServiceKey)
}
