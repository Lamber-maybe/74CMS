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
            access: 'jobfairolList',
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
            access: 'jobfairolList',
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
            access: 'jobfairolList',
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
            access: 'jobfairolList',
            activeMenu: '/jobfairol/list'
          },
          hidden: true
        },
        {
          // 增加网络招聘会数据导出
          path: '/jobfairol/export/index',
          name: 'jobfairolExport',
          redirect: 'noRedirect',
          component: () => import ('@/views/jobfairol/export/index'),
          meta: {
            title: '信息导出',
            access: 'jobfairExport'
          },
          children: [
            {
              path: '/jobfairol/export/docx',
              name: 'jobfairolExportDocx',
              component: () => import ('@/views/jobfairol/export/docx'),
              meta: {
                title: '文档资料',
                access: 'jobfairolExportDocx'
              }
            },
            {
              path: '/jobfairol/export/weixin',
              name: 'jobfairolExportWeixin',
              component: () => import ('@/views/jobfairol/export/weixin'),
              meta: {
                title: '公众号模板',
                access: 'jobfairolExportWeixin'
              }
            },
            {
              path: '/jobfairol/export/weixin_preview',
              name: 'jobfairolExportWeixinPreview',
              component: () => import ('@/views/jobfairol/export/weixin_preview'),
              meta: {
                title: '公众号模板',
                access: 'jobfairolExportWeixin',
                activeMenu: '/jobfairol/export/weixin'
              },
              hidden: true
            }
          ]
        }
      ]
    },
    {
      path: '/outbound',
      name: 'outbound',
      component: () => import('@/views/outbound'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '外呼中心', access: 'outbound' },
      children: [
        {
          path: '/outbound/dataDoard',
          name: 'outboundDataDoard',
          component: () => import('@/views/outbound/dataDoard'),
          meta: { title: '数据看板', access: 'outboundDataDoard' }
        }, {
          path: '/outbound/allOutbound',
          name: 'allOutbound',
          component: () => import('@/views/outbound/outbound'),
          meta: { title: '全部外呼', access: 'allOutbound' }
        }, {
          path: '/outbound/myOutbound',
          name: 'myOutbound',
          component: () => import('@/views/outbound/outbound'),
          meta: { title: '我的外呼', access: 'myOutbound' }
        }, {
          path: '/outbound/seats',
          name: 'seats',
          component: () => import('@/views/outbound/seats'),
          meta: { title: '坐席信息', access: 'seats' }
        }, {
          path: '/outbound/config',
          name: 'outboundConfig',
          component: () => import('@/views/outbound/config'),
          meta: { title: '外呼配置', access: 'outboundConfig' }
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
          meta: { title: '广告管理', access: 'shortvideoAd' }
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
    },
    {
      path: '/subsite',
      name: 'subsite',
      component: () => import('@/views/subsite'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '地区分站', access: 'subsite' },
      children: [
        {
          path: '/subsite/index',
          name: 'subsiteIndex',
          component: () => import('@/views/subsite/list'),
          meta: { title: '分站管理', access: 'subsiteIndex' }
        },
        {
          path: '/subsite/add',
          name: 'subsiteAdd',
          component: () => import('@/views/subsite/add'),
          meta: { title: '添加分站', access: 'subsiteAdd', activeMenu: '/subsite/index' },
          hidden: true
        },
        {
          path: '/subsite/edit',
          name: 'subsiteEdit',
          component: () => import('@/views/subsite/edit'),
          meta: { title: '编辑分站', access: 'subsiteEdit', activeMenu: '/subsite/index' },
          hidden: true
        }
      ]
    },
    {
      path: '/poster',
      name: 'poster',
      component: () => import('@/views/poster'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '海报设置', access: 'poster' },
      children: [
        {
          path: '/poster/job',
          name: 'posterJob',
          component: () => import('@/views/poster/job'),
          meta: { title: '职位海报', access: 'posterJob' }
        },
        {
          path: '/poster/resume',
          name: 'posterResume',
          component: () => import('@/views/poster/resume'),
          meta: { title: '简历海报', access: 'posterResume' }
        },
        {
          path: '/poster/company',
          name: 'posterCompany',
          component: () => import('@/views/poster/company'),
          meta: { title: '企业海报', access: 'posterCompany' }
        }
      ]
    }
  ]
}
export default applicationRouter
