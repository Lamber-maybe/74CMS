(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-238a2561"],{"2ca0":function(t,e,r){"use strict";var a=r("23e7"),n=r("06cf").f,o=r("50c4"),i=r("5a34"),s=r("1d80"),u=r("ab13"),l=r("c430"),c="".startsWith,d=Math.min,m=u("startsWith"),f=!l&&!m&&!!function(){var t=n(String.prototype,"startsWith");return t&&!t.writable}();a({target:"String",proto:!0,forced:!f&&!m},{startsWith:function(t){var e=String(s(this));i(t);var r=o(d(arguments.length>1?arguments[1]:void 0,e.length)),a=String(t);return c?c.call(e,a,r):e.slice(r,r+a.length)===a}})},3484:function(t,e,r){"use strict";r.r(e);var a=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"app-container"},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("span",[t._v("添加广告位")]),r("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/shortvideo/ad")}}},[t._v(" 返回 ")]),r("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),r("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"100px",rules:t.rules,"inline-message":!0}},[r("el-form-item",{attrs:{label:"所属平台",prop:"platform"}},[r("el-select",{attrs:{placeholder:"请选择所属平台"},model:{value:t.form.platform,callback:function(e){t.$set(t.form,"platform",e)},expression:"form.platform"}},t._l(t.options_platform,(function(t,e){return r("el-option",{key:e,attrs:{label:t.name,value:t.id}})})),1)],1),r("el-form-item",{attrs:{label:"广告位名称",prop:"name"}},[r("el-input",{model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),r("el-form-item",{attrs:{label:"调用名称",prop:"alias"}},[r("el-input",{model:{value:t.form.alias,callback:function(e){t.$set(t.form,"alias",e)},expression:"form.alias"}}),r("span",{staticClass:"smalltip"},[r("i",{staticClass:"el-icon-info"}),t._v(" 自定义广告位调用名不可以以 “QS_”开头 ")])],1),r("el-form-item",{attrs:{label:"数量",prop:"ad_num"}},[r("el-input",{model:{value:t.form.ad_num,callback:function(e){t.$set(t.form,"ad_num",t._n(e))},expression:"form.ad_num"}})],1),r("el-form-item",{attrs:{label:"建议宽度",prop:"width"}},[r("el-input",{model:{value:t.form.width,callback:function(e){t.$set(t.form,"width",t._n(e))},expression:"form.width"}})],1),r("el-form-item",{attrs:{label:"建议高度",prop:"height"}},[r("el-input",{model:{value:t.form.height,callback:function(e){t.$set(t.form,"height",t._n(e))},expression:"form.height"}})],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),r("el-button",{on:{click:function(e){return t.goto("/shortvideo/ad")}}},[t._v("返回")])],1)],1)],1)],1)},n=[],o=(r("2ca0"),r("5530")),i=r("8e35"),s={data:function(){var t=function(t,e,r){""==e&&r(),e.startsWith("QS_")?r(new Error("自定义广告位调用名不可以以 “QS_”开头")):r()};return{options_platform:[],form:{platform:"",name:"",alias:"",ad_num:"",width:"",height:""},rules:{platform:[{required:!0,message:"请选择所属平台",trigger:"change"}],name:[{required:!0,message:"请输入广告位名称",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"}],alias:[{required:!0,message:"请输入调用名称",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"},{validator:t,trigger:"blur"}],ad_num:[{required:!0,message:"请输入数量",trigger:"blur"},{type:"number",message:"数量必须为数字",trigger:"blur"}],width:[{required:!0,message:"请输入建议宽度",trigger:"blur"},{type:"number",message:"建议宽度必须为数字",trigger:"blur"}],height:[{required:!0,message:"请输入建议高度",trigger:"blur"},{type:"number",message:"建议高度必须为数字",trigger:"blur"}]}}},mounted:function(){},created:function(){this.fetchCategoryData()},methods:{fetchCategoryData:function(){var t=this;Object(i["g"])().then((function(e){t.options_platform=e.data})).catch((function(){}))},onSubmit:function(t){var e=this,r=this,a=Object(o["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;Object(i["h"])(a).then((function(t){return e.$message.success(t.message),setTimeout((function(){r.$router.push("/shortvideo/ad")}),1500),!0})).catch((function(){}))}))},goto:function(t){this.$router.push(t)}}},u=s,l=(r("94a5"),r("2877")),c=Object(l["a"])(u,a,n,!1,null,"e813dbbe",null);e["default"]=c.exports},"397f":function(t,e,r){},"8e35":function(t,e,r){"use strict";r.d(e,"n",(function(){return n})),r.d(e,"l",(function(){return o})),r.d(e,"m",(function(){return i})),r.d(e,"f",(function(){return s})),r.d(e,"a",(function(){return u})),r.d(e,"d",(function(){return l})),r.d(e,"c",(function(){return c})),r.d(e,"e",(function(){return d})),r.d(e,"k",(function(){return m})),r.d(e,"h",(function(){return f})),r.d(e,"j",(function(){return h})),r.d(e,"i",(function(){return p})),r.d(e,"g",(function(){return g})),r.d(e,"b",(function(){return b}));var a=r("b775");r("d722");function n(t){return Object(a["a"])({url:"/shortvideo/lists",method:"get",params:t})}function o(t){return Object(a["a"])({url:"/shortvideo/audit",method:"post",data:t})}function i(t){return Object(a["a"])({url:"/shortvideo/del",method:"post",data:t})}function s(t){return Object(a["a"])({url:"/shortvideo/ad_list",method:"get",params:t})}function u(t){return Object(a["a"])({url:"/shortvideo/ad_add",method:"post",data:t})}function l(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(a["a"])({url:"/shortvideo/ad_edit",method:e,data:t}):Object(a["a"])({url:"/shortvideo/ad_edit",method:e,params:t})}function c(t){return Object(a["a"])({url:"/shortvideo/ad_del",method:"post",data:t})}function d(t){return Object(a["a"])({url:"/shortvideo/innerLinkOptions",method:"get",params:t})}function m(t){return Object(a["a"])({url:"/shortvideo/ad_cat_list",method:"get",params:t})}function f(t){return Object(a["a"])({url:"/shortvideo/ad_cat_add",method:"post",data:t})}function h(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(a["a"])({url:"/shortvideo/ad_cat_edit",method:e,data:t}):Object(a["a"])({url:"/shortvideo/ad_cat_edit",method:e,params:t})}function p(t){return Object(a["a"])({url:"/shortvideo/ad_cat_del",method:"post",data:t})}function g(t){return Object(a["a"])({url:"/shortvideo/ad_cat_platform",method:"get",params:t})}function b(t){return Object(a["a"])({url:"/shortvideo/ad_cat_tree",method:"get",params:t})}},"94a5":function(t,e,r){"use strict";var a=r("397f"),n=r.n(a);n.a}}]);