(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-f7f9ce0a"],{"18a3":function(t,e,r){"use strict";r("b73e")},"52b5":function(t,e,r){"use strict";r.d(e,"a",(function(){return n}));var o=r("b775"),i=r("d722");function n(t){return Object(o["a"])({url:i["a"].getClassify,method:"get",params:t})}},"8f49":function(t,e,r){"use strict";r.r(e);var o=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"app-container"},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("span",[t._v("添加")]),r("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/content/help/list")}}},[t._v(" 返回 ")]),r("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),r("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"80px",rules:t.rules,"inline-message":!0}},[r("el-form-item",{attrs:{label:"标题",prop:"title"}},[r("el-input",{model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),r("el-form-item",{attrs:{label:"分类",prop:"cid"}},[r("el-select",{attrs:{placeholder:"请选择分类"},model:{value:t.form.cid,callback:function(e){t.$set(t.form,"cid",e)},expression:"form.cid"}},t._l(t.helpCategory,(function(t,e){return r("el-option",{key:e,attrs:{label:t.name,value:t.id}})})),1)],1),r("el-form-item",{attrs:{label:"内容",required:"",prop:"content"}},[r("div",{staticClass:"editor",attrs:{id:"editor"}})]),r("el-form-item",{attrs:{label:"是否显示"}},[r("el-switch",{model:{value:t.form.is_display,callback:function(e){t.$set(t.form,"is_display",e)},expression:"form.is_display"}})],1),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),r("el-form-item",{attrs:{label:"seo关键词",prop:"seo_keywords"}},[r("el-input",{model:{value:t.form.seo_keywords,callback:function(e){t.$set(t.form,"seo_keywords",e)},expression:"form.seo_keywords"}})],1),r("el-form-item",{attrs:{label:"seo描述",prop:"seo_description"}},[r("el-input",{attrs:{type:"textarea",rows:"4"},model:{value:t.form.seo_description,callback:function(e){t.$set(t.form,"seo_description",e)},expression:"form.seo_description"}})],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),r("el-button",{on:{click:function(e){return t.goto("/content/help/list")}}},[t._v("返回")])],1)],1)],1)],1)},i=[],n=r("7e2d"),s=r("f2d1"),a=r("52b5"),l=r("5fd4"),c=r.n(l),u=r("d722"),d=r("5f87"),f={data:function(){var t=this,e=function(e,r,o){r=t.editor.txt.text(),""===r?o(new Error("请输入内容")):o()};return{editor:"",helpCategory:[],form:{title:"",cid:"",content:"",is_display:!0,seo_keywords:"",seo_description:"",sort_id:0},rules:{title:[{required:!0,message:"请输入标题",trigger:"blur"},{max:100,message:"长度在 1 到 100 个字符",trigger:"blur"}],cid:[{required:!0,message:"请选择分类",trigger:"change"}],content:[{validator:e,trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}],seo_keywords:[{max:100,message:"长度在 0 到 100 个字符",trigger:"blur"}],seo_description:[{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"}]}}},mounted:function(){this.editor=new c.a("#editor"),this.editor.config.uploadImgServer=window.global.RequestBaseUrl+u["a"].uploadEditor,this.editor.config.uploadImgHeaders={admintoken:Object(d["e"])()},this.editor.config.zIndex=0,this.editor.config.pasteFilterStyle=!1,this.editor.create()},created:function(){this.fetchCategoryData()},methods:{fetchCategoryData:function(){var t=this,e={type:"helpCategory"};Object(a["a"])(e).then((function(e){t.helpCategory=e.data,t.helpCategoryLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,r=this;this.form.content=this.editor.txt.html();var o=Object(n["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;o.is_display=!0===o.is_display?1:0,Object(s["a"])(o).then((function(t){return e.$message.success(t.message),setTimeout((function(){r.$router.push("/content/help/list")}),1500),!0})).catch((function(){}))}))},goto:function(t){this.$router.push(t)}}},m=f,p=(r("18a3"),r("5d22")),h=Object(p["a"])(m,o,i,!1,null,"6c84d648",null);e["default"]=h.exports},b73e:function(t,e,r){},f2d1:function(t,e,r){"use strict";r.d(e,"d",(function(){return n})),r.d(e,"a",(function(){return s})),r.d(e,"c",(function(){return a})),r.d(e,"b",(function(){return l}));var o=r("b775"),i=r("d722");function n(t){return Object(o["a"])({url:i["a"].helpList,method:"get",params:t})}function s(t){return Object(o["a"])({url:i["a"].helpAdd,method:"post",data:t})}function a(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(o["a"])({url:i["a"].helpEdit,method:e,data:t}):Object(o["a"])({url:i["a"].helpEdit,method:e,params:t})}function l(t){return Object(o["a"])({url:i["a"].helpDelete,method:"post",data:t})}}}]);