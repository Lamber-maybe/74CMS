(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-5e575da2"],{2638:function(t,e,n){"use strict";var a=n("edc3"),r=n.n(a);r.a},4124:function(t,e,n){"use strict";n.d(e,"i",(function(){return i})),n.d(e,"f",(function(){return s})),n.d(e,"h",(function(){return o})),n.d(e,"g",(function(){return u})),n.d(e,"d",(function(){return c})),n.d(e,"b",(function(){return l})),n.d(e,"c",(function(){return d})),n.d(e,"e",(function(){return f})),n.d(e,"a",(function(){return g}));var a=n("b775"),r=n("d722");function i(t){return Object(a["a"])({url:r["a"].marketTplList,method:"get",params:t})}function s(t){return Object(a["a"])({url:r["a"].marketTplAdd,method:"post",data:t})}function o(t){return Object(a["a"])({url:r["a"].marketTplEdit,method:"post",data:t})}function u(t){return Object(a["a"])({url:r["a"].marketTplDelete,method:"post",data:t})}function c(t){return Object(a["a"])({url:r["a"].marketTaskList,method:"get",params:t})}function l(t){return Object(a["a"])({url:r["a"].marketTaskAdd,method:"post",data:t})}function d(t){return Object(a["a"])({url:r["a"].marketTaskDelete,method:"post",data:t})}function f(t){return Object(a["a"])({url:r["a"].marketTaskRun,method:"post",data:t})}function g(t){return Object(a["a"])({url:r["a"].marketSearchMember,method:"get",params:t})}},"73ae":function(t,e,n){"use strict";n.d(e,"d",(function(){return i})),n.d(e,"a",(function(){return s})),n.d(e,"c",(function(){return o})),n.d(e,"g",(function(){return u})),n.d(e,"f",(function(){return c})),n.d(e,"b",(function(){return l})),n.d(e,"e",(function(){return d}));var a=n("b775"),r=n("d722");function i(t){return Object(a["a"])({url:r["a"].cronList,method:"get",params:t})}function s(t){return Object(a["a"])({url:r["a"].cronAdd,method:"post",data:t})}function o(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(a["a"])({url:r["a"].cronEdit,method:e,data:t}):Object(a["a"])({url:r["a"].cronEdit,method:e,params:t})}function u(t){return Object(a["a"])({url:r["a"].cronSet,method:"post",data:t})}function c(t){return Object(a["a"])({url:r["a"].cronRun,method:"post",data:t})}function l(t){return Object(a["a"])({url:r["a"].cronDelete,method:"post",data:t})}function d(t){return Object(a["a"])({url:r["a"].cronLog,method:"get",params:t})}},ed08:function(t,e,n){"use strict";n.d(e,"a",(function(){return s})),n.d(e,"b",(function(){return o}));n("d3b7"),n("ac1f"),n("25f0"),n("4d90"),n("5319"),n("1276");var a=n("53ca"),r=n("a78e"),i=n.n(r);function s(t,e){if(0===arguments.length)return null;var n,r=e||"{y}-{m}-{d} {h}:{i}:{s}";"object"===Object(a["a"])(t)?n=t:("string"===typeof t&&/^[0-9]+$/.test(t)&&(t=parseInt(t)),"number"===typeof t&&10===t.toString().length&&(t*=1e3),n=new Date(t));var i={y:n.getFullYear(),m:n.getMonth()+1,d:n.getDate(),h:n.getHours(),i:n.getMinutes(),s:n.getSeconds(),a:n.getDay()},s=r.replace(/{([ymdhisa])+}/g,(function(t,e){var n=i[e];return"a"===e?["日","一","二","三","四","五","六"][n]:n.toString().padStart(2,"0")}));return s}function o(t){var e={utype:t.utype,token:t.token,mobile:t.mobile,userIminfo:t.user_iminfo};i.a.set("qscms_visitor",JSON.stringify(e),{expires:7})}},edc3:function(t,e,n){},fcf7:function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[!1===t.run_loading?n("div",[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("发送任务列表")])]),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[n("el-table-column",{attrs:{label:"任务名称",prop:"title","min-width":"150"}}),n("el-table-column",{attrs:{align:"center",label:"生成时间","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("i",{staticClass:"el-icon-time"}),n("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}],null,!1,123474231)}),n("el-table-column",{attrs:{label:"发送对象","min-width":"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(" "+t._s(t._f("targetFilter")(e.row.target))+"("+t._s(e.row.total)+") ")])]}}],null,!1,811500369)}),n("el-table-column",{attrs:{align:"center",label:"内容详情","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-popover",{attrs:{placement:"bottom",width:"200",trigger:"hover",content:e.row.content}},[n("el-button",{attrs:{slot:"reference",type:"text"},slot:"reference"},[t._v("[详情]")])],1)]}}],null,!1,1873562656)}),n("el-table-column",{attrs:{label:"发送渠道","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(t._s(t._f("sendTypeFilter")(e.row.send_type)))])]}}],null,!1,383596064)}),n("el-table-column",{attrs:{align:"center",label:"执行状态","min-width":"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.status?n("span",{staticClass:"font_info"},[t._v(" 已执行(成功"+t._s(e.row.success)+"，失败"+t._s(e.row.total-e.row.success)+") ")]):t._e(),0==e.row.status?n("span",{staticClass:"font_warning"},[t._v(" 未执行 ")]):t._e()]}}],null,!1,2880699312)}),n("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"160"},scopedSlots:t._u([{key:"default",fn:function(e){return[0==e.row.status?n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(n){return t.funRun(e.row,1,0)}}},[t._v(" 执行 ")]):n("el-button",{attrs:{size:"small",type:"primary",disabled:""}},[t._v(" 执行 ")]),n("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(n){return t.funDelete(e.row)}}},[t._v(" 删除 ")])]}}],null,!1,868038729)})],1),n("div",{staticClass:"spaceline"}),n("el-row",{attrs:{gutter:20}},[n("el-col",{attrs:{span:8}}),n("el-col",{staticStyle:{"text-align":"right"},attrs:{offset:8,span:16}},[n("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1):t._e(),!0===t.run_loading?n("div",{staticClass:"submiting-block"},[n("el-progress",{attrs:{type:"circle",percentage:t.percentage,"stroke-width":18,width:220}}),n("p",{staticStyle:{"font-weight":"bold","font-size":"20px","text-align":"center","margin-bottom":"50px",color:"#333"}},[t._v(" 正在发送 ")]),n("p",[t._v("任务总量："+t._s(t.run_info.total)+" 已经完成 "+t._s(t.run_info.success)+" 条")])],1):t._e()])],1)},r=[],i=(n("a15b"),n("a9e3"),n("b680"),n("ac1f"),n("1276"),n("4124")),s=n("73ae"),o=n("ed08"),u={filters:{colorFilter:function(t){return 1==t?"success":"danger"},timeFilter:function(t){return 0==t?"从未执行":Object(o["a"])(t,"{y}-{m}-{d} {h}:{i}")},targetFilter:function(t){switch(t){case"all":case"uid":case"remind":return"全部会员";case"company":case"job":case"setmeal":return"企业会员";case"resume":return"个人会员";default:return"全部会员"}},sendTypeFilter:function(t){for(var e=[],n=t.split(","),a=0;a<n.length;a++)switch(n[a]){case"message":e.push("站内信");break;case"sms":e.push("短信");break;case"weixin":e.push("微信");break;case"email":e.push("Email");break;default:break}return e.join(",")}},data:function(){return{run_loading:!1,run_info:{total:"正在计算",success:0},percentage:0,cron_id:0,item_info:null,dialogTitle:"",dialogFormVisible:!1,dialogListVisible:!1,list:null,listLoading:!0,total:0,currentPage:1,pagesize:10}},created:function(){this.fetchData()},methods:{funLog:function(t){this.cron_id=t.id,this.dialogListVisible=!0},fetchData:function(){var t=this;this.listLoading=!0;var e={page:this.currentPage,pagesize:this.pagesize};Object(i["d"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearch:function(){this.fetchData()},closeDialog:function(){this.dialogFormVisible=!1},closeListDialog:function(){this.dialogListVisible=!1},funAdd:function(){this.item_info=null,this.dialogTitle="添加",this.dialogFormVisible=!0},funEdit:function(t){this.item_info=t,this.dialogTitle="编辑",this.dialogFormVisible=!0},funDelete:function(t){var e=this;e.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var n={id:t.id};Object(i["c"])(n).then((function(t){return e.$message.success(t.message),e.fetchData(),!0}))})).catch((function(){}))},funRun:function(t,e,n){var a=this;this.run_loading=!0;var r={id:t.id,page:e};Object(i["e"])(r).then((function(r){1==r.data.continue?(a.run_info.total=r.data.total,a.run_info.success=parseInt(n)+parseInt(r.data.success),e++,a.percentage=Number(a.run_info.success)/Number(a.run_info.total)*100,a.percentage=a.percentage.toFixed(2),a.percentage=Number(a.percentage),a.funRun(t,e,a.run_info.success)):(a.$message.success(r.message),setTimeout((function(){a.run_loading=!1,a.funInitRuninfo(),a.fetchData()}),1500))})).catch((function(){a.fetchData(),a.run_loading=!1}))},funInitRuninfo:function(){this.percentage=0,this.run_info={total:"正在计算",success:0}},funStatus:function(t,e,n){var a=this,r={id:t.id,status:e};Object(s["g"])(r).then((function(t){return a.$message.success(t.message),a.list[n].status=e,!0}))}}},c=u,l=(n("2638"),n("2877")),d=Object(l["a"])(c,a,r,!1,null,"7bb75320",null);e["default"]=d.exports}}]);