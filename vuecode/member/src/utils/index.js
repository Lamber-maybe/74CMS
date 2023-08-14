import html2canvas from 'html2canvas'
/**
 * Parse the time to string
 * @param {(Object|string|number)} time
 * @param {string} cFormat
 * @returns {string | null}
 */
export function parseTime (time, cFormat) {
  if (arguments.length === 0) {
    return null
  }
  const format = cFormat || '{y}-{m}-{d} {h}:{i}:{s}'
  let date
  if (typeof time === 'object') {
    date = time
  } else {
    if (typeof time === 'string' && /^[0-9]+$/.test(time)) {
      time = parseInt(time)
    }
    if (typeof time === 'number' && time.toString().length === 10) {
      time = time * 1000
    }
    date = new Date(time)
  }
  const formatObj = {
    y: date.getFullYear(),
    m: date.getMonth() + 1,
    d: date.getDate(),
    h: date.getHours(),
    i: date.getMinutes(),
    s: date.getSeconds(),
    a: date.getDay()
  }
  const time_str = format.replace(/{([ymdhisa])+}/g, (result, key) => {
    const value = formatObj[key]
    // Note: getDay() returns 0 on Sunday
    if (key === 'a') {
      return [ '日', '一', '二', '三', '四', '五', '六' ][value]
    }
    return value.toString().padStart(2, '0')
  })
  return time_str
}

/**
 * @param {number} time
 * @param {string} option
 * @returns {string}
 */
export function formatTime (time, option, detailed) {
  if (('' + time).length === 10) {
    time = parseInt(time) * 1000
  } else {
    time = +time
  }
  const d = new Date(time)
  const now = Date.now()
  if (detailed) {
    const diff = (now - d) / 1000

    if (diff < 30) {
      return '刚刚'
    } else if (diff < 3600) {
      // less 1 hour
      return Math.ceil(diff / 60) + '分钟前'
    } else if (diff < 3600 * 24) {
      return Math.ceil(diff / 3600) + '小时前'
    } else if (diff < 3600 * 24 * 2) {
      return '1天前'
    }
  }

  if (option) {
    return parseTime(time, option)
  } else {
    return d.getMonth() + 1 + '月' + d.getDate() + '日' + d.getHours() + '时' + d.getMinutes() + '分'
  }
}

/**
 * @param {string} url
 * @returns {Object}
 */
