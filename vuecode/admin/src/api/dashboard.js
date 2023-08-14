import request from '@/utils/request'
import apiArr from '@/api'

export function dashboardBaseinfo(params) {
  return request({
    url: apiArr.dashboardBaseinfo,
    method: 'get',
    params
  })
}
export function dashboardUpgradeLog(params) {
  return request({
    url: apiArr.dashboardUpgradeLog,
    method: 'get',
    params
  })
}
export function dashboardAuthorize(params) {
  return request({
    url: apiArr.dashboardAuthorize,
    method: 'get',
    params
  })
}
export function dashboardOfficialNews(params) {
  return request({
    url: apiArr.dashboardOfficialNews,
    method: 'get',
    params
  })
}
export function dashboardChart(params) {
  return request({
    url: apiArr.dashboardChart,
    method: 'get',
    params
  })
}
