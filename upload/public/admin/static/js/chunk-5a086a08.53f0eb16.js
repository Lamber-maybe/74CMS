(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-5a086a08"],{"15fd":function(t,e,n){"use strict";n.d(e,"a",(function(){return a}));n("a4d3"),n("c975"),n("b64b");function i(t,e){if(null==t)return{};var n,i,a={},o=Object.keys(t);for(i=0;i<o.length;i++)n=o[i],e.indexOf(n)>=0||(a[n]=t[n]);return a}function a(t,e){if(null==t)return{};var n,a,o=i(t,e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);for(a=0;a<r.length;a++)n=r[a],e.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(t,n)&&(o[n]=t[n])}return o}},c897:function(t,e,n){},db78:function(t,e,n){"use strict";n.r(e);var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[t._m(0),n("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.funAdd}},[t._v("添加")]),n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.onSubmit()}}},[t._v("保存")]),n("div",{staticClass:"spaceline"}),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],key:t.tablekey,staticStyle:{width:"100% margin-bottom: 20px"},attrs:{"element-loading-text":"Loading",size:"mini",data:t.list,"row-key":"id",border:"","default-expand-all":!1,lazy:!0,load:t.loadData,"tree-props":{children:"children",hasChildren:"hasChildren"}}},[n("el-table-column",{attrs:{prop:"name",label:"分类名称"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-input",{staticClass:"small",attrs:{size:"mini"},model:{value:e.row.name,callback:function(n){t.$set(e.row,"name",n)},expression:"scope.row.name"}}),t._v(" (ID:"+t._s(e.row.id)+") ")]}}])}),n("el-table-column",{attrs:{prop:"spell",label:"别名",width:"180"}}),n("el-table-column",{attrs:{prop:"sort_id",label:"排序",width:"180"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-input",{staticClass:"mini",attrs:{size:"mini"},model:{value:e.row.sort_id,callback:function(n){t.$set(e.row,"sort_id",n)},expression:"scope.row.sort_id"}})]}}])}),n("el-table-column",{attrs:{fixed:"right",label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[3!=e.row.level?n("el-button",{attrs:{size:"small"},on:{click:function(n){return t.funAdd(e.$index,e.row)}}},[t._v(" 此类下添加子类 ")]):t._e(),n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(n){return t.funTemplate(e.row)}}},[t._v(" 模板设置 ")]),n("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(n){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")]),n("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(n){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),t.dialogFormVisible?n("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogFormVisible,width:"35%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[n("diaform",{attrs:{"form-id":t.formId,"form-parentid":t.formParentid},on:{setDialogFormVisible:t.closeDialog,pageReload:t.pageReload}})],1):t._e(),t.showTemplate?n("el-dialog",{attrs:{title:"模板设置",visible:t.showTemplate,width:"35%","close-on-click-modal":!1},on:{"update:visible":function(e){t.showTemplate=e}}},[n("templateList",{attrs:{pid:t.pid},on:{closeDialog:function(e){t.showTemplate=!1}}})],1):t._e()],1)},a=[function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"tip"},[n("p",[t._v(" 正式使用后删除分类会导致与此分类关联的信息类别丢失，修改不会受影响。 ")]),n("p",[t._v("删除顶级分类将会自动删除此分类下的子分类。")])])}],o=(n("99af"),n("2909")),r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"100px",rules:t.rules,"inline-message":!0}},[n("el-form-item",{attrs:{label:"所属分类",prop:"parentid"}},[n("el-cascader",{attrs:{placeholder:"顶级",options:t.options,"show-all-levels":!1,props:{checkStrictly:!0},clearable:""},model:{value:t.form.parentid,callback:function(e){t.$set(t.form,"parentid",e)},expression:"form.parentid"}})],1),n("el-form-item",{attrs:{label:"名称",prop:"name"}},[n("el-input",{staticClass:"middle",model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),n("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[n("el-input",{staticClass:"mini",model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),n("el-form-item",{attrs:{label:""}},[n("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),n("el-button",{on:{click:t.closeDialog}},[t._v("取 消")])],1)],1)],1)},s=[],l=n("15fd"),c=n("5530"),u=n("b775"),d=n("d722");function f(t){return Object(u["a"])({url:d["a"].configJobcategory,method:"get",params:t})}function m(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(u["a"])({url:d["a"].configJobcategoryEdit,method:e,data:t}):Object(u["a"])({url:d["a"].configJobcategoryEdit,method:e,params:t})}function p(t){return Object(u["a"])({url:d["a"].configJobcategoryAdd,method:"post",data:t})}function h(t){return Object(u["a"])({url:d["a"].configJobcategoryOptions,method:"GET",params:t})}function g(t){return Object(u["a"])({url:d["a"].configJobcategoryDelete,method:"post",data:t})}function b(t){return Object(u["a"])({url:d["a"].configJobcategoryTableSave,method:"post",data:t})}var v={props:["formId","formParentid"],data:function(){return{infoLoading:!1,options:[],form:{id:0,parentid:[],name:"",sort_id:0},rules:{name:[{required:!0,message:"请输入名称",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}]}}},mounted:function(){},created:function(){this.form.parentid=this.formParentid,this.fetchCategoryData()},methods:{fetchInfo:function(t){var e=this,n={id:t};m(n,"get").then((function(t){e.form=Object(c["a"])({},t.data.info),e.infoLoading=!1})).catch((function(){}))},fetchCategoryData:function(){var t=this;this.infoLoading=!0;var e={};h(e).then((function(e){t.options=e.data,t.formId&&t.fetchInfo(t.formId),t.infoLoading=!1})).catch((function(){}))},addSave:function(t,e){var n=this;this.$refs[e].validate((function(e){if(!e)return!1;p(t).then((function(t){return n.$message.success(t.message),n.closeDialog(),n.pageReload(),!0})).catch((function(){}))}))},editSave:function(t,e){var n=this;this.$refs[e].validate((function(e){if(!e)return!1;m(t).then((function(t){return n.$message.success(t.message),n.closeDialog(),n.pageReload(),!0})).catch((function(){}))}))},onSubmit:function(t){var e=this,n=e.form.parentid.length;switch(n){case 0:e.form.level=1;break;case 1:e.form.level=2;break;case 2:e.form.level=3;break}var i=Object(c["a"])({},this.form);if(parseInt(i.id)>0)this.editSave(i,t);else{var a=i.id,o=Object(l["a"])(i,["id"]);console.log(a),this.addSave(o,t)}},closeDialog:function(){this.$emit("setDialogFormVisible")},pageReload:function(){this.$emit("pageReload")}}},y=v,w=n("2877"),_=Object(w["a"])(y,r,s,!1,null,null,null),k=_.exports,x=n("fcace"),$={components:{diaform:k,templateList:x["a"]},data:function(){return{pid:0,showTemplate:!1,tablekey:1,formParentid:[],formId:0,dialogTitle:"",infoLoading:!1,dialogFormVisible:!1,saveData:[],list:[],listLoading:!0}},created:function(){this.fetchData()},methods:{funTemplate:function(t){this.pid=t.id,this.showTemplate=!0},fetchData:function(){var t=this;t.listLoading=!0;var e={};f(e).then((function(e){t.list=Object(o["a"])(e.data),t.saveData=Object(o["a"])(t.list),t.listLoading=!1})).catch((function(){}))},funAdd:function(t,e){this.formId=0,e?0==e.pid?this.formParentid=[e.id]:this.formParentid=[e.pid,e.id]:this.formParentid=[],this.dialogTitle="添加分类",this.dialogFormVisible=!0},funEdit:function(t,e){this.formId=e.id,this.dialogTitle="编辑分类",this.dialogFormVisible=!0},funDelete:function(t,e){var n=this;n.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};g(t).then((function(t){return n.$message.success(t.message),n.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0==t.tableIdarr.length)return t.$message.error("请选择要删除的数据"),!1;t.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};g(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},loadData:function(t,e,n){var i=this,a={pid:t.id};f(a).then((function(t){n(t.data),i.saveData=i.saveData.concat(t.data)})).catch((function(){}))},onSubmit:function(){var t=this,e=Object(o["a"])(t.saveData);b(e).then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))},closeDialog:function(){this.dialogFormVisible=!1},pageReload:function(){this.fetchData(),this.tablekey=!this.tablekey}}},O=$,D=Object(w["a"])(O,i,a,!1,null,null,null);e["default"]=D.exports},ed26:function(t,e,n){"use strict";var i=n("c897"),a=n.n(i);a.a},fcace:function(t,e,n){"use strict";var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[t._l(t.list,(function(e,i){return n("el-tag",{key:i,attrs:{closable:"",effect:t.form.id==e.id?"dark":"plain"},on:{click:function(n){return t.changeItem(e)},close:function(n){return t.handleClose(e)}}},[t._v(" "+t._s(e.title)+" ")])})),n("el-button",{staticClass:"button-new-tag",attrs:{size:"small"},on:{click:t.funAdd}},[t._v("+ 添加模板")]),n("div",{staticClass:"spaceline"}),t.showForm?n("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"80px",rules:t.rules,"inline-message":!0}},[n("el-form-item",{attrs:{label:"模板名称",prop:"title"}},[n("el-input",{staticClass:"middle",model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),n("el-form-item",{attrs:{label:"内容",prop:"content"}},[n("el-input",{attrs:{type:"textarea",rows:8,placeholder:"请输入内容"},model:{value:t.form.content,callback:function(e){t.$set(t.form,"content",e)},expression:"form.content"}})],1),n("el-form-item",{attrs:{label:""}},[n("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")])],1)],1):t._e()],2)},a=[],o=n("15fd"),r=n("5530"),s=n("b775"),l=n("d722");function c(t){return Object(s["a"])({url:l["a"].categoryJobTemplateList,method:"get",params:t})}function u(t){return Object(s["a"])({url:l["a"].categoryJobTemplateAdd,method:"post",data:t})}function d(t){return Object(s["a"])({url:l["a"].categoryJobTemplateEdit,method:"post",data:t})}function f(t){return Object(s["a"])({url:l["a"].categoryJobTemplateDelete,method:"post",data:t})}var m={props:["pid"],data:function(){return{showForm:!1,list:[],form:{id:0,pid:0,title:"",content:""},rules:{title:[{required:!0,message:"请输入模板名称",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"}],content:[{required:!0,message:"请输入内容",trigger:"blur"}]}}},mounted:function(){},created:function(){this.form.pid=this.pid,this.fetchInfo()},methods:{fetchInfo:function(){var t=this;c({pid:this.pid},"get").then((function(e){t.list=e.data})).catch((function(){}))},funAdd:function(){this.form={id:0,pid:this.pid,title:"",content:""},this.showForm=!0},addSave:function(t,e){var n=this;this.$refs[e].validate((function(e){if(!e)return!1;u(t).then((function(t){return n.$message.success(t.message),n.form={id:0,pid:n.pid,title:"",content:""},n.showForm=!1,n.fetchInfo(),!0})).catch((function(){}))}))},editSave:function(t,e){var n=this;this.$refs[e].validate((function(e){if(!e)return!1;d(t).then((function(t){return n.$message.success(t.message),n.form={id:0,pid:n.pid,title:"",content:""},n.showForm=!1,n.fetchInfo(),!0})).catch((function(){}))}))},onSubmit:function(t){var e=Object(r["a"])({},this.form);if(parseInt(e.id)>0)this.editSave(e,t);else{var n=e.id,i=Object(o["a"])(e,["id"]);console.log(n),this.addSave(i,t)}},changeItem:function(t){this.form={id:t.id,pid:t.pid,title:t.title,content:t.content},this.showForm=!0},handleClose:function(t){var e=this;this.$confirm("确定删除吗？","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(n){f({id:t.id}).then((function(t){return e.$message.success(t.message),e.form={id:0,pid:e.pid,title:"",content:""},e.showForm=!1,e.fetchInfo(),!0})).catch((function(){}))})).catch((function(t){}))}}},p=m,h=(n("ed26"),n("2877")),g=Object(h["a"])(p,i,a,!1,null,"595e522d",null);e["a"]=g.exports}}]);