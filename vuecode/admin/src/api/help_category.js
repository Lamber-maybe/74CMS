import request from '@/utils/request'
import apiArr from '@/api'

export function categoryList(params) {
  return request({
    url: apiArr.helpCategoryList,
    method: 'get',
    params
  })
}
export function categoryAdd(data) {
  return request({
    url: apiArr.helpCategoryAdd,
    method: 'post',
    data
  })
}
export function categoryEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.helpCategoryEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.helpCategoryEdit,
      method,
      params
    })
  }
}
export function categoryDelete(data) {
  return request({
    url: apiArr.helpCategoryDelete,
    method: 'post',
    data
  })
}
