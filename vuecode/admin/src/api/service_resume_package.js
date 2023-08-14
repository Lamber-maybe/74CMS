import request from '@/utils/request'
import apiArr from '@/api'

export function serviceResumePackageList(params) {
  return request({
    url: apiArr.serviceResumePackageList,
    method: 'get',
    params
  })
}
export function serviceResumePackageAdd(data) {
  return request({
    url: apiArr.serviceResumePackageAdd,
    method: 'post',
    data
  })
}
export function serviceResumePackageEdit(data) {
  return request({
    url: apiArr.serviceResumePackageEdit,
    method: 'post',
    data
  })
}
export function serviceResumePackageDelete(data) {
  return request({
    url: apiArr.serviceResumePackageDelete,
    method: 'post',
    data
  })
}
