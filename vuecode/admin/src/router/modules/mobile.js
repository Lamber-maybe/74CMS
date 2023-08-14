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
      import ('@/views/mobile/touch'),
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
        import ('@/views/mobile/touch/basic'),
      meta: {
        title: '基础配置',
        access: 'touchBasic'
      }
    },
    {
      path: '/mobile/touch/diy',
      name: 'touchDiy',
      component: () =>
        import ('@/views/mobile/touch/diy'),
      meta: {
        title: '个性化首页',
        access: 'touchDiy'
      }
    }
    ]
  }]
}
export default mobileRouter
