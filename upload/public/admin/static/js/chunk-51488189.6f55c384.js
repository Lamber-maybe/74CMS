(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-51488189"],{3120:function(t,e,i){},3768:function(t,e,i){"use strict";i.r(e);var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("el-card",[i("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[i("span",[t._v("视频求职")])]),i("vlist",{attrs:{vtype:2}})],1)],1)},o=[],n=i("3854"),r={components:{vlist:n["a"]},data:function(){return{}}},l=r,s=i("2877"),u=Object(s["a"])(l,a,o,!1,null,null,null);e["default"]=u.exports},3854:function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("div",{staticClass:"list-search"},[i("el-select",{staticClass:"list-options",attrs:{placeholder:"审核状态"},on:{change:t.funSearch},model:{value:t.form.audit,callback:function(e){t.$set(t.form,"audit",e)},expression:"form.audit"}},[i("el-option",{attrs:{label:"不限审核状态",value:"0"}}),i("el-option",{attrs:{label:"未审核",value:"1"}}),i("el-option",{attrs:{label:"已审核",value:"2"}}),i("el-option",{attrs:{label:"未通过",value:"3"}})],1),i("el-select",{staticClass:"list-options",attrs:{placeholder:"展示状态"},on:{change:t.funSearch},model:{value:t.form.is_public,callback:function(e){t.$set(t.form,"is_public",e)},expression:"form.is_public"}},[i("el-option",{attrs:{label:"不限展示状态",value:"0"}}),i("el-option",{attrs:{label:"不展示",value:"1"}}),i("el-option",{attrs:{label:"展示中",value:"2"}})],1),i("el-input",{staticClass:"input-with-select",attrs:{placeholder:"请输入搜索内容"},model:{value:t.form.keyword,callback:function(e){t.$set(t.form,"keyword",e)},expression:"form.keyword"}},[i("el-select",{staticClass:"input-sel",attrs:{slot:"prepend",placeholder:"请选择"},slot:"prepend",model:{value:t.form.key_type,callback:function(e){t.$set(t.form,"key_type",e)},expression:"form.key_type"}},[i("el-option",{attrs:{label:"标题",value:"1"}}),i("el-option",{attrs:{label:"UID",value:"2"}})],1),i("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1),i("div",{staticClass:"spaceline"}),i("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"selection-change":t.handleSelectionChange}},[i("el-table-column",{attrs:{type:"selection",width:"42"}}),1==t.vtype?i("el-table-column",{attrs:{label:"公司名称",prop:"companyname","show-overflow-tooltip":"","min-width":"150"}}):t._e(),2==t.vtype?i("el-table-column",{attrs:{label:"姓名",prop:"fullname","show-overflow-tooltip":"","min-width":"150"}}):t._e(),i("el-table-column",{attrs:{label:"标题",prop:"title","show-overflow-tooltip":"","min-width":"150"}}),i("el-table-column",{attrs:{align:"center",label:"视频预览",prop:"video_src","show-overflow-tooltip":"","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("span",{staticClass:"more",attrs:{slot:"reference"},on:{click:function(i){return t.preview(e.row)}},slot:"reference"},[t._v("[预览]")])]}}])}),i("el-table-column",{attrs:{label:"浏览量",align:"center","show-overflow-tooltip":"",prop:"view_count","min-width":"80"}}),i("el-table-column",{attrs:{label:"文件大小",align:"center","show-overflow-tooltip":"",prop:"filesize","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("span",{domProps:{innerHTML:t._s(t.fmtSize(e.row.filesize))}})]}}])}),i("el-table-column",{attrs:{align:"center",label:"审核状态",prop:"status","show-overflow-tooltip":"","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.audit?i("el-tag",{attrs:{type:"warning"},domProps:{innerHTML:t._s(t.auditFmt(e.row))}}):t._e(),2==e.row.audit?i("el-tag",{attrs:{type:"success"},domProps:{innerHTML:t._s(t.auditFmt(e.row))}}):t._e(),3==e.row.audit?i("el-tag",{attrs:{type:"danger"},domProps:{innerHTML:t._s(t.auditFmt(e.row))}}):t._e()]}}])}),i("el-table-column",{attrs:{label:"公开状态",align:"center","show-overflow-tooltip":"",prop:"is_public","min-width":"90"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.is_public?i("span",[t._v("关闭")]):t._e(),2==e.row.is_public?i("span",[t._v("开启")]):t._e()]}}])}),i("el-table-column",{attrs:{align:"center",label:"发布日期",prop:"addtime","min-width":"160","show-overflow-tooltip":""}}),i("el-table-column",{attrs:{align:"center",fixed:"right",label:"操作",prop:"title","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-button",{attrs:{size:"small",type:"warning"},on:{click:function(i){return t.doCheckOne(e.row)}}},[t._v("审核")]),i("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(i){return t.doDel(e.row)}}},[t._v("删除")])]}}])})],1),i("div",{staticClass:"spaceline"}),i("el-row",{attrs:{gutter:20}},[i("el-col",{attrs:{span:8}},[i("el-button",{attrs:{size:"small",type:"warning"},on:{click:t.doCheck}},[t._v(" 审核 ")]),i("el-button",{attrs:{size:"small",type:"danger"},on:{click:t.doDel}},[t._v(" 删除所选 ")])],1),i("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[i("el-pagination",{attrs:{"current-page":t.form.page,"page-size":t.form.pagesize,"page-sizes":[10,20,50,100],total:t.total,background:"",layout:"total, sizes, prev, pager, next, jumper"},on:{"current-change":t.handleCurrentChange,"size-change":t.handleSizeChange}})],1)],1),i("el-dialog",{attrs:{visible:t.dialogFormVisible,title:"将所选信息设置为",width:"25%"},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[i("el-form",{staticClass:"common-form",attrs:{"label-width":"80px"}},[i("el-form-item",{attrs:{label:"审核状态"}},[i("el-radio-group",{model:{value:t.setAuditVal,callback:function(e){t.setAuditVal=t._n(e)},expression:"setAuditVal"}},t._l(t.options_audit,(function(e,a){return i("el-radio",{key:a,attrs:{label:parseInt(a)}},[t._v(" "+t._s(e)+" ")])})),1)],1),3==t.setAuditVal?i("el-form-item",{attrs:{label:"原因"}},[i("el-input",{attrs:{rows:"3",type:"textarea"},model:{value:t.setAuditReason,callback:function(e){t.setAuditReason=e},expression:"setAuditReason"}})],1):t._e()],1),i("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(e){t.dialogFormVisible=!1}}},[t._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:t.doSetAuth}},[t._v(" 确 定 ")])],1)],1),i("el-dialog",{attrs:{visible:t.previewShow,width:"354px"},on:{"update:visible":function(e){t.previewShow=e}}},[i("div",{staticStyle:{"margin-right":"20px"},attrs:{slot:"title"},domProps:{innerHTML:t._s(t.previewCur.title)},slot:"title"}),i("video",{staticStyle:{width:"314px","max-height":"551px"},attrs:{autoplay:"",src:t.previewCur.video_src,controls:""}})])],1)},o=[],n=i("b85c"),r=(i("b680"),i("ed08")),l=i("8e35"),s={props:["vtype"],data:function(){return{form:{audit:"0",is_public:"0",type:1,key_type:"1",keyword:"",page:1,pagesize:15},previewShow:!1,previewCur:{},total:0,list:[],listLoading:!1,tableIdarr:[],options_audit:{1:"未审核",2:"已审核",3:"审核未通过"},setAuditVal:0,setAuditReason:"",dialogFormVisible:!1,curId:"",auditSubmitLoading:!1,topSubmitLoading:!1}},mounted:function(){this.getList()},methods:{preview:function(t){this.previewShow=!0,this.previewCur=t},fmtSize:function(t){var e=t/1024/1024;return e=e.toFixed(2),e+"M"},doCheck:function(){if(0==this.tableIdarr.length)return this.$message.error("请选择信息");this.curId=!1,this.setAuditVal=1,this.dialogFormVisible=!0},doCheckOne:function(t){this.dialogFormVisible=!0,this.curId=t.id,this.setAuditVal=2},doSetAuth:function(){var t=this;if(1==this.auditSubmitLoading)return!1;this.auditSubmitLoading=!0;var e={id:this.curId?[this.curId]:this.tableIdarr,type:this.vtype,audit:this.setAuditVal,reason:this.setAuditReason};Object(l["l"])(e,"post").then((function(e){return 200==e.code?(t.$message.success(e.message),t.auditSubmitLoading=!1,t.getList(),t.dialogFormVisible=!1,!0):(t.auditSubmitLoading=!1,t.$message.error(e.message),!1)}))},doDel:function(t){var e=this;t&&(this.curId=t.id);var i=this;this.$confirm("此操作将永久删除该信息, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:i.curId?[i.curId]:i.tableIdarr,type:e.vtype};Object(l["m"])(t).then((function(t){return i.$message.success(t.message),i.getList(),!0}))})).catch((function(){}))},handleSizeChange:function(t){this.form.pagesize=t,this.getList()},handleCurrentChange:function(t){this.form.page=t,this.getList()},dateFmt:function(t){return t>0?this.timeFilter(t):"-"},auditFmt:function(t){return this.options_audit[t.audit]},timeFilter:function(t){return Object(r["b"])(t,"{y}-{m}-{d}")},getList:function(){var t=this;this.form.type=this.vtype,this.listLoading=!0;var e=Object.assign({},this.form);Object(l["n"])(e).then((function(e){t.list=e.data.list,t.listLoading=!1,t.page=e.data.page,t.pagesize=e.data.pagesize,t.total=e.data.total}))},funSearch:function(){this.getList()},funSearchKeyword:function(){this.currentPage=1,this.getList()},handleSelectionChange:function(t){if(this.tableIdarr=[],t.length>0){var e,i=Object(n["a"])(t);try{for(i.s();!(e=i.n()).done;){var a=e.value;this.tableIdarr.push(a.id)}}catch(o){i.e(o)}finally{i.f()}}}}},u=s,c=(i("398a"),i("2877")),d=Object(c["a"])(u,a,o,!1,null,"6000a045",null);e["a"]=d.exports},"398a":function(t,e,i){"use strict";i("3120")},"8e35":function(t,e,i){"use strict";i.d(e,"n",(function(){return o})),i.d(e,"l",(function(){return n})),i.d(e,"m",(function(){return r})),i.d(e,"f",(function(){return l})),i.d(e,"a",(function(){return s})),i.d(e,"d",(function(){return u})),i.d(e,"c",(function(){return c})),i.d(e,"e",(function(){return d})),i.d(e,"k",(function(){return p})),i.d(e,"h",(function(){return h})),i.d(e,"j",(function(){return f})),i.d(e,"i",(function(){return m})),i.d(e,"g",(function(){return b})),i.d(e,"b",(function(){return v}));var a=i("b775");i("d722");function o(t){return Object(a["a"])({url:"/shortvideo/lists",method:"get",params:t})}function n(t){return Object(a["a"])({url:"/shortvideo/audit",method:"post",data:t})}function r(t){return Object(a["a"])({url:"/shortvideo/del",method:"post",data:t})}function l(t){return Object(a["a"])({url:"/shortvideo/ad_list",method:"get",params:t})}function s(t){return Object(a["a"])({url:"/shortvideo/ad_add",method:"post",data:t})}function u(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(a["a"])({url:"/shortvideo/ad_edit",method:e,data:t}):Object(a["a"])({url:"/shortvideo/ad_edit",method:e,params:t})}function c(t){return Object(a["a"])({url:"/shortvideo/ad_del",method:"post",data:t})}function d(t){return Object(a["a"])({url:"/shortvideo/innerLinkOptions",method:"get",params:t})}function p(t){return Object(a["a"])({url:"/shortvideo/ad_cat_list",method:"get",params:t})}function h(t){return Object(a["a"])({url:"/shortvideo/ad_cat_add",method:"post",data:t})}function f(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(a["a"])({url:"/shortvideo/ad_cat_edit",method:e,data:t}):Object(a["a"])({url:"/shortvideo/ad_cat_edit",method:e,params:t})}function m(t){return Object(a["a"])({url:"/shortvideo/ad_cat_del",method:"post",data:t})}function b(t){return Object(a["a"])({url:"/shortvideo/ad_cat_platform",method:"get",params:t})}function v(t){return Object(a["a"])({url:"/shortvideo/ad_cat_tree",method:"get",params:t})}}}]);