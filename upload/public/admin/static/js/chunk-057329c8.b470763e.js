(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-057329c8"],{"484f":function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("添加管理员")]),n("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/sys/admin/list")}}},[t._v(" 返回 ")]),n("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),n("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"100px",rules:t.rules,"inline-message":!0}},[n("el-form-item",{attrs:{label:"角色",prop:"role_id"}},[n("el-select",{attrs:{placeholder:"请选择角色"},model:{value:t.form.role_id,callback:function(e){t.$set(t.form,"role_id",e)},expression:"form.role_id"}},t._l(t.rolelist,(function(t,e){return n("el-option",{key:e,attrs:{label:t.name,value:t.id}})})),1)],1),n("el-form-item",{attrs:{label:"登录名",prop:"username"}},[n("el-input",{staticClass:"middle",model:{value:t.form.username,callback:function(e){t.$set(t.form,"username",e)},expression:"form.username"}})],1),n("el-form-item",{attrs:{label:"密码",prop:"password"}},[n("el-input",{staticClass:"middle",attrs:{"show-password":""},model:{value:t.form.password,callback:function(e){t.$set(t.form,"password",e)},expression:"form.password"}})],1),n("el-form-item",{attrs:{label:""}},[n("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),n("el-button",{on:{click:function(e){return t.goto("/sys/admin/list")}}},[t._v("返回")])],1)],1)],1)],1)},a=[],o=n("5530"),i=n("50fc3"),u={data:function(){return{rolelist:[],form:{username:"",password:"",role_id:""},rules:{role_id:[{required:!0,message:"请选择角色",trigger:"change"}],username:[{required:!0,message:"请输入登录名",trigger:"blur"},{max:15,message:"长度在 1 到 15 个字符",trigger:"blur"}],password:[{required:!0,message:"请输入密码",trigger:"blur"},{max:15,message:"长度在 1 到 15 个字符",trigger:"blur"}]}}},computed:{},mounted:function(){},created:function(){this.fetchRoleData()},methods:{fetchRoleData:function(){var t=this,e={};Object(i["m"])(e).then((function(e){t.rolelist=e.data})).catch((function(){}))},onSubmit:function(t){var e=this,n=this,r=Object(o["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;Object(i["i"])(r).then((function(t){return e.$message.success(t.message),setTimeout((function(){n.$router.push("/sys/admin/list")}),1500),!0})).catch((function(){}))}))},goto:function(t){this.$router.push(t)}}},s=u,c=n("2877"),l=Object(c["a"])(s,r,a,!1,null,null,null);e["default"]=l.exports},"50fc3":function(t,e,n){"use strict";n.d(e,"l",(function(){return o})),n.d(e,"i",(function(){return i})),n.d(e,"k",(function(){return u})),n.d(e,"j",(function(){return s})),n.d(e,"m",(function(){return c})),n.d(e,"g",(function(){return l})),n.d(e,"a",(function(){return d})),n.d(e,"b",(function(){return m})),n.d(e,"c",(function(){return f})),n.d(e,"e",(function(){return p})),n.d(e,"o",(function(){return b})),n.d(e,"f",(function(){return h})),n.d(e,"d",(function(){return g})),n.d(e,"h",(function(){return j})),n.d(e,"n",(function(){return O}));var r=n("b775"),a=n("d722");function o(t){return Object(r["a"])({url:a["a"].adminList,method:"get",params:t})}function i(t){return Object(r["a"])({url:a["a"].adminAdd,method:"post",data:t})}function u(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(r["a"])({url:a["a"].adminEdit,method:e,data:t}):Object(r["a"])({url:a["a"].adminEdit,method:e,params:t})}function s(t){return Object(r["a"])({url:a["a"].adminDelete,method:"post",data:t})}function c(t){return Object(r["a"])({url:a["a"].adminRoleOptions,method:"get",params:t})}function l(t){return Object(r["a"])({url:a["a"].adminLogList,method:"get",params:t})}function d(t){return Object(r["a"])({url:a["a"].adminAllList,method:"get",params:t})}function m(t){return Object(r["a"])({url:a["a"].adminBindQrcode,method:"get",params:t})}function f(t){return Object(r["a"])({url:a["a"].adminBindQrcodeCancel,method:"post",data:t})}function p(t){return Object(r["a"])({url:a["a"].adminIsBindWechat,method:"post",data:t})}function b(t){return Object(r["a"])({url:a["a"].getAdminCrmData,method:"post",data:t})}function h(t){return Object(r["a"])({url:a["a"].adminLock,method:"post",data:t})}function g(t){return Object(r["a"])({url:a["a"].adminDeblocking,method:"post",data:t})}function j(t){return Object(r["a"])({url:a["a"].adminLogTypeAll,method:"post",data:t})}function O(t){return Object(r["a"])({url:a["a"].delAdminLog,method:"post",data:t})}}}]);