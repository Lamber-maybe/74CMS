export default {
  // 获取全局配置参数信息
  global_config: '/home/config/index',
  // 获取枚举类数据
  classify: '/home/classify/index',
  // 获取用户微信openid
  get_weixin_openid: '/home/wechat/getOpenid',
  // 注册发送短信
  get_reg_code: '/member/sendsms/reg',
  // 企业注册
  reg_company: '/member/reg/company',
  // 个人注册
  reg_personal: '/member/reg/personal',
  // 账号密码登录
  login_pwd: '/member/login/password',
  // 验证码登录
  login_code: '/member/login/code',
  // 发送登录验证码
  get_login_code: '/member/sendsms/login',
  // 首页
  index_common: '/home/index/index',
  // 职位列表
  joblist: '/home/job/index',
  // 职位详情
  jobshow: '/home/job/show',
  // 简历列表
  resumelist: '/home/resume/index',
  // 简历详情
  resumeshow: '/home/resume/show',
  // 企业列表
  companylist: '/home/company/index',
  // 企业详情
  companyshow: '/home/company/show',
  // 职位竞争力分析
  competitiveness: '/home/job/competitiveness',
  // 申请职位
  jobapply: '/member/index/jobApplyAdd',
  // 收藏职位
  jobfav: '/member/index/favJobAdd',
  // 取消收藏职位
  jobfav_cancel: '/member/index/favJobCancel',
  // 关注企业
  company_attention: '/member/index/attentionCompanyAdd',
  // 取消关注企业
  company_attention_cancel: '/member/index/attentionCompanyCancel',
  // 下载简历
  resumedownload: '/member/index/downResumeAdd',
  // 收藏简历
  resumefav: '/member/index/favResumeAdd',
  // 取消收藏简历
  resumefav_cancel: '/member/index/favResumeCancel',
  // 热门职位
  hotwordlist: '/home/hotword/index',
  // 编辑简历
  editResume: '/personal/resume/detail',
  // 上传文件
  uploadFile: '/member/upload/index',
  // 获取求职意向
  get_intentions: '/member/recommend/getIntentions',
  // 获取推荐的职位列表
  recommend_joblist: '/member/recommend/job',
  // 简历基本资料保存
  resume_basic_save: '/personal/resume/basicSave',
  // 简历求职意向保存
  resume_intention_save: '/personal/resume/intentionSave',
  // 简历求职意向删除
  resume_intention_delete: '/personal/resume/intentionDelete',
  // 简历教育经历保存
  resume_education_save: '/personal/resume/educationSave',
  // 简历教育经历删除
  resume_education_delete: '/personal/resume/educationDelete',
  // 简历工作经历保存
  resume_work_save: '/personal/resume/workSave',
  // 简历工作经历删除
  resume_work_delete: '/personal/resume/workDelete',
  // 简历培训经历保存
  resume_train_save: '/personal/resume/trainingSave',
  // 简历培训经历删除
  resume_train_delete: '/personal/resume/trainingDelete',
  // 简历项目经历保存
  resume_project_save: '/personal/resume/projectSave',
  // 简历项目经历删除
  resume_project_delete: '/personal/resume/projectDelete',
  // 简历证书保存
  resume_certificate_save: '/personal/resume/certificateSave',
  // 简历证书删除
  resume_certificate_delete: '/personal/resume/certificateDelete',
  // 简历语言能力保存
  resume_language_save: '/personal/resume/languageSave',
  // 简历语言能力删除
  resume_language_delete: '/personal/resume/languageDelete',
  // 简历自我描述保存
  resume_specialty_save: '/personal/resume/specialty',
  // 简历标签保存
  resume_tag_save: '/personal/resume/tag',
  // 简历刷新
  resume_refresh: '/personal/resume/refresh',
  // 简历求职状态保存
  resume_current_save: '/personal/resume/currentSave',
  // 简历作品保存
  resume_img_save: '/personal/resume/imgAdd',
  // 简历作品删除
  resume_img_delete: '/personal/resume/imgDelete',
  // 简历头像照片保存
  resume_upload_photo: '/personal/resume/uploadPhoto',
  // 获取简历求职意向
  resume_get_intention: '/personal/resume/intentionList',
  // 设置简历显示状态
  resume_privates_set_display: '/personal/privates/setDisplay',
  // 添加屏蔽企业
  resume_privates_add_blacklist: '/personal/privates/addBlacklist',
  // 获取隐私设置信息
  resume_privates_info: '/personal/privates/index',
  // 设置姓名显示状态
  resume_privates_set_display_name: '/personal/privates/setDisplayName',
  // 添加屏蔽企业时搜索企业
  resume_privates_searchCompany: '/personal/privates/searchCompany',
  // 删除屏蔽企业
  resume_privates_deleteBlacklist: '/personal/privates/deleteBlacklist',
  // 个人面试邀请列表
  personal_manage_interview: '/personal/interview/index',
  // 个人面试邀请设为已查看
  personal_manage_interview_setLook: '/personal/interview/setLook',
  // 个人视频面试邀请列表
  personal_manage_interview_video: '/personal/interview_video/index',
  // 个人视频面试设为已查看
  personal_manage_interview_video_setLook: '/personal/interview_video/setLook',
  // 个人视频面试提醒企业
  personal_manage_interview_video_notice: '/personal/interview_video/notice',
  // 个人申请职位记录
  personal_manage_job_apply: '/personal/job_apply/index',
  // 个人被关注记录
  personal_manage_attention_me: '/personal/attention_me/index',
  // 个人足迹（查看职位记录）
  personal_manage_view_job: '/personal/view_job/index',
  // 个人关注的企业列表
  personal_manage_attention_company: '/personal/attention_company/index',
  // 个人取消关注企业
  personal_manage_attention_company_cancel: '/personal/attention_company/cancel',
  // 个人职位收藏列表
  personal_manage_fav_job: '/personal/fav_job/index',
  // 个人取消收藏职位
  personal_manage_fav_job_cancel: '/personal/fav_job/cancel',
  // 账号管理
  account_manage: '/member/account/index',
  // 重置用户名
  reset_username: '/member/account/resetUsername',
  // 重置手机号
  reset_mobile: '/member/account/resetMobile',
  // 重置密码
  reset_password: '/member/account/resetPassword',
  // 重置邮箱
  reset_email: '/member/account/resetEmail',
  // 验证手机号发送验证码
  sendsms_auth_mobile: '/member/sendsms/authMobile',
  // 地图搜索
  mapsearch: '/home/job/map',
  // 获取已发布的职位列表
  get_publish_jobs: '/member/recommend/getJobs',
  // 获取推荐的简历列表
  recommend_resumelist: '/member/recommend/resume',
  // 即时通讯快捷用语
  im_quick_msglist: '/member/im/quickMsglist',
  // 新闻分类列表
  news_categorylist: '/home/article/category',
  // 新闻列表
  news_list: '/home/article/index',
  // 新闻详情
  news_show: '/home/article/show',
  // 公告列表
  notice_list: '/home/notice/index',
  // 公告详情
  notice_show: '/home/notice/show',
  // 企业会员中心首页
  company_index: '/company/index/index',
  // 会员中心获取企业资料
  company_profile: '/company/profile/index',
  // 上传企业风采
  company_img_upload: '/company/profile/uploadImg',
  // 删除企业风采
  company_img_del: '/company/profile/deleteImg',
  // 专属客服
  customer_service: '/member/info/customerService',
  // 投诉专属客服
  customer_service_complaint: '/member/info/customerServiceComplaint',
  // 账号管理
  member_account: '/member/account/index',
  // 企业收到的简历列表
  company_jobapply_list: '/company/job_apply/index',
  // 企业收到的简历设为已婉拒
  company_jobapply_set_refuse: '/company/job_apply/setRefuse',
  // 企业收到的简历删除
  company_jobapply_del: '/company/job_apply/delete',
  // 企业收到的设为已查看
  company_jobapply_set_looked: '/company/job_apply/setLook',
  // 企业收到的设为同意前置操作
  company_jobapply_set_agree_pre: '/company/job_apply/setAgreePre',
  // 企业收到的邀请面试
  company_jobapply_set_agree: '/company/job_apply/setAgree',
  // 备注简历
  remark_resume: '/company/index/remarkResume',
  // 企业已下载简历列表
  company_downresume_list: '/company/down_resume/index',
  // 获取企业在招职位
  company_published_jobslist: '/company/index/joball',
  // 邀请面试
  interview_add: '/member/index/interviewAdd',
  // 企业面试邀请列表
  company_interview_list: '/company/interview/index',
  // 企业视频面试邀请列表
  company_interview_video_list: '/company/interview_video/index',
  // 企业视频面试提醒求职者
  company_interview_video_notice: '/company/interview_video/notice',
  // 企业收藏的简历列表
  company_fav_list: '/company/fav_resume/index',
  // 企业收藏的简历取消收藏
  company_fav_cancel: '/company/fav_resume/cancel',
  // 企业已查看简历列表
  company_view_resume_list: '/company/view_resume/index',
  // 企业已查看简历删除
  company_view_resume_del: '/company/view_resume/delete',
  // 获取企业认证信息
  company_authinfo: '/company/auth/index',
  // 营业执照认证提交
  company_auth_license: '/company/auth/license',
  // 负责人认证提交
  company_auth_legal: '/company/auth/legal_personal',
  // 查询可发布职位数
  company_check_jobadd_num: '/company/job/checkJobaddNumber',
  // 发布职位预请求
  company_jobadd_pre: '/company/job/addPre',
  // 发布职位保存
  company_jobadd_save: '/company/job/addSave',
  // 修改职位预请求
  company_jobedit_pre: '/company/job/editPre',
  // 修改职位保存
  company_jobedit_save: '/company/job/editSave',
  // 企业职位管理
  company_joblist: '/company/job/index',
  // 企业刷新职位
  company_job_refresh: '/company/job/refresh',
  // 企业刷新职位-批量
  company_job_refresh_batch: '/company/job/refreshBatch',
  // 企业更改职位状态
  company_job_set_display: '/company/job/setDisplay',
  // 优惠券列表
  coupon_list: '/company/service/couponList',
  // 我的积分
  member_points: '/member/info/myPoints',
  // 签到
  member_signin: '/member/sign_in/index',
  // 积分收支记录
  member_pointslog: '/member/info/pointsLog',
  // 企业订单记录
  company_orderlist: '/company/service/orderList',
  // 企业订单详情
  company_orderdetail: '/company/service/orderDetail',
  // 个人订单详情
  personal_orderdetail: '/personal/service/orderDetail',
  // 积分套餐列表
  points_setmeal_list: '/company/service/pointsList',
  // 企业下订单
  company_pay: '/company/service/pay',
  // 企业下订单（快捷支付）
  company_pay_direct_service: '/company/service/pay_direct_service',
  // 企业订单继续支付
  company_repay: '/company/service/repay',
  // 企业订单取消订单
  company_order_cancel: '/company/service/orderCancel',
  // 企业订单删除订单
  company_order_del: '/company/service/orderDel',
  // 个人订单取消订单
  personal_order_cancel: '/personal/service/orderCancel',
  // 个人订单删除订单
  personal_order_del: '/personal/service/orderDel',
  // 我的套餐
  member_setmeal: '/company/service/mysetmeal',
  // 增值服务列表
  company_servicelist: '/company/service/serviceList',
  // 企业套餐列表
  company_setmeallist: '/company/service/setmealList',
  // 企业套餐使用明细
  company_setmeallog: '/member/info/setmealLog',
  // 会员消息提醒列表
  member_msglist: '/member/account/msglist',
  // 会员登录日志列表
  member_loginlog: '/member/account/loginlog',
  // 找回密码发送邮件
  sendmail_forget: '/member/sendmail/forget',
  // 绑定邮箱发送邮件
  sendmail_bind: '/member/sendmail/bind',
  // 找回密码发送短信
  sendsms_forget: '/member/sendsms/forget',
  // 重置密码-手机验证码方式
  set_pwd_bymobile: '/member/forget/byMobile',
  // 重置密码-邮箱验证码方式
  set_pwd_byemail: '/member/forget/byEmail',
  // 意见反馈
  feedback: '/member/info/feedback',
  // 个人订单记录
  personal_orderlist: '/personal/service/orderList',
  // 个人下订单
  personal_pay: '/personal/service/pay',
  // 个人订单继续支付
  personal_repay: '/personal/service/repay',
  // 个人服务列表
  personal_servicelist: '/personal/service/serviceList',
  // 注册协议和隐私政策
  agreement: '/home/config/agreementAndPrivacy',
  // 验证手机号发送验证码(修改联系方式手机号、账号申诉时使用)
  sendsms_auth_mobile_nocheck: '/member/sendsms/authMobileNoCheck',
  // 账号申诉
  member_appeal: '/member/appeal/index',
  // 删除职位
  company_job_del: '/company/job/del',
  // 广告
  ad_list: '/home/ad/index',
  // rtc配置
  rtc_config: '/home/config/webrtc',
  // 注册简历 - 表单形式 - 第一步
  reg_resume_form_step1: '/personal/resume_reg_by_app_form/step1',
  // 注册简历 - 表单形式 - 第二步
  reg_resume_form_step2: '/personal/resume_reg_by_app_form/step2',
  // 注册简历 - 交互形式 - 第一步
  reg_resume_int_step1: '/personal/resume_reg_by_app_interactive/step1',
  // 注册简历 - 交互形式 - 第二步
  reg_resume_int_step2: '/personal/resume_reg_by_app_interactive/step2',
  // 注册简历 - 交互形式 - 第三步
  reg_resume_int_step3: '/personal/resume_reg_by_app_interactive/step3',
  // 注册简历 - 交互形式 - 第四步
  reg_resume_int_step4: '/personal/resume_reg_by_app_interactive/step4',
  // 注册简历 - 交互形式 - 第四步（无工作经验）
  reg_resume_int_step4_nowork: '/personal/resume_reg_by_app_interactive/stepNoWork',
  // 验证手机号
  auth_mobile: '/member/auth/mobile',
  // 获取简历完整度信息
  resume_complete_info: '/personal/resume/getResumeCompleteInfo',
  // 注销账号
  account_cancel_apply: '/member/account/cancelApply',
  // 举报
  tipoff: '/member/info/tipoff',
  // 二维码
  get_qrcode: '/home/qrcode/index',
  // 微信分享参数
  wechat_share_config: '/home/share/wechat',
  // 微海报-根据职位数获取模板
  microposte_get_tpl_by_jobnum: '/company/microposte/tpl',
  // 微海报-获取职位列表
  microposte_get_joblist: '/company/microposte/joblist',
  // 获取图片验证码
  captcha_picture: '/home/captcha/picture',
  // 解绑
  unbind: '/member/account/unbind',
  // oauth获取qq用户信息
  oauth_qq_getuserinfo: '/member/oauth/qq',
  // oauth获取qq用户信息后登录
  oauth_qq_login: '/member/login/qq',
  // oauth获取qq用户信息后绑定已登录账号
  oauth_qq_bind: '/member/account/bindQq',
  // oauth获取微信用户信息
  oauth_weixin_getuserinfo: '/member/oauth/weixinOffiaccount',
  // oauth获取微信用户信息后登录
  oauth_weixin_login: '/member/login/weixin',
  // oauth获取weixin用户信息后绑定已登录账号
  oauth_weixin_bind: '/member/account/bindWeixin',
  // 微信绑定已有账号或注册新账号
  bind_weixin: '/member/bind/weixin',
  // QQ绑定已有账号或注册新账号
  bind_qq: '/member/bind/qq',
  // 实地认证信息
  company_report: '/home/company/report',
  // 计划任务
  cron_run: '/home/cron/index',
  // 网络招聘会列表
  jobfairol: '/home/jobfairol/index',
  // 网络招聘会已报名
  jobfairol_reserve_list: '/company/jobfairol/index',
  // 网络招聘会详情页
  jobfairol_show: '/home/jobfairol/show',
  // 网络招聘会企业列表
  jobfairol_comlist: '/home/jobfairol/comlist',
  // 网络招聘会职位列表
  jobfairol_joblist: '/home/jobfairol/joblist',
  // 网络招聘会简历列表
  jobfairol_resumelist: '/home/jobfairol/resumelist',
  // 网络招聘会参会
  jobfairol_apply: '/home/jobfairol/apply',
  // 号码保护
  secret_phone: '/member/index/secretPhone',
  // 场景码扫描添加记录
  scene_record: '/home/index/scenerecord',
  // 海报
  create_poster: '/home/poster/index',
  // 页面管理详情
  pageinfo: '/home/config/pageinfo'
}
