(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-45ec73d2"],{"3bb0":function(t,n,e){t.exports=e.p+"static/img/oauth_loading.7a48dd1b.gif"},4146:function(t,n,e){"use strict";e("bd49")},"51c1":function(t,n,e){"use strict";e.r(n);var i=function(){var t=this,n=t.$createElement;t._self._c;return t._m(0)},o=[function(){var t=this,n=t.$createElement,i=t._self._c||n;return i("div",{staticClass:"content"},[i("div",{staticClass:"loading-ico"},[i("img",{attrs:{src:e("3bb0")}})]),i("div",{staticClass:"loading-txt"},[t._v("正在获取第三方授权...")])])}],a=e("751a"),s=e("d722"),c={name:"OauthWeixin",data:function(){return{code:""}},created:function(){this.init()},methods:{doLogin:function(t){var n=this;a["a"].post(s["a"].oauth_weixin_login,t).then((function(t){200===parseInt(t.code)?(n.$store.commit("clearCountDownFun"),n.$store.commit("setLoginState",{whether:!0,utype:t.data.utype,token:t.data.token,mobile:t.data.mobile,userIminfo:t.data.user_iminfo}),1===t.data.utype?n.$router.push("/company"):n.$router.push("/personal")):n.$message.error(t.message)})).catch((function(){}))},getUserinfo:function(t){var n=this,e={code:t};a["a"].post(s["a"].oauth_weixin_getuserinfo,e).then((function(t){n.doLogin(t.data)})).catch((function(){}))},init:function(){var t=this.$route.params.code;if(void 0!==t)return this.getUserinfo(t),!1}}},r=c,u=(e("4146"),e("2877")),d=Object(u["a"])(r,i,o,!1,null,"4c23d798",null);n["default"]=d.exports},bd49:function(t,n,e){}}]);