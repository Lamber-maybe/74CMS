(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-5b5f5da0"],{"0184":function(t,e,n){"use strict";var a=n("0e1f"),r=n.n(a);r.a},"0e1f":function(t,e,n){},"1e0b":function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("在线客服")])]),n("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{"label-width":"120px","inline-message":!0}},[n("el-form-item",{attrs:{label:"是否显示"}},[n("el-radio-group",{model:{value:t.form.service_ol_open,callback:function(e){t.$set(t.form,"service_ol_open",e)},expression:"form.service_ol_open"}},[n("el-radio",{attrs:{label:1}},[t._v("显示")]),n("el-radio",{attrs:{label:0}},[t._v("不显示")])],1)],1),n("el-form-item",{attrs:{label:""}},[n("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit()}}},[t._v("保存")])],1)],1),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[n("el-table-column",{attrs:{label:"姓名",prop:"name",width:"200"}}),n("el-table-column",{attrs:{label:"电话",prop:"mobile",width:"300"}}),n("el-table-column",{attrs:{label:"微信",prop:"weixin",width:"300"},scopedSlots:t._u([{key:"default",fn:function(t){return[n("el-popover",{attrs:{placement:"right",trigger:"hover"}},[n("img",{staticClass:"school_pop",attrs:{src:t.row.weixin_url}}),n("span",{attrs:{slot:"reference"},slot:"reference"},[n("i",{staticClass:"el-icon-picture"})])])]}}])}),n("el-table-column",{attrs:{label:"QQ",prop:"qq"}}),n("el-table-column",{attrs:{label:"是否显示",prop:"title"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-tag",{attrs:{type:t._f("displayFilter")(e.row.display)}},[t._v(" "+t._s(1==e.row.display?"显示":"隐藏")+" ")])]}}])}),n("el-table-column",{attrs:{label:"排序",prop:"sort",width:"100"}}),n("el-table-column",{attrs:{fixed:"right",label:"操作",width:"220"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(n){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")]),n("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(n){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),n("div",{staticClass:"spaceline"}),n("el-row",{attrs:{gutter:20}},[n("el-col",{attrs:{span:8}},[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.$router.push("/sys/basic/serviceol/add")}}},[t._v(" 新增客服 ")])],1)],1)],1)],1)},r=[],i=n("5530"),o=n("8012"),s=n("1063"),l={filters:{displayFilter:function(t){var e={1:"success",0:"info"};return e[t]}},data:function(){return{infoLoading:!1,list:null,listLoading:!1,form:{service_ol_open:1}}},created:function(){this.fetchData(),this.fetchInfo()},methods:{onSubmit:function(){var t=this,e=Object(i["a"])({},this.form);Object(s["j"])(e).then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))},fetchInfo:function(){var t=this;this.infoLoading=!0;var e={};Object(s["j"])(e,"get").then((function(e){var n=Object(i["a"])({},e.data),a=n.service_ol_open;t.form.service_ol_open=parseInt(a),console.log(t.form.service_ol_open),t.infoLoading=!1})).catch((function(){}))},fetchData:function(){var t=this;Object(o["d"])().then((function(e){t.list=e.data.items}))},funEdit:function(t,e){this.$router.push({path:"/sys/basic/serviceol/edit",query:{id:e.id}})},funDelete:function(t,e){var n=this;n.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(o["b"])(t).then((function(t){return n.$message.success(t.message),n.fetchData(),!0}))})).catch((function(){}))}}},c=l,u=(n("0184"),n("2877")),f=Object(u["a"])(c,a,r,!1,null,"75c65e0f",null);e["default"]=f.exports},8012:function(t,e,n){"use strict";n.d(e,"d",(function(){return i})),n.d(e,"a",(function(){return o})),n.d(e,"c",(function(){return s})),n.d(e,"b",(function(){return l}));var a=n("b775"),r=n("d722");function i(t){return Object(a["a"])({url:r["a"].serviceOl,method:"get",params:t})}function o(t){return Object(a["a"])({url:r["a"].serviceOlAdd,method:"post",data:t})}function s(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"===e?Object(a["a"])({url:r["a"].serviceOlEdit,method:e,data:t}):Object(a["a"])({url:r["a"].serviceOlEdit,method:e,params:t})}function l(t){return Object(a["a"])({url:r["a"].serviceOlDelete,method:"post",data:t})}}}]);