(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-20aab830"],{"52b5":function(t,e,a){"use strict";a.d(e,"a",(function(){return i}));var o=a("b775"),r=a("d722");function i(t){return Object(o["a"])({url:r["a"].getClassify,method:"get",params:t})}},"56fd":function(t,e,a){},"5def":function(t,e,a){},"8dbb":function(t,e,a){"use strict";a("56fd")},"97eb":function(t,e,a){"use strict";a.d(e,"i",(function(){return i})),a.d(e,"g",(function(){return s})),a.d(e,"f",(function(){return l})),a.d(e,"h",(function(){return n})),a.d(e,"b",(function(){return c})),a.d(e,"n",(function(){return u})),a.d(e,"e",(function(){return d})),a.d(e,"c",(function(){return m})),a.d(e,"j",(function(){return f})),a.d(e,"d",(function(){return p})),a.d(e,"o",(function(){return b})),a.d(e,"l",(function(){return h})),a.d(e,"k",(function(){return _})),a.d(e,"p",(function(){return g})),a.d(e,"a",(function(){return j})),a.d(e,"m",(function(){return C}));var o=a("b775"),r=a("d722");function i(t){return Object(o["a"])({url:r["a"].jobFairListOl,method:"get",params:t})}function s(t){return Object(o["a"])({url:r["a"].jobFairListOlDelete,method:"post",data:t})}function l(t){return Object(o["a"])({url:r["a"].jobFairListOlAdd,method:"post",data:t})}function n(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(o["a"])({url:r["a"].jobFairListOlEdit,method:e,data:t}):Object(o["a"])({url:r["a"].jobFairListOlEdit,method:e,params:t})}function c(t){return Object(o["a"])({url:r["a"].jobFairListOlCompanyList,method:"get",params:t})}function u(t){return Object(o["a"])({url:r["a"].jobFairListOlPersonalList,method:"get",params:t})}function d(t){return Object(o["a"])({url:r["a"].jobFairListOlSticky,method:"post",data:t})}function m(t){return Object(o["a"])({url:r["a"].jobFairListOlQrcode,method:"post",data:t})}function f(t){return Object(o["a"])({url:r["a"].jobFairListOlParAdd,method:"post",data:t})}function p(t){return Object(o["a"])({url:r["a"].jobFairListOlCompanySearch,method:"get",params:t})}function b(t){return Object(o["a"])({url:r["a"].jobFairListOlPersonalSearch,method:"get",params:t})}function h(t){return Object(o["a"])({url:r["a"].jobFairListOlStatus,method:"post",data:t})}function _(t){return Object(o["a"])({url:r["a"].jobFairListOlParticipateDelete,method:"post",data:t})}function g(t){return Object(o["a"])({url:r["a"].jobFairListOlQrService,method:"post",data:t})}function j(t){return Object(o["a"])({url:r["a"].jobFairListOlComBatchAdd,method:"post",data:t})}function C(t){return Object(o["a"])({url:r["a"].jobFairListOlPerBatchAdd,method:"post",data:t})}},c7a3:function(t,e,a){"use strict";a.r(e);var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[t._v("发布网络招聘会")]),a("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/jobfairol/list")}}},[t._v(" 返回 ")]),a("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),a("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"120px",rules:t.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"标题",prop:"title"}},[a("el-input",{model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}}),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),t._v(" (如：2017年5月1日医药行业主题招聘会) ")])],1),a("el-form-item",{attrs:{label:"举办日期",prop:"holddate"}},[a("el-date-picker",{attrs:{type:"datetimerange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期",format:"yyyy-MM-dd HH:mm:ss"},model:{value:t.form.holddate,callback:function(e){t.$set(t.form,"holddate",e)},expression:"form.holddate"}})],1),a("el-form-item",{attrs:{label:"招聘会介绍",required:"",prop:"content"}},[a("div",{staticStyle:{border:"1px solid #ccc"}},[a("Toolbar",{staticStyle:{"border-bottom":"1px solid #ccc"},attrs:{editor:t.editor,defaultConfig:t.toolbarConfig}}),a("Editor",{staticStyle:{height:"400px","overflow-y":"hidden"},on:{onCreated:t.onCreated},model:{value:t.form.content,callback:function(e){t.$set(t.form,"content",e)},expression:"form.content"}})],1)]),a("el-form-item",{attrs:{required:"",label:"允许报名套餐",prop:"enable_setmeal_id"}},[a("el-checkbox-group",{model:{value:t.form.enable_setmeal_id,callback:function(e){t.$set(t.form,"enable_setmeal_id",e)},expression:"form.enable_setmeal_id"}},t._l(t.options_setmeal,(function(e,o){return a("el-checkbox",{key:o,attrs:{label:e.id}},[t._v(t._s(e.name))])})),1)],1),a("el-form-item",{attrs:{label:"仅认证企业报名"}},[a("el-switch",{model:{value:t.form.must_company_audit,callback:function(e){t.$set(t.form,"must_company_audit",e)},expression:"form.must_company_audit"}})],1),a("el-form-item",{attrs:{label:"要求简历完整度"}},[a("el-input",{attrs:{type:"number"},model:{value:t.form.min_complete_percent,callback:function(e){t.$set(t.form,"min_complete_percent",e)},expression:"form.min_complete_percent"}})],1),a("el-form-item",{attrs:{required:"",label:"联系客服",prop:"qrcode"}},[a("el-upload",{staticClass:"thumb-uploader",attrs:{action:t.apiUpload,headers:t.headers,"show-file-list":!1,"on-success":t.handleQrSuccess,"before-upload":t.beforeThumbUpload}},[t.form.qrcode?a("img",{staticClass:"thumb",attrs:{src:t.qrcodeUrl}}):a("i",{staticClass:"el-icon-plus thumb-uploader-icon"})])],1),a("el-form-item",{attrs:{label:"上传图片"}},[a("div",{staticClass:"upload-wrapper"},[a("div",{staticClass:"upload-item"},[a("el-upload",{staticClass:"thumb-uploader",attrs:{action:t.apiUpload,headers:t.headers,"show-file-list":!1,"on-success":t.handleThumbSuccess,"before-upload":t.beforeThumbUpload}},[t.form.thumb?a("img",{staticClass:"thumb",attrs:{src:t.thumbUrl}}):a("i",{staticClass:"el-icon-plus thumb-uploader-icon"})]),a("div",{staticClass:"smalltips"},[t._v(" 缩略图 ")]),a("div",{staticClass:"sp"},[a("i",{staticClass:"el-icon-info"}),t._v(" 建议尺寸：245*145 ")])],1),a("div",{staticClass:"upload-item"},[a("el-upload",{staticClass:"thumb-uploader",attrs:{action:t.apiUpload,headers:t.headers,"show-file-list":!1,"on-success":t.handlePcSuccess,"before-upload":t.beforeThumbUpload}},[t.form.pc_header_logo?a("img",{staticClass:"thumb",attrs:{src:t.pc_header_logo_url}}):a("i",{staticClass:"el-icon-plus thumb-uploader-icon"})]),a("span",{staticClass:"smalltips"},[t._v(" pc头图 ")]),a("span",{staticClass:"sp"},[a("i",{staticClass:"el-icon-info"}),t._v(" 建议尺寸：750*280 ")])],1),a("div",{staticClass:"upload-item"},[a("el-upload",{staticClass:"thumb-uploader",attrs:{action:t.apiUpload,headers:t.headers,"show-file-list":!1,"on-success":t.handleMobileSuccess,"before-upload":t.beforeThumbUpload}},[t.form.mobile_header_logo?a("img",{staticClass:"thumb",attrs:{src:t.mobile_header_logo_url}}):a("i",{staticClass:"el-icon-plus thumb-uploader-icon"})]),a("span",{staticClass:"smalltips "},[t._v(" 触屏头图 ")]),a("span",{staticClass:" sp"},[a("i",{staticClass:"el-icon-info"}),t._v(" 建议尺寸：750*280 ")])],1)])]),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),a("el-button",{on:{click:function(e){return t.goto("/jobfairol/list")}}},[t._v("返回")])],1)],1)],1)],1)},r=[],i=a("2909"),s=(a("af93"),a("d3b7"),a("3ca3"),a("ddb0"),a("2b3d"),a("9861"),a("ac1f"),a("1276"),a("caad"),a("2532"),a("a15b"),a("5f87")),l=a("97eb"),n=a("d722"),c=a("52b5"),u=(a("560e"),a("4e15")),d={components:{Editor:u["a"],Toolbar:u["b"]},data:function(){var t=this,e=function(e,a,o){a=t.editor.getText(),""===a?o(new Error("请输入招聘会介绍")):o()},a=function(e,a,o){a=t.form.qrcode,""===a?o(new Error("请上传客服二维码")):o()};return{editor:null,toolbarConfig:{excludeKeys:["fullScreen"]},thumbUrl:"",qrcodeUrl:"",pc_header_logo_url:"",mobile_header_logo_url:"",apiUpload:window.global.RequestBaseUrl+n["a"].upload,headers:{admintoken:Object(s["e"])()},fileupload_size:"",fileupload_ext:"",options_setmeal:[],form:{title:"",thumb:"",pc_header_logo:"",mobile_header_logo:"",holddate:"",content:"",enable_setmeal_id:[],must_company_audit:!1,min_complete_percent:"",qrcode:""},rules:{title:[{required:!0,message:"请填写标题",trigger:"blur"},{max:200,message:"长度在 1 到 200 个字符",trigger:"blur"}],holddate:[{required:!0,message:"请选择举办日期",trigger:"blur"}],content:[{validator:e,trigger:"blur"}],enable_setmeal_id:[{required:!0,message:"请选择允许报名套餐"}],qrcode:[{validator:a}]}}},computed:{config:function(){return this.$store.state.config}},mounted:function(){this.getSetmeal()},beforeDestroy:function(){var t=this.editor;null!=t&&t.destroy()},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext},methods:{onCreated:function(t){this.editor=Object.seal(t),this.editor.getMenuConfig("uploadImage").headers={admintoken:Object(s["e"])()},this.editor.getMenuConfig("uploadImage").withCredentials=!0,this.editor.getMenuConfig("uploadImage").server=window.global.RequestBaseUrl+n["a"].uploadEditor,this.editor.getMenuConfig("uploadVideo").headers={admintoken:Object(s["e"])()},this.editor.getMenuConfig("uploadVideo").withCredentials=!0,this.editor.getMenuConfig("uploadVideo").server=window.global.RequestBaseUrl+n["a"].uploadEditorVideo},handleThumbSuccess:function(t,e){if(200!==t.code)return this.$message.error(t.message),!1;this.thumbUrl=URL.createObjectURL(e.raw),this.form.thumb=t.data.file_id},handlePcSuccess:function(t,e){if(200!==t.code)return this.$message.error(t.message),!1;this.pc_header_logo_url=URL.createObjectURL(e.raw),this.form.pc_header_logo=t.data.file_id},handleMobileSuccess:function(t,e){if(200!==t.code)return this.$message.error(t.message),!1;this.mobile_header_logo_url=URL.createObjectURL(e.raw),this.form.mobile_header_logo=t.data.file_id},handleQrSuccess:function(t,e){if(200!==t.code)return this.$message.error(t.message),!1;this.qrcodeUrl=URL.createObjectURL(e.raw),this.form.qrcode=t.data.file_id},beforeThumbUpload:function(t){var e=t.type.split("/"),a=e[1],o=this.fileupload_ext.split(",");return o.includes(a)?!(t.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)},onSubmit:function(t){var e=this,a=this;this.form.content=this.editor.getHtml();var o=Object.assign({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;o["starttime"]=new Date(o.holddate[0]).getTime()/1e3,o["endtime"]=new Date(o.holddate[1]).getTime()/1e3,o["must_company_audit"]=!0===o.must_company_audit?1:0,o["enable_setmeal_id"]=o.enable_setmeal_id.join(","),Object(l["f"])(o).then((function(t){return e.$message.success(t.message),setTimeout((function(){a.$router.push("/jobfairol/list")}),1500),!0})).catch((function(){}))}))},goto:function(t){this.$router.push(t)},getSetmeal:function(){var t=this;Object(c["a"])({type:"setmealList"}).then((function(e){t.options_setmeal=Object(i["a"])(e.data)})).catch((function(){}))}}},m=d,f=(a("8dbb"),a("e47d"),a("2877")),p=Object(f["a"])(m,o,r,!1,null,"3675f248",null);e["default"]=p.exports},e47d:function(t,e,a){"use strict";a("5def")}}]);