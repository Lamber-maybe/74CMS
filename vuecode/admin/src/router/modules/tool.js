import Layout from '@/layout'

const toolRouter = {
  path: '/tool',
  component: Layout,
  redirect: 'noRedirect',
  alwaysShow: true,
  name: 'tool',
  meta: {
    title: '工具',
    icon: 'example',
    access: 'tool'
  },
  children: [
    {
      path: '/tool/coupon',
      name: 'coupon',
      component: () =>
        import ('@/views/tool/coupon'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: {
        title: '优惠券',
        access: 'coupon'
      },
      children: [{
        path: '/tool/coupon/list',
        name: 'couponList',
        component: () =>
          import ('@/views/tool/coupon/list'),
        meta: {
          title: '优惠券列表',
          access: 'couponList'
        }
      },
      {
        path: '/tool/coupon/send',
        name: 'couponSend',
        component: () =>
          import ('@/views/tool/coupon/send'),
        meta: {
          title: '发放优惠券',
          access: 'couponSend'
        }
      },
      {
        path: '/tool/coupon/log',
        name: 'couponLog',
        component: () =>
          import ('@/views/tool/coupon/log'),
        meta: {
          title: '发放记录',
          access: 'couponLog'
        }
      },
      {
        path: '/tool/coupon/config',
        name: 'couponCnofig',
        component: () =>
          import ('@/views/tool/coupon/config'),
        meta: {
          title: '优惠券设置',
          access: 'couponCnofig'
        }
      }
      ]
    },
    {
      path: '/tool/cron',
      name: 'cron',
      component: () =>
        import ('@/views/tool/cron'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: {
        title: '计划任务',
        access: 'cron'
      },
      children: [{
        path: '/tool/cron/list',
        name: 'cronList',
        component: () =>
          import ('@/views/tool/cron/list'),
        meta: {
          title: '计划任务列表',
          access: 'cronList'
        }
      },
      {
        path: '/tool/cron/log',
        name: 'cronLog',
        component: () =>
          import ('@/views/tool/cron/log'),
        meta: {
          title: '执行日志',
          access: 'cronLog'
        }
      }
      ]
    },
    {
      path: '/tool/export',
      name: 'export',
      component: () =>
        import ('@/views/tool/export'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: {
        title: '数据导出',
        access: 'export'
      },
      children: [{
        path: '/tool/export/job',
        name: 'exportJob',
        component: () =>
          import ('@/views/tool/export/job'),
        meta: {
          title: '职位信息导出',
          access: 'exportJob'
        }
      },
      {
        path: '/tool/export/company',
        name: 'exportCompany',
        component: () =>
          import ('@/views/tool/export/company'),
        meta: {
          title: '企业信息导出',
          access: 'exportCompany'
        }
      },
      {
        path: '/tool/export/resume',
        name: 'exportResume',
        component: () =>
          import ('@/views/tool/export/resume'),
        meta: {
          title: '简历信息导出',
          access: 'exportResume'
        }
      }
      ]
    },
    {
      path: '/tool/linksubmit',
      name: 'linksubmit',
      component: () =>
        import ('@/views/tool/linksubmit'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: {
        title: '百度推送',
        access: 'linksubmit'
      },
      children: [{
        path: '/tool/linksubmit/basic',
        name: 'linksubmitBasic',
        component: () =>
          import ('@/views/tool/linksubmit/basic'),
        meta: {
          title: 'URL推送',
          access: 'linksubmitBasic'
        }
      }]
    },
    {
      path: '/tool/im',
      name: 'im',
      component: () =>
        import ('@/views/tool/im'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: {
        title: '即时通讯',
        access: 'im'
      },
      children: [{
        path: '/tool/im/basic',
        name: 'imBasic',
        component: () =>
          import ('@/views/tool/im/basic'),
        meta: {
          title: '基本配置',
          access: 'imBasic'
        }
      },
      {
        path: '/tool/im/quickmsg',
        name: 'imQuickmsg',
        component: () =>
          import ('@/views/tool/im/quickmsg'),
        meta: {
          title: '快捷语设置',
          access: 'imQuickmsg'
        }
      }
      ]
    },
    {
      path: '/tool/market',
      name: 'market',
      component: () =>
        import ('@/views/tool/market'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: {
        title: '消息群发',
        access: 'market'
      },
      children: [{
        path: '/tool/market/condition',
        name: 'marketCondition',
        component: () =>
          import ('@/views/tool/market/condition'),
        meta: {
          title: '按筛选条件群发',
          access: 'marketCondition'
        }
      },
      {
        path: '/tool/market/member',
        name: 'marketMember',
        component: () =>
          import ('@/views/tool/market/member'),
        meta: {
          title: '按指定会员发送',
          access: 'marketMember'
        }
      },
      {
        path: '/tool/market/remind',
        name: 'marketRemind',
        component: () =>
          import ('@/views/tool/market/remind'),
        meta: {
          title: '休眠用户提醒',
          access: 'marketRemind'
        }
      },
      {
        path: '/tool/market/setmeal',
        name: 'marketSetmeal',
        component: () =>
          import ('@/views/tool/market/setmeal'),
        meta: {
          title: '企业过期套餐提醒',
          access: 'marketSetmeal'
        }
      },
      {
        path: '/tool/market/list',
        name: 'marketList',
        component: () =>
          import ('@/views/tool/market/list'),
        meta: {
          title: '发送任务列表',
          access: 'marketList'
        }
      }
      ]
    },
    {
      path: '/tool/nologin_remind',
      name: 'nologinRemind',
      component: () =>
        import ('@/views/tool/nologin_remind'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: {
        title: '休眠用户提醒',
        access: 'nologinRemind'
      },
      children: [{
        path: '/tool/nologin_remind/basic',
        name: 'nologinRemindBasic',
        component: () =>
          import ('@/views/tool/nologin_remind/basic'),
        meta: {
          title: '基本配置',
          access: 'nologinRemindBasic'
        }
      }]
    }
  ]
}
export default toolRouter
