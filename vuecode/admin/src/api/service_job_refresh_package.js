import request from '@/utils/request'
import apiArr from '@/api'

export function serviceJobRefreshPackageList(params) {
  return request({
    url: apiArr.serviceJobRefreshPackageList,
    method: 'get',
    params
  })
}
export function serviceJobRefreshPackageAdd(data) {
  return request({
    url: apiArr.serviceJobRefreshPackageAdd,
    method: 'post',
    data
  })
}
export function serviceJobRefreshPackageEdit(data) {
  return request({
    url: apiArr.serviceJobRefreshPackageEdit,
    method: 'post',
    data
  })
}
export function serviceJobRefreshPackageDelete(data) {
  return request({
    url: apiArr.serviceJobRefreshPackageDelete,
    method: 'post',
    data
  })
}
