(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d22c131"],{f24c:function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{attrs:{slot:"header"},slot:"header"},[t._v("外呼配置")]),a("div",[a("div",{staticClass:"tip"},[a("p",[t._v(" 云外呼服务由第三方(招聘行业专用)线路商独立提供，在使用前请先联系客服开通外呼服务(座席+基础呼叫包)。您需： ")]),a("p",[t._v(" 1. 在开通外呼服务后完成appkey及appSecret 配置； ")]),a("p",[t._v(" 2. 保证您的座席和基础呼叫包在有效期内； ")]),a("p",[t._v(" 3. 基础呼叫包使用完后请联系客服单独购买套外呼叫包。 ")])]),a("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"160px"}},[a("el-form-item",{attrs:{label:"AppKey"}},[a("el-input",{staticClass:"small",staticStyle:{width:"50%"},model:{value:t.form.app_id,callback:function(e){t.$set(t.form,"app_id",e)},expression:"form.app_id"}})],1),a("el-form-item",{attrs:{label:"AppSecret"}},[a("el-input",{staticClass:"small",staticStyle:{width:"50%"},model:{value:t.form.app_secret,callback:function(e){t.$set(t.form,"app_secret",e)},expression:"form.app_secret"}})],1),a("el-form-item",[a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit()}}},[t._v("保存")])],1)],1)],1)])],1)},i=[],c=a("f09a"),p={data:function(){return{infoLoading:!0,form:{app_id:"",app_secret:""},submit:!1}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var t=this;Object(c["g"])().then((function(e){t.form.app_id=e.data.app_id,t.form.app_secret=e.data.app_secret})).catch((function(){}))},onSubmit:function(){var t=this;if(this.submit)return!1;this.submit=!0,Object(c["h"])({app_id:this.form.app_id,app_secret:this.form.app_secret}).then((function(e){t.$message.success(e.message),t.submit=!1})).catch((function(){t.submit=!1}))}}},r=p,n=a("2877"),o=Object(n["a"])(r,s,i,!1,null,null,null);e["default"]=o.exports}}]);