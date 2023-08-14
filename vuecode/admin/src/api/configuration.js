import request from '@/utils/request'
import apiArr from '@/api'

export function setConfig(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.setConfig,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.setConfig,
      method,
      params
    })
  }
}
export function clearCache(params) {
  return request({
    url: apiArr.clearCache,
    method: 'get',
    params
  })
}
export function getConfigCache(params) {
  return request({
    url: apiArr.getConfigCache,
    method: 'get',
    params
  })
}
export function smsTplList(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.smsTplList,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.smsTplList,
      method,
      params
    })
  }
}
export function setFieldRule(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.setFieldRule,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.setFieldRule,
      method,
      params
    })
  }
}
export function getFieldRule(params) {
  return request({
    url: apiArr.getFieldRule,
    method: 'get',
    params
  })
}
export function setResumeModule(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.setResumeModule,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.setResumeModule,
      method,
      params
    })
  }
}

export function sendMailTest(data) {
  return request({
    url: apiArr.sendMailTest,
    method: 'post',
    data
  })
}
export function sendSmsTest(data) {
  return request({
    url: apiArr.sendSmsTest,
    method: 'post',
    data
  })
}
export function setMobileIndexModule(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.setMobileIndexModule,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.setMobileIndexModule,
      method,
      params
    })
  }
}
export function mobileIndexMenuList(params) {
  return request({
    url: apiArr.mobileIndexMenuList,
    method: 'get',
    params
  })
}
export function mobileIndexMenuEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.mobileIndexMenuEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.mobileIndexMenuEdit,
      method,
      params
    })
  }
}
export function getNotifyRule(params) {
  return request({
    url: apiArr.getNotifyRule,
    method: 'get',
    params
  })
}
export function setNotifyRule(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.setNotifyRule,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.setNotifyRule,
      method,
      params
    })
  }
}
export function getWechatNotifyRule(params) {
  return request({
    url: apiArr.getWechatNotifyRule,
    method: 'get',
    params
  })
}
export function setWechatNotifyRule(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.setWechatNotifyRule,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.setNotifyRule,
      method,
      params
    })
  }
}
