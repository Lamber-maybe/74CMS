import request from '@/utils/request'
import apiArr from '@/api'

export function cronList(params) {
  return request({
    url: apiArr.cronList,
    method: 'get',
    params
  })
}
export function cronAdd(data) {
  return request({
    url: apiArr.cronAdd,
    method: 'post',
    data
  })
}
export function cronEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.cronEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.cronEdit,
      method,
      params
    })
  }
}
export function cronSet(data) {
  return request({
    url: apiArr.cronSet,
    method: 'post',
    data
  })
}
export function cronRun(data) {
  return request({
    url: apiArr.cronRun,
    method: 'post',
    data
  })
}
export function cronDelete(data) {
  return request({
    url: apiArr.cronDelete,
    method: 'post',
    data
  })
}
export function cronLog(params) {
  return request({
    url: apiArr.cronLog,
    method: 'get',
    params
  })
}
