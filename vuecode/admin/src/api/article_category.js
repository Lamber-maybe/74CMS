import request from '@/utils/request'
import apiArr from '@/api'

export function categoryList(params) {
  return request({
    url: apiArr.articleCategoryList,
    method: 'get',
    params
  })
}
export function categoryAdd(data) {
  return request({
    url: apiArr.articleCategoryAdd,
    method: 'post',
    data
  })
}
export function categoryEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.articleCategoryEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.articleCategoryEdit,
      method,
      params
    })
  }
}
export function categoryDelete(data) {
  return request({
    url: apiArr.articleCategoryDelete,
    method: 'post',
    data
  })
}
