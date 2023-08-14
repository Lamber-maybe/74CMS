import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

/**
 * 重写路由的push方法
 */
const routerPush = VueRouter.prototype.push
VueRouter.prototype.push = function push (location) {
  return routerPush.call(this, location).catch((error) => error)
}

const routes = [
  {
    path: '/login',
    name: 'login',
    component: () => import('../views/Login'),
    meta: {
      title: '登录',
      keepAlive: false,
      loginCheck: false
    }
  },
  {
    path: '/login/oauth/weixin',
    name: 'oauthWeixin',
    component: () => import('../views/Oauth/weixin'),
    meta: {
      title: '微信授权登录',
      keepAlive: false,
      loginCheck: false
    }
  },
  {
    path: '/',
    redirect: '/index',
    meta: {
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/index',
    name: 'index',
    component: () => import('../views/Index'),
    meta: {
      title: '控制台',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/tipoff',
    name: 'tipoff',
    component: () => import('../views/Tipoff'),
    meta: {
      title: '举报信息',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/feedback',
    name: 'feedback',
    component: () => import('../views/Feedback'),
    meta: {
      title: '意见建议',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/user',
    name: 'userIndex',
    component: () => import('../views/User/index'),
    meta: {
      title: '会员管理',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/business',
    name: 'businessIndex',
    component: () => import('../views/Business/index'),
    meta: {
      title: '业务管理',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/config',
    name: 'configIndex',
    component: () => import('../views/Config/index'),
    meta: {
      title: '系统工具',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/config/admin',
    name: 'configAdmin',
    component: () => import('../views/Config/admin'),
    meta: {
      title: '账号信息',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/stats',
    name: 'statsIndex',
    component: () => import('../views/Stats/index'),
    meta: {
      title: '数据统计',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/company_list/:type',
    name: 'companyList',
    component: () => import('../views/Company/companyList'),
    meta: {
      title: '企业管理',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/company_auth/:id',
    name: 'companyAuth',
    component: () => import('../views/Company/companyAuth'),
    meta: {
      title: '企业认证',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/companyimg_list',
    name: 'companyimgList',
    component: () => import('../views/Company/imgList'),
    meta: {
      title: '企业风采',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/cancellation',
    name: 'cancellationList',
    component: () => import('../views/Company/cancellationList'),
    meta: {
      title: '企业注销申请',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/job_list/:type',
    name: 'jobList',
    component: () => import('../views/Company/jobList'),
    meta: {
      title: '职位管理',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/resume_list/:type',
    name: 'resumeList',
    component: () => import('../views/Personal/resumeList'),
    meta: {
      title: '简历管理',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/photo_list',
    name: 'photoList',
    component: () => import('../views/Personal/photoList'),
    meta: {
      title: '照片作品',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/user_list/:type',
    name: 'userList',
    component: () => import('../views/User/userList'),
    meta: {
      title: '会员管理',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/user/:type/:uid',
    name: 'userShow',
    component: () => import('../views/User/userShow'),
    meta: {
      title: '会员详情',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/order/:utype',
    name: 'orderList',
    component: () => import('../views/Business/orderList'),
    meta: {
      title: '订单管理',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/order/detail/:id',
    name: 'orderShow',
    component: () => import('../views/Business/orderShow'),
    meta: {
      title: '订单详情',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/points',
    name: 'pointsEdit',
    component: () => import('../views/Business/pointsEdit'),
    meta: {
      title: '积分充值',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/setmeal',
    name: 'setmealList',
    component: () => import('../views/Business/setmealList'),
    meta: {
      title: '套餐管理',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/setmeal/:uid',
    name: 'setmealEdit',
    component: () => import('../views/Business/setmealEdit'),
    meta: {
      title: '编辑套餐',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/setmeal_add',
    name: 'setmealAdd',
    component: () => import('../views/Business/setmealAdd'),
    meta: {
      title: '开通套餐',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/promotion_add',
    name: 'promotionAdd',
    component: () => import('../views/Business/promotionAdd'),
    meta: {
      title: '新增推广',
      keepAlive: false,
      loginCheck: true
    }
  },
  {
    path: '/promotion_resume_add',
    name: 'promotionResumeAdd',
    component: () => import('../views/Business/promotionResumeAdd'),
    meta: {
      title: '新增推广',
      keepAlive: false,
      loginCheck: true
    }
  }
]

const router = new VueRouter({
  mode: 'hash',
  base: process.env.BASE_URL,
  routes
})

export default router
