// require("es6-promise").polyfill();
import 'babel-polyfill'
import Vue from 'vue'

import 'normalize.css/normalize.css' // A modern alternative to CSS resets

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
// import locale from "element-ui/lib/locale/lang/en"; // lang i18n

import '@/styles/index.scss' // global css

import App from './App'
import router from './router'
import store from './store'
import '@/icons' // icon
import '@/permission' // permission control
import VueClipboard from 'vue-clipboard2'

import {Base64} from 'js-base64'
Vue.prototype.$Base64 = Base64;


// set ElementUI lang to EN
// Vue.use(ElementUI, { locale })
// 如果想要中文版 element-ui，按如下方式声明
Vue.use(ElementUI)
Vue.use(VueClipboard)


import '../public/ueditor/ueditor.config'
import '../public/ueditor/ueditor.all'
import '../public/ueditor/lang/zh-cn/zh-cn'

Vue.config.productionTip = false

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
