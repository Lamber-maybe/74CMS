import request from '@/utils/request'
import apiArr from '@/api'

export function categoryJobTemplateList(params) {
  return request({
    url: apiArr.categoryJobTemplateList,
    method: 'get',
    params
  })
}
export function categoryJobTemplateAdd(data) {
  return request({
    url: apiArr.categoryJobTemplateAdd,
    method: 'post',
    data
  })
}
export function categoryJobTemplateEdit(data) {
  return request({
    url: apiArr.categoryJobTemplateEdit,
    method: 'post',
    data
  })
}
export function categoryJobTemplateDelete(data) {
  return request({
    url: apiArr.categoryJobTemplateDelete,
    method: 'post',
    data
  })
}