import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import 'amfe-flexible'
import './assets/css/base.css'
import './assets/css/reset.css'
import '@/permission' // permission control
import { Icon, Cell, CellGroup, Button, Dialog, Popup, Field, Form, Switch, DatetimePicker, Toast,Uploader,Skeleton,List,Empty,Overlay,Loading,ActionSheet,RadioGroup, Radio } from 'vant'
import BottomNav from './components/BottomNav'
import Head from './components/Head'
import Search from './components/Search'
import md5 from 'js-md5';
Vue.prototype.$md5 = md5;
Vue.config.productionTip = false

Vue.use(Icon)
Vue.use(Cell)
Vue.use(CellGroup)
Vue.use(Button)
Vue.use(Dialog)
Vue.use(Popup)
Vue.use(Field)
Vue.use(Form)
Vue.use(Switch)
Vue.use(DatetimePicker)
Vue.use(Toast)
Vue.use(Uploader)
Vue.use(Skeleton)
Vue.use(List)
Vue.use(Empty)
Vue.use(Overlay)
Vue.use(Loading)
Vue.use(ActionSheet)
Vue.use(RadioGroup)
Vue.use(Radio)
// 全局组件-头部
Vue.component('Head', Head)
// 全局组件-底部导航
Vue.component('BottomNav', BottomNav)
// 全局组件-搜索s
Vue.component('Search', Search)

router.beforeEach(async (to, from, next) => {
  if (to.meta.title) {
    document.title = to.meta.title
  }
  next()
})

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
