(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-4b850cb8"],{"0ccb":function(t,e,n){var i=n("50c4"),a=n("1148"),r=n("1d80"),o=Math.ceil,l=function(t){return function(e,n,l){var s,c,u=String(r(e)),d=u.length,f=void 0===l?" ":String(l),g=i(n);return g<=d||""==f?u:(s=g-d,c=a.call(f,o(s/f.length)),c.length>s&&(c=c.slice(0,s)),t?u+c:c+u)}};t.exports={start:l(!1),end:l(!0)}},"42e9":function(t,e,n){"use strict";n.r(e);var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("管理员列表")])]),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[n("el-table-column",{attrs:{align:"center",label:"ID",width:"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.id)+" ")]}}])}),n("el-table-column",{attrs:{label:"登录名",prop:"username","min-width":"120"}}),n("el-table-column",{attrs:{label:"角色",prop:"role_name","min-width":"120"}}),n("el-table-column",{attrs:{label:"是否是销售",prop:"is_sc","min-width":"100",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[parseInt(e.row.is_sc)?n("span",[t._v("是")]):n("span",[t._v("不是")])]}}])}),n("el-table-column",{attrs:{align:"center",prop:"openid",label:"微信绑定","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[""==e.row.openid?n("span",[n("el-button",{attrs:{type:"text"},on:{click:function(n){return t.funBind(e.row.id)}}},[t._v("[点击绑定]")])],1):n("span",[t._v("已绑定"),n("el-button",{attrs:{type:"text"},on:{click:function(n){return t.funBindCancel(e.row.id)}}},[t._v("[解绑]")])],1)]}}])}),n("el-table-column",{attrs:{align:"center",prop:"last_login_time",label:"最后登录时间","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("i",{staticClass:"el-icon-time"}),n("span",[t._v(t._s(t._f("timeFilter")(e.row.last_login_time)))])]}}])}),n("el-table-column",{attrs:{label:"最后登录IP",prop:"last_login_ip","min-width":"170"}}),n("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"330"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(n){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")]),n("el-button",{attrs:{size:"small"},on:{click:function(n){return t.funLog(e.row,1)}}},[t._v(" 登录日志 ")]),n("el-button",{attrs:{size:"small"},on:{click:function(n){return t.funLog(e.row)}}},[t._v(" 操作日志 ")]),n("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(n){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),n("div",{staticClass:"spaceline"}),n("el-row",{attrs:{gutter:20}},[n("el-col",{attrs:{span:8}},[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.goTo("add")}}},[t._v(" 添加 ")])],1),n("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[n("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1),t.dialogVisible?n("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogVisible,width:"60%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogVisible=e},close:t.closeDialog}},[n("dialist",{attrs:{"admin-id":t.adminId,"is-login":t.isLogin},on:{setDialogFormVisible:t.closeDialog}})],1):t._e()],1),t.showQrcode?n("el-dialog",{staticStyle:{"text-align":"center"},attrs:{title:"扫码绑定",visible:t.showQrcode,width:"15%","close-on-click-modal":!1},on:{"update:visible":function(e){t.showQrcode=e},close:function(e){t.showQrcode=!1}}},[n("div",{staticStyle:{"margin-bottom":"10px"}},[n("img",{attrs:{src:t.qrcodeSrc,width:"200",height:"200"}})])]):t._e()],1)},a=[],r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[0==t.isLogin?n("div",{staticClass:"list-search"},[n("el-input",{staticClass:"input-with-select",staticStyle:{width:"300px"},attrs:{placeholder:"请输入搜索内容",size:"small"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword(e)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[n("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1):t._e(),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"",size:"small","highlight-current-row":"",stripe:""}},[n("el-table-column",{attrs:{label:"时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),n("el-table-column",{attrs:{label:"操作",prop:"content"}}),n("el-table-column",{attrs:{label:"IP",prop:"ip"}}),n("el-table-column",{attrs:{label:"IP归属地",prop:"ip_addr"}})],1),n("div",{staticClass:"spaceline"}),n("el-row",{staticStyle:{"text-align":"right"}},[n("el-pagination",{attrs:{layout:"prev, pager, next",background:"",total:t.total,"current-page":t.currentPage,"page-size":t.pagesize},on:{"current-change":t.handleCurrentChange}})],1)],1)},o=[],l=n("50fc3"),s=n("ed08"),c={filters:{timeFilter:function(t){return Object(s["b"])(t,"{y}-{m}-{d} {h}:{i}:{s}")}},props:["adminId","isLogin"],data:function(){return{keyword:"",list:null,listLoading:!0,total:0,currentPage:1,pagesize:10}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={is_login:this.isLogin,keyword:this.keyword,admin_id:this.adminId,page:this.currentPage,pagesize:this.pagesize};Object(l["d"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearchKeyword:function(){this.currentPage=1,this.fetchData()}}},u=c,d=n("2877"),f=Object(d["a"])(u,r,o,!1,null,null,null),g=f.exports,p={components:{dialist:g},filters:{timeFilter:function(t){return Object(s["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{dialogTitle:"登录日志",admiId:0,isLogin:0,dialogVisible:!1,list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,qrcodeSrc:"",showQrcode:!1}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={page:this.currentPage,pagesize:this.pagesize};Object(l["h"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funEdit:function(t,e){this.$router.push({path:"/sys/admin/list/edit",query:{id:e.id}})},funDelete:function(t,e){var n=this;n.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(l["f"])(t).then((function(t){return n.$message.success(t.message),n.fetchData(),!0}))})).catch((function(){}))},goTo:function(t){this.$router.push("/sys/admin/list/"+t)},funLog:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0;this.adminId=t.id,this.isLogin=e,this.dialogVisible=!0,this.dialogTitle=0===e?"操作日志":"登录日志"},closeDialog:function(){this.dialogVisible=!1},funBind:function(t){var e=this;Object(l["b"])({id:t}).then((function(t){e.qrcodeSrc=t.data,e.showQrcode=!0}))},funBindCancel:function(t){var e=this;e.$confirm("确定解绑吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){Object(l["c"])({id:t}).then((function(t){e.$message.success(t.message),e.fetchData()}))})).catch((function(){}))}}},h=p,m=Object(d["a"])(h,i,a,!1,null,null,null);e["default"]=m.exports},"4d90":function(t,e,n){"use strict";var i=n("23e7"),a=n("0ccb").start,r=n("9a0c");i({target:"String",proto:!0,forced:r},{padStart:function(t){return a(this,t,arguments.length>1?arguments[1]:void 0)}})},"50fc3":function(t,e,n){"use strict";n.d(e,"h",(function(){return r})),n.d(e,"e",(function(){return o})),n.d(e,"g",(function(){return l})),n.d(e,"f",(function(){return s})),n.d(e,"i",(function(){return c})),n.d(e,"d",(function(){return u})),n.d(e,"a",(function(){return d})),n.d(e,"b",(function(){return f})),n.d(e,"c",(function(){return g}));var i=n("b775"),a=n("d722");function r(t){return Object(i["a"])({url:a["a"].adminList,method:"get",params:t})}function o(t){return Object(i["a"])({url:a["a"].adminAdd,method:"post",data:t})}function l(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(i["a"])({url:a["a"].adminEdit,method:e,data:t}):Object(i["a"])({url:a["a"].adminEdit,method:e,params:t})}function s(t){return Object(i["a"])({url:a["a"].adminDelete,method:"post",data:t})}function c(t){return Object(i["a"])({url:a["a"].adminRoleOptions,method:"get",params:t})}function u(t){return Object(i["a"])({url:a["a"].adminLogList,method:"get",params:t})}function d(t){return Object(i["a"])({url:a["a"].adminAllList,method:"get",params:t})}function f(t){return Object(i["a"])({url:a["a"].adminBindQrcode,method:"get",params:t})}function g(t){return Object(i["a"])({url:a["a"].adminBindQrcodeCancel,method:"post",data:t})}},"53ca":function(t,e,n){"use strict";n.d(e,"a",(function(){return i}));n("a4d3"),n("e01a"),n("d28b"),n("d3b7"),n("3ca3"),n("ddb0");function i(t){return i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},i(t)}},"9a0c":function(t,e,n){var i=n("342f");t.exports=/Version\/10\.\d+(\.\d+)?( Mobile\/\w+)? Safari\//.test(i)},ed08:function(t,e,n){"use strict";n.d(e,"b",(function(){return o})),n.d(e,"a",(function(){return l})),n.d(e,"c",(function(){return s}));n("d3b7"),n("ac1f"),n("25f0"),n("4d90"),n("5319"),n("1276");var i=n("53ca"),a=n("a78e"),r=n.n(a);function o(t,e){if(0===arguments.length)return null;var n,a=e||"{y}-{m}-{d} {h}:{i}:{s}";"object"===Object(i["a"])(t)?n=t:("string"===typeof t&&/^[0-9]+$/.test(t)&&(t=parseInt(t)),"number"===typeof t&&t.toString().length<=10&&(t*=1e3),n=new Date(t));var r={y:n.getFullYear(),m:n.getMonth()+1,d:n.getDate(),h:n.getHours(),i:n.getMinutes(),s:n.getSeconds(),a:n.getDay()},o=a.replace(/{([ymdhisa])+}/g,(function(t,e){var n=r[e];return"a"===e?["日","一","二","三","四","五","六"][n]:n.toString().padStart(2,"0")}));return o}function l(t,e){t=(""+t).length<=10?1e3*parseInt(t):+t;var n=new Date(t),i=Date.now(),a=(i-n)/1e3;return a<30?"刚刚":a<3600?Math.ceil(a/60)+"分钟前":a<86400?Math.ceil(a/3600)+"小时前":a<172800?"1天前":e?o(t,e):n.getMonth()+1+"月"+n.getDate()+"日"+n.getHours()+"时"+n.getMinutes()+"分"}function s(t){var e={utype:t.utype,token:t.token,mobile:t.mobile,userIminfo:t.user_iminfo};r.a.set("qscms_visitor",JSON.stringify(e),{expires:7})}}}]);