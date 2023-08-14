import request from '@/utils/request'
import apiArr from '@/api'
export function outboundRecordList(data) {
  return request({
    url: apiArr.outboundRecordList,
    method: 'post',
    data
  })
}
export function outboundAccount(params) {
  return request({
    url: apiArr.outboundAccount,
    method: 'get',
    params
  })
}
export function outboundAccountSave(data) {
  return request({
    url: apiArr.outboundAccount,
    method: 'post',
    data
  })
}
export function outboundSeat(data) {
  return request({
    url: apiArr.outboundSeat,
    method: 'post',
    data
  })
}
export function outboundStaffList(params) {
  return request({
    url: apiArr.outboundStaffList,
    method: 'get',
    params
  })
}
export function recordDetails(data) {
  return request({
    url: apiArr.recordDetails,
    method: 'post',
    data
  })
}
export function customLndex(params) {
  return request({
    url: apiArr.customLndex,
    method: 'get',
    params
  })
}
export function customLndexAdd(data) {
  return request({
    url: apiArr.customLndex,
    method: 'post',
    data
  })
}
export function dataBoardCallRecord(data) {
  return request({
    url: apiArr.dataBoardCallRecord,
    method: 'post',
    data
  })
}
export function dataBoardcallRecordList(data) {
  return request({
    url: apiArr.dataBoardcallRecordList,
    method: 'post',
    data
  })
}
export function seatCallRecord(data) {
  return request({
    url: apiArr.seatCallRecord,
    method: 'post',
    data
  })
}
export function bindSeat(data) {
  return request({
    url: apiArr.bindSeat,
    method: 'post',
    data
  })
}
export function unbindSeat(data) {
  return request({
    url: apiArr.unbindSeat,
    method: 'post',
    data
  })
}
export function outboundCall(data) {
  return request({
    url: apiArr.outboundCall,
    method: 'post',
    data
  })
}
export function UnbindList(data) {
  return request({
    url: apiArr.UnbindList,
    method: 'post',
    data
  })
}
