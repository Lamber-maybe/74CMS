(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-25e431af"],{"033e":function(e,t,a){"use strict";a("d2f0")},"0720":function(e,t,a){"use strict";a("9ccc")},"15fd":function(e,t,a){"use strict";a.d(t,"a",(function(){return r}));a("a4d3"),a("b64b");function i(e,t){if(null==e)return{};var a,i,r={},o=Object.keys(e);for(i=0;i<o.length;i++)a=o[i],t.indexOf(a)>=0||(r[a]=e[a]);return r}function r(e,t){if(null==e)return{};var a,r,o=i(e,t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);for(r=0;r<n.length;r++)a=n[r],t.indexOf(a)>=0||Object.prototype.propertyIsEnumerable.call(e,a)&&(o[a]=e[a])}return o}},"9ccc":function(e,t,a){},d2f0:function(e,t,a){},d39d:function(e,t,a){"use strict";a.r(t);var i=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("el-tabs",{attrs:{type:"border-card"}},[a("el-tab-pane",{attrs:{label:"公众号基础信息",lazy:!0}},[a("config-base")],1),a("el-tab-pane",{attrs:{label:"自定义关键词",lazy:!0}},[a("config-keyword")],1)],1)],1)},r=[],o=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("div",{staticClass:"spaceline"}),a("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px",rules:e.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"公众号名称"}},[a("el-input",{model:{value:e.form.wechat_name,callback:function(t){e.$set(e.form,"wechat_name",t)},expression:"form.wechat_name"}})],1),a("el-form-item",{attrs:{label:"微信号"}},[a("el-input",{model:{value:e.form.wechat_code,callback:function(t){e.$set(e.form,"wechat_code",t)},expression:"form.wechat_code"}})],1),a("el-form-item",{attrs:{label:"二维码图片"}},[a("el-upload",{staticClass:"qrcode-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handleQrcodeSuccess,"before-upload":e.beforeUpload}},[e.qrcodeUrl?a("img",{staticClass:"qrcode",attrs:{src:e.qrcodeUrl}}):a("i",{staticClass:"el-icon-plus qrcode-uploader-icon"})]),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),e._v(" 建议尺寸430*430 ")])],1),a("el-form-item",{attrs:{label:"关注欢迎语"}},[a("el-input",{staticClass:"middle",attrs:{type:"textarea",rows:8},model:{value:e.form.wechat_welcome_text,callback:function(t){e.$set(e.form,"wechat_welcome_text",t)},expression:"form.wechat_welcome_text"}})],1),a("el-form-item",{attrs:{label:"关注欢迎图片"}},[a("el-upload",{staticClass:"welcomepic-uploader",attrs:{action:e.apiUploadWechatMedia,headers:e.headers,"show-file-list":!1,"on-success":e.handleWelcomepicSuccess,"before-upload":e.beforeUpload}},[e.form.wechat_welcome_img?a("img",{staticClass:"welcomepic",attrs:{src:e.form.wechat_welcome_img}}):a("i",{staticClass:"el-icon-plus welcomepic-uploader-icon"})])],1),a("el-form-item",{attrs:{label:"默认信息图"}},[a("el-upload",{staticClass:"infopic-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handleInfopicSuccess,"before-upload":e.beforeUpload}},[e.infopicUrl?a("img",{staticClass:"infopic",attrs:{src:e.infopicUrl}}):a("i",{staticClass:"el-icon-plus infopic-uploader-icon"})]),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),e._v(" 支持JPG、PNG格式，建议尺寸200*200 ")])],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v(" 保存 ")])],1)],1)],1)},n=[],s=a("5530"),l=(a("d3b7"),a("3ca3"),a("ddb0"),a("2b3d"),a("9861"),a("ac1f"),a("1276"),a("caad"),a("2532"),a("1063")),c=a("d722"),u=a("5f87"),d={data:function(){return{infoLoading:!0,headers:{admintoken:Object(u["e"])()},fileupload_size:"",fileupload_ext:"",apiUpload:window.global.RequestBaseUrl+c["a"].upload,apiUploadWechatMedia:window.global.RequestBaseUrl+c["a"].uploadWechatMedia,qrcodeUrl:"",infopicUrl:"",form:{wechat_name:"",wechat_code:"",wechat_qrcode:"",wechat_welcome_text:"",wechat_welcome_img:"",wechat_info_img:"",wechat_welcome_img_mediaid:""},rules:{}}},computed:{config:function(){return this.$store.state.config}},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext,this.fetchInfo()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0;var t={};Object(l["j"])(t,"get").then((function(t){var a=Object(s["a"])({},t.data),i=a.wechat_name,r=a.wechat_code,o=a.wechat_qrcode,n=a.wechat_welcome_text,l=a.wechat_welcome_img,c=a.wechat_welcome_img_mediaid,u=a.wechat_info_img;e.form={wechat_name:i,wechat_code:r,wechat_qrcode:o,wechat_welcome_text:n,wechat_welcome_img:l,wechat_welcome_img_mediaid:c,wechat_info_img:u},e.qrcodeUrl=t.data.qrcodeUrl,e.infopicUrl=t.data.infopicUrl,e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,a=Object(s["a"])({},this.form);this.$refs[e].validate((function(e){if(!e)return!1;Object(l["j"])(a).then((function(e){return t.$store.dispatch("config/getConfigInfo"),t.$message.success(e.message),!0})).catch((function(){}))}))},handleQrcodeSuccess:function(e,t){if(200!==e.code)return this.$message.error(e.message),!1;this.qrcodeUrl=URL.createObjectURL(t.raw),this.form.wechat_qrcode=e.data.file_id},handleWelcomepicSuccess:function(e,t){if(200!==e.code)return this.$message.error(e.message),!1;this.form.wechat_welcome_img=e.data.url,this.form.wechat_welcome_img_mediaid=e.data.media_id,this.$message.success(e.message)},handleInfopicSuccess:function(e,t){if(200!==e.code)return this.$message.error(e.message),!1;this.infopicUrl=URL.createObjectURL(t.raw),this.form.wechat_info_img=e.data.file_id},beforeUpload:function(e){var t=e.type.split("/"),a=t[1],i=this.fileupload_ext.split(",");return i.includes(a)?!(e.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)}}},f=d,m=(a("0720"),a("2877")),h=Object(m["a"])(f,o,n,!1,null,"483d9c0b",null),p=h.exports,g=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.listLoading,expression:"listLoading"}],attrs:{data:e.list}},[a("el-table-column",{attrs:{label:"关键词",prop:"word","min-width":"100"}}),a("el-table-column",{attrs:{label:"返回文字",prop:"return_text","min-width":"200"}}),a("el-table-column",{attrs:{align:"center",label:"返回图片","min-width":"110"},scopedSlots:e._u([{key:"default",fn:function(e){return[a("img",{attrs:{src:e.row.return_img,height:"100"}})]}}])}),a("el-table-column",{attrs:{label:"返回链接",prop:"return_link","min-width":"240"}}),a("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"150"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return e.funEdit(t.$index,t.row)}}},[e._v(" 编辑 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return e.funDelete(t.$index,t.row)}}},[e._v(" 删除 ")])]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-row",{attrs:{gutter:20}},[a("el-col",{attrs:{span:8}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:e.funAdd}},[e._v(" 添加 ")]),a("el-button",{attrs:{size:"small",type:"danger"}},[e._v(" 删除 ")])],1),a("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[a("el-pagination",{attrs:{background:"","current-page":e.currentPage,"page-sizes":[10,15,20,30,40],"page-size":e.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:e.total},on:{"size-change":e.handleSizeChange,"current-change":e.handleCurrentChange}})],1)],1),e.dialogFormVisible?a("el-dialog",{attrs:{title:e.dialogTitle,visible:e.dialogFormVisible,width:"30%","close-on-click-modal":!1},on:{"update:visible":function(t){e.dialogFormVisible=t}}},[a("el-form",{ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px",rules:e.rules,"inline-message":!1}},[a("el-form-item",{attrs:{label:"关键词",prop:"word"}},[a("el-input",{model:{value:e.form.word,callback:function(t){e.$set(e.form,"word",t)},expression:"form.word"}})],1),a("el-form-item",{attrs:{label:"返回文字",prop:"return_text"}},[a("el-input",{model:{value:e.form.return_text,callback:function(t){e.$set(e.form,"return_text",t)},expression:"form.return_text"}})],1),a("el-form-item",{attrs:{label:"返回图片",prop:"return_img"}},[a("el-upload",{staticClass:"logo-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handleSuccess,"before-upload":e.beforeUpload}},[e.form.return_img?a("img",{staticClass:"logo",attrs:{src:e.form.return_img}}):a("i",{staticClass:"el-icon-plus logo-uploader-icon"})]),e.form.return_img?a("el-button",{attrs:{type:"text"},on:{click:function(t){e.form.return_img="",e.form.return_img_mediaid=""}}},[e._v("[删除]")]):e._e()],1),a("el-form-item",{attrs:{label:"返回链接",prop:"return_link"}},[a("el-input",{model:{value:e.form.return_link,callback:function(t){e.$set(e.form,"return_link",t)},expression:"form.return_link"}})],1),a("el-form-item",{attrs:{label:" "}},[a("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v(" 保存 ")]),a("el-button",{on:{click:e.closeDialog}},[e._v(" 取 消 ")])],1)],1)],1):e._e()],1)},_=[],w=a("15fd"),b=a("b775");function v(e){return Object(b["a"])({url:c["a"].wechatKeywordList,method:"get",params:e})}function x(e){return Object(b["a"])({url:c["a"].wechatKeywordAdd,method:"post",data:e})}function y(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==t?Object(b["a"])({url:c["a"].wechatKeywordEdit,method:t,data:e}):Object(b["a"])({url:c["a"].wechatKeywordEdit,method:t,params:e})}function k(e){return Object(b["a"])({url:c["a"].wechatKeywordDelete,method:"post",data:e})}var U=["id"],z={data:function(){return{headers:{admintoken:Object(u["e"])()},fileupload_size:"",fileupload_ext:"",apiUpload:window.global.RequestBaseUrl+c["a"].uploadWechatMedia,dialogFormVisible:!1,infoLoading:!0,list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,form:{id:"",word:"",return_text:"",return_img:"",return_img_mediaid:"",return_link:""},rules:{word:[{required:!0,message:"请输入关键词",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"}]}}},computed:{config:function(){return this.$store.state.config}},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext,this.fetchData()},methods:{handleSizeChange:function(e){this.pagesize=e,this.fetchData()},handleCurrentChange:function(e){this.currentPage=e,this.fetchData()},fetchData:function(){var e=this;this.listLoading=!0;var t={page:this.currentPage,pagesize:this.pagesize};v(t).then((function(t){e.list=t.data.items,e.listLoading=!1,e.total=t.data.total,e.currentPage=t.data.current_page,e.pagesize=t.data.pagesize})).catch((function(){}))},addSave:function(e,t){var a=this;this.$refs[t].validate((function(t){if(!t)return!1;x(e).then((function(e){return a.$message.success(e.message),a.closeDialog(),a.fetchData(),!0})).catch((function(){}))}))},editSave:function(e,t){var a=this;this.$refs[t].validate((function(t){if(!t)return!1;y(e).then((function(e){return a.$message.success(e.message),a.closeDialog(),a.fetchData(),!0})).catch((function(){}))}))},onSubmit:function(e){var t=this,a=Object(s["a"])({},t.form);if(parseInt(a.id)>0)t.editSave(a,e);else{var i=a.id,r=Object(w["a"])(a,U);console.log(i),t.addSave(r,e)}},funAdd:function(){this.form={id:"",word:"",return_text:"",return_img:"",return_img_mediaid:"",return_link:""},this.dialogTitle="添加关键词",this.dialogFormVisible=!0},funEdit:function(e,t){this.form={id:t.id,word:t.word,return_text:t.return_text,return_img:t.return_img,return_img_mediaid:t.return_img_mediaid,return_link:t.return_link},this.dialogTitle="编辑关键词",this.dialogFormVisible=!0},funDelete:function(e,t){var a=this;a.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.id};k(e).then((function(e){return a.$message.success(e.message),a.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},closeDialog:function(){this.dialogFormVisible=!1},handleSuccess:function(e,t){if(200!==e.code)return this.$message.error(e.message),!1;this.form.return_img=e.data.url,this.form.return_img_mediaid=e.data.media_id},beforeUpload:function(e){var t=e.type.split("/"),a=t[1],i=this.fileupload_ext.split(",");return i.includes(a)?!(e.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)}}},C=z,$=(a("033e"),Object(m["a"])(C,g,_,!1,null,"3e99e29b",null)),O=$.exports,j={components:{configBase:p,configKeyword:O}},S=j,q=Object(m["a"])(S,i,r,!1,null,null,null);t["default"]=q.exports}}]);