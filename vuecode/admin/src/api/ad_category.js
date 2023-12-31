import request from '@/utils/request'
import apiArr from '@/api'

export function categoryList(params) {
  return request({
    url: apiArr.adCategoryList,
    method: 'get',
    params
  })
}
export function categoryAdd(data) {
  return request({
    url: apiArr.adCategoryAdd,
    method: 'post',
    data
  })
}
export function categoryEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.adCategoryEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.adCategoryEdit,
      method,
      params
    })
  }
}
export function categoryDelete(data) {
  return request({
    url: apiArr.adCategoryDelete,
    method: 'post',
    data
  })
}
