(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-b5b11a78"],{"091d":function(e,t,a){},"23dd":function(e,t,a){},"52b5":function(e,t,a){"use strict";a.d(t,"a",(function(){return s}));var o=a("b775"),i=a("d722");function s(e){return Object(o["a"])({url:i["a"].getClassify,method:"get",params:e})}},"5e75":function(e,t,a){"use strict";a.r(t);var o=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[e._v("发布网络招聘会")]),a("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(t){return e.goto("/jobfairol/list")}}},[e._v(" 返回 ")]),a("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text",disabled:e.issubmit},on:{click:function(t){return e.onSubmit("form")}}},[e._v(" 保存 ")])],1),a("el-form",{ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px",rules:e.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"标题",prop:"title"}},[a("el-input",{model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}}),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),e._v(" (如：2017年5月1日医药行业主题招聘会) ")])],1),a("el-form-item",{attrs:{label:"举办日期",prop:"holddate"}},[a("el-date-picker",{attrs:{type:"datetimerange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期",format:"yyyy-MM-dd HH:mm:ss"},model:{value:e.form.holddate,callback:function(t){e.$set(e.form,"holddate",t)},expression:"form.holddate"}})],1),a("el-form-item",{attrs:{label:"招聘会介绍",required:"",prop:"content"}},[a("div",{staticStyle:{border:"1px solid #ccc"}},[a("Toolbar",{staticStyle:{"border-bottom":"1px solid #ccc"},attrs:{editor:e.editor,"default-config":e.toolbarConfig}}),a("Editor",{staticStyle:{height:"400px","overflow-y":"hidden"},on:{onCreated:e.onCreated},model:{value:e.form.content,callback:function(t){e.$set(e.form,"content",t)},expression:"form.content"}})],1)]),a("el-form-item",{attrs:{required:"",label:"允许报名套餐",prop:"enable_setmeal_id"}},[a("el-checkbox-group",{model:{value:e.form.enable_setmeal_id,callback:function(t){e.$set(e.form,"enable_setmeal_id",t)},expression:"form.enable_setmeal_id"}},e._l(e.options_setmeal,(function(t,o){return a("el-checkbox",{key:o,attrs:{label:t.id}},[e._v(e._s(t.name))])})),1)],1),a("el-form-item",{attrs:{label:"仅认证企业报名"}},[a("el-switch",{model:{value:e.form.must_company_audit,callback:function(t){e.$set(e.form,"must_company_audit",t)},expression:"form.must_company_audit"}})],1),a("el-form-item",{attrs:{label:"要求简历完整度"}},[a("el-input",{attrs:{type:"number",min:0},model:{value:e.form.min_complete_percent,callback:function(t){e.$set(e.form,"min_complete_percent",t)},expression:"form.min_complete_percent"}})],1),a("el-form-item",{attrs:{label:"浏览次数"}},[a("el-input",{attrs:{type:"number",min:0},model:{value:e.form.click,callback:function(t){e.$set(e.form,"click",t)},expression:"form.click"}})],1),a("el-form-item",{attrs:{required:"",label:"联系客服",prop:"qrcode"}},[a("el-upload",{staticClass:"thumb-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handleQrSuccess,"before-upload":e.beforeThumbUpload}},[e.form.qrcode?a("img",{staticClass:"thumb",attrs:{src:e.qrcodeUrl}}):a("i",{staticClass:"el-icon-plus thumb-uploader-icon"})])],1),a("el-form-item",{attrs:{label:"上传图片",prop:"thumb"}},[a("div",{staticClass:"upload-wrapper"},[a("div",{staticClass:"upload-item"},[a("el-upload",{staticClass:"thumb-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handleThumbSuccess,"before-upload":e.beforeThumbUpload}},[e.form.thumb?a("img",{staticClass:"thumb",attrs:{src:e.thumbUrl}}):a("i",{staticClass:"el-icon-plus thumb-uploader-icon"})]),a("div",{staticClass:"smalltips"},[e._v(" 缩略图 ")]),a("div",{staticClass:"sp"},[a("i",{staticClass:"el-icon-info"}),e._v(" 建议尺寸：245*145 ")])],1),a("div",{staticClass:"upload-item"},[a("el-upload",{staticClass:"thumb-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handlePcSuccess,"before-upload":e.beforeThumbUpload}},[e.form.pc_header_logo?a("img",{staticClass:"thumb",attrs:{src:e.pc_header_logo_url}}):a("i",{staticClass:"el-icon-plus thumb-uploader-icon"})]),a("span",{staticClass:"smalltips"},[e._v(" pc头图 ")]),a("span",{staticClass:"sp"},[a("i",{staticClass:"el-icon-info"}),e._v(" 建议尺寸：1200*330 ")])],1),a("div",{staticClass:"upload-item"},[a("el-upload",{staticClass:"thumb-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handleMobileSuccess,"before-upload":e.beforeThumbUpload}},[e.form.mobile_header_logo?a("img",{staticClass:"thumb",attrs:{src:e.mobile_header_logo_url}}):a("i",{staticClass:"el-icon-plus thumb-uploader-icon"})]),a("span",{staticClass:"smalltips "},[e._v(" 触屏头图 ")]),a("span",{staticClass:" sp"},[a("i",{staticClass:"el-icon-info"}),e._v(" 建议尺寸：750*280 ")])],1)])]),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary",disabled:e.issubmit},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),a("el-button",{on:{click:function(t){return e.goto("/jobfairol/list")}}},[e._v("返回")])],1)],1)],1)],1)},i=[],s=a("2909"),r=(a("af93"),a("ac1f"),a("1276"),a("d81d"),a("d3b7"),a("3ca3"),a("ddb0"),a("2b3d"),a("9861"),a("caad"),a("2532"),a("a15b"),a("5f87")),l=a("97eb"),n=a("d722"),c=a("52b5"),d=(a("560e"),a("4e15")),u={components:{Editor:d["a"],Toolbar:d["b"]},data:function(){var e=this,t=function(t,a,o){a=e.editor.getText(),""===a?o(new Error("请输入招聘会介绍")):o()},a=function(t,a,o){a=e.form.qrcode,""===a?o(new Error("请上传客服二维码")):o()};return{issubmit:!1,editor:null,toolbarConfig:{excludeKeys:["fullScreen"]},thumbUrl:"",qrcodeUrl:"",pc_header_logo_url:"",mobile_header_logo_url:"",apiUpload:window.global.RequestBaseUrl+n["a"].upload,headers:{admintoken:Object(r["e"])()},fileupload_size:"",fileupload_ext:"",options_setmeal:[],form:{id:"",title:"",thumb:"",pc_header_logo:"",mobile_header_logo:"",holddate:"",content:"",enable_setmeal_id:[],must_company_audit:!1,min_complete_percent:"",qrcode:"",click:0},rules:{title:[{required:!0,message:"请填写标题",trigger:"blur"},{max:200,message:"长度在 1 到 200 个字符",trigger:"blur"}],holddate:[{required:!0,message:"请选择举办日期",trigger:"blur"}],content:[{validator:t,trigger:"blur"}],enable_setmeal_id:[{required:!0,message:"请选择允许报名套餐",trigger:"blur"}],qrcode:[{validator:a}]}}},computed:{config:function(){return this.$store.state.config}},mounted:function(){this.getSetmeal(),this.getJobfairOnline(),this.form.id=this.$route.query.id},beforeDestroy:function(){var e=this.editor;null!=e&&e.destroy()},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext},methods:{onCreated:function(e){this.editor=Object.seal(e),this.editor.getMenuConfig("uploadImage").headers={admintoken:Object(r["e"])()},this.editor.getMenuConfig("uploadImage").withCredentials=!0,this.editor.getMenuConfig("uploadImage").server=window.global.RequestBaseUrl+n["a"].uploadEditor,this.editor.getMenuConfig("uploadVideo").headers={admintoken:Object(r["e"])()},this.editor.getMenuConfig("uploadVideo").withCredentials=!0,this.editor.getMenuConfig("uploadVideo").server=window.global.RequestBaseUrl+n["a"].uploadEditorVideo},getJobfairOnline:function(){var e=this;this.infoLoading=!0;var t={id:this.$route.query.id};Object(l["j"])(t,"get").then((function(t){e.form=Object.assign(e.form,t.data.info),e.form.holddate=[1e3*e.form.starttime,1e3*e.form.endtime],e.form.enable_setmeal_id=e.form.enable_setmeal_id.split(","),e.form.enable_setmeal_id=e.form.enable_setmeal_id.map((function(e){return parseInt(e)})),e.form.must_company_audit=1===parseInt(e.form.must_company_audit),e.thumbUrl=t.data.thumbUrl,e.qrcodeUrl=t.data.qrcodeUrl,e.mobile_header_logo_url=t.data.mobile_header_logo_url,e.pc_header_logo_url=t.data.pc_header_logo_url,e.infoLoading=!1})).catch((function(){}))},handleThumbSuccess:function(e,t){if(200!==e.code)return this.$message.error(e.message),!1;this.thumbUrl=URL.createObjectURL(t.raw),this.form.thumb=e.data.file_id},handlePcSuccess:function(e,t){if(200!==e.code)return this.$message.error(e.message),!1;this.pc_header_logo_url=URL.createObjectURL(t.raw),this.form.pc_header_logo=e.data.file_id},handleMobileSuccess:function(e,t){if(200!==e.code)return this.$message.error(e.message),!1;this.mobile_header_logo_url=URL.createObjectURL(t.raw),this.form.mobile_header_logo=e.data.file_id},handleQrSuccess:function(e,t){if(200!==e.code)return this.$message.error(e.message),!1;this.qrcodeUrl=URL.createObjectURL(t.raw),this.form.qrcode=e.data.file_id},beforeThumbUpload:function(e){var t=e.type.split("/"),a=t[1],o=this.fileupload_ext.split(",");return o.includes(a)?!(e.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)},onSubmit:function(e){var t=this;this.issubmit=!0;var a=this;this.form.content=this.editor.getHtml();var o=Object.assign({},this.form);this.$refs[e].validate((function(e){if(!e)return a.issubmit=!1,!1;o["starttime"]=new Date(o.holddate[0]).getTime()/1e3,o["endtime"]=new Date(o.holddate[1]).getTime()/1e3,o["must_company_audit"]=!0===o.must_company_audit?1:0,o["enable_setmeal_id"]=o.enable_setmeal_id.join(","),Object(l["j"])(o).then((function(e){return t.$message.success(e.message),setTimeout((function(){a.$router.push("/jobfairol/list")}),1500),!0})).catch((function(){a.issubmit=!1}))}))},goto:function(e){this.$router.push(e)},getSetmeal:function(){var e=this;Object(c["a"])({type:"setmealList"}).then((function(t){e.options_setmeal=Object(s["a"])(t.data)})).catch((function(){}))}}},m=u,f=(a("6622"),a("c513"),a("2877")),p=Object(f["a"])(m,o,i,!1,null,"1b513cea",null);t["default"]=p.exports},6622:function(e,t,a){"use strict";a("091d")},c513:function(e,t,a){"use strict";a("23dd")}}]);