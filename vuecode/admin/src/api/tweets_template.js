import request from '@/utils/request'
import apiArr from '@/api'

export function tweetsTemplateList(params) {
  return request({
    url: apiArr.tweetsTemplateList,
    method: 'get',
    params
  })
}
export function tweetsTitleFooter(params) {
  return request({
    url: apiArr.tweetsTitleFooter,
    method: 'get',
    params
  })
}
export function tweetsTemplateDel(data) {
  return request({
    url: apiArr.tweetsTemplateDel,
    method: 'post',
    data
  })
}
export function tweetsTemplateEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.tweetsTemplateEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.tweetsTemplateEdit,
      method,
      params
    })
  }
}
export function tweetsTemplateAdd(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.tweetsTemplateAdd,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.tweetsTemplateAdd,
      method,
      params
    })
  }
}

export function tweetsJoblist(data) {
  return request({
    url: apiArr.tweetsJoblist,
    method: 'post',
    data
  })
}
export function tweetsSearchCompany(params) {
  return request({
    url: apiArr.tweetsSearchCompany,
    method: 'get',
    params
  })
}
export function tweetsSearchJob(params) {
  return request({
    url: apiArr.tweetsSearchJob,
    method: 'get',
    params
  })
}
