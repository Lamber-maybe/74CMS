(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0e78223a"],{1487:function(e,t,r){"use strict";r("6d98")},"4d84":function(e,t,r){"use strict";r.r(t);var a=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("span",[e._v("添加广告")]),r("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(t){return e.goto("/content/ad/list")}}},[e._v(" 返回 ")]),r("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text",disabled:e.issubmit},on:{click:function(t){return e.onSubmit("form")}}},[e._v(" 保存 ")])],1),r("el-form",{ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px",rules:e.rules,"inline-message":!0}},[r("el-form-item",{attrs:{label:"广告标题",prop:"title"}},[r("el-input",{model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}})],1),r("el-form-item",{attrs:{label:"广告位",prop:"cid"}},[r("el-cascader",{attrs:{options:e.adCategory,props:{expandTrigger:"hover"}},on:{change:e.handleChange},model:{value:e.form.cid,callback:function(t){e.$set(e.form,"cid",t)},expression:"form.cid"}})],1),r("el-form-item",{attrs:{required:"",label:"本地上传"}},[r("el-upload",{staticClass:"thumb-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handleThumbSuccess,"before-upload":e.beforeThumbUpload}},[e.form.imageid?r("img",{staticClass:"thumb",attrs:{src:e.imageSrc}}):r("i",{staticClass:"el-icon-plus thumb-uploader-icon"})]),""!=e.size_text?r("span",{staticClass:"smalltip"},[r("i",{staticClass:"el-icon-info"}),e._v(" 建议尺寸"+e._s(e.size_text)+" ")]):e._e()],1),r("el-form-item",{attrs:{label:"或图片地址",prop:"imageurl"}},[r("el-input",{model:{value:e.form.imageurl,callback:function(t){e.$set(e.form,"imageurl",t)},expression:"form.imageurl"}})],1),r("el-form-item",{attrs:{label:"图片说明文字",prop:"explain"}},[r("el-input",{model:{value:e.form.explain,callback:function(t){e.$set(e.form,"explain",t)},expression:"form.explain"}})],1),r("el-form-item",{attrs:{label:"开始时间",prop:"starttime"}},[r("el-date-picker",{attrs:{type:"datetime",placeholder:"请选择开始时间",format:"yyyy-MM-dd HH:mm"},model:{value:e.form.starttime,callback:function(t){e.$set(e.form,"starttime",t)},expression:"form.starttime"}})],1),r("el-form-item",{attrs:{label:"结束时间",prop:"deadline"}},[r("el-date-picker",{attrs:{type:"datetime",placeholder:"请选择结束时间",format:"yyyy-MM-dd HH:mm"},model:{value:e.form.deadline,callback:function(t){e.$set(e.form,"deadline",t)},expression:"form.deadline"}})],1),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{model:{value:e.form.sort_id,callback:function(t){e.$set(e.form,"sort_id",e._n(t))},expression:"form.sort_id"}})],1),r("el-form-item",{attrs:{label:"目标地址",prop:"target"}},[r("el-radio-group",{model:{value:e.form.target,callback:function(t){e.$set(e.form,"target",t)},expression:"form.target"}},[r("el-radio",{attrs:{label:"0"}},[e._v("自定义链接")]),r("el-radio",{attrs:{label:"1"}},[e._v("跳转站内")]),r("el-radio",{attrs:{label:"2"}},[e._v("绑定企业")])],1)],1),0==e.form.target?r("el-form-item",{attrs:{label:"自定义跳转链接",prop:"link_url"}},[r("el-input",{model:{value:e.form.link_url,callback:function(t){e.$set(e.form,"link_url",t)},expression:"form.link_url"}})],1):e._e(),1==e.form.target?r("el-form-item",{attrs:{label:"跳转站内链接",prop:"inner_link"}},[r("el-select",{attrs:{placeholder:"请选择"},model:{value:e.form.inner_link,callback:function(t){e.$set(e.form,"inner_link",t)},expression:"form.inner_link"}},e._l(e.inner_options,(function(e){return r("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})})),1)],1):e._e(),1==e.form.target?r("el-form-item",{attrs:{label:"站内链接参数",prop:"inner_link_params"}},[r("el-input",{attrs:{placeholder:"如无参数请留空"},model:{value:e.form.inner_link_params,callback:function(t){e.$set(e.form,"inner_link_params",e._n(t))},expression:"form.inner_link_params"}}),r("span",{staticClass:"smalltip"},[r("i",{staticClass:"el-icon-info"}),e._v(" 填写需跳转的页面ID值(数字) ")])],1):e._e(),2==e.form.target?r("el-form-item",{attrs:{label:"企业ID",prop:"company_id"}},[r("el-input",{model:{value:e.form.company_id,callback:function(t){e.$set(e.form,"company_id",e._n(t))},expression:"form.company_id"}})],1):e._e(),r("el-form-item",{attrs:{label:"是否显示"}},[r("el-switch",{model:{value:e.form.is_display,callback:function(t){e.$set(e.form,"is_display",t)},expression:"form.is_display"}})],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary",disabled:e.issubmit},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),r("el-button",{on:{click:function(t){return e.goto("/content/ad/list")}}},[e._v("返回")])],1)],1)],1)],1)},i=[],o=r("5530"),n=(r("d3b7"),r("3ca3"),r("ddb0"),r("2b3d"),r("9861"),r("ac1f"),r("1276"),r("caad"),r("2532"),r("5f87")),l=r("61f7"),s=r("ff02"),u=r("52b5"),m=r("d722"),c={data:function(){var e=this,t=function(t,r,a){""==r&&a(),""!=e.form.starttime&&e.form.starttime>r?a(new Error("开始时间不能大于结束时间")):a()},r=function(t,r,a){""==e.form.imageid&&""==r?a(new Error("请上传图片或填写本地图片地址")):a()},a=function(e,t,r){""==t&&r(),Object(l["d"])(t)?r():r(new Error("请输入正确的网址"))},i=function(t,r,a){1==e.form.target&&""==r?a(new Error("请选择站内链接")):a()},o=function(t,r,a){2==e.form.target&&0==r?a(new Error("请填写企业ID")):a()};return{issubmit:!1,size_text:"",headers:{admintoken:Object(n["e"])()},fileupload_size:"",fileupload_ext:"",apiUpload:window.global.RequestBaseUrl+m["a"].uploadAd,adCategory:[],inner_options:[],form:{title:"",cid:[],imageid:"",imageurl:"",explain:"",starttime:"",deadline:"",sort_id:0,target:"0",link_url:"",inner_link:"",inner_link_params:"",company_id:0,is_display:!0},imageSrc:"",rules:{title:[{required:!0,message:"请输入广告标题",trigger:"blur"},{max:100,message:"长度在 1 到 100 个字符",trigger:"blur"}],cid:[{required:!0,message:"请选择广告位",trigger:"change"}],imageurl:[{validator:r,trigger:"blur"}],explain:[{max:255,message:"长度在 0 到 255 个字符",trigger:"blur"}],starttime:[{required:!0,message:"请选择开始时间",trigger:"blur"}],deadline:[{validator:t,trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}],link_url:[{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"},{validator:a,trigger:"blur"}],inner_link:[{validator:i,trigger:"change"}],company_id:[{type:"number",message:"企业ID必须为数字",trigger:"blur"},{validator:o,trigger:"blur"}]}}},computed:{config:function(){return this.$store.state.config}},mounted:function(){},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext,this.fetchCategoryData()},methods:{handleChange:function(e){var t=[];for(var r in this.adCategory){var a=this.adCategory[r];if(a.value==e[0]){t=a.children;break}}for(var i in t){var o=t[i];if(o.value==e[1]){this.size_text=o.w+"*"+o.h;break}}},fetchCategoryData:function(){var e=this,t={type:"adCategory"};Object(u["a"])(t).then((function(t){return e.adCategory=t.data,e.adCategoryLoading=!1,Object(s["d"])()})).then((function(t){e.inner_options=t.data})).catch((function(){}))},onSubmit:function(e){var t=this,r=this;r.issubmit=!0;var a=Object(o["a"])({},this.form);this.$refs[e].validate((function(e){if(!e)return r.issubmit=!1,!1;if(a.is_display=!0===a.is_display?1:0,a.starttime){var i=new Date(a.starttime);a.starttime=i.getFullYear()+"-"+(i.getMonth()+1)+"-"+i.getDate()+" "+i.getHours()+":"+i.getMinutes()}if(a.deadline){var o=new Date(a.deadline);a.deadline=o.getFullYear()+"-"+(o.getMonth()+1)+"-"+o.getDate()+" "+o.getHours()+":"+o.getMinutes()}Object(s["a"])(a).then((function(e){return t.$message.success(e.message),setTimeout((function(){r.$router.push("/content/ad/list")}),1500),!0})).catch((function(){return r.issubmit=!1,!1}))}))},handleThumbSuccess:function(e,t){if(200!=e.code)return this.$message.error(e.message),!1;this.imageSrc=URL.createObjectURL(t.raw),this.form.imageid=e.data.file_id},beforeThumbUpload:function(e){var t=e.type.split("/"),r=t[1],a=this.fileupload_ext.split(",");return a.includes(r)?!(e.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)},goto:function(e){this.$router.push(e)}}},d=c,f=(r("1487"),r("2877")),p=Object(f["a"])(d,a,i,!1,null,"1088db60",null);t["default"]=p.exports},"52b5":function(e,t,r){"use strict";r.d(t,"a",(function(){return o}));var a=r("b775"),i=r("d722");function o(e){return Object(a["a"])({url:i["a"].getClassify,method:"get",params:e})}},"6d98":function(e,t,r){},ff02:function(e,t,r){"use strict";r.d(t,"e",(function(){return o})),r.d(t,"a",(function(){return n})),r.d(t,"c",(function(){return l})),r.d(t,"b",(function(){return s})),r.d(t,"d",(function(){return u}));var a=r("b775"),i=r("d722");function o(e){return Object(a["a"])({url:i["a"].adList,method:"get",params:e})}function n(e){return Object(a["a"])({url:i["a"].adAdd,method:"post",data:e})}function l(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==t?Object(a["a"])({url:i["a"].adEdit,method:t,data:e}):Object(a["a"])({url:i["a"].adEdit,method:t,params:e})}function s(e){return Object(a["a"])({url:i["a"].adDelete,method:"post",data:e})}function u(e){return Object(a["a"])({url:i["a"].adInnerLinkOptions,method:"get",params:e})}}}]);