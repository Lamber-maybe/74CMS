import Layout from '@/layout'

const dashboardRouter = {
  path: '/dashboard',
  component: Layout,
  redirect: 'noRedirect',
  alwaysShow: true,
  name: 'dashboard',
  meta: {
    title: '工作台',
    icon: 'example',
    access: 'dashboard'
  },
  children: [{
    path: '/dashboard/index',
    name: 'management',
    component: () =>
      import('@/views/dashboard/index'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '管理中心',
      access: 'management'
    }
  }]
}
export default dashboardRouter
