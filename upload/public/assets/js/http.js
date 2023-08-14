var service = axios.create({
  baseURL: qscms.apiUrl,
  withCredentials: true, // 跨域支持发送cookie
  timeout: 5000 // 请求超时时间
})
function httpget (url, params) {
  return new Promise(function(resolve, reject){
    service
      .get(url, {
        headers: {
          'user-token': qscms.userToken,
          platform: qscms.platform
        },
        params: params
      })
      .then(function(res) {
        if (res.data.code != 200) {
          handlerHttpError(res.data)
          reject(res.data)
        } else {
          resolve(res.data)
        }
      })
      .catch(function(err){
        if (err.message.includes('timeout')) {
          window.ELEMENT.Message.error('请求超时，请刷新页面再试')
        }
        reject(err)
      })
  })
}
function httppost (url, data) {
  return new Promise(function(resolve, reject){
    service
      .post(url, data, {
        headers: {
          'user-token': qscms.userToken,
          platform: qscms.platform
        }
      })
      .then(function(res){
        if (res.data.code != 200) {
          handlerHttpError(res.data)
          reject(res.data)
        } else {
          resolve(res.data)
        }
      })
      .catch(function(err) {
        if (err.message.includes('timeout')) {
          window.ELEMENT.Message.error('请求超时，请刷新页面再试')
        }
        reject(err)
      })
  })
}
function postFormData (url, params) {
  return new Promise(function(resolve, reject){
    service({
      headers: {
        'Content-Type': 'multipart/form-data', // ;boundary=----WebKitFormBoundaryQ6d2Qh69dv9wad2u,
        'user-token': qscms.userToken,
        platform: qscms.platform
      },
      transformRequest: [
        function (data) {
          // 在请求之前对data传参进行格式转换
          var formData = new FormData()
          Object.keys(data).forEach(function(key){
            formData.append(key, data[key])
          })
          return formData
        }
      ],
      url: url,
      method: 'post',
      data: params
    })
      .then(function(res){
        if (res.data.code != 200) {
          handlerHttpError(res.data)
          reject(res.data)
        } else {
          resolve(res.data)
        }
      })
      .catch(function(err) {
        if (err.message.includes('timeout')) {
          window.ELEMENT.Message.error('请求超时，请刷新页面再试')
        }
        reject(err)
      })
  })
}
