(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-88beb970"],{2423:function(e,t,r){"use strict";r.d(t,"d",(function(){return a})),r.d(t,"a",(function(){return s})),r.d(t,"c",(function(){return l})),r.d(t,"b",(function(){return n}));var i=r("b775"),o=r("d722");function a(e){return Object(i["a"])({url:o["a"].articleList,method:"get",params:e})}function s(e){return Object(i["a"])({url:o["a"].articleAdd,method:"post",data:e})}function l(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==t?Object(i["a"])({url:o["a"].articleEdit,method:t,data:e}):Object(i["a"])({url:o["a"].articleEdit,method:t,params:e})}function n(e){return Object(i["a"])({url:o["a"].articleDelete,method:"post",data:e})}},"52b5":function(e,t,r){"use strict";r.d(t,"a",(function(){return a}));var i=r("b775"),o=r("d722");function a(e){return Object(i["a"])({url:o["a"].getClassify,method:"get",params:e})}},abca:function(e,t,r){},cfc6:function(e,t,r){"use strict";var i=r("abca"),o=r.n(i);o.a},d14d:function(e,t,r){"use strict";r.r(t);var i=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("span",[e._v("编辑资讯")]),r("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(t){return e.goto("/content/article/list")}}},[e._v(" 返回 ")]),r("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(t){return e.onSubmit("form")}}},[e._v(" 保存 ")])],1),r("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"80px",rules:e.rules,"inline-message":!0}},[r("el-form-item",{attrs:{label:"资讯标题",prop:"title"}},[r("el-input",{model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}})],1),r("el-form-item",{attrs:{label:"资讯分类",prop:"cid"}},[r("el-select",{attrs:{placeholder:"请选择资讯分类"},model:{value:e.form.cid,callback:function(t){e.$set(e.form,"cid",t)},expression:"form.cid"}},e._l(e.articleCategory,(function(e,t){return r("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1)],1),r("el-form-item",{attrs:{label:"缩略图"}},[r("el-upload",{staticClass:"thumb-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handleThumbSuccess,"before-upload":e.beforeThumbUpload}},[e.form.thumb?r("img",{staticClass:"thumb",attrs:{src:e.imageUrl}}):r("i",{staticClass:"el-icon-plus thumb-uploader-icon"})])],1),r("el-form-item",{attrs:{label:"内容",required:"",prop:"content"}},[r("div",{staticClass:"editor",attrs:{id:"editor"}})]),r("el-form-item",{attrs:{label:"是否显示"}},[r("el-switch",{model:{value:e.form.is_display,callback:function(t){e.$set(e.form,"is_display",t)},expression:"form.is_display"}})],1),r("el-form-item",{attrs:{label:"发布日期"}},[r("el-date-picker",{attrs:{type:"datetime",format:"yyyy-MM-dd HH:mm",placeholder:"请选择发布日期"},model:{value:e.form.addtime,callback:function(t){e.$set(e.form,"addtime",t)},expression:"form.addtime"}})],1),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{model:{value:e.form.sort_id,callback:function(t){e.$set(e.form,"sort_id",e._n(t))},expression:"form.sort_id"}})],1),r("el-form-item",{attrs:{label:"外部链接",prop:"link_url"}},[r("el-input",{model:{value:e.form.link_url,callback:function(t){e.$set(e.form,"link_url",t)},expression:"form.link_url"}})],1),r("el-form-item",{attrs:{label:"seo关键词",prop:"seo_keywords"}},[r("el-input",{model:{value:e.form.seo_keywords,callback:function(t){e.$set(e.form,"seo_keywords",t)},expression:"form.seo_keywords"}})],1),r("el-form-item",{attrs:{label:"seo描述",prop:"seo_description"}},[r("el-input",{attrs:{type:"textarea",rows:"4"},model:{value:e.form.seo_description,callback:function(t){e.$set(e.form,"seo_description",t)},expression:"form.seo_description"}})],1),r("el-form-item",{attrs:{label:"来源",prop:"source"}},[r("el-select",{attrs:{placeholder:"请选择资讯来源"},model:{value:e.form.source,callback:function(t){e.$set(e.form,"source",t)},expression:"form.source"}},[r("el-option",{attrs:{label:"原创",value:0}}),r("el-option",{attrs:{label:"转载",value:1}})],1)],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),r("el-button",{on:{click:function(t){return e.goto("/content/article/list")}}},[e._v("返回")])],1)],1)],1)],1)},o=[],a=(r("caad"),r("d3b7"),r("ac1f"),r("2532"),r("3ca3"),r("1276"),r("ddb0"),r("2b3d"),r("5530")),s=r("5f87"),l=r("61f7"),n=r("2423"),c=r("52b5"),d=r("6fad"),u=r.n(d),m=r("d722"),f={data:function(){var e=this,t=function(t,r,i){r=e.editor.txt.text(),""===r?i(new Error("请输入内容")):i()},r=function(e,t,r){""==t&&r(),Object(l["d"])(t)?r():r(new Error("请输入正确的网址"))};return{headers:{admintoken:Object(s["e"])()},fileupload_size:"",fileupload_ext:"",apiUpload:window.global.RequestBaseUrl+m["a"].upload,editor:"",articleCategory:[],form:{title:"",cid:"",content:"",thumb:"",is_display:!0,link_url:"",seo_keywords:"",seo_description:"",addtime:"",sort_id:0,source:0},imageUrl:"",rules:{title:[{required:!0,message:"请输入资讯标题",trigger:"blur"},{max:100,message:"长度在 1 到 100 个字符",trigger:"blur"}],cid:[{required:!0,message:"请选择资讯分类",trigger:"change"}],content:[{validator:t,trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}],link_url:[{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"},{validator:r,trigger:"blur"}],seo_keywords:[{max:100,message:"长度在 0 到 100 个字符",trigger:"blur"}],seo_description:[{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"}]}}},computed:{config:function(){return this.$store.state.config}},mounted:function(){this.editor=new u.a("#editor"),this.editor.config.uploadImgServer=window.global.RequestBaseUrl+m["a"].uploadEditor,this.editor.config.uploadImgHeaders={admintoken:Object(s["e"])()},this.editor.config.zIndex=0,this.editor.config.pasteFilterStyle=!1,this.editor.create()},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext,this.fetchInfo()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0,Object(c["a"])({type:"articleCategory"}).then((function(t){e.articleCategory=t.data;var r={id:e.$route.query.id};return Object(n["c"])(r,"get")})).then((function(t){e.form=Object(a["a"])({},t.data.info),e.form.addtime=1e3*e.form.addtime,e.form.is_display=1==e.form.is_display,e.editor.txt.html(e.form.content),e.imageUrl=t.data.imageUrl,e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,r=this;this.form.content=this.editor.txt.html();var i=Object(a["a"])({},this.form);this.$refs[e].validate((function(e){if(!e)return!1;if(i.is_display=!0===i.is_display?1:0,i.addtime){var o=new Date(i.addtime);i.addtime=o.getFullYear()+"-"+(o.getMonth()+1)+"-"+o.getDate()+" "+o.getHours()+":"+o.getMinutes()}Object(n["c"])(i).then((function(e){return t.$message.success(e.message),setTimeout((function(){r.$router.push("/content/article/list")}),1500),!0})).catch((function(){}))}))},handleThumbSuccess:function(e,t){if(200!=e.code)return this.$message.error(e.message),!1;this.imageUrl=URL.createObjectURL(t.raw),this.form.thumb=e.data.file_id},beforeThumbUpload:function(e){var t=e.type.split("/"),r=t[1],i=this.fileupload_ext.split(",");return i.includes(r)?!(e.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)},goto:function(e){this.$router.push(e)}}},p=f,b=(r("cfc6"),r("2877")),g=Object(b["a"])(p,i,o,!1,null,"44bd6aee",null);t["default"]=g.exports}}]);