(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0bc84c6e"],{"401f":function(t,e,r){"use strict";r("84a4")},"84a4":function(t,e,r){},"8e35":function(t,e,r){"use strict";r.d(e,"n",(function(){return o})),r.d(e,"l",(function(){return a})),r.d(e,"m",(function(){return i})),r.d(e,"f",(function(){return s})),r.d(e,"a",(function(){return u})),r.d(e,"d",(function(){return l})),r.d(e,"c",(function(){return d})),r.d(e,"e",(function(){return c})),r.d(e,"k",(function(){return f})),r.d(e,"h",(function(){return m})),r.d(e,"j",(function(){return h})),r.d(e,"i",(function(){return p})),r.d(e,"g",(function(){return g})),r.d(e,"b",(function(){return b}));var n=r("b775");r("d722");function o(t){return Object(n["a"])({url:"/shortvideo/lists",method:"get",params:t})}function a(t){return Object(n["a"])({url:"/shortvideo/audit",method:"post",data:t})}function i(t){return Object(n["a"])({url:"/shortvideo/del",method:"post",data:t})}function s(t){return Object(n["a"])({url:"/shortvideo/ad_list",method:"get",params:t})}function u(t){return Object(n["a"])({url:"/shortvideo/ad_add",method:"post",data:t})}function l(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(n["a"])({url:"/shortvideo/ad_edit",method:e,data:t}):Object(n["a"])({url:"/shortvideo/ad_edit",method:e,params:t})}function d(t){return Object(n["a"])({url:"/shortvideo/ad_del",method:"post",data:t})}function c(t){return Object(n["a"])({url:"/shortvideo/innerLinkOptions",method:"get",params:t})}function f(t){return Object(n["a"])({url:"/shortvideo/ad_cat_list",method:"get",params:t})}function m(t){return Object(n["a"])({url:"/shortvideo/ad_cat_add",method:"post",data:t})}function h(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(n["a"])({url:"/shortvideo/ad_cat_edit",method:e,data:t}):Object(n["a"])({url:"/shortvideo/ad_cat_edit",method:e,params:t})}function p(t){return Object(n["a"])({url:"/shortvideo/ad_cat_del",method:"post",data:t})}function g(t){return Object(n["a"])({url:"/shortvideo/ad_cat_platform",method:"get",params:t})}function b(t){return Object(n["a"])({url:"/shortvideo/ad_cat_tree",method:"get",params:t})}},"9c04":function(t,e,r){"use strict";r.r(e);var n=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"app-container"},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("span",[t._v("编辑广告位")]),r("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/shortvideo/ad")}}},[t._v(" 返回 ")]),r("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),r("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"100px",rules:t.rules,"inline-message":!0}},[r("el-form-item",{attrs:{label:"所属平台",prop:"platform"}},[r("el-select",{attrs:{placeholder:"请选择所属平台"},model:{value:t.form.platform,callback:function(e){t.$set(t.form,"platform",e)},expression:"form.platform"}},t._l(t.options_platform,(function(t,e){return r("el-option",{key:e,attrs:{label:t.name,value:t.id}})})),1)],1),r("el-form-item",{attrs:{label:"广告位名称",prop:"name"}},[r("el-input",{model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),r("el-form-item",{attrs:{label:"调用名称",prop:"alias"}},[r("el-input",{model:{value:t.form.alias,callback:function(e){t.$set(t.form,"alias",e)},expression:"form.alias"}}),r("span",{staticClass:"smalltip"},[r("i",{staticClass:"el-icon-info"}),t._v(" 自定义广告位调用名不可以以 “QS_”开头 ")])],1),r("el-form-item",{attrs:{label:"数量",prop:"ad_num"}},[r("el-input",{model:{value:t.form.ad_num,callback:function(e){t.$set(t.form,"ad_num",t._n(e))},expression:"form.ad_num"}})],1),r("el-form-item",{attrs:{label:"建议宽度",prop:"width"}},[r("el-input",{model:{value:t.form.width,callback:function(e){t.$set(t.form,"width",t._n(e))},expression:"form.width"}})],1),r("el-form-item",{attrs:{label:"建议高度",prop:"height"}},[r("el-input",{model:{value:t.form.height,callback:function(e){t.$set(t.form,"height",t._n(e))},expression:"form.height"}})],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),r("el-button",{on:{click:function(e){return t.goto("/shortvideo/ad")}}},[t._v("返回")])],1)],1)],1)],1)},o=[],a=r("7e2d"),i=(r("bb21"),r("8e35")),s={data:function(){var t=this,e=function(e,r,n){""==r&&n(),0==t.is_sys&&r.startsWith("QS_")?n(new Error("自定义广告位调用名不可以以 “QS_”开头")):n()};return{is_sys:0,infoLoading:!0,options_platform:[],form:{platform:"",name:"",alias:"",ad_num:"",width:"",height:""},rules:{platform:[{required:!0,message:"请选择所属平台",trigger:"change"}],name:[{required:!0,message:"请输入广告位名称",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"}],alias:[{required:!0,message:"请输入调用名称",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"},{validator:e,trigger:"blur"}],ad_num:[{required:!0,message:"请输入数量",trigger:"blur"},{type:"number",message:"数量必须为数字",trigger:"blur"}],width:[{required:!0,message:"请输入建议宽度",trigger:"blur"},{type:"number",message:"建议宽度必须为数字",trigger:"blur"}],height:[{required:!0,message:"请输入建议高度",trigger:"blur"},{type:"number",message:"建议高度必须为数字",trigger:"blur"}]}}},mounted:function(){},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var t=this;this.infoLoading=!0,Object(i["g"])().then((function(e){return t.options_platform=e.data,Object(i["j"])({id:t.$route.query.id},"get")})).then((function(e){t.form=Object(a["a"])({},e.data.info),t.is_sys=e.data.info.is_sys,t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,r=this,n=Object(a["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;Object(i["j"])(n).then((function(t){return e.$message.success(t.message),setTimeout((function(){r.$router.push("/shortvideo/ad")}),1500),!0})).catch((function(){}))}))},goto:function(t){this.$router.push(t)}}},u=s,l=(r("401f"),r("5d22")),d=Object(l["a"])(u,n,o,!1,null,"d5089a6e",null);e["default"]=d.exports},bb21:function(t,e,r){"use strict";var n=r("1c8b"),o=r("aa6b").f,a=r("d88d"),i=r("07a2"),s=r("2732"),u=r("783d"),l=r("9b9d"),d="".startsWith,c=Math.min,f=u("startsWith"),m=!l&&!f&&!!function(){var t=o(String.prototype,"startsWith");return t&&!t.writable}();n({target:"String",proto:!0,forced:!m&&!f},{startsWith:function(t){var e=String(s(this));i(t);var r=a(c(arguments.length>1?arguments[1]:void 0,e.length)),n=String(t);return d?d.call(e,n,r):e.slice(r,r+n.length)===n}})}}]);