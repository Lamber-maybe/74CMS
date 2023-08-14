import request from '@/utils/request'
import apiArr from '@/api'

export function entrustList(params) {
  return request({
    url: apiArr.entrustList,
    method: 'get',
    params
  })
}
export function entrustIntentionList(params) {
  return request({
    url: apiArr.entrustIntentionList,
    method: 'get',
    params
  })
}
export function entrustMatchList(params) {
  return request({
    url: apiArr.entrustMatchList,
    method: 'get',
    params
  })
}
export function entrustMatchApply(data) {
  return request({
    url: apiArr.entrustMatchApply,
    method: 'post',
    data
  })
}

export function entrustDelete(data) {
  return request({
    url: apiArr.entrustDelete,
    method: 'post',
    data
  })
}
