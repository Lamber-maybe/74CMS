import Layout from '@/layout'

const userRouter = {
  path: '/user',
  component: Layout,
  redirect: 'noRedirect',
  alwaysShow: true,
  name: 'user',
  meta: {
    title: '用户',
    icon: 'example',
    access: 'user'
  },
  children: [{
    path: '/user/member',
    name: 'member',
    component: () =>
      import('@/views/user/member'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '会员管理',
      access: 'member'
    },
    children: [{
      path: '/user/member/company',
      name: 'memberCompany',
      component: () =>
        import('@/views/user/member/company'),
      meta: {
        title: '企业会员',
        access: 'memberCompany'
      }
    },
    {
      path: '/user/member/personal',
      name: 'memberPersonal',
      component: () =>
        import('@/views/user/member/personal'),
      meta: {
        title: '个人会员',
        access: 'memberPersonal'
      }
    },
    {
      path: '/user/member/invalid',
      name: 'memberInvalid',
      component: () =>
        import('@/views/user/member/invalid'),
      meta: {
        title: '无效会员',
        access: 'memberInvalid'
      }
    }
    ]
  },
  {
    path: '/user/resume',
    name: 'resume',
    component: () =>
      import('@/views/user/resume'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '个人简历管理',
      access: 'resume'
    },
    children: [
    // {
    //   path: '/user/resume/noaudit',
    //   name: 'resumeNoaudit',
    //   component: () =>
    //     import('@/views/user/resume/noaudit'),
    //   meta: {
    //     title: '未审核简历',
    //     access: 'resumeNoaudit'
    //   }
    // },
    // {
    //   path: '/user/resume/list',
    //   name: 'resumeList',
    //   component: () =>
    //     import('@/views/user/resume/list'),
    //   meta: {
    //     title: '简历管理',
    //     access: 'resumeList'
    //   }
    // },
    // {
    //   path: '/user/resume/edit',
    //   name: 'resumeEdit',
    //   component: () =>
    //     import('@/views/user/resume/edit'),
    //   meta: {
    //     title: '编辑简历',
    //     access: 'resumeList',
    //     activeMenu: '/user/resume/list'
    //   },
    //   hidden: true
    // },
    // {
    //   path: '/user/resume_img',
    //   name: 'resume_img',
    //   component: () =>
    //     import('@/views/user/resume_img/index'),
    //   meta: {
    //     title: '照片/作品',
    //     access: 'resume_img'
    //   }
    // },
      {
        path: '/user/resume/add',
        name: 'resumeAdd',
        component: () =>
          import('@/views/user/resume/add'),
        meta: {
          title: '添加简历',
          access: 'resumeList',
          activeMenu: '/user/resume/list'
        },
        hidden: true
      },
      {
        path: '/user/urmList',
        name: 'urmList',
        component: () =>
          import('@/views/user/resume/urm/List'),
        meta: {
          title: '全部简历',
          access: 'urmList'
        }
      },
      {
        path: '/user/urm/trashcan',
        name: 'urmTrashcan',
        component: () =>
          import('@/views/user/resume/urm/Trashcan'),
        meta: {
          title: '简历回收站',
          access: 'urmTrashcan'
        }
      },
      {
        path: '/user/job_apply',
        name: 'job_apply',
        component: () =>
          import('@/views/user/job_apply/index'),
        meta: {
          title: '投递简历记录',
          access: 'job_apply'
        }
      }
    ]
  },
  {
    path: '/user/company',
    name: 'company',
    component: () =>
      import('@/views/user/company'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '企业信息管理',
      access: 'company'
    },
    children: [
      {
        path: '/user/company/crm/whole',
        name: 'wholeClue',
        component: () =>
          import('@/views/user/company/crm/Whole'),
        meta: {
          title: '全部线索',
          access: 'wholeClue'
        }
      },
      {
        path: '/user/company/crm/wholeAdd',
        name: 'wholeAdd',
        component: () =>
          import('@/views/user/company/crm/components/Add'),
        meta: {
          title: '新增线索',
          access: 'wholeMy'
        },
        hidden: true
      },
      {
        path: '/user/company/crm/my',
        name: 'wholeMy',
        component: () =>
          import('@/views/user/company/crm/Whole'),
        meta: {
          title: '我的线索',
          access: 'wholeMy'
        }
      },
      {
        path: '/user/company/crm/internationalWaters',
        name: 'wholeInternationalWaters',
        component: () =>
          import('@/views/user/company/crm/Whole'),
        meta: {
          title: '线索公海',
          access: 'wholeInternationalWaters'
        }
      },
      {
        path: '/user/company/crm/allClient',
        name: 'allClient',
        component: () =>
          import('@/views/user/company/crm/Client'),
        meta: {
          title: '全部客户',
          access: 'allClient'
        }
      },
      {
        path: '/user/company/crm/myClient',
        name: 'myClient',
        component: () =>
          import('@/views/user/company/crm/Client'),
        meta: {
          title: '我的客户',
          access: 'myClient'
        }
      },
      {
        path: '/user/company/crm/pubilceClient',
        name: 'pubilceClient',
        component: () =>
          import('@/views/user/company/crm/Client'),
        meta: {
          title: '客户公海',
          access: 'pubilceClient'
        }
      },
      {
        path: '/user/company/crm/config',
        name: 'crmConfig',
        component: () =>
          import('@/views/user/company/crm/Config'),
        meta: {
          title: 'CRM设置',
          access: 'crmConfig'
        }
      },
      {
        path: '/user/company/crm/trashcan',
        name: 'trashcan',
        component: () =>
          import('@/views/user/company/crm/Trashcan'),
        meta: {
          title: 'CRM回收站',
          access: 'trashcan'
        }
      },
      // {
      //   path: '/user/company/noaudit',
      //   name: 'companyNoaudit',
      //   component: () =>
      //     import('@/views/user/company/noaudit'),
      //   meta: {
      //     title: '待审核企业',
      //     access: 'companyNoaudit'
      //   }
      // },
      // {
      //   path: '/user/company/list',
      //   name: 'companyList',
      //   component: () =>
      //     import('@/views/user/company/list'),
      //   meta: {
      //     title: '企业管理',
      //     access: 'companyList'
      //   }
      // },
      {
        path: '/user/company/add',
        name: 'companyAdd',
        component: () =>
          import('@/views/user/company/add'),
        meta: {
          title: '添加企业',
          access: 'myClient'
        },
        hidden: true
      },
      {
        path: '/user/company/edit',
        name: 'companyEdit',
        component: () =>
          import('@/views/user/company/edit'),
        meta: {
          title: '修改企业',
          access: 'myClient'
        },
        hidden: true
      },
      // {
      //   path: '/user/job/noaudit',
      //   name: 'jobNoaudit',
      //   component: () =>
      //     import('@/views/user/job/noaudit'),
      //   meta: {
      //     title: '待审核职位',
      //     access: 'jobNoaudit'
      //   }
      // },
      // {
      //   path: '/user/job/list',
      //   name: 'jobList',
      //   component: () =>
      //     import('@/views/user/job/list'),
      //   meta: {
      //     title: '职位管理',
      //     access: 'jobList'
      //   }
      // },
      // {
      //   path: '/user/job/list/extension',
      //   name: 'extension',
      //   component: () =>
      //     import('@/views/user/job/list/extension'),
      //   meta: {
      //     title: '职位推广',
      //     access: 'jobList'
      //   },
      //   hidden: true
      // },
      {
        path: '/user/job/edit',
        name: 'jobEdit',
        component: () =>
          import('@/views/user/job/edit'),
        meta: {
          title: '修改职位',
          access: 'jobList'
        },
        hidden: true
      }
      // {
      //   path: '/user/company_img',
      //   name: 'company_img',
      //   component: () =>
      //     import('@/views/user/company_img/index'),
      //   meta: {
      //     title: '企业风采',
      //     access: 'company_img'
      //   }
      // },
      // {
      //   path: '/user/company_interview',
      //   name: 'company_interview',
      //   component: () =>
      //     import('@/views/user/company_interview/index'),
      //   meta: {
      //     title: '面试邀请',
      //     access: 'company_interview'
      //   }
      // },
      // {
      //   path: '/user/company_interview_video',
      //   name: 'company_interview_video',
      //   component: () =>
      //     import('@/views/user/company_interview_video/index'),
      //   meta: {
      //     title: '视频面试',
      //     access: 'company_interview_video'
      //   }
      // },
      // {
      //   path: '/user/company_down',
      //   name: 'company_down',
      //   component: () =>
      //     import('@/views/user/company_down/index'),
      //   meta: {
      //     title: '简历下载',
      //     access: 'company_down'
      //   }
      // },
      // {
      //   path: '/user/cancel_apply',
      //   name: 'cancel_apply',
      //   component: () =>
      //     import('@/views/user/cancel_apply/index'),
      //   meta: {
      //     title: '账号注销申请',
      //     access: 'cancel_apply'
      //   }
      // }
    ]
  },
  {
    path: '/user/job',
    name: 'job',
    component: () =>
      import('@/views/user/job'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '职位信息管理',
      access: 'job'
    },
    children: [
      // {
      //   path: '/user/company/noaudit',
      //   name: 'companyNoaudit',
      //   component: () =>
      //     import('@/views/user/company/noaudit'),
      //   meta: {
      //     title: '待审核企业',
      //     access: 'companyNoaudit'
      //   }
      // },
      // {
      //   path: '/user/company/list',
      //   name: 'companyList',
      //   component: () =>
      //     import('@/views/user/company/list'),
      //   meta: {
      //     title: '企业管理',
      //     access: 'companyList'
      //   }
      // },

      // {
      //   path: '/user/job/noaudit',
      //   name: 'jobNoaudit',
      //   component: () =>
      //     import('@/views/user/job/noaudit'),
      //   meta: {
      //     title: '待审核职位',
      //     access: 'jobNoaudit'
      //   }
      // },
      {
        path: '/user/job/list',
        name: 'jobList',
        component: () =>
          import('@/views/user/job/list'),
        meta: {
          title: '职位管理',
          access: 'jobList'
        }
      },
      // {
      //   path: '/user/job/list/extension',
      //   name: 'extension',
      //   component: () =>
      //     import('@/views/user/job/list/extension'),
      //   meta: {
      //     title: '职位推广',
      //     access: 'jobList'
      //   },
      //   hidden: true
      // },
      // {
      //   path: '/user/company_img',
      //   name: 'company_img',
      //   component: () =>
      //     import('@/views/user/company_img/index'),
      //   meta: {
      //     title: '企业风采',
      //     access: 'company_img'
      //   }
      // },
      {
        path: '/user/company_interview',
        name: 'company_interview',
        component: () =>
          import('@/views/user/company_interview/index'),
        meta: {
          title: '面试邀请',
          access: 'company_interview'
        }
      },
      {
        path: '/user/company_interview_video',
        name: 'company_interview_video',
        component: () =>
          import('@/views/user/company_interview_video/index'),
        meta: {
          title: '视频面试',
          access: 'company_interview_video'
        }
      },
      {
        path: '/user/company_down',
        name: 'company_down',
        component: () =>
          import('@/views/user/company_down/index'),
        meta: {
          title: '简历下载',
          access: 'company_down'
        }
      }
      // {
      //   path: '/user/cancel_apply',
      //   name: 'cancel_apply',
      //   component: () =>
      //     import('@/views/user/cancel_apply/index'),
      //   meta: {
      //     title: '账号注销申请',
      //     access: 'cancel_apply'
      //   }
      // }
    ]
  },
  {
    path: '/user/cancel_apply',
    name: 'cancel_apply',
    component: () =>
      import('@/views/user/cancel_apply'),
    redirect: 'noRedirect',
    alwaysShow: true,
    meta: {
      title: '账号注销申请',
      access: 'cancel_apply'
    },
    children: [
      // {
      //   path: '/user/company/noaudit',
      //   name: 'companyNoaudit',
      //   component: () =>
      //     import('@/views/user/company/noaudit'),
      //   meta: {
      //     title: '待审核企业',
      //     access: 'companyNoaudit'
      //   }
      // },
      // {
      //   path: '/user/company/list',
      //   name: 'companyList',
      //   component: () =>
      //     import('@/views/user/company/list'),
      //   meta: {
      //     title: '企业管理',
      //     access: 'companyList'
      //   }
      // },

      // {
      //   path: '/user/job/noaudit',
      //   name: 'jobNoaudit',
      //   component: () =>
      //     import('@/views/user/job/noaudit'),
      //   meta: {
      //     title: '待审核职位',
      //     access: 'jobNoaudit'
      //   }
      // },
      // {
      //   path: '/user/job/list',
      //   name: 'jobList',
      //   component: () =>
      //     import('@/views/user/job/list'),
      //   meta: {
      //     title: '职位管理',
      //     access: 'jobList'
      //   }
      // },
      // {
      //   path: '/user/job/list/extension',
      //   name: 'extension',
      //   component: () =>
      //     import('@/views/user/job/list/extension'),
      //   meta: {
      //     title: '职位推广',
      //     access: 'jobList'
      //   },
      //   hidden: true
      // },
      // {
      //   path: '/user/company_img',
      //   name: 'company_img',
      //   component: () =>
      //     import('@/views/user/company_img/index'),
      //   meta: {
      //     title: '企业风采',
      //     access: 'company_img'
      //   }
      // },
      // {
      //   path: '/user/company_interview',
      //   name: 'company_interview',
      //   component: () =>
      //     import('@/views/user/company_interview/index'),
      //   meta: {
      //     title: '面试邀请',
      //     access: 'company_interview'
      //   }
      // },
      // {
      //   path: '/user/company_interview_video',
      //   name: 'company_interview_video',
      //   component: () =>
      //     import('@/views/user/company_interview_video/index'),
      //   meta: {
      //     title: '视频面试',
      //     access: 'company_interview_video'
      //   }
      // },
      // {
      //   path: '/user/company_down',
      //   name: 'company_down',
      //   component: () =>
      //     import('@/views/user/company_down/index'),
      //   meta: {
      //     title: '简历下载',
      //     access: 'company_down'
      //   }
      // }
      {
        path: '/user/cancel_apply',
        name: 'cancel_apply',
        component: () =>
          import('@/views/user/cancel_apply/index'),
        meta: {
          title: '账号注销申请',
          access: 'cancel_apply'
        }
      }
    ]
  }
  ]
}
export default userRouter
