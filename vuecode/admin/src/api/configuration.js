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
export function getAdminList(params) {
  return request({
    url: apiArr.getNotificationAdminList,
    method: 'get',
    params
  })
}
export function getToDoNotification(params) {
  return request({
    url: apiArr.getToDoNotification,
    method: 'get',
    params
  })
}
export function setToDoNotification(params) {
  return request({
    url: apiArr.setToDoNotification,
    method: 'post',
    data: params
  })
}
export function switchButton(params) {
  return request({
    url: apiArr.switchButtons,
    method: 'get',
    params
  })
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
export function resumeConfig(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.resumeConfig,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.resumeConfig,
      method,
      params
    })
  }
}
export function resumeCheck(params) {
  return request({
    url: apiArr.resumeCheck,
    method: 'post',
    data: params
  })
}
export function documentConfig(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.documentConfig,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.documentConfig,
      method,
      params
    })
  }
}
export function documentCheck(params) {
  return request({
    url: apiArr.documentCheck,
    method: 'post',
    data: params
  })
}
export function switchWechatTplType(params) {
  return request({
    url: apiArr.switchWechatTplType,
    method: 'post',
    data: params
  })
}