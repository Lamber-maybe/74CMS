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
          component: () => import('@/views/jobfairol/list/add'),
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
          component: () => import('@/views/jobfairol/list/edit'),
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
          component: () => import('@/views/jobfairol/exhibitors/company/list'),
          meta: {
            title: '参会企业',
            access: 'jobfairolList',
            activeMenu: '/jobfairol/list'
          },
          hidden: true
        },
        {
          path: '/jobfairol/exhibitors/company/add',
          name: 'jobfairolExhibitorsCompanyAdd',
          component: () => import('@/views/jobfairol/exhibitors/company/add'),
          meta: {
            title: '新增参会企业',
            access: 'jobfairolList',
            activeMenu: '/jobfairol/list'
          },
          hidden: true
        },
        {
          path: '/jobfairol/exhibitors/personal/list',
          name: 'jobfairolExhibitorsPersonalList',
          component: () => import('@/views/jobfairol/exhibitors/personal/list'),
          meta: {
            title: '参会个人',
            access: 'jobfairolList',
            activeMenu: '/jobfairol/list'
          },
          hidden: true
        },
        {
          path: '/jobfairol/exhibitors/personal/add',
          name: 'jobfairolExhibitorsPersonalAdd',
          component: () => import('@/views/jobfairol/exhibitors/personal/add'),
          meta: {
            title: '新增参会个人',
            access: 'jobfairolList',
            activeMenu: '/jobfairol/list'
          },
          hidden: true
        }
      ]
    },
    {
      path: '/shortvideo',
      name: 'shortvideo',
      component: () => import('@/views/shortvideo'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '视频招聘', access: 'shortvideo' },
      children: [
        {
          path: '/shortvideo/com',
          name: 'shortvideoCom',
          component: () => import('@/views/shortvideo/com'),
          meta: { title: '视频招聘', access: 'shortvideoCom' }
        },
        {
          path: '/shortvideo/person',
          name: 'shortvideoPerson',
          component: () => import('@/views/shortvideo/person'),
          meta: { title: '视频求职', access: 'shortvideoPerson' }
        },
        {
          path: '/shortvideo/ad',
          name: 'shortvideoAd',
          component: () => import('@/views/shortvideo/ad'),
          meta: { title: '广告管理', access: 'shortvideoAd' },
        },
        {
          path: '/shortvideo/ad/list/add',
          name: 'shortvideoAdAdd',
          component: () => import('@/views/shortvideo/ad/list/add'),
          meta: { title: '添加广告', access: 'shortvideoAd', activeMenu: '/shortvideo/ad' },
          hidden: true
        },
        {
          path: '/shortvideo/ad/list/edit',
          name: 'shortvideoAdEdit',
          component: () => import('@/views/shortvideo/ad/list/edit'),
          meta: { title: '编辑广告', access: 'shortvideoAdEdit', activeMenu: '/shortvideo/ad' },
          hidden: true
        },
        {
          path: '/shortvideo/ad/category/add',
          name: 'shortvideoAdCategoryAdd',
          component: () => import('@/views/shortvideo/ad/category/add'),
          meta: { title: '添加广告位', access: 'shortvideoAd', activeMenu: '/shortvideo/ad' },
          hidden: true
        },
        {
          path: '/shortvideo/ad/category/edit',
          name: 'shortvideoAdCategoryEdit',
          component: () => import('@/views/shortvideo/ad/category/edit'),
          meta: { title: '编辑广告位', access: 'shortvideoAd', activeMenu: '/shortvideo/ad' },
          hidden: true
        },
        {
          path: '/shortvideo/setting',
          name: 'shortvideoSetting',
          component: () => import('@/views/shortvideo/setting.vue'),
          meta: { title: '功能配置', access: 'shortvideoSetting' }
        }
      ]
    }
  ]
}
export default applicationRouter
