function arrayUnique(arr){
  return Array.from(new Set(arr))
}
function setStorageValue(name,value){
  localStorage.setItem(name,value)
}
function getStorageValue(name){
  return localStorage.getItem(name)
}
function removeStorageValue(name){
  localStorage.removeItem(name)
}

// 对比日期大小(a是否大于b)
function dateCompare(a, b) {
  if (a === null || b === null) {
    return 'neq'
  }
  var arr = a.split('-')
  var starttime = new Date(arr[0], arr[1], arr[2])
  var starttimes = starttime.getTime()
  var arrs = b.split('-')
  var endTime = new Date(arrs[0], arrs[1], arrs[2])
  var endTimes = endTime.getTime()
  if (starttimes > endTimes) {
    return 'gt'
  } else if (starttimes < endTimes) {
    return 'lt'
  } else {
    return 'eq'
  }
}
/**
 * 是否弹出微信二维码窗口（当天只弹出一次）
 * cy 2023-7-17
 * @param val
 * @param {boolean} isRecord [是否记录日期：true|是，false|否]
 * @returns {boolean} [true|可以弹出，false|不可以弹出]
 */
function whetherPopupWeChatQrcodeWindow(val, isRecord = false) {
  let dateInstance = new Date()
  let currentDate = dateInstance.getFullYear() + '-' + (dateInstance.getMonth() + 1) + '-' + dateInstance.getDate()

  let mark = 'wechat_qrcode_' + val
  let recordDate = localStorage.getItem(mark)
  // 判难是否已记录当天日期
  if (dateCompare(recordDate, currentDate) != 'eq') {
    // 判断是否记录日期
    if (true === isRecord) {
      // 记录当天的日期
      localStorage.setItem(mark, currentDate)
    }
    return true;
  }
  return false;
}