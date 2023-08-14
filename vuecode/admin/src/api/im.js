import request from '@/utils/request'
import apiArr from '@/api'

export function imQuickmsgList(params, method = 'post') {
  return request({
    url: apiArr.imQuickmsgList,
    method: 'post',
    params
  })
}
export function imQuickmsgAdd(params, method = 'post') {
  return request({
    url: apiArr.imQuickmsgAdd,
    method: 'post',
    params
  })
}
export function imQuickmsgEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.imQuickmsgEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.imQuickmsgEdit,
      method,
      params
    })
  }
}
export function imQuickmsgSaveAll(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.imQuickmsgSaveAll,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.imQuickmsgSaveAll,
      method,
      params
    })
  }
}
export function imQuickmsgDelete(data) {
  return request({
    url: apiArr.imQuickmsgDelete,
    method: 'post',
    data
  })
}
export function messageBack(data) {
  return request({
    url: apiArr.imMessageBack,
    method: 'post',
    data
  })
}
export function messageForbid(data) {
  return request({
    url: apiArr.imMessageForbid,
    method: 'post',
    data
  })
}
export function setImRule(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.setImRule,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.setImRule,
      method,
      params
    })
  }
}
export function imForbidList(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.imForbidList,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.imForbidList,
      method,
      params
    })
  }
}
export function imChatmanageList(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.imChatmanageList,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.imChatmanageList,
      method,
      params
    })
  }
}
export function imChatmessageList(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.imChatmessageList,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.imChatmessageList,
      method,
      params
    })
  }
}

