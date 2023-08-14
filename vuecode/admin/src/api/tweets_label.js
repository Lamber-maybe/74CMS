import request from '@/utils/request'
import apiArr from '@/api'

export function tweetslabelList(params) {
  return request({
    url: apiArr.tweetslabelList,
    method: 'get',
    params
  })
}
