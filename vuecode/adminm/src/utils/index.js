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
