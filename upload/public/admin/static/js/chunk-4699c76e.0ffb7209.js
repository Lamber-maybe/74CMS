(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-4699c76e"],{"2af5":function(t,e,a){"use strict";a("6f5f")},"6f5f":function(t,e,a){},"73ae":function(t,e,a){"use strict";a.d(e,"d",(function(){return s})),a.d(e,"a",(function(){return o})),a.d(e,"c",(function(){return l})),a.d(e,"g",(function(){return r})),a.d(e,"f",(function(){return c})),a.d(e,"b",(function(){return u})),a.d(e,"e",(function(){return f}));var n=a("b775"),i=a("d722");function s(t){return Object(n["a"])({url:i["a"].cronList,method:"get",params:t})}function o(t){return Object(n["a"])({url:i["a"].cronAdd,method:"post",data:t})}function l(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(n["a"])({url:i["a"].cronEdit,method:e,data:t}):Object(n["a"])({url:i["a"].cronEdit,method:e,params:t})}function r(t){return Object(n["a"])({url:i["a"].cronSet,method:"post",data:t})}function c(t){return Object(n["a"])({url:i["a"].cronRun,method:"post",data:t})}function u(t){return Object(n["a"])({url:i["a"].cronDelete,method:"post",data:t})}function f(t){return Object(n["a"])({url:i["a"].cronLog,method:"get",params:t})}},"8a9e":function(t,e,a){"use strict";a.r(e);for(var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[t._v("计划任务列表")])]),a("div",{staticClass:"tip"},[a("p",[t._v(" 计划任务是一项使系统在规定时间自动执行某些特定任务的功能，在需要的情况下，您也可以方便的将其用于功能的扩展。 ")]),a("p",[t._v(" 计划任务是与系统核心紧密关联的功能特性，不当的设置可能造成功能的隐患，严重时可能导致网站无法正常运行。 ")])]),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[a("el-table-column",{attrs:{label:"名称",prop:"name","min-width":"160"}}),a("el-table-column",{attrs:{align:"center",label:"类型","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.is_sys?a("span",{staticClass:"font_info"},[t._v(" 系统内置 ")]):t._e(),0==e.row.is_sys?a("span",{staticClass:"font_warning"},[t._v(" 自定义 ")]):t._e()]}}])}),a("el-table-column",{attrs:{label:"执行间隔","min-width":"140"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("span",[t._v(t._s(e.row.rule))])]}}])}),a("el-table-column",{attrs:{align:"center",label:"上次执行时间","min-width":"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("i",{staticClass:"el-icon-time"}),a("span",[t._v(t._s(t._f("timeFilter")(e.row.last_execute_time)))]),a("span",{staticStyle:{cursor:"pointer",color:"#3a8ee6","font-size":"12px"},on:{click:function(a){return t.funLog(e.row)}}},[t._v(" [详情] ")])]}}])}),a("el-table-column",{attrs:{align:"center",label:"下次执行时间","min-width":"180"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("i",{staticClass:"el-icon-time"}),a("span",[t._v(t._s(t._f("timeFilter")(e.row.next_execute_time)))])]}}])}),a("el-table-column",{attrs:{align:"center",label:"状态","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-tag",{attrs:{type:t._f("colorFilter")(e.row.status)}},[t._v(" "+t._s(1==e.row.status?"可用":"停用")+" ")])]}}])}),a("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"380"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"small"},on:{click:function(a){return t.funRun(e.row,e.$index)}}},[t._v(" 执行 ")]),a("el-button",{attrs:{size:"small"},on:{click:function(a){return t.funCopy(e.row)}}},[t._v(" 复制链接 ")]),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return t.funEdit(e.row)}}},[t._v(" 修改 ")]),1==e.row.status?a("el-button",{attrs:{size:"small",type:"info"},on:{click:function(a){return t.funStatus(e.row,0,e.$index)}}},[t._v(" 停用 ")]):t._e(),0==e.row.status?a("el-button",{attrs:{size:"small",type:"success"},on:{click:function(a){return t.funStatus(e.row,1,e.$index)}}},[t._v(" 启用 ")]):t._e(),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return t.funDelete(e.row)}}},[t._v(" 删除 ")])]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-row",{attrs:{gutter:20}},[a("el-col",{attrs:{span:8}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.funAdd}},[t._v(" 添加计划任务 ")])],1),a("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[a("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1),t.dialogFormVisible?a("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogFormVisible,width:"35%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogFormVisible=e},close:t.closeDialog}},[a("diaform",{attrs:{"item-info":t.item_info},on:{setDialogFormVisible:t.closeDialog,pageReload:t.fetchData}})],1):t._e(),t.dialogListVisible?a("el-dialog",{attrs:{title:"任务执行记录",visible:t.dialogListVisible,width:"50%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogListVisible=e},close:t.closeListDialog}},[a("dialist",{attrs:{"cron-id":t.cronId},on:{setDialogFormVisible:t.closeListDialog}})],1):t._e()],1)},i=[],s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"",size:"small","highlight-current-row":"",stripe:""}},[a("el-table-column",{attrs:{label:"任务名称",prop:"cron_name"}}),a("el-table-column",{attrs:{align:"center",label:"执行时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),a("el-table-column",{attrs:{align:"center",label:"耗费时长"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(t._s(e.row.seconds)+"秒")]}}])}),a("el-table-column",{attrs:{align:"center",label:"类型"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.is_auto?a("span",{staticClass:"font_success"},[t._v(" 自动执行 ")]):a("span",{staticClass:"font_warning"},[t._v("手动执行")])]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-row",{attrs:{gutter:20}},[a("el-col",{staticStyle:{"text-align":"right"},attrs:{span:20}},[a("el-pagination",{attrs:{layout:"prev, pager, next",background:"",total:t.total,"current-page":t.currentPage,"page-size":t.pagesize},on:{"current-change":t.handleCurrentChange}})],1)],1)],1)},o=[],l=a("73ae"),r=a("ed08"),c={filters:{timeFilter:function(t){return Object(r["b"])(t,"{y}-{m}-{d} {h}:{i}:{s}")}},props:["cronId"],data:function(){return{list:null,listLoading:!0,total:0,currentPage:1,pagesize:5}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={cron_id:this.cronId,page:this.currentPage,pagesize:this.pagesize};Object(l["e"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()}}},u=c,f=a("2877"),d=Object(f["a"])(u,s,o,!1,null,null,null),m=d.exports,p=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"150px",rules:t.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"计划任务名称",prop:"name"}},[a("el-input",{model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),a("el-form-item",{attrs:{label:"任务脚本类名称",prop:"action"}},[a("el-input",{model:{value:t.form.action,callback:function(e){t.$set(t.form,"action",e)},expression:"form.action"}}),a("el-tooltip",{staticClass:"item",attrs:{effect:"dark",placement:"top-start"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v(" 设置本任务的执行程序类名称，请勿包含路径； "),a("br"),t._v(" 程序脚本统一存放于 /application/common/lib/cron/ 目录中 ")]),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"})])])],1),a("el-form-item",{attrs:{label:"每周",prop:"weekday"}},[a("el-select",{attrs:{placeholder:"请选择星期几执行"},model:{value:t.form.weekday,callback:function(e){t.$set(t.form,"weekday",e)},expression:"form.weekday"}},[a("el-option",{attrs:{label:"不限制",value:"-1"}}),a("el-option",{attrs:{label:"一",value:"1"}}),a("el-option",{attrs:{label:"二",value:"2"}}),a("el-option",{attrs:{label:"三",value:"3"}}),a("el-option",{attrs:{label:"四",value:"4"}}),a("el-option",{attrs:{label:"五",value:"5"}}),a("el-option",{attrs:{label:"六",value:"6"}}),a("el-option",{attrs:{label:"日",value:"0"}})],1),a("el-tooltip",{staticClass:"item",attrs:{effect:"dark",placement:"top-start"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v(" 设置星期几执行本任务，本设置会覆盖下面的“日”设定 ")]),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"})])])],1),a("el-form-item",{attrs:{label:"每月",prop:"day"}},[a("el-select",{attrs:{placeholder:"设置哪一日执行本任务"},model:{value:t.form.day,callback:function(e){t.$set(t.form,"day",e)},expression:"form.day"}},[a("el-option",{attrs:{label:"不限制",value:"-1"}}),t._l(t.options_days,(function(t,e){return a("el-option",{key:e,attrs:{label:t.name,value:t.id}})}))],2),a("el-tooltip",{staticClass:"item",attrs:{effect:"dark",placement:"top-start"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v(" 设置哪一日执行本任务 ")]),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"})])])],1),a("el-form-item",{attrs:{label:"小时",prop:"hour"}},[a("el-select",{attrs:{placeholder:"设置哪一小时执行本任务"},model:{value:t.form.hour,callback:function(e){t.$set(t.form,"hour",e)},expression:"form.hour"}},[a("el-option",{attrs:{label:"不限制",value:"-1"}}),t._l(t.options_hours,(function(t,e){return a("el-option",{key:e,attrs:{label:t.name,value:t.id}})}))],2),a("el-tooltip",{staticClass:"item",attrs:{effect:"dark",placement:"top-start"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v(" 设置哪一小时执行本任务 ")]),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"})])])],1),a("el-form-item",{attrs:{label:"分钟",prop:"minute"}},[a("el-input",{attrs:{placeholder:"设置哪一分钟执行本任务"},model:{value:t.form.minute,callback:function(e){t.$set(t.form,"minute",e)},expression:"form.minute"}}),a("el-tooltip",{staticClass:"item",attrs:{effect:"dark",placement:"top-start"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v(" 设置哪一分钟执行本任务，可设置每隔几分钟执行， "),a("br"),t._v(" 例如每5分钟执行则填写：*/5 ")]),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"})])])],1),a("el-form-item",{attrs:{label:"可用",prop:"status"}},[a("el-switch",{model:{value:t.form.status,callback:function(e){t.$set(t.form,"status",e)},expression:"form.status"}})],1),a("el-form-item",{attrs:{label:" "}},[a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")]),a("el-button",{on:{click:t.closeDialog}},[t._v(" 取 消 ")])],1)],1)],1)},h=[],g=a("5530"),b=[],v=1;v<=31;v++){var _={name:v+"日",id:v+""};b.push(_)}for(var w=[],y=0;y<=23;y++){var k={name:y+"时",id:y+""};w.push(k)}var C={props:["itemInfo"],data:function(){return{options_days:b,options_hours:w,form:{id:0,name:"",action:"",weekday:"-1",day:"-1",hour:"-1",minute:"",status:!0},rules:{}}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){null!==this.itemInfo&&(this.itemInfo.status=1==this.itemInfo.status,this.form=Object(g["a"])(Object(g["a"])({},this.form),this.itemInfo),this.form.weekday=this.form.weekday+"",this.form.day=this.form.day+"",this.form.hour=this.form.hour+"",this.form.minute=this.form.minute+"")},addSave:function(t,e){var a=this;this.$refs[e].validate((function(e){if(!e)return!1;t.status=!0===t.status?1:0,Object(l["a"])(t).then((function(t){return a.$message.success(t.message),a.closeDialog(),a.pageReload(),!0})).catch((function(){}))}))},editSave:function(t,e){var a=this;this.$refs[e].validate((function(e){if(!e)return!1;t.status=!0===t.status?1:0,Object(l["c"])(t).then((function(t){return a.$message.success(t.message),a.closeDialog(),a.pageReload(),!0})).catch((function(){}))}))},onSubmit:function(t){var e=this,a=Object(g["a"])({},e.form);parseInt(a.id)>0?e.editSave(a,t):e.addSave(a,t)},closeDialog:function(){this.$emit("setDialogFormVisible")},pageReload:function(){this.$emit("pageReload")}}},x=C,z=(a("2af5"),Object(f["a"])(x,p,h,!1,null,"174b5daa",null)),$=z.exports,D={components:{diaform:$,dialist:m},filters:{colorFilter:function(t){return 1==t?"success":"danger"},timeFilter:function(t){return 0==t?"从未执行":Object(r["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{cronId:0,item_info:null,dialogTitle:"",dialogFormVisible:!1,dialogListVisible:!1,list:null,listLoading:!0,total:0,currentPage:1,pagesize:10}},created:function(){this.fetchData()},methods:{funLog:function(t){this.cronId=t.id,this.dialogListVisible=!0},fetchData:function(){var t=this;this.listLoading=!0;var e={page:this.currentPage,pagesize:this.pagesize};Object(l["d"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearch:function(){this.fetchData()},closeDialog:function(){this.dialogFormVisible=!1},closeListDialog:function(){this.dialogListVisible=!1},funAdd:function(){this.item_info=null,this.dialogTitle="添加",this.dialogFormVisible=!0},funEdit:function(t){this.item_info=t,this.dialogTitle="编辑",this.dialogFormVisible=!0},funDelete:function(t){var e=this;e.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var a={id:t.id};Object(l["b"])(a).then((function(t){return e.$message.success(t.message),e.fetchData(),!0}))})).catch((function(){}))},funRun:function(t,e){var a=this;a.$confirm("确定执行该计划任务吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var n={id:t.id};Object(l["f"])(n).then((function(t){return a.$message.success(t.message),a.list[e].last_execute_time=t.data.last_execute_time,a.list[e].next_execute_time=t.data.next_execute_time,!0}))})).catch((function(){}))},funStatus:function(t,e,a){var n=this,i={id:t.id,status:e};Object(l["g"])(i).then((function(t){return n.$message.success(t.message),n.list[a].status=e,!0}))},funCopy:function(t){var e=this,a=t.runUrl;this.$copyText(a).then((function(t){e.$message.success("链接已复制到剪切板！")}),(function(t){e.$message.error("抱歉，复制失败！")}))}}},L=D,S=Object(f["a"])(L,n,i,!1,null,null,null);e["default"]=S.exports}}]);