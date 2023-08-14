import Cookies from 'js-cookie'

const ConfigKey = 'qscms_config'

export function getConfig() {
  return Cookies.get(ConfigKey)
}

export function setConfig(config) {
  return Cookies.set(ConfigKey, config)
}

export function removeConfig() {
  return Cookies.remove(ConfigKey)
}
