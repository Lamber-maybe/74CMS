import request from '@/utils/request'
import apiArr from '@/api'

export function makePoster(params) {
  return request({
    url: apiArr.makePoster,
    method: 'get',
    params
  })
}
