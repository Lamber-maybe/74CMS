(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-b3062ba4"],{2423:function(t,e,r){"use strict";r.d(e,"d",(function(){return o})),r.d(e,"a",(function(){return s})),r.d(e,"c",(function(){return l})),r.d(e,"b",(function(){return n}));var a=r("b775"),i=r("d722");function o(t){return Object(a["a"])({url:i["a"].articleList,method:"get",params:t})}function s(t){return Object(a["a"])({url:i["a"].articleAdd,method:"post",data:t})}function l(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(a["a"])({url:i["a"].articleEdit,method:e,data:t}):Object(a["a"])({url:i["a"].articleEdit,method:e,params:t})}function n(t){return Object(a["a"])({url:i["a"].articleDelete,method:"post",data:t})}},"267d":function(t,e,r){"use strict";var a=r("4d1a"),i=r.n(a);i.a},"4d1a":function(t,e,r){},"52b5":function(t,e,r){"use strict";r.d(e,"a",(function(){return o}));var a=r("b775"),i=r("d722");function o(t){return Object(a["a"])({url:i["a"].getClassify,method:"get",params:t})}},d14d:function(t,e,r){"use strict";r.r(e);var a=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"app-container"},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("span",[t._v("编辑资讯")]),r("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/content/article/list")}}},[t._v(" 返回 ")]),r("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),r("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"80px",rules:t.rules,"inline-message":!0}},[r("el-form-item",{attrs:{label:"资讯标题",prop:"title"}},[r("el-input",{model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),r("el-form-item",{attrs:{label:"资讯分类",prop:"cid"}},[r("el-select",{attrs:{placeholder:"请选择资讯分类"},model:{value:t.form.cid,callback:function(e){t.$set(t.form,"cid",e)},expression:"form.cid"}},t._l(t.articleCategory,(function(t,e){return r("el-option",{key:e,attrs:{label:t.name,value:t.id}})})),1)],1),r("el-form-item",{attrs:{label:"缩略图"}},[r("el-upload",{staticClass:"thumb-uploader",attrs:{action:t.apiUpload,headers:t.headers,"show-file-list":!1,"on-success":t.handleThumbSuccess,"before-upload":t.beforeThumbUpload}},[t.form.thumb?r("img",{staticClass:"thumb",attrs:{src:t.imageUrl}}):r("i",{staticClass:"el-icon-plus thumb-uploader-icon"})])],1),r("el-form-item",{attrs:{label:"内容",required:"",prop:"content"}},[r("div",{staticClass:"editor",attrs:{id:"editor"}})]),r("el-form-item",{attrs:{label:"附件",prop:"attach"}},[r("el-upload",{staticClass:"upload-demo",attrs:{action:t.apiAttachUpload,headers:t.headers,"on-remove":t.handleRemove,"file-list":t.form.attach,"on-success":t.handleAttachSuccess,"before-upload":t.beforeAttachUpload}},[r("el-button",{attrs:{size:"small",type:"primary"}},[t._v("点击上传")]),r("div",{staticClass:"el-upload__tip",attrs:{slot:"tip"},slot:"tip"},[t._v("只能上传excel,word,ppt文件，且不超过"+t._s(t.fileupload_size)+"kb")])],1)],1),r("el-form-item",{attrs:{label:"是否显示"}},[r("el-switch",{model:{value:t.form.is_display,callback:function(e){t.$set(t.form,"is_display",e)},expression:"form.is_display"}})],1),r("el-form-item",{attrs:{label:"发布日期"}},[r("el-date-picker",{attrs:{type:"datetime",format:"yyyy-MM-dd HH:mm",placeholder:"请选择发布日期"},model:{value:t.form.addtime,callback:function(e){t.$set(t.form,"addtime",e)},expression:"form.addtime"}})],1),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),r("el-form-item",{attrs:{label:"外部链接",prop:"link_url"}},[r("el-input",{model:{value:t.form.link_url,callback:function(e){t.$set(t.form,"link_url",e)},expression:"form.link_url"}})],1),r("el-form-item",{attrs:{label:"seo关键词",prop:"seo_keywords"}},[r("el-input",{model:{value:t.form.seo_keywords,callback:function(e){t.$set(t.form,"seo_keywords",e)},expression:"form.seo_keywords"}})],1),r("el-form-item",{attrs:{label:"seo描述",prop:"seo_description"}},[r("el-input",{attrs:{type:"textarea",rows:"4"},model:{value:t.form.seo_description,callback:function(e){t.$set(t.form,"seo_description",e)},expression:"form.seo_description"}})],1),r("el-form-item",{attrs:{label:"来源",prop:"source"}},[r("el-select",{attrs:{placeholder:"请选择资讯来源"},model:{value:t.form.source,callback:function(e){t.$set(t.form,"source",e)},expression:"form.source"}},[r("el-option",{attrs:{label:"原创",value:0}}),r("el-option",{attrs:{label:"转载",value:1}})],1)],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),r("el-button",{on:{click:function(e){return t.goto("/content/article/list")}}},[t._v("返回")])],1)],1)],1)],1)},i=[],o=(r("caad"),r("c975"),r("a434"),r("b0c0"),r("d3b7"),r("ac1f"),r("2532"),r("3ca3"),r("1276"),r("ddb0"),r("2b3d"),r("5530")),s=r("5f87"),l=r("61f7"),n=r("2423"),c=r("52b5"),d=r("6fad"),u=r.n(d),m=r("d722"),f={data:function(){var t=this,e=function(e,r,a){r=t.editor.txt.text(),""===r?a(new Error("请输入内容")):a()},r=function(t,e,r){""==e&&r(),Object(l["d"])(e)?r():r(new Error("请输入正确的网址"))};return{headers:{admintoken:Object(s["e"])()},fileupload_size:"",fileupload_ext:"",apiUpload:window.global.RequestBaseUrl+m["a"].upload,apiAttachUpload:window.global.RequestBaseUrl+m["a"].uploadAttach,editor:"",articleCategory:[],form:{title:"",cid:"",content:"",attach:[],thumb:"",is_display:!0,link_url:"",seo_keywords:"",seo_description:"",addtime:"",sort_id:0,source:0},imageUrl:"",rules:{title:[{required:!0,message:"请输入资讯标题",trigger:"blur"},{max:100,message:"长度在 1 到 100 个字符",trigger:"blur"}],cid:[{required:!0,message:"请选择资讯分类",trigger:"change"}],content:[{validator:e,trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}],link_url:[{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"},{validator:r,trigger:"blur"}],seo_keywords:[{max:100,message:"长度在 0 到 100 个字符",trigger:"blur"}],seo_description:[{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"}]}}},computed:{config:function(){return this.$store.state.config}},mounted:function(){this.editor=new u.a("#editor"),this.editor.config.uploadImgServer=window.global.RequestBaseUrl+m["a"].uploadEditor,this.editor.config.uploadImgHeaders={admintoken:Object(s["e"])()},this.editor.config.zIndex=0,this.editor.config.pasteFilterStyle=!1,this.editor.create()},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext,this.fetchInfo()},methods:{handleRemove:function(t,e){var r=this.form.attach.indexOf({name:t.name,url:t.url});this.form.attach.splice(r,1)},handleAttachSuccess:function(t,e){if(200!=t.code)return this.$message.error(t.message),!1;var r={name:t.data.name,url:t.data.url};this.form.attach.push(r)},beforeAttachUpload:function(t){var e="doc,docx,xls,xlsx,csv,ppt,pptx,pdf",r=t.name.split("."),a=r[r.length-1];return e.includes(a)?!(t.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)},fetchInfo:function(){var t=this;this.infoLoading=!0,Object(c["a"])({type:"articleCategory"}).then((function(e){t.articleCategory=e.data;var r={id:t.$route.query.id};return Object(n["c"])(r,"get")})).then((function(e){t.form=Object(o["a"])({},e.data.info),t.form.addtime=1e3*t.form.addtime,t.form.is_display=1==t.form.is_display,t.editor.txt.html(t.form.content),t.imageUrl=e.data.imageUrl,t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,r=this;this.form.content=this.editor.txt.html();var a=Object(o["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;if(a.is_display=!0===a.is_display?1:0,a.addtime){var i=new Date(a.addtime);a.addtime=i.getFullYear()+"-"+(i.getMonth()+1)+"-"+i.getDate()+" "+i.getHours()+":"+i.getMinutes()}Object(n["c"])(a).then((function(t){return e.$message.success(t.message),setTimeout((function(){r.$router.push("/content/article/list")}),1500),!0})).catch((function(){}))}))},handleThumbSuccess:function(t,e){if(200!=t.code)return this.$message.error(t.message),!1;this.imageUrl=URL.createObjectURL(e.raw),this.form.thumb=t.data.file_id},beforeThumbUpload:function(t){var e=t.type.split("/"),r=e[1],a=this.fileupload_ext.split(",");return a.includes(r)?!(t.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)},goto:function(t){this.$router.push(t)}}},p=f,h=(r("267d"),r("2877")),b=Object(h["a"])(p,a,i,!1,null,"6f9557ed",null);e["default"]=b.exports}}]);