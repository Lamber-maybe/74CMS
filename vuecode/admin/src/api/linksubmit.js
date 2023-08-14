import request from '@/utils/request'
import apiArr from '@/api'

export function submit(params) {
  return request({
    url: apiArr.linksubmit,
    method: 'get',
    params
  })
}
