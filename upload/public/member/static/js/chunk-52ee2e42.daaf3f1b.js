(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-52ee2e42"],{"1e83":function(t,e,s){},"20a3":function(t,e,s){"use strict";s("932a")},"45ce":function(t,e,s){"use strict";var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s(t.who,{ref:"child",tag:"components",attrs:{mask:t.mask}})],1)},i=[],o=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div")},r=[],c={name:"CaptchaTencent",props:["mask"],data:function(){return{}},mounted:function(){if(void 0===window.TencentCaptcha){var t=document.createElement("script"),e=document.getElementsByTagName("head")[0];t.type="text/javascript",t.charset="UTF-8",t.src="https://ssl.captcha.qq.com/TCaptcha.js",e.appendChild(t)}},created:function(){},computed:{},methods:{show:function(t){var e=this;if(""==e.$store.state.config.captcha_tencent_appid)return this.$message.error("请正确配置腾讯防水墙appid"),!1;var s=new window.TencentCaptcha(e.$store.state.config.captcha_tencent_appid,(function(e){0===e.ret&&t(e)}));s.show()}}},n=c,l=s("5d22"),d=Object(l["a"])(n,o,r,!1,null,"6b7a70b0",null),h=d.exports,p=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div")},u=[],m={name:"CaptchaVaptcha",props:["mask"],data:function(){return{}},mounted:function(){if(void 0===window.vaptcha){var t=document.createElement("script"),e=document.getElementsByTagName("head")[0];t.type="text/javascript",t.charset="UTF-8",t.src="https://v.vaptcha.com/v3.js",e.appendChild(t)}},created:function(){},computed:{},methods:{show:function(t){var e=this;if(""==e.$store.state.config.captcha_vaptcha_vid)return this.$message.error("请正确配置手势验证vid"),!1;window.vaptcha({vid:e.$store.state.config.captcha_vaptcha_vid,type:"invisible",scene:0,offline_server:""}).then((function(e){e.validate(),e.listen("pass",(function(){var s={code:e.getToken()};t(s)})),e.listen("close",(function(){}))}))}}},f=m,v=Object(l["a"])(f,p,u,!1,null,"7abd7660",null),w=v.exports,b=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("el-dialog",{attrs:{title:"验证码",visible:t.showDialog,modal:t.showMadal,width:"350px","close-on-press-escape":!1,"close-on-click-modal":!1,"destroy-on-close":!0},on:{"update:visible":function(e){t.showDialog=e}}},[s("el-form",{attrs:{"label-width":"0px",inline:!0}},[s("el-form-item",{attrs:{label:" "}},[s("el-input",{staticStyle:{width:"120px"},model:{value:t.code,callback:function(e){t.code=e},expression:"code"}})],1),s("el-form-item",{attrs:{label:" "}},[s("img",{staticClass:"captcha_img",attrs:{src:t.src},on:{click:t.changeImg}})]),s("div",{staticClass:"clearfix"}),s("el-form-item",{attrs:{label:" "}},[s("el-button",{staticStyle:{width:"280px"},attrs:{type:"primary"},on:{click:t.handlerConfirm}},[t._v("确定")])],1)],1)],1)],1)},g=[],_=s("751a"),$=s("d722"),y={name:"CaptchaPicture",props:["mask"],data:function(){return{showDialog:!1,code:"",src:"",secret_str:"",callback:null,showMadal:!1}},mounted:function(){},created:function(){},computed:{},methods:{handlerConfirm:function(){if(""==this.code)return this.$message.error("请输入验证码"),!1;var t={code:this.code,secret_str:this.secret_str};this.callback(t),this.showDialog=!1},show:function(t){this.showDialog=!0,this.callback=t,!0===this.mask&&(this.showMadal=!0),this.changeImg()},changeImg:function(){var t=this;_["a"].get($["a"].captcha_picture,{}).then((function(e){t.secret_str=e.data.secret_str,t.src=e.data.src})).catch((function(){}))}}},C=y,k=(s("20a3"),Object(l["a"])(C,b,g,!1,null,"889dadc6",null)),x=k.exports,E={name:"Captcha",props:["mask"],components:{ctencent:h,cvaptcha:w,cpicture:x},data:function(){return{who:""}},mounted:function(){},created:function(){this.who=""==this.$store.state.config.captcha_type?"picture":this.$store.state.config.captcha_type,this.who="c"+this.who},computed:{},methods:{show:function(t,e){1!=this.$store.state.config.captcha_open||void 0!==e&&!0!==e?t():this.$refs.child.show(t)}}},F=E,M=Object(l["a"])(F,a,i,!1,null,"b60cad64",null);e["a"]=M.exports},"7e04":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"reg_box"},[t.showForm?s("div",{staticClass:"reg_group"},[s("div",{staticClass:"g_title"},[t._v(t._s("mobile"==t.type?"手机":"邮箱")+"找回密码")]),s("div",{staticClass:"sw_b"},[s("router-link",{staticClass:"swb",class:2==t.utype?"active":"",attrs:{to:"/forget/2"}},[t._v("求职者用户")]),s("router-link",{staticClass:"swb r",class:1==t.utype?"active":"",attrs:{to:"/forget/1"}},[t._v("企业招聘用户")]),s("div",{staticClass:"clear"})],1),"mobile"==t.type?s("div",{staticClass:"g_input"},[s("el-input",{attrs:{placeholder:"请输入手机号",clearable:""},model:{value:t.mobile,callback:function(e){t.mobile=e},expression:"mobile"}}),s("el-input",{attrs:{placeholder:"请输入验证码",clearable:""},model:{value:t.code,callback:function(e){t.code=e},expression:"code"}}),s("div",{staticClass:"for_position"},[s("el-button",{attrs:{type:"text"},on:{click:t.sendSms}},[t._v(t._s(t.$store.state.sendSmsBtnText))])],1),s("el-input",{attrs:{placeholder:"新密码","show-password":""},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}}),s("el-input",{attrs:{placeholder:"确认密码","show-password":""},model:{value:t.repeatPassword,callback:function(e){t.repeatPassword=e},expression:"repeatPassword"}})],1):t._e(),"email"==t.type?s("div",{staticClass:"g_input"},[s("el-input",{attrs:{placeholder:"请输入邮箱",clearable:""},model:{value:t.email,callback:function(e){t.email=e},expression:"email"}}),s("el-input",{attrs:{placeholder:"请输入验证码",clearable:""},model:{value:t.code,callback:function(e){t.code=e},expression:"code"}}),s("div",{staticClass:"for_position"},[s("el-button",{attrs:{type:"text"},on:{click:t.sendEmail}},[t._v(t._s(t.$store.state.sendEmailBtnText))])],1),s("el-input",{attrs:{placeholder:"新密码","show-password":""},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}}),s("el-input",{attrs:{placeholder:"确认密码","show-password":""},model:{value:t.repeatPassword,callback:function(e){t.repeatPassword=e},expression:"repeatPassword"}})],1):t._e(),s("el-button",{staticClass:"g_btn",attrs:{type:"primary"},on:{click:t.handleSubmit}},[t._v("提交")]),s("div",{staticClass:"g_sw_login"},[s("span",{on:{click:t.changeMethod}},[t._v("邮箱找回密码")])]),s("div",{staticClass:"b_t1"},[t._v("上面的方式都不可用？")]),s("div",{staticClass:"b_t2"},[t._v("你还可以进行 "),s("router-link",{attrs:{to:"/appeal"}},[t._v("账号申诉")]),t._v(" 或 电话联系我们 ("),s("span",[t._v(t._s(t.$store.state.config.contact_tel))]),t._v(") ")],1)],1):t._e(),t.showForm?t._e():s("div",{staticClass:"find_result"},[s("div",{staticClass:"fr_1"},[t._v("重置密码成功")]),s("br"),s("div",{staticClass:"fr_2"},[t._v("下次登录请使用新密码登录")]),s("el-button",{staticClass:"f_btn",attrs:{type:"primary",round:""},on:{click:function(e){return t.$router.push("/login")}}},[t._v("登录")])],1),s("Captcha",{ref:"captcha"})],1)},i=[],o=s("751a"),r=s("d722"),c=s("45ce"),n={name:"FindPwd",components:{Captcha:c["a"]},data:function(){return{utype:1,showForm:!0,type:"mobile",mobile:"",email:"",code:"",password:"",repeatPassword:"",regularMobile:/^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,regularEmail:/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/}},created:function(){this.utype=void 0===this.$route.params.utype?1:this.$route.params.utype,this.$store.commit("clearCountDownFun")},methods:{changeMethod:function(){"mobile"==this.type?this.type="email":this.type="mobile"},sendSms:function(){var t=this;return!this.$store.state.sendSmsBtnDisabled&&(this.mobile?this.regularMobile.test(this.mobile)?void this.$refs.captcha.show((function(e){t.$store.dispatch("sendSmsFun",{url:r["a"].sendsms_forget,mobile:t.mobile,type:t.utype,captcha:e}).then((function(e){if(200!==e.code)return t.$message.error(t.$store.state.sendSmsMessage),!1;t.$message({type:"success",message:t.$store.state.sendSmsMessage})}))})):(this.$message.error("手机号格式不正确"),!1):(this.$message.error("请输入手机号"),!1))},sendEmail:function(){var t=this;return!this.$store.state.sendEmailBtnDisabled&&(this.email?this.regularEmail.test(this.email)?void this.$store.dispatch("sendEmailFun",{url:r["a"].sendmail_forget,email:this.email,type:this.utype}).then((function(e){if(200!==e.code)return t.$message.error(t.$store.state.sendEmailMessage),!1;t.$message({type:"success",message:t.$store.state.sendEmailMessage})})):(this.$message.error("手机号格式不正确"),!1):(this.$message.error("请输入邮箱"),!1))},handleSubmit:function(){var t=this;if("mobile"==this.type&&!this.mobile)return this.$message.error("请输入手机号"),!1;if("mobile"==this.type&&!this.regularMobile.test(this.mobile))return this.$message.error("手机号格式不正确"),!1;if("email"==this.type&&!this.email)return this.$message.error("请输入邮箱"),!1;if("email"==this.type&&!this.regularEmail.test(this.email))return this.$message.error("邮箱格式不正确"),!1;if(!this.code)return this.$message.error("请输入验证码"),!1;if(!this.password)return this.$message.error("请输入新密码"),!1;if(!this.repeatPassword)return this.$message.error("请再次确认密码"),!1;if(this.password!==this.repeatPassword)return this.$message.error("两次输入的密码不一致"),!1;var e={mobile:this.mobile,email:this.email,code:this.code,password:this.password,utype:this.utype},s="mobile"==this.type?r["a"].set_pwd_bymobile:r["a"].set_pwd_byemail;o["a"].post(s,e).then((function(e){if(200!==parseInt(e.code))return t.$message.error(e.message),!1;t.$store.commit("clearCountDownFun"),t.$store.commit("clearCountDownFunEmail"),t.showForm=!1})).catch((function(){}))}}},l=n,d=(s("eec4"),s("5d22")),h=Object(d["a"])(l,a,i,!1,null,"01388b5c",null);e["default"]=h.exports},"932a":function(t,e,s){},eec4:function(t,e,s){"use strict";s("1e83")}}]);