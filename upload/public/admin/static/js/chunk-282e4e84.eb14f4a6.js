(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-282e4e84"],{"1b28":function(e,t,r){},"2b26":function(e,t,r){"use strict";r.d(t,"a",(function(){return a}));r("f3dd"),r("fe8a");function n(e,t){if(null==e)return{};var r,n,a={},i=Object.keys(e);for(n=0;n<i.length;n++)r=i[n],t.indexOf(r)>=0||(a[r]=e[r]);return a}function a(e,t){if(null==e)return{};var r,a,i=n(e,t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);for(a=0;a<o.length;a++)r=o[a],t.indexOf(r)>=0||Object.prototype.propertyIsEnumerable.call(e,r)&&(i[r]=e[r])}return i}},"44e4":function(e,t,r){var n=r("1c8b"),a=r("cb06");n({target:"Number",stat:!0},{isInteger:a})},"513c":function(e,t,r){"use strict";var n=r("1e2c"),a=r("d890"),i=r("e8d6"),o=r("1944"),s=r("faa8"),l=r("2118"),c=r("7063"),u=r("9f67"),d=r("efe2"),m=r("6d60"),f=r("b338").f,p=r("aa6b").f,_=r("d910").f,b=r("c10f").trim,g="Number",v=a[g],h=v.prototype,y=l(m(h))==g,x=function(e){var t,r,n,a,i,o,s,l,c=u(e,!1);if("string"==typeof c&&c.length>2)if(c=b(c),t=c.charCodeAt(0),43===t||45===t){if(r=c.charCodeAt(2),88===r||120===r)return NaN}else if(48===t){switch(c.charCodeAt(1)){case 66:case 98:n=2,a=49;break;case 79:case 111:n=8,a=55;break;default:return+c}for(i=c.slice(2),o=i.length,s=0;s<o;s++)if(l=i.charCodeAt(s),l<48||l>a)return NaN;return parseInt(i,n)}return+c};if(i(g,!v(" 0o1")||!v("0b1")||v("+0x1"))){for(var w,k=function(e){var t=arguments.length<1?0:e,r=this;return r instanceof k&&(y?d((function(){h.valueOf.call(r)})):l(r)!=g)?c(new v(x(t)),r,k):x(t)},S=n?f(v):"MAX_VALUE,MIN_VALUE,NaN,NEGATIVE_INFINITY,POSITIVE_INFINITY,EPSILON,isFinite,isInteger,isNaN,isSafeInteger,MAX_SAFE_INTEGER,MIN_SAFE_INTEGER,parseFloat,parseInt,isInteger".split(","),F=0;S.length>F;F++)s(v,w=S[F])&&!s(k,w)&&_(k,w,p(v,w));k.prototype=h,h.constructor=k,o(a,g,k)}},7063:function(e,t,r){var n=r("a719"),a=r("50fb");e.exports=function(e,t,r){var i,o;return a&&"function"==typeof(i=t.constructor)&&i!==r&&n(o=i.prototype)&&o!==r.prototype&&a(e,o),e}},"72d2":function(e,t,r){"use strict";r.r(t);var n=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-tabs",{attrs:{type:"border-card"}},[r("el-tab-pane",{attrs:{label:"简历置顶",lazy:!0}},[r("resume_stick")],1),r("el-tab-pane",{attrs:{label:"简历标签",lazy:!0}},[r("resume_tag")],1)],1)],1)},a=[],i=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.listLoading,expression:"listLoading"}],staticStyle:{width:"100%"},attrs:{"element-loading-text":"Loading",fit:"","highlight-current-row":"",data:e.list}},[r("el-table-column",{attrs:{prop:"name",label:"名称",width:"180"}}),r("el-table-column",{attrs:{label:"服务时长"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v(e._s(t.row.days)+"天")]}}])}),r("el-table-column",{attrs:{label:"服务价格"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("￥"+e._s(t.row.expense))]}}])}),r("el-table-column",{attrs:{label:"积分抵扣"},scopedSlots:e._u([{key:"default",fn:function(t){return[0==t.row.enable_points_deduct?r("span",[e._v("不允许抵扣")]):e._e(),1==t.row.enable_points_deduct?r("span",[e._v("允许全额兑换")]):e._e(),2==t.row.enable_points_deduct?r("span",[e._v(" 允许抵扣"+e._s(t.row.deduct_max)+"元 ")]):e._e()]}}])}),r("el-table-column",{attrs:{label:"显示状态"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-tag",{attrs:{type:e._f("colorFilter")(t.row.is_display)}},[e._v(" "+e._s(1==t.row.is_display?"显示":"隐藏")+" ")])]}}])}),r("el-table-column",{attrs:{label:"推荐"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-tag",{attrs:{type:e._f("colorFilter")(t.row.recommend)}},[e._v(" "+e._s(1==t.row.recommend?"是":"否")+" ")])]}}])}),r("el-table-column",{attrs:{label:"排序"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("span",[e._v(e._s(t.row.sort_id))])]}}])}),r("el-table-column",{attrs:{fixed:"right",label:"操作",width:"220"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(r){return e.funEdit(t.$index,t.row)}}},[e._v(" 编辑 ")]),r("el-button",{attrs:{size:"small",type:"danger",disabled:e.form.reg_service==t.row.id},on:{click:function(r){return e.funDelete(t.$index,t.row)}}},[e._v(" 删除 ")])]}}])})],1),r("div",{staticClass:"spaceline"}),r("el-button",{attrs:{size:"small",type:"primary"},on:{click:e.funAdd}},[e._v("添加")]),e.dialogFormVisible?r("el-dialog",{attrs:{title:e.dialogTitle,visible:e.dialogFormVisible,width:"30%","close-on-click-modal":!1},on:{"update:visible":function(t){e.dialogFormVisible=t}}},[r("el-form",{ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px",rules:e.rules,"inline-message":!1}},[r("el-form-item",{attrs:{label:"服务名称",prop:"name"}},[r("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),r("el-form-item",{attrs:{label:"服务时长",prop:"days"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.days,callback:function(t){e.$set(e.form,"days",e._n(t))},expression:"form.days"}})],1),r("el-form-item",{attrs:{label:"服务价格",prop:"expense"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.expense,callback:function(t){e.$set(e.form,"expense",e._n(t))},expression:"form.expense"}}),e._v(" 元 ")],1),r("el-form-item",{attrs:{label:"积分抵扣",prop:"enable_points_deduct"}},[r("el-radio-group",{model:{value:e.form.enable_points_deduct,callback:function(t){e.$set(e.form,"enable_points_deduct",t)},expression:"form.enable_points_deduct"}},[r("el-radio",{attrs:{label:"0"}},[e._v("不允许")]),r("el-radio",{attrs:{label:"1"}},[e._v("全额兑换")]),r("el-radio",{attrs:{label:"2"}},[e._v("部分抵扣")])],1)],1),2==e.form.enable_points_deduct?r("el-form-item",{attrs:{label:"最大允许抵扣",prop:"deduct_max"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.deduct_max,callback:function(t){e.$set(e.form,"deduct_max",e._n(t))},expression:"form.deduct_max"}}),e._v(" 元 ")],1):e._e(),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.sort_id,callback:function(t){e.$set(e.form,"sort_id",e._n(t))},expression:"form.sort_id"}})],1),r("el-form-item",{attrs:{label:"显示",prop:"is_display"}},[r("el-switch",{model:{value:e.form.is_display,callback:function(t){e.$set(e.form,"is_display",t)},expression:"form.is_display"}})],1),r("el-form-item",{attrs:{label:"推荐",prop:"recommend"}},[r("el-switch",{model:{value:e.form.recommend,callback:function(t){e.$set(e.form,"recommend",t)},expression:"form.recommend"}})],1),r("el-form-item",{attrs:{label:" "}},[r("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),r("el-button",{on:{click:e.closeDialog}},[e._v("取 消")])],1)],1)],1):e._e()],1)},o=[],s=r("2b26"),l=r("7e2d"),c=(r("44e4"),r("513c"),r("053b"),r("b775")),u=r("d722");function d(e){return Object(c["a"])({url:u["a"].serviceResumeStickList,method:"get",params:e})}function m(e){return Object(c["a"])({url:u["a"].serviceResumeStickAdd,method:"post",data:e})}function f(e){return Object(c["a"])({url:u["a"].serviceResumeStickEdit,method:"post",data:e})}function p(e){return Object(c["a"])({url:u["a"].serviceResumeStickDelete,method:"post",data:e})}var _=["id"],b={filters:{colorFilter:function(e){return 1==e?"success":"danger"}},data:function(){var e=this,t=function(t,r,n){if(2==e.form.enable_points_deduct){if(!r)return n(new Error("请输入最大允许抵扣金额"));if(parseFloat(r)>=parseFloat(e.form.expense))return n(new Error("抵扣金额必须小于服务价格"))}return n()},r=function(t,r,n){return r<=0?n(new Error("值必须大于0")):2==e.form.enable_points_deduct&&parseFloat(r)<=parseFloat(e.form.deduct_max)?n(new Error("服务价格必须大于抵扣金额")):n()},n=function(e,t,r){return Number.isInteger(t)?t<=0?r(new Error("值必须大于0")):r():r(new Error("请输入数字值")),r()};return{dialogTitle:"",dialogFormVisible:!1,list:null,listLoading:!0,form:{id:"",name:"",days:"",expense:"",enable_points_deduct:0,deduct_max:"",is_display:!0,recommend:!0,sort_id:0},rules:{name:[{required:!0,message:"请输入服务名称",trigger:"blur"}],days:[{required:!0,message:"请输入服务时长",trigger:"blur"},{validator:n,trigger:"blur"}],expense:[{required:!0,message:"请输入服务价格",trigger:"blur"},{validator:r,trigger:"blur"}],deduct_max:[{validator:t,trigger:"blur"}],sort_id:[{required:!0,message:"请输入排序",trigger:"blur"}]}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;this.listLoading=!0;var t={};d(t).then((function(t){e.list=t.data.items,e.listLoading=!1})).catch((function(){}))},addSave:function(e,t){var r=this;this.$refs[t].validate((function(t){if(!t)return!1;m(e).then((function(e){return r.$message.success(e.message),r.closeDialog(),r.fetchData(),!0})).catch((function(){}))}))},editSave:function(e,t){var r=this;this.$refs[t].validate((function(t){if(!t)return!1;f(e).then((function(e){return r.$message.success(e.message),r.closeDialog(),r.fetchData(),!0})).catch((function(){}))}))},onSubmit:function(e){var t=this,r=Object(l["a"])({},t.form);if(r.is_display=!0===r.is_display?1:0,r.recommend=!0===r.recommend?1:0,parseInt(r.id)>0)t.editSave(r,e);else{var n=r.id,a=Object(s["a"])(r,_);console.log(n),t.addSave(a,e)}},funAdd:function(e,t){this.form={id:"",name:"",days:"",expense:"",enable_points_deduct:"0",deduct_max:"",is_display:!0,recommend:!0,sort_id:0},this.dialogTitle="添加服务",this.dialogFormVisible=!0},funEdit:function(e,t){this.form={id:t.id,name:t.name,days:t.days,expense:t.expense,enable_points_deduct:t.enable_points_deduct+"",deduct_max:t.deduct_max,is_display:1==t.is_display,recommend:1==t.recommend,sort_id:t.sort_id},this.dialogTitle="编辑服务",this.dialogFormVisible=!0},funDelete:function(e,t){var r=this;r.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.id};p(e).then((function(e){return r.$message.success(e.message),r.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},closeDialog:function(){this.dialogFormVisible=!1}}},g=b,v=(r("8c2f"),r("5d22")),h=Object(v["a"])(g,i,o,!1,null,"15bb0942",null),y=h.exports,x=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.listLoading,expression:"listLoading"}],staticStyle:{width:"100%"},attrs:{"element-loading-text":"Loading",fit:"","highlight-current-row":"",data:e.list}},[r("el-table-column",{attrs:{prop:"name",label:"名称","min-width":"180"}}),r("el-table-column",{attrs:{label:"服务时长"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v(e._s(t.row.days)+"天")]}}])}),r("el-table-column",{attrs:{label:"服务价格"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("￥"+e._s(t.row.expense))]}}])}),r("el-table-column",{attrs:{label:"积分抵扣"},scopedSlots:e._u([{key:"default",fn:function(t){return[0==t.row.enable_points_deduct?r("span",[e._v("不允许抵扣")]):e._e(),1==t.row.enable_points_deduct?r("span",[e._v("允许全额兑换")]):e._e(),2==t.row.enable_points_deduct?r("span",[e._v(" 允许抵扣"+e._s(t.row.deduct_max)+"元 ")]):e._e()]}}])}),r("el-table-column",{attrs:{label:"显示状态"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-tag",{attrs:{type:e._f("colorFilter")(t.row.is_display)}},[e._v(" "+e._s(1==t.row.is_display?"显示":"隐藏")+" ")])]}}])}),r("el-table-column",{attrs:{label:"推荐"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-tag",{attrs:{type:e._f("colorFilter")(t.row.recommend)}},[e._v(" "+e._s(1==t.row.recommend?"是":"否")+" ")])]}}])}),r("el-table-column",{attrs:{label:"排序"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("span",[e._v(e._s(t.row.sort_id))])]}}])}),r("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"220"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(r){return e.funEdit(t.$index,t.row)}}},[e._v(" 编辑 ")]),r("el-button",{attrs:{size:"small",type:"danger",disabled:e.form.reg_service==t.row.id},on:{click:function(r){return e.funDelete(t.$index,t.row)}}},[e._v(" 删除 ")])]}}])})],1),r("div",{staticClass:"spaceline"}),r("el-button",{attrs:{size:"small",type:"primary"},on:{click:e.funAdd}},[e._v("添加")]),e.dialogFormVisible?r("el-dialog",{attrs:{title:e.dialogTitle,visible:e.dialogFormVisible,width:"30%","close-on-click-modal":!1},on:{"update:visible":function(t){e.dialogFormVisible=t}}},[r("el-form",{ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px",rules:e.rules,"inline-message":!1}},[r("el-form-item",{attrs:{label:"服务名称",prop:"name"}},[r("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),r("el-form-item",{attrs:{label:"服务时长",prop:"days"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.days,callback:function(t){e.$set(e.form,"days",e._n(t))},expression:"form.days"}})],1),r("el-form-item",{attrs:{label:"服务价格",prop:"expense"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.expense,callback:function(t){e.$set(e.form,"expense",e._n(t))},expression:"form.expense"}}),e._v(" 元 ")],1),r("el-form-item",{attrs:{label:"积分抵扣",prop:"enable_points_deduct"}},[r("el-radio-group",{model:{value:e.form.enable_points_deduct,callback:function(t){e.$set(e.form,"enable_points_deduct",t)},expression:"form.enable_points_deduct"}},[r("el-radio",{attrs:{label:"0"}},[e._v("不允许")]),r("el-radio",{attrs:{label:"1"}},[e._v("全额兑换")]),r("el-radio",{attrs:{label:"2"}},[e._v("部分抵扣")])],1)],1),2==e.form.enable_points_deduct?r("el-form-item",{attrs:{label:"最大允许抵扣",prop:"deduct_max"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.deduct_max,callback:function(t){e.$set(e.form,"deduct_max",e._n(t))},expression:"form.deduct_max"}}),e._v(" 元 ")],1):e._e(),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{attrs:{type:"number"},model:{value:e.form.sort_id,callback:function(t){e.$set(e.form,"sort_id",e._n(t))},expression:"form.sort_id"}})],1),r("el-form-item",{attrs:{label:"显示",prop:"is_display"}},[r("el-switch",{model:{value:e.form.is_display,callback:function(t){e.$set(e.form,"is_display",t)},expression:"form.is_display"}})],1),r("el-form-item",{attrs:{label:"推荐",prop:"recommend"}},[r("el-switch",{model:{value:e.form.recommend,callback:function(t){e.$set(e.form,"recommend",t)},expression:"form.recommend"}})],1),r("el-form-item",{attrs:{label:" "}},[r("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),r("el-button",{on:{click:e.closeDialog}},[e._v("取 消")])],1)],1)],1):e._e()],1)},w=[];function k(e){return Object(c["a"])({url:u["a"].serviceResumeTagList,method:"get",params:e})}function S(e){return Object(c["a"])({url:u["a"].serviceResumeTagAdd,method:"post",data:e})}function F(e){return Object(c["a"])({url:u["a"].serviceResumeTagEdit,method:"post",data:e})}function $(e){return Object(c["a"])({url:u["a"].serviceResumeTagDelete,method:"post",data:e})}var E=["id"],O={filters:{colorFilter:function(e){return 1==e?"success":"danger"}},data:function(){var e=this,t=function(t,r,n){if(2==e.form.enable_points_deduct){if(!r)return n(new Error("请输入最大允许抵扣金额"));if(parseFloat(r)>=parseFloat(e.form.expense))return n(new Error("抵扣金额必须小于服务价格"))}return n()},r=function(t,r,n){return r<=0?n(new Error("值必须大于0")):2==e.form.enable_points_deduct&&parseFloat(r)<=parseFloat(e.form.deduct_max)?n(new Error("服务价格必须大于抵扣金额")):n()},n=function(e,t,r){return Number.isInteger(t)?t<=0?r(new Error("值必须大于0")):r():r(new Error("请输入数字值")),r()};return{dialogTitle:"",dialogFormVisible:!1,list:null,listLoading:!0,form:{id:"",name:"",days:"",expense:"",enable_points_deduct:0,deduct_max:"",is_display:!0,recommend:!0,sort_id:0},rules:{name:[{required:!0,message:"请输入服务名称",trigger:"blur"}],days:[{required:!0,message:"请输入服务时长",trigger:"blur"},{validator:n,trigger:"blur"}],expense:[{required:!0,message:"请输入服务价格",trigger:"blur"},{validator:r,trigger:"blur"}],deduct_max:[{validator:t,trigger:"blur"}],sort_id:[{required:!0,message:"请输入排序",trigger:"blur"}]}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;this.listLoading=!0;var t={};k(t).then((function(t){e.list=t.data.items,e.listLoading=!1})).catch((function(){}))},addSave:function(e,t){var r=this;this.$refs[t].validate((function(t){if(!t)return!1;S(e).then((function(e){return r.$message.success(e.message),r.closeDialog(),r.fetchData(),!0})).catch((function(){}))}))},editSave:function(e,t){var r=this;this.$refs[t].validate((function(t){if(!t)return!1;F(e).then((function(e){return r.$message.success(e.message),r.closeDialog(),r.fetchData(),!0})).catch((function(){}))}))},onSubmit:function(e){var t=this,r=Object(l["a"])({},t.form);if(r.is_display=!0===r.is_display?1:0,r.recommend=!0===r.recommend?1:0,parseInt(r.id)>0)t.editSave(r,e);else{var n=r.id,a=Object(s["a"])(r,E);console.log(n),t.addSave(a,e)}},funAdd:function(e,t){this.form={id:"",name:"",days:"",expense:"",enable_points_deduct:"0",deduct_max:"",is_display:!0,recommend:!0,sort_id:0},this.dialogTitle="添加服务",this.dialogFormVisible=!0},funEdit:function(e,t){this.form={id:t.id,name:t.name,days:t.days,expense:t.expense,enable_points_deduct:t.enable_points_deduct+"",deduct_max:t.deduct_max,is_display:1==t.is_display,recommend:1==t.recommend,sort_id:t.sort_id},this.dialogTitle="编辑服务",this.dialogFormVisible=!0},funDelete:function(e,t){var r=this;r.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.id};$(e).then((function(e){return r.$message.success(e.message),r.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},closeDialog:function(){this.dialogFormVisible=!1}}},I=O,D=(r("acc1"),Object(v["a"])(I,x,w,!1,null,"12c456a4",null)),N=D.exports,T={components:{resume_stick:y,resume_tag:N}},j=T,V=Object(v["a"])(j,n,a,!1,null,null,null);t["default"]=V.exports},"8c2f":function(e,t,r){"use strict";r("e90f")},acc1:function(e,t,r){"use strict";r("1b28")},cb06:function(e,t,r){var n=r("a719"),a=Math.floor;e.exports=function(e){return!n(e)&&isFinite(e)&&a(e)===e}},e90f:function(e,t,r){}}]);