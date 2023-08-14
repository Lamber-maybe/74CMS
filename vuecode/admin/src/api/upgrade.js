import request from '@/utils/request'
import apiArr from '@/api'

export function newVersionList(params) {
  return request({
    url: apiArr.newVersionList,
    method: 'get',
    params
  })
}
export function updateStart(params) {
  return request({
    url: apiArr.updateStart,
    method: 'get',
    params
  })
}
export function updateDownload(params) {
  return request({
    url: apiArr.updateDownload,
    method: 'get',
    params
  })
}
export function speedProgress(params) {
  return request({
    url: apiArr.speedProgress,
    method: 'get',
    params
  })
}
export function unzip(params) {
  return request({
    url: apiArr.upgradeUnzip,
    method: 'get',
    params
  })
}
export function update(params) {
  return request({
    url: apiArr.upgradeUpdate,
    method: 'get',
    params
  })
}
