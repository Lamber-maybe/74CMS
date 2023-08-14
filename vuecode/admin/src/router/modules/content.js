import Layout from '@/layout'

const contentRouter = {
  path: '/content',
  component: Layout,
  redirect: 'noRedirect',
  alwaysShow: true,
  name: 'content',
  meta: { title: '内容', icon: 'example', access: 'content' },
  children: [
    {
      path: '/content/ad',
      name: 'ad',
      component: () => import('@/views/content/ad'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '广告管理', access: 'ad' },
      children: [
        {
          path: '/content/ad/list',
          name: 'adList',
          component: () => import('@/views/content/ad/list'),
          meta: { title: '广告列表', access: 'adList' }
        },
        {
          path: '/content/ad/list/add',
          name: 'adAdd',
          component: () => import('@/views/content/ad/list/add'),
          meta: { title: '添加广告', access: 'adList', activeMenu: '/content/ad/list' },
          hidden: true
        },
        {
          path: '/content/ad/list/edit',
          name: 'adEdit',
          component: () => import('@/views/content/ad/list/edit'),
          meta: { title: '编辑广告', access: 'adList', activeMenu: '/content/ad/list' },
          hidden: true
        },
        {
          path: '/content/ad/category',
          name: 'adCategory',
          component: () => import('@/views/content/ad/category'),
          meta: { title: '广告位', access: 'adCategory' }
        },
        {
          path: '/content/ad/category/add',
          name: 'adCategoryAdd',
          component: () => import('@/views/content/ad/category/add'),
          meta: { title: '添加广告位', access: 'adCategory', activeMenu: '/content/ad/category' },
          hidden: true
        },
        {
          path: '/content/ad/category/edit',
          name: 'adCategoryEdit',
          component: () => import('@/views/content/ad/category/edit'),
          meta: { title: '编辑广告位', access: 'adCategory', activeMenu: '/content/ad/category' },
          hidden: true
        }
      ]
    },
    {
      path: '/content/notice',
      name: 'notice',
      component: () => import('@/views/content/notice'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '公告管理', access: 'notice' },
      children: [
        {
          path: '/content/notice/list',
          name: 'noticeList',
          component: () => import('@/views/content/notice/list'),
          meta: { title: '公告列表', access: 'noticeList' }
        },
        {
          path: '/content/notice/list/add',
          name: 'noticeAdd',
          component: () => import('@/views/content/notice/list/add'),
          meta: { title: '添加公告', access: 'noticeList', activeMenu: '/content/notice/list' },
          hidden: true
        },
        {
          path: '/content/notice/list/edit',
          name: 'noticeEdit',
          component: () => import('@/views/content/notice/list/edit'),
          meta: { title: '编辑公告', access: 'noticeList', activeMenu: '/content/notice/list' },
          hidden: true
        }
      ]
    },
    {
      path: '/content/article',
      name: 'article',
      component: () => import('@/views/content/article'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '资讯管理', access: 'article' },
      children: [
        {
          path: '/content/article/list',
          name: 'articleList',
          component: () => import('@/views/content/article/list'),
          meta: { title: '资讯列表', access: 'articleList' }
        },
        {
          path: '/content/article/list/add',
          name: 'articleAdd',
          component: () => import('@/views/content/article/list/add'),
          meta: { title: '添加资讯', access: 'articleList', activeMenu: '/content/article/list' },
          hidden: true
        },
        {
          path: '/content/article/list/edit',
          name: 'articleEdit',
          component: () => import('@/views/content/article/list/edit'),
          meta: { title: '编辑资讯', access: 'articleList', activeMenu: '/content/article/list' },
          hidden: true
        },
        {
          path: '/content/article/category',
          name: 'articleCategory',
          component: () => import('@/views/content/article/category'),
          meta: { title: '资讯分类', access: 'articleCategory' }
        },
        {
          path: '/content/article/category/add',
          name: 'articleCategoryAdd',
          component: () => import('@/views/content/article/category/add'),
          meta: { title: '添加分类', access: 'articleCategory', activeMenu: '/content/article/category' },
          hidden: true
        },
        {
          path: '/content/article/category/edit',
          name: 'articleCategoryEdit',
          component: () => import('@/views/content/article/category/edit'),
          meta: { title: '编辑分类', access: 'articleCategory', activeMenu: '/content/article/category' },
          hidden: true
        }
      ]
    },
    {
      path: '/content/explain',
      name: 'explain',
      component: () => import('@/views/content/explain'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '说明页管理', access: 'explain' },
      children: [
        {
          path: '/content/explain/list',
          name: 'explainList',
          component: () => import('@/views/content/explain/list'),
          meta: { title: '说明页列表', access: 'explainList' }
        },
        {
          path: '/content/explain/list/add',
          name: 'explainAdd',
          component: () => import('@/views/content/explain/list/add'),
          meta: { title: '添加说明页', access: 'explainList', activeMenu: '/content/explain/list' },
          hidden: true
        },
        {
          path: '/content/explain/list/edit',
          name: 'explainEdit',
          component: () => import('@/views/content/explain/list/edit'),
          meta: { title: '编辑说明页', access: 'explainList', activeMenu: '/content/explain/list' },
          hidden: true
        }
      ]
    },
    {
      path: '/content/hrtool',
      name: 'hrtool',
      component: () => import('@/views/content/hrtool'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: 'Hr工具箱', access: 'hrtool' },
      children: [
        {
          path: '/content/hrtool/list',
          name: 'hrtoolList',
          component: () => import('@/views/content/hrtool/list'),
          meta: { title: 'Hr工具箱列表', access: 'hrtoolList' }
        },
        {
          path: '/content/hrtool/list/add',
          name: 'hrtoolAdd',
          component: () => import('@/views/content/hrtool/list/add'),
          meta: { title: '添加Hr工具箱', access: 'hrtoolList', activeMenu: '/content/hrtool/list' },
          hidden: true
        },
        {
          path: '/content/hrtool/list/edit',
          name: 'hrtoolEdit',
          component: () => import('@/views/content/hrtool/list/edit'),
          meta: { title: '编辑Hr工具箱', access: 'hrtoolList', activeMenu: '/content/hrtool/list' },
          hidden: true
        },
        {
          path: '/content/hrtool/category',
          name: 'hrtoolCategory',
          component: () => import('@/views/content/hrtool/category'),
          meta: { title: 'Hr工具箱分类', access: 'hrtoolCategory' }
        },
        {
          path: '/content/hrtool/category/add',
          name: 'hrtoolCategoryAdd',
          component: () => import('@/views/content/hrtool/category/add'),
          meta: { title: '添加分类', access: 'hrtoolCategory', activeMenu: '/content/hrtool/category' },
          hidden: true
        },
        {
          path: '/content/hrtool/category/edit',
          name: 'hrtoolCategoryEdit',
          component: () => import('@/views/content/hrtool/category/edit'),
          meta: { title: '编辑分类', access: 'hrtoolCategory', activeMenu: '/content/hrtool/category' },
          hidden: true
        }
      ]
    },
    {
      path: '/content/flink',
      name: 'flink',
      component: () => import('@/views/content/flink'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '友情链接', access: 'flink' },
      children: [
        {
          path: '/content/flink/list',
          name: 'flinkList',
          component: () => import('@/views/content/flink/list'),
          meta: { title: '友情链接列表', access: 'flinkList' }
        },
        {
          path: '/content/flink/list/add',
          name: 'flinkAdd',
          component: () => import('@/views/content/flink/list/add'),
          meta: { title: '添加友情链接', access: 'flinkList', activeMenu: '/content/flink/list' },
          hidden: true
        },
        {
          path: '/content/flink/list/edit',
          name: 'flinkEdit',
          component: () => import('@/views/content/flink/list/edit'),
          meta: { title: '编辑友情链接', access: 'flinkList', activeMenu: '/content/flink/list' },
          hidden: true
        }
      ]
    },
    {
      path: '/content/help',
      name: 'help',
      component: () => import('@/views/content/help'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '帮助中心', access: 'help' },
      children: [
        {
          path: '/content/help/list',
          name: 'helpList',
          component: () => import('@/views/content/help/list'),
          meta: { title: '信息管理', access: 'helpList' }
        },
        {
          path: '/content/help/list/add',
          name: 'helpAdd',
          component: () => import('@/views/content/help/list/add'),
          meta: { title: '添加', access: 'helpList', activeMenu: '/content/help/list' },
          hidden: true
        },
        {
          path: '/content/help/list/edit',
          name: 'helpEdit',
          component: () => import('@/views/content/help/list/edit'),
          meta: { title: '编辑', access: 'helpList', activeMenu: '/content/help/list' },
          hidden: true
        },
        {
          path: '/content/help/category',
          name: 'helpCategory',
          component: () => import('@/views/content/help/category'),
          meta: { title: '分类管理', access: 'helpCategory' }
        },
        {
          path: '/content/help/category/add',
          name: 'helpCategoryAdd',
          component: () => import('@/views/content/help/category/add'),
          meta: { title: '添加分类', access: 'helpCategory', activeMenu: '/content/help/category' },
          hidden: true
        },
        {
          path: '/content/help/category/edit',
          name: 'helpCategoryEdit',
          component: () => import('@/views/content/help/category/edit'),
          meta: { title: '编辑分类', access: 'helpCategory', activeMenu: '/content/help/category' },
          hidden: true
        }
      ]
    },
    {
      path: '/content/appeal',
      name: 'appeal',
      component: () => import('@/views/content/appeal'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '账号申诉', access: 'appeal' },
      children: [
        {
          path: '/content/appeal/list',
          name: 'appealList',
          component: () => import('@/views/content/appeal/list'),
          meta: { title: '信息管理', access: 'appealList' }
        }
      ]
    },
    {
      path: '/content/feedback',
      name: 'feedback',
      component: () => import('@/views/content/feedback'),
      redirect: 'noRedirect',
      alwaysShow: true,
      meta: { title: '投诉建议', access: 'feedback' },
      children: [
        {
          path: '/content/feedback/tipoff',
          name: 'feedbackTipoff',
          component: () => import('@/views/content/feedback/tipoff'),
          meta: { title: '举报信息', access: 'feedbackTipoff' }
        },
        {
          path: '/content/feedback/suggest',
          name: 'feedbackSuggest',
          component: () => import('@/views/content/feedback/suggest'),
          meta: { title: '意见建议', access: 'feedbackSuggest' }
        },
        {
          path: '/content/feedback/complaint',
          name: 'feedbackComplaint',
          component: () => import('@/views/content/feedback/complaint'),
          meta: { title: '投诉客服', access: 'feedbackComplaint' }
        }
      ]
    }
  ]
}
export default contentRouter
