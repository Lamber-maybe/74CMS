(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-05d60544"],{"0ccb":function(t,e,n){var a=n("50c4"),r=n("1148"),i=n("1d80"),s=Math.ceil,o=function(t){return function(e,n,o){var l,c,u=String(i(e)),d=u.length,f=void 0===o?" ":String(o),h=a(n);return h<=d||""==f?u:(l=h-d,c=r.call(f,s(l/f.length)),c.length>l&&(c=c.slice(0,l)),t?u+c:c+u)}};t.exports={start:o(!1),end:o(!0)}},3878:function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("意见建议")])]),n("div",{staticClass:"list-search"},[n("el-select",{staticClass:"list-options",attrs:{placeholder:"不限所属类型"},on:{change:t.funSearch},model:{value:t.type,callback:function(e){t.type=e},expression:"type"}},[n("el-option",{attrs:{label:"不限所属类型",value:""}}),t._l(t.optionsType,(function(t,e){return n("el-option",{key:e,attrs:{label:t.name,value:t.id}})}))],2),n("el-select",{staticClass:"list-options",attrs:{placeholder:"不限处理状态"},on:{change:t.funSearch},model:{value:t.status,callback:function(e){t.status=e},expression:"status"}},[n("el-option",{attrs:{label:"不限处理状态",value:""}}),n("el-option",{attrs:{label:"已处理",value:"1"}}),n("el-option",{attrs:{label:"未处理",value:"0"}})],1)],1),n("div",{staticClass:"spaceline"}),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"selection-change":t.handleSelectionChange}},[n("el-table-column",{attrs:{type:"selection",width:"42"}}),n("el-table-column",{attrs:{label:"类型","min-width":"60"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.type_cn)+" ")]}}])}),n("el-table-column",{attrs:{label:"处理情况","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-tag",{attrs:{type:t._f("statusFilter")(e.row.status)}},[t._v(" "+t._s(1==e.row.status?"已处理":"未处理")+" ")])]}}])}),n("el-table-column",{attrs:{label:"内容","min-width":"400"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-popover",{attrs:{placement:"right",trigger:"hover",width:"400"}},[t._v(" "+t._s(e.row.content)+" "),n("span",{attrs:{slot:"reference"},slot:"reference"},[t._v(" "+t._s(e.row.content_short)+" ")])])]}}])}),n("el-table-column",{attrs:{label:"联系方式","min-width":"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.mobile)+" 【uid："+t._s(e.row.uid)+"】 ")]}}])}),n("el-table-column",{attrs:{align:"center",prop:"addtime",label:"添加时间","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("i",{staticClass:"el-icon-time"}),n("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),n("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(n){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),n("div",{staticClass:"spaceline"}),n("el-row",{attrs:{gutter:20}},[n("el-col",{attrs:{span:8}},[n("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.funHandler}},[t._v(" 处理 ")]),n("el-button",{attrs:{size:"small",type:"danger"},on:{click:t.funDeleteBatch}},[t._v(" 删除 ")])],1),n("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[n("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1),n("el-dialog",{attrs:{title:"将所选信息设置为",visible:t.dialogVisible,width:"25%"},on:{"update:visible":function(e){t.dialogVisible=e}}},[n("el-form",{staticClass:"common-form",attrs:{"label-width":"80px"}},[n("el-form-item",{attrs:{label:"处理情况"}},[n("el-radio-group",{model:{value:t.setStatusVal,callback:function(e){t.setStatusVal=e},expression:"setStatusVal"}},[n("el-radio",{attrs:{label:"0"}},[t._v("未处理")]),n("el-radio",{attrs:{label:"1"}},[t._v("已处理")])],1)],1)],1),n("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[n("el-button",{on:{click:function(e){t.dialogVisible=!1}}},[t._v("取 消")]),n("el-button",{attrs:{type:"primary"},on:{click:t.funSetStatus}},[t._v(" 确 定 ")])],1)],1)],1)},r=[],i=n("b85c"),s=n("b775"),o=n("d722");function l(t){return Object(s["a"])({url:o["a"].feedbackList,method:"get",params:t})}function c(t){return Object(s["a"])({url:o["a"].feedbackDelete,method:"post",data:t})}function u(t){return Object(s["a"])({url:o["a"].feedbackHandler,method:"post",data:t})}var d=n("ed08"),f=n("52b5"),h={filters:{statusFilter:function(t){var e={1:"success",0:"danger"};return e[t]},timeFilter:function(t){return Object(d["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{setStatusVal:"0",dialogVisible:!1,tableIdarr:[],list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,optionsType:[],type:"",status:""}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0,Object(f["a"])({type:"feedback"}).then((function(e){t.optionsType=e.data;var n={status:t.status,type:t.type,page:t.currentPage,pagesize:t.pagesize};return l(n)})).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearch:function(){this.fetchData()},funDelete:function(t,e){var n=this;n.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};c(t).then((function(t){return n.$message.success(t.message),n.fetchData(),!0}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0==t.tableIdarr.length)return t.$message.error("请选择要删除的信息"),!1;t.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};c(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0}))})).catch((function(){}))},handleSelectionChange:function(t){if(this.tableIdarr=[],t.length>0){var e,n=Object(i["a"])(t);try{for(n.s();!(e=n.n()).done;){var a=e.value;this.tableIdarr.push(a.id)}}catch(r){n.e(r)}finally{n.f()}}},goTo:function(t){this.$router.push("/content/feedback/feedback/"+t)},funHandler:function(){if(0==this.tableIdarr.length)return this.$message.error("请选择要处理的数据"),!1;this.dialogVisible=!0},funSetStatus:function(){var t=this,e={id:this.tableIdarr,status:this.setStatusVal};u(e,"post").then((function(e){return 200==e.code?(t.$message.success(e.message),t.fetchData(),t.dialogVisible=!1,!0):(t.$message.error(e.message),!1)}))}}},p=h,g=n("2877"),b=Object(g["a"])(p,a,r,!1,null,null,null);e["default"]=b.exports},"4d90":function(t,e,n){"use strict";var a=n("23e7"),r=n("0ccb").start,i=n("9a0c");a({target:"String",proto:!0,forced:i},{padStart:function(t){return r(this,t,arguments.length>1?arguments[1]:void 0)}})},"52b5":function(t,e,n){"use strict";n.d(e,"a",(function(){return i}));var a=n("b775"),r=n("d722");function i(t){return Object(a["a"])({url:r["a"].getClassify,method:"get",params:t})}},"53ca":function(t,e,n){"use strict";n.d(e,"a",(function(){return a}));n("a4d3"),n("e01a"),n("d28b"),n("d3b7"),n("3ca3"),n("ddb0");function a(t){return a="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},a(t)}},"9a0c":function(t,e,n){var a=n("342f");t.exports=/Version\/10\.\d+(\.\d+)?( Mobile\/\w+)? Safari\//.test(a)},b85c:function(t,e,n){"use strict";n.d(e,"a",(function(){return r}));n("a4d3"),n("e01a"),n("d28b"),n("d3b7"),n("3ca3"),n("ddb0");var a=n("06c5");function r(t,e){var n="undefined"!==typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!n){if(Array.isArray(t)||(n=Object(a["a"])(t))||e&&t&&"number"===typeof t.length){n&&(t=n);var r=0,i=function(){};return{s:i,n:function(){return r>=t.length?{done:!0}:{done:!1,value:t[r++]}},e:function(t){throw t},f:i}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var s,o=!0,l=!1;return{s:function(){n=n.call(t)},n:function(){var t=n.next();return o=t.done,t},e:function(t){l=!0,s=t},f:function(){try{o||null==n["return"]||n["return"]()}finally{if(l)throw s}}}}},ed08:function(t,e,n){"use strict";n.d(e,"b",(function(){return s})),n.d(e,"a",(function(){return o})),n.d(e,"c",(function(){return l}));n("d3b7"),n("ac1f"),n("25f0"),n("4d90"),n("5319"),n("1276");var a=n("53ca"),r=n("a78e"),i=n.n(r);function s(t,e){if(0===arguments.length)return null;var n,r=e||"{y}-{m}-{d} {h}:{i}:{s}";"object"===Object(a["a"])(t)?n=t:("string"===typeof t&&/^[0-9]+$/.test(t)&&(t=parseInt(t)),"number"===typeof t&&t.toString().length<=10&&(t*=1e3),n=new Date(t));var i={y:n.getFullYear(),m:n.getMonth()+1,d:n.getDate(),h:n.getHours(),i:n.getMinutes(),s:n.getSeconds(),a:n.getDay()},s=r.replace(/{([ymdhisa])+}/g,(function(t,e){var n=i[e];return"a"===e?["日","一","二","三","四","五","六"][n]:n.toString().padStart(2,"0")}));return s}function o(t,e){t=(""+t).length<=10?1e3*parseInt(t):+t;var n=new Date(t),a=Date.now(),r=(a-n)/1e3;return r<30?"刚刚":r<3600?Math.ceil(r/60)+"分钟前":r<86400?Math.ceil(r/3600)+"小时前":r<172800?"1天前":e?s(t,e):n.getMonth()+1+"月"+n.getDate()+"日"+n.getHours()+"时"+n.getMinutes()+"分"}function l(t){var e={utype:t.utype,token:t.token,mobile:t.mobile,userIminfo:t.user_iminfo};i.a.set("qscms_visitor",JSON.stringify(e),{expires:7})}}}]);