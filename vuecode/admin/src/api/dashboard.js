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
export function newDataStatisticsIndex(params) {
  return request({
    url: apiArr.newDataStatisticsIndex,
    method: 'get',
    params
  })
}
export function newRealTimeData(params) {
  return request({
    url: apiArr.newRealTimeData,
    method: 'get',
    params
  })
}
export function newGeneralstatistics(params) {
  return request({
    url: apiArr.newGeneralstatistics,
    method: 'get',
    params
  })
}
export function newOfficialData(params) {
  return request({
    url: apiArr.newOfficialData,
    method: 'get',
    params
  })
}
export function newDataStatisticsChart(data) {
  return request({
    url: apiArr.newDataStatisticsChart,
    method: 'post',
    data
  })
}
