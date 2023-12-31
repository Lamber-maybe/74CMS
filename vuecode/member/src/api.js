export default {
    // 获取全局配置参数信息
    global_config: '/home/config/index',
    // 获取枚举类数据
    classify: '/home/classify/index',
    //获取用户信息
    userinfo: '/home/config/userinfo',
    //退出登录
    logout: '/member/login/logout',
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
    // 下载简历
    resumedownload: '/member/index/downResumeAdd',
    // 编辑简历
    editResume: '/personal/resume/detail',
    // 上传文件
    uploadFile: '/member/upload/index',
    // 获取求职意向
    get_intentions: '/member/recommend/getIntentions',
    // 获取推荐的职位列表
    recommend_joblist: '/member/recommend/job',
    // 获取推荐的职位列表统计
    recommend_joblist_total: '/member/recommend/jobTotal',
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
    // 个人面试邀请列表统计
    personal_manage_interview_total: '/personal/interview/total',
    // 个人面试邀请设为已查看
    personal_manage_interview_setLook: '/personal/interview/setLook',
    // 个人视频面试邀请列表
    personal_manage_interview_video: '/personal/interview_video/index',
    // 个人视频面试邀请列表统计
    personal_manage_interview_video_total: '/personal/interview_video/total',
    // 个人视频面试设为已查看
    personal_manage_interview_video_setLook: '/personal/interview_video/setLook',
    // 个人视频面试提醒企业
    personal_manage_interview_video_notice: '/personal/interview_video/notice',
    // 个人申请职位记录
    personal_manage_job_apply: '/personal/job_apply/index',
    // 个人申请职位记录统计
    personal_manage_job_apply_total: '/personal/job_apply/total',
    // 个人申请职位记录删除
    personal_manage_job_apply_del: '/personal/job_apply/delete',
    // 个人申请职位记录删除（批量）
    personal_manage_job_apply_del_batch: '/personal/job_apply/deleteBatch',
    // 个人被关注记录
    personal_manage_attention_me: '/personal/attention_me/index',
    // 个人被关注记录统计
    personal_manage_attention_me_total: '/personal/attention_me/total',
    // 个人足迹（查看职位记录）
    personal_manage_view_job: '/personal/view_job/index',
    // 个人足迹（查看职位记录）统计
    personal_manage_view_job_total: '/personal/view_job/total',
    // 删除查看职位记录
    personal_manage_view_job_del: '/personal/view_job/delete',
    // 删除查看职位记录（批量）
    personal_manage_view_job_del_batch: '/personal/view_job/deleteBatch',
    // 个人关注的企业列表
    personal_manage_attention_company: '/personal/attention_company/index',
    // 个人关注的企业列表统计
    personal_manage_attention_company_total: '/personal/attention_company/total',
    // 个人取消关注企业
    personal_manage_attention_company_cancel: '/personal/attention_company/cancel',
    // 个人取消关注企业（批量）
    personal_manage_attention_company_cancel_batch: '/personal/attention_company/cancelBatch',
    // 个人职位收藏列表
    personal_manage_fav_job: '/personal/fav_job/index',
    // 个人职位收藏列表统计
    personal_manage_fav_job_total: '/personal/fav_job/total',
    // 个人取消收藏职位
    personal_manage_fav_job_cancel: '/personal/fav_job/cancel',
    // 个人取消收藏职位(批量)
    personal_manage_fav_job_cancel_batch: '/personal/fav_job/cancelBatch',
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
    // 获取已发布的职位列表
    get_publish_jobs: '/member/recommend/getJobs',
    // 获取推荐的简历列表
    recommend_resumelist: '/member/recommend/resume',
    // 获取推荐的简历列表统计
    recommend_resumelist_total: '/member/recommend/resumeTotal',
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
    // 企业收到的简历列表统计
    company_jobapply_list_total: '/company/job_apply/total',
    // 企业收到的简历查看率统计
    company_jobapply_watch_situation: '/company/job_apply/watchSituation',
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
    // 企业已下载简历列表统计
    company_downresume_list_total: '/company/down_resume/total',
    // 获取企业在招职位
    company_published_jobslist: '/company/index/joball',
    // 邀请面试
    interview_add: '/member/index/interviewAdd',
    // 企业面试邀请列表
    company_interview_list: '/company/interview/index',
    // 企业面试邀请列表统计
    company_interview_list_total: '/company/interview/total',
    // 企业视频面试邀请列表
    company_interview_video_list: '/company/interview_video/index',
    // 企业视频面试邀请列表统计
    company_interview_video_list_total: '/company/interview_video/total',
    // 企业视频面试提醒求职者
    company_interview_video_notice: '/company/interview_video/notice',
    // 企业收藏的简历列表
    company_fav_list: '/company/fav_resume/index',
    // 企业收藏的简历列表统计
    company_fav_list_total: '/company/fav_resume/total',
    // 企业收藏的简历取消收藏
    company_fav_cancel: '/company/fav_resume/cancel',
    // 企业收藏的简历取消收藏（批量）
    company_fav_cancel_batch: '/company/fav_resume/cancelBatch',
    // 企业已查看简历列表
    company_view_resume_list: '/company/view_resume/index',
    // 企业已查看简历列表统计
    company_view_resume_list_total: '/company/view_resume/total',
  // 企业被查看简历列表
  company_view_beresume_list: '/company/view_be_browsed/index',
  // 企业被查看简历列表统计
  company_view_beresume_list_total: '/company/view_be_browsed/total',
    // 企业已查看简历删除
    company_view_resume_del: '/company/view_resume/delete',
    // 企业已查看简历删除(批量)
    company_view_resume_del_batch: '/company/view_resume/deleteBatch',
    // 获取企业认证信息
    company_authinfo: '/company/auth/index',
    // 营业执照认证提交
    company_auth_license: '/company/auth/license',
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
    // 企业职位管理统计
    company_joblist_total: '/company/job/total',
    // 企业刷新职位
    company_job_refresh: '/company/job/refresh',
    // 企业刷新职位-批量
    company_job_refresh_batch: '/company/job/refreshBatch',
    // 企业更改职位状态
    company_job_set_display: '/company/job/setDisplay',
    // 企业批量关闭职位
    company_job_set_close_batch: '/company/job/setCloseBatch',
    // 优惠券列表
    coupon_list: '/company/service/couponList',
    // 我的积分
    member_points: '/member/info/myPoints',
    // 签到
    member_signin: '/member/sign_in/index',
    // 查看当日是否签到
    member_user_ignin: '/member/sign_in/userSignin',
    // 积分收支记录
    member_pointslog: '/member/info/pointsLog',
    // 积分收支记录统计
    member_pointslog_total: '/member/info/pointsLogTotal',
    // 企业订单记录
    company_orderlist: '/company/service/orderList',
    // 企业订单记录统计
    company_orderlist_total: '/company/service/orderListTotal',
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
    // 企业套餐使用明细统计
    company_setmeallog_total: '/member/info/setmealLogTotal',
    // 会员消息提醒列表
    member_msglist: '/member/account/msglist',
    // 会员消息提醒列表统计
    member_msglist_total: '/member/account/msglistTotal',
    //会员消息提醒未读查询
    member_msgunread: '/member/account/msgunread',
    // 会员登录日志列表
    member_loginlog: '/member/account/loginlog',
    // 会员登录日志列表统计
    member_loginlog_total: '/member/account/loginlogTotal',
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
    // 个人订单记录统计
    personal_orderlist_total: '/personal/service/orderListTotal',
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
    // 批量删除职位
    company_job_del_batch: '/company/job/delBatch',
    // 广告
    ad_list: '/home/ad/index',
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
    // 二维码
    get_qrcode: '/home/qrcode/index',
    // 扫描微信支付结果
    scan_wxpay_result: '/home/pay/scanWxpay',
    // 获取图片验证码
    captcha_picture: '/home/captcha/picture',
    // 解绑
    unbind: '/member/account/unbind',
    //扫描扫码上传结果
    scan_upload_result: '/member/scan_upload_result/index',
    //oauth获取qq用户信息
    oauth_qq_getuserinfo: '/member/oauth/qq',
    //oauth获取qq用户信息后登录
    oauth_qq_login: '/member/login/qq',
    //oauth获取qq用户信息后绑定已登录账号
    oauth_qq_bind: '/member/account/bindQq',
    //oauth获取微信用户信息
    oauth_weixin_getuserinfo: '/member/oauth/weixin',
    //oauth获取微信用户信息后登录
    oauth_weixin_login: '/member/login/weixin',
    //微信绑定已有账号或注册新账号
    bind_weixin: '/member/bind/weixin',
    //QQ绑定已有账号或注册新账号
    bind_qq: '/member/bind/qq',
    //企业会员中心首页招聘效果统计
    company_index_stat: '/company/index/stat',
    //职位订阅保存
    personal_subscribe_job_save: '/personal/subscribe_job/submit',
    //取消职位订阅
    personal_subscribe_job_cancel: '/personal/subscribe_job/cancel',
    //获取职位订阅信息
    personal_subscribe_job: '/personal/subscribe_job/index',
    //简历基本资料保存头像
    resume_upload_photo: '/personal/resume/uploadPhoto',
    //委托投递保存
    personal_entrust_save: '/personal/entrust/submit',
    //委托投递取消
    personal_entrust_cancel: '/personal/entrust/cancel',
    //获取委托投递信息
    personal_entrust: '/personal/entrust/index',
    //个人会员中心首页统计
    personal_index: '/personal/index/index',
    //执行计划任务
    cron_run: '/home/cron/index',
    //网络招聘会列表
    company_jobfairol: '/home/jobfairol/index',
    //已报名网络招聘会列表
    company_jobfairol_reserve: '/company/jobfairol/index',
    // 简历详情
    resumeshow: '/home/resume/show',
    // rtc配置
    rtc_config: '/home/config/webrtc',
    //委托书下载
    downloadproxy: '/home/index/downloadproxy',
   
    //职位分类模板
    categoryjob_template_list: '/company/job/getCategoryJobTemplate',
    // 面试邀请数据预加载
    interview_add_pre: '/member/index/interviewAddPre',
    // 海报
    create_poster: '/home/poster/index',
    download_poster: '/home/poster/download',
    // 海报索引列表
    poster_tplindex_list: '/home/poster/getTplindexList',
    // 会话access_token
    im_gettoken: '/member/Im/getToken',
    // 会话记录
    im_chat_list: '/member/Im/chatList',
    // 聊天记录
    im_message_list: '/member/Im/messageList',
    // 当前用户详情
    im_userinfo: '/member/Im/userinfo',
    // 删除会话
    im_remove_chat: '/member/Im/removeChat',
    //职位详情
    im_job_detail: '/member/Im/jobinfo',
    // 简历详情
    im_resume_detail: '/member/Im/resumeinfo',
    // 投递简历
    im_job_apply: '/member/index/jobApplyAdd',
    // 即时通讯快捷用语
    im_phrase_list: '/member/im/phraseList',
    // 添加即时通讯快捷用语
    im_phrase_add: '/member/im/phraseAdd',
    // 修改即时通讯快捷用语
    im_phrase_edit: '/member/im/phraseEdit',
    // 删除即时通讯快捷用语
    im_phrase_del: '/member/im/phraseDel',
    // 修改即时通讯快捷用语排序
    im_phrase_sort: '/member/im/phraseSort',
    // 即时通讯切换职位列表
    im_joblist: '/member/im/joblist',
    // 即时通讯切换职位
    im_change_job: '/member/im/changejob',
    // 即时通讯置顶聊天
    im_chat_stick: '/member/im/chatStick',
    // 即时通讯招呼语列表
    im_hellomsg_list: '/member/im/hellomsgList',
    // 即时通讯招呼语选择
    im_hellomsg_select: '/member/im/hellomsgSelect',
    // 即时通讯黑名单
    im_blacklist: '/member/im/blacklist',
    // 即时通讯黑名单添加
    im_blacklist_add: '/member/im/blacklistAdd',
    // 即时通讯黑名单移出
    im_blacklist_del: '/member/im/blacklistDel',
    // 即时通讯检查是否在黑名单
    im_blacklist_check: '/member/im/blacklistCheck',
    //即时通讯获取面试信息
    im_interview_info: '/member/im/interviewInfo',
    //即时通讯进入界面时全局检测
    im_window_global: '/member/im/imWindowGlobal',
    //即时通讯检查绑定状态
    im_check_bind:'/member/im/imCheckBind',
    // 获取优化简历详情
    defectResumeInfo: '/personal/resume/getNoComplete',
    // 【新增】职聊关键字屏蔽功能 zch 2022.10.18
    im_keyword_replace: '/member/im/keywordReplace',
  //简历解析 -上传附件简历
  enclosureSave: '/personal/resume/enclosureSave',
  //简历解析 -附件简历删除
  enclosureDelete: '/personal/resume/enclosureDelete',
  //简历解析 -附件简历详情
  enclosureResumeDetail: '/personal/resume/detail',
  //创建简历解析
  analysisCreate: '/personal/analysis/create',
  //获取简历解析回调状态
  analysisIsNotify: '/personal/analysis/isNotify',
  //导入创建简历
  importResumeCreate: '/personal/resume_reg_by_analysis/importCreate',
  // 校验简历解析配置
  documentCheck: '/home/zhitoo/documentCheck',
  // 发送注销验证码
  cancelApply: '/member/sendsms/cancelApply',
  // 导出PDF简历
  exportPdfByPhp: '/home/resume/exportPdfByPhp',
  // 生成企业LOGO头像
  sendCompanyLogo: '/company/index/sendCompanyLogo',
  // 会员收到投递面试邀请新消息
  isLookApply: '/member/index/isLookApply'
}
