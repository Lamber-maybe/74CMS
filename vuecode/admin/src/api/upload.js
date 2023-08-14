import request from '@/utils/request'
import apiArr from '@/api'

export function apiUpload(data) {
  return request({
    url: apiArr.upload,
    method: 'post',
    data
  })
}

export function  checkQiniu() {
  return request({
    url: '/upload/checkQiniu',
    method: 'get',
  })
}
