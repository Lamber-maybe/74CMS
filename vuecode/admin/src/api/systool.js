import request from '@/utils/request'
import apiArr from '@/api'

export function sysToolSyncSetmeal(data) {
  return request({
    url: apiArr.sysToolSyncSetmeal,
    method: 'post',
    data
  })
}
export function sysToolSyncCompanyDisplay(data) {
  return request({
    url: apiArr.sysToolSyncCompanyDisplay,
    method: 'post',
    data
  })
}
