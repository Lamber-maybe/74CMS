(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-ac0db73c"],{"45ce":function(t,e,s){"use strict";var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s(t.who,{ref:"child",tag:"components",attrs:{mask:t.mask},on:{setSubmitFun:t.setSubmitFun}})],1)},i=[],o=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div")},n=[],r={name:"CaptchaTencent",props:["mask"],data:function(){return{}},mounted:function(){if(void 0===window.TencentCaptcha){var t=document.createElement("script"),e=document.getElementsByTagName("head")[0];t.type="text/javascript",t.charset="UTF-8",t.src="https://ssl.captcha.qq.com/TCaptcha.js",e.appendChild(t)}},created:function(){},computed:{},methods:{show:function(t){var e=this;if(""==e.$store.state.config.captcha_tencent_appid)return this.$message.error("请正确配置腾讯防水墙appid"),!1;var s=new window.TencentCaptcha(e.$store.state.config.captcha_tencent_appid,(function(e){0===e.ret&&t(e)}));s.show()}}},c=r,l=s("2877"),d=Object(l["a"])(c,o,n,!1,null,"6b7a70b0",null),u=d.exports,h=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div")},m=[],p={name:"CaptchaVaptcha",props:["mask"],data:function(){return{}},mounted:function(){if(void 0===window.vaptcha){var t=document.createElement("script"),e=document.getElementsByTagName("head")[0];t.type="text/javascript",t.charset="UTF-8",t.src="https://v.vaptcha.com/v3.js",e.appendChild(t)}},created:function(){},computed:{},methods:{show:function(t){var e=this;if(""==e.$store.state.config.captcha_vaptcha_vid)return this.$message.error("请正确配置手势验证vid"),!1;window.vaptcha({vid:e.$store.state.config.captcha_vaptcha_vid,type:"invisible",scene:0,offline_server:""}).then((function(e){e.validate(),e.listen("pass",(function(){var s={code:e.getToken()};t(s)})),e.listen("close",(function(){}))}))}}},f=p,v=Object(l["a"])(f,h,m,!1,null,"7abd7660",null),b=v.exports,w=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("el-dialog",{attrs:{title:"输入图中验证码",visible:t.showDialog,modal:t.showMadal,width:"350px","close-on-press-escape":!1,"close-on-click-modal":!1,"destroy-on-close":!0,"before-close":t.handleClose,"append-to-body":""},on:{"update:visible":function(e){t.showDialog=e}}},[s("el-form",{attrs:{"label-width":"0px",inline:!0},nativeOn:{submit:function(t){t.preventDefault()}}},[s("div",{staticClass:"verification_code"},[s("div",{staticClass:"code_text"},[s("p",[t._v("验证码")])]),s("div",{staticClass:"code_photo"},[s("el-form-item",[s("img",{staticClass:"captcha_img",attrs:{src:t.src},on:{click:t.changeImg}})])],1)]),s("div",{staticClass:"clearfix"}),s("div",{staticClass:"input"},[s("el-form-item",{attrs:{label:" "}},[s("el-input",{ref:"ipt",staticClass:"input_code",attrs:{placeholder:"请输入图中验证码"},nativeOn:{keydown:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.handlerConfirm.apply(null,arguments)}},model:{value:t.code,callback:function(e){t.code=e},expression:"code"}})],1)],1),s("el-form-item",{attrs:{label:" "}},[s("el-button",{staticStyle:{width:"280px"},attrs:{type:"primary"},on:{click:t.handlerConfirm}},[t._v("确定")])],1)],1)],1)],1)},_=[],g=s("751a"),$=s("d722"),y={name:"CaptchaPicture",props:["mask"],data:function(){return{showDialog:!1,code:"",src:"",secret_str:"",callback:null,showMadal:!1}},mounted:function(){},created:function(){},computed:{},methods:{handlerConfirm:function(){if(""==this.code)return this.$message.error("请输入验证码"),!1;var t={code:this.code,secret_str:this.secret_str};this.callback(t),this.showDialog=!1,this.code=""},show:function(t){var e=this;this.showDialog=!0,this.$nextTick((function(){e.$refs.ipt.focus()})),this.callback=t,!0===this.mask&&(this.showMadal=!0),this.changeImg()},changeImg:function(){var t=this;g["a"].get($["a"].captcha_picture,{}).then((function(e){t.secret_str=e.data.secret_str,t.src=e.data.src})).catch((function(){}))},handleClose:function(t){this.$emit("setSubmitFun"),t()}}},C=y,k=(s("5556"),s("eaba"),Object(l["a"])(C,w,_,!1,null,"eb3cf8d0",null)),x=k.exports,E={name:"Captcha",props:["mask"],components:{ctencent:u,cvaptcha:b,cpicture:x},data:function(){return{who:""}},mounted:function(){},created:function(){this.who=""==this.$store.state.config.captcha_type?"picture":this.$store.state.config.captcha_type,this.who="c"+this.who},computed:{},methods:{show:function(t,e){1!=this.$store.state.config.captcha_open||void 0!==e&&!0!==e?t():this.$refs.child.show(t)},setSubmitFun:function(){this.$emit("setSubmitFun")}}},S=E,F=Object(l["a"])(S,a,i,!1,null,"082081b6",null);e["a"]=F.exports},"4f49":function(t,e,s){},5556:function(t,e,s){"use strict";s("4f49")},"635e":function(t,e,s){},"7e04":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"reg_box"},[t.showForm?s("div",{staticClass:"reg_group"},[s("div",{staticClass:"g_title"},[t._v(t._s("mobile"==t.type?"手机":"邮箱")+"找回密码")]),s("div",{staticClass:"sw_b"},[s("router-link",{staticClass:"swb",class:2==t.utype?"active":"",attrs:{to:"/forget/2"}},[t._v("求职者用户")]),s("router-link",{staticClass:"swb r",class:1==t.utype?"active":"",attrs:{to:"/forget/1"}},[t._v("企业招聘用户")]),s("div",{staticClass:"clear"})],1),"mobile"==t.type?s("div",{staticClass:"g_input"},[s("el-input",{attrs:{placeholder:"请输入手机号",clearable:""},model:{value:t.mobile,callback:function(e){t.mobile=e},expression:"mobile"}}),s("el-input",{attrs:{placeholder:"请输入验证码",clearable:""},model:{value:t.code,callback:function(e){t.code=e},expression:"code"}}),s("div",{staticClass:"for_position"},[s("el-button",{style:"color:"+t.$store.state.sendSmsBtnTextColor,attrs:{type:"text"},on:{click:t.sendSms}},[t._v(t._s(t.$store.state.sendSmsBtnText))])],1),s("el-input",{attrs:{placeholder:"新密码","show-password":""},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}}),s("el-input",{attrs:{placeholder:"确认密码","show-password":""},model:{value:t.repeatPassword,callback:function(e){t.repeatPassword=e},expression:"repeatPassword"}})],1):t._e(),"email"==t.type?s("div",{staticClass:"g_input"},[s("el-input",{attrs:{placeholder:"请输入邮箱",clearable:""},model:{value:t.email,callback:function(e){t.email=e},expression:"email"}}),s("el-input",{attrs:{placeholder:"请输入验证码",clearable:""},model:{value:t.code,callback:function(e){t.code=e},expression:"code"}}),s("div",{staticClass:"for_position"},[s("el-button",{style:"color:"+t.$store.state.sendEmailBtnTextColor,attrs:{type:"text"},on:{click:t.sendEmail}},[t._v(t._s(t.$store.state.sendEmailBtnText))])],1),s("el-input",{attrs:{placeholder:"新密码","show-password":""},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}}),s("el-input",{attrs:{placeholder:"确认密码","show-password":""},model:{value:t.repeatPassword,callback:function(e){t.repeatPassword=e},expression:"repeatPassword"}})],1):t._e(),s("el-button",{staticClass:"g_btn",attrs:{type:"primary"},on:{click:t.handleSubmit}},[t._v("提交")]),s("div",{staticClass:"g_sw_login"},[s("span",{on:{click:t.changeMethod}},[t._v(t._s("mobile"==t.type?"邮箱":"手机")+"找回密码")])]),s("div",{staticClass:"b_t1"},[t._v("上面的方式都不可用？")]),s("div",{staticClass:"b_t2"},[t._v("你还可以进行 "),s("router-link",{attrs:{to:"/appeal"}},[t._v("账号申诉")]),t._v(" 或 电话联系我们 ("),s("span",[t._v(t._s(t.$store.state.config.contact_tel))]),t._v(") ")],1)],1):t._e(),t.showForm?t._e():s("div",{staticClass:"find_result"},[s("div",{staticClass:"fr_1"},[t._v("重置密码成功")]),s("br"),s("div",{staticClass:"fr_2"},[t._v("下次登录请使用新密码登录")]),s("el-button",{staticClass:"f_btn",attrs:{type:"primary",round:""},on:{click:function(e){return t.$router.push("/login")}}},[t._v("登录")])],1),s("Captcha",{ref:"captcha"})],1)},i=[],o=(s("ac1f"),s("00b4"),s("751a")),n=s("d722"),r=s("45ce"),c={name:"FindPwd",components:{Captcha:r["a"]},data:function(){return{utype:1,showForm:!0,type:"mobile",mobile:"",email:"",code:"",password:"",repeatPassword:"",regularMobile:/^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,regularEmail:/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,sendSmsLimit:!1,sendEmailLimit:!1}},created:function(){this.utype=void 0===this.$route.params.utype?1:this.$route.params.utype,this.$store.commit("clearCountDownFun"),this.$store.commit("clearCountDownFunEmail")},methods:{changeMethod:function(){"mobile"==this.type?this.type="email":this.type="mobile"},sendSms:function(){var t=this;return!this.$store.state.sendSmsBtnDisabled&&(this.mobile?this.regularMobile.test(this.mobile)?!this.sendSmsLimit&&(this.sendSmsLimit=!0,void this.$refs.captcha.show((function(e){t.$store.dispatch("sendSmsFun",{url:n["a"].sendsms_forget,mobile:t.mobile,type:t.utype,captcha:e}).then((function(e){if(200!==e.code)return t.sendSmsLimit=!1,t.$message.error(t.$store.state.sendSmsMessage),!1;t.sendSmsLimit=!1,t.$message({type:"success",message:t.$store.state.sendSmsMessage})}))}))):(this.$message.error("手机号格式不正确"),!1):(this.$message.error("请输入手机号"),!1))},sendEmail:function(){var t=this;return!this.$store.state.sendEmailBtnDisabled&&(this.email?this.regularEmail.test(this.email)?!this.sendEmailLimit&&(this.sendEmailLimit=!0,void this.$store.dispatch("sendEmailFun",{url:n["a"].sendmail_forget,email:this.email,type:this.utype}).then((function(e){if(200!==e.code)return t.sendEmailLimit=!1,t.$message.error(t.$store.state.sendEmailMessage),!1;t.sendEmailLimit=!1,t.$message({type:"success",message:t.$store.state.sendEmailMessage})}))):(this.$message.error("手机号格式不正确"),!1):(this.$message.error("请输入邮箱"),!1))},handleSubmit:function(){var t=this;if("mobile"==this.type&&!this.mobile)return this.$message.error("请输入手机号"),!1;if("mobile"==this.type&&!this.regularMobile.test(this.mobile))return this.$message.error("手机号格式不正确"),!1;if("email"==this.type&&!this.email)return this.$message.error("请输入邮箱"),!1;if("email"==this.type&&!this.regularEmail.test(this.email))return this.$message.error("邮箱格式不正确"),!1;if(!this.code)return this.$message.error("请输入验证码"),!1;if(!this.password)return this.$message.error("请输入新密码"),!1;if(!this.repeatPassword)return this.$message.error("请再次确认密码"),!1;if(this.password!==this.repeatPassword)return this.$message.error("两次输入的密码不一致"),!1;var e={mobile:this.mobile,email:this.email,code:this.code,password:this.password,utype:this.utype},s="mobile"==this.type?n["a"].set_pwd_bymobile:n["a"].set_pwd_byemail;o["a"].post(s,e).then((function(e){if(200!==parseInt(e.code))return t.$message.error(e.message),!1;t.$store.commit("clearCountDownFun"),t.$store.commit("clearCountDownFunEmail"),t.showForm=!1})).catch((function(){}))}}},l=c,d=(s("a0a6"),s("2877")),u=Object(d["a"])(l,a,i,!1,null,"10e93910",null);e["default"]=u.exports},9521:function(t,e,s){},a0a6:function(t,e,s){"use strict";s("9521")},eaba:function(t,e,s){"use strict";s("635e")}}]);