(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-a6d5c198"],{"0ccb":function(e,t,a){var n=a("50c4"),r=a("1148"),o=a("1d80"),i=Math.ceil,l=function(e){return function(t,a,l){var s,c,u=String(o(t)),d=u.length,f=void 0===l?" ":String(l),p=n(a);return p<=d||""==f?u:(s=p-d,c=r.call(f,i(s/f.length)),c.length>s&&(c=c.slice(0,s)),e?u+c:c+u)}};e.exports={start:l(!1),end:l(!0)}},4028:function(e,t,a){"use strict";a("6fd6")},"4d90":function(e,t,a){"use strict";var n=a("23e7"),r=a("0ccb").start,o=a("9a0c");n({target:"String",proto:!0,forced:o},{padStart:function(e){return r(this,e,arguments.length>1?arguments[1]:void 0)}})},"53ca":function(e,t,a){"use strict";a.d(t,"a",(function(){return n}));a("a4d3"),a("e01a"),a("d3b7"),a("d28b"),a("3ca3"),a("ddb0");function n(e){return n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},n(e)}},"6fd6":function(e,t,a){},7123:function(e,t,a){},"7db0":function(e,t,a){"use strict";var n=a("23e7"),r=a("b727").find,o=a("44d2"),i=a("ae40"),l="find",s=!0,c=i(l);l in[]&&Array(1)[l]((function(){s=!1})),n({target:"Array",proto:!0,forced:s||!c},{find:function(e){return r(this,e,arguments.length>1?arguments[1]:void 0)}}),o(l)},"9a0c":function(e,t,a){var n=a("342f");e.exports=/Version\/10\.\d+(\.\d+)?( Mobile\/\w+)? Safari\//.test(n)},b85c:function(e,t,a){"use strict";a.d(t,"a",(function(){return r}));a("a4d3"),a("e01a"),a("d3b7"),a("d28b"),a("3ca3"),a("ddb0");var n=a("06c5");function r(e,t){var a="undefined"!==typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!a){if(Array.isArray(e)||(a=Object(n["a"])(e))||t&&e&&"number"===typeof e.length){a&&(e=a);var r=0,o=function(){};return{s:o,n:function(){return r>=e.length?{done:!0}:{done:!1,value:e[r++]}},e:function(e){throw e},f:o}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,l=!0,s=!1;return{s:function(){a=a.call(e)},n:function(){var e=a.next();return l=e.done,e},e:function(e){s=!0,i=e},f:function(){try{l||null==a["return"]||a["return"]()}finally{if(s)throw i}}}}},c740:function(e,t,a){"use strict";var n=a("23e7"),r=a("b727").findIndex,o=a("44d2"),i=a("ae40"),l="findIndex",s=!0,c=i(l);l in[]&&Array(1)[l]((function(){s=!1})),n({target:"Array",proto:!0,forced:s||!c},{findIndex:function(e){return r(this,e,arguments.length>1?arguments[1]:void 0)}}),o(l)},d2e0:function(e,t,a){"use strict";a("7123")},ed08:function(e,t,a){"use strict";a.d(t,"b",(function(){return i})),a.d(t,"a",(function(){return l})),a.d(t,"c",(function(){return s}));var n=a("53ca"),r=(a("ac1f"),a("00b4"),a("d3b7"),a("25f0"),a("5319"),a("4d90"),a("1276"),a("e9c4"),a("a78e")),o=a.n(r);function i(e,t){if(0===arguments.length)return null;var a,r=t||"{y}-{m}-{d} {h}:{i}:{s}";"object"===Object(n["a"])(e)?a=e:("string"===typeof e&&/^[0-9]+$/.test(e)&&(e=parseInt(e)),"number"===typeof e&&e.toString().length<=10&&(e*=1e3),a=new Date(e));var o={y:a.getFullYear(),m:a.getMonth()+1,d:a.getDate(),h:a.getHours(),i:a.getMinutes(),s:a.getSeconds(),a:a.getDay()},i=r.replace(/{([ymdhisa])+}/g,(function(e,t){var a=o[t];return"a"===t?["日","一","二","三","四","五","六"][a]:a.toString().padStart(2,"0")}));return i}function l(e,t){e=(""+e).length<=10?1e3*parseInt(e):+e;var a=new Date(e),n=Date.now(),r=(n-a)/1e3;return r<30?"刚刚":r<3600?Math.ceil(r/60)+"分钟前":r<86400?Math.ceil(r/3600)+"小时前":r<172800?"1天前":t?i(e,t):a.getMonth()+1+"月"+a.getDate()+"日"+a.getHours()+"时"+a.getMinutes()+"分"}function s(e){var t={utype:e.utype,token:e.token,mobile:e.mobile,userIminfo:e.user_iminfo};o.a.set("qscms_visitor",JSON.stringify(t),{expires:7})}},f947:function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[e._v("场景码列表")])]),a("div",{staticClass:"list-search"},[a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限页面"},on:{change:e.funSearch},model:{value:e.searchForm.type,callback:function(t){e.$set(e.searchForm,"type",t)},expression:"searchForm.type"}},[a("el-option",{attrs:{label:"不限页面",value:""}}),e._l(e.options_type,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.alias}})}))],2),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限类型"},on:{change:e.funSearch},model:{value:e.searchForm.platform,callback:function(t){e.$set(e.searchForm,"platform",t)},expression:"searchForm.platform"}},[a("el-option",{attrs:{label:"不限类型",value:""}}),e._l(e.options_platform,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.value}})}))],2),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限有效状态"},on:{change:e.funSearch},model:{value:e.searchForm.status,callback:function(t){e.$set(e.searchForm,"status",t)},expression:"searchForm.status"}},[a("el-option",{attrs:{label:"不限有效状态",value:""}}),a("el-option",{attrs:{label:"有效",value:"1"}}),a("el-option",{attrs:{label:"已过期",value:"0"}})],1),a("el-input",{staticClass:"input-with-select",attrs:{placeholder:"请输入搜索内容"},model:{value:e.searchForm.keyword,callback:function(t){e.$set(e.searchForm,"keyword",t)},expression:"searchForm.keyword"}},[a("el-select",{staticClass:"input-sel",attrs:{slot:"prepend",placeholder:"请选择"},slot:"prepend",model:{value:e.searchForm.key_type,callback:function(t){e.$set(e.searchForm,"key_type",t)},expression:"searchForm.key_type"}},[a("el-option",{attrs:{label:"场景码名称",value:"1"}})],1),a("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:e.funSearchKeyword},slot:"append"})],1)],1),a("div",{staticClass:"spaceline"}),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.listLoading,expression:"listLoading"}],attrs:{data:e.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"selection-change":e.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection",width:"42"}}),a("el-table-column",{attrs:{label:"名称",prop:"title","min-width":"150"}}),a("el-table-column",{attrs:{align:"center",label:"页面",prop:"type_cn","min-width":"100"}}),a("el-table-column",{attrs:{align:"center",label:"到期时间","min-width":"150"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("i",{staticClass:"el-icon-time"}),t.row.deadline>0?a("span",[e._v(e._s(e._f("timeFilter")(t.row.deadline)))]):a("span",[e._v("永久有效")])]}}])}),a("el-table-column",{attrs:{align:"center",label:"类型",prop:"platform_cn","min-width":"120"}}),a("el-table-column",{attrs:{align:"center",label:"扫码次数",prop:"total_scan","min-width":"80"}}),a("el-table-column",{attrs:{align:"center",label:"关注人数",prop:"total_subscribe","min-width":"80"}}),a("el-table-column",{attrs:{align:"center",label:"注册量",prop:"total_reg","min-width":"80"}}),a("el-table-column",{attrs:{align:"center",label:"是否有效","min-width":"100"},scopedSlots:e._u([{key:"default",fn:function(t){return[t.row.deadline>0?a("el-tag",{attrs:{type:e._f("colorFilter")(t.row.status)}},[e._v(e._s(1==t.row.status?"有效":"已过期"))]):a("el-tag",{attrs:{type:"success"}},[e._v("有效")])]}}])}),a("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"250"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-button",{attrs:{size:"small"},on:{click:function(a){return e.funShowQrcode(t.row)}}},[e._v(" 场景码 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return e.funDelete(t.row)}}},[e._v(" 删除 ")]),0==t.row.deadline||1==t.row.status?a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return e.copyUrl(t.row)}}},[e._v(" 复制链接 ")]):e._e()]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-row",{attrs:{gutter:20}},[a("el-col",{attrs:{span:8}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){e.dialogFormVisible=!0}}},[e._v(" 添加场景码 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:e.funDeleteBatch}},[e._v(" 删除所选 ")])],1),a("el-col",{staticStyle:{"text-align":"right"},attrs:{offset:8,span:16}},[a("el-pagination",{attrs:{background:"","current-page":e.currentPage,"page-sizes":[10,15,20,30,40],"page-size":e.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:e.total},on:{"size-change":e.handleSizeChange,"current-change":e.handleCurrentChange}})],1)],1)],1),e.showQrcode?a("el-dialog",{staticStyle:{"text-align":"center"},attrs:{title:e.qrcodeTitle,visible:e.showQrcode,width:"15%","close-on-click-modal":!1},on:{"update:visible":function(t){e.showQrcode=t},close:function(t){e.showQrcode=!1}}},[a("div",{staticStyle:{"margin-bottom":"10px"}},[a("img",{attrs:{src:e.qrcodeSrc,width:"200",height:"200"}})]),a("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.funDownload(e.qrcodeId)}}},[e._v("下载")])],1):e._e(),e.dialogFormVisible?a("el-dialog",{attrs:{title:"添加场景码",visible:e.dialogFormVisible,width:"35%","close-on-click-modal":!1},on:{"update:visible":function(t){e.dialogFormVisible=t},close:e.closeDialog}},[a("diaform",{on:{setDialogFormVisible:e.closeDialog,pageReload:e.fetchData}})],1):e._e()],1)},r=[],o=a("b85c"),i=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("el-form",{ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"150px",rules:e.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"场景码名称",prop:"title"}},[a("el-input",{model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}})],1),a("el-form-item",{attrs:{label:"场景码类型",prop:"platform"}},[a("el-radio-group",{model:{value:e.form.platform,callback:function(t){e.$set(e.form,"platform",t)},expression:"form.platform"}},e._l(e.options_platform,(function(t,n){return a("el-radio",{key:n,attrs:{label:t.value}},[e._v(e._s(t.name))])})),1)],1),0==e.form.platform?a("el-form-item",{attrs:{label:"有效期至",prop:"deadline",required:""}},[a("el-date-picker",{attrs:{type:"date",placeholder:"选择日期",format:"yyyy-MM-dd","picker-options":e.pickerOptions},model:{value:e.form.deadline,callback:function(t){e.$set(e.form,"deadline",t)},expression:"form.deadline"}})],1):e._e(),a("el-form-item",{attrs:{label:"场景码页面",prop:"type"}},[a("el-select",{attrs:{placeholder:"请选择"},on:{change:e.changeType},model:{value:e.form.type,callback:function(t){e.$set(e.form,"type",t)},expression:"form.type"}},e._l(e.options_type,(function(e){return a("el-option",{key:e.alias,attrs:{label:e.name,value:e.alias}})})),1)],1),e.showParam?a("el-form-item",{attrs:{label:e.paramLabel,prop:"paramid",required:""}},[a("el-select",{attrs:{filterable:"",remote:"","reserve-keyword":"",placeholder:"请输入关键词","remote-method":e.searchList,loading:e.loading},model:{value:e.form.paramid,callback:function(t){e.$set(e.form,"paramid",t)},expression:"form.paramid"}},e._l(e.options_search_list,(function(t){return a("el-option",{key:t.id,attrs:{label:t.label,value:t.id}},[a("span",{staticStyle:{float:"left"}},[e._v(e._s(t.label))]),""!=t.label_small?a("span",{staticStyle:{float:"right",color:"#8492a6","font-size":"13px"}},[e._v(e._s(t.label_small))]):e._e()])})),1)],1):e._e(),a("el-form-item",{attrs:{label:" "}},[a("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v(" 确定 ")]),a("el-button",{on:{click:e.closeDialog}},[e._v(" 取 消 ")])],1)],1)],1)},l=[],s=a("5530"),c=(a("7db0"),a("d3b7"),a("c740"),a("a434"),a("b775")),u=a("d722");function d(e){return Object(c["a"])({url:u["a"].sceneQrcodeList,method:"get",params:e})}function f(e){return Object(c["a"])({url:u["a"].sceneQrcodeAdd,method:"post",data:e})}function p(e){return Object(c["a"])({url:u["a"].sceneQrcodeDelete,method:"post",data:e})}function m(e){return Object(c["a"])({url:u["a"].sceneQrcodeDeleteAll,method:"post",data:e})}function h(e){return Object(c["a"])({url:u["a"].sceneQrcodePlatformList,method:"get",params:e})}function g(e){return Object(c["a"])({url:u["a"].sceneQrcodeTypeList,method:"get",params:e})}function b(e){return Object(c["a"])({url:u["a"].sceneSearchList,method:"get",params:e})}var y={props:["itemInfo"],data:function(){var e=this,t=function(t,a,n){!0===e.showParam&&""===a?n(new Error("请输入"+e.paramLabel)):n()},a=function(t,a,n){0==e.form.platform&&""===a?n(new Error("请选择有效期")):n()};return{loading:!1,pickerOptions:{disabledDate:function(e){var t=new Date,a=new Date;return a.setDate(t.getDate()+30),e.getTime()>a.getTime()||e.getTime()<t.getTime()}},paramLabel:"标题/ID",showParam:!1,form:{title:"",type:"",deadline:"",platform:0,paramid:""},options_search_list:[],options_platform:[],options_type:[],rules:{title:[{required:!0,message:"请输入场景码名称",trigger:"blur"}],platform:[{required:!0,message:"请选择平台",trigger:"change"}],deadline:[{validator:a,trigger:"change"}],type:[{required:!0,message:"请选择类型",trigger:"change"}],paramid:[{validator:t,trigger:"blur"}]}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;h({}).then((function(t){e.options_platform=t.data})).catch((function(){})),g({}).then((function(t){e.options_type=t.data})).catch((function(){}))},addSave:function(e,t){var a=this;this.$refs[t].validate((function(t){if(!t)return!1;f(e).then((function(e){return a.$message.success(e.message),a.closeDialog(),a.pageReload(),!0})).catch((function(){}))}))},onSubmit:function(e){var t=this,a=Object(s["a"])({},t.form);t.addSave(a,e)},closeDialog:function(){this.$emit("setDialogFormVisible")},pageReload:function(){this.$emit("pageReload")},changeType:function(e){var t=this.options_type.find((function(t){return t.alias==e}));switch(""!=t.offiaccount_param_name?this.showParam=!0:this.showParam=!1,e){case"job":this.paramLabel="职位名称/ID";break;case"company":this.paramLabel="企业名称/ID";break;case"resume":this.paramLabel="姓名/ID";break;case"notice":this.paramLabel="标题/ID";break;case"jobfairol":this.paramLabel="标题/ID";break;case"news":this.paramLabel="标题/ID";break}if("jobfairol"==t.alias){2==this.form.platform&&(this.form.platform=0);var a=this.options_platform.findIndex((function(e){return 2===e.value}));a>=0&&this.options_platform.splice(a,1)}else this.fetchData()},searchList:function(e){var t=this;if(""==e)return this.options_search_list=[],!1;var a=this.form.type,n={keyword:e,type:a};b(n).then((function(e){t.options_search_list=e.data})).catch((function(){}))}}},v=y,_=(a("d2e0"),a("2877")),w=Object(_["a"])(v,i,l,!1,null,"7e79a5a4",null),k=w.exports,D=a("ed08"),S=a("5f87"),x={components:{diaform:k},filters:{colorFilter:function(e){return 1==e?"success":"danger"},timeFilter:function(e){return Object(D["b"])(e,"{y}-{m}-{d}")}},data:function(){return{searchForm:{type:"",platform:"",status:"",keyword:"",key_type:"1"},dialogFormVisible:!1,list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,showQrcode:!1,qrcodeSrc:"",qrcodeTitle:"",qrcodeId:0,options_platform:[],options_type:[],tableIdarr:""}},created:function(){this.fetchData()},methods:{funSearchKeyword:function(){this.currentPage=1,this.fetchData()},fetchData:function(){var e=this;this.listLoading=!0;var t={page:this.currentPage,pagesize:this.pagesize,type:this.searchForm.type,platform:this.searchForm.platform,status:this.searchForm.status,keyword:this.searchForm.keyword,key_type:this.searchForm.key_type};d(t).then((function(t){e.list=t.data.items,e.listLoading=!1,e.total=t.data.total,e.currentPage=t.data.current_page,e.pagesize=t.data.pagesize})),h({}).then((function(t){e.options_platform=t.data})).catch((function(){})),g({}).then((function(t){e.options_type=t.data})).catch((function(){}))},handleSizeChange:function(e){this.pagesize=e,this.fetchData()},handleCurrentChange:function(e){this.currentPage=e,this.fetchData()},funSearch:function(){this.fetchData()},closeDialog:function(){this.dialogFormVisible=!1},funAdd:function(){this.dialogFormVisible=!0},funDelete:function(e){var t=this;t.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var a={id:e.id};p(a).then((function(e){return t.$message.success(e.message),t.fetchData(),!0}))})).catch((function(){}))},copyUrl:function(e){var t=document.createElement("input");t.value=e.copy_url,document.body.appendChild(t),t.select(),document.execCommand("Copy"),t.className="oInput",t.style.display="none",this.$message({type:"success",message:"复制成功"})},funShowQrcode:function(e){if(e.deadline>0&&1!=e.status)return this.$message.error("场景码已过期"),!1;this.qrcodeId=e.id,this.qrcodeTitle=e.title,this.qrcodeSrc=e.qrcode_src,this.showQrcode=!0},funDownload:function(e){location.href=window.global.RequestBaseUrl+u["a"].sceneQrcodeDownload+(-1==window.global.RequestBaseUrl.indexOf("?")?"?":"&")+"admintoken="+Object(S["e"])()+"&id="+e},funDeleteBatch:function(){var e=this;if(0==e.tableIdarr.length)return e.$message.error("请选择要删除的信息"),!1;e.$confirm("此操作将永久删除选中的信息, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.tableIdarr};m(t).then((function(t){return e.$message.success(t.message),e.fetchData(),!0}))})).catch((function(){}))},handleSelectionChange:function(e){if(this.tableIdarr=[],e.length>0){var t,a=Object(o["a"])(e);try{for(a.s();!(t=a.n()).done;){var n=t.value;this.tableIdarr.push(n.id)}}catch(r){a.e(r)}finally{a.f()}}}}},F=x,I=(a("4028"),Object(_["a"])(F,n,r,!1,null,"256f5e22",null));t["default"]=I.exports}}]);