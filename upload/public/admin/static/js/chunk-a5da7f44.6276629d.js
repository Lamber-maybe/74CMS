(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-a5da7f44"],{"52b5":function(t,e,r){"use strict";r.d(e,"a",(function(){return i}));var a=r("b775"),o=r("d722");function i(t){return Object(a["a"])({url:o["a"].getClassify,method:"get",params:t})}},"56fd":function(t,e,r){},"7bb7":function(t,e,r){},"8dbb":function(t,e,r){"use strict";var a=r("56fd"),o=r.n(a);o.a},"97eb":function(t,e,r){"use strict";r.d(e,"i",(function(){return i})),r.d(e,"g",(function(){return n})),r.d(e,"f",(function(){return s})),r.d(e,"h",(function(){return l})),r.d(e,"b",(function(){return u})),r.d(e,"n",(function(){return c})),r.d(e,"e",(function(){return d})),r.d(e,"c",(function(){return m})),r.d(e,"j",(function(){return f})),r.d(e,"d",(function(){return b})),r.d(e,"o",(function(){return p})),r.d(e,"l",(function(){return h})),r.d(e,"k",(function(){return g})),r.d(e,"p",(function(){return _})),r.d(e,"a",(function(){return j})),r.d(e,"m",(function(){return O}));var a=r("b775"),o=r("d722");function i(t){return Object(a["a"])({url:o["a"].jobFairListOl,method:"get",params:t})}function n(t){return Object(a["a"])({url:o["a"].jobFairListOlDelete,method:"post",data:t})}function s(t){return Object(a["a"])({url:o["a"].jobFairListOlAdd,method:"post",data:t})}function l(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(a["a"])({url:o["a"].jobFairListOlEdit,method:e,data:t}):Object(a["a"])({url:o["a"].jobFairListOlEdit,method:e,params:t})}function u(t){return Object(a["a"])({url:o["a"].jobFairListOlCompanyList,method:"get",params:t})}function c(t){return Object(a["a"])({url:o["a"].jobFairListOlPersonalList,method:"get",params:t})}function d(t){return Object(a["a"])({url:o["a"].jobFairListOlSticky,method:"post",data:t})}function m(t){return Object(a["a"])({url:o["a"].jobFairListOlQrcode,method:"post",data:t})}function f(t){return Object(a["a"])({url:o["a"].jobFairListOlParAdd,method:"post",data:t})}function b(t){return Object(a["a"])({url:o["a"].jobFairListOlCompanySearch,method:"get",params:t})}function p(t){return Object(a["a"])({url:o["a"].jobFairListOlPersonalSearch,method:"get",params:t})}function h(t){return Object(a["a"])({url:o["a"].jobFairListOlStatus,method:"post",data:t})}function g(t){return Object(a["a"])({url:o["a"].jobFairListOlParticipateDelete,method:"post",data:t})}function _(t){return Object(a["a"])({url:o["a"].jobFairListOlQrService,method:"post",data:t})}function j(t){return Object(a["a"])({url:o["a"].jobFairListOlComBatchAdd,method:"post",data:t})}function O(t){return Object(a["a"])({url:o["a"].jobFairListOlPerBatchAdd,method:"post",data:t})}},a15b:function(t,e,r){"use strict";var a=r("23e7"),o=r("44ad"),i=r("fc6a"),n=r("a640"),s=[].join,l=o!=Object,u=n("join",",");a({target:"Array",proto:!0,forced:l||!u},{join:function(t){return s.call(i(this),void 0===t?",":t)}})},c7a3:function(t,e,r){"use strict";r.r(e);var a=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"app-container"},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("span",[t._v("发布网络招聘会")]),r("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/jobfairol/list")}}},[t._v(" 返回 ")]),r("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),r("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"120px",rules:t.rules,"inline-message":!0}},[r("el-form-item",{attrs:{label:"标题",prop:"title"}},[r("el-input",{model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}}),r("span",{staticClass:"smalltip"},[r("i",{staticClass:"el-icon-info"}),t._v(" (如：2017年5月1日医药行业主题招聘会) ")])],1),r("el-form-item",{attrs:{label:"举办日期",prop:"holddate"}},[r("el-date-picker",{attrs:{type:"datetimerange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期",format:"yyyy-MM-dd HH:mm:ss"},model:{value:t.form.holddate,callback:function(e){t.$set(t.form,"holddate",e)},expression:"form.holddate"}})],1),r("el-form-item",{attrs:{required:"",label:"缩略图",prop:"thumb"}},[r("el-upload",{staticClass:"thumb-uploader",attrs:{action:t.apiUpload,headers:t.headers,"show-file-list":!1,"on-success":t.handleThumbSuccess,"before-upload":t.beforeThumbUpload}},[t.form.thumb?r("img",{staticClass:"thumb",attrs:{src:t.thumbUrl}}):r("i",{staticClass:"el-icon-plus thumb-uploader-icon"})])],1),r("el-form-item",{attrs:{label:"招聘会介绍",required:"",prop:"content"}},[r("div",{staticClass:"editor",attrs:{id:"editor"}})]),r("el-form-item",{attrs:{required:"",label:"允许报名套餐",prop:"enable_setmeal_id"}},[r("el-checkbox-group",{model:{value:t.form.enable_setmeal_id,callback:function(e){t.$set(t.form,"enable_setmeal_id",e)},expression:"form.enable_setmeal_id"}},t._l(t.options_setmeal,(function(e,a){return r("el-checkbox",{key:a,attrs:{label:e.id}},[t._v(t._s(e.name))])})),1)],1),r("el-form-item",{attrs:{label:"仅认证企业报名"}},[r("el-switch",{model:{value:t.form.must_company_audit,callback:function(e){t.$set(t.form,"must_company_audit",e)},expression:"form.must_company_audit"}})],1),r("el-form-item",{attrs:{label:"要求简历完整度"}},[r("el-input",{attrs:{type:"number"},model:{value:t.form.min_complete_percent,callback:function(e){t.$set(t.form,"min_complete_percent",e)},expression:"form.min_complete_percent"}})],1),r("el-form-item",{attrs:{required:"",label:"联系客服",prop:"qrcode"}},[r("el-upload",{staticClass:"thumb-uploader",attrs:{action:t.apiUpload,headers:t.headers,"show-file-list":!1,"on-success":t.handleQrSuccess,"before-upload":t.beforeThumbUpload}},[t.form.qrcode?r("img",{staticClass:"thumb",attrs:{src:t.qrcodeUrl}}):r("i",{staticClass:"el-icon-plus thumb-uploader-icon"})])],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),r("el-button",{on:{click:function(e){return t.goto("/jobfairol/list")}}},[t._v("返回")])],1)],1)],1)],1)},o=[],i=(r("caad"),r("a15b"),r("d3b7"),r("ac1f"),r("2532"),r("3ca3"),r("1276"),r("ddb0"),r("2b3d"),r("2909")),n=r("5f87"),s=r("97eb"),l=r("6fad"),u=r.n(l),c=r("d722"),d=r("52b5"),m={data:function(){var t=this,e=function(e,r,a){r=t.editor.txt.text(),""===r?a(new Error("请输入招聘会介绍")):a()},r=function(e,r,a){r=t.form.thumb,""===r?a(new Error("请上传缩略图")):a()},a=function(e,r,a){r=t.form.qrcode,""===r?a(new Error("请上传客服二维码")):a()};return{thumbUrl:"",qrcodeUrl:"",apiUpload:window.global.RequestBaseUrl+c["a"].upload,headers:{admintoken:Object(n["e"])()},fileupload_size:"",fileupload_ext:"",editor:"",options_setmeal:[],form:{title:"",thumb:"",holddate:"",content:"",enable_setmeal_id:[],must_company_audit:!1,min_complete_percent:"",qrcode:""},rules:{title:[{required:!0,message:"请填写标题",trigger:"blur"},{max:200,message:"长度在 1 到 200 个字符",trigger:"blur"}],holddate:[{required:!0,message:"请选择举办日期",trigger:"blur"}],content:[{validator:e,trigger:"blur"}],thumb:[{validator:r}],enable_setmeal_id:[{required:!0,message:"请选择允许报名套餐"}],qrcode:[{validator:a}]}}},computed:{config:function(){return this.$store.state.config}},mounted:function(){this.editor=new u.a("#editor"),this.editor.config.uploadImgServer=window.global.RequestBaseUrl+c["a"].uploadEditor,this.editor.config.uploadImgHeaders={admintoken:Object(n["e"])()},this.editor.config.zIndex=0,this.editor.config.pasteFilterStyle=!1,this.editor.create(),this.getSetmeal()},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext},methods:{handleThumbSuccess:function(t,e){if(200!==t.code)return this.$message.error(t.message),!1;this.thumbUrl=URL.createObjectURL(e.raw),this.form.thumb=t.data.file_id},handleQrSuccess:function(t,e){if(200!==t.code)return this.$message.error(t.message),!1;this.qrcodeUrl=URL.createObjectURL(e.raw),this.form.qrcode=t.data.file_id},beforeThumbUpload:function(t){var e=t.type.split("/"),r=e[1],a=this.fileupload_ext.split(",");return a.includes(r)?!(t.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)},onSubmit:function(t){var e=this,r=this;this.form.content=this.editor.txt.html();var a=Object.assign({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;a["starttime"]=new Date(a.holddate[0]).getTime()/1e3,a["endtime"]=new Date(a.holddate[1]).getTime()/1e3,a["must_company_audit"]=!0===a.must_company_audit?1:0,a["enable_setmeal_id"]=a.enable_setmeal_id.join(","),Object(s["f"])(a).then((function(t){return e.$message.success(t.message),setTimeout((function(){r.$router.push("/jobfairol/list")}),1500),!0})).catch((function(){}))}))},goto:function(t){this.$router.push(t)},getSetmeal:function(){var t=this;Object(d["a"])({type:"setmealList"}).then((function(e){t.options_setmeal=Object(i["a"])(e.data)})).catch((function(){}))}}},f=m,b=(r("8dbb"),r("d426"),r("2877")),p=Object(b["a"])(f,a,o,!1,null,"d6550572",null);e["default"]=p.exports},d426:function(t,e,r){"use strict";var a=r("7bb7"),o=r.n(a);o.a}}]);