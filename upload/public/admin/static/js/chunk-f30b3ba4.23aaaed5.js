(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-f30b3ba4"],{"15fd":function(t,e,i){"use strict";i.d(e,"a",(function(){return n}));i("a4d3"),i("b64b");function a(t,e){if(null==t)return{};var i,a,n={},o=Object.keys(t);for(a=0;a<o.length;a++)i=o[a],e.indexOf(i)>=0||(n[i]=t[i]);return n}function n(t,e){if(null==t)return{};var i,n,o=a(t,e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);for(n=0;n<r.length;n++)i=r[n],e.indexOf(i)>=0||Object.prototype.propertyIsEnumerable.call(t,i)&&(o[i]=t[i])}return o}},"4bd3":function(t,e,i){"use strict";i.r(e);var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[t._m(0),i("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.funAdd}},[t._v("添加")]),i("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.onSubmit()}}},[t._v("保存")]),i("div",{staticClass:"spaceline"}),i("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],key:t.tablekey,staticStyle:{width:"100%","margin-bottom":"20px"},attrs:{"element-loading-text":"Loading",size:"mini",data:t.list,"row-key":"id",border:"","default-expand-all":!1,lazy:!0,load:t.loadData,"tree-props":{children:"children",hasChildren:"hasChildren"}}},[i("el-table-column",{attrs:{prop:"name",label:"分类名称"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-input",{staticClass:"mini",attrs:{size:"mini"},model:{value:e.row.name,callback:function(i){t.$set(e.row,"name",i)},expression:"scope.row.name"}}),t._v(" (ID:"+t._s(e.row.id)+") ")]}}])}),i("el-table-column",{attrs:{prop:"spell",label:"别名",width:"180"}}),i("el-table-column",{attrs:{prop:"sort_id",label:"排序",width:"180"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-input",{staticClass:"mini",attrs:{size:"mini"},model:{value:e.row.sort_id,callback:function(i){t.$set(e.row,"sort_id",i)},expression:"scope.row.sort_id"}})]}}])}),i("el-table-column",{attrs:{fixed:"right",label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[3!=e.row.level?i("el-button",{attrs:{size:"small"},on:{click:function(i){return t.funAdd(e.row)}}},[t._v(" 此类下添加子类 ")]):t._e(),i("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(i){return t.funEdit(e.row)}}},[t._v(" 编辑 ")]),i("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(i){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),t.dialogFormVisible?i("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogFormVisible,width:"35%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[i("diaform",{attrs:{"form-id":t.formId,"form-parentid":t.formParentid},on:{setDialogFormVisible:t.closeDialog,pageReload:t.pageReload}})],1):t._e()],1)},n=[function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"tip"},[i("p",[t._v(" 正式使用后删除分类会导致与此分类关联的信息类别丢失，修改不会受影响。 ")]),i("p",[t._v(" 删除顶级分类将会自动删除此分类下的子分类。 ")])])}],o=i("2909"),r=(i("99af"),function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"100px",rules:t.rules,"inline-message":!0}},[i("el-form-item",{attrs:{label:"所属分类",prop:"parentid"}},[i("el-cascader",{attrs:{placeholder:"顶级",options:t.options,"show-all-levels":!1,props:{checkStrictly:!0},clearable:""},model:{value:t.form.parentid,callback:function(e){t.$set(t.form,"parentid",e)},expression:"form.parentid"}})],1),i("el-form-item",{attrs:{label:"名称",prop:"name"}},[i("el-input",{staticClass:"middle",model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),i("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[i("el-input",{staticClass:"mini",model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",t._n(e))},expression:"form.sort_id"}})],1),i("el-form-item",{attrs:{label:""}},[i("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),i("el-button",{on:{click:t.closeDialog}},[t._v("取 消")])],1)],1)],1)}),s=[],l=i("15fd"),c=i("5530"),d=(i("4de4"),i("d3b7"),i("b775")),u=i("d722");function f(t){return Object(d["a"])({url:u["a"].configDistrict,method:"get",params:t})}function m(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(d["a"])({url:u["a"].configDistrictEdit,method:e,data:t}):Object(d["a"])({url:u["a"].configDistrictEdit,method:e,params:t})}function p(t){return Object(d["a"])({url:u["a"].configDistrictAdd,method:"post",data:t})}function h(t){return Object(d["a"])({url:u["a"].configDistrictOptions,method:"GET",params:t})}function g(t){return Object(d["a"])({url:u["a"].configDistrictDelete,method:"post",data:t})}function b(t){return Object(d["a"])({url:u["a"].configDistrictTableSave,method:"post",data:t})}var v=["id"],D={props:["formId","formParentid"],data:function(){return{infoLoading:!1,options:[],form:{id:0,parentid:[],name:"",sort_id:0},rules:{name:[{required:!0,message:"请输入名称",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"}],sort_id:[{type:"number",message:"排序必须为数字",trigger:"blur"}]}}},mounted:function(){},created:function(){this.form.parentid=this.formParentid,this.fetchCategoryData()},methods:{fetchInfo:function(t){var e=this,i={id:t};m(i,"get").then((function(t){e.form=Object(c["a"])({},t.data.info),e.infoLoading=!1}))},fetchCategoryData:function(){var t=this;this.infoLoading=!0;var e={};h(e).then((function(e){t.options=e.data,t.formId&&t.fetchInfo(t.formId),t.infoLoading=!1})).catch((function(){}))},addSave:function(t,e){var i=this;this.$refs[e].validate((function(e){if(!e)return!1;p(t).then((function(t){return i.$message.success(t.message),i.closeDialog(),i.pageReload(),!0})).catch((function(){}))}))},editSave:function(t,e){var i=this;this.$refs[e].validate((function(e){if(!e)return!1;m(t).then((function(t){return i.$message.success(t.message),i.closeDialog(),i.pageReload(),!0})).catch((function(){}))}))},onSubmit:function(t){var e=this,i=e.form.parentid.filter((function(t){if(""!=t||null!=t||"undefined"!=t)return t})),a=i.length;switch(a){case 0:e.form.level=1;break;case 1:e.form.level=2;break;case 2:e.form.level=3;break}var n=Object(c["a"])({},this.form);if(parseInt(n.id)>0)this.editSave(n,t);else{var o=n.id,r=Object(l["a"])(n,v);console.log(o),this.addSave(r,t)}},closeDialog:function(){this.$emit("setDialogFormVisible")},pageReload:function(){this.$emit("pageReload")}}},_=D,y=i("2877"),w=Object(y["a"])(_,r,s,!1,null,null,null),k=w.exports,O={components:{diaform:k},data:function(){return{tablekey:1,formParentid:[],formId:0,dialogTitle:"",infoLoading:!1,dialogFormVisible:!1,saveData:[],list:[],listLoading:!0}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;t.listLoading=!0;var e={};f(e,"get").then((function(e){t.list=Object(o["a"])(e.data),t.saveData=Object(o["a"])(t.list),t.listLoading=!1}))},funAdd:function(t){this.formId=0,t?0==t.pid?this.formParentid=[t.id]:this.formParentid=[t.pid,t.id]:this.formParentid=[],this.dialogTitle="添加分类",this.dialogFormVisible=!0},funEdit:function(t){this.formId=t.id,this.dialogTitle="编辑分类",this.dialogFormVisible=!0},funDelete:function(t,e){var i=this;i.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};g(t).then((function(t){return i.$message.success(t.message),i.fetchData(),!0}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0==t.tableIdarr.length)return t.$message.error("请选择要删除的数据"),!1;t.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};g(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},loadData:function(t,e,i){var a=this,n={pid:t.id};f(n,"get").then((function(t){i(t.data),a.saveData=a.saveData.concat(t.data)})).catch((function(){}))},onSubmit:function(){var t=this,e=Object(o["a"])(t.saveData);b(e).then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))},closeDialog:function(){this.dialogFormVisible=!1},pageReload:function(){this.fetchData(),this.tablekey=!this.tablekey}}},x=O,$=Object(y["a"])(x,a,n,!1,null,null,null);e["default"]=$.exports}}]);