(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-125439e0"],{"45a2":function(t,e,a){"use strict";a.d(e,"b",(function(){return o})),a.d(e,"a",(function(){return r}));var i=a("b775"),n=a("d722");function o(t){return Object(i["a"])({url:n["a"].pageList,method:"get",params:t})}function r(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(i["a"])({url:n["a"].pageEdit,method:e,data:t}):Object(i["a"])({url:n["a"].pageEdit,method:e,params:t})}},"8a68":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[t._v("页面管理")])]),a("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"120px","inline-message":!0}},[a("el-form-item",{attrs:{label:"路由规则"}},[a("el-radio-group",{model:{value:t.form.route_rule,callback:function(e){t.$set(t.form,"route_rule",e)},expression:"form.route_rule"}},[a("el-radio",{attrs:{label:"def"}},[t._v("默认")]),a("el-radio",{attrs:{label:"qishi_6_0_min"}},[t._v("骑士6.0极简")]),a("el-radio",{attrs:{label:"qishi_6_0_pathinfo"}},[t._v("骑士6.0默认")]),a("el-radio",{attrs:{label:"qishi_3_7"}},[t._v("骑士3.7")])],1)],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")])],1)],1),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[a("el-table-column",{attrs:{label:"页面名称",prop:"name",width:"200"}}),a("el-table-column",{attrs:{label:"seo标题",prop:"seo_title"}}),a("el-table-column",{attrs:{label:"缓存时长",width:"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.enable_cache?a("span",[t._v(t._s(0==e.row.expire?"不缓存":e.row.expire+"秒"))]):a("span",{staticStyle:{"font-style":"italic",color:"#d3d3d3","font-size":"13px"}},[t._v("不可缓存")])]}}])}),a("el-table-column",{attrs:{fixed:"right",label:"操作",width:"220"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")])]}}])})],1)],1)],1)},n=[],o=a("5530"),r=a("1063"),l=a("45a2"),s={data:function(){return{list:null,listLoading:!0,infoLoading:!0,form:{route_rule:"def"}}},created:function(){this.fetchInfo(),this.fetchData()},methods:{clearCache:function(){var t=this;Object(r["a"])({}).then((function(e){200==e.code&&t.$store.dispatch("config/getConfigInfo")}))},fetchInfo:function(){var t=this;this.infoLoading=!0;var e={};Object(r["j"])(e,"get").then((function(e){var a=Object(o["a"])({},e.data),i=a.route_rule;t.form={route_rule:i},console.log(t.form),t.infoLoading=!1})).catch((function(){}))},fetchData:function(){var t=this;this.listLoading=!0,Object(l["b"])({}).then((function(e){t.list=e.data,t.listLoading=!1}))},funEdit:function(t,e){this.$router.push({path:"/sys/basic/page/edit",query:{id:e.id}})},onSubmit:function(t){var e=this,a=Object(o["a"])({},this.form);this.$refs[t].validate((function(t){if(!t)return!1;Object(r["j"])(a).then((function(t){return e.clearCache(),e.$message.success(t.message),!0})).catch((function(){}))}))}}},c=s,u=a("2877"),d=Object(u["a"])(c,i,n,!1,null,null,null);e["default"]=d.exports}}]);