import Layout from '@/layout'

const statRouter = {
  path: '/stat',
  component: Layout,
  redirect: 'noRedirect',
  alwaysShow: true,
  name: 'stat',
  meta: {
    title: '画像',
    icon: 'example',
    access: 'stat'
  },
  children: [{
    path: '/stat/overview',
    name: 'statOverview',
    component: () =>
      import ('@/views/stat/overview'),
    meta: {
      title: '系统数据总览',
      access: 'statOverview'
    }
  },
  {
    path: '/stat/resume',
    name: 'statResume',
    component: () =>
      import ('@/views/stat/resume'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '简历数据统计',
      access: 'statResume'
    },
    children: [{
      path: '/stat/resume/overview',
      name: 'statResumeOverview',
      component: () =>
        import ('@/views/stat/resume/overview'),
      meta: {
        title: '简历总览分析',
        access: 'statResumeOverview'
      }
    },
    {
      path: '/stat/resume/personal',
      name: 'statResumePersonal',
      component: () =>
        import ('@/views/stat/resume/personal'),
      meta: {
        title: '求职者结构',
        access: 'statResumePersonal'
      }
    },
    {
      path: '/stat/resume/intention',
      name: 'statResumeIntention',
      component: () =>
        import ('@/views/stat/resume/intention'),
      meta: {
        title: '简历流向',
        access: 'statResumeIntention'
      }
    },
    {
      path: '/stat/resume/hot',
      name: 'statResumeHot',
      component: () =>
        import ('@/views/stat/resume/hot'),
      meta: {
        title: '热度分析',
        access: 'statResumeHot'
      }
    }
    ]
  },
  {
    path: '/stat/company',
    name: 'statCompany',
    component: () =>
      import ('@/views/stat/company'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '企业数据统计',
      access: 'statCompany'
    },
    children: [{
      path: '/stat/company/overview',
      name: 'statCompanyOverview',
      component: () =>
        import ('@/views/stat/company/overview'),
      meta: {
        title: '企业总览分析',
        access: 'statCompanyOverview'
      }
    },
    {
      path: '/stat/company/joboverview',
      name: 'statCompanyJoboverview',
      component: () =>
        import ('@/views/stat/company/joboverview'),
      meta: {
        title: '职位总览分析',
        access: 'statCompanyJoboverview'
      }
    },
    {
      path: '/stat/company/business',
      name: 'statCompanyBusiness',
      component: () =>
        import ('@/views/stat/company/business'),
      meta: {
        title: '业务分析',
        access: 'statCompanyBusiness'
      }
    },
    {
      path: '/stat/company/hot',
      name: 'statCompanyHot',
      component: () =>
        import ('@/views/stat/company/hot'),
      meta: {
        title: '热度分析',
        access: 'statCompanyHot'
      }
    }
    ]
  },
  {
    path: '/stat/order',
    name: 'statOrder',
    component: () =>
      import ('@/views/stat/order'),
    meta: {
      title: '业务成交统计',
      access: 'statOrder'
    }
  },
  {
    path: '/stat/screen',
    name: 'statScreen',
    component: () =>
      import ('@/views/stat/screen'),
    meta: {
      title: '数据大屏',
      access: 'statScreen'
    }
  }
  ]
}
export default statRouter
