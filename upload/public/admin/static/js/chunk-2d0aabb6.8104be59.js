(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0aabb6"],{"11ee":function(e,t,o){"use strict";o.r(t);var a=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"app-container"},[o("el-tabs",{attrs:{type:"border-card"}},[o("el-tab-pane",{attrs:{label:"qq互联",lazy:!0}},[o("qq")],1),o("el-tab-pane",{attrs:{label:"腾讯rtc",lazy:!0}},[o("trtc")],1),o("el-tab-pane",{attrs:{label:"微信开放平台",lazy:!0}},[o("weixinopen")],1),o("el-tab-pane",{attrs:{label:"号码隐私保护",lazy:!0}},[o("alicloudpro")],1),o("el-tab-pane",{attrs:{label:"简历解析",lazy:!0}},[o("resumeanalysis")],1),o("el-tab-pane",{attrs:{label:"在线文档预览",lazy:!0}},[o("zhitooview")],1)],1)],1)},n=[],c=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"app-container"},[e._m(0),o("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px"}},[o("el-form-item",{attrs:{label:"开启qq登录"}},[o("el-switch",{model:{value:e.form.account_qqlogin_open,callback:function(t){e.$set(e.form,"account_qqlogin_open",t)},expression:"form.account_qqlogin_open"}})],1),o("el-form-item",{attrs:{label:"AppId"}},[o("el-input",{staticClass:"middle",model:{value:e.form.account_qqlogin_appid,callback:function(t){e.$set(e.form,"account_qqlogin_appid",t)},expression:"form.account_qqlogin_appid"}})],1),o("el-form-item",{attrs:{label:"AppKey"}},[o("el-input",{staticClass:"middle",model:{value:e.form.account_qqlogin_appkey,callback:function(t){e.$set(e.form,"account_qqlogin_appkey",t)},expression:"form.account_qqlogin_appkey"}})],1),o("el-form-item",{attrs:{label:""}},[o("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")])],1)],1)],1)},i=[function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"tip"},[o("p",[e._v(" 网站接入QQ登录后，用户只需要使用QQ账号密码就可登录，简化用户注册流程，更有效率的提高转化用户流量 ")]),o("p",[e._v(" 接入QQ登录前，网站需首先进行申请，获得对应的appid与appkey，以保证后续流程中可正确对网站与用户进行验证与授权。 现在就去 "),o("a",{staticStyle:{color:"#1E88E5"},attrs:{href:"https://connect.qq.com/index.html",target:"_blank"}},[e._v(" [申请] ")])]),o("p",[e._v(" 网站接入QQ登录后，为了用户信息同步，请站长在网站应用和移动端应用审核通过后，在各自的应用--应用接口（申请unionid开启），否则用户信息将不会同步给使用者带来不必要的麻烦 ")])])}],r=o("5530"),l=o("1063"),s={data:function(){return{infoLoading:!0,form:{account_qqlogin_open:!1,account_qqlogin_appid:"",account_qqlogin_appkey:""}}},mounted:function(){},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0;var t={};Object(l["n"])(t,"get").then((function(t){var o=Object(r["a"])({},t.data),a=o.account_qqlogin_open,n=o.account_qqlogin_appid,c=o.account_qqlogin_appkey;e.form={account_qqlogin_open:1==a,account_qqlogin_appid:n,account_qqlogin_appkey:c},e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,o=Object(r["a"])({},this.form);o.account_qqlogin_open=!0===o.account_qqlogin_open?1:0,this.$refs[e].validate((function(e){if(!e)return!1;Object(l["n"])(o).then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))}))}}},p=s,u=o("2877"),_=Object(u["a"])(p,c,i,!1,null,null,null),f=_.exports,m=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"app-container"},[e._m(0),!0===e.setting_secrecy?o("div",{staticClass:"no-promise"},[e._m(1)]):o("el-form",{ref:"form",staticClass:"common-form",attrs:{"v-loading":e.infoLoading,model:e.form,"label-width":"120px"}},[o("el-form-item",{attrs:{label:"AppId"}},[o("el-input",{staticClass:"middle",model:{value:e.form.account_trtc_appid,callback:function(t){e.$set(e.form,"account_trtc_appid",t)},expression:"form.account_trtc_appid"}})],1),o("el-form-item",{attrs:{label:"SecretKey"}},[o("el-input",{staticClass:"middle",model:{value:e.form.account_trtc_secretkey,callback:function(t){e.$set(e.form,"account_trtc_secretkey",t)},expression:"form.account_trtc_secretkey"}})],1),o("el-form-item",{attrs:{label:""}},[o("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")])],1)],1)],1)},d=[function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"tip"},[o("p",[e._v(" 视频面试集成了腾讯云trtc技术，该服务是收费的，费用由腾讯云收取。现在就去 "),o("a",{staticStyle:{color:"#1E88E5"},attrs:{href:"https://cloud.tencent.com/product/trtc",target:"_blank"}},[e._v(" [申请] ")])]),o("p",[e._v(" 为保证视频面试的流畅性和可用性，建议您提前配置https，并引导客户使用谷歌内核浏览器参与视频面试 ")])])},function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"notice-div"},[o("p",{staticClass:"notice-p"},[e._v("暂无查看权限，请联系网站负责人")])])}],h={data:function(){return{infoLoading:!0,form:{account_trtc_appid:"",account_trtc_secretkey:""},setting_secrecy:!1}},mounted:function(){},created:function(){this.setting_secrecy=!!window.global.SettingSecrecy&&window.global.SettingSecrecy,this.fetchInfo()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0;var t={};Object(l["n"])(t,"get").then((function(t){var o=Object(r["a"])({},t.data),a=o.account_trtc_appid,n=o.account_trtc_secretkey;e.form={account_trtc_appid:a,account_trtc_secretkey:n},e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,o=Object(r["a"])({},this.form);this.$refs[e].validate((function(e){if(!e)return!1;Object(l["n"])(o).then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))}))}}},g=h,b=Object(u["a"])(g,m,d,!1,null,null,null),v=b.exports,y=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"app-container"},[e._m(0),o("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px"}},[o("el-form-item",{attrs:{label:"开启微信登录"}},[o("el-switch",{model:{value:e.form.wechat_login_open,callback:function(t){e.$set(e.form,"wechat_login_open",t)},expression:"form.wechat_login_open"}})],1),o("el-form-item",{attrs:{label:"AppId"}},[o("el-input",{staticClass:"middle",model:{value:e.form.wechat_open_appid,callback:function(t){e.$set(e.form,"wechat_open_appid",t)},expression:"form.wechat_open_appid"}})],1),o("el-form-item",{attrs:{label:"AppSecret"}},[o("el-input",{staticClass:"middle",model:{value:e.form.wechat_open_appsecret,callback:function(t){e.$set(e.form,"wechat_open_appsecret",t)},expression:"form.wechat_open_appsecret"}})],1),o("el-form-item",{attrs:{label:""}},[o("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")])],1)],1)],1)},k=[function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"tip"},[o("p",[e._v(" 网站接入微信帐号登录后，用户只需要使用微信账号扫码就可登录，简化用户注册流程，更有效率的提高转化用户流量 ")]),o("p",[e._v(" 接入微信登录前，网站需首先进行申请，获得对应的AppID与AppSecret，以保证后续流程中可正确对网站与用户进行验证与授权。现在就去 "),o("a",{staticStyle:{color:"#1E88E5"},attrs:{href:"https://open.weixin.qq.com/",target:"_blank"}},[e._v(" [申请] ")])])])}],S={data:function(){return{infoLoading:!0,form:{wechat_login_open:!1,wechat_open_appid:"",wechat_open_appsecret:""}}},mounted:function(){},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0;var t={};Object(l["n"])(t,"get").then((function(t){var o=Object(r["a"])({},t.data),a=o.wechat_login_open,n=o.wechat_open_appid,c=o.wechat_open_appsecret;e.form={wechat_login_open:1==a,wechat_open_appid:n,wechat_open_appsecret:c},e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,o=Object(r["a"])({},this.form);o.wechat_login_open=!0===o.wechat_login_open?1:0,this.$refs[e].validate((function(e){if(!e)return!1;Object(l["n"])(o).then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))}))}}},q=S,w=Object(u["a"])(q,y,k,!1,null,null,null),x=w.exports,C=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"app-container"},[e._m(0),!0===e.setting_secrecy?o("div",{staticClass:"no-promise"},[e._m(1)]):o("el-form",{ref:"form",staticClass:"common-form",attrs:{"v-loading":e.infoLoading,model:e.form,"label-width":"120px"}},[o("el-form-item",{attrs:{label:"开启保护"}},[o("el-switch",{model:{value:e.form.alicloud_phone_protect_open,callback:function(t){e.$set(e.form,"alicloud_phone_protect_open",t)},expression:"form.alicloud_phone_protect_open"}})],1),o("el-form-item",{attrs:{label:"号码隐私模式"}},[o("el-radio-group",{attrs:{disabled:!e.form.alicloud_phone_protect_open},model:{value:e.form.alicloud_phone_protect_type,callback:function(t){e.$set(e.form,"alicloud_phone_protect_type",t)},expression:"form.alicloud_phone_protect_type"}},[o("el-radio",{attrs:{label:"1"}},[e._v("AxB")]),o("el-radio",{attrs:{label:"2"}},[e._v("AxN")])],1)],1),o("el-form-item",{attrs:{label:"隐私保护主体"}},[o("el-checkbox-group",{attrs:{disabled:!e.form.alicloud_phone_protect_open},model:{value:e.form.alicloud_phone_protect_target,callback:function(t){e.$set(e.form,"alicloud_phone_protect_target",t)},expression:"form.alicloud_phone_protect_target"}},[o("el-checkbox",{attrs:{label:"2",disabled:!0}},[e._v("个人会员")]),o("el-checkbox",{attrs:{label:"1"}},[e._v("企业会员")])],1)],1),o("el-form-item",{attrs:{label:"号池key"}},[1==e.form.alicloud_phone_protect_type?o("el-input",{staticClass:"middle",attrs:{disabled:!e.form.alicloud_phone_protect_open},model:{value:e.form.alicloud_pool_key,callback:function(t){e.$set(e.form,"alicloud_pool_key",t)},expression:"form.alicloud_pool_key"}}):e._e(),2==e.form.alicloud_phone_protect_type?o("el-input",{staticClass:"middle",attrs:{disabled:!e.form.alicloud_phone_protect_open},model:{value:e.form.alicloud_pool_key_axn,callback:function(t){e.$set(e.form,"alicloud_pool_key_axn",t)},expression:"form.alicloud_pool_key_axn"}}):e._e()],1),o("el-form-item",{attrs:{label:"AccessKeyId"}},[o("el-input",{staticClass:"middle",attrs:{disabled:!e.form.alicloud_phone_protect_open},model:{value:e.form.alicloud_app_key,callback:function(t){e.$set(e.form,"alicloud_app_key",t)},expression:"form.alicloud_app_key"}})],1),o("el-form-item",{attrs:{label:"AccessKeySecret"}},[o("el-input",{staticClass:"middle",attrs:{disabled:!e.form.alicloud_phone_protect_open},model:{value:e.form.alicloud_appsecret,callback:function(t){e.$set(e.form,"alicloud_appsecret",t)},expression:"form.alicloud_appsecret"}})],1),o("el-form-item",{attrs:{label:""}},[o("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")])],1)],1)],1)},$=[function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"tip"},[o("p",[e._v(" 本服务基于阿里云`号码隐私保护`服务，该服务为收费项目，具体收费标准以阿里云官方为准。["),o("a",{staticStyle:{color:"#1E88E5"},attrs:{href:"https://www.aliyun.com/product/pls?spm=5176.19720258.J_8058803260.44.e9392c4aZL2Yz4",target:"_blank"}},[e._v("申请地址")]),e._v("] ["),o("a",{staticStyle:{color:"#1E88E5"},attrs:{href:"http://doc.74cms.com/#/se/aliconceal_apply",target:"_blank"}},[e._v("申请教程")]),e._v("] ")]),o("p",[e._v("您可以自主选择使用AxB模式或AxN模式，AxB模式所需号码相对较少(拔号要求高)，AxN模式所需号码较多(体验较好)")]),o("p",[e._v("号码隐私保护开启后，全站个人简历联系将以虚拟号码呈现，同时您可以选择是否启用企业的虚拟保护。")])])},function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"notice-div"},[o("p",{staticClass:"notice-p"},[e._v("暂无查看权限，请联系网站负责人")])])}],j=(o("ac1f"),o("1276"),o("a15b"),{name:"Alicloudpro",data:function(){return{infoLoading:!0,form:{alicloud_phone_protect_open:0,alicloud_phone_protect_type:"",alicloud_app_key:"",alicloud_appsecret:"",alicloud_pool_key:"",alicloud_pool_key_axn:"",alicloud_phone_protect_target:[2]},setting_secrecy:!1}},mounted:function(){},created:function(){this.setting_secrecy=!!window.global.SettingSecrecy&&window.global.SettingSecrecy,this.fetchInfo()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0;var t={};Object(l["n"])(t,"get").then((function(t){var o=Object(r["a"])({},t.data),a=o.alicloud_phone_protect_open,n=o.alicloud_phone_protect_type,c=o.alicloud_app_key,i=o.alicloud_pool_key_axn,l=o.alicloud_appsecret,s=o.alicloud_pool_key,p=o.alicloud_phone_protect_target,u={alicloud_phone_protect_open:a,alicloud_phone_protect_type:n,alicloud_app_key:c,alicloud_pool_key_axn:i,alicloud_appsecret:l,alicloud_pool_key:s,alicloud_phone_protect_target:p};u.alicloud_phone_protect_target=u.alicloud_phone_protect_target&&u.alicloud_phone_protect_target.split(",")||[2],u.alicloud_phone_protect_open=!!parseInt(u.alicloud_phone_protect_open),e.form=u,e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,o=Object(r["a"])({},this.form);o.alicloud_phone_protect_open=o.alicloud_phone_protect_open?1:0,o.alicloud_phone_protect_target=o.alicloud_phone_protect_target.join(","),this.$refs[e].validate((function(e){if(!e)return!1;Object(l["n"])(o).then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))}))}}}),K=j,A=Object(u["a"])(K,C,$,!1,null,"4a91756f",null),O=A.exports,L=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"app-container"},[o("div",{staticClass:"tip"},[o("p",[e._v(" 本服务基于智兔引擎`轻阅`应用，该服务为收费服务，请联系您的专属客服开通服务。 ")]),o("p",[e._v("您可自主决定是否启用此功能，简历解析支持站外导入pdf、doc、docx、txt、html格式的文件，求职者无需繁琐输入，系统智能完成简历创建 ")]),0==e.is_check?o("p",[e._v(" 您当前尚未开通服务，请联系官方客服开通。 ")]):e._e(),1==e.is_check?o("p",[e._v(" 当前解析剩余："+e._s(e.total)+" 次 "),o("el-tooltip",{staticClass:"item",attrs:{effect:"dark",placement:"bottom"}},[o("div",{attrs:{slot:"content"},slot:"content"},e._l(e.accountBalanceList,(function(t,a){return o("p",{key:a,staticClass:"tooltipItem"},[e._v(e._s(t.num)+" 次查询余额将在 "+e._s(e._f("timeFilter")(t.deadline))+" 到期")])})),0),o("i",{staticClass:"el-icon-info",staticStyle:{color:"rgb(86, 82, 83)",cursor:"pointer"}})])],1):e._e()]),!0===e.setting_secrecy?o("div",{staticClass:"no-promise"},[e._m(0)]):o("el-form",{ref:"form",staticClass:"common-form",attrs:{"v-loading":e.infoLoading,rules:e.rules,model:e.form,"label-width":"120px"}},[o("el-form-item",{attrs:{label:"启用简历解析"}},[o("el-switch",{on:{change:function(t){return e.switchChangeBtn(e.form.is_open)}},model:{value:e.form.is_open,callback:function(t){e.$set(e.form,"is_open",t)},expression:"form.is_open"}})],1),o("el-form-item",{attrs:{label:"AppKey",prop:"appKey"}},[o("el-input",{staticClass:"middle",model:{value:e.form.appKey,callback:function(t){e.$set(e.form,"appKey",t)},expression:"form.appKey"}})],1),o("el-form-item",{attrs:{label:"AppSecret",prop:"appSecret"}},[o("el-input",{staticClass:"middle",model:{value:e.form.appSecret,callback:function(t){e.$set(e.form,"appSecret",t)},expression:"form.appSecret"}})],1),o("el-form-item",{attrs:{label:""}},[o("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")])],1)],1)],1)},E=[function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"notice-div"},[o("p",{staticClass:"notice-p"},[e._v("暂无查看权限，请联系网站负责人")])])}],I=o("ed08"),z={filters:{timeFilter:function(e){return Object(I["b"])(e,"{y}-{m}-{d} {h}:{i}:{s}")}},data:function(){return{infoLoading:!1,form:{is_open:!1,appKey:"",appSecret:""},rules:{appKey:[{required:!0,message:"请输入AppKey",trigger:"blur"}],appSecret:[{required:!0,message:"请输入AppSecret",trigger:"blur"}]},accountBalanceList:[],total:0,is_check:0,setting_secrecy:!1}},mounted:function(){},created:function(){this.setting_secrecy=!!window.global.SettingSecrecy&&window.global.SettingSecrecy,this.fetchInfo(),this.getResumeCheck()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0;var t={};Object(l["k"])(t,"get").then((function(t){var o=Object(r["a"])({},t.data),a=o.is_open,n=o.appKey,c=o.appSecret;e.form={is_open:1==a,appKey:n,appSecret:c},e.form.is_open?e.rules={appKey:[{required:!0,message:"请输入AppKey",trigger:"blur"}],appSecret:[{required:!0,message:"请输入AppSecret",trigger:"blur"}]}:e.rules={appKey:[{required:!1,message:"请输入AppKey",trigger:"blur"}],appSecret:[{required:!1,message:"请输入AppSecret",trigger:"blur"}]},e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,o=Object(r["a"])({},this.form);o.is_open=!0===o.is_open?1:0,this.$refs[e].validate((function(e){if(!e)return!1;Object(l["k"])(o,"post").then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))}))},getResumeCheck:function(){var e=this;Object(l["j"])().then((function(t){e.total=t.data.total,e.accountBalanceList=t.data.list,e.is_check=t.data.is_check}))},switchChangeBtn:function(e){this.rules=e?{appKey:[{required:!0,message:"请输入AppKey",trigger:"blur"}],appSecret:[{required:!0,message:"请输入AppSecret",trigger:"blur"}]}:{appKey:[{required:!1,message:"请输入AppKey",trigger:"blur"}],appSecret:[{required:!1,message:"请输入AppSecret",trigger:"blur"}]}}}},B=z,Q=Object(u["a"])(B,L,E,!1,null,null,null),N=Q.exports,F=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"app-container"},[o("div",{staticClass:"tip"},[o("p",[e._v(" 本服务基于智兔引擎`易览`应用，该服务为收费服务，如需开通业务请联系您的专属客服。 ")]),o("p",[e._v(" 支持 doc、docx、xls、xlsx、ppt、pptx、pdf、dwg、zip、rar、txt、js、html、mp3、mp4等常用格式的文件实时线上预览。 ")]),o("p",[e._v(" 本场景内应用于简历附件查看。 ")]),0==e.is_check?o("p",[e._v(" 您当前尚未开通服务，请联系官方客服开通。 ")]):e._e(),1==e.is_check?o("p",[e._v(" 服务有效期至："+e._s(e._f("timeFilter")(e.deadline))+" ")]):e._e()]),!0===e.setting_secrecy?o("div",{staticClass:"no-promise"},[e._m(0)]):o("el-form",{ref:"form",staticClass:"common-form",attrs:{"v-loading":e.infoLoading,rules:e.rules,model:e.form,"label-width":"120px"}},[o("el-form-item",{attrs:{label:"AppKey",prop:"appKey"}},[o("el-input",{staticClass:"middle",on:{change:e.inputChange},model:{value:e.form.appKey,callback:function(t){e.$set(e.form,"appKey",t)},expression:"form.appKey"}})],1),o("el-form-item",{attrs:{label:"AppSecret",prop:"appSecret"}},[o("el-input",{staticClass:"middle",on:{change:e.inputChange},model:{value:e.form.appSecret,callback:function(t){e.$set(e.form,"appSecret",t)},expression:"form.appSecret"}})],1),o("el-form-item",{attrs:{label:""}},[o("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")])],1)],1)],1)},D=[function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"notice-div"},[o("p",{staticClass:"notice-p"},[e._v("暂无查看权限，请联系网站负责人")])])}],J={filters:{timeFilter:function(e){return Object(I["b"])(e,"{y}-{m}-{d} {h}:{i}:{s}")}},data:function(){return{infoLoading:!1,form:{appKey:"",appSecret:""},rules:{appKey:[{required:!0,message:"请输入AppKey",trigger:"blur"}],appSecret:[{required:!0,message:"请输入AppSecret",trigger:"blur"}]},is_check:0,deadline:0,setting_secrecy:!1}},mounted:function(){},created:function(){this.setting_secrecy=!!window.global.SettingSecrecy&&window.global.SettingSecrecy,this.fetchInfo(),this.getDocumentCheck()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0;var t={};Object(l["c"])(t,"get").then((function(t){var o=Object(r["a"])({},t.data),a=o.appKey,n=o.appSecret;e.form={appKey:a,appSecret:n},e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,o=Object(r["a"])({},this.form);this.$refs[e].validate((function(e){if(!e)return!1;Object(l["c"])(o,"post").then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))}))},getDocumentCheck:function(){var e=this;Object(l["b"])().then((function(t){e.deadline=t.data.deadline,e.is_check=t.data.is_check}))},inputChange:function(){""===this.form.appKey&&""===this.form.appSecret?this.rules={appKey:[{required:!1,message:"请输入AppKey",trigger:"blur"}],appSecret:[{required:!1,message:"请输入AppSecret",trigger:"blur"}]}:this.rules={appKey:[{required:!0,message:"请输入AppKey",trigger:"blur"}],appSecret:[{required:!0,message:"请输入AppSecret",trigger:"blur"}]}}}},R=J,Y=Object(u["a"])(R,F,D,!1,null,null,null),Z=Y.exports,G={components:{qq:f,trtc:v,weixinopen:x,alicloudpro:O,resumeanalysis:N,zhitooview:Z}},H=G,M=Object(u["a"])(H,a,n,!1,null,null,null);t["default"]=M.exports}}]);