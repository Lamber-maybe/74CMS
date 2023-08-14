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
