(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-6641a78c"],{"11fb":function(t,e,s){},2909:function(t,e,s){"use strict";s.d(e,"a",(function(){return o}));var a=s("6b75");function i(t){if(Array.isArray(t))return Object(a["a"])(t)}s("a4d3"),s("e01a"),s("d3b7"),s("d28b"),s("3ca3"),s("ddb0"),s("a630");function n(t){if("undefined"!==typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}var c=s("06c5");s("d9e2");function r(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function o(t){return i(t)||n(t)||Object(c["a"])(t)||r()}},"56bc":function(t,e,s){"use strict";s("11fb")},"8bbd":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{staticClass:"personal_top"},[s("div",{staticClass:"top_content"},[s("div",{staticClass:"avatar_box"},[s("img",{attrs:{src:t.basic.photo_img_src}})]),s("div",{staticClass:"info"},[s("div",{staticClass:"name"},[t._v(t._s(t.basic.fullname))]),s("div",{staticClass:"some"},[s("div",{staticClass:"some_item"},[t._v(t._s(t.basic.sex_text))]),s("div",{staticClass:"some_item"},[t._v(t._s(t.basic.age)+"岁")]),s("div",{staticClass:"some_item"},[t._v(t._s(t.basic.education_text))]),s("div",{staticClass:"some_item"},[t._v(t._s(t.basic.experience_text))]),s("div",{staticClass:"clear"})]),s("div",{staticClass:"contact"},[""!==t.contact.mobile?s("div",{staticClass:"contact_item phone"},[t._v(t._s(t.contact.mobile))]):t._e(),""!==t.contact.weixin?s("div",{staticClass:"contact_item wx"},[t._v(t._s(t.contact.weixin))]):t._e(),s("div",{staticClass:"clear"})])]),s("div",{staticClass:"top_block",on:{click:function(e){return t.$router.push("/personal/job/jobapply")}}},[s("div",{staticClass:"tx1"},[t._v(t._s(t.resumeTotal.job_apply.number))]),s("div",{staticClass:"tx2"},[t._v("我的投递")])]),s("div",{staticClass:"top_block",on:{click:function(e){return t.$router.push("/personal/job/interview")}}},[s("div",{staticClass:"tx1"},[t._v(t._s(t.resumeTotal.interview.number))]),s("div",{staticClass:"tx2"},[t._v("面试邀请")])]),s("div",{staticClass:"top_block",on:{click:function(e){return t.$router.push("/personal/job/attention_me")}}},[s("div",{staticClass:"tx1"},[t._v(t._s(t.resumeTotal.attention_me.number))]),s("div",{staticClass:"tx2"},[t._v("对我感兴趣")])]),s("div",{staticClass:"top_block",on:{click:function(e){return t.$router.push("/personal/job/viewjob")}}},[s("div",{staticClass:"tx1"},[t._v(t._s(t.resumeTotal.view_job.number))]),s("div",{staticClass:"tx2"},[t._v("我的足迹")])]),s("div",{staticClass:"clear"})])]),t.basic.complete_percent<t.$store.state.config.apply_job_min_percent?s("div",{staticClass:"integrity_tip"},[t._v("您的简历完整度较低，严重影响求职质量，建议立即完善简历信息 "),s("el-button",{staticClass:"r_btn",attrs:{type:"warning",size:"small"},on:{click:function(e){return t.$router.push("/personal/resume")}}},[t._v("去完善")])],1):t._e(),s("div",{staticClass:"box_1"},[s("div",{staticClass:"b_left"},[s("div",{staticClass:"tx1"},[t._v("我的简历")]),s("el-switch",{staticClass:"tx_switch",attrs:{"active-color":"#409eff","inactive-color":"#bec3d1","inactive-text":"公开状态"},on:{change:t.handleStatus},model:{value:t.resumePublic,callback:function(e){t.resumePublic=e},expression:"resumePublic"}}),0==t.basic.audit?s("div",{staticClass:"examine wait"}):t._e(),1==t.basic.audit?s("div",{staticClass:"examine pass"}):t._e(),2==t.basic.audit?s("div",{staticClass:"examine fail"}):t._e(),s("div",{staticClass:"speed"},[s("el-progress",{attrs:{type:"circle","show-text":!1,width:112,percentage:t.basic.complete_percent}}),s("div",{staticClass:"p_txt"},[t._v(t._s(t.basic.complete_percent)),s("span",[t._v("%")])]),s("div",{staticClass:"txt"},[t._v("简历完整度")])],1),t.basic.complete_percent<t.$store.state.config.apply_job_min_percent?s("div",{staticClass:"tx2"},[t._v("完整度太低严重影响求职质量，"),s("router-link",{attrs:{to:"/personal/resume"}},[t._v("去完善！")])],1):s("div",{staticClass:"tx2"},[t._v("使用简历置顶提高简历曝光率，"),s("a",{attrs:{href:"javascript:;"},on:{click:function(e){return t.$router.push("/personal/service/stick")}}},[t._v("去置顶！")])])],1),s("div",{staticClass:"b_right"},[s("el-dropdown",{staticClass:"intention-top",on:{command:t.changeIntention}},[s("div",{staticClass:"drop_bar substring"},[t._v(t._s(t.currentIntention.category_text))]),s("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},t._l(t.intentionList,(function(e,a){return s("el-dropdown-item",{key:a,attrs:{command:e}},[t._v(t._s(e.category_text))])})),1)],1),s("div",{staticClass:"tx1 substring"},[t._v("工作地区:  "+t._s(t.currentIntention.district_text))]),s("div",{staticClass:"tx1 substring"},[t._v("期望薪资:  "+t._s(t.currentIntention.wage_text)+"/月")]),s("div",{staticClass:"tx1 substring"},[t._v("期望行业:  "+t._s(t.currentIntention.trade_text))]),s("div",{staticClass:"tx1 substring"},[t._v("求职状态:  "+t._s(t.basic.current_text))])],1),s("div",{staticClass:"b_btn"},[s("div",{staticClass:"tx1"},[t._v("最近更新："+t._s(t._f("timeFilter")(t.basic.refreshtime)))]),s("div",{staticClass:"b_group"},[s("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.$router.push("/personal/resume")}}},[t._v("修改简历")]),s("el-button",{attrs:{type:"primary",plain:""},on:{click:t.handlerPreview}},[t._v("预览简历")]),s("el-button",{attrs:{type:"primary"},on:{click:t.refreshResume}},[t._v("刷新简历")]),s("el-button",{attrs:{type:"primary",plain:""},on:{click:function(e){return t.handlerJumpService("/personal/service/stick","stick")}}},[t._v("置顶简历")])],1)]),s("div",{staticClass:"clear"}),1==t.resumeisDisplay&&0!=t.defectResumeAry.length?s("div",{staticClass:"resume_tips"},[s("div",{staticClass:"tips_text"},[t._v("您的简历缺少"+t._s(t.defectResumeAry[0].remarks)+" ，可能错过面试机会哦！ 赶快去补充，让HR看到你过往的工作能力")]),s("div",{staticClass:"tips_icon",on:{click:function(e){return t.handlerJumpEditResume(t.defectResumeAry[0])}}},[t._v(" 去完善 "),s("i",{staticClass:"el-icon-arrow-right"})]),s("div",{staticClass:"clear"})]):t._e(),0==t.resumeisDisplay?s("div",{staticClass:"resume_tips bg2"},[s("div",{staticClass:"tips_text"},[t._v("您的简历已隐藏，企业无法搜到到您的简历哦！")]),s("div",{staticClass:"tips_icon",on:{click:function(e){return t.handleResumeStatus(!0)}}},[t._v(" 立即开启 ")]),s("div",{staticClass:"clear"})]):t._e()]),t.ad_dataset_banner.items.length>0?s("div",{staticClass:"box_2"},[s("el-carousel",{attrs:{height:"70px"}},t._l(t.ad_dataset_banner.items,(function(e,a){return s("el-carousel-item",{key:a},[s("img",{staticClass:"img",attrs:{src:e.image_src},on:{click:function(s){return t.handleClickBanner(e)}}})])})),1)],1):t._e(),s("div",{staticClass:"box_3"},[s("div",{staticClass:"b_title"},[t._v("优选服务")]),s("div",{staticClass:"b_group"},[s("a",{staticClass:"b_item",attrs:{href:"javascript:;"},on:{click:function(e){return t.handlerJumpService("/personal/service/stick","stick")}}},[s("p",{staticClass:"b_ico c1"}),s("p",{staticClass:"tx1"},[t._v("简历置顶")]),s("p",{staticClass:"tx2"},[t._v("曝光量提升18倍，增大求职概率")])]),s("a",{staticClass:"b_item",attrs:{href:"javascript:;"},on:{click:function(e){return t.handlerJumpService("/personal/service/tag","tag")}}},[s("p",{staticClass:"b_ico c2"}),s("p",{staticClass:"tx1"},[t._v("醒目标签")]),s("p",{staticClass:"tx2"},[t._v("众人中间脱颖而出，是金子就要发光")])]),s("a",{staticClass:"b_item",attrs:{href:"javascript:;"},on:{click:function(e){return t.$router.push("/personal/fav/subscribe")}}},[s("p",{staticClass:"b_ico c3"}),s("p",{staticClass:"tx1"},[t._v("职位订阅")]),s("p",{staticClass:"tx2"},[t._v("好职位不能错过，精准智能匹配")])]),s("a",{staticClass:"b_item",attrs:{href:"javascript:;"},on:{click:t.handlerEntrust}},[s("p",{staticClass:"b_ico c4"}),s("p",{staticClass:"tx1"},[t._v("委托投递")]),s("p",{staticClass:"tx2"},[t._v("无需登录，系统精准投递")])]),s("a",{staticClass:"b_item",attrs:{href:"javascript:;"},on:{click:function(e){return t.$router.push("/personal/privacy/setPrivacy")}}},[s("p",{staticClass:"b_ico c5"}),s("p",{staticClass:"tx1"},[t._v("隐私设置")]),s("p",{staticClass:"tx2"},[t._v("贴心隐私保护，拒绝被打扰")])]),s("a",{staticClass:"b_item",attrs:{href:"javascript:;"},on:{click:function(e){return t.$router.push("/personal/points")}}},[s("p",{staticClass:"b_ico c6"}),s("p",{staticClass:"tx1"},[t._v("我的"+t._s(t.$store.state.config.points_byname))]),s("p",{staticClass:"tx2"},[t._v("做任务赢"+t._s(t.$store.state.config.points_byname)+"，每天进步一点点")])]),s("div",{staticClass:"clear"})])]),s("div",{staticClass:"box_4"},[s("div",{staticClass:"b_title"},[t._v("职位推荐")]),s("div",{staticClass:"b_sw"},[t._l(t.intentionList,(function(e,a){return s("div",{key:a,staticClass:"sw_item",class:e.id==t.recommendIntentionId?"active":"",on:{click:function(s){return t.changeRecommendIntention(e)}}},[t._v(t._s(e.category_text))])})),s("div",{staticClass:"clear"})],2),t.recommendList.length>0?s("div",{staticClass:"b_group"},[t._l(t.recommendList,(function(e,a){return s("div",{key:a,staticClass:"b_item",on:{click:function(s){return t.handlerRecommendLink(e)}}},[s("div",{staticClass:"b_name substring"},[t._v(t._s(e.jobname))]),s("div",{staticClass:"b_city substring"},[t._v(t._s(e.district_text))]),s("div",{staticClass:"b_wage substring"},[t._v(t._s(e.wage_text)+"/月")])])})),s("div",{staticClass:"clear"}),s("div",{staticClass:"b_more"},[t._v("想看更多推荐职位？"),s("a",{attrs:{target:"_blank",href:t.joblistUrl}},[t._v("去查看>>")])])],2):t._e(),0==t.recommendList.length&&!0===t.showRecommendEmpty?s("div",{staticClass:"b_empty"},[t._v("没有数据了")]):t._e()]),s("el-dialog",{attrs:{title:"委托投递",visible:t.showEntrust,width:"400px","close-on-press-escape":!1,"close-on-click-modal":!1},on:{"update:visible":function(e){t.showEntrust=e},opened:t.handlerEntrustInit}},[s("Entrust",{ref:"entrust",on:{closeEntrust:function(e){t.showEntrust=!1}}})],1)],1)},i=[],n=s("2909"),c=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("el-form",{ref:"form",attrs:{"label-width":"100px"}},[!1===t.hasEntrust?s("el-form-item",{attrs:{label:"委托天数"}},[s("el-select",{attrs:{placeholder:"请选择委托天数"},model:{value:t.days,callback:function(e){t.days=e},expression:"days"}},[s("el-option",{attrs:{label:"3天",value:3}}),s("el-option",{attrs:{label:"7天",value:7}}),s("el-option",{attrs:{label:"15天",value:15}})],1)],1):t._e(),!0===t.hasEntrust?s("el-form-item",{attrs:{label:"剩余委托天数"}},[s("span",{staticStyle:{color:"#e6a23c"}},[t._v(t._s(t.showDays)+"天")])]):t._e(),s("el-form-item",[!1===t.hasEntrust?s("el-button",{attrs:{type:"primary"},on:{click:t.onSubmit}},[t._v("确定委托")]):t._e(),!0===t.hasEntrust?s("el-button",{attrs:{type:"primary"},on:{click:t.onCancel}},[t._v("取消委托")]):t._e()],1)],1)},r=[],o=s("751a"),l=s("d722"),u={props:[],data:function(){return{hasEntrust:!1,days:3,showDays:0}},mounted:function(){},methods:{initCB:function(){this.fetchData()},fetchData:function(){var t=this;o["a"].get(l["a"].personal_entrust).then((function(e){null===e.data?t.hasEntrust=!1:(t.hasEntrust=!0,t.showDays=e.data.days)}))},onSubmit:function(){var t=this;o["a"].post(l["a"].personal_entrust_save,{days:this.days}).then((function(e){t.$message({type:"success",message:e.message}),t.$emit("closeEntrust")}))},onCancel:function(){var t=this;o["a"].post(l["a"].personal_entrust_cancel).then((function(e){t.$message({type:"success",message:e.message}),t.hasEntrust=!1}))}}},_=u,d=s("2877"),m=Object(d["a"])(_,c,r,!1,null,"dc283f02",null),p=m.exports,v=s("ed08"),b=s("e16f"),h={name:"Index",components:{Entrust:p},filters:{timeFilter:function(t){return Object(v["c"])(t,"{y}-{m}-{d} {h}:{i}",!0)}},data:function(){return{resumeTotal:{job_apply:0,interview:0,attention_me:0,view_job:0},showEntrust:!1,showRecommendEmpty:!1,showLowPop:!1,showRefreshPop:!1,resumePublic:!0,currentIntention:{},recommendIntentionId:0,recommendList:[],previewUrl:"",joblistUrl:"",ad_dataset_banner:{alias:"QS_member_personal_banner",items:[]},contact:{},intentionList:[],defectResumeAry:[],resumeisDisplay:""}},computed:{basic:function(){return this.$store.state.resume.basic}},watch:{basic:{deep:!0,handler:function(){var t=this.$store.state.resume.basic,e=new Date,s=e.getFullYear()+"-"+(e.getMonth()+1)+"-"+e.getDate(),a=localStorage.getItem("web_resume_complete_notice_date");if(t.complete_percent<this.$store.state.config.apply_job_min_percent&&"eq"!=Object(v["b"])(a,s)&&(this.showLowPop=!0,localStorage.setItem("web_resume_complete_notice_date",s)),!1===this.showLowPop){var i=localStorage.getItem("web_resume_refresh_notice_date");"eq"!=Object(v["b"])(i,s)&&(this.showRefreshPop=!0,localStorage.setItem("web_resume_refresh_notice_date",s))}}}},created:function(){this.initInfo(),this.getDefectResumeInfo()},methods:{getDefectResumeInfo:function(){var t=this;o["a"].get(l["a"].defectResumeInfo).then((function(e){200==e.code&&(t.defectResumeAry=e.data.no_complete_array,t.resumeisDisplay=e.data.is_display,t.resumePublic=1==e.data.is_display)}))},handlerJumpEditResume:function(t){"training"==t.field&&(t.field="train"),this.$router.push("/personal/resume"+"#".concat(t.field,"Group"))},handleClickBanner:function(t){""!==t.link_url&&window.open(t.link_url)},handlerJumpService:function(t,e){return"stick"==e&&1===this.basic.stick||"tag"==e&&""!==this.basic.service_tag?(this.$message.error("该简历已经在推广状态，不能重复推广"),!1):void this.$router.push(t)},fetchAd:function(){var t=this;o["a"].post(l["a"].ad_list,{alias:["QS_member_personal_banner@web"]}).then((function(e){t.ad_dataset_banner.items=e.data.items["QS_member_personal_banner@web"]})).catch((function(){}))},handleStatus:function(t){var e=this;t?this.handleResumeStatus(t):this.$confirm("简历关闭后企业将无法搜索到您的简历，是否设为关闭?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){e.handleResumeStatus(t)})).catch((function(){e.resumePublic=!t}))},handleResumeStatus:function(t){var e=this;o["a"].post(l["a"].resume_privates_set_display,{display:t?1:0}).then((function(s){200===parseInt(s.code)?(e.$message({message:s.message,type:"success"}),e.getDefectResumeInfo()):(e.$message.error(s.message),e.resumePublic=!t)})).catch((function(s){e.$message.error(s),e.resumePublic=!t}))},refreshResume:function(){var t=this;this.showRefreshPop=!1,o["a"].post(l["a"].resume_refresh).then((function(e){200===parseInt(e.code)?(t.basic.refreshtime=e.data,t.$message({type:"success",message:e.message})):t.$message.error(e.message)})).catch((function(t){console.log(t)}))},initInfo:function(){var t=this;o["a"].get(l["a"].editResume).then((function(e){return null===e.data?(Object(b["a"])({code:50007,message:"请先添加一份简历"}),!1):0==e.data.intention_list.length?(Object(b["a"])({code:50005,message:"请先完善简历求职意向"}),!1):(t.fetchAd(),t.contact=e.data.contact,t.intentionList=e.data.intention_list,t.$store.dispatch("setBasicInfo",e.data.basic),t.$store.dispatch("setResumeContact",e.data.contact),t.$store.dispatch("setIntentionList",e.data.intention_list),t.resumePublic=1==e.data.basic.is_display,t.currentIntention=e.data.intention_list[0],t.recommendIntentionId=e.data.intention_list[0].id,t.previewUrl=e.data.preview_url,t.changeRecommendIntention(t.currentIntention),void t.fetchResumeTotal())})).catch((function(t){console.log(t)}))},fetchResumeTotal:function(){var t=this;o["a"].post(l["a"].personal_index).then((function(e){t.resumeTotal={job_apply:e.data.manage.job_apply,interview:e.data.manage.interview,attention_me:e.data.manage.attention_me,view_job:e.data.manage.view}}))},changeIntention:function(t){this.currentIntention=t},handlerPreview:function(){window.open(this.previewUrl)},changeRecommendIntention:function(t){var e=this;this.recommendIntentionId=t.id,this.showRecommendEmpty=!1,this.recommendList=[],o["a"].get(l["a"].recommend_joblist,{id:t.id,page:1,pagesize:16}).then((function(t){e.recommendList=Object(n["a"])(t.data.items),e.joblistUrl=t.data.joblist_link_url_web,0==e.recommendList.length&&(e.showRecommendEmpty=!0)})).catch((function(){}))},handlerRecommendLink:function(t){window.open(t.job_link_url_web)},handlerAdClick:function(t){Object(v["a"])(t)},handlerEntrust:function(){this.showEntrust=!0},handlerEntrustInit:function(){this.$refs.entrust.initCB()}}},f=h,C=(s("56bc"),Object(d["a"])(f,a,i,!1,null,"27f3c26c",null));e["default"]=C.exports}}]);