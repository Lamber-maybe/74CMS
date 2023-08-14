import request from '@/utils/request'
import apiArr from '@/api'

export function taskList(params) {
  return request({
    url: apiArr.taskList,
    method: 'get',
    params
  })
}
export function taskSave(data) {
  return request({
    url: apiArr.taskSave,
    method: 'post',
    data
  })
}
