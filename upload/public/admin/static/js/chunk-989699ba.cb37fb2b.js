(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-989699ba"],{"5cd0":function(t,e,r){"use strict";r("aff4")},9040:function(t,e,r){"use strict";r.d(e,"d",(function(){return n})),r.d(e,"a",(function(){return s})),r.d(e,"c",(function(){return a})),r.d(e,"b",(function(){return l}));var i=r("b775"),o=r("d722");function n(t){return Object(i["a"])({url:o["a"].explainList,method:"get",params:t})}function s(t){return Object(i["a"])({url:o["a"].explainAdd,method:"post",data:t})}function a(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(i["a"])({url:o["a"].explainEdit,method:e,data:t}):Object(i["a"])({url:o["a"].explainEdit,method:e,params:t})}function l(t){return Object(i["a"])({url:o["a"].explainDelete,method:"post",data:t})}},aff4:function(t,e,r){},de06:function(t,e,r){"use strict";r.r(e);var i=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"app-container"},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("span",[t._v("编辑说明页")]),r("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/content/explain/list")}}},[t._v(" 返回 ")]),r("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),r("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"80px",rules:t.rules,"inline-message":!0}},[r("el-form-item",{attrs:{label:"标题",prop:"title"}},[r("el-input",{model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),r("el-form-item",{attrs:{label:"内容",required:"",prop:"content"}},[r("div",{staticClass:"editor",attrs:{id:"editor"}})]),r("el-form-item",{attrs:{label:"是否显示"}},[r("el-switch",{model:{value:t.form.is_display,callback:function(e){t.$set(t.form,"is_display",e)},expression:"form.is_display"}})],1),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),r("el-form-item",{attrs:{label:"外部链接",prop:"link_url"}},[r("el-input",{model:{value:t.form.link_url,callback:function(e){t.$set(t.form,"link_url",e)},expression:"form.link_url"}})],1),r("el-form-item",{attrs:{label:"seo关键词",prop:"seo_keywords"}},[r("el-input",{model:{value:t.form.seo_keywords,callback:function(e){t.$set(t.form,"seo_keywords",e)},expression:"form.seo_keywords"}})],1),r("el-form-item",{attrs:{label:"seo描述",prop:"seo_description"}},[r("el-input",{attrs:{type:"textarea",rows:"4"},model:{value:t.form.seo_description,callback:function(e){t.$set(t.form,"seo_description",e)},expression:"form.seo_description"}})],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),r("el-button",{on:{click:function(e){return t.goto("/content/explain/list")}}},[t._v("返回")])],1)],1)],1)],1)},o=[],n=r("7e2d"),s=r("61f7"),a=r("9040"),l=r("5fd4"),c=r.n(l),u=r("d722"),d=r("5f87"),f={data:function(){var t=this,e=function(e,r,i){r=t.editor.txt.text(),""===r?i(new Error("请输入内容")):i()},r=function(t,e,r){""==e&&r(),Object(s["d"])(e)?r():r(new Error("请输入正确的网址"))};return{infoLoading:!0,editor:"",explainCategory:[],form:{title:"",content:"",is_display:!0,link_url:"",seo_keywords:"",seo_description:"",sort_id:0},imageUrl:"",rules:{title:[{required:!0,message:"请输入标题",trigger:"blur"},{max:100,message:"长度在 1 到 100 个字符",trigger:"blur"}],content:[{validator:e,trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}],link_url:[{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"},{validator:r,trigger:"blur"}],seo_keywords:[{max:100,message:"长度在 0 到 100 个字符",trigger:"blur"}],seo_description:[{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"}]}}},mounted:function(){this.editor=new c.a("#editor"),this.editor.config.uploadImgServer=window.global.RequestBaseUrl+u["a"].uploadEditor,this.editor.config.uploadImgHeaders={admintoken:Object(d["e"])()},this.editor.config.zIndex=0,this.editor.config.pasteFilterStyle=!1,this.editor.create()},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var t=this;this.infoLoading=!0;var e={id:this.$route.query.id};Object(a["c"])(e,"get").then((function(e){t.form=Object(n["a"])({},e.data.info),t.form.is_display=1==t.form.is_display,t.editor.txt.html(t.form.content),t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,r=this;this.form.content=this.editor.txt.html();var i=Object(n["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;i.is_display=!0===i.is_display?1:0,Object(a["c"])(i).then((function(t){return e.$message.success(t.message),setTimeout((function(){r.$router.push("/content/explain/list")}),1500),!0})).catch((function(){}))}))},goto:function(t){this.$router.push(t)}}},m=f,p=(r("5cd0"),r("5d22")),g=Object(p["a"])(m,i,o,!1,null,"06276613",null);e["default"]=g.exports}}]);