import request from '@/utils/request'
import apiArr from '@/api'

export function getClassify(params) {
  return request({
    url: apiArr.getClassify,
    method: 'get',
    params
  })
}
