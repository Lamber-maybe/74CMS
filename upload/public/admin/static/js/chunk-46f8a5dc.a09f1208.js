(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-46f8a5dc"],{2592:function(t,e,i){},"2de5":function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAMCAYAAAC9QufkAAAAvklEQVQokYWRMQoCMRBFX8TSwgvsFazsdHuvoIV29tp4AxGEbcVym20FO7FcEW0srF3FO9hHAgnENTv7IJBh8mfC/0prjWE0mTeADdAHTsAqS5MXAmo4nrnuFph6Tz9AB6gcYLblgHbC9XJB3Ouaawt42l7o5M3ytN3+yKN4S791KCOObREB9/Pl1rb1ARiIameYNS2yw65ZmhS1q31xmboEfLdDiAn4boeOlMC/2yEqEvhxO4SYgGiYoTIB4Atfl1UFq1kTBwAAAABJRU5ErkJggg=="},"5ab5":function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAMCAYAAAC9QufkAAAAcElEQVQokWP4//8/DDP9//9/5v///69CaUUkOawYWRCkARl8JmQAiDj8nzxwmImBfMCI7AzZ////v0dyww5CfmYEux0BZBkYGGwZGBhOMjAw3CXoJgKm440BQprxxsDgCG1sGG8MoIc2NoA9BhgYGACzIKLIwi8QQAAAAABJRU5ErkJggg=="},"5cc9":function(t,e,i){"use strict";i("2592")},bdee:function(t,e,i){"use strict";i.r(e);var s=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"app-container"},[s("el-card",{staticClass:"box-card"},[s("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[s("span",[t._v("企业注销申请")])]),s("div",{staticClass:"tip"},[s("p",[t._v("注销账号后删除会员所有数据，不可恢复。如需留存会员数据，请点击备份，备份会员数据。")])]),s("div",{staticStyle:{float:"right","z-index":"1",position:"relative","margin-top":"13px"}},[s("span",{staticClass:"switch",on:{click:function(e){return t.goto("/user/company_cancel_backup")}}},[s("img",{staticStyle:{width:"10px",height:"10px","margin-left":"14px"},attrs:{src:i("a698"),alt:""}}),t._v(" 查看我的备份 ")])]),s("div",{staticClass:"list-search"},[s("el-input",{staticClass:"input-with-select",staticStyle:{width:"440px",float:"left"},attrs:{placeholder:"请输入搜索内容"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword.apply(null,arguments)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[s("el-select",{staticClass:"input-sel",attrs:{slot:"prepend",placeholder:"请选择"},slot:"prepend",model:{value:t.key_type,callback:function(e){t.key_type=e},expression:"key_type"}},[s("el-option",{attrs:{label:"公司名称",value:"1"}})],1),s("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1),""!=t.processingStatus?s("div",{staticClass:"filterCriteria"},[s("div",{staticStyle:{float:"left",display:"inline-block","margin-top":"6px"}},[t._v("已选条件：")]),s("div",{staticStyle:{float:"left",display:"inline-block",width:"80%"}},[""!=t.processingStatus?s("div",{staticClass:"selected"},[s("div",{staticClass:"name"},[t._v("处理状态："+t._s(t.processingStatus.name))]),s("div",{staticClass:"closes",on:{click:function(e){return t.reset(t.processingStatus.field)}}},[s("i",{staticClass:"el-icon-close"})])]):t._e()]),s("div",{staticStyle:{float:"right",display:"inline-block","margin-top":"6px",color:"#409eff","font-size":"13px"},on:{click:function(e){return t.reset("all")}}},[s("i",{staticClass:"el-icon-delete"}),t._v(" 清空条件 ")]),s("div",{staticStyle:{clear:"both"}})]):t._e(),s("div",{staticStyle:{position:"relative"}},[s("div",{class:""!=t.processingStatus||""!=t.citycategoryFilter?"setField_s":"setField"},[s("el-popover",{attrs:{placement:"bottom-start",width:"220",trigger:"manual"},model:{value:t.visiblepop,callback:function(e){t.visiblepop=e},expression:"visiblepop"}},[s("div",{staticClass:"setField_h"},t._l(t.fieldData,(function(e){return s("div",{staticClass:"setFields"},[1==e.select?s("input",{attrs:{type:"checkbox",name:e.name,checked:""},domProps:{value:e.field},on:{change:function(i){return t.select(e.field)}}}):t._e(),0==e.select?s("input",{attrs:{type:"checkbox",name:e.name},domProps:{value:e.field},on:{change:function(i){return t.select(e.field)}}}):t._e(),s("span",{staticStyle:{"margin-left":"10px"}},[t._v(t._s(e.name))])])})),0),s("el-button",{class:"menu"==t.menu_icon?"field_s":"field_s_select",attrs:{slot:"reference"},on:{click:t.menu},slot:"reference"},["menu"==t.menu_icon?s("img",{attrs:{src:i("2de5"),alt:""}}):t._e(),"menu_select"==t.menu_icon?s("img",{attrs:{src:i("5ab5"),alt:""}}):t._e()]),s("div",{staticStyle:{"margin-top":"10px","margin-right":"10px"}},[s("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(e){return t.setFieldClose()}}},[t._v("关闭")]),s("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(e){return t.setFieldConfirm()}}},[t._v("确认")])],1)],1)],1),s("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],ref:"multipleTable",attrs:{data:t.list,border:"",stripe:"","element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"sort-change":t.sortTable,"selection-change":t.handleSelectionChange}},[s("el-table-column",{attrs:{fixed:"",type:"selection",width:"42"}}),t._l(t.fieldData,(function(e){return 1==e.is_locking&&1==e.select?s("el-table-column",{attrs:{fixed:"",width:e.width,prop:e.field,sortable:e.is_sortable},scopedSlots:t._u([{key:"header",fn:function(i){return[s("div",{class:"custom"==e.is_sortable?"sotrTime":"",on:{mouseenter:function(i){return t.locks(e.field)},mouseleave:function(i){return t.locks(e.field)}}},[s("span",[t._v(t._s(e.name))]),s("el-popover",{attrs:{placement:"bottom",trigger:"hover"}},["status"==e.field?s("div",{staticClass:"screen_s"},[s("el-checkbox-group",t._l(t.status,(function(e){return s("div",{staticClass:"screenStyle"},[s("el-radio",{key:e.id,attrs:{label:e.id},model:{value:t.auditScreen,callback:function(e){t.auditScreen=e},expression:"auditScreen"}},[t._v(t._s(e.name))])],1)})),0)],1):t._e(),s("div",{staticStyle:{"margin-top":"10px"}},[s("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(i){return t.reset(e.field)}}},[t._v("重置")]),s("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(e){return t.confirm()}}},[t._v("确认")])],1),"status"==e.field?s("div",{staticClass:"drop_down",attrs:{slot:"reference"},slot:"reference"}):t._e()]),1==e.is_lock_display?s("div",{staticClass:"unlock_display",attrs:{slot:"reference"},on:{click:function(i){return t.locking(e.field)}},slot:"reference"}):t._e(),0==e.is_lock_display?s("div",{staticClass:"unlock",attrs:{slot:"reference"},on:{click:function(i){return t.locking(e.field)}},slot:"reference"}):t._e()],1)]}},{key:"default",fn:function(i){return["id"==e.field?s("div",[""==i.row.id?s("span",[t._v("-")]):s("span",[t._v(t._s(i.row.id))])]):t._e(),"companyname"==e.field?s("div",[""==i.row.companyname?s("span",[t._v("-")]):s("span",[t._v(t._s(i.row.companyname))])]):t._e(),"regtime"==e.field?s("div",[""==i.row.regtime?s("span",[t._v("-")]):s("span",[t._v(t._s(i.row.regtime))])]):t._e(),"contact"==e.field?s("div",[""==i.row.contact?s("span",[t._v("-")]):s("span",[t._v(t._s(i.row.contact))])]):t._e(),"mobile"==e.field?s("div",[""==i.row.mobile?s("span",[t._v("-")]):s("span",[t._v(t._s(i.row.mobile))])]):t._e(),"status"==e.field?s("div",[0==i.row.status?s("span",[0==i.row.status?s("el-tag",{attrs:{type:"danger"}},[t._v("待处理")]):t._e()],1):s("span",[1==i.row.status?s("el-tag",{attrs:{type:"success"}},[t._v("已处理")]):t._e()],1)]):t._e(),"addtime"==e.field?s("div",[""==i.row.addtime?s("span",[t._v("-")]):s("span",[s("i",{staticClass:"el-icon-time"}),s("span",[t._v(t._s(t._f("timeFilter")(i.row.addtime)))])])]):t._e(),"handlertime"==e.field?s("div",[""==i.row.handlertime?s("span",[t._v("-")]):s("span",[s("i",{staticClass:"el-icon-time"}),s("span",[t._v(t._s(t._f("timeFilter")(i.row.handlertime)))])])]):t._e()]}}],null,!0)}):t._e()})),t._l(t.fieldData,(function(e){return 0==e.is_locking&&1==e.select?s("el-table-column",{attrs:{width:e.width,prop:e.field,sortable:e.is_sortable},scopedSlots:t._u([{key:"header",fn:function(i){return[s("div",{class:"custom"==e.is_sortable?"sotrTime":"",on:{mouseenter:function(i){return t.locks(e.field)},mouseleave:function(i){return t.locks(e.field)}}},[s("span",[t._v(t._s(e.name))]),s("el-popover",{attrs:{placement:"bottom",trigger:"hover"}},["status"==e.field?s("div",{staticClass:"screen_s"},[s("el-checkbox-group",t._l(t.status,(function(e){return s("div",{staticClass:"screenStyle"},[s("el-radio",{key:e.id,attrs:{label:e.id},model:{value:t.auditScreen,callback:function(e){t.auditScreen=e},expression:"auditScreen"}},[t._v(t._s(e.name))])],1)})),0)],1):t._e(),s("div",{staticStyle:{"margin-top":"10px"}},[s("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(i){return t.reset(e.field)}}},[t._v("重置")]),s("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(e){return t.confirm()}}},[t._v("确认")])],1),"status"==e.field?s("div",{staticClass:"drop_down",attrs:{slot:"reference"},slot:"reference"}):t._e()]),1==e.is_lock_display?s("div",{staticClass:"lock_display",attrs:{slot:"reference"},on:{click:function(i){return t.locking(e.field)}},slot:"reference"}):t._e(),0==e.is_lock_display?s("div",{staticClass:"lock",attrs:{slot:"reference"},on:{click:function(i){return t.locking(e.field)}},slot:"reference"}):t._e()],1)]}},{key:"default",fn:function(i){return["id"==e.field?s("div",[""==i.row.id?s("span",[t._v("-")]):s("span",[t._v(t._s(i.row.id))])]):t._e(),"companyname"==e.field?s("div",[""==i.row.companyname?s("span",[t._v("-")]):s("span",[t._v(t._s(i.row.companyname))])]):t._e(),"regtime"==e.field?s("div",[""==i.row.regtime?s("span",[t._v("-")]):s("span",[t._v(t._s(i.row.regtime))])]):t._e(),"contact"==e.field?s("div",[""==i.row.contact?s("span",[t._v("-")]):s("span",[t._v(t._s(i.row.contact))])]):t._e(),"mobile"==e.field?s("div",[""==i.row.mobile?s("span",[t._v("-")]):s("span",[t._v(t._s(i.row.mobile))])]):t._e(),"status"==e.field?s("div",[0==i.row.status?s("span",[0==i.row.status?s("el-tag",{attrs:{type:"danger"}},[t._v("待处理")]):t._e()],1):s("span",[1==i.row.status?s("el-tag",{attrs:{type:"success"}},[t._v("已处理")]):t._e()],1)]):t._e(),"addtime"==e.field?s("div",[""==i.row.addtime?s("span",[t._v("-")]):s("span",[s("i",{staticClass:"el-icon-time"}),s("span",[t._v(t._s(t._f("timeFilter")(i.row.addtime)))])])]):t._e(),"handlertime"==e.field?s("div",[""==i.row.handlertime?s("span",[t._v("-")]):s("span",[s("i",{staticClass:"el-icon-time"}),s("span",[t._v(t._s(t._f("timeFilter")(i.row.handlertime)))])])]):t._e()]}}],null,!0)}):t._e()})),s("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"230"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.is_backups||1==e.row.status?s("el-button",{attrs:{size:"small",type:"primary",disabled:""},on:{click:function(i){return t.backups(e.row)}}},[t._v(" 备份 ")]):s("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(i){return t.backups(e.row)}}},[t._v(" 备份 ")]),1==e.row.status?s("el-button",{attrs:{size:"small",type:"primary",disabled:""},on:{click:function(i){return t.funHandle(e.row)}}},[t._v(" 处理 ")]):s("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(i){return t.funHandle(e.row)}}},[t._v(" 处理 ")]),s("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(i){return t.funDel(e.row)}}},[t._v(" 删除 ")])]}}])})],2)],1),s("div",{staticClass:"spaceline"}),s("el-row",{attrs:{gutter:20}},[s("el-col",{attrs:{span:10}},[s("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.choose}},[t._v(" 全选/反选 ")]),s("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(e){return t.funDeleteBatch()}}},[t._v(" 删除 ")])],1),s("el-col",{attrs:{span:8}}),s("el-col",{staticStyle:{"text-align":"right"},attrs:{offset:8,span:16}},[s("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,20,50,100],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1)],1)},a=[],n=i("b85c"),l=(i("e9c4"),i("b0c0"),i("4e82"),i("d3b7"),i("159b"),i("f8b5")),c=i("ed08"),o=i("f09a"),r={name:"CommpanyCancelApply",filters:{timeFilter:function(t){return Object(c["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{status:[{id:1,name:"待处理"},{id:2,name:"已处理"}],auditScreen:"",fieldData:[{name:"ID",field:"id",select:!0,is_locking:!1,is_lock_display:!1,width:100,is_sortable:!1},{name:"企业名称",field:"companyname",select:!0,is_locking:!1,is_lock_display:!1,width:205,is_sortable:!1},{name:"注册时间",field:"regtime",select:!0,is_locking:!1,is_lock_display:!1,width:190,is_sortable:!1},{name:"联系人",field:"contact",select:!0,is_locking:!1,is_lock_display:!1,width:190,is_sortable:!1},{name:"手机号",field:"mobile",select:!0,is_locking:!1,is_lock_display:!1,width:190,is_sortable:!1},{name:"处理状态",field:"status",select:!0,is_locking:!1,is_lock_display:!1,width:175,is_sortable:!1},{name:"申请时间",field:"addtime",select:!0,is_locking:!1,is_lock_display:!1,width:200,is_sortable:"custom"},{name:"处理时间",field:"handlertime",select:!0,is_locking:!1,is_lock_display:!1,width:200,is_sortable:"custom"}],list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,key_type:"1",keyword:"",tableData:[],tableIdarr:[],sortType:"",sort:"",processingStatus:"",loading:!1,visiblepop:!1,menu_icon:"menu",citycategoryFilter:""}},created:function(){this.fetchData(),this.getField()},methods:{setFieldClose:function(){this.menu_icon="menu",this.visiblepop=!1},select:function(t){for(var e=0;e<=this.fieldData.length-1;e++)this.fieldData[e].field==t&&(1==this.fieldData[e].select?this.fieldData[e].select=!1:this.fieldData[e].select=!0)},menu:function(){"menu"==this.menu_icon?(this.visiblepop=!0,this.menu_icon="menu_select"):(this.visiblepop=!1,this.menu_icon="menu")},locking:function(t){for(var e=0;e<=this.fieldData.length-1;e++)this.fieldData[e].field==t&&(1==this.fieldData[e].is_locking?(this.fieldData[e].is_locking=!1,this.fieldData[e].is_lock_display=!1,this.fieldData[e].icon="el-icon-lock"):(this.fieldData[e].icon="el-icon-unlock",this.fieldData[e].is_lock_display=!1,this.fieldData[e].is_locking=!0));this.setFieldConfirm()},getField:function(){var t=this;Object(o["c"])({name:"commpanyCancelApply"}).then((function(e){""!=e.data&&(t.fieldData=JSON.parse(e.data))})).catch((function(){}))},setFieldConfirm:function(){var t=this;Object(o["d"])({name:"commpanyCancelApply",value:JSON.stringify(this.fieldData)}).then((function(e){200==e.code?t.$message.success(e.message):t.$message.error(e.message),t.visiblepop=!1,t.menu_icon="menu"})).catch((function(){}))},locks:function(t){for(var e=0;e<=this.fieldData.length-1;e++)this.fieldData[e].field==t&&(1==this.fieldData[e].is_lock_display?this.fieldData[e].is_lock_display=!1:this.fieldData[e].is_lock_display=!0)},reset:function(t){"all"==t&&(this.auditScreen=""),"status"==t&&(this.auditScreen=""),this.confirm()},confirm:function(){if(this.currentPage=1,""!=this.auditScreen)for(var t=0;t<=this.status.length-1;t++)this.auditScreen==this.status[t].id&&(this.processingStatus={field:"status",name:this.status[t].name});else this.processingStatus="";this.memberCancelApplyList()},memberCancelApplyList:function(){var t=this;this.loading=!0,Object(l["i"])({key_type:this.key_type,keyword:this.keyword,page:this.currentPage,pagesize:this.pagesize,utype:1,sortType:this.sort_type,sort:this.sort,status:this.auditScreen}).then((function(e){t.tableData=e.data.items,t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize})).catch((function(){t.loading=!1}))},sortTable:function(t){var e=t.column,i=t.order;this.sortType="ascending"==i?"asc":"descending"==i?"desc":"",this.sort_type=e.property,this.sort=this.sortType,this.memberCancelApplyList()},goto:function(t){this.$router.push(t)},handleSelectionChange:function(t){if(this.tableIdarr=[],t.length>0){var e,i=Object(n["a"])(t);try{for(i.s();!(e=i.n()).done;){var s=e.value;this.tableIdarr.push(s.id)}}catch(a){i.e(a)}finally{i.f()}}},funDeleteBatch:function(){if(0==this.tableIdarr.length)return this.$message.error("请选择要删除的数据"),!1;var t=this;t.$confirm("确定删除吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};Object(l["g"])(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0}))})).catch((function(){}))},choose:function(){var t=this;this.tableData.forEach((function(e){t.$refs.multipleTable.toggleRowSelection(e)}))},fetchData:function(){var t=this;this.listLoading=!0,localStorage.getItem("apply_status")&&"1"==localStorage.getItem("apply_status")&&(this.auditScreen=1,this.processingStatus={name:"待处理",field:"status"},localStorage.setItem("apply_status",""));var e={key_type:this.key_type,keyword:this.keyword,page:this.currentPage,pagesize:this.pagesize,utype:1,sortType:this.sortType,sort:this.sort,status:this.auditScreen};Object(l["i"])(e).then((function(e){t.tableData=e.data.items,t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearch:function(){this.fetchData()},funSearchKeyword:function(){this.currentPage=1,this.fetchData()},funDel:function(t){var e=this;e.$confirm("确定删除吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var i={id:t.id};Object(l["f"])(i).then((function(t){return e.$message.success(t.message),e.fetchData(),!0}))})).catch((function(){}))},funHandle:function(t){var e=this,i=this;1===t.is_backups?this.cancellation(t):i.$confirm("您在注销之前，可备份保留会员信息，是否备份"+t.companyname+"的会员信息？","提示",{cancelButtonText:"进行处理",confirmButtonText:"备份",type:"warning"}).then((function(){var s={id:t.id};Object(l["e"])(s).then((function(s){return i.$message.success(s.message),e.cancellation(t),i.fetchData(),!0}))})).catch((function(){e.cancellation(t)}))},cancellation:function(t){var e=this;e.$confirm("确定注销吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var i={id:t.id,type:1};Object(l["h"])(i).then((function(t){return e.$message.success(t.message),e.fetchData(),!0}))})).catch((function(){}))},backups:function(t){var e=this;e.$confirm("确定备份吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var i={id:t.id};Object(l["e"])(i).then((function(t){return e.$message.success(t.message),e.fetchData(),!0}))})).catch((function(){}))}}},d=r,u=(i("5cc9"),i("2877")),p=Object(u["a"])(d,s,a,!1,null,"fccf5958",null);e["default"]=p.exports}}]);