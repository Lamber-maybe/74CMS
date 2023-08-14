import request from '@/utils/request'
import apiArr from '@/api'

export function memberCancelApplyList(params) {
  return request({
    url: apiArr.memberCancelApplyList,
    method: 'get',
    params
  })
}
export function memberCancelApplyDelete(data) {
  return request({
    url: apiArr.memberCancelApplyDelete,
    method: 'post',
    data
  })
}
export function memberCancelApplyHandle(data) {
  return request({
    url: apiArr.memberCancelApplyHandle,
    method: 'post',
    data
  })
}

