(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2062d830"],{"15fd":function(e,t,r){"use strict";r.d(t,"a",(function(){return a}));r("a4d3"),r("b64b");function n(e,t){if(null==e)return{};var r,n,a={},i=Object.keys(e);for(n=0;n<i.length;n++)r=i[n],t.indexOf(r)>=0||(a[r]=e[r]);return a}function a(e,t){if(null==e)return{};var r,a,i=n(e,t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);for(a=0;a<o.length;a++)r=o[a],t.indexOf(r)>=0||Object.prototype.propertyIsEnumerable.call(e,r)&&(i[r]=e[r])}return i}},"28f3":function(e,t,r){"use strict";r("fe9e")},4026:function(e,t,r){"use strict";r("6ae2")},"5e89":function(e,t,r){var n=r("861d"),a=Math.floor;e.exports=function(e){return!n(e)&&isFinite(e)&&a(e)===e}},"6ae2":function(e,t,r){},"72d2":function(e,t,r){"use strict";r.r(t);var n=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-tabs",{attrs:{type:"border-card"}},[r("el-tab-pane",{attrs:{label:"简历置顶",lazy:!0}},[r("resume_stick")],1),r("el-tab-pane",{attrs:{label:"简历标签",lazy:!0}},[r("resume_tag")],1)],1)],1)},a=[],i=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.listLoading,expression:"listLoading"}],staticStyle:{width:"100%"},attrs:{"element-loading-text":"Loading",fit:"","highlight-current-row":"",data:e.list}},[r("el-table-column",{attrs:{prop:"name",label:"名称","min-width":"180"}}),r("el-table-column",{attrs:{label:"服务时长","min-width":"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v(e._s(t.row.days)+"天")]}}])}),r("el-table-column",{attrs:{label:"服务价格","min-width":"100"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("￥"+e._s(t.row.expense))]}}])}),r("el-table-column",{attrs:{label:"积分抵扣","min-width":"150"},scopedSlots:e._u([{key:"default",fn:function(t){return[0==t.row.enable_points_deduct?r("span",[e._v("不允许抵扣")]):e._e(),1==t.row.enable_points_deduct?r("span",[e._v("允许全额兑换")]):e._e(),2==t.row.enable_points_deduct?r("span",[e._v(" 允许抵扣"+e._s(t.row.deduct_max)+"元 ")]):e._e()]}}])}),r("el-table-column",{attrs:{label:"显示状态","min-width":"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-tag",{attrs:{type:e._f("colorFilter")(t.row.is_display)}},[e._v(" "+e._s(1==t.row.is_display?"显示":"隐藏")+" ")])]}}])}),r("el-table-column",{attrs:{label:"推荐","min-width":"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-tag",{attrs:{type:e._f("colorFilter")(t.row.recommend)}},[e._v(" "+e._s(1==t.row.recommend?"是":"否")+" ")])]}}])}),r("el-table-column",{attrs:{label:"排序","min-width":"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("span",[e._v(e._s(t.row.sort_id))])]}}])}),r("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"150"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(r){return e.funEdit(t.$index,t.row)}}},[e._v(" 编辑 ")]),r("el-button",{attrs:{size:"small",type:"danger",disabled:e.form.reg_service==t.row.id},on:{click:function(r){return e.funDelete(t.$index,t.row)}}},[e._v(" 删除 ")])]}}])})],1),r("div",{staticClass:"spaceline"}),r("el-button",{attrs:{size:"small",type:"primary"},on:{click:e.funAdd}},[e._v("添加")]),e.dialogFormVisible?r("el-dialog",{attrs:{title:e.dialogTitle,visible:e.dialogFormVisible,width:"30%","close-on-click-modal":!1},on:{"update:visible":function(t){e.dialogFormVisible=t}}},[r("el-form",{ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px",rules:e.rules,"inline-message":!1}},[r("el-form-item",{attrs:{label:"服务名称",prop:"name"}},[r("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),r("el-form-item",{attrs:{label:"服务时长",prop:"days"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.days,callback:function(t){e.$set(e.form,"days",e._n(t))},expression:"form.days"}})],1),r("el-form-item",{attrs:{label:"服务价格",prop:"expense"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.expense,callback:function(t){e.$set(e.form,"expense",e._n(t))},expression:"form.expense"}}),e._v(" 元 ")],1),r("el-form-item",{attrs:{label:"积分抵扣",prop:"enable_points_deduct"}},[r("el-radio-group",{model:{value:e.form.enable_points_deduct,callback:function(t){e.$set(e.form,"enable_points_deduct",t)},expression:"form.enable_points_deduct"}},[r("el-radio",{attrs:{label:"0"}},[e._v("不允许")]),r("el-radio",{attrs:{label:"1"}},[e._v("全额兑换")]),r("el-radio",{attrs:{label:"2"}},[e._v("部分抵扣")])],1)],1),2==e.form.enable_points_deduct?r("el-form-item",{attrs:{label:"最大允许抵扣",prop:"deduct_max"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.deduct_max,callback:function(t){e.$set(e.form,"deduct_max",e._n(t))},expression:"form.deduct_max"}}),e._v(" 元 ")],1):e._e(),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.sort_id,callback:function(t){e.$set(e.form,"sort_id",e._n(t))},expression:"form.sort_id"}})],1),r("el-form-item",{attrs:{label:"显示",prop:"is_display"}},[r("el-switch",{model:{value:e.form.is_display,callback:function(t){e.$set(e.form,"is_display",t)},expression:"form.is_display"}})],1),r("el-form-item",{attrs:{label:"推荐",prop:"recommend"}},[r("el-switch",{model:{value:e.form.recommend,callback:function(t){e.$set(e.form,"recommend",t)},expression:"form.recommend"}})],1),r("el-form-item",{attrs:{label:" "}},[r("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),r("el-button",{on:{click:e.closeDialog}},[e._v("取 消")])],1)],1)],1):e._e()],1)},o=[],l=r("15fd"),s=r("5530"),d=(r("8ba4"),r("a9e3"),r("b0c0"),r("b775")),c=r("d722");function u(e){return Object(d["a"])({url:c["a"].serviceResumeStickList,method:"get",params:e})}function m(e){return Object(d["a"])({url:c["a"].serviceResumeStickAdd,method:"post",data:e})}function f(e){return Object(d["a"])({url:c["a"].serviceResumeStickEdit,method:"post",data:e})}function p(e){return Object(d["a"])({url:c["a"].serviceResumeStickDelete,method:"post",data:e})}var _=["id"],b={filters:{colorFilter:function(e){return 1==e?"success":"danger"}},data:function(){var e=this,t=function(t,r,n){if(2==e.form.enable_points_deduct){if(!r)return n(new Error("请输入最大允许抵扣金额"));if(parseFloat(r)>=parseFloat(e.form.expense))return n(new Error("抵扣金额必须小于服务价格"))}return n()},r=function(t,r,n){return r<=0?n(new Error("值必须大于0")):2==e.form.enable_points_deduct&&parseFloat(r)<=parseFloat(e.form.deduct_max)?n(new Error("服务价格必须大于抵扣金额")):n()},n=function(e,t,r){return Number.isInteger(t)?t<=0?r(new Error("值必须大于0")):r():r(new Error("请输入数字值")),r()};return{dialogTitle:"",dialogFormVisible:!1,list:null,listLoading:!0,form:{id:"",name:"",days:"",expense:"",enable_points_deduct:0,deduct_max:"",is_display:!0,recommend:!0,sort_id:0},rules:{name:[{required:!0,message:"请输入服务名称",trigger:"blur"}],days:[{required:!0,message:"请输入服务时长",trigger:"blur"},{validator:n,trigger:"blur"}],expense:[{required:!0,message:"请输入服务价格",trigger:"blur"},{validator:r,trigger:"blur"}],deduct_max:[{validator:t,trigger:"blur"}],sort_id:[{required:!0,message:"请输入排序",trigger:"blur"}]}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;this.listLoading=!0;var t={};u(t).then((function(t){e.list=t.data.items,e.listLoading=!1})).catch((function(){}))},addSave:function(e,t){var r=this;this.$refs[t].validate((function(t){if(!t)return!1;m(e).then((function(e){return r.$message.success(e.message),r.closeDialog(),r.fetchData(),!0})).catch((function(){}))}))},editSave:function(e,t){var r=this;this.$refs[t].validate((function(t){if(!t)return!1;f(e).then((function(e){return r.$message.success(e.message),r.closeDialog(),r.fetchData(),!0})).catch((function(){}))}))},onSubmit:function(e){var t=this,r=Object(s["a"])({},t.form);if(r.is_display=!0===r.is_display?1:0,r.recommend=!0===r.recommend?1:0,parseInt(r.id)>0)t.editSave(r,e);else{var n=r.id,a=Object(l["a"])(r,_);console.log(n),t.addSave(a,e)}},funAdd:function(e,t){this.form={id:"",name:"",days:"",expense:"",enable_points_deduct:"0",deduct_max:"",is_display:!0,recommend:!0,sort_id:0},this.dialogTitle="添加服务",this.dialogFormVisible=!0},funEdit:function(e,t){this.form={id:t.id,name:t.name,days:t.days,expense:t.expense,enable_points_deduct:t.enable_points_deduct+"",deduct_max:t.deduct_max,is_display:1==t.is_display,recommend:1==t.recommend,sort_id:t.sort_id},this.dialogTitle="编辑服务",this.dialogFormVisible=!0},funDelete:function(e,t){var r=this;r.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.id};p(e).then((function(e){return r.$message.success(e.message),r.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},closeDialog:function(){this.dialogFormVisible=!1}}},g=b,v=(r("28f3"),r("2877")),h=Object(v["a"])(g,i,o,!1,null,"aff0587a",null),y=h.exports,x=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.listLoading,expression:"listLoading"}],staticStyle:{width:"100%"},attrs:{"element-loading-text":"Loading",fit:"","highlight-current-row":"",data:e.list}},[r("el-table-column",{attrs:{prop:"name",label:"名称","min-width":"180"}}),r("el-table-column",{attrs:{label:"服务时长","min-width":"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v(e._s(t.row.days)+"天")]}}])}),r("el-table-column",{attrs:{label:"服务价格","min-width":"100"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("￥"+e._s(t.row.expense))]}}])}),r("el-table-column",{attrs:{label:"积分抵扣","min-width":"150"},scopedSlots:e._u([{key:"default",fn:function(t){return[0==t.row.enable_points_deduct?r("span",[e._v("不允许抵扣")]):e._e(),1==t.row.enable_points_deduct?r("span",[e._v("允许全额兑换")]):e._e(),2==t.row.enable_points_deduct?r("span",[e._v(" 允许抵扣"+e._s(t.row.deduct_max)+"元 ")]):e._e()]}}])}),r("el-table-column",{attrs:{label:"显示状态","min-width":"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-tag",{attrs:{type:e._f("colorFilter")(t.row.is_display)}},[e._v(" "+e._s(1==t.row.is_display?"显示":"隐藏")+" ")])]}}])}),r("el-table-column",{attrs:{label:"推荐","min-width":"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-tag",{attrs:{type:e._f("colorFilter")(t.row.recommend)}},[e._v(" "+e._s(1==t.row.recommend?"是":"否")+" ")])]}}])}),r("el-table-column",{attrs:{label:"排序","min-width":"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("span",[e._v(e._s(t.row.sort_id))])]}}])}),r("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"150"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(r){return e.funEdit(t.$index,t.row)}}},[e._v(" 编辑 ")]),r("el-button",{attrs:{size:"small",type:"danger",disabled:e.form.reg_service==t.row.id},on:{click:function(r){return e.funDelete(t.$index,t.row)}}},[e._v(" 删除 ")])]}}])})],1),r("div",{staticClass:"spaceline"}),r("el-button",{attrs:{size:"small",type:"primary"},on:{click:e.funAdd}},[e._v("添加")]),e.dialogFormVisible?r("el-dialog",{attrs:{title:e.dialogTitle,visible:e.dialogFormVisible,width:"30%","close-on-click-modal":!1},on:{"update:visible":function(t){e.dialogFormVisible=t}}},[r("el-form",{ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px",rules:e.rules,"inline-message":!1}},[r("el-form-item",{attrs:{label:"服务名称",prop:"name"}},[r("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),r("el-form-item",{attrs:{label:"服务时长",prop:"days"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.days,callback:function(t){e.$set(e.form,"days",e._n(t))},expression:"form.days"}})],1),r("el-form-item",{attrs:{label:"服务价格",prop:"expense"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.expense,callback:function(t){e.$set(e.form,"expense",e._n(t))},expression:"form.expense"}}),e._v(" 元 ")],1),r("el-form-item",{attrs:{label:"积分抵扣",prop:"enable_points_deduct"}},[r("el-radio-group",{model:{value:e.form.enable_points_deduct,callback:function(t){e.$set(e.form,"enable_points_deduct",t)},expression:"form.enable_points_deduct"}},[r("el-radio",{attrs:{label:"0"}},[e._v("不允许")]),r("el-radio",{attrs:{label:"1"}},[e._v("全额兑换")]),r("el-radio",{attrs:{label:"2"}},[e._v("部分抵扣")])],1)],1),2==e.form.enable_points_deduct?r("el-form-item",{attrs:{label:"最大允许抵扣",prop:"deduct_max"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.deduct_max,callback:function(t){e.$set(e.form,"deduct_max",e._n(t))},expression:"form.deduct_max"}}),e._v(" 元 ")],1):e._e(),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.sort_id,callback:function(t){e.$set(e.form,"sort_id",e._n(t))},expression:"form.sort_id"}})],1),r("el-form-item",{attrs:{label:"显示",prop:"is_display"}},[r("el-switch",{model:{value:e.form.is_display,callback:function(t){e.$set(e.form,"is_display",t)},expression:"form.is_display"}})],1),r("el-form-item",{attrs:{label:"推荐",prop:"recommend"}},[r("el-switch",{model:{value:e.form.recommend,callback:function(t){e.$set(e.form,"recommend",t)},expression:"form.recommend"}})],1),r("el-form-item",{attrs:{label:" "}},[r("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),r("el-button",{on:{click:e.closeDialog}},[e._v("取 消")])],1)],1)],1):e._e()],1)},w=[];function k(e){return Object(d["a"])({url:c["a"].serviceResumeTagList,method:"get",params:e})}function $(e){return Object(d["a"])({url:c["a"].serviceResumeTagAdd,method:"post",data:e})}function S(e){return Object(d["a"])({url:c["a"].serviceResumeTagEdit,method:"post",data:e})}function F(e){return Object(d["a"])({url:c["a"].serviceResumeTagDelete,method:"post",data:e})}var D=["id"],O={filters:{colorFilter:function(e){return 1==e?"success":"danger"}},data:function(){var e=this,t=function(t,r,n){if(2==e.form.enable_points_deduct){if(!r)return n(new Error("请输入最大允许抵扣金额"));if(parseFloat(r)>=parseFloat(e.form.expense))return n(new Error("抵扣金额必须小于服务价格"))}return n()},r=function(t,r,n){return r<=0?n(new Error("值必须大于0")):2==e.form.enable_points_deduct&&parseFloat(r)<=parseFloat(e.form.deduct_max)?n(new Error("服务价格必须大于抵扣金额")):n()},n=function(e,t,r){return Number.isInteger(t)?t<=0?r(new Error("值必须大于0")):r():r(new Error("请输入数字值")),r()};return{dialogTitle:"",dialogFormVisible:!1,list:null,listLoading:!0,form:{id:"",name:"",days:"",expense:"",enable_points_deduct:0,deduct_max:"",is_display:!0,recommend:!0,sort_id:0},rules:{name:[{required:!0,message:"请输入服务名称",trigger:"blur"}],days:[{required:!0,message:"请输入服务时长",trigger:"blur"},{validator:n,trigger:"blur"}],expense:[{required:!0,message:"请输入服务价格",trigger:"blur"},{validator:r,trigger:"blur"}],deduct_max:[{validator:t,trigger:"blur"}],sort_id:[{required:!0,message:"请输入排序",trigger:"blur"}]}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;this.listLoading=!0;var t={};k(t).then((function(t){e.list=t.data.items,e.listLoading=!1})).catch((function(){}))},addSave:function(e,t){var r=this;this.$refs[t].validate((function(t){if(!t)return!1;$(e).then((function(e){return r.$message.success(e.message),r.closeDialog(),r.fetchData(),!0})).catch((function(){}))}))},editSave:function(e,t){var r=this;this.$refs[t].validate((function(t){if(!t)return!1;S(e).then((function(e){return r.$message.success(e.message),r.closeDialog(),r.fetchData(),!0})).catch((function(){}))}))},onSubmit:function(e){var t=this,r=Object(s["a"])({},t.form);if(r.is_display=!0===r.is_display?1:0,r.recommend=!0===r.recommend?1:0,parseInt(r.id)>0)t.editSave(r,e);else{var n=r.id,a=Object(l["a"])(r,D);console.log(n),t.addSave(a,e)}},funAdd:function(e,t){this.form={id:"",name:"",days:"",expense:"",enable_points_deduct:"0",deduct_max:"",is_display:!0,recommend:!0,sort_id:0},this.dialogTitle="添加服务",this.dialogFormVisible=!0},funEdit:function(e,t){this.form={id:t.id,name:t.name,days:t.days,expense:t.expense,enable_points_deduct:t.enable_points_deduct+"",deduct_max:t.deduct_max,is_display:1==t.is_display,recommend:1==t.recommend,sort_id:t.sort_id},this.dialogTitle="编辑服务",this.dialogFormVisible=!0},funDelete:function(e,t){var r=this;r.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.id};F(e).then((function(e){return r.$message.success(e.message),r.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},closeDialog:function(){this.dialogFormVisible=!1}}},E=O,j=(r("4026"),Object(v["a"])(E,x,w,!1,null,"42ab114a",null)),T=j.exports,L={components:{resume_stick:y,resume_tag:T}},V=L,q=Object(v["a"])(V,n,a,!1,null,null,null);t["default"]=q.exports},"8ba4":function(e,t,r){var n=r("23e7"),a=r("5e89");n({target:"Number",stat:!0},{isInteger:a})},fe9e:function(e,t,r){}}]);