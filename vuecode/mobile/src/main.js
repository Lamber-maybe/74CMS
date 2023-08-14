import Vue from 'vue'
import router from './router'
import store from './store'
import App from './App'
import 'amfe-flexible'
import './assets/css/base.css'
import {
  Button,
  Checkbox,
  CheckboxGroup,
  Field,
  Form,
  RadioGroup,
  Radio,
  Calendar,
  Picker,
  Popup,
  Area,
  DropdownMenu,
  DropdownItem,
  Dialog,
  Switch,
  Search,
  Tag,
  Uploader,
  Col,
  Row,
  CountDown,
  Progress,
  Slider,
  ActionSheet,
  Toast,
  Notify,
  DatetimePicker,
  List,
  Empty,
  PullRefresh,
  Skeleton,
  Cell,
  CellGroup,
  Loading,
  NoticeBar,
  Step,
  Steps,
  Divider
} from 'vant'
import Head from './components/Head'
import BottomNav from './components/BottomNav'
import TopSearch from './components/TopSearch'
import VueAwesomeSwiper from 'vue-awesome-swiper'
import 'swiper/css/swiper.min.css'
import VueClipboard from 'vue-clipboard2'

// 重写表单样式
import './assets/css/resetForm.css'
import '@/permission' // permission control

// title插件
import VueWechatTitle from 'vue-wechat-title'
Vue.use(VueWechatTitle)
Vue.use(VueClipboard)

Vue.config.productionTip = false
Vue.config.devtools = true

Vue.use(Button)
Vue.use(Checkbox)
Vue.use(CheckboxGroup)
Vue.use(Field)
Vue.use(Form)
Vue.use(RadioGroup)
Vue.use(Radio)
Vue.use(Calendar)
Vue.use(Picker)
Vue.use(Popup)
Vue.use(Area)
Vue.use(DropdownMenu)
Vue.use(DropdownItem)
Vue.use(Dialog)
Vue.use(Switch)
Vue.use(Search)
Vue.use(Tag)
Vue.use(Uploader)
Vue.use(Col)
Vue.use(Row)
Vue.use(CountDown)
Vue.use(Progress)
Vue.use(Slider)
Vue.use(ActionSheet)
Vue.use(DatetimePicker)
Vue.use(VueAwesomeSwiper)
Vue.use(Toast)
Vue.use(Notify)
Vue.use(List)
Vue.use(Empty)
Vue.use(PullRefresh)
Vue.use(Skeleton)
Vue.use(Cell)
Vue.use(CellGroup)
Vue.use(Loading)
Vue.use(NoticeBar)
Vue.use(Step)
Vue.use(Steps)
Vue.use(Divider)

// 全局组件-头部
Vue.component('Head', Head)
// 全局组件-底部导航
Vue.component('BottomNav', BottomNav)
// 全局组件-顶部搜索
Vue.component('TopSearch', TopSearch)

router.beforeEach(async (to, from, next) => {
  const globalConfig = store.state.config
  if (!globalConfig) {
    try {
      await store.dispatch('getConfig')
    } catch (error) {
      console.log(error)
      Notify(error.message || 'Has Error')
      next(false)
    }
  }

  if (to.meta.title) {
    document.title =
      to.meta.title + (store.state.config.sitename !== undefined ? ' - ' + store.state.config.sitename : '')
  }
  next()
})

new Vue({
  router,
  store,
  render: (h) => h(App)
}).$mount('#app')
