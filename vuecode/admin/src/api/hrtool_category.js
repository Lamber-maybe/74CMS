import request from '@/utils/request'
import apiArr from '@/api'

export function categoryList(params) {
  return request({
    url: apiArr.hrtoolCategoryList,
    method: 'get',
    params
  })
}
export function categoryAdd(data) {
  return request({
    url: apiArr.hrtoolCategoryAdd,
    method: 'post',
    data
  })
}
export function categoryEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.hrtoolCategoryEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.hrtoolCategoryEdit,
      method,
      params
    })
  }
}
export function categoryDelete(data) {
  return request({
    url: apiArr.hrtoolCategoryDelete,
    method: 'post',
    data
  })
}
