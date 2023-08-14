import request from '@/utils/request'
import apiArr from '@/api'

export function jobPromotionList(params) {
  return request({
    url: apiArr.jobPromotionList,
    method: 'get',
    params
  })
}
export function jobPromotionSearchCompany(params) {
  return request({
    url: apiArr.jobPromotionSearchCompany,
    method: 'get',
    params
  })
}
export function jobPromotionSearchJob(params) {
  return request({
    url: apiArr.jobPromotionSearchJob,
    method: 'get',
    params
  })
}
export function jobPromotionAdd(data) {
  return request({
    url: apiArr.jobPromotionAdd,
    method: 'post',
    data
  })
}
export function jobPromotionEdit(data) {
  return request({
    url: apiArr.jobPromotionEdit,
    method: 'post',
    data
  })
}
export function jobPromotionCancel(data) {
  return request({
    url: apiArr.jobPromotionCancel,
    method: 'post',
    data
  })
}
