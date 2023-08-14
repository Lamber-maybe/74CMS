import request from '@/utils/request'
import apiArr from '@/api'
export function urmList(data) {
  return request({
    url: apiArr.urmList,
    method: 'post',
    data
  })
}
export function resumeDetailsList(params) {
  return request({
    url: apiArr.resumeDetails,
    method: 'get',
    params
  })
}
export function resumeUrmJobApply(params) {
  return request({
    url: apiArr.resumeUrmJobApply,
    method: 'get',
    params
  })
}
export function resumeUrmInterview(params) {
  return request({
    url: apiArr.resumeUrmInterview,
    method: 'get',
    params
  })
}
export function resumeUrmAttentionMe(params) {
  return request({
    url: apiArr.resumeUrmAttentionMe,
    method: 'get',
    params
  })
}
export function resumeUrmViewJob(params) {
  return request({
    url: apiArr.resumeUrmViewJob,
    method: 'get',
    params
  })
}
export function addVisit(data) {
  return request({
    url: apiArr.urmAddVisit,
    method: 'post',
    data
  })
}
export function followUpList(params) {
  return request({
    url: apiArr.urmFollowUpList,
    method: 'get',
    params
  })
}
export function updateResume(data) {
  return request({
    url: apiArr.updateResume,
    method: 'post',
    data
  })
}
export function recycleBinList(data) {
  return request({
    url: apiArr.urmRecycleBinList,
    method: 'post',
    data
  })
}
export function recycleBinDel(data) {
  return request({
    url: apiArr.urmRecycleBinDel,
    method: 'post',
    data
  })
}
export function recycleBinReturn(data) {
  return request({
    url: apiArr.urmRecycleBinReturn,
    method: 'post',
    data
  })
}
export function resumeDel(data) {
  return request({
    url: apiArr.resumeDel,
    method: 'post',
    data
  })
}
export function setContactStatus(params) {
  return request({
    url: apiArr.setContactStatus,
    method: 'get',
    params
  })
}
// 简历作品批量审核列表
export function allCustomersImg(params) {
  return request({
    url: apiArr.resumeImgList,
    method: 'get',
    params
  })
}
export function resumeImgAudit(data) {
  return request({
    url: apiArr.resumeImgAudit,
    method: 'post',
    data
  })
}
export function resumeImgDelete(data) {
  return request({
    url: apiArr.resumeImgDel,
    method: 'post',
    data
  })
}
export function resumeImgEdit(data) {
  return request({
    url: apiArr.resumeImgEdit,
    method: 'post',
    data
  })
}
