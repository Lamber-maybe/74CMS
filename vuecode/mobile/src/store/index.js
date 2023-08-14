import Vue from 'vue'
import Vuex from 'vuex'
import persistedState from 'vuex-persistedstate'
import axios from 'axios'
import api from '@/api'
import VueCookies from 'vue-cookies'
import router from '@/router'
Vue.use(Vuex)
const service = axios.create({
  baseURL: window.global.RequestBaseUrl,
  withCredentials: true, // 跨域支持发送cookie
  timeout: window.global.RequestTimeout // 请求超时时间
})
const store = new Vuex.Store({
  state: {
    imToken: '', // 聊天token
    ws: '', // websock对象
    imSelfAvatar: '', // 用户头像信息
    imTargetUserinfo: {}, // 对方用户信息
    imChatid: '',
    messagelist: [], // 消息列表
    imShowParams: {}, // 会话列表跳转 聊天页面所需参数
    sendScrollState: false, // 区分上下拉加载还是 发送消息设置 滚动条
    isInBlackObj: {
      chatid: '',
      cancel_enable: '2',
      message: ''
    }, // 是否在黑名单中
    IsUpdataChatList: 0, // 不是同一个人聊天则需要更新会话列表
    imUnreaded: false, // 未读消息状态
    videoShowPlay: false,
    isHeadShow: true, // 是否显示头部
    LoginOrNot: false, // 用户是否登录
    LoginType: 0, // 用户类型-1为企业 2位个人
    userToken: 'user_token',
    userMobile: '',
    platform: 'mobile',
    sendSmsInterval: 60, // 发送验证码间隔时间，以秒为单位
    sendSmsBtnText: '获取验证码',
    sendSmsBtnTextColor: '#2199ff',
    sendSmsBtnDisabled: false,
    sendEmailInterval: 60, // 发送验证码间隔时间，以秒为单位
    sendEmailBtnText: '获取验证码',
    sendEmailBtnTextColor: '#2199ff',
    sendEmailBtnDisabled: false,
    sendSmsTimer: '',
    sendSmsMessage: '',
    sendEmailTimer: '',
    sendEmailMessage: '',
    minWage: 500,
    maxWage: 50000,
    minAge: 16,
    maxAge: 65,
    classifyEdu: '',
    classifyWage: '',
    classifyAge: '',
    classifyCurrent: '',
    classifyJobCategory: '',
    classifyCityCategory: '',
    classifyCityOriginal: '',
    classifyMajor: '',
    classifyMajorOriginal: '',
    classifyMarriage: '',
    classifyResumeNature: '',
    classifyTrade: '',
    classifyLanguage: '',
    classifyLanguageLevel: '',
    classifyResumeTag: '',
    classifyJobTag: '',
    classifyExperience: '',
    classifyJobNature: '',
    classifyCompanyNature: '',
    classifyCompanyScale: '',
    classifyFeedback: '',
    classifyResumeStrongTag: '',
    classifyTipoffJob: '',
    classifyTipoffResume: '',
    resume: {
      module_list: {},
      field_rule: {},
      basic: {},
      contact: {},
      intention_list: [],
      work_list: [],
      education_list: [],
      project_list: [],
      training_list: [],
      language_list: [],
      certificate_list: [],
      img_list: []
    },
    config: '',
    dropdownSettrOption: [
      { text: '不限', value: 0 },
      { text: '3天内', value: 3 },
      { text: '7天内', value: 7 },
      { text: '15天内', value: 15 },
      { text: '30天内', value: 30 }
    ],
    userIminfo: {
      userid: '',
      user_token: ''
    }
  },
  mutations: {
    setLoginState (state, data) {
      state.LoginOrNot = data.whether
      state.LoginType = data.utype
      state.userToken = data.token
      state.userMobile = data.mobile
      state.userIminfo = data.userIminfo
    },
    // 更改是否显示头部的状态
    toggleHeadShow (state, data) {
      state.isHeadShow = data
    },
    clearCountDownFun (state, data) {
      state.sendSmsBtnDisabled = false
      state.sendSmsBtnText = '获取验证码'
      state.sendSmsBtnTextColor = '#2199ff'
      state.sendSmsInterval = 60
      clearInterval(state.sendSmsTimer)
    },
    // 倒计时
    countDownFun (state, data) {
      if (state.sendSmsInterval === 0) {
        state.sendSmsBtnDisabled = false
        state.sendSmsBtnText = '获取验证码'
        state.sendSmsBtnTextColor = '#2199ff'
        state.sendSmsInterval = 60
        clearInterval(state.sendSmsTimer)
      } else {
        state.sendSmsBtnDisabled = true
        state.sendSmsBtnText = `重发 ${state.sendSmsInterval} 秒`
        state.sendSmsBtnTextColor = '#c9c9c9'
        state.sendSmsInterval--
      }
    },
    clearCountDownFunEmail (state, data) {
      state.sendEmailBtnDisabled = false
      state.sendEmailBtnText = '获取验证码'
      state.sendEmailBtnTextColor = '#2199ff'
      state.sendEmailInterval = 60
      clearInterval(state.sendEmailTimer)
      clearInterval(state.sendSmsTimer)
    },
    // 倒计时
    countDownFunEmail (state, data) {
      if (state.sendEmailInterval === 0) {
        state.sendEmailBtnDisabled = false
        state.sendEmailBtnText = '获取验证码'
        state.sendEmailBtnTextColor = '#2199ff'
        state.sendEmailInterval = 60
        clearInterval(state.sendEmailTimer)
      } else {
        state.sendEmailBtnDisabled = true
        state.sendEmailBtnText = `重发 ${state.sendEmailInterval} 秒`
        state.sendEmailBtnTextColor = '#c9c9c9'
        state.sendEmailInterval--
      }
    },
    // 更新验证码相关状态
    setSendSmsState (state, data) {
      state.sendSmsMessage = data.data.message
    },
    setSendEmailState (state, data) {
      state.sendEmailMessage = data.data.message
    },
    // 设置分类
    setClassify (state, data) {
      if (data.fy === 'education') {
        state.classifyEdu = data.data
      } else if (data.fy === 'current') {
        state.classifyCurrent = data.data
      } else if (data.fy === 'jobcategory') {
        state.classifyJobCategory = data.data
      } else if (data.fy === 'citycategory') {
        state.classifyCityCategory = data.data
      } else if (data.fy === 'major') {
        state.classifyMajor = data.data
      } else if (data.fy === 'marriage') {
        state.classifyMarriage = data.data
      } else if (data.fy === 'resumeNature') {
        state.classifyResumeNature = data.data
      } else if (data.fy === 'trade') {
        state.classifyTrade = data.data
      } else if (data.fy === 'language') {
        state.classifyLanguage = data.data
      } else if (data.fy === 'languageLevel') {
        state.classifyLanguageLevel = data.data
      } else if (data.fy === 'resumeTag') {
        state.classifyResumeTag = data.data
      } else if (data.fy === 'experience') {
        state.classifyExperience = data.data
      } else if (data.fy === 'jobNature') {
        state.classifyJobNature = data.data
      } else if (data.fy === 'jobTag') {
        state.classifyJobTag = data.data
      } else if (data.fy === 'companyNature') {
        state.classifyCompanyNature = data.data
      } else if (data.fy === 'companyScale') {
        state.classifyCompanyScale = data.data
      } else if (data.fy === 'feedback') {
        state.classifyFeedback = data.data
      } else if (data.fy === 'resumeStrongTag') {
        state.classifyResumeStrongTag = data.data
      } else if (data.fy === 'tipoffJob') {
        state.classifyTipoffJob = data.data
      } else if (data.fy === 'tipoffResume') {
        state.classifyTipoffResume = data.data
      }
    },
    // 设置薪资分类
    setClassifyWage (state, data) {
      state.classifyWage = data
    },
    // 设置年龄分类
    setClassifyAge (state, data) {
      state.classifyAge = data
    },
    // 设置专业分类
    setClassifyMajor (state, data) {
      state.classifyMajorOriginal = data
    },
    // 设置地区分类
    setClassifyCity (state, data) {
      state.classifyCityOriginal = data
    },
    // 更新简历基本资料
    setBasicInfo (state, data) {
      state.resume.basic = data
    },
    // 更新简历模块列表
    setModuleList (state, data) {
      state.resume.module_list = data
    },
    // 更新简历字段列表
    setFieldList (state, data) {
      state.resume.field_rule = data
    },
    // 更新简历联系方式
    setResumeContact (state, data) {
      state.resume.contact = data
    },
    // 更新简历求职意向
    setIntentionList (state, data) {
      state.resume.intention_list = data
    },
    // 更新简历工作经历
    setWorkList (state, data) {
      state.resume.work_list = data
    },
    // 更新简历教育经历
    setEducationList (state, data) {
      state.resume.education_list = data
    },
    // 更新简历项目经历
    setProjectList (state, data) {
      state.resume.project_list = data
    },
    // 更新简历培训经历
    setTrainingList (state, data) {
      state.resume.training_list = data
    },
    // 更新简历语言能力
    setLanguageList (state, data) {
      state.resume.language_list = data
    },
    // 更新简历获得证书
    setCertificateList (state, data) {
      state.resume.certificate_list = data
    },
    // 更新简历作品
    setResumeImgList (state, data) {
      state.resume.img_list = data
    },
    // 设置网站配置
    setConfig (state, data) {
      state.config = data
    },
    // 设置平台
    setPlatform (state, data) {
      state.platform = data
    },
    // 设置聊天Token
    setImToken (state, data) {
      state.imToken = data
    },
    // 设置会话列表跳转会话页面参数
    setImShowParams (state, data) {
      state.imShowParams = data
    },
    // 设置聊天id
    setimChatid (state, data) {
      state.imChatid = data
    },
    // 设置用户聊天信息
    setImSelfAvatar (state, data) {
      state.imSelfAvatar = data
    },
    // 设置对方聊天用户信息
    setImTargetUserinfo (state, data) {
      state.imTargetUserinfo = data
    },
    // 连接 websock
    WebSocket_connect (state) {
      state.ws = new WebSocket('wss://imserv.v2.74cms.com')
    },
    // 更新消息列表
    setChatList (state, data) {
      function isJSON (str) {
        if (typeof str == 'string') {
          try {
            var obj = JSON.parse(str)
            if (typeof obj == 'object' && obj) {
              return true
            } else {
              return false
            }
          } catch (e) {
            return false
          }
        }
      }
      if (isJSON(data.data)) {
        state.imUnreaded = true
        // 当消息返回错误信息时
        if (data.error !== undefined) {
          this.$message({
            message: data.error,
            type: 'error'
          })
          return false
        }
        let mesData = JSON.parse(data.data)
        let message = isJSON(mesData.content) ? JSON.parse(mesData.content) : mesData.content
        // 屏蔽对方 / 被对方屏蔽
        if (mesData.type == 'isInBlacklist') {
          if (mesData.cancel_enable == 1) {
            state.isInBlackObj = {
              chatid: mesData.chatid,
              cancel_enable: mesData.cancel_enable,
              message: mesData.content
            }
          } else if (mesData.cancel_enable == 0) {
            state.isInBlackObj = {
              chatid: mesData.chatid,
              cancel_enable: mesData.cancel_enable,
              message: mesData.content
            }
          } else {
            state.isInBlackObj = {
              chatid: '',
              cancel_enable: '2',
              message: ''
            }
          }
          return false
        }
        // id 相同说明是同一个人 则需要处理数据
        if (state.imChatid == mesData.chatid) {
          if (mesData.type == 'return_receipt_one') {
            state.messagelist.forEach((element, index) => {
              if (element.messageid == message.messageid) {
                state.messagelist[index].readed = 1
              }
            })
          } else if (mesData.type == 'return_receipt_all') {
            state.messagelist.forEach((element, index) => {
              state.messagelist[index].readed = 1
            })
          } else {
            var addObj = {
              self_side: mesData.self_side,
              avatar: mesData.self_side == 1 ? state.imSelfAvatar : state.imTargetUserinfo.avatar,
              type: mesData.type,
              message: message,
              addtime: mesData.addtime,
              readed: 0,
              messageid: mesData.messageid
            }
            if (mesData.replace == 1) {
              state.messagelist.forEach((item) => {
                if (item.messageid == mesData.messageid) {
                  if (isJSON(mesData.content)) {
                    item.message = JSON.parse(mesData.content)
                  } else {
                    item.message = mesData.content
                  }
                }
              })
            } else {
              state.messagelist.push(addObj)
              if (mesData.self_side == 1) {
              } else {
                /**
                 * 发送回执
                 */
                if (router.currentRoute.name == 'ImShow') {
                  var msgObj = {
                    controller: 'SendReturnReceipt',
                    action: 'one',
                    args: {
                      token: state.imToken,
                      messageid: mesData.messageid
                    }
                  }
                  var msgStr = JSON.stringify(msgObj)
                  state.ws.send(msgStr)
                }
              }
            }
          }
        } else {
          // 不是同一个人 添加红点
          state.IsUpdataChatList += 1
        }
      }
    },
    // 设置消息列表
    setMessagelist (state, data) {
      state.messagelist = data
    },
    // 设置滚动的调状态
    setScrollState (state, data) {
      state.sendScrollState = data
    },
    // 设置聊天过程中被拉入黑名单参数
    setBlackObj (state, data) {
      state.isInBlackObj = data
    }
  },
  actions: {
    // 请求网站配置
    getConfig (context, value) {
      return new Promise((resolve, reject) => {
        service({
          method: 'GET',
          headers: {
            'user-token': this.state.userToken,
            platform: this.state.platform,
            'subsiteid': localStorage.getItem('qscms_subsiteid')
          },
          url: api.global_config,
          data: {}
        })
          .then((res) => {
            context.commit('setConfig', res.data.data)
            resolve(res.data.data)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },
    // 发送验证码
    sendSmsFun (context, value) {
      return new Promise((resolve, reject) => {
        service({
          method: 'POST',
          headers: {
            'user-token': context.state.userToken,
            platform: context.state.platform,
            'subsiteid': localStorage.getItem('qscms_subsiteid')
          },
          url: value.url,
          data: {
            mobile: value.mobile,
            utype: value.type,
            captcha: value.captcha
          }
        })
          .then((res) => {
            context.commit('setSendSmsState', res)
            if (parseInt(res.data.code) === 200) {
              context.commit('countDownFun')
              context.state.sendSmsTimer = setInterval(function () {
                context.commit('countDownFun')
              }, 1000)
            }
            resolve(res.data)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },
    // 发送邮箱验证码
    sendEmailFun (context, value) {
      return new Promise((resolve, reject) => {
        service({
          method: 'POST',
          headers: {
            'user-token': context.state.userToken,
            platform: context.state.platform,
            'subsiteid': localStorage.getItem('qscms_subsiteid')
          },
          url: value.url,
          data: {
            email: value.email,
            utype: value.type
          }
        })
          .then((res) => {
            context.commit('setSendEmailState', res)
            if (parseInt(res.data.code) === 200) {
              context.commit('countDownFunEmail')
              context.state.sendEmailTimer = setInterval(function () {
                context.commit('countDownFunEmail')
              }, 1000)
            }
            resolve(res.data)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },
    // 设置薪资分类
    getClassifyWage (context, value) {
      let minWage = context.state.minWage
      let maxWage = context.state.maxWage
      let wageArray = []
      let listArray = []
      for (let i = 0; i < 36; i++) {
        if (minWage < maxWage) {
          listArray = []
          let listWage = minWage
          for (let j = 0; j < 36; j++) {
            if (listWage <= maxWage) {
              if (listWage !== minWage) {
                listArray.push(listWage)
              }
              if (listWage >= 15000) {
                listWage += 5000
              } else {
                listWage += 500
              }
            }
          }
          wageArray[minWage] = listArray
          if (minWage >= 15000) {
            minWage += 5000
          } else {
            minWage += 500
          }
        }
      }
      context.commit('setClassifyWage', wageArray)
    },
    // 设置年龄分类
    getClassifyAge (context, value) {
      let minAge = context.state.minAge
      let maxAge = context.state.maxAge
      let ageArray = []
      let listArray = []
      for (let i = 16; i < 65; i++) {
        if (minAge < maxAge) {
          listArray = []
          let listAge = minAge
          for (let j = 0; j < 50; j++) {
            if (listAge <= maxAge) {
              if (listAge !== minAge) {
                listArray.push(listAge)
              }
              listAge += 1
            }
          }
          ageArray[minAge] = listArray
          minAge += 1
        }
      }
      context.commit('setClassifyAge', ageArray)
    },
    // 获取枚举数据
    getClassify (context, value) {
      return new Promise((resolve, reject) => {
        service({
          method: 'GET',
          headers: {
            'user-token': context.state.userToken,
            platform: context.state.platform,
            'subsiteid': localStorage.getItem('qscms_subsiteid')
          },
          url: api.classify,
          params: {type: value}
        })
          .then((res) => {
            if (parseInt(res.data.code) === 200) {
              let dataArray = JSON.parse(JSON.stringify(res.data.data))
              let commitArray = []
              if (value === 'jobcategory') {
                for (let province of dataArray) {
                  context.dispatch('makeChildren', province).then((res) => {
                    commitArray.push({
                      id: province.value,
                      text: province.label,
                      children: res
                    })
                  })
                }
              } else if (value === 'citycategory') {
                context.commit('setClassifyCity', dataArray)
                let provinceArray = {}
                let cityArray = {}
                let countyArray = {}
                for (let province of dataArray) {
                  provinceArray[province.value] = province.label
                  for (let city of province.children) {
                    cityArray[city.value] = city.label
                    for (let county of city.children) {
                      countyArray[county.value] = county.label
                    }
                  }
                }
                commitArray = {
                  province_list: provinceArray,
                  city_list: cityArray,
                  county_list: countyArray
                }
              } else if (value === 'major') {
                context.commit('setClassifyMajor', dataArray)
                let majorPArray = []
                let majorSArray = []
                for (let majorP of dataArray) {
                  majorSArray = []
                  for (let majorS of majorP.children) {
                    majorSArray.push(majorS.label)
                  }
                  majorPArray[majorP.label] = majorSArray
                }
                commitArray = majorPArray
              } else {
                Object.keys(dataArray).forEach(function (key) {
                  commitArray.push({
                    id: dataArray[key].id,
                    text: dataArray[key].name
                  })
                })
              }
              context.commit('setClassify', {
                fy: value,
                data: commitArray
              })
            }
            resolve(res.data)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },
    makeChildren (context, value) {
      let areaArray = []
      for (let area of value.children) {
        context.dispatch('makeChildrenLast', area.children).then((res) => {
          areaArray.push({
            id: area.value,
            text: area.label,
            children: res
          })
        })
      }
      return areaArray
    },
    makeChildrenLast (context, value) {
      let areaArray = []
      for (let item of value) {
        areaArray.push({
          id: item.value,
          text: item.label
        })
      }
      return areaArray
    },
    // 更新简历基本资料
    setBasicInfo (context, value) {
      context.commit('setBasicInfo', value)
    },
    // 更新简历模块列表
    setModuleList (context, value) {
      context.commit('setModuleList', value)
    },
    // 更新简历字段列表
    setFieldList (context, value) {
      context.commit('setFieldList', value)
    },
    // 更新简历联系方式
    setResumeContact (context, value) {
      context.commit('setResumeContact', value)
    },
    // 更新简历求职意向
    setIntentionList (context, value) {
      context.commit('setIntentionList', value)
    },
    // 更新简历工作经历
    setWorkList (context, value) {
      context.commit('setWorkList', value)
    },
    // 更新简历教育经历
    setEducationList (context, value) {
      context.commit('setEducationList', value)
    },
    // 更新简历项目经历
    setProjectList (context, value) {
      context.commit('setProjectList', value)
    },
    // 更新简历培训经历
    setTrainingList (context, value) {
      context.commit('setTrainingList', value)
    },
    // 更新简历语言能力
    setLanguageList (context, value) {
      context.commit('setLanguageList', value)
    },
    // 更新简历获得证书
    setCertificateList (context, value) {
      context.commit('setCertificateList', value)
    },
    // 更新简历作品
    setResumeImgList (context, value) {
      context.commit('setResumeImgList', value)
    },
    initWebSocket ({commit, state}, value) {
      commit('WebSocket_connect')
      state.ws.onopen = function () {
        var msgObj = {
          controller: 'Connect',
          action: 'index',
          args: {
            token: value
          }
        }
        var msgStr = JSON.stringify(msgObj)
        state.ws.send(msgStr)
        console.log('连接成功')
      }
      state.ws.onmessage = function (e) {
        // commit('WEBSOCKET_REIVE', e)
        commit('setChatList', e)
      }
      state.ws.onerror = function (error) {
        console.log('连接失败：', error)
        store.commit('initWebSocket', state.imToken)
      }
      state.ws.onclose = function (e) {
        console.log('websocket 断开: ' + e.code + ' ' + e.reason + ' ' + e.wasClean)
        store.commit('initWebSocket', state.imToken)
        console.log('连接关闭', e)
      }
    },
    // WebSocket 发送消息
    webSocket_send ({state}, value) {
      var msgObj = JSON.stringify(value)
      if (
        value.controller == 'SendInvite' ||
        value.controller == 'SendWechat' ||
        value.controller == 'SendMobile' ||
        value.controller == 'SendMap' ||
        value.controller == 'SendResume' ||
        value.controller == 'SendJob' ||
        value.controller == 'SendText' ||
        value.controller == 'SendImage') {
        state.sendScrollState = true
      }
      state.ws.send(msgObj)
    }
  },
  modules: {},
  getters: {},
  plugins: [ persistedState() ]
})
export default store
// store.commit("getConfig", "");
