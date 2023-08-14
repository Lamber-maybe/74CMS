import request from '@/utils/request'
import apiArr from '@/api'

// 获取采集设置信息
export function getSeting(data) {
  return request({
    url: apiArr.getSeting,
    method: 'get',
    data
  })
}

// 保存采集设置
export function saveSeting(data) {
  return request({
    url: apiArr.saveSeting,
    method: 'post',
    data
  })
}

// 保存职位设置
export function saveJobSeting(data) {
  return request({
    url: apiArr.saveJobSeting,
    method: 'post',
    data
  })
}

// 保存企业设置
export function saveCompanySeting(data) {
  return request({
    url: apiArr.saveCompanySeting,
    method: 'post',
    data
  })
}

// 保存账号设置
export function saveAccountSeting(data) {
  return request({
    url: apiArr.saveAccountSeting,
    method: 'post',
    data
  })
}

// 保存资讯设置
export function saveArticleSeting(data) {
  return request({
    url: apiArr.saveArticleSeting,
    method: 'post',
    data
  })
}
