(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-721ce44b"],{"1a46":function(t,e,r){},"28da":function(t,e,r){"use strict";r.r(e);var o=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"app-container"},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("span",[t._v("编辑分类")]),r("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/content/hrtool/category")}}},[t._v(" 返回 ")]),r("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text",disabled:t.issubmit},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),r("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"80px",rules:t.rules,"inline-message":!0}},[r("el-form-item",{attrs:{label:"分类名称",prop:"name"}},[r("el-input",{model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),r("el-form-item",{attrs:{label:"描述",prop:"describe"}},[r("el-input",{attrs:{type:"textarea",rows:"4"},model:{value:t.form.describe,callback:function(e){t.$set(t.form,"describe",e)},expression:"form.describe"}})],1),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary",disabled:t.issubmit},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),r("el-button",{on:{click:function(e){return t.goto("/content/hrtool/category")}}},[t._v("返回")])],1)],1)],1)],1)},n=[],i=r("5530"),a=r("cfc2"),s={data:function(){return{issubmit:!1,infoLoading:!0,form:{name:"",describe:"",sort_id:0},rules:{name:[{required:!0,message:"请输入分类名称",trigger:"blur"},{max:10,message:"长度在 1 到 10 个字符",trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}],describe:[{required:!0,message:"请输入分类描述",trigger:"blur"},{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"}]}}},mounted:function(){},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var t=this;this.infoLoading=!0;var e={id:this.$route.query.id};Object(a["c"])(e,"get").then((function(e){t.form=Object(i["a"])({},e.data.info),t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,r=this;r.issubmit=!0;var o=Object(i["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return r.issubmit=!1,!1;Object(a["c"])(o).then((function(t){return e.$message.success(t.message),setTimeout((function(){r.$router.push("/content/hrtool/category")}),1500),!0})).catch((function(){return r.issubmit=!1,!1}))}))},goto:function(t){this.$router.push(t)}}},c=s,u=(r("476a"),r("2877")),l=Object(u["a"])(c,o,n,!1,null,"7174a752",null);e["default"]=l.exports},"476a":function(t,e,r){"use strict";r("1a46")},cfc2:function(t,e,r){"use strict";r.d(e,"d",(function(){return i})),r.d(e,"a",(function(){return a})),r.d(e,"c",(function(){return s})),r.d(e,"b",(function(){return c}));var o=r("b775"),n=r("d722");function i(t){return Object(o["a"])({url:n["a"].hrtoolCategoryList,method:"get",params:t})}function a(t){return Object(o["a"])({url:n["a"].hrtoolCategoryAdd,method:"post",data:t})}function s(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(o["a"])({url:n["a"].hrtoolCategoryEdit,method:e,data:t}):Object(o["a"])({url:n["a"].hrtoolCategoryEdit,method:e,params:t})}function c(t){return Object(o["a"])({url:n["a"].hrtoolCategoryDelete,method:"post",data:t})}}}]);