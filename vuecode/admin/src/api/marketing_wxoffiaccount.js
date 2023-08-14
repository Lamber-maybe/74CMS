import request from '@/utils/request'
import apiArr from '@/api'

export function marketingWxoffiaccount(data) {
  return request({
    url: apiArr.marketingWxoffiaccount,
    method: 'post',
    data
  })
}
export function marketingWxoffiaccountSearchCompany(params) {
  return request({
    url: apiArr.marketingWxoffiaccountSearchCompany,
    method: 'get',
    params
  })
}
export function marketingSearch(data) {
  return request({
    url: apiArr.marketingSearch,
    method: 'post',
    data
  })
}
export function companySearchByKeyword(data) {
  return request({
    url: apiArr.companySearchByKeyword,
    method: 'post',
    data
  })
}
export function wxTemplateList(data) {
  return request({
    url: apiArr.wxTemplateList,
    method: 'post',
    data
  })
}
export function templateOption(data) {
  return request({
    url: apiArr.templateOption,
    method: 'post',
    data
  })
}
export function templateEdit(data) {
  return request({
    url: apiArr.templateEdit,
    method: 'post',
    data
  })
}
export function templateDetails(data) {
  return request({
    url: apiArr.templateDetails,
    method: 'post',
    data
  })
}
export function templateDelete(data) {
  return request({
    url: apiArr.templateDelete,
    method: 'post',
    data
  })
}
