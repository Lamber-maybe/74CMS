(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-26c1b2f8"],{3606:function(t,e,s){"use strict";s("8d6c")},"45ce":function(t,e,s){"use strict";var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s(t.who,{ref:"child",tag:"components",attrs:{mask:t.mask},on:{setSubmitFun:t.setSubmitFun}})],1)},o=[],n=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div")},a=[],r={name:"CaptchaTencent",props:["mask"],data:function(){return{}},mounted:function(){if(void 0===window.TencentCaptcha){var t=document.createElement("script"),e=document.getElementsByTagName("head")[0];t.type="text/javascript",t.charset="UTF-8",t.src="https://ssl.captcha.qq.com/TCaptcha.js",e.appendChild(t)}},created:function(){},computed:{},methods:{show:function(t){var e=this;if(""==e.$store.state.config.captcha_tencent_appid)return this.$message.error("请正确配置腾讯防水墙appid"),!1;var s=new window.TencentCaptcha(e.$store.state.config.captcha_tencent_appid,(function(e){0===e.ret&&t(e)}));s.show()}}},c=r,u=s("2877"),l=Object(u["a"])(c,n,a,!1,null,"6b7a70b0",null),d=l.exports,p=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div")},h=[],m={name:"CaptchaVaptcha",props:["mask"],data:function(){return{}},mounted:function(){if(void 0===window.vaptcha){var t=document.createElement("script"),e=document.getElementsByTagName("head")[0];t.type="text/javascript",t.charset="UTF-8",t.src="https://v.vaptcha.com/v3.js",e.appendChild(t)}},created:function(){},computed:{},methods:{show:function(t){var e=this;if(""==e.$store.state.config.captcha_vaptcha_vid)return this.$message.error("请正确配置手势验证vid"),!1;window.vaptcha({vid:e.$store.state.config.captcha_vaptcha_vid,type:"invisible",scene:0,offline_server:""}).then((function(e){e.validate(),e.listen("pass",(function(){var s={code:e.getToken()};t(s)})),e.listen("close",(function(){}))}))}}},f=m,_=Object(u["a"])(f,p,h,!1,null,"7abd7660",null),b=_.exports,y=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("el-dialog",{attrs:{title:"输入图中验证码",visible:t.showDialog,modal:t.showMadal,width:"350px","close-on-press-escape":!1,"close-on-click-modal":!1,"destroy-on-close":!0,"before-close":t.handleClose,"append-to-body":""},on:{"update:visible":function(e){t.showDialog=e}}},[s("el-form",{attrs:{"label-width":"0px",inline:!0},nativeOn:{submit:function(t){t.preventDefault()}}},[s("div",{staticClass:"verification_code"},[s("div",{staticClass:"code_text"},[s("p",[t._v("验证码")])]),s("div",{staticClass:"code_photo"},[s("el-form-item",[s("img",{staticClass:"captcha_img",attrs:{src:t.src},on:{click:t.changeImg}})])],1)]),s("div",{staticClass:"clearfix"}),s("div",{staticClass:"input"},[s("el-form-item",{attrs:{label:" "}},[s("el-input",{ref:"ipt",staticClass:"input_code",attrs:{placeholder:"请输入图中验证码"},nativeOn:{keydown:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.handlerConfirm.apply(null,arguments)}},model:{value:t.code,callback:function(e){t.code=e},expression:"code"}})],1)],1),s("el-form-item",{attrs:{label:" "}},[s("el-button",{staticStyle:{width:"280px"},attrs:{type:"primary"},on:{click:t.handlerConfirm}},[t._v("确定")])],1)],1)],1)],1)},g=[],v=s("751a"),w=s("d722"),C={name:"CaptchaPicture",props:["mask"],data:function(){return{showDialog:!1,code:"",src:"",secret_str:"",callback:null,showMadal:!1}},mounted:function(){},created:function(){},computed:{},methods:{handlerConfirm:function(){if(""==this.code)return this.$message.error("请输入验证码"),!1;var t={code:this.code,secret_str:this.secret_str};this.callback(t),this.showDialog=!1,this.code=""},show:function(t){var e=this;this.showDialog=!0,this.$nextTick((function(){e.$refs.ipt.focus()})),this.callback=t,!0===this.mask&&(this.showMadal=!0),this.changeImg()},changeImg:function(){var t=this;v["a"].get(w["a"].captcha_picture,{}).then((function(e){t.secret_str=e.data.secret_str,t.src=e.data.src})).catch((function(){}))},handleClose:function(t){this.$emit("setSubmitFun"),t()}}},$=C,k=(s("5556"),s("eaba"),Object(u["a"])($,y,g,!1,null,"eb3cf8d0",null)),x=k.exports,O={name:"Captcha",props:["mask"],components:{ctencent:d,cvaptcha:b,cpicture:x},data:function(){return{who:""}},mounted:function(){},created:function(){this.who=""==this.$store.state.config.captcha_type?"picture":this.$store.state.config.captcha_type,this.who="c"+this.who},computed:{},methods:{show:function(t,e){1!=this.$store.state.config.captcha_open||void 0!==e&&!0!==e?t():this.$refs.child.show(t)},setSubmitFun:function(){this.$emit("setSubmitFun")}}},S=O,j=Object(u["a"])(S,i,o,!1,null,"082081b6",null);e["a"]=j.exports},"4f49":function(t,e,s){},5530:function(t,e,s){"use strict";s.d(e,"a",(function(){return n}));s("b64b"),s("a4d3"),s("4de4"),s("d3b7"),s("e439"),s("159b"),s("dbb4");function i(t,e,s){return e in t?Object.defineProperty(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}function o(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(t);e&&(i=i.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,i)}return s}function n(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?o(Object(s),!0).forEach((function(e){i(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):o(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}},5556:function(t,e,s){"use strict";s("4f49")},"635e":function(t,e,s){},"71fb":function(t,e,s){"use strict";s.r(e);var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"login_box",class:1==t.utype?"":"p"},[s("div",{staticClass:"b_group"},[1==t.$store.state.config.wechat_login_open?s("div",{class:t.scan?"b_sw aco":"b_sw",on:{click:t.showScan}}):t._e(),s("div",{staticClass:"login_tips",class:1==t.utype?"tips_color_2":"tips_color_1",on:{click:function(e){return e.stopPropagation(),t.$router.push(t.layout.utype_other_login_route)}}},[s("div",{staticClass:"text_1"},[t._v(t._s(1==t.utype?"我是求职者":"我是招聘方"))]),s("div",{staticClass:"text_2",on:{click:function(e){return e.stopPropagation(),t.$router.push(t.layout.utype_other_login_route)}}},[t._v(t._s(1==t.utype?"个人在此登录":"企业在此登录"))])]),s("div",{directives:[{name:"show",rawName:"v-show",value:!t.scan,expression:"!scan"}]},[s("div",{staticClass:"b_title"},[t._v(t._s(t.layout.utype_current_text)+"登录")]),t.loginByCode?s("div",{staticClass:"b_input"},[s("el-input",{attrs:{placeholder:"请输入手机号",clearable:""},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.handleSubmit.apply(null,arguments)}},model:{value:t.formCode.mobile,callback:function(e){t.$set(t.formCode,"mobile",e)},expression:"formCode.mobile"}}),s("el-input",{attrs:{placeholder:"请输入验证码",clearable:""},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.keyupSubmit.apply(null,arguments)}},model:{value:t.formCode.code,callback:function(e){t.$set(t.formCode,"code",e)},expression:"formCode.code"}}),s("div",{staticClass:"for_position"},[s("el-button",{style:"color:"+t.$store.state.sendSmsBtnTextColor,attrs:{type:"text",disabled:t.is_submit},on:{click:t.sendSms}},[t._v(t._s(t.$store.state.sendSmsBtnText))])],1)],1):s("div",{staticClass:"b_input"},[s("el-input",{attrs:{placeholder:"请输入手机号/用户名/邮箱",clearable:""},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.handleSubmit.apply(null,arguments)}},model:{value:t.formPwd.username,callback:function(e){t.$set(t.formPwd,"username",e)},expression:"formPwd.username"}}),s("el-input",{attrs:{placeholder:"请输入密码","show-password":""},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.handleSubmit.apply(null,arguments)}},model:{value:t.formPwd.password,callback:function(e){t.$set(t.formPwd,"password",e)},expression:"formPwd.password"}})],1),s("div",{staticClass:"b_handle"},[s("div",{staticClass:"h_left",on:{click:function(e){t.loginByCode=!t.loginByCode}}},[t._v(t._s(t.loginByCode?"账号密码登录":"手机验证码登录"))]),s("div",{staticClass:"h_right"},[s("router-link",{attrs:{to:"/forget/"+t.utype}},[t._v("忘记密码?")])],1),s("div",{staticClass:"clear"})]),s("el-button",{staticClass:"b_btn",attrs:{type:"primary"},on:{click:t.handleSubmit}},[t._v("立即登录")]),s("div",{staticClass:"b_reg"},[t._v("没有账号？"),s("router-link",{attrs:{to:"/reg"+(1==t.utype?"/company":"")}},[t._v("立即注册")])],1),1==t.$store.state.config.account_qqlogin_open||1==t.$store.state.config.wechat_login_open?s("div",{staticClass:"b_coop"},[s("div",{staticClass:"p_title"},[t._v("合作账号登录")]),s("div",{staticClass:"p_group"},[1==t.$store.state.config.account_qqlogin_open?s("div",{staticClass:"p_item"},[s("a",{staticClass:"p_ico qq",attrs:{href:"javascript:;"},on:{click:t.qqLogin}})]):t._e(),1==t.$store.state.config.wechat_login_open?s("div",{staticClass:"p_item"},[s("a",{staticClass:"p_ico wx",attrs:{href:"javascript:;"},on:{click:t.showScan}})]):t._e()])]):t._e()],1),s("div",{directives:[{name:"show",rawName:"v-show",value:t.scan,expression:"scan"}],staticClass:"scan_login",attrs:{id:"wxlogin"}},[s("div",{staticClass:"l_t1"},[t._v("手机扫码，安全登录")]),t._m(0),s("div",{staticClass:"l_t2"},[t._v("打开微信扫一扫，快速登录")])])]),s("div",{staticClass:"clear"}),s("Captcha",{ref:"captcha",attrs:{mask:!0},on:{setSubmitFun:t.setSubmitFun}})],1)},o=[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"scan_box"},[s("div",{staticClass:"img"})])}],n=s("5530"),a=(s("ac1f"),s("00b4"),s("ed08")),r=s("e16f"),c=s("751a"),u=s("d722"),l=s("45ce"),d={name:"Login",components:{Captcha:l["a"]},data:function(){return{regularMobile:/^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,loginByCode:!1,scan:!1,layout:{utype_current_text:"企业招聘会员",utype_other_text:"求职者会员",utype_other_login_route:"",utype_current_reg_route:""},formPwd:{username:"",password:""},formCode:{mobile:"",code:""},utype:2,redirect:"",sendSmsLimit:!1,is_submit:!1}},watch:{utype:{immediate:!0,handler:function(){this.initLayout()}}},created:function(){var t=this.$route.query.code;if(void 0!==t)return this.$router.push("/oauth/weixin/"+t),!1;if("company"===this.$route.params.utype_alias?this.utype=1:(this.$route.params.utype_alias,this.utype=2),!0===Object(a["e"])())return location.href=this.$store.state.config.mobile_domain+"member/login",!1;if(void 0===window.WxLogin){var e=document.createElement("script"),s=document.getElementsByTagName("head")[0];e.type="text/javascript",e.charset="UTF-8",e.src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js",s.appendChild(e)}this.$store.commit("clearCountDownFun")},methods:{showScan:function(){var t=this;this.scan=!this.scan,!0===this.scan&&new window.WxLogin({id:"wxlogin",appid:t.$store.state.config.wechat_open_appid,scope:"snsapi_login",redirect_uri:location.href,state:"",style:"",href:"data:text/css;base64,LmltcG93ZXJCb3ggLnFyY29kZSB7DQogIHdpZHRoOiAyMDBweDsNCn0NCi5pbXBvd2VyQm94IC5pbmZvIHsNCiAgd2lkdGg6IDIwMHB4Ow0KfQ0KLmltcG93ZXJCb3ggLnN0YXR1cyB7DQogIHRleHQtYWxpZ246IGNlbnRlcjsNCn0="})},initLayout:function(){this.redirect=void 0!==this.$route.query.redirect?this.$route.query.redirect:"",this.layout.utype_current_text=1===this.utype?"企业招聘会员":"求职者会员",this.layout.utype_other_text=1===this.utype?"求职者会员":"企业招聘会员",this.layout.utype_other_login_route=1===this.utype?"/login":"/login/company",this.layout.utype_current_reg_route=1===this.utype?"/reg/company":"/reg",this.redirect&&(this.layout.utype_other_login_route=this.layout.utype_other_login_route+"?redirect="+this.redirect,this.layout.utype_current_reg_route=this.layout.utype_current_reg_route+"?redirect="+this.redirect)},redirectTo:function(){""!==this.redirect?0==this.redirect.indexOf("http")?location.href=this.redirect:this.$router.push(this.redirect):1===this.utype?this.$router.push("/company"):this.$router.push("/personal")},doSubmitPwd:function(){var t=this;if(!this.formPwd.username)return this.$message.error("请输入手机号/用户名/邮箱"),!1;if(!this.formPwd.password)return this.$message.error("请输入密码"),!1;var e=Object(n["a"])({},this.formPwd);e.utype=this.utype,c["a"].get(u["a"].login_pwd,e).then((function(s){var i=!1;1==s.data&&(i=!0),t.$refs.captcha.show((function(s){void 0!==s&&(e.captcha=s),c["a"].post(u["a"].login_pwd,e).then((function(e){200===parseInt(e.code)?(t.$store.commit("clearCountDownFun"),t.$store.commit("setLoginState",{whether:!0,utype:e.data.utype,token:e.data.token,mobile:e.data.mobile,userIminfo:e.data.user_iminfo}),200!=e.data.next_code?Object(r["a"])({code:e.data.next_code,message:""}):t.redirectTo()):t.$message.error(e.message)})).catch((function(){}))}),i)})).catch((function(){}))},doSubmitCode:function(){var t=this;if(!this.formCode.mobile)return this.$message.error("请输入手机号"),!1;if(!this.regularMobile.test(this.formCode.mobile))return this.$message.error("手机号格式不正确"),!1;var e=Object(n["a"])({},this.formCode);e.utype=this.utype,c["a"].get(u["a"].login_code,e).then((function(s){var i=!1;1==s.data&&(i=!0),t.$refs.captcha.show((function(s){void 0!==s&&(e.captcha=s),c["a"].post(u["a"].login_code,e).then((function(e){200===parseInt(e.code)?(t.$store.commit("clearCountDownFun"),t.$store.commit("setLoginState",{whether:!0,utype:e.data.utype,token:e.data.token,mobile:e.data.mobile,userIminfo:e.data.user_iminfo}),200!=e.data.next_code?Object(r["a"])({code:e.data.next_code,message:""}):t.redirectTo()):t.$message.error(e.message)})).catch((function(){}))}),i)})).catch((function(){}))},handleSubmit:function(){this.loginByCode?this.doSubmitCode():this.doSubmitPwd()},keyupSubmit:function(){""!=this.formCode.code&&(this.loginByCode?this.doSubmitCode():this.doSubmitPwd())},sendSms:function(){var t=this;return!this.$store.state.sendSmsBtnDisabled&&(this.formCode.mobile?this.regularMobile.test(this.formCode.mobile)?(this.is_submit=!0,this.sendSmsLimit=!0,void this.$refs.captcha.show((function(e){t.$store.dispatch("sendSmsFun",{url:u["a"].get_login_code,mobile:t.formCode.mobile,type:t.utype,captcha:e}).then((function(e){200===e.code?(t.is_submit=!1,t.sendSmsLimit=!1,t.$message({type:"success",message:t.$store.state.sendSmsMessage})):(t.is_submit=!1,t.sendSmsLimit=!1,t.$message.error(t.$store.state.sendSmsMessage))}))}))):(this.$message.error("手机号格式不正确"),!1):(this.$message.error("请输入手机号"),!1))},qqLogin:function(){this.$router.push("/oauth/qq/login")},setSubmitFun:function(){this.is_submit=!1}}},p=d,h=(s("3606"),s("2877")),m=Object(h["a"])(p,i,o,!1,null,"9720150c",null);e["default"]=m.exports},"8d6c":function(t,e,s){},dbb4:function(t,e,s){var i=s("23e7"),o=s("83ab"),n=s("56ef"),a=s("fc6a"),r=s("06cf"),c=s("8418");i({target:"Object",stat:!0,sham:!o},{getOwnPropertyDescriptors:function(t){var e,s,i=a(t),o=r.f,u=n(i),l={},d=0;while(u.length>d)s=o(i,e=u[d++]),void 0!==s&&c(l,e,s);return l}})},e439:function(t,e,s){var i=s("23e7"),o=s("d039"),n=s("fc6a"),a=s("06cf").f,r=s("83ab"),c=o((function(){a(1)})),u=!r||c;i({target:"Object",stat:!0,forced:u,sham:!r},{getOwnPropertyDescriptor:function(t,e){return a(n(t),e)}})},eaba:function(t,e,s){"use strict";s("635e")}}]);