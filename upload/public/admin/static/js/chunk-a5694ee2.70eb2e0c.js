(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-a5694ee2"],{"2cd6":function(t,e,n){},"77fe":function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("编辑")]),n("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/content/flink/list")}}},[t._v(" 返回 ")]),n("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),n("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"80px",rules:t.rules,"inline-message":!0}},[n("el-form-item",{attrs:{label:"名称",prop:"name"}},[n("el-input",{model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),n("el-form-item",{attrs:{label:"网址",prop:"link_url"}},[n("el-input",{model:{value:t.form.link_url,callback:function(e){t.$set(t.form,"link_url",e)},expression:"form.link_url"}})],1),n("el-form-item",{attrs:{label:"是否显示"}},[n("el-switch",{model:{value:t.form.is_display,callback:function(e){t.$set(t.form,"is_display",e)},expression:"form.is_display"}})],1),n("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[n("el-input",{model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),n("el-form-item",{attrs:{label:"备注",prop:"notes"}},[n("el-input",{attrs:{type:"textarea"},model:{value:t.form.notes,callback:function(e){t.$set(t.form,"notes",e)},expression:"form.notes"}})],1),n("el-form-item",{attrs:{label:""}},[n("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),n("el-button",{on:{click:function(e){return t.goto("/content/flink/list")}}},[t._v("返回")])],1)],1)],1)],1)},i=[],o=n("5530"),a=n("61f7"),s=n("9587"),l={data:function(){var t=function(t,e,n){""==e&&n(),Object(a["d"])(e)?n():n(new Error("请输入正确的网址"))};return{form:{name:"",notes:"",is_display:!0,link_url:"",sort_id:0},rules:{name:[{required:!0,message:"请输入名称",trigger:"blur"},{max:100,message:"长度在 1 到 100 个字符",trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}],link_url:[{required:!0,message:"请输入网址",trigger:"blur"},{max:200,message:"长度在 0 到 200 个字符",trigger:"blur"},{validator:t,trigger:"blur"}]}}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var t=this;this.infoLoading=!0;var e={id:this.$route.query.id};Object(s["c"])(e,"get").then((function(e){t.form=Object(o["a"])({},e.data.info),t.form.is_display=1==t.form.is_display,t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,n=this,r=Object(o["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;r.is_display=!0===r.is_display?1:0,Object(s["c"])(r).then((function(t){return e.$message.success(t.message),setTimeout((function(){n.$router.push("/content/flink/list")}),1500),!0})).catch((function(){}))}))},goto:function(t){this.$router.push(t)}}},u=l,c=(n("ffb0"),n("2877")),f=Object(c["a"])(u,r,i,!1,null,"5484afda",null);e["default"]=f.exports},9587:function(t,e,n){"use strict";n.d(e,"d",(function(){return o})),n.d(e,"a",(function(){return a})),n.d(e,"c",(function(){return s})),n.d(e,"b",(function(){return l}));var r=n("b775"),i=n("d722");function o(t){return Object(r["a"])({url:i["a"].flinkList,method:"get",params:t})}function a(t){return Object(r["a"])({url:i["a"].flinkAdd,method:"post",data:t})}function s(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(r["a"])({url:i["a"].flinkEdit,method:e,data:t}):Object(r["a"])({url:i["a"].flinkEdit,method:e,params:t})}function l(t){return Object(r["a"])({url:i["a"].flinkDelete,method:"post",data:t})}},ffb0:function(t,e,n){"use strict";var r=n("2cd6"),i=n.n(r);i.a}}]);