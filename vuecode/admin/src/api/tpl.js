import request from '@/utils/request'
import apiArr from '@/api'

export function tplIndex(params) {
  return request({
    url: apiArr.tplIndex,
    method: 'get',
    params
  })
}
// 详情页模板切换
export function showTpl(params) {
  return request({
    url: apiArr.showTpl,
    method: 'get',
    params
  })
}
