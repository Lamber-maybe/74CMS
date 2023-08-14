import request from '@/utils/request'
import apiArr from '@/api'

export function serviceResumeTagList(params) {
  return request({
    url: apiArr.serviceResumeTagList,
    method: 'get',
    params
  })
}
export function serviceResumeTagAdd(data) {
  return request({
    url: apiArr.serviceResumeTagAdd,
    method: 'post',
    data
  })
}
export function serviceResumeTagEdit(data) {
  return request({
    url: apiArr.serviceResumeTagEdit,
    method: 'post',
    data
  })
}
export function serviceResumeTagDelete(data) {
  return request({
    url: apiArr.serviceResumeTagDelete,
    method: 'post',
    data
  })
}
