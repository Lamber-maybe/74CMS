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
export function memberCancelApplyBackups(data) {
  return request({
    url: apiArr.memberCancelApplyBackups,
    method: 'post',
    data
  })
}
export function memberCancelApplyDeleteAll(data) {
  return request({
    url: apiArr.memberCancelApplyDeleteAll,
    method: 'post',
    data
  })
}
export function exportBackups(data) {
  return request({
    url: apiArr.exportBackups,
    method: 'post',
    data
  })
}
export function backupsList(params) {
  return request({
    url: apiArr.backupsList,
    method: 'get',
    params
  })
}
export function backupsDelete(data) {
  return request({
    url: apiArr.backupsDelete,
    method: 'post',
    data
  })
}
export function classify(params) {
  return request({
    url: apiArr.classify,
    method: 'get',
    params
  })
}
