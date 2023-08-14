(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2179bc04"],{"42e9":function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("管理员列表")])]),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[n("el-table-column",{attrs:{align:"center",label:"ID",width:"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.id)+" ")]}}])}),n("el-table-column",{attrs:{label:"登录名",prop:"username","min-width":"120"}}),n("el-table-column",{attrs:{label:"状态",prop:"status","min-width":"100",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[1===parseInt(e.row.status)?n("span",[t._v("正常")]):n("span",[t._v("锁定")])]}}])}),n("el-table-column",{attrs:{label:"角色",prop:"role_name","min-width":"120"}}),n("el-table-column",{attrs:{align:"center",prop:"openid",label:"微信绑定","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[""==e.row.openid?n("span",[n("el-button",{attrs:{type:"text"},on:{click:function(n){return t.funBind(e.row.id)}}},[t._v("[点击绑定]")])],1):n("span",[t._v("已绑定"),n("el-button",{attrs:{type:"text"},on:{click:function(n){return t.funBindCancel(e.row.id)}}},[t._v("[解绑]")])],1)]}}])}),n("el-table-column",{attrs:{align:"center",prop:"last_login_time",label:"最后登录时间","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("i",{staticClass:"el-icon-time"}),n("span",[t._v(t._s(t._f("timeFilter")(e.row.last_login_time)))])]}}])}),n("el-table-column",{attrs:{label:"最后登录IP",prop:"last_login_ip","min-width":"170"}}),n("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"330"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(n){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")]),n("el-button",{attrs:{size:"small"},on:{click:function(n){return t.funLog(e.row,1)}}},[t._v(" 登录日志 ")]),n("el-button",{attrs:{size:"small"},on:{click:function(n){return t.funLog(e.row)}}},[t._v(" 操作日志 ")]),1===parseInt(e.row.status)?n("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(n){return t.funLock(e.$index,e.row)}}},[t._v(" 锁定 ")]):n("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(n){return t.funDeblocking(e.$index,e.row)}}},[t._v(" 解锁 ")])]}}])})],1),n("div",{staticClass:"spaceline"}),n("el-row",{attrs:{gutter:20}},[n("el-col",{attrs:{span:8}},[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.goTo("add")}}},[t._v(" 添加 ")])],1),n("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[n("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,20,50,100],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1),t.dialogVisible?n("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogVisible,width:"60%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogVisible=e},close:t.closeDialog}},[n("dialist",{attrs:{"admin-id":t.adminId,"is-login":t.isLogin},on:{setDialogFormVisible:t.closeDialog}})],1):t._e()],1),t.showQrcode?n("el-dialog",{staticStyle:{"text-align":"center"},attrs:{title:"扫码绑定",visible:t.showQrcode,width:"15%","close-on-click-modal":!1},on:{"update:visible":function(e){t.showQrcode=e},close:function(e){t.showQrcode=!1}}},[n("div",{staticStyle:{"margin-bottom":"10px"}},[n("img",{attrs:{src:t.qrcodeSrc,width:"200",height:"200"}})])]):t._e()],1)},i=[],o=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[0==t.isLogin?n("div",{staticClass:"list-search"},[n("el-input",{staticClass:"input-with-select",staticStyle:{width:"300px"},attrs:{placeholder:"请输入搜索内容",size:"small"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword.apply(null,arguments)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[n("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1):t._e(),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"",size:"small","highlight-current-row":"",stripe:""}},[n("el-table-column",{attrs:{label:"时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),n("el-table-column",{attrs:{label:"操作",prop:"content"}}),n("el-table-column",{attrs:{label:"IP",prop:"ip"}}),n("el-table-column",{attrs:{label:"IP归属地",prop:"ip_addr"}})],1),n("div",{staticClass:"spaceline"}),n("el-row",{staticStyle:{"text-align":"right"}},[n("el-pagination",{attrs:{layout:"prev, pager, next",background:"",total:t.total,"current-page":t.currentPage,"page-size":t.pagesize},on:{"current-change":t.handleCurrentChange}})],1)],1)},r=[],l=n("50fc3"),c=n("ed08"),s={filters:{timeFilter:function(t){return Object(c["b"])(t,"{y}-{m}-{d} {h}:{i}:{s}")}},props:["adminId","isLogin"],data:function(){return{keyword:"",list:null,listLoading:!0,total:0,currentPage:1,pagesize:10}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={is_login:this.isLogin,keyword:this.keyword,admin_id:this.adminId,page:this.currentPage,pagesize:this.pagesize};Object(l["g"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearchKeyword:function(){this.currentPage=1,this.fetchData()}}},u=s,d=n("2877"),f=Object(d["a"])(u,o,r,!1,null,null,null),h=f.exports,g={components:{dialist:h},filters:{timeFilter:function(t){return Object(c["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{dialogTitle:"登录日志",admiId:0,isLogin:0,dialogVisible:!1,list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,qrcodeSrc:"",showQrcode:!1,total_company:0,total_clue:0}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={page:this.currentPage,pagesize:this.pagesize};Object(l["l"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funEdit:function(t,e){this.$router.push({path:"/sys/admin/list/edit",query:{id:e.id}})},funDelete:function(t,e){var n=this;n.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(l["j"])(t).then((function(t){return n.$message.success(t.message),n.fetchData(),!0}))})).catch((function(){}))},goTo:function(t){this.$router.push("/sys/admin/list/"+t)},funLog:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0;this.adminId=t.id,this.isLogin=e,this.dialogVisible=!0,this.dialogTitle=0===e?"操作日志":"登录日志"},closeDialog:function(){this.dialogVisible=!1},funBind:function(t){var e=this;Object(l["b"])({id:t}).then((function(t){e.qrcodeSrc=t.data,e.showQrcode=!0}))},funBindCancel:function(t){var e=this;e.$confirm("确定解绑吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){Object(l["c"])({id:t}).then((function(t){e.$message.success(t.message),e.fetchData()}))})).catch((function(){}))},funLock:function(t,e){var n=this,a=e.username,i={id:e.id};Object(l["o"])(i).then((function(t){var e=n;e.$confirm("当前管理员 "+a+" 有 "+t.data.total_company+" 线索, "+t.data.total_clue+" 客户,锁定后将自动释放且无法登录管理后台,是否继续？","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){Object(l["f"])(i).then((function(t){return e.$message.success(t.message),e.fetchData(),!0})).catch((function(){}))})).catch((function(){}))})).catch((function(){}))},funDeblocking:function(t,e){var n=e.username,a=this;a.$confirm("此操作将解锁管理员【"+n+"】, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(l["d"])(t).then((function(t){return a.$message.success(t.message),a.fetchData(),!0}))})).catch((function(){}))}}},p=g,m=Object(d["a"])(p,a,i,!1,null,null,null);e["default"]=m.exports},"50fc3":function(t,e,n){"use strict";n.d(e,"l",(function(){return o})),n.d(e,"i",(function(){return r})),n.d(e,"k",(function(){return l})),n.d(e,"j",(function(){return c})),n.d(e,"m",(function(){return s})),n.d(e,"g",(function(){return u})),n.d(e,"a",(function(){return d})),n.d(e,"b",(function(){return f})),n.d(e,"c",(function(){return h})),n.d(e,"e",(function(){return g})),n.d(e,"o",(function(){return p})),n.d(e,"f",(function(){return m})),n.d(e,"d",(function(){return b})),n.d(e,"h",(function(){return w})),n.d(e,"n",(function(){return v}));var a=n("b775"),i=n("d722");function o(t){return Object(a["a"])({url:i["a"].adminList,method:"get",params:t})}function r(t){return Object(a["a"])({url:i["a"].adminAdd,method:"post",data:t})}function l(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(a["a"])({url:i["a"].adminEdit,method:e,data:t}):Object(a["a"])({url:i["a"].adminEdit,method:e,params:t})}function c(t){return Object(a["a"])({url:i["a"].adminDelete,method:"post",data:t})}function s(t){return Object(a["a"])({url:i["a"].adminRoleOptions,method:"get",params:t})}function u(t){return Object(a["a"])({url:i["a"].adminLogList,method:"get",params:t})}function d(t){return Object(a["a"])({url:i["a"].adminAllList,method:"get",params:t})}function f(t){return Object(a["a"])({url:i["a"].adminBindQrcode,method:"get",params:t})}function h(t){return Object(a["a"])({url:i["a"].adminBindQrcodeCancel,method:"post",data:t})}function g(t){return Object(a["a"])({url:i["a"].adminIsBindWechat,method:"post",data:t})}function p(t){return Object(a["a"])({url:i["a"].getAdminCrmData,method:"post",data:t})}function m(t){return Object(a["a"])({url:i["a"].adminLock,method:"post",data:t})}function b(t){return Object(a["a"])({url:i["a"].adminDeblocking,method:"post",data:t})}function w(t){return Object(a["a"])({url:i["a"].adminLogTypeAll,method:"post",data:t})}function v(t){return Object(a["a"])({url:i["a"].delAdminLog,method:"post",data:t})}}}]);