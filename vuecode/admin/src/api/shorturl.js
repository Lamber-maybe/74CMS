import request from '@/utils/request'
import apiArr from '@/api'

export function shorturlList(params) {
  return request({
    url: apiArr.shorturlList,
    method: 'get',
    params
  })
}
export function shorturlSave(data) {
  return request({
    url: apiArr.shorturlSave,
    method: 'post',
    data
  })
}
export function shorturlDelete(data) {
  return request({
    url: apiArr.shorturlDelete,
    method: 'post',
    data
  })
}
