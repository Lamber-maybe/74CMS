(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-7602ebe1"],{"52b5":function(t,e,a){"use strict";a.d(e,"a",(function(){return r}));var n=a("b775"),i=a("d722");function r(t){return Object(n["a"])({url:i["a"].getClassify,method:"get",params:t})}},5312:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[t._v("投诉客服")])]),a("div",{staticClass:"list-search"},[a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限处理状态"},on:{change:t.funSearch},model:{value:t.status,callback:function(e){t.status=e},expression:"status"}},[a("el-option",{attrs:{label:"不限处理状态",value:""}}),a("el-option",{attrs:{label:"已处理",value:"1"}}),a("el-option",{attrs:{label:"未处理",value:"0"}})],1)],1),a("div",{staticClass:"spaceline"}),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"selection-change":t.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection",width:"42"}}),a("el-table-column",{attrs:{label:"处理情况",width:"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-tag",{attrs:{type:t._f("statusFilter")(e.row.status)}},[t._v(" "+t._s(1==e.row.status?"已处理":"未处理")+" ")])]}}])}),a("el-table-column",{attrs:{label:"客服",width:"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.name)+" ")]}}])}),a("el-table-column",{attrs:{label:"内容","show-overflow-tooltip":""},scopedSlots:t._u([{key:"default",fn:function(e){return[a("span",[t._v(t._s(e.row.content))])]}}])}),a("el-table-column",{attrs:{label:"投诉人",width:"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.mobile)+" 【uid："+t._s(e.row.uid)+"】 ")]}}])}),a("el-table-column",{attrs:{align:"center",prop:"addtime",label:"投诉时间",width:"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("i",{staticClass:"el-icon-time"}),a("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),a("el-table-column",{attrs:{fixed:"right",label:"操作",width:"220"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-row",{attrs:{gutter:20}},[a("el-col",{attrs:{span:8}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.funHandler}},[t._v(" 处理 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:t.funDeleteBatch}},[t._v(" 删除 ")])],1),a("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[a("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1),a("el-dialog",{attrs:{title:"将所选信息设置为",visible:t.dialogVisible,width:"25%"},on:{"update:visible":function(e){t.dialogVisible=e}}},[a("el-form",{staticClass:"common-form",attrs:{"label-width":"80px"}},[a("el-form-item",{attrs:{label:"处理情况"}},[a("el-radio-group",{model:{value:t.setStatusVal,callback:function(e){t.setStatusVal=e},expression:"setStatusVal"}},[a("el-radio",{attrs:{label:"0"}},[t._v("未处理")]),a("el-radio",{attrs:{label:"1"}},[t._v("已处理")])],1)],1)],1),a("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{on:{click:function(e){t.dialogVisible=!1}}},[t._v("取 消")]),a("el-button",{attrs:{type:"primary"},on:{click:t.funSetStatus}},[t._v(" 确 定 ")])],1)],1)],1)},i=[],r=a("b85c"),s=a("b775"),l=a("d722");function o(t){return Object(s["a"])({url:l["a"].complaintList,method:"get",params:t})}function c(t){return Object(s["a"])({url:l["a"].complaintDelete,method:"post",data:t})}function u(t){return Object(s["a"])({url:l["a"].complaintHandler,method:"post",data:t})}var d=a("ed08"),f=a("52b5"),h={filters:{statusFilter:function(t){var e={1:"success",0:"danger"};return e[t]},timeFilter:function(t){return Object(d["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{setStatusVal:"0",dialogVisible:!1,tableIdarr:[],list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,optionsType:[],type:"",status:""}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0,Object(f["a"])({type:"complaint"}).then((function(e){t.optionsType=e.data;var a={status:t.status,type:t.type,page:t.currentPage,pagesize:t.pagesize};return o(a)})).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearch:function(){this.fetchData()},funDelete:function(t,e){var a=this;a.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};c(t).then((function(t){return a.$message.success(t.message),a.fetchData(),!0}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0==t.tableIdarr.length)return t.$message.error("请选择要删除的信息"),!1;t.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};c(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0}))})).catch((function(){}))},handleSelectionChange:function(t){if(this.tableIdarr=[],t.length>0){var e,a=Object(r["a"])(t);try{for(a.s();!(e=a.n()).done;){var n=e.value;this.tableIdarr.push(n.id)}}catch(i){a.e(i)}finally{a.f()}}},goTo:function(t){this.$router.push("/content/complaint/complaint/"+t)},funHandler:function(){if(0==this.tableIdarr.length)return this.$message.error("请选择要处理的数据"),!1;this.dialogVisible=!0},funSetStatus:function(){var t=this,e={id:this.tableIdarr,status:this.setStatusVal};u(e,"post").then((function(e){return 200==e.code?(t.$message.success(e.message),t.fetchData(),t.dialogVisible=!1,!0):(t.$message.error(e.message),!1)}))}}},p=h,g=a("2877"),b=Object(g["a"])(p,n,i,!1,null,null,null);e["default"]=b.exports},b85c:function(t,e,a){"use strict";a.d(e,"a",(function(){return i}));a("a4d3"),a("e01a"),a("d3b7"),a("d28b"),a("3ca3"),a("ddb0");var n=a("06c5");function i(t,e){var a="undefined"!==typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!a){if(Array.isArray(t)||(a=Object(n["a"])(t))||e&&t&&"number"===typeof t.length){a&&(t=a);var i=0,r=function(){};return{s:r,n:function(){return i>=t.length?{done:!0}:{done:!1,value:t[i++]}},e:function(t){throw t},f:r}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var s,l=!0,o=!1;return{s:function(){a=a.call(t)},n:function(){var t=a.next();return l=t.done,t},e:function(t){o=!0,s=t},f:function(){try{l||null==a["return"]||a["return"]()}finally{if(o)throw s}}}}}}]);