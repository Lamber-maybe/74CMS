import Layout from '@/layout'

const businessRouter = {
  path: '/business',
  component: Layout,
  redirect: 'noRedirect',
  alwaysShow: true,
  name: 'business',
  meta: {
    title: '业务',
    icon: 'example',
    access: 'mobile'
  },
  children: [{
    path: '/business/company',
    name: 'businessCompany',
    component: () =>
      import ('@/views/business/company'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '企业业务管理',
      access: 'businessCompany'
    },
    children: [{
      path: '/business/company/setmeal',
      name: 'businessCompanySetmeal',
      component: () =>
        import ('@/views/business/company/setmeal'),
      meta: {
        title: '套餐管理',
        access: 'businessCompanySetmeal'
      }
    },
    {
      path: '/business/company/point',
      name: 'businessCompanyPoint',
      component: () =>
        import ('@/views/business/company/point'),
      meta: {
        title: '积分管理',
        access: 'businessCompanyPoint'
      }
    }, {
      path: '/business/company/order',
      name: 'businessCompanyOrder',
      component: () =>
        import ('@/views/business/company/order'),
      meta: {
        title: '订单管理',
        access: 'businessCompanyOrder'
      }
    },
    {
      path: '/business/company/promotion',
      name: 'businessCompanyPromotion',
      component: () =>
        import ('@/views/business/company/promotion'),
      meta: {
        title: '企业推广',
        access: 'businessCompanyPromotion'
      }
    },
    {
      path: '/business/company/report',
      name: 'businessCompanyReport',
      component: () =>
        import ('@/views/business/company/report'),
      meta: {
        title: '实地认证',
        access: 'businessCompanyReport'
      }
    },
    {
      path: '/business/company/report/add',
      name: 'businessCompanyReportAdd',
      component: () =>
        import ('@/views/business/company/report/add'),
      meta: {
        title: '添加实地认证',
        access: 'businessCompanyReportAdd',
        activeMenu: '/business/company/report'
      },
      hidden: true
    },
    {
      path: '/business/company/report/edit',
      name: 'businessCompanyReportEdit',
      component: () =>
        import ('@/views/business/company/report/edit'),
      meta: {
        title: '编辑实地认证',
        access: 'businessCompanyReportEdit',
        activeMenu: '/business/company/report'
      },
      hidden: true
    }
    ]
  },
  {
    path: '/business/personal',
    name: 'businessPersonal',
    component: () =>
      import ('@/views/business/personal'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '个人业务管理',
      access: 'businessPersonal'
    },
    children: [{
      path: '/business/personal/order',
      name: 'businessPersonalOrder',
      component: () =>
        import ('@/views/business/personal/order'),
      meta: {
        title: '订单管理',
        access: 'businessPersonalOrder'
      }
    },
    {
      path: '/business/personal/point',
      name: 'businessPersonalPoint',
      component: () =>
        import ('@/views/business/personal/point'),
      meta: {
        title: '积分管理',
        access: 'businessPersonalPoint'
      }
    },
    {
      path: '/business/personal/promotion',
      name: 'businessPersonalPromotion',
      component: () =>
        import ('@/views/business/personal/promotion'),
      meta: {
        title: '简历推广',
        access: 'businessPersonalPromotion'
      }
    },
    {
      path: '/business/personal/entrust',
      name: 'businessPersonalEntrust',
      component: () =>
        import ('@/views/business/personal/entrust'),
      meta: {
        title: '委托投递',
        access: 'businessPersonalEntrust'
      }
    }
    ]
  }
  ]
}
export default businessRouter
