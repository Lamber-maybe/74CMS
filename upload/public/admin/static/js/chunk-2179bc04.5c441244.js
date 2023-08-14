(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2179bc04"],{"42e9":function(t,e,n){"use strict";n.r(e);var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("管理员列表")])]),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[n("el-table-column",{attrs:{align:"center",label:"ID",width:"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.id)+" ")]}}])}),n("el-table-column",{attrs:{label:"登录名",prop:"username","min-width":"120"}}),n("el-table-column",{attrs:{label:"角色",prop:"role_name","min-width":"120"}}),n("el-table-column",{attrs:{label:"是否是销售",prop:"is_sc","min-width":"100",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[parseInt(e.row.is_sc)?n("span",[t._v("是")]):n("span",[t._v("不是")])]}}])}),n("el-table-column",{attrs:{align:"center",prop:"openid",label:"微信绑定","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[""==e.row.openid?n("span",[n("el-button",{attrs:{type:"text"},on:{click:function(n){return t.funBind(e.row.id)}}},[t._v("[点击绑定]")])],1):n("span",[t._v("已绑定"),n("el-button",{attrs:{type:"text"},on:{click:function(n){return t.funBindCancel(e.row.id)}}},[t._v("[解绑]")])],1)]}}])}),n("el-table-column",{attrs:{align:"center",prop:"last_login_time",label:"最后登录时间","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("i",{staticClass:"el-icon-time"}),n("span",[t._v(t._s(t._f("timeFilter")(e.row.last_login_time)))])]}}])}),n("el-table-column",{attrs:{label:"最后登录IP",prop:"last_login_ip","min-width":"170"}}),n("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"330"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(n){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")]),n("el-button",{attrs:{size:"small"},on:{click:function(n){return t.funLog(e.row,1)}}},[t._v(" 登录日志 ")]),n("el-button",{attrs:{size:"small"},on:{click:function(n){return t.funLog(e.row)}}},[t._v(" 操作日志 ")]),n("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(n){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),n("div",{staticClass:"spaceline"}),n("el-row",{attrs:{gutter:20}},[n("el-col",{attrs:{span:8}},[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.goTo("add")}}},[t._v(" 添加 ")])],1),n("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[n("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1),t.dialogVisible?n("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogVisible,width:"60%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogVisible=e},close:t.closeDialog}},[n("dialist",{attrs:{"admin-id":t.adminId,"is-login":t.isLogin},on:{setDialogFormVisible:t.closeDialog}})],1):t._e()],1),t.showQrcode?n("el-dialog",{staticStyle:{"text-align":"center"},attrs:{title:"扫码绑定",visible:t.showQrcode,width:"15%","close-on-click-modal":!1},on:{"update:visible":function(e){t.showQrcode=e},close:function(e){t.showQrcode=!1}}},[n("div",{staticStyle:{"margin-bottom":"10px"}},[n("img",{attrs:{src:t.qrcodeSrc,width:"200",height:"200"}})])]):t._e()],1)},a=[],l=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[0==t.isLogin?n("div",{staticClass:"list-search"},[n("el-input",{staticClass:"input-with-select",staticStyle:{width:"300px"},attrs:{placeholder:"请输入搜索内容",size:"small"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword.apply(null,arguments)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[n("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1):t._e(),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"",size:"small","highlight-current-row":"",stripe:""}},[n("el-table-column",{attrs:{label:"时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),n("el-table-column",{attrs:{label:"操作",prop:"content"}}),n("el-table-column",{attrs:{label:"IP",prop:"ip"}}),n("el-table-column",{attrs:{label:"IP归属地",prop:"ip_addr"}})],1),n("div",{staticClass:"spaceline"}),n("el-row",{staticStyle:{"text-align":"right"}},[n("el-pagination",{attrs:{layout:"prev, pager, next",background:"",total:t.total,"current-page":t.currentPage,"page-size":t.pagesize},on:{"current-change":t.handleCurrentChange}})],1)],1)},r=[],o=n("50fc3"),s=n("ed08"),c={filters:{timeFilter:function(t){return Object(s["b"])(t,"{y}-{m}-{d} {h}:{i}:{s}")}},props:["adminId","isLogin"],data:function(){return{keyword:"",list:null,listLoading:!0,total:0,currentPage:1,pagesize:10}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={is_login:this.isLogin,keyword:this.keyword,admin_id:this.adminId,page:this.currentPage,pagesize:this.pagesize};Object(o["d"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearchKeyword:function(){this.currentPage=1,this.fetchData()}}},u=c,d=n("2877"),g=Object(d["a"])(u,l,r,!1,null,null,null),h=g.exports,p={components:{dialist:h},filters:{timeFilter:function(t){return Object(s["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{dialogTitle:"登录日志",admiId:0,isLogin:0,dialogVisible:!1,list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,qrcodeSrc:"",showQrcode:!1}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={page:this.currentPage,pagesize:this.pagesize};Object(o["h"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funEdit:function(t,e){this.$router.push({path:"/sys/admin/list/edit",query:{id:e.id}})},funDelete:function(t,e){var n=this;n.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(o["f"])(t).then((function(t){return n.$message.success(t.message),n.fetchData(),!0}))})).catch((function(){}))},goTo:function(t){this.$router.push("/sys/admin/list/"+t)},funLog:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0;this.adminId=t.id,this.isLogin=e,this.dialogVisible=!0,this.dialogTitle=0===e?"操作日志":"登录日志"},closeDialog:function(){this.dialogVisible=!1},funBind:function(t){var e=this;Object(o["b"])({id:t}).then((function(t){e.qrcodeSrc=t.data,e.showQrcode=!0}))},funBindCancel:function(t){var e=this;e.$confirm("确定解绑吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){Object(o["c"])({id:t}).then((function(t){e.$message.success(t.message),e.fetchData()}))})).catch((function(){}))}}},f=p,m=Object(d["a"])(f,i,a,!1,null,null,null);e["default"]=m.exports},"50fc3":function(t,e,n){"use strict";n.d(e,"h",(function(){return l})),n.d(e,"e",(function(){return r})),n.d(e,"g",(function(){return o})),n.d(e,"f",(function(){return s})),n.d(e,"i",(function(){return c})),n.d(e,"d",(function(){return u})),n.d(e,"a",(function(){return d})),n.d(e,"b",(function(){return g})),n.d(e,"c",(function(){return h}));var i=n("b775"),a=n("d722");function l(t){return Object(i["a"])({url:a["a"].adminList,method:"get",params:t})}function r(t){return Object(i["a"])({url:a["a"].adminAdd,method:"post",data:t})}function o(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(i["a"])({url:a["a"].adminEdit,method:e,data:t}):Object(i["a"])({url:a["a"].adminEdit,method:e,params:t})}function s(t){return Object(i["a"])({url:a["a"].adminDelete,method:"post",data:t})}function c(t){return Object(i["a"])({url:a["a"].adminRoleOptions,method:"get",params:t})}function u(t){return Object(i["a"])({url:a["a"].adminLogList,method:"get",params:t})}function d(t){return Object(i["a"])({url:a["a"].adminAllList,method:"get",params:t})}function g(t){return Object(i["a"])({url:a["a"].adminBindQrcode,method:"get",params:t})}function h(t){return Object(i["a"])({url:a["a"].adminBindQrcodeCancel,method:"post",data:t})}}}]);