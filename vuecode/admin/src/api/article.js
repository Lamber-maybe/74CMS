import request from '@/utils/request'
import apiArr from '@/api'

export function articleList(params) {
  return request({
    url: apiArr.articleList,
    method: 'get',
    params
  })
}
export function articleAdd(data) {
  return request({
    url: apiArr.articleAdd,
    method: 'post',
    data
  })
}
export function articleEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.articleEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.articleEdit,
      method,
      params
    })
  }
}
export function articleDelete(data) {
  return request({
    url: apiArr.articleDelete,
    method: 'post',
    data
  })
}
