(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-36900a54"],{"060e":function(t,n,e){"use strict";e.r(n);var o=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"app-container"},[e("el-card",{staticClass:"box-card"},[e("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[e("span",[t._v("休眠用户提醒")])]),e("div",{staticClass:"tip"},[e("p",[t._v(" 此功能主要用于通过短信提醒一定时间范围内未登录的用户登录网站。开启此功能可能要耗费较多的短信，请按需开启。 ")]),e("p",[t._v(" 用户登录条件区间指需按照[较近]-[较远]天数设置（如配置15-30天则会发送短信给最近15-30天内未登录的用户）；提醒次数限制指符合该条件会提醒几次，满足指定次数后将不再提醒。 ")])]),e("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"150px",rules:t.rules,"inline-message":!0}},[e("el-form-item",{attrs:{label:"用户未登录条件区间"}},[e("el-input",{staticStyle:{width:"200px"},model:{value:t.nologin_notice_time1,callback:function(n){t.nologin_notice_time1=n},expression:"nologin_notice_time1"}},[e("template",{slot:"append"},[t._v("天")])],2),t._v(" - "),e("el-input",{staticStyle:{width:"200px"},model:{value:t.nologin_notice_time2,callback:function(n){t.nologin_notice_time2=n},expression:"nologin_notice_time2"}},[e("template",{slot:"append"},[t._v("天")])],2)],1),e("el-form-item",{attrs:{label:"提醒次数限制"}},[e("el-input",{staticStyle:{width:"200px"},model:{value:t.form.nologin_notice_counter,callback:function(n){t.$set(t.form,"nologin_notice_counter",n)},expression:"form.nologin_notice_counter"}},[e("template",{slot:"append"},[t._v("次")])],2)],1),e("el-form-item",{attrs:{label:""}},[e("el-button",{attrs:{type:"primary"},on:{click:function(n){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1)],1)],1)],1)},i=[],a=e("5530"),c=(e("ac1f"),e("1276"),e("1063")),l={data:function(){return{nologin_notice_time1:"",nologin_notice_time2:"",infoLoading:!1,form:{nologin_notice_timerange:"",nologin_notice_counter:""},rules:{}}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.infoLoading=!0;var n={};Object(c["n"])(n,"get").then((function(n){var e=Object(a["a"])({},n.data),o=e.nologin_notice_timerange,i=e.nologin_notice_counter,c=o.indexOf(",");if(-1!=c){var l=o.split(",");t.nologin_notice_time1=l[0],t.nologin_notice_time2=l[1]}t.form.nologin_notice_counter=i,t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var n=this,e=Object(a["a"])({},this.form);e.nologin_notice_timerange=this.nologin_notice_time1+","+this.nologin_notice_time2,this.$refs[t].validate((function(t){if(!t)return!1;Object(c["n"])(e).then((function(t){return n.$message.success(t.message),!0})).catch((function(){}))}))}}},s=l,r=(e("aa09"),e("2877")),_=Object(r["a"])(s,o,i,!1,null,"3346d4aa",null);n["default"]=_.exports},aa09:function(t,n,e){"use strict";e("e9da")},e9da:function(t,n,e){}}]);