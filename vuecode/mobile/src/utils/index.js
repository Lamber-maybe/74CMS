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
// 判断是否是微信浏览器的函数
export function isWeiXin () {
  var ua = window.navigator.userAgent.toLowerCase()
  if (ua.indexOf('micromessenger') != -1) {
    return true
  } else {
    return false
  }
}
// 对象转数组
export function objToArray (array) {
  var arr = []
  for (var i in array) {
    arr.push(array[i])
  }
  return arr
}
// 生成图片
export function generatePicture (dom) {
  return new Promise((resolve, reject) => {
    html2canvas(dom, {
      allowTaint: true,
      width: dom.clientWidth, // dom 原始宽度
      height: dom.clientHeight,
      scrollY: 0,
      scrollX: 0,
      dpi: window.devicePixelRatio * 4,
      scale: 4,
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
// 批量替换字符串
export function strReplace (str, find, replace) {
  str = str.replace(find, replace)
  if (str.indexOf(find) != -1) {
    strReplace(str, find, replace)
  }
  return str
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
