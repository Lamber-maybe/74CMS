(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-41246f10"],{"0ccb":function(t,e,a){var n=a("50c4"),o=a("1148"),i=a("1d80"),l=Math.ceil,r=function(t){return function(e,a,r){var s,c,u=String(i(e)),d=u.length,f=void 0===r?" ":String(r),p=n(a);return p<=d||""==f?u:(s=p-d,c=o.call(f,l(s/f.length)),c.length>s&&(c=c.slice(0,s)),t?u+c:c+u)}};t.exports={start:r(!1),end:r(!0)}},"10f1":function(t,e,a){"use strict";var n=a("136f"),o=a.n(n);o.a},"136f":function(t,e,a){},"4d90":function(t,e,a){"use strict";var n=a("23e7"),o=a("0ccb").start,i=a("9a0c");n({target:"String",proto:!0,forced:i},{padStart:function(t){return o(this,t,arguments.length>1?arguments[1]:void 0)}})},"53ca":function(t,e,a){"use strict";a.d(e,"a",(function(){return n}));a("a4d3"),a("e01a"),a("d28b"),a("d3b7"),a("3ca3"),a("ddb0");function n(t){return n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},n(t)}},"9a0c":function(t,e,a){var n=a("342f");t.exports=/Version\/10\.\d+(\.\d+)?( Mobile\/\w+)? Safari\//.test(n)},"9f7b":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[t._v("企业推广")])]),a("div",{staticClass:"list-search"},[a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限推广方案"},on:{change:t.funSearch},model:{value:t.type,callback:function(e){t.type=e},expression:"type"}},[a("el-option",{attrs:{label:"不限推广方案",value:""}}),a("el-option",{attrs:{label:"职位置顶",value:"jobstick"}}),a("el-option",{attrs:{label:"紧急招聘",value:"emergency"}})],1),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限到期时间"},on:{change:t.funSearch},model:{value:t.settr,callback:function(e){t.settr=e},expression:"settr"}},[a("el-option",{attrs:{label:"不限到期时间",value:""}}),a("el-option",{attrs:{label:"三天内到期",value:"3"}}),a("el-option",{attrs:{label:"一周内到期",value:"7"}}),a("el-option",{attrs:{label:"一月内到期",value:"30"}}),a("el-option",{attrs:{label:"三月内到期",value:"90"}})],1),a("el-select",{staticClass:"list-options",attrs:{placeholder:"按开通时间排序"},on:{change:t.funSearch},model:{value:t.sort,callback:function(e){t.sort=e},expression:"sort"}},[a("el-option",{attrs:{label:"按开通时间排序",value:""}}),a("el-option",{attrs:{label:"按到期时间排序",value:"1"}})],1),a("el-input",{staticClass:"input-with-select",attrs:{placeholder:"请输入搜索内容"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword(e)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[a("el-select",{staticClass:"input-sel",attrs:{slot:"prepend",placeholder:"请选择"},slot:"prepend",model:{value:t.key_type,callback:function(e){t.key_type=e},expression:"key_type"}},[a("el-option",{attrs:{label:"职位名称",value:"1"}}),a("el-option",{attrs:{label:"企业名称",value:"2"}}),a("el-option",{attrs:{label:"职位ID",value:"3"}}),a("el-option",{attrs:{label:"企业ID",value:"4"}}),a("el-option",{attrs:{label:"会员UID",value:"5"}})],1),a("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1),a("div",{staticClass:"spaceline"}),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[a("el-table-column",{attrs:{label:"所属企业",prop:"companyname","min-width":"220"}}),a("el-table-column",{attrs:{label:"推广职位",prop:"jobname","min-width":"170"}}),a("el-table-column",{attrs:{align:"center",label:"推广类型","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s("jobstick"==e.row.type?"置顶":"紧急")+" ")]}}])}),a("el-table-column",{attrs:{align:"center",label:"推广天数","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.days)+"天 ")]}}])}),a("el-table-column",{attrs:{align:"center",label:"开始时间","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("i",{staticClass:"el-icon-time"}),a("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),a("el-table-column",{attrs:{align:"center",label:"结束时间","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("i",{staticClass:"el-icon-time"}),a("span",[t._v(t._s(t._f("timeFilter")(e.row.deadline)))])]}}])}),a("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"170"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return t.funEdit(e.row)}}},[t._v(" 编辑 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return t.funCancel(e.row)}}},[t._v(" 取消推广 ")])]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-row",{attrs:{gutter:20}},[a("el-col",{attrs:{span:8}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.funAdd}},[t._v(" 添加推广 ")])],1),a("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[a("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1),t.dialogVisible?a("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogVisible,width:"35%"},on:{"update:visible":function(e){t.dialogVisible=e}}},[null===t.itemInfo?a("dia_add",{on:{setDialogVisible:t.closeDialog,pageReload:t.fetchData}}):a("dia_edit",{attrs:{"item-info":t.itemInfo},on:{setDialogVisible:t.closeDialog,pageReload:t.fetchData}})],1):t._e()],1)},o=[],i=a("5530"),l=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"150px",rules:t.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"选择企业",prop:"company_id"}},[a("el-select",{attrs:{filterable:"",remote:"","reserve-keyword":"",placeholder:"请输入企业ID/企业名称","remote-method":t.companySearch,loading:t.loading},on:{change:t.selectedCompany},model:{value:t.form.company_id,callback:function(e){t.$set(t.form,"company_id",e)},expression:"form.company_id"}},t._l(t.options_companylist,(function(e){return a("el-option",{key:e.id,attrs:{label:e.companyname,value:e.id}},[a("span",{staticStyle:{float:"left"}},[t._v(t._s(e.companyname))]),a("span",{staticStyle:{float:"right",color:"#8492a6","font-size":"13px"}},[t._v(" ID:"+t._s(e.id)+" ")])])})),1)],1),a("el-form-item",{attrs:{label:"选择职位",prop:"jobid"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:t.form.jobid,callback:function(e){t.$set(t.form,"jobid",e)},expression:"form.jobid"}},t._l(t.options_joblist,(function(e){return a("el-option",{key:e.id,attrs:{label:e.jobname,value:e.id}},[a("span",{staticStyle:{float:"left"}},[t._v(t._s(e.jobname))]),a("span",{staticStyle:{float:"right",color:"#8492a6","font-size":"13px"}},[t._v(" ID:"+t._s(e.id)+" ")])])})),1)],1),a("el-form-item",{attrs:{label:"推广天数",prop:"days"}},[a("el-input",{attrs:{type:"number"},model:{value:t.form.days,callback:function(e){t.$set(t.form,"days",t._n(e))},expression:"form.days"}})],1),a("el-form-item",{attrs:{label:"推广方案",prop:"type"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:t.form.type,callback:function(e){t.$set(t.form,"type",e)},expression:"form.type"}},[a("el-option",{attrs:{label:"置顶",value:"jobstick"}}),a("el-option",{attrs:{label:"紧急",value:"emergency"}})],1)],1),a("el-form-item",{attrs:{label:" "}},[a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),a("el-button",{on:{click:t.closeDialog}},[t._v("取 消")])],1)],1)],1)},r=[],s=a("b775"),c=a("d722");function u(t){return Object(s["a"])({url:c["a"].jobPromotionList,method:"get",params:t})}function d(t){return Object(s["a"])({url:c["a"].jobPromotionSearchCompany,method:"get",params:t})}function f(t){return Object(s["a"])({url:c["a"].jobPromotionSearchJob,method:"get",params:t})}function p(t){return Object(s["a"])({url:c["a"].jobPromotionAdd,method:"post",data:t})}function m(t){return Object(s["a"])({url:c["a"].jobPromotionEdit,method:"post",data:t})}function b(t){return Object(s["a"])({url:c["a"].jobPromotionCancel,method:"post",data:t})}var g={data:function(){return{loading:!1,options_companylist:[],options_joblist:[],form:{company_id:"",jobid:"",days:"",type:""},rules:{jobid:[{required:!0,message:"请选择职位",trigger:"change"}],days:[{required:!0,message:"请填写推广天数",trigger:"blur"}],type:[{required:!0,message:"请选择推广方案",trigger:"change"}]}}},created:function(){},methods:{onSubmit:function(t){var e=this;e.$refs[t].validate((function(t){if(!t)return!1;var a={pid:e.form.jobid,days:e.form.days,type:e.form.type};p(a).then((function(t){return e.$message.success(t.message),e.closeDialog(),e.pageReload(),!0})).catch((function(){}))}))},companySearch:function(t){var e=this;""!==t?(this.loading=!0,d({keyword:t}).then((function(t){e.options_companylist=t.data.items,e.loading=!1})).catch((function(){}))):this.options=[]},selectedCompany:function(t){var e=this;f({company_id:t}).then((function(t){e.options_joblist=t.data.items})).catch((function(){}))},closeDialog:function(){this.$emit("setDialogVisible")},pageReload:function(){this.$emit("pageReload")}}},h=g,y=(a("9f9e"),a("2877")),v=Object(y["a"])(h,l,r,!1,null,"87b07bd4",null),_=v.exports,k=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"150px",rules:t.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"推广职位"}},[t._v(" "+t._s(t.itemInfo.jobname)+" ")]),a("el-form-item",{attrs:{label:"所属企业"}},[t._v(" "+t._s(t.itemInfo.companyname)+" ")]),a("el-form-item",{attrs:{label:"推广方案"}},[t._v(" "+t._s("jobstick"==t.itemInfo.type?"置顶":"紧急")+" ")]),a("el-form-item",{attrs:{label:"推广期限"}},[t._v(" "+t._s(t._f("timeFilter")(t.itemInfo.addtime))+" ~ "+t._s(t._f("timeFilter")(t.itemInfo.deadline))+" ")]),a("el-form-item",{attrs:{label:"延长推广天数",prop:"days"}},[a("el-input",{attrs:{type:"number"},model:{value:t.form.days,callback:function(e){t.$set(t.form,"days",t._n(e))},expression:"form.days"}})],1),a("el-form-item",{attrs:{label:" "}},[a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),a("el-button",{on:{click:t.closeDialog}},[t._v("取 消")])],1)],1)],1)},w=[],S=a("ed08"),j={filters:{timeFilter:function(t){return Object(S["b"])(t,"{y}-{m}-{d} {h}:{i}")}},props:["itemInfo"],data:function(){return{form:{days:""},rules:{days:[{required:!0,message:"请填写延长推广天数",trigger:"blur"}]}}},created:function(){},methods:{onSubmit:function(t){var e=this;e.$refs[t].validate((function(t){if(!t)return!1;var a={id:e.itemInfo.id,days:e.form.days};m(a).then((function(t){return e.$message.success(t.message),e.closeDialog(),e.pageReload(),!0})).catch((function(){}))}))},closeDialog:function(){this.$emit("setDialogVisible")},pageReload:function(){this.$emit("pageReload")}}},D=j,x=(a("10f1"),Object(y["a"])(D,k,w,!1,null,"27a31cac",null)),C=x.exports,I={components:{dia_add:_,dia_edit:C},filters:{timeFilter:function(t){return Object(S["b"])(t,"{y}-{m}-{d}")}},data:function(){return{dialogTitle:"",dialogVisible:!1,itemInfo:{},loading:!1,options:[],settr:"",type:"",list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,key_type:"1",keyword:"",rules:{},sort:""}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={type:this.type,settr:this.settr,key_type:this.key_type,keyword:this.keyword,sort:this.sort,page:this.currentPage,pagesize:this.pagesize};u(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize})).catch((function(){}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearch:function(){this.fetchData()},funSearchKeyword:function(){this.currentPage=1,this.fetchData()},goto:function(t){this.$router.push(t)},funAdd:function(){this.itemInfo=null,this.dialogTitle="添加推广",this.dialogVisible=!0},funEdit:function(t){this.itemInfo=Object(i["a"])({},t),this.dialogTitle="编辑推广",this.dialogVisible=!0},closeDialog:function(){this.dialogVisible=!1},funCancel:function(t){var e=this;e.$confirm("确定取消推广吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var a={id:t.id};b(a).then((function(t){return e.$message.success(t.message),e.fetchData(),!0}))})).catch((function(){}))}}},$=I,z=Object(y["a"])($,n,o,!1,null,null,null);e["default"]=z.exports},"9f9e":function(t,e,a){"use strict";var n=a("fa8f"),o=a.n(n);o.a},ed08:function(t,e,a){"use strict";a.d(e,"b",(function(){return l})),a.d(e,"a",(function(){return r})),a.d(e,"c",(function(){return s}));a("d3b7"),a("ac1f"),a("25f0"),a("4d90"),a("5319"),a("1276");var n=a("53ca"),o=a("a78e"),i=a.n(o);function l(t,e){if(0===arguments.length)return null;var a,o=e||"{y}-{m}-{d} {h}:{i}:{s}";"object"===Object(n["a"])(t)?a=t:("string"===typeof t&&/^[0-9]+$/.test(t)&&(t=parseInt(t)),"number"===typeof t&&t.toString().length<=10&&(t*=1e3),a=new Date(t));var i={y:a.getFullYear(),m:a.getMonth()+1,d:a.getDate(),h:a.getHours(),i:a.getMinutes(),s:a.getSeconds(),a:a.getDay()},l=o.replace(/{([ymdhisa])+}/g,(function(t,e){var a=i[e];return"a"===e?["日","一","二","三","四","五","六"][a]:a.toString().padStart(2,"0")}));return l}function r(t,e){t=(""+t).length<=10?1e3*parseInt(t):+t;var a=new Date(t),n=Date.now(),o=(n-a)/1e3;return o<30?"刚刚":o<3600?Math.ceil(o/60)+"分钟前":o<86400?Math.ceil(o/3600)+"小时前":o<172800?"1天前":e?l(t,e):a.getMonth()+1+"月"+a.getDate()+"日"+a.getHours()+"时"+a.getMinutes()+"分"}function s(t){var e={utype:t.utype,token:t.token,mobile:t.mobile,userIminfo:t.user_iminfo};i.a.set("qscms_visitor",JSON.stringify(e),{expires:7})}},fa8f:function(t,e,a){}}]);