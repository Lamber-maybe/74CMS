(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-7fbb73d8"],{"15fd":function(t,e,a){"use strict";a.d(e,"a",(function(){return l}));a("a4d3"),a("c975"),a("b64b");function i(t,e){if(null==t)return{};var a,i,l={},n=Object.keys(t);for(i=0;i<n.length;i++)a=n[i],e.indexOf(a)>=0||(l[a]=t[a]);return l}function l(t,e){if(null==t)return{};var a,l,n=i(t,e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(t);for(l=0;l<o.length;l++)a=o[l],e.indexOf(a)>=0||Object.prototype.propertyIsEnumerable.call(t,a)&&(n[a]=t[a])}return n}},"90cf":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-tabs",{attrs:{type:"border-card"}},[a("el-tab-pane",{attrs:{label:"公众号自定义菜单",lazy:!0}},[a("config-index")],1)],1)],1)},l=[],n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],key:t.tablekey,staticStyle:{width:"100%","margin-bottom":"20px"},attrs:{"element-loading-text":"Loading",data:t.list,"row-key":"id","default-expand-all":!1,lazy:!0,load:t.loadData,"tree-props":{children:"children",hasChildren:"hasChildren"}}},[a("el-table-column",{attrs:{prop:"title",label:"菜单名称"}}),a("el-table-column",{attrs:{prop:"key",label:"菜单关键字"}}),a("el-table-column",{attrs:{prop:"type",label:"菜单类型"}}),a("el-table-column",{attrs:{prop:"url",label:"链接地址"}}),a("el-table-column",{attrs:{prop:"sort_id",label:"排序",width:"180"}}),a("el-table-column",{attrs:{fixed:"right",label:"操作",width:"350"},scopedSlots:t._u([{key:"default",fn:function(e){return[0==e.row.pid?a("el-button",{attrs:{size:"small"},on:{click:function(a){return t.funAdd(e.row)}}},[t._v(" 此菜单下添加子菜单 ")]):t._e(),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.funAdd}},[t._v(" 添加 ")]),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.funSync}},[t._v(" 同步菜单 ")]),t.dialogFormVisible?a("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogFormVisible,width:"35%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[a("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"100px",rules:t.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"所属菜单",prop:"parentid"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:t.form.pid,callback:function(e){t.$set(t.form,"pid",e)},expression:"form.pid"}},t._l(t.options,(function(t){return a("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})})),1)],1),a("el-form-item",{attrs:{label:"名称",prop:"title"}},[a("el-input",{staticClass:"middle",model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),a("el-form-item",{attrs:{label:"菜单类型",prop:"type"}},[a("el-radio-group",{model:{value:t.form.type,callback:function(e){t.$set(t.form,"type",e)},expression:"form.type"}},[a("el-radio",{attrs:{label:"click"}},[t._v(" 点击事件 ")]),a("el-radio",{attrs:{label:"view"}},[t._v(" 链接事件 ")])],1)],1),"click"==t.form.type?a("el-form-item",{attrs:{label:"菜单key",prop:"key"}},[a("el-input",{staticClass:"middle",model:{value:t.form.key,callback:function(e){t.$set(t.form,"key",e)},expression:"form.key"}})],1):t._e(),"view"==t.form.type?a("el-form-item",{attrs:{label:"跳转链接",prop:"url"}},[a("el-input",{staticClass:"middle",model:{value:t.form.url,callback:function(e){t.$set(t.form,"url",e)},expression:"form.url"}})],1):t._e(),a("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[a("el-input",{staticClass:"mini",model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")]),a("el-button",{on:{click:t.closeDialog}},[t._v(" 取 消 ")])],1)],1)],1):t._e()],1)},o=[],r=(a("4160"),a("159b"),a("15fd")),s=a("5530"),c=a("2909"),u=a("b775"),d=a("d722");function f(t){return Object(u["a"])({url:d["a"].wechatMenuList,method:"get",params:t})}function m(t){return Object(u["a"])({url:d["a"].wechatMenuAdd,method:"post",data:t})}function p(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(u["a"])({url:d["a"].wechatMenuEdit,method:e,data:t}):Object(u["a"])({url:d["a"].wechatMenuEdit,method:e,params:t})}function b(t){return Object(u["a"])({url:d["a"].wechatMenuDelete,method:"post",data:t})}function h(t){return Object(u["a"])({url:d["a"].wechatMenuSync,method:"post",data:t})}var g={components:{},data:function(){return{options:[{value:0,label:"顶级菜单"}],form:{id:0,pid:0,title:"",key:"",type:"click",url:"",pagepath:"",sort_id:0},tablekey:1,dialogTitle:"",dialogFormVisible:!1,list:[],listLoading:!0,rules:{title:[{required:!0,message:"请输入名称",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}]}}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;t.listLoading=!0;var e={};f(e).then((function(e){t.list=Object(c["a"])(e.data),t.options=[{value:0,label:"顶级菜单"}],t.list.forEach((function(e){t.options.push({value:e.id,label:e.title})})),t.listLoading=!1})).catch((function(){}))},funAdd:function(t){this.form={id:0,pid:0,title:"",key:"",type:"click",url:"",pagepath:"",sort_id:0},t&&(this.form.pid=t.id),this.dialogTitle="添加分类",this.dialogFormVisible=!0},funEdit:function(t,e){this.form=e,this.dialogTitle="编辑分类",this.dialogFormVisible=!0},funDelete:function(t,e){var a=this;a.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};b(t).then((function(t){return a.$message.success(t.message),a.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0==t.tableIdarr.length)return t.$message.error("请选择要删除的数据"),!1;t.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};b(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},addSave:function(t,e){var a=this;this.$refs[e].validate((function(e){if(!e)return!1;m(t).then((function(t){return a.$message.success(t.message),a.closeDialog(),a.pageReload(),!0})).catch((function(){}))}))},editSave:function(t,e){var a=this;this.$refs[e].validate((function(e){if(!e)return!1;p(t).then((function(t){return a.$message.success(t.message),a.closeDialog(),a.pageReload(),!0})).catch((function(){}))}))},onSubmit:function(t){var e=Object(s["a"])({},this.form);if(parseInt(e.id)>0)this.editSave(e,t);else{var a=e.id,i=Object(r["a"])(e,["id"]);console.log(a),this.addSave(i,t)}},loadData:function(t,e,a){var i={pid:t.id};f(i).then((function(t){a(t.data)})).catch((function(){}))},closeDialog:function(){this.dialogFormVisible=!1},pageReload:function(){this.fetchData(),this.tablekey=!this.tablekey},funSync:function(){var t=this;h({}).then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))}}},v=g,y=(a("a190"),a("2877")),k=Object(y["a"])(v,n,o,!1,null,"1c397bfe",null),w=k.exports,_={components:{configIndex:w}},x=_,$=Object(y["a"])(x,i,l,!1,null,null,null);e["default"]=$.exports},a190:function(t,e,a){"use strict";var i=a("d155"),l=a.n(i);l.a},d155:function(t,e,a){}}]);