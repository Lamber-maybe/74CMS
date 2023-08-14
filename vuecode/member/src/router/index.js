import Vue from 'vue'
import VueRouter from 'vue-router'
import Layout from '../layout'
import LayoutPersonal from '../layoutPersonal/index'
import LayoutMember from '../layoutMember/index'

Vue.use(VueRouter)
/**
 * 重写路由的push方法
 */
const routerPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location) {
  return routerPush.call(this, location).catch((error) => error)
}
const routes = [
  {
    path: '/',
    name: 'Member',
    component: LayoutMember,
    redirect: '/login',
    children: [
      {
        path: '/oauth/qq/:type?',
        name: 'MemberOauthQq',
        component: () => import('../views/member/oauth/qq'),
        meta: {
          title: '第三方授权',
          keepAlive: false,
          loginCheck: false,
          utype: 0
        }
      },
      {
        path: '/oauth/weixin/:code',
        name: 'MemberOauthWeixin',
        component: () => import('../views/member/oauth/weixin'),
        meta: {
          title: '第三方授权',
          keepAlive: false,
          loginCheck: false,
          utype: 0
        }
      },
      {
        path: '/reg/:utype?',
        name: 'Register',
        component: () => import('../views/member/reg/Index'),
        meta: {
          title: '会员注册',
          keepAlive: false,
          loginCheck: false,
          utype: 0
        }
      },
      {
        path: '/login/:utype_alias?',
        name: 'MemberLogin',
        component: () => import('../views/member/Login'),
        meta: {
          title: '会员登录',
          keepAlive: false,
          loginCheck: false,
          utype: 0
        }
      },
      {
        path: '/wxpay', //微信支付
        name: 'Wxpay',
        component: () => import('../views/Wxpay'),
        meta: {
          title: '微信支付 - 会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 0
        }
      },
      {
        path: '/resume_save/:id(\\d+)', //生成简历pdf
        name: 'ResumeSave',
        component: () => import('../views/member/ResumeSave'),
        meta: {
          title: '保存简历 - 会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 0
        }
      },
      {
        path: '/bind',
        name: 'MemberBind',
        component: () => import('../views/member/Bind'),
        meta: {
          title: '关联账号 - 会员中心',
          keepAlive: false,
          loginCheck: false,
          utype: 0
        }
      },
      {
        path: '/forget/:utype',
        name: 'FindPwd',
        component: () => import('../views/member/FindPwd'),
        meta: {
          title: '找回密码 - 会员中心',
          keepAlive: false,
          loginCheck: false,
          utype: 0
        }
      },
      {
        path: '/appeal',
        name: 'Appeal',
        component: () => import('../views/member/Appeal'),
        meta: {
          title: '账号申诉 - 会员中心',
          keepAlive: false,
          loginCheck: false,
          utype: 0
        }
      }
    ]
  },
  {
    path: '/personal',
    name: 'Personal',
    component: LayoutPersonal,
    redirect: '/personal/index',
    children: [
      {
        path: '/personal/index',
        name: 'PersonalIndex',
        component: () => import('../views/personal/Index'),
        meta: {
          title: '求职者会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 2,
          activeMenu: '/personal'
        }
      },
      {
        path: '/personal/recommend', //智能推荐
        name: 'PersonalRecommend',
        component: () => import('../views/personal/Recommend'),
        meta: {
          title: '智能推荐 - 求职者会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 2
        }
      },
      {
        path: '/personal/message', //消息通知
        name: 'PersonalMessage',
        component: () => import('../views/personal/Message'),
        meta: {
          title: '消息通知 - 求职者会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 2
        }
      },
      {
        path: '/personal/job', //求职管理
        name: 'Personaljob',
        component: () => import('../views/personal/job/index'),
        redirect: '/personal/job/jobapply',
        children: [
          {
            path: '/personal/job/jobapply', //我的投递
            name: 'PersonalJobApply',
            component: () => import('../views/personal/job/JobApply'),
            meta: {
              title: '我的投递 - 求职管理 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2
            }
          },
          {
            path: '/personal/job/viewjob', //我的足迹
            name: 'PersonalViewJob',
            component: () => import('../views/personal/job/ViewJob'),
            meta: {
              title: '我的足迹 - 求职管理 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2
            }
          },
          {
            path: '/personal/job/attention_me', //对我感兴趣
            name: 'PersonalAttentionMe',
            component: () => import('../views/personal/job/AttentionMe'),
            meta: {
              title: '对我感兴趣 - 求职管理 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2
            }
          },
          {
            path: '/personal/job/interview', //面试邀请
            name: 'PersonalInterview',
            component: () => import('../views/personal/job/Interview'),
            meta: {
              title: '面试邀请 - 求职管理 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2
            }
          },
          {
            path: '/personal/job/interview_video', //视频面试
            name: 'PersonalVideo',
            component: () => import('../views/personal/job/InterviewVideo'),
            meta: {
              title: '视频面试 - 求职管理 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2
            }
          }
        ]
      },
      {
        path: '/personal/fav', //收藏关注
        name: 'Personalfav',
        component: () => import('../views/personal/follow/index'),
        children: [
          {
            path: '/personal/fav/attention_company', //关注的企业
            name: 'PersonalAttentionCompany',
            component: () => import('../views/personal/follow/AttentionCompany'),
            meta: {
              title: '关注的企业 - 收藏关注 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2
            }
          },
          {
            path: '/personal/fav/favjob', //收藏的职位
            name: 'PersonalFavJob',
            component: () => import('../views/personal/follow/JobFavorites'),
            meta: {
              title: '收藏的职位 - 收藏关注 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2
            }
          },
          {
            path: '/personal/fav/subscribe', //职位订阅
            name: 'PersonalSubscribe',
            component: () => import('../views/personal/follow/Subscribe'),
            meta: {
              title: '职位订阅 - 收藏关注 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2
            }
          }
        ]
      },
      {
        path: '/personal/points', //我的任务  积分
        name: 'Personalpoints',
        component: () => import('../views/personal/points/index'),
        redirect: '/personal/points/integral',
        children: [
          {
            path: '/personal/points/integral', //获取积分
            name: 'PersonalPointsIntegral',
            component: () => import('../views/personal/points/integral'),
            meta: {
              title: '我的积分 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2,
              activeMenu: '/personal/points'
            }
          },
          {
            path: '/personal/points/detailed', //积分明细
            name: 'PersonalPointsDetailed',
            component: () => import('../views/personal/points/detailed'),
            meta: {
              title: '积分明细 - 我的积分 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2,
              activeMenu: '/personal/points'
            }
          }
        ]
      },
      {
        path: '/personal/privacy', //隐私设置
        name: 'PersonalPrivacy',
        component: () => import('../views/personal/Privacy/index'),
        redirect: '/personal/privacy/setPrivacy',
        children: [
          {
            path: '/personal/privacy/setPrivacy', //设置隐私
            name: 'PersonalPrivacySetPrivacy',
            component: () => import('../views/personal/Privacy/setPrivacy'),
            meta: {
              title: '隐私设置 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2,
              activeMenu: '/personal/privacy'
            }
          },
          {
            path: '/personal/privacy/shield', //屏蔽企业
            name: 'PersonalPrivacyshield',
            component: () => import('../views/personal/Privacy/shield'),
            meta: {
              title: '屏蔽企业 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2,
              activeMenu: '/personal/privacy'
            }
          }
        ]
      },
      {
        path: '/personal/account', //账号管理
        name: 'PersonalAccount',
        component: () => import('../views/personal/account/Index'),
        redirect: '/personal/account/safety',
        children: [
          {
            path: '/personal/account/safety', //账号安全
            name: 'PersonalAccountSafety',
            component: () => import('../views/personal/account/Safety'),
            meta: {
              title: '账号安全 - 账号管理 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2,
              activeMenu: '/personal/account'
            }
          },
          {
            path: '/personal/account/loginlog', //登录日志
            name: 'PersonalAccountLoginlog',
            component: () => import('../views/personal/account/Loginlog'),
            meta: {
              title: '登录日志 - 账号管理 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2,
              activeMenu: '/personal/account'
            }
          },
          {
            path: '/personal/account/feedback', //建议反馈
            name: 'PersonalAccountFeedback',
            component: () => import('../views/personal/account/Feedback'),
            meta: {
              title: '建议反馈 - 账号管理 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2,
              activeMenu: '/personal/account'
            }
          }
        ]
      },
      {
        path: '/personal/service', //增值服务
        name: 'personalService',
        component: () => import('../views/personal/service/index'),
        redirect: '/personal/service/stick',
        children: [
          {
            path: '/personal/service/stick', //简历置顶
            name: 'PersonalServiceStick',
            component: () => import('../views/personal/service/Stick'),
            meta: {
              title: '简历置顶 - 增值服务 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2,
              activeMenu: '/personal/service'
            }
          },
          {
            path: '/personal/service/tag', //醒目标签
            name: 'PersonalServiceTag',
            component: () => import('../views/personal/service/Tag'),
            meta: {
              title: '醒目标签 - 增值服务 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2,
              activeMenu: '/personal/service'
            }
          },
          {
            path: '/personal/service/order', //订单列表
            name: 'PersonalServiceOrderList',
            component: () => import('../views/personal/service/OrderList'),
            meta: {
              title: '我的订单 - 增值服务 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2,
              activeMenu: '/personal/service'
            }
          },
          {
            path: '/personal/service/order/:id', //订单详情
            name: 'PersonalServiceOrderDetail',
            component: () => import('../views/personal/service/OrderDetail'),
            meta: {
              title: '订单详情 - 增值服务 - 求职者会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 2,
              activeMenu: '/personal/service'
            }
          }
        ]
      }
    ]
  },
  {
    path: '/personal/resume',
    name: 'PersonalResume',
    component: LayoutMember,
    children: [
      {
        path: '/personal/resume',
        name: 'PersonalResumeIndex',
        component: () => import('../views/personal/Resume'),
        meta: {
          title: '完善简历 - 求职者会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 2
        }
      },
      {
        path: '/personal/resume/add_resume_step1',
        name: 'PersonalAddResumeStep1',
        component: () => import('../views/personal/AddResumeStep1'),
        meta: {
          title: '创建简历 - 求职者会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 2
        }
      },
      {
        path: '/personal/resume/add_resume_step2/:work',
        name: 'PersonalAddResumeStep2',
        component: () => import('../views/personal/AddResumeStep2'),
        meta: {
          title: '创建简历 - 求职者会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 2
        }
      },
      {
        path: '/personal/resume/add_resume_step3',
        name: 'PersonalAddResumeStep3',
        component: () => import('../views/personal/AddResumeStep3'),
        meta: {
          title: '创建简历完成 - 求职者会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 2
        }
      }
    ]
  },

  {
    path: '/company',
    name: 'Company',
    component: Layout,
    children: [
      {
        path: '/company',
        name: 'CompanyIndex',
        component: () => import('../views/company/Index'),
        meta: {
          title: '企业会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 1,
          activeMenu: '/company'
        }
      },
      {
        path: '/company/recommend', //智能推荐
        name: 'CompanyRecommend',
        component: () => import('../views/company/Recommend'),
        meta: {
          title: '智能推荐 - 企业会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 1
        }
      },
      {
        path: '/company/message', //消息通知
        name: 'CompanyMessage',
        component: () => import('../views/company/Message'),
        meta: {
          title: '消息通知 - 企业会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 1
        }
      },
      {
        path: '/company/microposte', //微海报
        name: 'CompanyMicroposte',
        component: () => import('../views/company/Microposte'),
        meta: {
          title: '微海报 - 企业会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 1
        }
      },
      {
        path: '/company/manage', //企业管理
        name: 'CompanyManage',
        component: () => import('../views/company/manage/Index'),
        redirect: '/company/manage/baseinfo',
        children: [
          {
            path: '/company/manage/baseinfo', //基本资料
            name: 'CompanyManageBaseinfo',
            component: () => import('../views/company/manage/Baseinfo'),
            meta: {
              title: '企业基本资料 - 企业管理 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1,
              activeMenu: '/company/manage/baseinfo'
            }
          },
          {
            path: '/company/manage/img', //企业风采
            name: 'CompanyManageImg',
            component: () => import('../views/company/manage/Img'),
            meta: {
              title: '企业风采 - 企业管理 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1,
              activeMenu: '/company/manage/img'
            }
          }
        ]
      },
      {
        path: '/company/resume', //简历管理
        name: 'CompanyResume',
        component: () => import('../views/company/resume/Index'),
        redirect: '/company/resume/jobapply',
        children: [
          {
            path: '/company/resume/jobapply', //收到投递
            name: 'CompanyResumeJobapply',
            component: () => import('../views/company/resume/Jobapply'),
            meta: {
              title: '收到投递 - 简历管理 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1
            }
          },
          {
            path: '/company/resume/download', //我的下载
            name: 'CompanyResumeDownload',
            component: () => import('../views/company/resume/Download'),
            meta: {
              title: '我的下载 - 简历管理 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1
            }
          },
          {
            path: '/company/resume/fav', //我的收藏
            name: 'CompanyResumeFav',
            component: () => import('../views/company/resume/Fav'),
            meta: {
              title: '我的收藏 - 简历管理 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1
            }
          },
          {
            path: '/company/resume/browsing', //浏览记录
            name: 'CompanyResumeBrowsing',
            component: () => import('../views/company/resume/Browsing'),
            meta: {
              title: '浏览记录 - 简历管理 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1
            }
          },
          {
            path: '/company/resume/interview', //面试邀请
            name: 'CompanyResumeInterview',
            component: () => import('../views/company/resume/Interview'),
            meta: {
              title: '面试邀请 - 简历管理 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1
            }
          },
          {
            path: '/company/resume/interview_video', //视频面试
            name: 'CompanyResumeInterviewVideo',
            component: () => import('../views/company/resume/InterviewVideo'),
            meta: {
              title: '视频面试 - 简历管理 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1
            }
          }
        ]
      },
      {
        path: '/company/auth', //企业认证
        name: 'CompanyAuth',
        component: () => import('../views/company/Auth'),
        meta: {
          title: '企业认证 - 企业会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 1
        }
      },
      // {
      //   path:'/company/manage/ComAuth', //企业认证
      //   name: 'CompanyManageComAuth',
      //   component: () => import('../views/company/manage/ComAuth'),
      //   meta: {
      //     title: '企业风采 - 企业管理 - 企业会员中心',
      //     keepAlive: false,
      //     loginCheck: true,
      //     utype: 1,
      //     activeMenu: '/company/manage/ComAuth'
      //   }
      // },
      {
        path: '/company/account', //账号管理
        name: 'CompanyAccount',
        component: () => import('../views/company/account/Index'),
        redirect: '/company/account/safety',
        children: [
          {
            path: '/company/account/safety', //账号安全
            name: 'CompanyAccountSafety',
            component: () => import('../views/company/account/Safety'),
            meta: {
              title: '账号安全 - 账号管理 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1,
              activeMenu: '/company/account'
            }
          },
          {
            path: '/company/account/loginlog', //登录日志
            name: 'CompanyAccountLoginlog',
            component: () => import('../views/company/account/Loginlog'),
            meta: {
              title: '登录日志 - 账号管理 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1,
              activeMenu: '/company/account'
            }
          },
          {
            path: '/company/account/feedback', //建议反馈
            name: 'CompanyAccountFeedback',
            component: () => import('../views/company/account/Feedback'),
            meta: {
              title: '建议反馈 - 账号管理 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1,
              activeMenu: '/company/account'
            }
          }
        ]
      },
      {
        path: '/company/joblist', //职位列表
        name: 'CompanyJoblist',
        component: () => import('../views/company/Joblist'),
        meta: {
          title: '职位管理 - 企业会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 1
        }
      },
      {
        path: '/company/jobadd', //发布职位
        name: 'CompanyJobadd',
        component: () => import('../views/company/Jobadd'),
        meta: {
          title: '发布职位 - 职位管理 - 企业会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 1,
          activeMenu: '/company/joblist'
        }
      },
      {
        path: '/company/jobedit/:id', //修改职位
        name: 'CompanyJobedit',
        component: () => import('../views/company/Jobedit'),
        meta: {
          title: '修改职位 - 职位管理 - 企业会员中心',
          keepAlive: false,
          loginCheck: true,
          utype: 1,
          activeMenu: '/company/joblist'
        }
      },
      {
        path: '/company/service', //会员服务
        name: 'CompanyService',
        component: () => import('../views/company/service/Index'),
        redirect: '/company/service/setmeal',
        children: [
          {
            path: '/company/service/setmeal', //我的套餐
            name: 'CompanyServiceSetmeal',
            component: () => import('../views/company/service/Setmeal'),
            meta: {
              title: '我的套餐 - 会员服务 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1
            }
          },
          {
            path: '/company/service/setmeal/detail', //我的特权
            name: 'CompanyServiceSetmealDetail',
            component: () => import('../views/company/service/SetmealDetail'),
            meta: {
              title: '我的套餐 - 会员服务 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1,
              activeMenu: '/company/service/setmeal'
            }
          },
          {
            path: '/company/service/point', //我的积分
            name: 'CompanyServicePoint',
            component: () => import('../views/company/service/Point'),
            meta: {
              title: '我的积分 - 会员服务 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1
            }
          },
          {
            path: '/company/service/pointlog', //积分明细
            name: 'CompanyServicePointLog',
            component: () => import('../views/company/service/PointLog'),
            meta: {
              title: '积分明细 - 会员服务 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1,
              activeMenu: '/company/service/point'
            }
          },
          {
            path: '/company/service/coupon', //优惠券
            name: 'CompanyServiceCoupon',
            component: () => import('../views/company/service/Coupon'),
            meta: {
              title: '优惠券 - 会员服务 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1
            }
          },
          {
            path: '/company/service/order', //订单
            name: 'CompanyServiceOrder',
            component: () => import('../views/company/service/OrderList'),
            meta: {
              title: '我的订单 - 会员服务 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1
            }
          },
          {
            path: '/company/service/order/:id', //订单详情
            name: 'CompanyServiceOrderDetail',
            component: () => import('../views/company/service/OrderDetail'),
            meta: {
              title: '订单详情 - 会员服务 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1,
              activeMenu: '/company/service/order'
            }
          },
          {
            path: '/company/service/increment', //增值服务
            name: 'CompanyServiceIncrement',
            component: () => import('../views/company/service/Increment'),
            meta: {
              title: '增值服务 - 会员服务 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1
            }
          },
          {
            path: '/company/service/increment/add/:type?', //增值服务购买
            name: 'CompanyServiceIncrementAdd',
            component: () => import('../views/company/service/IncrementAdd'),
            meta: {
              title: '增值服务 - 会员服务 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1,
              activeMenu: '/company/service/increment'
            }
          },
          {
            path: '/company/service/setmeal/add', //套餐购买
            name: 'CompanyServiceSetmealAdd',
            component: () => import('../views/company/service/SetmealAdd'),
            meta: {
              title: '升级套餐 - 会员服务 - 企业会员中心',
              keepAlive: false,
              loginCheck: true,
              utype: 1,
              activeMenu: '/company/service/setmeal'
            }
          }
        ]
      }
    ]
  },
  {
    path: '/video_test/:interview_id', //视频面试测试
    name: 'Video',
    component: () => import('../views/vi/Test'),
    meta: {
      title: '视频面试-测试',
      keepAlive: false,
      loginCheck: true,
      utype: 0
    }
  },
  {
    path: '/video/:interview_id', //视频面试
    name: 'Video',
    component: () => import('../views/vi/Main'),
    meta: {
      title: '视频面试',
      keepAlive: false,
      loginCheck: true,
      utype: 0
    }
  },
  {
    path: '/ipdeny',
    component: () => import('@/views/ipdeny'),
    meta: { title: '禁止访问' }
  },
  {
    path: '/error',
    component: () => import('@/views/Error'),
    meta: { title: '系统提示' }
  },
  {
    path: '*',
    component: () => import('@/views/404'),
    meta: { title: '404 Not Found' }
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
