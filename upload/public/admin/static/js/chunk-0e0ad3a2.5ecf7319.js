(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0e0ad3a2"],{"050a":function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("添加分类")]),n("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/content/help/category")}}},[t._v(" 返回 ")]),n("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),n("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"80px",rules:t.rules,"inline-message":!0}},[n("el-form-item",{attrs:{label:"分类名称",prop:"name"}},[n("el-input",{model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),n("el-form-item",{attrs:{label:""}},[n("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),n("el-button",{on:{click:function(e){return t.goto("/content/help/category")}}},[t._v("返回")])],1)],1)],1)],1)},a=[],o=n("7e2d"),i=n("885b"),u={data:function(){return{form:{name:""},rules:{name:[{required:!0,message:"请输入分类名称",trigger:"blur"},{max:10,message:"长度在 1 到 10 个字符",trigger:"blur"}]}}},mounted:function(){},created:function(){},methods:{onSubmit:function(t){var e=this,n=this,r=Object(o["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;Object(i["a"])(r).then((function(t){return e.$message.success(t.message),setTimeout((function(){n.$router.push("/content/help/category")}),1500),!0})).catch((function(){}))}))},goto:function(t){this.$router.push(t)}}},c=u,s=(n("daa9"),n("5d22")),l=Object(s["a"])(c,r,a,!1,null,"6a343612",null);e["default"]=l.exports},"38ee":function(t,e,n){},"885b":function(t,e,n){"use strict";n.d(e,"d",(function(){return o})),n.d(e,"a",(function(){return i})),n.d(e,"c",(function(){return u})),n.d(e,"b",(function(){return c}));var r=n("b775"),a=n("d722");function o(t){return Object(r["a"])({url:a["a"].helpCategoryList,method:"get",params:t})}function i(t){return Object(r["a"])({url:a["a"].helpCategoryAdd,method:"post",data:t})}function u(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(r["a"])({url:a["a"].helpCategoryEdit,method:e,data:t}):Object(r["a"])({url:a["a"].helpCategoryEdit,method:e,params:t})}function c(t){return Object(r["a"])({url:a["a"].helpCategoryDelete,method:"post",data:t})}},daa9:function(t,e,n){"use strict";n("38ee")}}]);