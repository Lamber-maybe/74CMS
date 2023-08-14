import request from '@/utils/request'
import apiArr from '@/api'

export function serviceOlList(params) {
  return request({
    url: apiArr.serviceOl,
    method: 'get',
    params
  })
}

export function serviceOlAdd(data) {
  return request({
    url: apiArr.serviceOlAdd,
    method: 'post',
    data
  })
}

export function serviceOlEdit(params, method = 'post') {
  if (method === 'post') {
    return request({
      url: apiArr.serviceOlEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.serviceOlEdit,
      method,
      params
    })
  }
}

export function serviceOlDelete(data) {
  return request({
    url: apiArr.serviceOlDelete,
    method: 'post',
    data
  })
}
