import request from '@/utils/request'
import apiArr from '@/api'

export function makePoster(params) {
  return request({
    url: apiArr.makePoster,
    method: 'get',
    params
  })
}

export function posterList(params) {
  return request({
    url: apiArr.posterList,
    method: 'get',
    params
  })
}
export function posterAdd(data) {
  return request({
    url: apiArr.posterAdd,
    method: 'post',
    data
  })
}
export function posterEdit(data) {
  return request({
    url: apiArr.posterEdit,
    method: 'post',
    data
  })
}
export function posterDisplay(data) {
  return request({
    url: apiArr.posterDisplay,
    method: 'post',
    data
  })
}
export function posterDelete(data) {
  return request({
    url: apiArr.posterDelete,
    method: 'post',
    data
  })
}

export function posterTplindexList(params) {
  return request({
    url: apiArr.posterTplindexList,
    method: 'get',
    params
  })
}
