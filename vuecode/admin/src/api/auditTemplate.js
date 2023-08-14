import request from '@/utils/request'
import apiArr from '@/api'

// 获取审核模板
export function getAuditTemplateList(params) {
  return request({
    url: apiArr.getAuditTemplateList,
    method: 'get',
    params
  })
}
// 删除审核模板
export function deleteAuditTemplate(data) {
  return request({
    url: apiArr.deleteAuditTemplate,
    method: 'post',
    data
  })
}
