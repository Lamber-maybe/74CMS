(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3bf724d8"],{"0bb4":function(t,e,o){"use strict";o.d(e,"d",(function(){return a})),o.d(e,"a",(function(){return s})),o.d(e,"c",(function(){return n})),o.d(e,"b",(function(){return l}));var i=o("b775"),r=o("d722");function a(t){return Object(i["a"])({url:r["a"].noticeList,method:"get",params:t})}function s(t){return Object(i["a"])({url:r["a"].noticeAdd,method:"post",data:t})}function n(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(i["a"])({url:r["a"].noticeEdit,method:e,data:t}):Object(i["a"])({url:r["a"].noticeEdit,method:e,params:t})}function l(t){return Object(i["a"])({url:r["a"].noticeDelete,method:"post",data:t})}},"0e01":function(t,e,o){"use strict";o("1c9a")},"1c9a":function(t,e,o){},b819:function(t,e,o){"use strict";o.r(e);var i=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"app-container"},[o("el-card",{staticClass:"box-card"},[o("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[o("span",[t._v("编辑公告")]),o("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/content/notice/list")}}},[t._v(" 返回 ")]),o("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text",disabled:t.issubmit},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),o("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"80px",rules:t.rules,"inline-message":!0}},[o("el-form-item",{attrs:{label:"标题",prop:"title"}},[o("el-input",{model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),o("el-form-item",{attrs:{label:"内容",required:"",prop:"content"}},[o("div",{staticStyle:{border:"1px solid #ccc"}},[o("Toolbar",{staticStyle:{"border-bottom":"1px solid #ccc"},attrs:{editor:t.editor,defaultConfig:t.toolbarConfig}}),o("Editor",{staticStyle:{height:"400px","overflow-y":"hidden"},on:{onCreated:t.onCreated},model:{value:t.form.content,callback:function(e){t.$set(t.form,"content",e)},expression:"form.content"}})],1)]),o("el-form-item",{attrs:{label:"附件",prop:"attach"}},[o("el-upload",{staticClass:"upload-demo",attrs:{action:t.apiAttachUpload,headers:t.headers,"on-remove":t.handleRemove,"file-list":t.form.attach,"on-success":t.handleAttachSuccess,"before-upload":t.beforeAttachUpload}},[o("el-button",{attrs:{size:"small",type:"primary"}},[t._v("点击上传")]),o("div",{staticClass:"el-upload__tip",attrs:{slot:"tip"},slot:"tip"},[t._v("只能上传excel,word,ppt文件，且不超过"+t._s(t.fileupload_size)+"kb")])],1)],1),o("el-form-item",{attrs:{label:"是否显示"}},[o("el-switch",{model:{value:t.form.is_display,callback:function(e){t.$set(t.form,"is_display",e)},expression:"form.is_display"}})],1),o("el-form-item",{attrs:{label:"发布日期"}},[o("el-date-picker",{attrs:{type:"datetime",format:"yyyy-MM-dd HH:mm",placeholder:"请选择发布日期"},model:{value:t.form.addtime,callback:function(e){t.$set(t.form,"addtime",e)},expression:"form.addtime"}})],1),o("el-form-item",{attrs:{label:"点击量",prop:"click"}},[o("el-input",{model:{value:t.form.click,callback:function(e){t.$set(t.form,"click",t._n(e))},expression:"form.click"}})],1),o("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[o("el-input",{model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),o("el-form-item",{attrs:{label:"外部链接",prop:"link_url"}},[o("el-input",{model:{value:t.form.link_url,callback:function(e){t.$set(t.form,"link_url",e)},expression:"form.link_url"}})],1),o("el-form-item",{attrs:{label:"seo关键词",prop:"seo_keywords"}},[o("el-input",{model:{value:t.form.seo_keywords,callback:function(e){t.$set(t.form,"seo_keywords",e)},expression:"form.seo_keywords"}})],1),o("el-form-item",{attrs:{label:"seo描述",prop:"seo_description"}},[o("el-input",{attrs:{type:"textarea",rows:"4"},model:{value:t.form.seo_description,callback:function(e){t.$set(t.form,"seo_description",e)},expression:"form.seo_description"}})],1),o("el-form-item",{attrs:{label:""}},[o("el-button",{attrs:{type:"primary",disabled:t.issubmit},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),o("el-button",{on:{click:function(e){return t.goto("/content/notice/list")}}},[t._v("返回")])],1)],1)],1)],1)},r=[],a=o("5530"),s=(o("af93"),o("b0c0"),o("a434"),o("ac1f"),o("1276"),o("2532"),o("61f7")),n=o("0bb4"),l=o("d722"),c=o("5f87"),d=(o("560e"),o("4e15")),u={components:{Editor:d["a"],Toolbar:d["b"]},data:function(){var t=this,e=function(e,o,i){o=t.editor.getText(),""===o?i(new Error("请输入内容")):i()},o=function(t,e,o){""==e&&o(),Object(s["d"])(e)?o():o(new Error("请输入正确的网址"))};return{issubmit:!1,editor:null,toolbarConfig:{excludeKeys:["fullScreen"]},headers:{admintoken:Object(c["e"])()},fileupload_size:"",apiAttachUpload:window.global.RequestBaseUrl+l["a"].uploadAttach,infoLoading:!0,form:{title:"",content:"",attach:[],is_display:!0,link_url:"",seo_keywords:"",seo_description:"",addtime:"",sort_id:0},rules:{title:[{required:!0,message:"请输入资讯标题",trigger:"blur"},{max:100,message:"长度在 1 到 100 个字符",trigger:"blur"}],content:[{validator:e,trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}],link_url:[{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"},{validator:o,trigger:"blur"}],seo_keywords:[{max:100,message:"长度在 0 到 100 个字符",trigger:"blur"}],seo_description:[{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"}]}}},computed:{config:function(){return this.$store.state.config}},mounted:function(){},created:function(){this.fileupload_size=this.config.fileupload_size,this.fetchInfo()},beforeDestroy:function(){var t=this.editor;null!=t&&t.destroy()},methods:{onCreated:function(t){this.editor=Object.seal(t),this.editor.getMenuConfig("uploadImage").headers={admintoken:Object(c["e"])()},this.editor.getMenuConfig("uploadImage").withCredentials=!0,this.editor.getMenuConfig("uploadImage").server=window.global.RequestBaseUrl+l["a"].uploadEditor,this.editor.getMenuConfig("uploadVideo").headers={admintoken:Object(c["e"])()},this.editor.getMenuConfig("uploadVideo").withCredentials=!0,this.editor.getMenuConfig("uploadVideo").server=window.global.RequestBaseUrl+l["a"].uploadEditorVideo},handleRemove:function(t,e){var o=this.form.attach.indexOf({name:t.name,url:t.url});this.form.attach.splice(o,1)},handleAttachSuccess:function(t,e){if(200!=t.code)return this.$message.error(t.message),!1;var o={name:t.data.name,url:t.data.url};this.form.attach.push(o)},beforeAttachUpload:function(t){var e="doc,docx,xls,xlsx,csv,ppt,pptx,pdf",o=t.name.split("."),i=o[o.length-1];return e.includes(i)?!(t.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)},fetchInfo:function(){var t=this;this.infoLoading=!0;var e={id:this.$route.query.id};Object(n["c"])(e,"get").then((function(e){t.form=Object(a["a"])({},e.data.info),t.form.addtime=1e3*t.form.addtime,t.form.is_display=1==t.form.is_display,t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,o=this;o.issubmit=!0,this.form.content=this.editor.getHtml();var i=Object(a["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return o.issubmit=!1,!1;if(i.is_display=!0===i.is_display?1:0,i.addtime){var r=new Date(i.addtime);i.addtime=r.getFullYear()+"-"+(r.getMonth()+1)+"-"+r.getDate()+" "+r.getHours()+":"+r.getMinutes()}Object(n["c"])(i).then((function(t){return e.$message.success(t.message),setTimeout((function(){o.$router.push("/content/notice/list")}),1500),!0})).catch((function(){return o.issubmit=!1,!1}))}))},goto:function(t){this.$router.push(t)}}},f=u,m=(o("0e01"),o("2877")),p=Object(m["a"])(f,i,r,!1,null,"0fcd4868",null);e["default"]=p.exports}}]);