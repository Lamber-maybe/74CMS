import request from '@/utils/request'
import apiArr from '@/api'

export function memberList(params) {
  return request({
    url: apiArr.memberList,
    method: 'get',
    params
  })
}
export function memberAdd(data) {
  return request({
    url: apiArr.memberAdd,
    method: 'post',
    data
  })
}
export function memberEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.memberEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.memberEdit,
      method,
      params
    })
  }
}
export function memberDelete(data) {
  return request({
    url: apiArr.memberDelete,
    method: 'post',
    data
  })
}
export function memberCheckUnique(data) {
  return request({
    url: apiArr.memberCheckUnique,
    method: 'post',
    data
  })
}
export function memberLock(data) {
  return request({
    url: apiArr.memberLock,
    method: 'post',
    data
  })
}
export function memberIm(data) {
  return request({
    url: apiArr.memberIm,
    method: 'post',
    data
  })
}
export function memberDetail(params) {
  return request({
    url: apiArr.memberDetail,
    method: 'get',
    params
  })
}
export function memberPointsList(params) {
  return request({
    url: apiArr.memberPointsList,
    method: 'get',
    params
  })
}
export function memberPointsEdit(data) {
  return request({
    url: apiArr.memberPointsEdit,
    method: 'post',
    data
  })
}
export function memberPointsLog(params) {
  return request({
    url: apiArr.memberPointsLog,
    method: 'get',
    params
  })
}
export function memberLoginLog(params) {
  return request({
    url: apiArr.memberLoginLog,
    method: 'get',
    params
  })
}
export function memberActionLog(params) {
  return request({
    url: apiArr.memberActionLog,
    method: 'get',
    params
  })
}
export function management(params) {
  return request({
    url: apiArr.management,
    method: 'get',
    params
  })
}
export function lifeCycle(params) {
  return request({
    url: apiArr.lifeCycle,
    method: 'get',
    params
  })
}

