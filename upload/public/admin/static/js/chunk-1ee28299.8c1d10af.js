(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-1ee28299"],{8012:function(e,t,r){"use strict";r.d(t,"d",(function(){return a})),r.d(t,"a",(function(){return s})),r.d(t,"c",(function(){return l})),r.d(t,"b",(function(){return n}));var i=r("b775"),o=r("d722");function a(e){return Object(i["a"])({url:o["a"].serviceOl,method:"get",params:e})}function s(e){return Object(i["a"])({url:o["a"].serviceOlAdd,method:"post",data:e})}function l(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"===t?Object(i["a"])({url:o["a"].serviceOlEdit,method:t,data:e}):Object(i["a"])({url:o["a"].serviceOlEdit,method:t,params:e})}function n(e){return Object(i["a"])({url:o["a"].serviceOlDelete,method:"post",data:e})}},"8fbe":function(e,t,r){"use strict";var i=r("db68"),o=r.n(i);o.a},"98d2":function(e,t,r){"use strict";r.r(t);var i=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("span",[e._v("添加在线客服")]),r("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(t){return e.goto("/sys/basic/serviceol")}}},[e._v(" 返回 ")]),r("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(t){return e.onSubmit("form")}}},[e._v(" 保存 ")])],1),r("el-form",{ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px","inline-message":!0,rules:e.rules}},[r("el-form-item",{attrs:{label:"姓名",prop:"name"}},[r("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),r("el-form-item",{attrs:{label:"电话",prop:"mobile"}},[r("el-input",{model:{value:e.form.mobile,callback:function(t){e.$set(e.form,"mobile",t)},expression:"form.mobile"}})],1),r("el-form-item",{attrs:{required:"",label:"微信",prop:"weixin"}},[r("el-upload",{staticClass:"thumb-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handleThumbSuccess,"before-upload":e.beforeThumbUpload}},[e.form.weixin?r("img",{staticClass:"thumb",attrs:{src:e.imageSrc}}):r("i",{staticClass:"el-icon-plus thumb-uploader-icon"})])],1),r("el-form-item",{attrs:{label:"QQ",prop:"qq"}},[r("el-input",{model:{value:e.form.qq,callback:function(t){e.$set(e.form,"qq",t)},expression:"form.qq"}})],1),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{model:{value:e.form.sort,callback:function(t){e.$set(e.form,"sort",t)},expression:"form.sort"}})],1),r("el-form-item",{attrs:{label:"是否显示",prop:"is_display"}},[r("el-radio-group",{model:{value:e.form.display,callback:function(t){e.$set(e.form,"display",t)},expression:"form.display"}},[r("el-radio",{attrs:{label:1}},[e._v("显示")]),r("el-radio",{attrs:{label:0}},[e._v("不显示")])],1)],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),r("el-button",{on:{click:function(t){return e.goto("/sys/basic/serviceol")}}},[e._v("返回")])],1)],1)],1)],1)},o=[],a=(r("caad"),r("d3b7"),r("ac1f"),r("2532"),r("3ca3"),r("1276"),r("ddb0"),r("2b3d"),r("5530")),s=r("8012"),l=r("5f87"),n=r("d722"),c={data:function(){var e=this,t=function(t,r,i){""==e.form.weixin&&""==r?i(new Error("请上传微信图片")):i()};return{headers:{admintoken:Object(l["e"])()},fileupload_size:"",fileupload_ext:"",apiUpload:window.global.RequestBaseUrl+n["a"].upload,form:{name:"",mobile:"",weixin:"",qq:"",sort:0,display:1},imageSrc:"",rules:{name:[{required:!0,message:"请输入姓名",trigger:"blur"}],mobile:[{required:!0,message:"请输入电话",trigger:"blur"}],weixin:[{validator:t,trigger:"blur"}],qq:[{required:!0,message:"请输入QQ",trigger:"blur"}]}}},computed:{config:function(){return this.$store.state.config}},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext},methods:{onSubmit:function(e){var t=this,r=this,i=Object(a["a"])({},this.form);this.$refs[e].validate((function(e){if(!e)return!1;Object(s["a"])(i).then((function(e){return t.$message.success(e.message),setTimeout((function(){r.$router.push("/sys/basic/serviceol")}),1500),!0})).catch((function(){}))}))},goto:function(e){this.$router.push(e)},handleThumbSuccess:function(e,t){if(200!==parseInt(e.code))return this.$message.error(e.message),!1;this.imageSrc=URL.createObjectURL(t.raw),this.form.weixin=e.data.file_id},beforeThumbUpload:function(e){var t=e.type.split("/"),r=t[1],i=this.fileupload_ext.split(",");return i.includes(r)?!(e.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)}}},u=c,f=(r("8fbe"),r("2877")),m=Object(f["a"])(u,i,o,!1,null,"40cd3819",null);t["default"]=m.exports},db68:function(e,t,r){}}]);