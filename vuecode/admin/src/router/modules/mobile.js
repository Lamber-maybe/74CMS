import Layout from '@/layout'

const mobileRouter = {
  path: '/mobile',
  component: Layout,
  redirect: 'noRedirect',
  alwaysShow: true,
  name: 'mobile',
  meta: {
    title: '移动端',
    icon: 'example',
    access: 'mobile'
  },
  children: [{
    path: '/mobile/touch',
    name: 'touch',
    component: () =>
      import('@/views/mobile/touch'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '手机触屏端',
      access: 'touch'
    },
    children: [{
      path: '/mobile/touch/basic',
      name: 'touchBasic',
      component: () =>
        import('@/views/mobile/touch/basic'),
      meta: {
        title: '基础配置',
        access: 'touchBasic'
      }
    },
    {
      path: '/mobile/touch/diy',
      name: 'touchDiy',
      component: () =>
        import('@/views/mobile/touch/diy'),
      meta: {
        title: '个性化首页',
        access: 'touchDiy'
      }
    },
    {
      path: '/mobile/touch/page',
      name: 'mobileTouchPage',
      component: () =>
        import('@/views/mobile/touch/page/index'),
      meta: {
        title: '页面管理',
        access: 'mobileTouchPage'
      }
    },
    {
      path: '/mobile/touch/page/edit',
      name: 'mobileTouchPageEdit',
      component: () =>
        import('@/views/mobile/touch/page/edit'),
      meta: {
        title: '页面管理',
        access: 'mobileTouchPage',
        activeMenu: '/mobile/touch/page'
      },
      hidden: true
    }
    ]
  }]
}
export default mobileRouter
