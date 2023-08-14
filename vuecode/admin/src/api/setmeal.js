import request from '@/utils/request'
import apiArr from '@/api'

export function setmealList(params) {
  return request({
    url: apiArr.setmealList,
    method: 'get',
    params
  })
}
export function setmealAdd(data) {
  return request({
    url: apiArr.setmealAdd,
    method: 'post',
    data
  })
}
export function setmealEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.setmealEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.setmealEdit,
      method,
      params
    })
  }
}
export function setmealDelete(data) {
  return request({
    url: apiArr.setmealDelete,
    method: 'post',
    data
  })
}
