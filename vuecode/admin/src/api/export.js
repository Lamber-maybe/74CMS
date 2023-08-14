import request from '@/utils/request'
import apiArr from '@/api'

export function exportJob(data) {
  return request({
    url: apiArr.exportJob,
    method: 'post',
    data
  })
}
export function exportJobById(data) {
  return request({
    url: apiArr.exportJobById,
    method: 'post',
    data
  })
}
export function exportCompany(data) {
  return request({
    url: apiArr.exportCompany,
    method: 'post',
    data
  })
}
export function exportCompanyById(data) {
  return request({
    url: apiArr.exportCompanyById,
    method: 'post',
    data
  })
}
export function exportResume(data) {
  return request({
    url: apiArr.exportResume,
    method: 'post',
    data
  })
}
