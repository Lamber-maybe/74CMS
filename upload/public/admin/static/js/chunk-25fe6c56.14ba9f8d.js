(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-25fe6c56"],{2782:function(t,e,a){"use strict";a.d(e,"g",(function(){return o})),a.d(e,"d",(function(){return s})),a.d(e,"f",(function(){return r})),a.d(e,"h",(function(){return c})),a.d(e,"e",(function(){return l})),a.d(e,"i",(function(){return u})),a.d(e,"j",(function(){return m})),a.d(e,"c",(function(){return p})),a.d(e,"a",(function(){return d})),a.d(e,"b",(function(){return f}));var i=a("b775"),n=a("d722");function o(t){return Object(i["a"])({url:n["a"].imQuickmsgList,method:"post",params:t})}function s(t){return Object(i["a"])({url:n["a"].imQuickmsgAdd,method:"post",data:t})}function r(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(i["a"])({url:n["a"].imQuickmsgEdit,method:e,data:t}):Object(i["a"])({url:n["a"].imQuickmsgEdit,method:e,params:t})}function c(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(i["a"])({url:n["a"].imQuickmsgSaveAll,method:e,data:t}):Object(i["a"])({url:n["a"].imQuickmsgSaveAll,method:e,params:t})}function l(t){return Object(i["a"])({url:n["a"].imQuickmsgDelete,method:"post",data:t})}function u(t){return Object(i["a"])({url:n["a"].imMessageBack,method:"post",data:t})}function m(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(i["a"])({url:n["a"].setImRule,method:e,data:t}):Object(i["a"])({url:n["a"].setImRule,method:e,params:t})}function p(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(i["a"])({url:n["a"].imForbidList,method:e,data:t}):Object(i["a"])({url:n["a"].imForbidList,method:e,params:t})}function d(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(i["a"])({url:n["a"].imChatmanageList,method:e,data:t}):Object(i["a"])({url:n["a"].imChatmanageList,method:e,params:t})}function f(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(i["a"])({url:n["a"].imChatmessageList,method:e,data:t}):Object(i["a"])({url:n["a"].imChatmessageList,method:e,params:t})}},"4a1c":function(t,e,a){},5221:function(t,e,a){"use strict";a("7fb0")},"62d2":function(t,e,a){"use strict";a("c2f9")},"7fb0":function(t,e,a){},8767:function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-tabs",{attrs:{type:"border-card"}},[a("el-tab-pane",{attrs:{label:"基本配置",lazy:!0}},[a("toolimbasic")],1),a("el-tab-pane",{attrs:{label:"个人配置",lazy:!0}},[a("toolimper")],1),a("el-tab-pane",{attrs:{label:"企业配置",lazy:!0}},[a("toolimcom")],1)],1)],1)},n=[],o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-form",{ref:"form",staticClass:"common-form",attrs:{"label-width":"240px","inline-message":!0}},[a("el-form-item",{attrs:{label:"开启职聊功能"}},[a("el-switch",{model:{value:t.im_open,callback:function(e){t.im_open=e},expression:"im_open"}})],1),a("el-form-item",{attrs:{label:"app_key"}},[a("el-input",{model:{value:t.form.app_key,callback:function(e){t.$set(t.form,"app_key",e)},expression:"form.app_key"}})],1),a("el-form-item",{attrs:{label:"app_secret"}},[a("el-input",{model:{value:t.form.app_secret,callback:function(e){t.$set(t.form,"app_secret",e)},expression:"form.app_secret"}})],1),a("el-form-item",{attrs:{label:"开启聊天公告"}},[a("el-switch",{model:{value:t.im_notice_open,callback:function(e){t.im_notice_open=e},expression:"im_notice_open"}})],1),a("el-form-item",{directives:[{name:"show",rawName:"v-show",value:t.im_notice_open,expression:"im_notice_open"}],attrs:{label:"公告内容"}},[a("el-input",{staticClass:"middle",attrs:{maxlength:"50",type:"textarea",rows:4,"show-word-limit":""},model:{value:t.im_notice,callback:function(e){t.im_notice=e},expression:"im_notice"}})],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1)],1)],1)},s=[],r=a("5530"),c=a("1063"),l={data:function(){return{infoLoading:!1,im_open:!1,form:{app_key:"",app_secret:""},im_notice_open:!1,im_notice:""}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.infoLoading=!0;var e={};Object(c["j"])(e,"get").then((function(e){var a=Object(r["a"])({},e.data),i=a.account_im,n=a.im_open,o=a.im_unread,s=a.im_notice_open,c=a.im_notice;t.form=i,t.im_open="1"===n,t.im_unread="1"===o,t.im_notice_open="1"===s,t.im_notice=c,t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,a={im_open:this.im_open?"1":"0",account_im:Object(r["a"])({},this.form),im_unread:this.im_unread?"1":"0",im_notice_open:this.im_notice_open?"1":"0",im_notice:this.im_notice};Object(c["j"])(a).then((function(t){return e.$message.success(t.message),!0})).catch((function(){}))}}},u=l,m=(a("5221"),a("2877")),p=Object(m["a"])(u,o,s,!1,null,"13cbff60",null),d=p.exports,f=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-form",{ref:"form",staticClass:"common-form",attrs:{"label-width":"240px","inline-message":!0}},[a("el-form-item",{attrs:{label:"强制关注公众号"}},[a("el-switch",{model:{value:t.bind_weixin,callback:function(e){t.bind_weixin=e},expression:"bind_weixin"}})],1),a("el-form-item",{attrs:{label:"个人聊天简历完整度限制",prop:"complete_percent"}},[a("el-input",{model:{value:t.complete_percent,callback:function(e){t.complete_percent=t._n(e)},expression:"complete_percent"}},[a("template",{slot:"append"},[t._v("%")])],2),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),t._v(" 0表示不限制 ")])],1),a("el-form-item",{attrs:{label:"个人简历状态限制"}},[a("el-radio-group",{model:{value:t.audit_status,callback:function(e){t.audit_status=e},expression:"audit_status"}},[a("el-radio",{attrs:{label:"1"}},[t._v("审核通过")]),a("el-radio",{attrs:{label:"3"}},[t._v("不限")])],1)],1),a("el-form-item",{attrs:{label:"个人发起次数",prop:"im_per_launch_time"}},[a("el-input",{model:{value:t.max_per_day,callback:function(e){t.max_per_day=t._n(e)},expression:"max_per_day"}},[a("template",{slot:"append"},[t._v("次/天")])],2)],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1)],1)],1)},_=[],b=a("2782"),h={data:function(){return{infoLoading:!1,bind_weixin:!1,complete_percent:"",audit_status:"",max_per_day:""}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.infoLoading=!0;var e={utype:2};Object(b["j"])(e,"get").then((function(e){var a=Object(r["a"])({},e.data),i=a.bind_weixin,n=a.complete_percent,o=a.audit_status,s=a.max_per_day;t.bind_weixin="1"===i,t.complete_percent=n,t.audit_status=o,t.max_per_day=s,t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,a={bind_weixin:this.bind_weixin?"1":"0",complete_percent:this.complete_percent,audit_status:this.audit_status,max_per_day:this.max_per_day,utype:2};Object(b["j"])(a).then((function(t){return e.$message.success(t.message),!0})).catch((function(){}))}}},v=h,x=(a("a9c5"),Object(m["a"])(v,f,_,!1,null,"34ede182",null)),g=x.exports,j=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[t._m(0),a("el-form",{ref:"form",staticClass:"common-form",attrs:{"label-width":"240px","inline-message":!0}},[a("el-form-item",{attrs:{label:"强制关注公众号"}},[a("el-switch",{model:{value:t.bind_weixin,callback:function(e){t.bind_weixin=e},expression:"bind_weixin"}})],1),a("el-form-item",{attrs:{label:"企业显示状态限制"}},[a("el-radio-group",{model:{value:t.display_status,callback:function(e){t.display_status=e},expression:"display_status"}},[a("el-radio",{attrs:{label:"1"}},[t._v("显示中")]),a("el-radio",{attrs:{label:"3"}},[t._v("不限")])],1)],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1)],1)],1)},y=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"tip"},[a("p",[t._v(" 不同套餐类型的企业发起次数、每天可聊次数请在 `系统 --\x3e 企业业务配置 --\x3e 套餐配置` 中进行配置 ")])])}],w={data:function(){return{infoLoading:!1,bind_weixin:!1,display_status:""}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.infoLoading=!0;var e={utype:1};Object(b["j"])(e,"get").then((function(e){var a=Object(r["a"])({},e.data),i=a.bind_weixin,n=a.display_status;t.bind_weixin="1"===i,t.display_status=n,t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,a={bind_weixin:this.bind_weixin?"1":"0",display_status:this.display_status,utype:1};Object(b["j"])(a).then((function(t){return e.$message.success(t.message),!0})).catch((function(){}))}}},k=w,O=(a("62d2"),Object(m["a"])(k,j,y,!1,null,"74fdb232",null)),L=O.exports,C={components:{toolimbasic:d,toolimper:g,toolimcom:L}},$=C,S=Object(m["a"])($,i,n,!1,null,null,null);e["default"]=S.exports},a9c5:function(t,e,a){"use strict";a("4a1c")},c2f9:function(t,e,a){}}]);