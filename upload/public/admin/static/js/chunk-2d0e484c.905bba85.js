(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0e484c"],{9120:function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("网络招聘会管理")])]),n("div",{staticClass:"list-search"},[n("el-select",{staticClass:"list-options",attrs:{placeholder:"添加时间"},on:{change:t.funSearch},model:{value:t.settr,callback:function(e){t.settr=e},expression:"settr"}},[n("el-option",{attrs:{label:"不限添加时间",value:""}}),n("el-option",{attrs:{label:"三天内",value:"3"}}),n("el-option",{attrs:{label:"一周内",value:"7"}}),n("el-option",{attrs:{label:"一月内",value:"30"}}),n("el-option",{attrs:{label:"半年内",value:"180"}}),n("el-option",{attrs:{label:"一年内",value:"360"}})],1),n("el-input",{staticClass:"input-with-select",attrs:{placeholder:"请输入搜索内容"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword.apply(null,arguments)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[n("el-select",{staticClass:"input-sel",attrs:{slot:"prepend",placeholder:"请选择"},slot:"prepend",model:{value:t.key_type,callback:function(e){t.key_type=e},expression:"key_type"}},[n("el-option",{attrs:{label:"标题",value:"1"}}),n("el-option",{attrs:{label:"招聘会ID",value:"2"}})],1),n("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1),n("div",{staticClass:"spaceline"}),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"selection-change":t.handleSelectionChange}},[n("el-table-column",{attrs:{type:"selection",width:"42"}}),n("el-table-column",{attrs:{label:"标题","show-overflow-tooltip":"","min-width":"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-link",{attrs:{href:e.row.jobfair_link,target:"_blank",type:"primary"}},[t._v(" "+t._s(e.row.title)+" ")])]}}])}),n("el-table-column",{attrs:{label:"举办时间","show-overflow-tooltip":"",width:"260"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(t._f("timeFilter")(e.row.starttime))+" 至 "+t._s(t._f("timeFilter")(e.row.endtime))+" ")]}}])}),n("el-table-column",{attrs:{label:"预定状态",width:"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[2===e.row.score?n("span",{staticStyle:{color:"#4caf50"}},[t._v("进行中")]):1===e.row.score?n("span",{staticStyle:{color:"#f0ad00"}},[t._v("未开始")]):n("span",{staticStyle:{color:"#9e9e9e"}},[t._v("已结束")])]}}])}),n("el-table-column",{attrs:{label:"显示状态",width:"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-switch",{on:{change:function(n){return t.modifyState(e.row)}},model:{value:e.row.display,callback:function(n){t.$set(e.row,"display",n)},expression:"scope.row.display"}})]}}])}),n("el-table-column",{attrs:{align:"center",prop:"created_at",label:"添加日期",width:"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("i",{staticClass:"el-icon-time"}),n("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),n("el-table-column",{attrs:{label:"允许报名",align:"center",width:"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[1===e.row.setmeal?n("el-tooltip",{attrs:{effect:"dark",content:e.row.setmeal_cn,placement:"top-start"}},[n("el-tag",[t._v("限制套餐")])],1):n("span",[t._v("全部套餐")])]}}])}),n("el-table-column",{attrs:{label:"参会企业/个人",align:"center",width:"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.total_company)+" / "+t._s(e.row.total_personal)+" ")]}}])}),n("el-table-column",{attrs:{fixed:"right",align:"right",label:"操作",width:"330"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(n){return t.funCompany(e.$index,e.row)}}},[t._v(" 参会企业 ")]),n("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(n){return t.funPersonal(e.$index,e.row)}}},[t._v(" 参会个人 ")]),n("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(n){return t.funEdit(e.$index,e.row)}}},[t._v(" 修改 ")]),n("el-button",{attrs:{size:"mini",type:"danger"},on:{click:function(n){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),n("div",{staticClass:"spaceline"}),n("el-row",{attrs:{gutter:20}},[n("el-col",{attrs:{span:8}},[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.goAdd}},[t._v(" 添加网络招聘会 ")]),n("el-button",{attrs:{size:"small",type:"danger"},on:{click:t.funDeleteBatch}},[t._v(" 删除所选 ")])],1),n("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[n("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,20,50,100],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1)],1)},i=[],l=n("b85c"),r=n("97eb"),o=n("ed08"),s={filters:{timeFilter:function(t){return Object(o["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{settr:"",keyword:"",key_type:"1",total:0,currentPage:1,pagesize:10,listLoading:!1,list:[],tableIdarr:[],showQrcodeWindow:!1,jobfairId:0}},created:function(){this.getJobFairOnLine()},methods:{modifyState:function(t){var e=this,n=this,a={display:t.display,id:t.id};Object(r["m"])(a).then((function(t){return e.$message.success(t.message),setTimeout((function(){n.$router.push("/jobfairol/list")}),1500),!0})).catch((function(){n.issubmit=!1}))},getJobFairOnLine:function(){var t=this;this.listLoading=!0;var e={settr:this.settr,key_type:this.key_type,keyword:this.keyword,page:this.currentPage,pagesize:this.pagesize};Object(r["k"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},funCompany:function(t,e){this.$router.push({path:"/jobfairol/exhibitors/company/list",query:{jobfair_id:e.id}})},funPersonal:function(t,e){this.$router.push({path:"/jobfairol/exhibitors/personal/list",query:{jobfair_id:e.id}})},funSearch:function(){this.getJobFairOnLine()},funSearchKeyword:function(){this.currentPage=1,this.getJobFairOnLine()},handleSizeChange:function(t){this.pagesize=t,this.getJobFairOnLine()},handleCurrentChange:function(t){this.currentPage=t,this.getJobFairOnLine()},handleSelectionChange:function(t){if(this.tableIdarr=[],t.length>0){var e,n=Object(l["a"])(t);try{for(n.s();!(e=n.n()).done;){var a=e.value;this.tableIdarr.push(a.id)}}catch(i){n.e(i)}finally{n.f()}}},goAdd:function(){this.$router.push("/jobfairol/list/add")},funDelete:function(t,e){var n=this;n.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(r["i"])(t).then((function(t){return n.$message.success(t.message),n.getJobFairOnLine(),!0}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0===t.tableIdarr.length)return t.$message.error("请选择要删除的数据"),!1;t.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};Object(r["i"])(e).then((function(e){return t.$message.success(e.message),t.getJobFairOnLine(),!0}))})).catch((function(){}))},funEdit:function(t,e){this.$router.push({path:"/jobfairol/list/edit",query:{id:e.id}})}}},c=s,u=n("2877"),d=Object(u["a"])(c,a,i,!1,null,null,null);e["default"]=d.exports}}]);