import request from '@/utils/request'
import apiArr from '@/api'

export function feedbackList(params) {
  return request({
    url: apiArr.feedbackList,
    method: 'get',
    params
  })
}
export function feedbackDelete(data) {
  return request({
    url: apiArr.feedbackDelete,
    method: 'post',
    data
  })
}
export function feedbackHandler(data) {
  return request({
    url: apiArr.feedbackHandler,
    method: 'post',
    data
  })
}
