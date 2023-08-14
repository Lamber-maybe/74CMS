import Cookies from 'js-cookie'

const TokenKey = 'qscms_token'
const AccessKey = 'qscms_access'

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

export function setAccess (access) {
  return Cookies.set(AccessKey, access)
}

export function removeAccess () {
  return Cookies.remove(AccessKey)
}
