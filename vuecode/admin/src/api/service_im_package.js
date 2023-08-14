import request from '@/utils/request'
import apiArr from '@/api'

export function serviceImPackageList(params) {
  return request({
    url: apiArr.serviceImPackageList,
    method: 'get',
    params
  })
}
export function serviceImPackageAdd(data) {
  return request({
    url: apiArr.serviceImPackageAdd,
    method: 'post',
    data
  })
}
export function serviceImPackageEdit(data) {
  return request({
    url: apiArr.serviceImPackageEdit,
    method: 'post',
    data
  })
}
export function serviceImPackageDelete(data) {
  return request({
    url: apiArr.serviceImPackageDelete,
    method: 'post',
    data
  })
}