export function param2Obj (url) {
  const search = url.split('?')[1]
  if (!search) {
    return {}
  }
  return JSON.parse(
    '{"' +
      decodeURIComponent(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"').replace(/\+/g, ' ') +
      '"}'
  )
}
export function obj2Param (data, isPrefix = false) {
  let prefix = isPrefix ? '?' : ''
  let _result = []
  for (let key in data) {
    let value = data[key]
    // 去掉为空的参数
    if ([ '', undefined, null ].includes(value)) {
      continue
    }
    if (value.constructor === Array) {
      value.forEach((_value) => {
        _result.push(encodeURIComponent(key) + '[]=' + encodeURIComponent(_value))
      })
    } else {
      _result.push(encodeURIComponent(key) + '=' + encodeURIComponent(value))
    }
  }

  return _result.length ? prefix + _result.join('&') : ''
}

/**
 * 计算两坐标点之间的距离
 * 返回友好的距离长度
 *
 * @param   current_lat     decimal   当前位置纬度
 * @param   current_lng     decimal   当前位置经度
 * @param   target_lat      decimal   目标纬度
 * @param   target_lng      decimal   目标经度
 *
 * @return  decimal   距离
 */
export function countDistance (current_lat, current_lng, target_lat, target_lng) {
  let PI = '3.1415926535898'
  let radLat1 = current_lat * (PI / 180)
  let radLat2 = target_lat * (PI / 180)
  let a = radLat1 - radLat2
  let b = current_lng * (PI / 180) - target_lng * (PI / 180)
  let s =
    2 *
    Math.asin(
      Math.sqrt(Math.pow(Math.sin(a / 2), 2) + Math.cos(radLat1) * Math.cos(radLat2) * Math.pow(Math.sin(b / 2), 2))
    )
  s = s * 6378
  // if (!type) {
  s = s > 1 ? parseFloat(s).toFixed(1) + 'km' : Math.round(s * 1000) + 'm'
  // } else {
  //     s = round(s, 1)
  // }
  return s
}
// 对象转数组
export function objToArray (array) {
  var arr = []
  for (var i in array) {
    arr.push(array[i])
  }
  return arr
}
// 批量替换字符串
export function strReplace (str, find, replace) {
  str = str.replace(find, replace)
  if (str.indexOf(find) != -1) {
    strReplace(str, find, replace)
  }
  return str
}

export function generatePicture (dom) {
  return new Promise((resolve, reject) => {
    html2canvas(dom, {
      allowTaint: true,
      useCORS: true // 支持跨域
    })
      .then((canvas) => {
        // 转成图片，生成图片地址
        let imgUrl = canvas.toDataURL('image/png')
        resolve(imgUrl)
      })
      .catch((error) => {
        reject(error)
      })
  })
}
/**
 * 校验身份证
 */
export function isIdcard (idcard) {
  idcard = idcard.toUpperCase()
  //身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X。
  if (!/(^\d{15}$)|(^\d{17}([0-9]|X)$)/.test(idcard)) {
    return false
  }
  //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
  //下面分别分析出生日期和校验位
  let len, re
  len = idcard.length
  if (len == 15) {
    re = new RegExp(/^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/)
    let arrSplit = idcard.match(re)

    //检查生日日期是否正确
    let dtmBirth = new Date('19' + arrSplit[2] + '/' + arrSplit[3] + '/' + arrSplit[4])
    let bCorrectDay
    bCorrectDay =
      dtmBirth.getYear() == Number(arrSplit[2]) &&
      dtmBirth.getMonth() + 1 == Number(arrSplit[3]) &&
      dtmBirth.getDate() == Number(arrSplit[4])
    if (!bCorrectDay) {
      return false
    } else {
      //将15位身份证转成18位
      //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
      let arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2)
      let arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2')
      let nTemp = 0,
        i
      idcard = idcard.substr(0, 6) + '19' + idcard.substr(6, idcard.length - 6)
      for (i = 0; i < 17; i++) {
        nTemp += idcard.substr(i, 1) * arrInt[i]
      }
      idcard += arrCh[nTemp % 11]
      return true
    }
  }
  if (len == 18) {
    re = new RegExp(/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/)
    let arrSplit = idcard.match(re)

    //检查生日日期是否正确
    let dtmBirth = new Date(arrSplit[2] + '/' + arrSplit[3] + '/' + arrSplit[4])
    let bCorrectDay
    bCorrectDay =
      dtmBirth.getFullYear() == Number(arrSplit[2]) &&
      dtmBirth.getMonth() + 1 == Number(arrSplit[3]) &&
      dtmBirth.getDate() == Number(arrSplit[4])
    if (!bCorrectDay) {
      return false
    } else {
      //检验18位身份证的校验码是否正确。
      //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
      let valnum
      let arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2)
      let arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2')
      let nTemp = 0,
        i
      for (i = 0; i < 17; i++) {
        nTemp += idcard.substr(i, 1) * arrInt[i]
      }
      valnum = arrCh[nTemp % 11]
      if (valnum != idcard.substr(17, 1)) {
        return false
      }
      return true
    }
  }
  return false
}
// 对比日期大小(a是否大于b)
export function dateCompare (a, b) {
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
//banner跳转
export function adLink (item) {
  if (item.web_link_url != '') {
    window.open(item.web_link_url)
  }
}
export function setStorageValue(name,value){
  localStorage.setItem(name,value)
}
export function getStorageValue(name){
  return localStorage.getItem(name)  
}
export function removeStorageValue(name){
  localStorage.removeItem(name)
}
