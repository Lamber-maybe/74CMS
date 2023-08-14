import Vue from 'vue'
import Vuex from 'vuex'
import persistedState from 'vuex-persistedstate'
import axios from 'axios'
import api from '@/api'
import VueCookies from 'vue-cookies'
Vue.use(Vuex)
const service = axios.create({
  baseURL: window.global.RequestBaseUrl,
  withCredentials: true, // 跨域支持发送cookie
  timeout: window.global.RequestTimeout // 请求超时时间
})
const store = new Vuex.Store({
  state: {
    videoShowPlay: false,
    isHeadShow: true, // 是否显示头部
    LoginOrNot: false, // 用户是否登录
    LoginType: 0, // 用户类型-1为企业 2位个人
    userToken: 'user_token',
    userMobile: '',
    platform: 'mobile',
    sendSmsInterval: 60, // 发送验证码间隔时间，以秒为单位
    sendSmsBtnText: '获取验证码',
    sendSmsBtnDisabled: false,
    sendEmailInterval: 60, // 发送验证码间隔时间，以秒为单位
    sendEmailBtnText: '获取验证码',
    sendEmailBtnDisabled: false,
    sendSmsTimer: '',
    sendSmsMessage: '',
    sendEmailTimer: '',
    sendEmailMessage: '',
    minWage: 500,
    maxWage: 15000,
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
      state.sendSmsInterval = 60
      clearInterval(state.sendSmsTimer)
    },
    // 倒计时
    countDownFun (state, data) {
      if (state.sendSmsInterval === 0) {
        state.sendSmsBtnDisabled = false
        state.sendSmsBtnText = '获取验证码'
        state.sendSmsInterval = 60
        clearInterval(state.sendSmsTimer)
      } else {
        state.sendSmsBtnDisabled = true
        state.sendSmsBtnText = `重发 ${state.sendSmsInterval} 秒`
        state.sendSmsInterval--
      }
    },
    clearCountDownFunEmail (state, data) {
      state.sendEmailBtnDisabled = false
      state.sendEmailBtnText = '获取验证码'
      state.sendEmailInterval = 60
      clearInterval(state.sendEmailTimer)
      clearInterval(state.sendSmsTimer)
    },
    // 倒计时
    countDownFunEmail (state, data) {
      if (state.sendEmailInterval === 0) {
        state.sendEmailBtnDisabled = false
        state.sendEmailBtnText = '获取验证码'
        state.sendEmailInterval = 60
        clearInterval(state.sendEmailTimer)
      } else {
        state.sendEmailBtnDisabled = true
        state.sendEmailBtnText = `重发 ${state.sendEmailInterval} 秒`
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
            'subsiteid': VueCookies.get('qscms_subsiteid')
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
            'subsiteid': VueCookies.get('qscms_subsiteid')
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
            'subsiteid': VueCookies.get('qscms_subsiteid')
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
      for (let i = 0; i < 50; i++) {
        if (minWage < maxWage) {
          listArray = []
          let listWage = minWage
          for (let j = 0; j < 50; j++) {
            if (listWage <= maxWage) {
              if (listWage !== minWage) {
                listArray.push(listWage)
              }
              listWage += 500
            }
          }
          wageArray[minWage] = listArray
          minWage += 500
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
            'subsiteid': VueCookies.get('qscms_subsiteid')
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
    }
  },
  modules: {},
  getters: {},
  plugins: [ persistedState() ]
})
export default store
// store.commit("getConfig", "");
