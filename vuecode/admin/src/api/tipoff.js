import request from '@/utils/request'
import apiArr from '@/api'

export function tipoffList(params) {
  return request({
    url: apiArr.tipoffList,
    method: 'get',
    params
  })
}
export function tipoffDelete(data) {
  return request({
    url: apiArr.tipoffDelete,
    method: 'post',
    data
  })
}
export function tipoffHandler(data) {
  return request({
    url: apiArr.tipoffHandler,
    method: 'post',
    data
  })
}
