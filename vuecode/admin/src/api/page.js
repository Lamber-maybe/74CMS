import request from '@/utils/request'
import apiArr from '@/api'

export function pageList(params) {
  return request({
    url: apiArr.pageList,
    method: 'get',
    params
  })
}
export function pageEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.pageEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.pageEdit,
      method,
      params
    })
  }
}
