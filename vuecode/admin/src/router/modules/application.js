import Layout from '@/layout'

const applicationRouter = {
  path: '/application',
  component: Layout,
  redirect: 'noRedirect',
  alwaysShow: true,
  name: 'application',
  meta: { title: '应用', icon: 'example', access: 'application' },
  children: [
    {
      path: '/jobfairol',
      name: 'jobfairol',
      component: () => import('@/views/jobfairol'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '网络招聘会', access: 'jobfairol' },
      children: [
        {
          path: '/jobfairol/list',
          name: 'jobfairolList',
          component: () => import('@/views/jobfairol/list'),
          meta: { title: '网络招聘会列表', access: 'jobfairolList' }
        },
        {
          path: '/jobfairol/list/add',
          name: 'jobfairolAdd',
          component: () => import ('@/views/jobfairol/list/add'),
          meta: {
            title: '添加网络招聘会',
            access: 'jobfairolList',
            activeMenu: '/jobfairol/list'
          },
          hidden: true
        },
        {
          path: '/jobfairol/list/edit',
          name: 'jobfairolEdit',
          component: () => import ('@/views/jobfairol/list/edit'),
          meta: {
            title: '编辑网络招聘会',
            access: 'jobfairolList',
            activeMenu: '/jobfairol/list'
          },
          hidden: true
        },
        {
          path: '/jobfairol/exhibitors/company/list',
          name: 'jobfairolExhibitorsCompanyList',
          component: () => import ('@/views/jobfairol/exhibitors/company/list'),
          meta: {
            title: '参会企业',
            access: 'jobfairolExhibitors',
            activeMenu: '/jobfairol/list'
          },
          hidden: true
        },
        {
          path: '/jobfairol/exhibitors/company/add',
          name: 'jobfairolExhibitorsCompanyAdd',
          component: () => import ('@/views/jobfairol/exhibitors/company/add'),
          meta: {
            title: '新增参会企业',
            access: 'jobfairolExhibitors',
            activeMenu: '/jobfairol/list'
          },
          hidden: true
        },
        {
          path: '/jobfairol/exhibitors/personal/list',
          name: 'jobfairolExhibitorsPersonalList',
          component: () => import ('@/views/jobfairol/exhibitors/personal/list'),
          meta: {
            title: '参会个人',
            access: 'jobfairolExhibitors',
            activeMenu: '/jobfairol/list'
          },
          hidden: true
        },
        {
          path: '/jobfairol/exhibitors/personal/add',
          name: 'jobfairolExhibitorsPersonalAdd',
          component: () => import ('@/views/jobfairol/exhibitors/personal/add'),
          meta: {
            title: '新增参会个人',
            access: 'jobfairolExhibitors',
            activeMenu: '/jobfairol/list'
          },
          hidden: true
        }
      ]
    }
  ]
}
export default applicationRouter
