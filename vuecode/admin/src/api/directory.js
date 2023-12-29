import request from '@/utils/request'
import apiArr from '@/api'

// 获取基础配置
export function getBaseConfig(params) {
  return request({
    url: apiArr.baseConfig,
    method: 'get',
    params
  })
}

// 保存基础配置
export function saveBaseConfig(data) {
  return request({
    url: apiArr.baseConfig,
    method: 'post',
    data
  })
}

// 获取企业线索列表
export function getCompanyClueList(data) {
  return request({
    url: apiArr.getCompanyClueList,
    method: 'post',
    data
  })
}

// 获取消耗记录列表
export function getConsumeRecordList(data) {
  return request({
    url: apiArr.getConsumeRecordList,
    method: 'post',
    data
  })
}

// 导入线索
export function importClue(data) {
  return request({
    url: apiArr.importClue,
    method: 'post',
    data
  })
}

// 获取账户余额
export function getAccountBalance(params) {
  return request({
    url: apiArr.getAccountBalance,
    method: 'get',
    params
  })
}

//获取联系人列表
export function getContactList(params) {
  return request({
    url: apiArr.getContactList,
    method: 'get',
    params
  })
}
// 设为主要联系人
export function setAsMainContact(data) {
  return request({
    url: apiArr.setAsMainContact,
    method: 'post',
    data
  })
}
// 新增联系人
export function clueAddContact(data) {
  return request({
    url: apiArr.clueAddContact,
    method: 'post',
    data
  })
}
// 编辑联系人
export function clueEditContact(data) {
  return request({
    url: apiArr.clueEditContact,
    method: 'post',
    data
  })
}
// 删除联系人
export function deleteContact(data) {
  return request({
    url: apiArr.deleteContact,
    method: 'post',
    data
  })
}
//校验重复的线索名称
export function verifyRepeatClueName(params) {
  return request({
    url: apiArr.verifyRepeatClueName,
    method: 'get',
    params
  })
}
//校验重复的手机号
export function verifyRepeatMobile(params) {
  return request({
    url: apiArr.verifyRepeatMobile,
    method: 'get',
    params
  })
}
//校验重复客户
export function verifyRepeatCompany(params) {
  return request({
    url: apiArr.verifyRepeatCompany,
    method: 'get',
    params
  })
}
//合并线索到客户
export function mergeClueToCompany(data) {
  return request({
    url: apiArr.mergeClueToCompany,
    method: 'post',
    data
  })
}
//查看下一页是否要提示 queryCacheIsExists
export function queryCacheIsExists(data) {
  return request({
    url: apiArr.queryCacheIsExists,
    method: 'post',
    data
  })
}