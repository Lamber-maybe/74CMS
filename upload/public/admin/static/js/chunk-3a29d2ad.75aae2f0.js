(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3a29d2ad"],{"15fd":function(t,e,a){"use strict";a.d(e,"a",(function(){return n}));a("a4d3"),a("b64b");function i(t,e){if(null==t)return{};var a,i,n={},o=Object.keys(t);for(i=0;i<o.length;i++)a=o[i],e.indexOf(a)>=0||(n[a]=t[a]);return n}function n(t,e){if(null==t)return{};var a,n,o=i(t,e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);for(n=0;n<r.length;n++)a=r[n],e.indexOf(a)>=0||Object.prototype.propertyIsEnumerable.call(t,a)&&(o[a]=t[a])}return o}},1780:function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[t._m(0),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.funAdd}},[t._v("添加")]),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.onSubmit()}}},[t._v("保存")]),a("div",{staticClass:"spaceline"}),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],key:t.tablekey,staticStyle:{width:"100%","margin-bottom":"20px"},attrs:{"element-loading-text":"Loading",size:"mini",data:t.list,"row-key":"id",border:"","default-expand-all":!1,lazy:!0,load:t.loadData,"tree-props":{children:"children",hasChildren:"hasChildren"}}},[a("el-table-column",{attrs:{prop:"name",label:"分类名称"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-input",{staticClass:"small",attrs:{size:"mini"},model:{value:e.row.name,callback:function(a){t.$set(e.row,"name",a)},expression:"scope.row.name"}}),t._v(" (ID:"+t._s(e.row.id)+") ")]}}])}),a("el-table-column",{attrs:{prop:"sort_id",label:"排序",width:"180"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-input",{staticClass:"mini",attrs:{size:"mini"},model:{value:e.row.sort_id,callback:function(a){t.$set(e.row,"sort_id",a)},expression:"scope.row.sort_id"}})]}}])}),a("el-table-column",{attrs:{fixed:"right",label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[2!=e.row.level?a("el-button",{attrs:{size:"small"},on:{click:function(a){return t.funAdd(e.$index,e.row)}}},[t._v(" 此类下添加子类 ")]):t._e(),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),t.dialogFormVisible?a("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogFormVisible,width:"35%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[a("diaform",{attrs:{id:t.childId,pid:t.childPid},on:{setDialogFormVisible:t.closeDialog,pageReload:t.pageReload}})],1):t._e()],1)},n=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"tip"},[a("p",[t._v(" 正式使用后删除分类会导致与此分类关联的信息类别丢失，修改不会受影响。 ")]),a("p",[t._v(" 删除顶级分类将会自动删除此分类下的子分类。 ")])])}],o=a("2909"),r=(a("99af"),function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"100px",rules:t.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"所属分类",prop:"parentid"}},[a("el-cascader",{attrs:{placeholder:"顶级",options:t.options,"show-all-levels":!1,props:{checkStrictly:!0},"popper-class":"cancelRadio",clearable:""},model:{value:t.form.parentid,callback:function(e){t.$set(t.form,"parentid",e)},expression:"form.parentid"}})],1),a("el-form-item",{attrs:{label:"名称",prop:"name"}},[a("el-input",{staticClass:"middle",model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),a("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[a("el-input",{staticClass:"mini",model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),a("el-button",{on:{click:t.closeDialog}},[t._v("取 消")])],1)],1)],1)}),l=[],s=a("15fd"),c=a("5530"),d=a("b775"),u=a("d722");function f(t){return Object(d["a"])({url:u["a"].configMajor,method:"get",params:t})}function m(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(d["a"])({url:u["a"].configMajorEdit,method:e,data:t}):Object(d["a"])({url:u["a"].configMajorEdit,method:e,params:t})}function h(t){return Object(d["a"])({url:u["a"].configMajorAdd,method:"post",data:t})}function p(t){return Object(d["a"])({url:u["a"].configMajorOptions,method:"GET",params:t})}function g(t){return Object(d["a"])({url:u["a"].configMajorDelete,method:"post",data:t})}function b(t){return Object(d["a"])({url:u["a"].configMajorTableSave,method:"post",data:t})}var v=["id"],_={props:["pid","id"],data:function(){return{infoLoading:!1,options:[],form:{id:0,parentid:[],name:"",sort_id:0},rules:{name:[{required:!0,message:"请输入名称",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}]}}},mounted:function(){},created:function(){this.fetchCategoryData()},methods:{fetchInfo:function(t){var e=this,a={id:t};m(a,"get").then((function(t){e.form=Object(c["a"])({},t.data.info),e.infoLoading=!1})).catch((function(){}))},fetchCategoryData:function(){var t=this;this.infoLoading=!0;var e={};p(e).then((function(e){t.options=e.data;var a=t.pid,i=t.id;a?t.form.parentid=[a]:i&&t.fetchInfo(i),t.infoLoading=!1})).catch((function(){}))},addSave:function(t,e){var a=this;this.$refs[e].validate((function(e){if(!e)return!1;h(t).then((function(t){return a.$message.success(t.message),a.closeDialog(),a.pageReload(),!0})).catch((function(){}))}))},editSave:function(t,e){var a=this;this.$refs[e].validate((function(e){if(!e)return!1;m(t).then((function(t){return a.$message.success(t.message),a.closeDialog(),a.pageReload(),!0})).catch((function(){}))}))},onSubmit:function(t){var e=this,a=e.form.parentid.length;switch(a){case 0:e.form.level=1;break;case 1:e.form.level=2;break;case 2:e.form.level=3;break}var i=Object(c["a"])({},this.form);if(parseInt(i.id)>0)this.editSave(i,t);else{var n=i.id,o=Object(s["a"])(i,v);console.log(n),this.addSave(o,t)}},closeDialog:function(){this.$emit("setDialogFormVisible")},pageReload:function(){this.$emit("pageReload")}}},y=_,k=a("2877"),w=Object(k["a"])(y,r,l,!1,null,null,null),D=w.exports,j={components:{diaform:D},data:function(){return{tablekey:1,childPid:0,childId:0,dialogTitle:"",infoLoading:!1,dialogFormVisible:!1,saveData:[],list:[],listLoading:!0}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;t.listLoading=!0;var e={};f(e).then((function(e){t.list=Object(o["a"])(e.data),t.saveData=Object(o["a"])(t.list),t.listLoading=!1})).catch((function(){}))},funAdd:function(t,e){this.childId=0,this.childPid=e?e.id:0,this.dialogTitle="添加分类",this.dialogFormVisible=!0},funEdit:function(t,e){this.childPid=0,this.childId=e?e.id:0,this.dialogTitle="编辑分类",this.dialogFormVisible=!0},funDelete:function(t,e){var a=this;a.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};g(t).then((function(t){return a.$message.success(t.message),a.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0==t.tableIdarr.length)return t.$message.error("请选择要删除的数据"),!1;t.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};g(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},loadData:function(t,e,a){var i=this,n={pid:t.id};f(n).then((function(t){a(t.data),i.saveData=i.saveData.concat(t.data)})).catch((function(){}))},onSubmit:function(){var t=this,e=Object(o["a"])(t.saveData);b(e).then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))},closeDialog:function(){this.dialogFormVisible=!1},pageReload:function(){this.fetchData(),this.tablekey=!this.tablekey}}},x=j,O=Object(k["a"])(x,i,n,!1,null,null,null);e["default"]=O.exports}}]);