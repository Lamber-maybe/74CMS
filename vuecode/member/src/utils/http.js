/**
 * http.js
 * 封装axios，
 * 调用方法:
 * http.get('/api/enquiry/web/query',{id:1}).then((res)=>{你的操作})
 * http.post('/api/enquiry/web/update',{id:1}).then((res)=>{你的操作})
 * http.postFormData('/api/enquiry/web/update',{id:1,file:file}).then((res)=>{你的操作})
 */
import {setStorageValue,getStorageValue} from '@/utils/index'
import axios from 'axios'
import store from '@/store'
import { handlerHttpError } from '@/utils/error'
// import { Message } from 'element-ui'
import { message } from '@/utils/resetMessage'
const service = axios.create({
  baseURL: window.global.RequestBaseUrl,
  withCredentials: true, // 跨域支持发送cookie
  timeout: window.global.RequestTimeout // 请求超时时间
})

export default {
  /**
       * get方法，对应get请求
       * @param {String} url [请求的url地址]
       * @param {Object} params [请求时携带的参数]
       */
  get (url, params) {
    return new Promise((resolve, reject) => {
      service
        .get(url, {
          headers: {
            'user-token': store.state.userToken,
            platform: window.global.Platform
          },
          params: params
        })
        .then((res) => {
          if (res.data.code !== 200) {
            handlerHttpError(res.data)
            reject(res.data)
          } else {
            resolve(res.data)
          }
        })
        .catch((err) => {
          if (err.message.includes('timeout')) {
            message.error('请求超时，请刷新页面再试')
          }
          reject(err)
        })
    })
  },
  /**
       * post方法，对应post请求
       * @param {String} url [请求的url地址]
       * @param {Object} data [请求时携带的参数]
       */
  post (url, data, loginErrorAlias) {
    return new Promise((resolve, reject) => {
      service
        .post(url, data, {
          headers: {
            'user-token': store.state.userToken,
            platform: window.global.Platform
          }
        })
        .then((res) => {
          if (res.data.code !== 200) {
            if (loginErrorAlias !== undefined) {
              let LoginErrorNum = getStorageValue(loginErrorAlias)
              LoginErrorNum = LoginErrorNum?LoginErrorNum:0
              LoginErrorNum = parseInt(LoginErrorNum)
              setStorageValue(loginErrorAlias,parseInt(LoginErrorNum + 1))
            }
            handlerHttpError(res.data)
            reject(res.data)
          } else {
            resolve(res.data)
          }
        })
        .catch((err) => {
          if (err.message.includes('timeout')) {
            message.error('请求超时，请刷新页面再试')
          }
          reject(err)
        })
    })
  },
  /**
       * postFormData方法，对应post请求，用来提交文件+数据
       * @param {String} url [请求的url地址]
       * @param {Object} params [请求时携带的参数]
       */
  postFormData (url, params) {
    return new Promise((resolve, reject) => {
      service({
        headers: {
          'Content-Type': 'multipart/form-data', // ;boundary=----WebKitFormBoundaryQ6d2Qh69dv9wad2u,
          'user-token': store.state.userToken,
          platform: window.global.Platform
        },
        transformRequest: [
          function (data) {
            // 在请求之前对data传参进行格式转换
            const formData = new FormData()
            Object.keys(data).forEach((key) => {
              formData.append(key, data[key])
            })
            return formData
          }
        ],
        url: url,
        method: 'post',
        data: params
      })
        .then((res) => {
          if (res.data.code !== 200) {
            handlerHttpError(res.data)
            reject(res.data)
          } else {
            resolve(res.data)
          }
        })
        .catch((err) => {
          if (err.message.includes('timeout')) {
            message.error('请求超时，请刷新页面再试')
          }
          reject(err)
        })
    })
  }
}
