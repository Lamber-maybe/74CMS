import request from '@/utils/request'
import apiArr from '@/api'

export function dashboardBaseinfo(params) {
  return request({
    url: apiArr.dashboardBaseinfo,
    method: 'get',
    params
  })
}
export function officialData(params) {
  return request({
    url: apiArr.officialData,
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
