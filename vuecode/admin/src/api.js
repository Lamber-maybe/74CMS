import {
  saveJobSeting
} from '@/api/datacollection'
import {
  clueDelete,
  clueExport,
  clueReceive,
  clueRelease,
  companyCrmAdd,
  companyCrmEdit,
  companyJob,
  companyList,
  companyReceive,
  crmClueRelease,
  deletedCustomers,
  deleteImg,
  exportList,
  refreshCrmJob,
  setDisplay
} from '@/api/company_crm'
import {
  customerServiceEdit
} from '@/api/personal'
import {
  bindSeat,
  customLndex,
  dataBoardcallRecordList,
  outboundSeat,
  recordDetails
} from '@/api/outbound'
import {
  resumeDel,
  resumeUrmInterview,
  resumeUrmViewJob,
  urmList
} from '@/api/resume_urm'
import {
  companyBackupsList
} from '@/api/member_cancel_apply'
import {
  jobfairOlModifyState
} from '@/api/jobfairol'
import {
  jobModifyState
} from "@/api/job";

export default {
  // 首页仪表盘
  dashboardBaseinfo: '/index/index',
  officialData: '/index/officialData',
  dashboardChart: '/index/chart',
  // 登录页图片验证码
  captchaSrc: '/login/captcha',
  // 获取/保存基础配置
  setConfig: '/config/index',
  // 清除缓存
  clearCache: '/config/clearCache',
  // 获取全局基础配置
  getConfigCache: '/login/config',
  // 上传
  upload: '/upload/index',
  // 广告上传
  uploadAd: '/upload/ad',
  // 编辑器上传
  uploadEditor: '/upload/editor',
  // 编辑器上传视频
  uploadEditorVideo: '/upload/editorVideo',
  // 附件上传
  uploadAttach: '/upload/attach',
  // 上传hr工具箱
  uploadHrtool: '/hrtool/upload',
  // 上传微信素材
  uploadWechatMedia: '/upload/wechatMedia',
  // 上传海报
  uploadPoster: '/upload/poster',
  // 短信模板配置
  smsTplList: '/config/smsTplList',
  // 扩展字段自定义
  setFieldRule: '/config/fieldRule',
  // 获取字段自定义规则
  getFieldRule: '/config/getFieldRule',
  // 简历模块自定义
  setResumeModule: '/config/resumeModule',
  // 获取通知规则
  getNotifyRule: '/config/getNotifyRule',
  // 通知规则
  setNotifyRule: '/config/setNotifyRule',
  // 切换微信模板类型
  switchWechatTplType: 'config/switchWechatTplType',
  // 获取微信通知规则
  getWechatNotifyRule: '/config/getWechatNotifyRule',
  // 微信通知规则
  setWechatNotifyRule: '/config/setWechatNotifyRule',
  // 微信关键字配置
  wechatKeywordList: '/wechat_keyword/index',
  wechatKeywordAdd: '/wechat_keyword/add',
  wechatKeywordEdit: '/wechat_keyword/edit',
  wechatKeywordDelete: '/wechat_keyword/delete',
  // 微信菜单配置
  wechatMenuList: '/wechat_menu/index',
  wechatMenuAdd: '/wechat_menu/add',
  wechatMenuEdit: '/wechat_menu/edit',
  wechatMenuDelete: '/wechat_menu/delete',
  wechatMenuSync: '/wechat_menu/sync',
  // 微信分享配置
  wechatShareList: '/wechat_share/index',
  wechatShareAdd: '/wechat_share/add',
  wechatShareEdit: '/wechat_share/edit',
  wechatShareDelete: '/wechat_share/delete',

  // 资讯
  articleList: '/article/index',
  articleAdd: '/article/add',
  articleEdit: '/article/edit',
  articleDelete: '/article/delete',
  articleModifyState: '/article/articleModifyState',

  // 资讯分类
  articleCategoryList: '/article_category/index',
  articleCategoryAdd: '/article_category/add',
  articleCategoryEdit: '/article_category/edit',
  articleCategoryDelete: '/article_category/delete',
  // 帮助中心
  helpList: '/help/index',
  helpAdd: '/help/add',
  helpEdit: '/help/edit',
  helpDelete: '/help/delete',
  helpModifyState: '/help/helpModifyState',

  // 帮助中心分类
  helpCategoryList: '/help_category/index',
  helpCategoryAdd: '/help_category/add',
  helpCategoryEdit: '/help_category/edit',
  helpCategoryDelete: '/help_category/delete',

  // Hr工具箱
  hrtoolList: '/hrtool/index',
  hrtoolAdd: '/hrtool/add',
  hrtoolEdit: '/hrtool/edit',
  hrtoolDelete: '/hrtool/delete',

  // Hr工具箱分类
  hrtoolCategoryList: '/hrtool_category/index',
  hrtoolCategoryAdd: '/hrtool_category/add',
  hrtoolCategoryEdit: '/hrtool_category/edit',
  hrtoolCategoryDelete: '/hrtool_category/delete',
  // 账号申诉
  appealList: '/appeal/index',
  appealHandler: '/appeal/handler',
  appealDelete: '/appeal/delete',
  // 举报信息
  tipoffList: '/tipoff/index',
  tipoffHandler: '/tipoff/handler',
  tipoffDelete: '/tipoff/delete',
  // 意见建议
  feedbackList: '/feedback/index',
  feedbackHandler: '/feedback/handler',
  feedbackDelete: '/feedback/delete',
  // 投诉客服
  complaintList: '/complaint/index',
  complaintHandler: '/complaint/handler',
  complaintDelete: '/complaint/delete',

  // 广告
  adList: '/ad/index',
  adAdd: '/ad/add',
  adEdit: '/ad/edit',
  adDelete: '/ad/delete',
  adInnerLinkOptions: '/ad/innerLinkOptions',
  adState: '/ad/adModifyState',

  // 广告位
  adCategoryList: '/ad_category/index',
  adCategoryAdd: '/ad_category/add',
  adCategoryEdit: '/ad_category/edit',
  adCategoryDelete: '/ad_category/delete',

  // 公告
  noticeList: '/notice/index',
  noticeAdd: '/notice/add',
  noticeEdit: '/notice/edit',
  noticeDelete: '/notice/delete',
  noticeModifyState: '/notice/noticeModifyState',

  // 说明页
  explainList: '/explain/index',
  explainAdd: '/explain/add',
  explainEdit: '/explain/edit',
  explainDelete: '/explain/delete',
  explainModifyState: '/explain/explainModifyState',
  // 友情链接
  flinkList: '/flink/index',
  flinkAdd: '/flink/add',
  flinkEdit: '/flink/edit',
  flinkDelete: '/flink/delete',
  flinkModifyState: '/flink/flinkModifyState',

  // 获取分类缓存
  getClassify: '/classify/index',

  // 地区分类
  configDistrict: '/config_district/index',
  configDistrictAdd: '/config_district/add',
  configDistrictEdit: '/config_district/edit',
  configDistrictOptions: '/config_district/options',
  configDistrictDelete: '/config_district/delete',
  configDistrictTableSave: '/config_district/tablesave',

  // 职位分类
  configJobcategory: '/config_jobcategory/index',
  configJobcategoryAdd: '/config_jobcategory/add',
  configJobcategoryEdit: '/config_jobcategory/edit',
  configJobcategoryOptions: '/config_jobcategory/options',
  configJobcategoryDelete: '/config_jobcategory/delete',
  configJobcategoryTableSave: '/config_jobcategory/tablesave',

  // 专业分类
  configMajor: '/config_major/index',
  configMajorAdd: '/config_major/add',
  configMajorEdit: '/config_major/edit',
  configMajorOptions: '/config_major/options',
  configMajorDelete: '/config_major/delete',
  configMajorTableSave: '/config_major/tablesave',

  // 其他分类组
  configCategoryGroup: '/config_category_group/index',
  configCategoryGroupAdd: '/config_category_group/add',
  configCategoryGroupEdit: '/config_category_group/edit',
  configCategoryGroupDelete: '/config_category_group/delete',

  // 其他分类
  configCategory: '/config_category/index',
  configCategoryAdd: '/config_category/add',
  configCategoryEdit: '/config_category/edit',
  configCategoryDelete: '/config_category/delete',
  configCategoryTableSave: '/config_category/tablesave',

  // 管理员
  adminList: '/admin/index',
  adminAdd: '/admin/add',
  adminEdit: '/admin/edit',
  adminDelete: '/admin/delete',
  adminRoleOptions: '/admin/roleoptions',
  adminLogList: '/admin/loglist',
  adminAllList: '/admin/alladmin',
  adminBindQrcode: '/admin/getBindQrcode',
  adminBindQrcodeCancel: '/admin/bindQrcodeCancel',
  adminIsBindWechat: '/admin/isBindWechat',
  getAdminCrmData: '/admin/getAdminCrmData',
  adminLock: '/admin/lock',
  adminDeblocking: '/admin/deblocking',
  adminLogTypeAll: '/admin/adminLogTypeAll',
  delAdminLog: '/admin/delAdminLog',

  // 管理员角色
  adminRoleList: '/admin_role/index',
  adminRoleAdd: '/admin_role/add',
  adminRoleEdit: '/admin_role/edit',
  adminRoleDelete: '/admin_role/delete',

  // 套餐管理
  setmealList: '/setmeal/index',
  setmealAdd: '/setmeal/add',
  setmealEdit: '/setmeal/edit',
  setmealDelete: '/setmeal/delete',

  // 会员管理
  memberList: '/member/index',
  memberAdd: '/member/add',
  memberEdit: '/member/edit',
  memberDelete: '/member/delete',
  memberCheckUnique: '/member/isUnique',
  memberLock: '/member/lock',
  memberIm: '/member/im',
  memberDetail: '/member/detail',
  memberLoginLog: '/member/loginlog',
  memberActionLog: '/member/actionlog',
  management: '/member/management',

  // 简历管理
  resumeAdd: '/resume/add',
  resumeList: '/resume/index',
  resumeModuleList: '/resume/moduleList',
  resumeBasic: '/resume/basic',
  resumeIntentionList: '/resume/intentionList',
  resumeIntentionAddAndEdit: '/resume/intentionAddAndEdit',
  resumeIntentionDelete: '/resume/intentionDelete',
  resumeSpecialty: '/resume/specialty',
  resumeEducationList: '/resume/educationList',
  resumeEducationAddAndEdit: '/resume/educationAddAndEdit',
  resumeEducationDelete: '/resume/educationDelete',
  resumeWorkList: '/resume/workList',
  resumeWorkAddAndEdit: '/resume/workAddAndEdit',
  resumeWorkDelete: '/resume/workDelete',
  resumeTrainingList: '/resume/trainingList',
  resumeTrainingAddAndEdit: '/resume/trainingAddAndEdit',
  resumeTrainingDelete: '/resume/trainingDelete',
  resumeProjectList: '/resume/projectList',
  resumeProjectAddAndEdit: '/resume/projectAddAndEdit',
  resumeProjectDelete: '/resume/projectDelete',
  resumeCertificateList: '/resume/CertificateList',
  resumeCertificateAddAndEdit: '/resume/CertificateAddAndEdit',
  resumeCertificateDelete: '/resume/CertificateDelete',
  resumeLanguageList: '/resume/LanguageList',
  resumeLanguageAddAndEdit: '/resume/LanguageAddAndEdit',
  resumeLanguageDelete: '/resume/LanguageDelete',
  resumeTag: '/resume/tag',
  resumeImg: '/resume/img',
  resumeImgAdd: '/resume/imgAdd',
  resumeImgDelete: '/resume/imgDelete',
  resumeAudit: '/resume/setAudit',
  resumeLevel: '/resume/setLevel',
  resumeComment: '/resume/setComment',
  resumeDelete: '/resume/delete',
  resumeRefresh: '/resume/refresh',
  // 附件简历
  enclosureSave: '/resume/enclosureSave',
  enclosureDelete: '/resume/enclosureDelete',

  // 照片/作品
  resumeImgList: '/resume_img/index',
  resumeImgAudit: '/resume_img/setAudit',
  resumeImgDel: '/resume_img/delete',
  resumeImgEdit: '/resume_img/edit',

  // 企业管理
  companyList: '/company/index',
  companyAdd: '/company/add',
  companyEdit: '/company/edit',
  companyAudit: '/company/setAudit',
  companyDelete: '/company/delete',
  companySetService: '/company/setService',
  companySetDisplay: '/company/setDisplay',

  // 职位管理
  jobList: '/job/index',
  jobEdit: '/job/edit',
  jobDelete: '/job/delete',
  jobAudit: '/job/setAudit',
  jobRefresh: '/job/refresh',
  jobModifyState: '/job/jobModifyState',
  // 职位推广链接
  jobLink: '/job/jobLink',

  // 企业风采
  companyImgList: '/company_img/index',
  companyImgAudit: '/company_img/setAudit',
  companyImgDelete: '/company_img/delete',
  companyImgDeleteOne: '/company_img/deleteOne',
  companyImgEdit: '/company_img/edit',

  // 面试邀请列表
  companyInterviewList: '/company_interview/index',
  companyInterviewVideoList: '/company_interview_video/index',

  // 简历下载列表
  companyDownList: '/company_down/index',

  // 账号注销申请
  memberCancelApplyList: '/member_cancel_apply/index',
  memberCancelApplyDelete: '/member_cancel_apply/delete',
  memberCancelApplyHandle: '/member_cancel_apply/handle',
  memberCancelApplyDeleteAll: '/member_cancel_apply/deleteAll',
  // 注销申请备份
  memberCancelApplyBackups: '/member_cancel_apply/backups',
  exportBackups: '/member_cancel_apply/exportBackups',
  backupsList: '/member_cancel_apply/backupsList',
  backupsDelete: '/member_cancel_apply/backupsDelete',
  // 优惠券
  couponList: '/coupon/index',
  couponAdd: '/coupon/add',
  couponEdit: '/coupon/edit',
  couponDelete: '/coupon/delete',
  couponLog: '/coupon/log',
  couponRecord: '/coupon/record',
  couponSend: '/coupon/send',
  couponAll: '/coupon/options_all',

  // 计划任务
  cronList: '/cron/index',
  cronAdd: '/cron/add',
  cronEdit: '/cron/edit',
  cronSet: '/cron/setStatus',
  cronRun: '/cron/run',
  cronDelete: '/cron/delete',
  cronLog: '/cron/loglist',

  // 客服配置
  customerServiceList: '/customer_service/index',
  customerServiceAdd: '/customer_service/add',
  customerServiceEdit: '/customer_service/edit',
  customerServiceDelete: '/customer_service/delete',

  // 任务配置
  taskList: '/task/index',
  taskSave: '/task/saveall',

  // 发送测试邮件
  sendMailTest: '/config/sendMailTest',
  // 发送测试短信
  sendSmsTest: '/config/sendSmsTest',
  // 触屏首页个性化设置
  setMobileIndexModule: '/config/setMobileIndexModule',
  // 触屏首页个性化菜单列表
  mobileIndexMenuList: '/config/mobileIndexMenuList',
  // 触屏首页个性化菜单修改
  mobileIndexMenuEdit: '/config/mobileIndexMenuEdit',

  // 企业套餐管理
  companySetmealList: '/company_setmeal/index',
  companySetmealLog: '/company_setmeal/log',
  companySetmealEdit: '/company_setmeal/edit',
  companySetmealAdd: '/company_setmeal/add',
  companySetmealOpenLog: '/company_setmeal/openlog',
  setOpeningTime: '/company_setmeal/setOpeningTime',

  // 订单列表
  orderList: '/order/index',
  orderConfirm: '/order/confirm',
  orderCancel: '/order/cancel',
  orderExport: '/order/order_export',
  // 积分管理
  memberPointsList: '/member/points_list',
  memberPointsEdit: '/member/points_set',
  memberPointsLog: '/member/points_log',

  // 导出
  exportJob: '/export/job',
  exportJobById: '/export/jobById',
  exportCompany: '/export/company',
  exportCompanyById: '/export/companyById',
  exportResume: '/export/resume',

  // im快捷语设置
  imQuickmsgList: '/im_quickmsg/index',
  imQuickmsgSaveAll: '/im_quickmsg/save_all',
  imQuickmsgEdit: '/im_quickmsg/edit',
  imQuickmsgAdd: '/im_quickmsg/add',
  imQuickmsgDelete: '/im_quickmsg/delete',

  // im基本设置
  setImRule: '/im_rule/index',

  // im已禁聊用户
  imForbidList: '/im_forbid/index',

  // 会话管理
  imChatmanageList: '/im_chatmanage/index',
  imChatmessageList: '/im_chatmanage/messageList',
  imMessageBack: '/im_chatmanage/messageBack',
  imMessageForbid: '/im_chatmanage/messageForbid',
  // 百度链接提交
  linksubmit: '/link_submit/index',

  // 画像统计
  // 总览-总览统计
  overviewTotal: '/stat_overview/total',
  // 总览-已完成订单
  overviewOrder: '/stat_overview/order',
  // 总览-注册量趋势
  overviewReg: '/stat_overview/reg',
  // 总览-活跃度分析
  overviewActive: '/stat_overview/active',
  // 简历总览-性别分布
  resumeOverviewSex: '/stat_resume_overview/sex',
  // 简历总览-年龄分布
  resumeOverviewAge: '/stat_resume_overview/age',
  // 简历总览-学历分布
  resumeOverviewEdu: '/stat_resume_overview/edu',
  // 简历总览-经验分布
  resumeOverviewExp: '/stat_resume_overview/exp',
  // 简历总览-意向地区分布
  resumeOverviewIntentionDistrict: '/stat_resume_overview/intentionDistrict',
  // 简历总览-意向职位分布
  resumeOverviewIntentionJobcategory: '/stat_resume_overview/intentionJobcategory',
  // 简历总览-求职者活跃度
  resumeOverviewActive: '/stat_resume_overview/active',
  // 简历总览-简历新增数
  resumeOverviewResumeAdd: '/stat_resume_overview/resumeAdd',
  // 求职者结构-学历分布
  personalEdu: '/stat_personal/edu',
  // 求职者结构-年龄分布
  personalAge: '/stat_personal/age',
  // 求职者结构-经验分布
  personalExp: '/stat_personal/exp',
  // 求职者结构-职类分布
  personalJobcategory: '/stat_personal/jobcategory',
  // 简历流向-求职者申请职位企业性质流向
  intentionComNature: '/stat_intention/comNature',
  // 简历流向-求职者申请职位企业规模流向
  intentionComScale: '/stat_intention/comScale',
  // 简历流向-求职者申请职位企业地区流向
  intentionComDistrict: '/stat_intention/comDistrict',
  // 简历流向-求职者申请职位企业行业流向
  intentionComTrade: '/stat_intention/comTrade',
  // 简历热度-简历刷新量排行榜TOP100
  resumeHotRefresh: '/stat_resume_hot/refresh',
  // 简历热度-简历投递量排行榜TOP100
  resumeHotJobapply: '/stat_resume_hot/jobapply',
  // 简历热度-求职者登录排行榜TOP100
  resumeHotLogin: '/stat_resume_hot/login',
  // 简历热度-求职者被下载排行榜TOP100
  resumeHotDown: '/stat_resume_hot/down',
  // 简历热度-热门简历排行榜TOP100
  resumeHotView: '/stat_resume_hot/view',
  // 企业总览-企业性质分布
  companyOverviewComNature: '/stat_company_overview/nature',
  // 企业总览-企业规模分布
  companyOverviewComScale: '/stat_company_overview/scale',
  // 企业总览-企业认证分布
  companyOverviewComAudit: '/stat_company_overview/audit',
  // 企业总览-企业套餐分布
  companyOverviewComSetmeal: '/stat_company_overview/setmeal',
  // 企业总览-企业地区分布
  companyOverviewComDistrict: '/stat_company_overview/district',
  // 企业总览-企业行业分布
  companyOverviewComTrade: '/stat_company_overview/trade',
  // 企业总览-企业新增趋势
  companyOverviewComAdd: '/stat_company_overview/comAdd',
  // 企业总览-企业活跃度分布
  companyOverviewActive: '/stat_company_overview/active',
  // 职位总览-学历要求分布
  jobOverviewEdu: '/stat_job_overview/edu',
  // 职位总览-经验要求分布
  jobOverviewExp: '/stat_job_overview/exp',
  // 职位总览-工作性质分布
  jobOverviewNature: '/stat_job_overview/nature',
  // 职位总览-薪资分布
  jobOverviewWage: '/stat_job_overview/wage',
  // 职位总览-职位地区分布
  jobOverviewDistrict: '/stat_job_overview/district',
  // 职位总览-职能分类分布TOP10
  jobOverviewJobcategory: '/stat_job_overview/jobcategory',
  // 职位总览-职位趋势
  jobOverviewJobAdd: '/stat_job_overview/jobAdd',
  // 职位总览-职位活跃度分析
  jobOverviewActive: '/stat_job_overview/active',
  // 业务分析-企业套餐会员分析
  businessSetmeal: '/stat_business/setmeal',
  // 业务分析-职位增值服务分析
  businessService: '/stat_business/service',
  // 业务分析-下载简历趋势
  businessDown: '/stat_business/down',
  // 企业热度-职位刷新量排行榜TOP100
  jobHotRefresh: '/stat_job_hot/refresh',
  // 企业热度-企业下载量排行榜TOP100
  comHotDown: '/stat_job_hot/down',
  // 企业热度-企业登录排行榜TOP100
  comHotLogin: '/stat_job_hot/login',
  // 企业热度-职位被投递排行榜TOP100
  comHotJobapply: '/stat_job_hot/jobapply',
  // 企业热度-热门职位排行榜TOP100
  jobHotView: '/stat_job_hot/view',
  // 企业热度-热门企业排行榜TOP100
  comHotView: '/stat_job_hot/viewCom',
  // 业务成交统计-总览统计
  orderTotal: '/stat_order/total',
  // 业务成交统计-个人订单成交额
  orderPersonal: '/stat_order/personal',
  // 业务成交统计-企业订单成交额
  orderCompany: '/stat_order/company',
  // 业务成交统计-各订单支付方式结构
  orderPayType: '/stat_order/payType',
  // 业务成交统计-新增业务成交趋势
  orderPayTotal: '/stat_order/payTotal',
  // 业务成交统计-企业新开通套餐趋势分布
  orderPaySetmeal: '/stat_order/paySetmeal',

  // 企业推广
  jobPromotionList: '/promotion_job/index',
  // 搜索企业
  jobPromotionSearchCompany: '/promotion_job/searchCompany',
  // 搜索职位
  jobPromotionSearchJob: '/promotion_job/searchJob',
  // 添加企业推广
  jobPromotionAdd: '/promotion_job/add',
  // 编辑企业推广
  jobPromotionEdit: '/promotion_job/edit',
  // 取消企业推广
  jobPromotionCancel: '/promotion_job/cancel',

  // 简历推广
  resumePromotionList: '/promotion_resume/index',
  // 搜索简历
  resumePromotionSearch: '/promotion_resume/search',
  // 添加简历推广
  resumePromotionAdd: '/promotion_resume/add',
  // 编辑简历推广
  resumePromotionEdit: '/promotion_resume/edit',
  // 取消简历推广
  resumePromotionCancel: '/promotion_resume/cancel',

  // 增值服务-职位置顶列表
  serviceJobStickList: '/company_service_stick/index',
  // 增值服务-职位置顶添加
  serviceJobStickAdd: '/company_service_stick/add',
  // 增值服务-职位置顶编辑
  serviceJobStickEdit: '/company_service_stick/edit',
  // 增值服务-职位置顶删除
  serviceJobStickDelete: '/company_service_stick/delete',
  // 增值服务-职位紧急列表
  serviceJobEmergencyList: '/company_service_emergency/index',
  // 增值服务-职位紧急添加
  serviceJobEmergencyAdd: '/company_service_emergency/add',
  // 增值服务-职位紧急编辑
  serviceJobEmergencyEdit: '/company_service_emergency/edit',
  // 增值服务-职位紧急删除
  serviceJobEmergencyDelete: '/company_service_emergency/delete',
  // 增值服务-职位智能刷新列表
  serviceJobRefreshPackageList: '/company_service_refresh_job_package/index',
  // 增值服务-职位智能刷新添加
  serviceJobRefreshPackageAdd: '/company_service_refresh_job_package/add',
  // 增值服务-职位智能刷新编辑
  serviceJobRefreshPackageEdit: '/company_service_refresh_job_package/edit',
  // 增值服务-职位智能刷新删除
  serviceJobRefreshPackageDelete: '/company_service_refresh_job_package/delete',
  // 增值服务-简历增值包列表
  serviceResumePackageList: '/company_service_resume_package/index',
  // 增值服务-简历增值包添加
  serviceResumePackageAdd: '/company_service_resume_package/add',
  // 增值服务-简历增值包编辑
  serviceResumePackageEdit: '/company_service_resume_package/edit',
  // 增值服务-简历增值包删除
  serviceResumePackageDelete: '/company_service_resume_package/delete',
  // 增值服务-职聊增值包列表
  serviceImPackageList: '/company_service_im_package/index',
  // 增值服务-职聊增值包添加
  serviceImPackageAdd: '/company_service_im_package/add',
  // 增值服务-职聊增值包编辑
  serviceImPackageEdit: '/company_service_im_package/edit',
  // 增值服务-职聊增值包删除
  serviceImPackageDelete: '/company_service_im_package/delete',
  // 增值服务-简历置顶列表
  serviceResumeStickList: '/personal_service_stick/index',
  // 增值服务-简历置顶添加
  serviceResumeStickAdd: '/personal_service_stick/add',
  // 增值服务-简历置顶编辑
  serviceResumeStickEdit: '/personal_service_stick/edit',
  // 增值服务-简历置顶删除
  serviceResumeStickDelete: '/personal_service_stick/delete',
  // 增值服务-简历标签列表
  serviceResumeTagList: '/personal_service_tag/index',
  // 增值服务-简历标签添加
  serviceResumeTagAdd: '/personal_service_tag/add',
  // 增值服务-简历标签编辑
  serviceResumeTagEdit: '/personal_service_tag/edit',
  // 增值服务-简历标签删除
  serviceResumeTagDelete: '/personal_service_tag/delete',
  // 增值服务-积分套餐列表
  servicePointsList: '/company_service_points/index',
  // 增值服务-积分套餐添加
  servicePointsAdd: '/company_service_points/add',
  // 增值服务-积分套餐编辑
  servicePointsEdit: '/company_service_points/edit',
  // 增值服务-积分套餐删除
  servicePointsDelete: '/company_service_points/delete',

  // 实地认证列表
  companyReportList: '/company_report/index',
  // 实地认证添加
  companyReportAdd: '/company_report/add',
  // 实地认证编辑
  companyReportEdit: '/company_report/edit',
  // 实地认证删除
  companyReportDelete: '/company_report/delete',
  // 搜索企业
  companyReportSearchCompany: '/company_report/searchCompany',

  // 营销模板列表
  marketTplList: '/market/tplList',
  // 营销模板添加
  marketTplAdd: '/market/tplAdd',
  // 营销模板修改
  marketTplEdit: '/market/tplEdit',
  // 营销模板删除
  marketTplDelete: '/market/tplDelete',
  // 营销模板列表
  marketTaskList: '/market/taskList',
  // 营销任务添加
  marketTaskAdd: '/market/taskAdd',
  // 营销模板删除
  marketTaskDelete: '/market/taskDelete',
  // 发送营销
  marketTaskRun: '/market/taskRun',
  // 营销模板列表
  marketSearchMember: '/market/searchMember',
  // 委托投递
  entrustList: '/entrust/index',
  entrustIntentionList: '/entrust/getIntentions',
  entrustMatchList: '/entrust/matchList',
  entrustMatchApply: '/entrust/apply',
  entrustDelete: '/entrust/delete',

  // 页面配置
  pageList: '/page/index',
  pageEdit: '/page/edit',

  // 导航
  navList: '/nav/index',
  navAdd: '/nav/add',
  navEdit: '/nav/edit',
  navDelete: '/nav/delete',

  // 简历投递记录
  jobApplyList: '/job_apply/index',

  // 网络招聘会
  jobFairListOl: '/jobfairol/index',
  jobFairListOlAdd: '/jobfairol/add',
  jobFairListOlEdit: '/jobfairol/edit',
  jobFairListOlDelete: '/jobfairol/delete',
  jobFairListOlCompanyList: '/jobfairol/companyList',
  jobFairListOlPersonalList: '/jobfairol/personalList',
  jobFairListOlSticky: '/jobfairol/setSticky',
  jobFairListOlQrcode: '/jobfairol/setQrcode',
  jobFairListOlCompanySearch: '/jobfairol/getCompany',
  jobFairListOlParAdd: '/jobfairol/participateAdd',
  jobFairListOlPersonalSearch: '/jobfairol/getPersonal',
  jobFairListOlStatus: '/jobfairol/setStatus',
  jobFairListOlParticipateDelete: '/jobfairol/participateDelete',
  jobFairListOlQrService: '/jobfairol/qrService',
  jobFairListOlComBatchAdd: '/jobfairol/companyBatchAdd',
  jobFairListOlPerBatchAdd: '/jobfairol/personalBatchAdd',
  jobfairOlModifyState: '/jobfairol/modifyState',

  // 微信公众号营销
  marketingWxoffiaccount: '/marketing/index',
  marketingWxoffiaccountSearchCompany: '/marketing/companySearch',

  // 公众号营销（new）
  marketingSearch: '/marketing/search',
  companySearchByKeyword: '/marketing/companySearchByKeyword',
  wxTemplateList: '/marketing/templateList',
  templateOption: '/marketing/templateOption',
  templateDelete: '/marketing/templateDelete',
  templateEdit: '/marketing/templateEdit',
  templateDetails: '/marketing/templateDetails',

  // 二维码
  showQrcode: '/qrcode/normal',
  // 登录二维码
  showLoginQrcode: '/qrcode/login',
  // 登录二维码
  loginScan: '/login/scan',
  // 场景码
  sceneQrcodeList: '/scene_qrcode/index',
  sceneQrcodeAdd: '/scene_qrcode/add',
  sceneQrcodeDelete: '/scene_qrcode/delete',
  sceneQrcodePlatformList: '/scene_qrcode/platformList',
  sceneQrcodeTypeList: '/scene_qrcode/typeList',
  sceneQrcodeDownload: '/scene_qrcode/download',
  sceneSearchList: '/scene_qrcode/searchList',

  // 敏感词
  badwordList: '/badword/index',
  badwordAdd: '/badword/add',
  badwordEdit: '/badword/edit',
  badwordDelete: '/badword/delete',
  badwordImport: '/badword/import',

  // 社群推文营销
  tweetslabelList: '/tweets_label/index',
  tweetsTemplateList: '/tweets_template/index',
  tweetsTemplateEdit: '/tweets_template/edit',
  tweetsTemplateAdd: '/tweets_template/add',
  tweetsTemplateDel: '/tweets_template/del',
  tweetsJoblist: '/tweets_template/joblist',
  tweetsSearchJob: '/tweets_template/jobSearch',
  tweetsSearchCompany: '/tweets_template/companySearch',
  tweetsTitleFooter: '/tweets_template/title_footer',

  // 生成海报
  makePoster: '/poster/index',
  downloadPoster: '/poster/download',

  // 触屏页面配置
  pageMobileList: '/page_mobile/index',
  pageMobileEdit: '/page_mobile/edit',

  // 简历导入
  resumeImport: '/resume/import',
  // 热门关键词
  hotwordList: '/hotword/index',
  hotwordAdd: '/hotword/add',
  hotwordEdit: '/hotword/edit',
  hotwordDelete: '/hotword/delete',
  hotwordSaveAll: '/hotword/saveAll',
  // 在线客服
  serviceOl: '/service_ol/index',
  serviceOlAdd: '/service_ol/add',
  serviceOlEdit: '/service_ol/edit',
  serviceOlDelete: '/service_ol/delete',

  // 简历导入模板下载
  downloadImportResumeTpl: '/resume/downloadImportResumeTpl',

  // 职位分类模板
  categoryJobTemplateList: '/category_job_template/index',
  categoryJobTemplateAdd: '/category_job_template/add',
  categoryJobTemplateEdit: '/category_job_template/edit',
  categoryJobTemplateDelete: '/category_job_template/delete',
  // 手机号黑名单
  smsBlacklist: '/sms_blacklist/index',
  smsBlacklistAdd: '/sms_blacklist/add',
  smsBlacklistEdit: '/sms_blacklist/edit',
  smsBlacklistDelete: '/sms_blacklist/delete',
  // pc首页模板
  tplIndex: '/tpl/index',

  // 短链接
  shorturlList: '/short_url/lists',
  shorturlSave: '/short_url/save',
  shorturlDelete: '/short_url/del',

  // 分站
  subsiteList: '/subsite/index',
  subsiteAdd: '/subsite/add',
  subsiteEdit: '/subsite/edit',
  subsiteDelete: '/subsite/delete',
  subsiteIsDisplay: '/subsite/modifyIsDisplay',

  // 系统工具箱
  sysToolSyncSetmeal: '/sys_tool/syncSetmeal',
  sysToolSyncCompanyDisplay: '/sys_tool/syncCompanyDisplay',

  // 海报
  posterList: '/poster/lists',
  posterAdd: '/poster/add',
  posterEdit: '/poster/edit',
  posterDelete: '/poster/delete',
  posterDisplay: '/poster/setDisplay',
  posterTplindexList: '/poster/getTplindexList',

  // 刷新职位
  refresh_job: '/company/refreshJob',
  // 在线升级
  newVersionList: '/upgrade/newVersionList',
  updateStart: '/upgrade/startup',
  updateDownload: '/upgrade/download',
  speedProgress: '/upgrade/speedProgress',
  upgradeUnzip: '/upgrade/unzip',
  upgradeUpdate: '/upgrade/update',

  // 数据采集
  getSeting: '/collection_seting/getSetingInfo',
  saveSeting: '/collection_seting/saveSeting',
  saveJobSeting: '/collection_seting/saveJobSeting',
  saveCompanySeting: '/collection_seting/saveCompanySeting',
  saveAccountSeting: '/collection_seting/saveAccountSeting',
  saveArticleSeting: '/collection_seting/saveArticleSeting', // 资讯采集

  // 个人中心
  personalIndex: '/personal/index',
  personalAvatar: '/personal/avatar',
  personalUsername: '/personal/username',
  personalPassword: '/personal/password',
  personalBindingCode: '/personal/bindingCode',
  personalBindingMobile: '/personal/bindingMobile',

  // 网络招聘会信息导出
  jobfairOlListAll: '/jobfairOl/getJobfairOnAll',
  isExhibitors: '/jobfair_ol_export/isExhibitors',
  jobfairOlCompanyJobsList: '/jobfair_ol_export/exhibitors',

  // 场景码批量删除 zch 2022/8/1
  sceneQrcodeDeleteAll: '/scene_qrcode/deleteAll',

  // 详情模板
  showTpl: '/tpl/showTpl',
  // 企业crm
  crmCustomList: 'b2bcrm/crm_custom_list/index',
  clueList: 'b2bcrm/Clue/index',
  classify: 'classify/index',
  clueAdminLists: 'b2bcrm/Clue/adminlists',
  clueDetails: 'b2bcrm/Clue/details',
  followUpList: 'b2bcrm/follow_up/index',
  addVisit: 'b2bcrm/follow_up/addVisit',
  exportList: 'b2bcrm/Clue/export',
  clueEdit: 'b2bcrm/Clue/clueEdit',
  clueExport: 'b2bcrm/Clue/export',
  clueDelete: 'b2bcrm/Clue/delete',
  clueReceive: 'b2bcrm/Clue/receive',
  clueRelease: 'b2bcrm/Clue/release',
  clueAdd: 'b2bcrm/Clue/clueAdd',
  crmCompanyList: 'b2bcrm/Company/index',
  lifeCycle: 'b2bcrm/Company/lifeCycle',
  companyCrmAudit: '/company/setAudit',
  refreshCrmJob: '/company/refreshJob',
  exportCrmCompanyById: '/export/companyById',
  companyDetails: 'b2bcrm/Company/companyDetail',
  contactList: 'b2bcrm/Company_Contact/index',
  clueContactList: 'b2bcrm/Clue/getContactList',
  updateCompany: 'b2bcrm/Company/updateCompany',
  getAllCrmTags: 'b2bcrm/Setting/getAllCrmTags',
  deleteImg: 'b2bcrm/Profile/deleteImg',
  delContact: 'b2bcrm/Company_Contact/delContact',
  addContact: 'b2bcrm/Company_Contact/addContact',
  editContact: 'b2bcrm/Company_Contact/editContact',
  companyJob: 'b2bcrm/company_job/index',
  setDisplay: 'b2bcrm/company_job/setDisplay',
  jobApply: 'b2bcrm/Recruitment/jobApply',
  downResume: '/b2bcrm/Recruitment/downResume',
  viewResume: 'b2bcrm/Recruitment/viewResume',
  viewed: 'b2bcrm/Recruitment/viewBeBrowsed',
  pointsLog: 'b2bcrm/Points/pointsLog',
  newConsumeLog: 'b2bcrm/Points/consumeLog',
  crmOrderList: 'b2bcrm/company_order/orderList',
  lifeCycleAll: 'b2bcrm/Setting/lifeCycleAll',
  lifeCycleEdit: 'b2bcrm/Setting/lifeCycleEdit',
  lifeCycleSwitch: 'b2bcrm/Setting/lifeCycleSwitch',
  getSysConfigByCategory: 'b2bcrm/Setting/getSysConfigByCategory',
  editCrmTag: 'b2bcrm/Setting/editCrmTag',
  addCrmTag: 'b2bcrm/Setting/addCrmTag',
  delCrmTag: 'b2bcrm/Setting/delCrmTag',
  editConfig: 'b2bcrm/Setting/editConfig',
  getisWeixinBind: 'b2bcrm/Company/getisWeixinBind',
  qrcodeCrm: 'b2bcrm/Company/qrcode',
  companyReceive: 'b2bcrm/Company/receive',
  companyRelease: 'b2bcrm/Company/release',
  companyCrmSetDisplay: 'b2bcrm/Company/setDisplay',
  customerCrmServiceEdit: 'b2bcrm/customer_service/edit',
  putRecycleBin: 'b2bcrm/recycle_bin/put',
  recycleBinList: 'b2bcrm/recycle_bin/index',
  recycleBinReturn: 'b2bcrm/recycle_bin/recover',
  recycleBinDel: 'b2bcrm/recycle_bin/del',
  toBeFollowedup: 'b2bcrm/follow_up/toBeFollowedup',
  companyCrmAdd: 'b2bcrm/Company/add',
  crmClueRelease: 'b2bcrm/Crm_Clue_Release/index',
  isNameRepeat: 'b2bcrm/Clue/isNameRepeat',
  // 外呼系统
  outboundRecordList: 'outbound/record/index',
  outboundAccount: 'outbound/Account/index',
  outboundSeat: 'outbound/seat/index',
  outboundStaffList: 'outbound/seat/staffList',
  recordDetails: 'outbound/record/details',
  customLndex: 'Custom/index',
  dataBoardCallRecord: 'outbound/data_board/callRecord',
  dataBoardcallRecordList: 'outbound/data_board/callRecordList',
  seatCallRecord: 'outbound/data_board/seatCallRecord',
  bindSeat: 'outbound/Seat/bindSeat',
  unbindSeat: 'outbound/Seat/unbindSeat',
  outboundCall: 'outbound/outbound_call/call',
  UnbindList: 'outbound/seat/unbindList',
  // urm
  urmList: 'b2burm/Resume/index',
  resumeDetails: 'b2burm/Resume/resumeDetail',
  resumeUrmJobApply: 'b2burm/Resume/jobApply',
  resumeUrmInterview: 'b2burm/Resume/interview',
  resumeUrmAttentionMe: 'b2burm/Resume/attentionMe',
  resumeUrmViewJob: 'b2burm/Resume/viewjob',
  urmAddVisit: 'b2burm/follow_up/addVisit',
  urmFollowUpList: 'b2burm/follow_up/index',
  updateResume: 'b2burm/Resume/updateResume',
  urmRecycleBinList: 'b2burm/recycle_bin/index',
  urmRecycleBinDel: 'b2burm/recycle_bin/del',
  urmRecycleBinReturn: 'b2burm/recycle_bin/recover',
  resumeDel: 'b2burm/recycle_bin/put',
  setContactStatus: 'b2burm/resume/setContactStatus',
  // 企业名录
  baseConfig: 'b2bcrm/company_directory/baseConfig',
  getCompanyClueList: 'b2bcrm/company_directory/getCompanyClueList',
  getConsumeRecordList: 'b2bcrm/company_directory/getConsumeRecordList',
  importClue: 'b2bcrm/company_directory/importClue',
  getAccountBalance: 'b2bcrm/company_directory/getAccountBalance',
  getContactList: 'b2bcrm/Clue/getContactList',
  setAsMainContact: 'b2bcrm/Clue/setAsMainContact',
  clueAddContact: 'b2bcrm/Clue/addContact',
  clueEditContact: 'b2bcrm/Clue/editContact',
  deleteContact: 'b2bcrm/Clue/deleteContact',
  verifyRepeatClueName: 'b2bcrm/Clue/verifyRepeatClueName',
  verifyRepeatMobile: 'b2bcrm/Clue/verifyRepeatMobile',
  verifyRepeatCompany: 'b2bcrm/Clue/verifyRepeatCompany',
  mergeClueToCompany: 'b2bcrm/Clue/mergeClueToCompany',
  queryCacheIsExists: 'b2bcrm/company_directory/queryCacheIsExists',
  // 简历解析
  resumeConfig: 'zhitoo/resume/config',
  resumeCheck: 'zhitoo/resume/check',
  documentConfig: 'zhitoo/document/config',
  documentCheck: 'zhitoo/document/check',
  // 工作看板
  workData: 'b2bcrm/work_statistics/work',
  salesStatistics: 'b2bcrm/work_statistics/salesStatistics',
  newCompanyStatistics: 'b2bcrm/work_statistics/newCompanyStatistics',
  newClueStatistics: 'b2bcrm/work_statistics/newClueStatistics',
  newCompanyFollowStatistics: 'b2bcrm/work_statistics/newCompanyFollowStatistics',
  newClueFollowStatistics: 'b2bcrm/work_statistics/newClueFollowStatistics',
  workLifeCycle: 'b2bcrm/work_statistics/lifeCycle',
  // 销售看板
  salesKanban: '/b2bcrm/sale_statistics/salesKanban',
  companyCrmClueStatistics: '/b2bcrm/sale_statistics/companyCrmClueStatistics',
  // 首页新增接口
  newDataStatisticsIndex: 'new_data_statistics/index',
  newDataStatisticsChart: 'new_data_statistics/chart',
  newRealTimeData: 'new_data_statistics/realTimeData',
  newGeneralstatistics: 'new_data_statistics/generalstatistics',
  newOfficialData: 'new_data_statistics/officialData',

  // 生成企业logo
  sendCompanyLogo: '/company/sendCompanyLogo',

  // 获取审核模板
  getAuditTemplateList: '/audit_template/getList',
  // 删除审核模板
  deleteAuditTemplate: '/audit_template/delete'
}
