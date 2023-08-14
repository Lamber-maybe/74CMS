import request from '@/utils/request'
import apiArr from '@/api'

export function tplIndex(params) {
  return request({
    url: apiArr.tplIndex,
    method: 'get',
    params
  })
}