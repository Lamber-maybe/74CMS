import request from '@/utils/request'
import apiArr from '@/api'

export function marketTplList(params) {
  return request({
    url: apiArr.marketTplList,
    method: 'get',
    params
  })
}
export function marketTplAdd(data) {
  return request({
    url: apiArr.marketTplAdd,
    method: 'post',
    data
  })
}
export function marketTplEdit(data) {
  return request({
    url: apiArr.marketTplEdit,
    method: 'post',
    data
  })
}
export function marketTplDelete(data) {
  return request({
    url: apiArr.marketTplDelete,
    method: 'post',
    data
  })
}
export function marketTaskList(params) {
  return request({
    url: apiArr.marketTaskList,
    method: 'get',
    params
  })
}
export function marketTaskAdd(data) {
  return request({
    url: apiArr.marketTaskAdd,
    method: 'post',
    data
  })
}
export function marketTaskDelete(data) {
  return request({
    url: apiArr.marketTaskDelete,
    method: 'post',
    data
  })
}
export function marketTaskRun(data) {
  return request({
    url: apiArr.marketTaskRun,
    method: 'post',
    data
  })
}
export function marketSearchMember(params) {
  return request({
    url: apiArr.marketSearchMember,
    method: 'get',
    params
  })
}
