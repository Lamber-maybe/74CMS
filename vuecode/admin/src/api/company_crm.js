import request from '@/utils/request'
import apiArr from '@/api'
export function crmCustomList(params) {
  return request({
    url: apiArr.crmCustomList,
    method: 'get',
    params
  })
}
export function clueList(params) {
  return request({
    url: apiArr.clueList,
    method: 'get',
    params
  })
}
export function classify(params) {
  return request({
    url: apiArr.classify,
    method: 'get',
    params
  })
}
export function clueAdminLists(params) {
  return request({
    url: apiArr.clueAdminLists,
    method: 'get',
    params
  })
}
export function crmCustomListEdit(data) {
  return request({
    url: apiArr.crmCustomList,
    method: 'post',
    data
  })
}
export function clueDetails(params) {
  return request({
    url: apiArr.clueDetails,
    method: 'get',
    params
  })
}
export function followUpList(params) {
  return request({
    url: apiArr.followUpList,
    method: 'get',
    params
  })
}
export function addVisit(data) {
  return request({
    url: apiArr.addVisit,
    method: 'post',
    data
  })
}
export function exportList(params) {
  return request({
    url: apiArr.exportList,
    method: 'get',
    params
  })
}
export function clueEdit(data) {
  return request({
    url: apiArr.clueEdit,
    method: 'post',
    data
  })
}
export function clueExport(data) {
  return request({
    url: apiArr.clueExport,
    method: 'post',
    data
  })
}
export function clueDelete(params) {
  return request({
    url: apiArr.clueDelete,
    method: 'get',
    params
  })
}
export function clueReceive(data) {
  return request({
    url: apiArr.clueReceive,
    method: 'post',
    data
  })
}
export function clueRelease(data) {
  return request({
    url: apiArr.clueRelease,
    method: 'post',
    data
  })
}
export function clueAdd(data) {
  return request({
    url: apiArr.clueAdd,
    method: 'post',
    data
  })
}
export function crmCompanyList(params) {
  return request({
    url: apiArr.crmCompanyList,
    method: 'get',
    params
  })
}
export function lifeCycle(params) {
  return request({
    url: apiArr.lifeCycle,
    method: 'get',
    params
  })
}
export function companyCrmAudit(data) {
  return request({
    url: apiArr.companyCrmAudit,
    method: 'post',
    data
  })
}
// 刷新职位
export function refreshCrmJob(data) {
  return request({
    url: apiArr.refreshCrmJob,
    method: 'post',
    data
  })
}
export function exportCrmCompanyById(data) {
  return request({
    url: apiArr.exportCrmCompanyById,
    method: 'post',
    data
  })
}
export function companyDetails(params) {
  return request({
    url: apiArr.companyDetails,
    method: 'get',
    params
  })
}
export function contactList(params) {
  return request({
    url: apiArr.contactList,
    method: 'get',
    params
  })
}
export function updateCompany(data) {
  return request({
    url: apiArr.updateCompany,
    method: 'post',
    data
  })
}
export function getAllCrmTags(data) {
  return request({
    url: apiArr.getAllCrmTags,
    method: 'post',
    data
  })
}
export function companyCrmEdit(data) {
  return request({
    url: apiArr.companyCrmEdit,
    method: 'post',
    data
  })
}
export function deleteImg(data) {
  return request({
    url: apiArr.deleteImg,
    method: 'post',
    data
  })
}
export function companyContactList(params) {
  return request({
    url: apiArr.contactList,
    method: 'get',
    params
  })
}
export function delContact(params) {
  return request({
    url: apiArr.delContact,
    method: 'get',
    params
  })
}
export function addContact(data) {
  return request({
    url: apiArr.addContact,
    method: 'post',
    data
  })
}
export function editContact(data) {
  return request({
    url: apiArr.editContact,
    method: 'post',
    data
  })
}
export function companyJob(params) {
  return request({
    url: apiArr.companyJob,
    method: 'get',
    params
  })
}
export function setDisplay(data) {
  return request({
    url: apiArr.setDisplay,
    method: 'post',
    data
  })
}
export function jobApply(params) {
  return request({
    url: apiArr.jobApply,
    method: 'get',
    params
  })
}
export function downResume(params) {
  return request({
    url: apiArr.downResume,
    method: 'get',
    params
  })
}
export function viewResume(params) {
  return request({
    url: apiArr.viewResume,
    method: 'get',
    params
  })
}
export function viewed(params) {
  return request({
    url: apiArr.viewed,
    method: 'get',
    params
  })
}
export function pointsLog(params) {
  return request({
    url: apiArr.pointsLog,
    method: 'get',
    params
  })
}
export function consumeLog(params) {
  return request({
    url: apiArr.newConsumeLog,
    method: 'get',
    params
  })
}
export function crmOrderList(params) {
  return request({
    url: apiArr.crmOrderList,
    method: 'get',
    params
  })
}
export function lifeCycleAll(data) {
  return request({
    url: apiArr.lifeCycleAll,
    method: 'post',
    data
  })
}
export function lifeCycleEdit(data) {
  return request({
    url: apiArr.lifeCycleEdit,
    method: 'post',
    data
  })
}
export function lifeCycleSwitch(data) {
  return request({
    url: apiArr.lifeCycleSwitch,
    method: 'post',
    data
  })
}
export function getSysConfigByCategory(data) {
  return request({
    url: apiArr.getSysConfigByCategory,
    method: 'post',
    data
  })
}
export function editCrmTag(data) {
  return request({
    url: apiArr.editCrmTag,
    method: 'post',
    data
  })
}
export function addCrmTag(data) {
  return request({
    url: apiArr.addCrmTag,
    method: 'post',
    data
  })
}
export function delCrmTag(data) {
  return request({
    url: apiArr.delCrmTag,
    method: 'post',
    data
  })
}
export function editConfig(data) {
  return request({
    url: apiArr.editConfig,
    method: 'post',
    data
  })
}
export function getisWeixinBind(params) {
  return request({
    url: apiArr.getisWeixinBind,
    method: 'get',
    params
  })
}
export function qrcode(params) {
  return request({
    url: apiArr.qrcodeCrm,
    method: 'get',
    params
  })
}
export function companyReceive(data) {
  return request({
    url: apiArr.companyReceive,
    method: 'post',
    data
  })
}
export function companyRelease(data) {
  return request({
    url: apiArr.companyRelease,
    method: 'post',
    data
  })
}
export function companySetDisplay(data) {
  return request({
    url: apiArr.companyCrmSetDisplay,
    method: 'post',
    data
  })
}
export function putRecycleBin(data) {
  return request({
    url: apiArr.putRecycleBin,
    method: 'post',
    data
  })
}
export function recycleBinList(data) {
  return request({
    url: apiArr.recycleBinList,
    method: 'post',
    data
  })
}
export function recycleBinReturn(data) {
  return request({
    url: apiArr.recycleBinReturn,
    method: 'post',
    data
  })
}
export function recycleBinDel(data) {
  return request({
    url: apiArr.recycleBinDel,
    method: 'post',
    data
  })
}
export function toBeFollowedup(params) {
  return request({
    url: apiArr.toBeFollowedup,
    method: 'get',
    params
  })
}
export function companyCrmAdd(data) {
  return request({
    url: apiArr.companyCrmAdd,
    method: 'post',
    data
  })
}
export function crmClueRelease(params) {
  return request({
    url: apiArr.crmClueRelease,
    method: 'get',
    params
  })
}
