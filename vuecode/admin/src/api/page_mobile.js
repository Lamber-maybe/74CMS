import request from '@/utils/request'
import apiArr from '@/api'

export function pageMobileList(params) {
  return request({
    url: apiArr.pageMobileList,
    method: 'get',
    params
  })
}
export function pageMobileEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.pageMobileEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.pageMobileEdit,
      method,
      params
    })
  }
}
