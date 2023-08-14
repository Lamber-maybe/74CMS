import Layout from '@/layout'

const systemRouter = {
  path: '/sys',
  component: Layout,
  redirect: 'noRedirect',
  alwaysShow: true,
  name: 'sys',
  meta: {
    title: '系统',
    icon: 'example',
    access: 'sys'
  },
  children: [{
    path: '/sys/basic',
    name: 'sysBasic',
    component: () =>
      import('@/views/sys/basic'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '基础配置',
      access: 'sysBasic'
    },
    children: [{
      path: '/sys/basic/config',
      name: 'sysBasicConfig',
      component: () =>
        import('@/views/sys/basic/config'),
      meta: {
        title: '系统配置',
        access: 'sysBasicConfig'
      }
    },
    {
      path: '/sys/basic/customize',
      name: 'sysBasicCustomize',
      component: () =>
        import('@/views/sys/basic/customize'),
      meta: {
        title: '自定义数据配置',
        access: 'sysBasicCustomize'
      }
    },
    {
      path: '/sys/basic/recommend',
      name: 'sysBasicRecommend',
      component: () =>
        import('@/views/sys/basic/recommend'),
      meta: {
        title: '精准推荐',
        access: 'sysBasicRecommend'
      }
    },
    {
      path: '/sys/basic/sms',
      name: 'sysBasicSms',
      component: () =>
        import('@/views/sys/basic/sms'),
      meta: {
        title: '短信配置',
        access: 'sysBasicSms'
      }
    },
    {
      path: '/sys/basic/mail',
      name: 'sysBasicMail',
      component: () =>
        import('@/views/sys/basic/mail'),
      meta: {
        title: '邮箱配置',
        access: 'sysBasicMail'
      }
    },
    {
      path: '/sys/basic/pay',
      name: 'sysBasicPay',
      component: () =>
        import('@/views/sys/basic/pay'),
      meta: {
        title: '支付配置',
        access: 'sysBasicPay'
      }
    },
    {
      path: '/sys/basic/safe',
      name: 'sysBasicSafe',
      component: () =>
        import('@/views/sys/basic/safe'),
      meta: {
        title: '安全配置',
        access: 'sysBasicSafe'
      }
    },
    {
      path: '/sys/basic/extend',
      name: 'sysBasicExtend',
      component: () =>
        import('@/views/sys/basic/extend'),
      meta: {
        title: '系统扩展配置',
        access: 'sysBasicExtend'
      }
    },
    {
      path: '/sys/basic/notify',
      name: 'sysBasicNotify',
      component: () =>
        import('@/views/sys/basic/notify'),
      meta: {
        title: '消息通知',
        access: 'sysBasicNotify'
      }
    },
    {
      path: '/sys/basic/page',
      name: 'sysBasicPage',
      component: () =>
        import('@/views/sys/basic/page/index'),
      meta: {
        title: '页面管理',
        access: 'sysBasicPage'
      }
    },
    {
      path: '/sys/basic/page/edit',
      name: 'sysBasicPageEdit',
      component: () =>
        import('@/views/sys/basic/page/edit'),
      meta: {
        title: '页面管理',
        access: 'sysBasicPage',
        activeMenu: '/sys/basic/page'
      },
      hidden: true
    },
    {
      path: '/sys/basic/nav',
      name: 'sysBasicNav',
      component: () =>
        import('@/views/sys/basic/nav/index'),
      meta: {
        title: '导航设置',
        access: 'sysBasicNav'
      }
    },
    {
      path: '/sys/basic/nav/add',
      name: 'sysBasicNavAdd',
      component: () =>
        import('@/views/sys/basic/nav/add'),
      meta: {
        title: '添加导航',
        access: 'sysBasicNav',
        activeMenu: '/sys/basic/nav'
      },
      hidden: true
    },
    {
      path: '/sys/basic/nav/edit',
      name: 'sysBasicNavEdit',
      component: () =>
        import('@/views/sys/basic/nav/edit'),
      meta: {
        title: '编辑导航',
        access: 'sysBasicNav',
        activeMenu: '/sys/basic/nav'
      },
      hidden: true
    },
    {
      path: '/sys/basic/cooperation',
      name: 'sysBasicCooperation',
      component: () =>
        import('@/views/sys/basic/cooperation'),
      meta: {
        title: '合作账号',
        access: 'sysBasicCooperation'
      }
    },
    {
      path: '/sys/basic/serviceol',
      name: 'sysBasicServiceOl',
      component: () =>
        import('@/views/sys/basic/serviceol'),
      meta: {
        title: '在线客服',
        access: 'sysBasicServiceOl'
      }
    },
    {
      path: '/sys/basic/serviceol/add',
      name: 'sysBasicServiceOlAdd',
      component: () =>
        import('@/views/sys/basic/serviceol/add'),
      meta: {
        title: '新增在线客服',
        access: 'sysBasicServiceOl'
      },
      hidden: true
    },
    {
      path: '/sys/basic/serviceol/edit',
      name: 'sysBasicServiceOlEdit',
      component: () =>
        import('@/views/sys/basic/serviceol/edit'),
      meta: {
        title: '修改在线客服',
        access: 'sysBasicServiceOl'
      },
      hidden: true
    },
    {
      path: '/sys/basic/tpl',
      name: 'sysBasicTpl',
      component: () =>
        import('@/views/sys/basic/tpl'),
      meta: {
        title: '模板管理',
        access: 'sysBasicTpl'
      }
    }
    ]
  },
  {
    path: '/sys/category',
    name: 'sysCategory',
    component: () =>
      import('@/views/sys/category'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '分类配置',
      access: 'sysCategory'
    },
    children: [{
      path: '/sys/category/district',
      name: 'sysCategoryDistrict',
      component: () =>
        import('@/views/sys/category/district'),
      meta: {
        title: '地区分类',
        access: 'sysCategoryDistrict'
      }
    },
    {
      path: '/sys/category/jobcategory',
      name: 'sysCategoryJobcategory',
      component: () =>
        import('@/views/sys/category/jobcategory'),
      meta: {
        title: '职位分类',
        access: 'sysCategoryJobcategory'
      }
    },
    {
      path: '/sys/category/major',
      name: 'sysCategoryMajor',
      component: () =>
        import('@/views/sys/category/major'),
      meta: {
        title: '专业分类',
        access: 'sysCategoryMajor'
      }
    },
    {
      path: '/sys/category/group',
      name: 'sysCategoryGroup',
      component: () =>
        import('@/views/sys/category/group'),
      meta: {
        title: '其他分类组',
        access: 'sysCategoryGroup'
      }
    },
    {
      path: '/sys/category/grouplist',
      name: 'sysCategoryGroupList',
      component: () =>
        import('@/views/sys/category/group/list'),
      meta: {
        title: '其他分类管理',
        access: 'sysCategoryGroup',
        activeMenu: '/sys/category/group'
      },
      hidden: true
    }
    ]
  },
  {
    path: '/sys/company',
    name: 'sysCompany',
    component: () =>
      import('@/views/sys/company'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '企业业务配置',
      access: 'sysCompany'
    },
    children: [{
      path: '/sys/company/base',
      name: 'sysCompanyBase',
      component: () =>
        import('@/views/sys/company/base'),
      meta: {
        title: '基础配置',
        access: 'sysCompanyBase'
      }
    },
    {
      path: '/sys/company/setmeal',
      name: 'sysCompanySetmeal',
      component: () =>
        import('@/views/sys/company/setmeal'),
      meta: {
        title: '套餐配置',
        access: 'sysCompanySetmeal'
      }
    },
    {
      path: '/sys/company/direct_service',
      name: 'sysCompanyDirectService',
      component: () =>
        import('@/views/sys/company/direct_service'),
      meta: {
        title: '快捷消费',
        access: 'sysCompanyDirectService'
      }
    },
    {
      path: '/sys/company/points',
      name: 'sysCompanyPoints',
      component: () =>
        import('@/views/sys/company/points'),
      meta: {
        title: '积分套餐',
        access: 'sysCompanyPoints'
      }
    },
    {
      path: '/sys/company/service',
      name: 'sysCompanyService',
      component: () =>
        import('@/views/sys/company/service'),
      meta: {
        title: '增值服务',
        access: 'sysCompanyService'
      }
    },
    {
      path: '/sys/company/task',
      name: 'companyTask',
      component: () =>
        import('@/views/sys/company/task'),
      meta: {
        title: '任务配置',
        access: 'companyTask'
      }
    }
    // {
    //   path: '/sys/company/customer_service',
    //   name: 'sysCompanyCustomerService',
    //   component: () =>
    //     import('@/views/sys/company/customer_service'),
    //   meta: {
    //     title: '客服配置',
    //     access: 'sysCompanyCustomerService'
    //   }
    // }
    ]
  },
  {
    path: '/sys/personal',
    name: 'sysPersonal',
    component: () =>
      import('@/views/sys/personal'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '个人业务配置',
      access: 'sysPersonal'
    },
    children: [{
      path: '/sys/personal/base',
      name: 'sysPersonalBase',
      component: () =>
        import('@/views/sys/personal/base'),
      meta: {
        title: '基础配置',
        access: 'sysPersonalBase'
      }
    },
    {
      path: '/sys/personal/service',
      name: 'sysPersonalService',
      component: () =>
        import('@/views/sys/personal/service'),
      meta: {
        title: '增值服务',
        access: 'sysPersonalService'
      }
    },
    {
      path: '/sys/personal/task',
      name: 'personalTask',
      component: () =>
        import('@/views/sys/personal/task'),
      meta: {
        title: '任务配置',
        access: 'personalTask'
      }
    }
    ]
  },
  {
    path: '/sys/wechat',
    name: 'sysWechat',
    component: () =>
      import('@/views/sys/wechat'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '微信平台配置',
      access: 'sysWechat'
    },
    children: [{
      path: '/sys/wechat/api',
      name: 'sysWechatApi',
      component: () =>
        import('@/views/sys/wechat/api'),
      meta: {
        title: '微信对接配置',
        access: 'sysWechatApi'
      }
    }, {
      path: '/sys/wechat/base',
      name: 'sysWechatBase',
      component: () =>
        import('@/views/sys/wechat/base'),
      meta: {
        title: '公众号基础信息',
        access: 'sysWechatBase'
      }
    }, {
      path: '/sys/wechat/menu',
      name: 'sysWechatMenu',
      component: () =>
        import('@/views/sys/wechat/menu'),
      meta: {
        title: '公众号自定义菜单',
        access: 'sysWechatMenu'
      }
    }, {
      path: '/sys/wechat/share',
      name: 'sysWechatShare',
      component: () =>
        import('@/views/sys/wechat/share'),
      meta: {
        title: '微信分享配置',
        access: 'sysWechatShare'
      }
    },
    {
      path: '/sys/wechat/notify',
      name: 'sysWechatNotify',
      component: () =>
        import('@/views/sys/wechat/notify'),
      meta: {
        title: '模板消息通知',
        access: 'sysWechatNotify'
      }
    }
    ]
  },
  {
    path: '/sys/admin',
    name: 'admin',
    component: () =>
      import('@/views/sys/admin'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '系统管理员',
      access: 'admin'
    },
    children: [{
      path: '/sys/admin/list',
      name: 'adminList',
      component: () =>
        import('@/views/sys/admin/list'),
      meta: {
        title: '管理员列表',
        access: 'adminList'
      }
    },
    {
      path: '/sys/admin/list/add',
      name: 'adminAdd',
      component: () =>
        import('@/views/sys/admin/list/add'),
      meta: {
        title: '添加管理员',
        access: 'adminList',
        activeMenu: '/sys/admin/list'
      },
      hidden: true
    },
    {
      path: '/sys/admin/list/edit',
      name: 'adminEdit',
      component: () =>
        import('@/views/sys/admin/list/edit'),
      meta: {
        title: '编辑管理员',
        access: 'adminList',
        activeMenu: '/sys/admin/list'
      },
      hidden: true
    },
    {
      path: '/sys/admin/role',
      name: 'role',
      component: () =>
        import('@/views/sys/admin/role'),
      meta: {
        title: '角色权限管理',
        access: 'role'
      }
    },
    {
      path: '/sys/admin/role/add',
      name: 'roleAdd',
      component: () =>
        import('@/views/sys/admin/role/add'),
      meta: {
        title: '添加角色',
        access: 'role',
        activeMenu: '/sys/admin/role'
      },
      hidden: true
    },
    {
      path: '/sys/admin/role/edit',
      name: 'roleEdit',
      component: () =>
        import('@/views/sys/admin/role/edit'),
      meta: {
        title: '编辑角色',
        access: 'role',
        activeMenu: '/sys/admin/role'
      },
      hidden: true
    }
    ]
  },
  {
    path: '/upgrade',
    name: 'upgrade',
    component: () =>
      import('@/views/sys/upgrade/index'),
    meta: {
      title: '系统升级',
      access: 'upgrade'
    },
    hidden: true
  },
  {
    path: '/sys/log',
    name: 'log',
    component: () =>
      import('@/views/sys/log'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '系统日志',
      access: 'log'
    },
    children: [{
      path: '/sys/log/company',
      name: 'logCompany',
      component: () =>
        import('@/views/sys/log/member/company'),
      meta: {
        title: '企业会员日志',
        access: 'logCompany'
      }
    }, {
      path: '/sys/log/personal',
      name: 'logPersonal',
      component: () =>
        import('@/views/sys/log/member/personal'),
      meta: {
        title: '个人会员日志',
        access: 'logPersonal'
      }
    }, {
      path: '/sys/log/admin',
      name: 'logAdmin',
      component: () =>
        import('@/views/sys/log/admin'),
      meta: {
        title: '管理员日志',
        access: 'logAdmin'
      }
    }
    ]
  }
  ]
}
export default systemRouter
