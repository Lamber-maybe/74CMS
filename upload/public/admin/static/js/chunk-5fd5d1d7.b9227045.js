(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-5fd5d1d7"],{4730:function(t,e,o){"use strict";o.d(e,"d",(function(){return a})),o.d(e,"a",(function(){return n})),o.d(e,"c",(function(){return s})),o.d(e,"b",(function(){return l}));var r=o("b775"),i=o("d722");function a(t){return Object(r["a"])({url:i["a"].hrtoolList,method:"get",params:t})}function n(t){return Object(r["a"])({url:i["a"].hrtoolAdd,method:"post",data:t})}function s(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(r["a"])({url:i["a"].hrtoolEdit,method:e,data:t}):Object(r["a"])({url:i["a"].hrtoolEdit,method:e,params:t})}function l(t){return Object(r["a"])({url:i["a"].hrtoolDelete,method:"post",data:t})}},"52b5":function(t,e,o){"use strict";o.d(e,"a",(function(){return a}));var r=o("b775"),i=o("d722");function a(t){return Object(r["a"])({url:i["a"].getClassify,method:"get",params:t})}},"649f":function(t,e,o){},a7ef:function(t,e,o){"use strict";o.r(e);var r=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"app-container"},[o("el-card",{staticClass:"box-card"},[o("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[o("span",[t._v("编辑")]),o("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/content/hrtool/list")}}},[t._v(" 返回 ")]),o("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),o("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"80px",rules:t.rules,"inline-message":!0}},[o("el-form-item",{attrs:{label:"文件名称",prop:"filename"}},[o("el-input",{model:{value:t.form.filename,callback:function(e){t.$set(t.form,"filename",e)},expression:"form.filename"}})],1),o("el-form-item",{attrs:{label:"分类",prop:"cid"}},[o("el-select",{attrs:{placeholder:"请选择分类"},model:{value:t.form.cid,callback:function(e){t.$set(t.form,"cid",e)},expression:"form.cid"}},t._l(t.hrtoolCategory,(function(t,e){return o("el-option",{key:e,attrs:{label:t.name,value:t.id}})})),1)],1),o("el-form-item",{attrs:{label:"文件",prop:"fileurl"}},[o("el-upload",{staticClass:"thumb-uploader",attrs:{action:t.apiUpload,headers:t.headers,"show-file-list":!1,"on-success":t.handlerFileSuccess,"before-upload":t.beforeFileUpload}},[o("i",{staticClass:"el-icon-plus thumb-uploader-icon"})])],1),o("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[o("el-input",{model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),o("el-form-item",{attrs:{label:""}},[o("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),o("el-button",{on:{click:function(e){return t.goto("/content/hrtool/list")}}},[t._v("返回")])],1)],1)],1)],1)},i=[],a=o("5530"),n=o("5f87"),s=o("4730"),l=o("52b5"),u=o("d722"),c={data:function(){return{headers:{admintoken:Object(n["e"])()},fileupload_size:"",fileupload_ext:"",apiUpload:window.global.RequestBaseUrl+u["a"].uploadHrtool,hrtoolCategory:[],form:{filename:"",cid:"",fileurl:"",sort_id:0},rules:{filename:[{required:!0,message:"请输入文件名称",trigger:"blur"},{max:100,message:"长度在 1 到 100 个字符",trigger:"blur"}],cid:[{required:!0,message:"请选择分类",trigger:"change"}],fileurl:[{required:!0,message:"请上传文件",trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}]}}},computed:{config:function(){return this.$store.state.config}},mounted:function(){},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext,this.fetchInfo()},methods:{fetchInfo:function(){var t=this;this.infoLoading=!0,Object(l["a"])({type:"hrtoolCategory"}).then((function(e){t.hrtoolCategory=e.data;var o={id:t.$route.query.id};return Object(s["c"])(o,"get")})).then((function(e){t.form=Object(a["a"])({},e.data.info),t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,o=this,r=Object(a["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;Object(s["c"])(r).then((function(t){return e.$message.success(t.message),setTimeout((function(){o.$router.push("/content/hrtool/list")}),1500),!0})).catch((function(){}))}))},handlerFileSuccess:function(t,e){if(200!=t.code)return this.$message.error(t.message),!1;this.form.fileurl=t.data.save_path,this.$message({type:"success",message:"上传成功"})},beforeFileUpload:function(t){return!(t.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1)},goto:function(t){this.$router.push(t)}}},f=c,d=(o("f936"),o("2877")),m=Object(d["a"])(f,r,i,!1,null,"24aaaad0",null);e["default"]=m.exports},f936:function(t,e,o){"use strict";var r=o("649f"),i=o.n(r);i.a}}]);