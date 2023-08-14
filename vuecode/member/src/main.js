import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import './assets/css/reset.css'
import './assets/css/base.css'
import '@/permission' // permission control
import CompanyPublicTitle from './components/company/PublicTitle'
import PersonalPublicTitle from './components/personal/PublicTitle'
import MenuNav from './components/MenuNav'
import Pagination from './components/Pagination'
import { message } from './utils/resetMessage'

Vue.config.productionTip = false
Vue.use(ElementUI)
Vue.prototype.$message = message;
Vue.component('company-title', CompanyPublicTitle)
Vue.component('personal-title', PersonalPublicTitle)
Vue.component('menu-nav', MenuNav)
Vue.component('pagination', Pagination)

router.beforeEach(async(to, from, next) => {
    // 根据路由改变个人和企业会员中心背景色
    if (to.path.includes('/personal') || to.path.includes('/member')) {
        document.querySelector('body').setAttribute('style', 'background-color:#f3f3f3')
    } else {
        document.querySelector('body').setAttribute('style', 'background-color:#ffffff')
    }
    const hasGetConfig = store.state.config
    if (!hasGetConfig) {
        try {
            await store.dispatch('getConfig')
        } catch (error) {
            console.log(error)
            message.error(error.message || 'Has Error')
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