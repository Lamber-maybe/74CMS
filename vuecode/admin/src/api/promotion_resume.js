import request from '@/utils/request'
import apiArr from '@/api'

export function resumePromotionList(params) {
  return request({
    url: apiArr.resumePromotionList,
    method: 'get',
    params
  })
}
export function resumePromotionSearch(params) {
  return request({
    url: apiArr.resumePromotionSearch,
    method: 'get',
    params
  })
}
export function resumePromotionAdd(data) {
  return request({
    url: apiArr.resumePromotionAdd,
    method: 'post',
    data
  })
}
export function resumePromotionEdit(data) {
  return request({
    url: apiArr.resumePromotionEdit,
    method: 'post',
    data
  })
}
export function resumePromotionCancel(data) {
  return request({
    url: apiArr.resumePromotionCancel,
    method: 'post',
    data
  })
}
