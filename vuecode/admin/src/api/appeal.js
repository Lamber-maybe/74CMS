import request from '@/utils/request'
import apiArr from '@/api'

export function appealList(params) {
  return request({
    url: apiArr.appealList,
    method: 'get',
    params
  })
}
export function appealHandler(data) {
  return request({
    url: apiArr.appealHandler,
    method: 'post',
    data
  })
}
export function appealDelete(data) {
  return request({
    url: apiArr.appealDelete,
    method: 'post',
    data
  })
}
