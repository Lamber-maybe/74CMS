(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-24be53f8"],{"27f7":function(t,e,o){"use strict";o("5457")},"52b5":function(t,e,o){"use strict";o.d(e,"a",(function(){return n}));var r=o("b775"),i=o("d722");function n(t){return Object(r["a"])({url:i["a"].getClassify,method:"get",params:t})}},5457:function(t,e,o){},"8f49":function(t,e,o){"use strict";o.r(e);var r=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"app-container"},[o("el-card",{staticClass:"box-card"},[o("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[o("span",[t._v("添加")]),o("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/content/help/list")}}},[t._v(" 返回 ")]),o("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),o("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"80px",rules:t.rules,"inline-message":!0}},[o("el-form-item",{attrs:{label:"标题",prop:"title"}},[o("el-input",{model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),o("el-form-item",{attrs:{label:"分类",prop:"cid"}},[o("el-select",{attrs:{placeholder:"请选择分类"},model:{value:t.form.cid,callback:function(e){t.$set(t.form,"cid",e)},expression:"form.cid"}},t._l(t.helpCategory,(function(t,e){return o("el-option",{key:e,attrs:{label:t.name,value:t.id}})})),1)],1),o("el-form-item",{attrs:{label:"内容",required:"",prop:"content"}},[o("div",{staticStyle:{border:"1px solid #ccc"}},[o("Toolbar",{staticStyle:{"border-bottom":"1px solid #ccc"},attrs:{editor:t.editor,defaultConfig:t.toolbarConfig}}),o("Editor",{staticStyle:{height:"400px","overflow-y":"hidden"},on:{onCreated:t.onCreated},model:{value:t.form.content,callback:function(e){t.$set(t.form,"content",e)},expression:"form.content"}})],1)]),o("el-form-item",{attrs:{label:"是否显示"}},[o("el-switch",{model:{value:t.form.is_display,callback:function(e){t.$set(t.form,"is_display",e)},expression:"form.is_display"}})],1),o("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[o("el-input",{model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),o("el-form-item",{attrs:{label:"seo关键词",prop:"seo_keywords"}},[o("el-input",{model:{value:t.form.seo_keywords,callback:function(e){t.$set(t.form,"seo_keywords",e)},expression:"form.seo_keywords"}})],1),o("el-form-item",{attrs:{label:"seo描述",prop:"seo_description"}},[o("el-input",{attrs:{type:"textarea",rows:"4"},model:{value:t.form.seo_description,callback:function(e){t.$set(t.form,"seo_description",e)},expression:"form.seo_description"}})],1),o("el-form-item",{attrs:{label:""}},[o("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),o("el-button",{on:{click:function(e){return t.goto("/content/help/list")}}},[t._v("返回")])],1)],1)],1)],1)},i=[],n=o("5530"),a=(o("af93"),o("f2d1")),s=o("52b5"),l=(o("560e"),o("4e15")),c=o("d722"),d=o("5f87"),u={components:{Editor:l["a"],Toolbar:l["b"]},data:function(){var t=this,e=function(e,o,r){o=t.editor.getText(),""===o?r(new Error("请输入内容")):r()};return{editor:null,toolbarConfig:{excludeKeys:["fullScreen"]},helpCategory:[],form:{title:"",cid:"",content:"",is_display:!0,seo_keywords:"",seo_description:"",sort_id:0},rules:{title:[{required:!0,message:"请输入标题",trigger:"blur"},{max:100,message:"长度在 1 到 100 个字符",trigger:"blur"}],cid:[{required:!0,message:"请选择分类",trigger:"change"}],content:[{validator:e,trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}],seo_keywords:[{max:100,message:"长度在 0 到 100 个字符",trigger:"blur"}],seo_description:[{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"}]}}},mounted:function(){},created:function(){this.fetchCategoryData()},beforeDestroy:function(){var t=this.editor;null!=t&&t.destroy()},methods:{onCreated:function(t){this.editor=Object.seal(t),this.editor.getMenuConfig("uploadImage").headers={admintoken:Object(d["e"])()},this.editor.getMenuConfig("uploadImage").withCredentials=!0,this.editor.getMenuConfig("uploadImage").server=window.global.RequestBaseUrl+c["a"].uploadEditor,this.editor.getMenuConfig("uploadVideo").headers={admintoken:Object(d["e"])()},this.editor.getMenuConfig("uploadVideo").withCredentials=!0,this.editor.getMenuConfig("uploadVideo").server=window.global.RequestBaseUrl+c["a"].uploadEditorVideo},fetchCategoryData:function(){var t=this,e={type:"helpCategory"};Object(s["a"])(e).then((function(e){t.helpCategory=e.data,t.helpCategoryLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,o=this;this.form.content=this.editor.getHtml();var r=Object(n["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;r.is_display=!0===r.is_display?1:0,Object(a["a"])(r).then((function(t){return e.$message.success(t.message),setTimeout((function(){o.$router.push("/content/help/list")}),1500),!0})).catch((function(){}))}))},goto:function(t){this.$router.push(t)}}},f=u,m=(o("27f7"),o("2877")),p=Object(m["a"])(f,r,i,!1,null,"59c03daa",null);e["default"]=p.exports},f2d1:function(t,e,o){"use strict";o.d(e,"d",(function(){return n})),o.d(e,"a",(function(){return a})),o.d(e,"c",(function(){return s})),o.d(e,"b",(function(){return l}));var r=o("b775"),i=o("d722");function n(t){return Object(r["a"])({url:i["a"].helpList,method:"get",params:t})}function a(t){return Object(r["a"])({url:i["a"].helpAdd,method:"post",data:t})}function s(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(r["a"])({url:i["a"].helpEdit,method:e,data:t}):Object(r["a"])({url:i["a"].helpEdit,method:e,params:t})}function l(t){return Object(r["a"])({url:i["a"].helpDelete,method:"post",data:t})}}}]);