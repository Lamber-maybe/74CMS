import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'
if (store === undefined){
  console.log(store)
}
Vue.use(Router)

/* Layout */
import Layout from '@/layout'
/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
		roles: ['admin','editor']    control the page roles (you can set multiple roles)
		title: 'title'               the name show in sidebar and breadcrumb (recommend set)
		icon: 'svg-name'             the icon show in the sidebar
		breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
		activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
	}
 */
/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true,
    meta: { title: '登录' }
  },
  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true,
    meta: { title: '404' }
  },
  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    name: 'Dashboard',
    meta: { title: '工作台' },
    children: [{
      path: '/dashboard',
      name: 'welcome',
      component: () => import('@/views/dashboard/index'),
      meta: { title: '管理中心', icon: 'dashboard' }
    },
    {
      path: '/corpwechat/personal',
      name: 'corpwechatPersonal',
      hidden: true,
      component: () => import('@/views/corpwechat/personal/index'),
      meta: { title: '个人设置', access: 'corpwechatPersonal' }
    }]
  }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  base: process.env.BASE_URL,
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()
// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
