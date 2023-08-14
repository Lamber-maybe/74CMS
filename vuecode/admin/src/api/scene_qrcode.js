import request from '@/utils/request'
import apiArr from '@/api'

export function sceneQrcodeList(params) {
  return request({
    url: apiArr.sceneQrcodeList,
    method: 'get',
    params
  })
}
export function sceneQrcodeAdd(data) {
  return request({
    url: apiArr.sceneQrcodeAdd,
    method: 'post',
    data
  })
}
export function sceneQrcodeDelete(data) {
  return request({
    url: apiArr.sceneQrcodeDelete,
    method: 'post',
    data
  })
}
export function sceneQrcodePlatformList(params) {
  return request({
    url: apiArr.sceneQrcodePlatformList,
    method: 'get',
    params
  })
}
export function sceneQrcodeTypeList(params) {
  return request({
    url: apiArr.sceneQrcodeTypeList,
    method: 'get',
    params
  })
}
export function sceneSearchList(params) {
  return request({
    url: apiArr.sceneSearchList,
    method: 'get',
    params
  })
}
