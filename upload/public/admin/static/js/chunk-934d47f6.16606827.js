(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-934d47f6"],{"264d":function(e,t,a){"use strict";a.r(t);var o=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[e._m(0),a("el-tabs",{attrs:{type:"border-card"}},[a("el-tab-pane",{attrs:{label:"基本设置",lazy:!0}},[a("config-basic")],1),a("el-tab-pane",{attrs:{label:"联系方式",lazy:!0}},[a("config-contact")],1),a("el-tab-pane",{attrs:{label:"认证/审核",lazy:!0}},[a("config-audit")],1),a("el-tab-pane",{attrs:{label:"搜索",lazy:!0}},[a("config-search")],1)],1)],1)},i=[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"tip"},[a("p",[e._v(" 不同的运营阶段您可以选择不同的设置。 ")])])}],r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("div",{staticClass:"spaceline"}),a("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"200px",rules:e.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"允许公司名称重复",required:""}},[a("el-switch",{model:{value:e.form.company_repeat,callback:function(t){e.$set(e.form,"company_repeat",t)},expression:"form.company_repeat"}})],1),a("el-form-item",{attrs:{label:"允许企业充值积分",required:""}},[a("el-switch",{model:{value:e.form.enable_com_buy_points,callback:function(t){e.$set(e.form,"enable_com_buy_points",t)},expression:"form.enable_com_buy_points"}})],1),a("el-form-item",{attrs:{label:"申请职位间隔时间",prop:"apply_jobs_space"}},[a("el-input",{model:{value:e.form.apply_jobs_space,callback:function(t){e.$set(e.form,"apply_jobs_space",t)},expression:"form.apply_jobs_space"}},[a("template",{slot:"append"},[e._v("天")])],2),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),e._v(" 申请同一职位的间隔天数，0表示不允许重复申请 ")])],1),a("el-form-item",{attrs:{label:"刷新职位时间间隔",prop:"refresh_jobs_space"}},[a("el-input",{model:{value:e.form.refresh_jobs_space,callback:function(t){e.$set(e.form,"refresh_jobs_space",t)},expression:"form.refresh_jobs_space"}},[a("template",{slot:"append"},[e._v("分钟")])],2),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),e._v(" 刷新同一职位的间隔分钟数，0表示不限制 ")])],1),a("el-form-item",{attrs:{label:"职位列表最大显示条数",prop:"job_list_max"}},[a("el-input",{attrs:{min:"0",max:"1000"},model:{value:e.form.job_list_max,callback:function(t){e.$set(e.form,"job_list_max",e._n(t))},expression:"form.job_list_max"}}),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),e._v(" 职位列表可展示的职位数量上限，0表示不限制 ")])],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")])],1)],1)],1)},n=[],s=a("5530"),l=a("1063"),c={data:function(){return{infoLoading:!0,form:{company_repeat:!1,apply_jobs_space:0,refresh_jobs_space:0,job_list_max:0,enable_com_buy_points:!1},rules:{apply_jobs_space:[{required:!0,message:"请输入申请职位间隔时间",trigger:"blur"}],refresh_jobs_space:[{required:!0,message:"请输入刷新职位时间间隔",trigger:"blur"}],job_list_max:[{required:!0,message:"请输入职位列表最大显示条数",trigger:"blur"}]}}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0;var t={};Object(l["j"])(t,"get").then((function(t){var a=Object(s["a"])({},t.data),o=a.company_repeat,i=a.apply_jobs_space,r=a.refresh_jobs_space,n=a.job_list_max,l=a.enable_com_buy_points;e.form={company_repeat:1==o,apply_jobs_space:i,refresh_jobs_space:r,job_list_max:n,enable_com_buy_points:1==l},e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,a=Object(s["a"])({},this.form);this.$refs[e].validate((function(e){if(!e)return!1;a.company_repeat=!0===a.company_repeat?1:0,a.enable_com_buy_points=!0===a.enable_com_buy_points?1:0,Object(l["j"])(a).then((function(e){return t.$store.dispatch("config/getConfigInfo"),t.$message.success(e.message),!0})).catch((function(){}))}))}}},m=c,f=(a("9b60"),a("2877")),u=Object(f["a"])(m,r,n,!1,null,"b8ea197a",null),_=u.exports,d=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("div",{staticClass:"spaceline"}),a("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"200px",rules:e.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"web端查看联系方式条件",required:""}},[a("el-radio-group",{model:{value:e.form.showjobcontact,callback:function(t){e.$set(e.form,"showjobcontact",t)},expression:"form.showjobcontact"}},[a("el-radio",{attrs:{label:"0"}},[e._v("游客")]),a("el-radio",{attrs:{label:"1"}},[e._v("已登录")]),a("el-radio",{attrs:{label:"2"}},[e._v("已登录有简历")]),a("el-radio",{attrs:{label:"3"}},[e._v("投递后显示")])],1)],1),a("el-form-item",{attrs:{label:"移动端查看联系方式条件",required:""}},[a("el-radio-group",{model:{value:e.form.showjobcontact_mobile,callback:function(t){e.$set(e.form,"showjobcontact_mobile",t)},expression:"form.showjobcontact_mobile"}},[a("el-radio",{attrs:{label:"0"}},[e._v("游客")]),a("el-radio",{attrs:{label:"1"}},[e._v("已登录")]),a("el-radio",{attrs:{label:"2"}},[e._v("已登录有简历")]),a("el-radio",{attrs:{label:"3"}},[e._v("投递后显示")])],1)],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")])],1)],1)],1)},b=[],p={data:function(){return{infoLoading:!0,form:{showjobcontact:1,showjobcontact_mobile:1},rules:{}}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0;var t={};Object(l["j"])(t,"get").then((function(t){var a=Object(s["a"])({},t.data),o=a.showjobcontact,i=a.showjobcontact_mobile;e.form={showjobcontact:o,showjobcontact_mobile:i},e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,a=Object(s["a"])({},this.form);this.$refs[e].validate((function(e){if(!e)return!1;Object(l["j"])(a).then((function(e){return t.$store.dispatch("config/getConfigInfo"),t.$message.success(e.message),!0})).catch((function(){}))}))}}},v=p,h=Object(f["a"])(v,d,b,!1,null,null,null),j=h.exports,g=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("div",{staticClass:"spaceline"}),a("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"220px",rules:e.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"强制企业认证营业执照",required:""}},[a("el-switch",{model:{value:e.form.must_com_audit_certificate,callback:function(t){e.$set(e.form,"must_com_audit_certificate",t)},expression:"form.must_com_audit_certificate"}})],1),a("el-form-item",{attrs:{label:"新注册企业认证状态",required:""}},[a("el-radio-group",{model:{value:e.form.audit_new_com,callback:function(t){e.$set(e.form,"audit_new_com",t)},expression:"form.audit_new_com"}},[a("el-radio",{attrs:{label:"0"}},[e._v("待认证")]),a("el-radio",{attrs:{label:"1"}},[e._v("认证通过")])],1)],1),a("el-form-item",{attrs:{label:"修改企业资料后认证状态",required:""}},[a("el-radio-group",{model:{value:e.form.audit_edit_com,callback:function(t){e.$set(e.form,"audit_edit_com",t)},expression:"form.audit_edit_com"}},[a("el-radio",{attrs:{label:"0"}},[e._v("保持不变")]),a("el-radio",{attrs:{label:"1"}},[e._v("重新认证")])],1)],1),a("el-form-item",{attrs:{label:"未认证企业新发布职位审核状态",required:""}},[a("el-radio-group",{model:{value:e.form.audit_unverifycom_addjob,callback:function(t){e.$set(e.form,"audit_unverifycom_addjob",t)},expression:"form.audit_unverifycom_addjob"}},[a("el-radio",{attrs:{label:"0"}},[e._v("待审核")]),a("el-radio",{attrs:{label:"1"}},[e._v("审核通过")])],1)],1),a("el-form-item",{attrs:{label:"未认证企业修改职位后审核状态",required:""}},[a("el-radio-group",{model:{value:e.form.audit_unverifycom_editjob,callback:function(t){e.$set(e.form,"audit_unverifycom_editjob",t)},expression:"form.audit_unverifycom_editjob"}},[a("el-radio",{attrs:{label:"0"}},[e._v("保持不变")]),a("el-radio",{attrs:{label:"1"}},[e._v("重新认证")])],1)],1),a("el-form-item",{attrs:{label:"已认证企业新发布职位审核状态",required:""}},[a("el-radio-group",{model:{value:e.form.audit_verifycom_addjob,callback:function(t){e.$set(e.form,"audit_verifycom_addjob",t)},expression:"form.audit_verifycom_addjob"}},[a("el-radio",{attrs:{label:"0"}},[e._v("待审核")]),a("el-radio",{attrs:{label:"1"}},[e._v("审核通过")])],1)],1),a("el-form-item",{attrs:{label:"已认证企业修改职位后审核状态",required:""}},[a("el-radio-group",{model:{value:e.form.audit_verifycom_editjob,callback:function(t){e.$set(e.form,"audit_verifycom_editjob",t)},expression:"form.audit_verifycom_editjob"}},[a("el-radio",{attrs:{label:"0"}},[e._v("保持不变")]),a("el-radio",{attrs:{label:"1"}},[e._v("重新认证")])],1)],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")])],1)],1)],1)},y=[],x={data:function(){return{infoLoading:!0,form:{must_com_audit_certificate:!1,audit_new_com:0,audit_edit_com:0,audit_verifycom_addjob:0,audit_verifycom_editjob:0,audit_unverifycom_addjob:0,audit_unverifycom_editjob:0},rules:{}}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0;var t={};Object(l["j"])(t,"get").then((function(t){var a=Object(s["a"])({},t.data),o=a.audit_new_com,i=a.audit_edit_com,r=a.audit_verifycom_addjob,n=a.audit_verifycom_editjob,l=a.audit_unverifycom_addjob,c=a.audit_unverifycom_editjob,m=a.must_com_audit_certificate;e.form={audit_new_com:o,audit_edit_com:i,audit_verifycom_addjob:r,audit_verifycom_editjob:n,audit_unverifycom_addjob:l,audit_unverifycom_editjob:c,must_com_audit_certificate:1==m},e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,a=Object(s["a"])({},this.form);this.$refs[e].validate((function(e){if(!e)return!1;a.must_com_audit_certificate=!0===a.must_com_audit_certificate?1:0,Object(l["j"])(a).then((function(e){return t.$store.dispatch("config/getConfigInfo"),t.$message.success(e.message),!0})).catch((function(){}))}))}}},w=x,$=Object(f["a"])(w,g,y,!1,null,null,null),C=$.exports,k=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("div",{staticClass:"spaceline"}),a("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"200px",rules:e.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"搜索职位登录限制",required:""}},[a("el-switch",{model:{value:e.form.job_search_login,callback:function(t){e.$set(e.form,"job_search_login",t)},expression:"form.job_search_login"}})],1),a("el-form-item",{attrs:{label:"搜索职位登录条数限制",prop:"job_search_login_num"}},[a("el-input",{model:{value:e.form.job_search_login_num,callback:function(t){e.$set(e.form,"job_search_login_num",t)},expression:"form.job_search_login_num"}}),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),e._v(" 未登录会员可以搜索职位条数 ")])],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")])],1)],1)],1)},O=[],L={data:function(){return{infoLoading:!0,form:{job_search_login:!1,job_search_login_num:0},rules:{job_search_login_num:[{required:!0,message:"请输入搜索职位登录条数限制",trigger:"blur"}]}}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0;var t={};Object(l["j"])(t,"get").then((function(t){var a=Object(s["a"])({},t.data),o=a.job_search_login,i=a.job_search_login_num;e.form={job_search_login:1==o,job_search_login_num:i},e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,a=Object(s["a"])({},this.form);this.$refs[e].validate((function(e){if(!e)return!1;a.job_search_login=!0===a.job_search_login?1:0,Object(l["j"])(a).then((function(e){return t.$store.dispatch("config/getConfigInfo"),t.$message.success(e.message),!0})).catch((function(){}))}))}}},q=L,I=(a("695d"),Object(f["a"])(q,k,O,!1,null,"5af5d3be",null)),S=I.exports,E={components:{configBasic:_,configContact:j,configAudit:C,configSearch:S}},z=E,N=Object(f["a"])(z,o,i,!1,null,null,null);t["default"]=N.exports},"2a1c":function(e,t,a){},"695d":function(e,t,a){"use strict";var o=a("e200"),i=a.n(o);i.a},"9b60":function(e,t,a){"use strict";var o=a("2a1c"),i=a.n(o);i.a},e200:function(e,t,a){}}]);