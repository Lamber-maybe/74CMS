(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-04777392"],{"332f":function(t,e,n){"use strict";n.d(e,"a",(function(){return r}));var a=n("78b3");function i(t){if(Array.isArray(t))return Object(a["a"])(t)}n("79fd"),n("45af"),n("b1fa"),n("1ec5"),n("2e6f"),n("af21"),n("0a4f");function o(t){if("undefined"!==typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}var c=n("ec81");function l(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function r(t){return i(t)||o(t)||Object(c["a"])(t)||l()}},"3ce1":function(t,e,n){},"4f22":function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],ref:"multipleTable",staticStyle:{width:"100%"},attrs:{type:"index",data:t.list,"header-cell-style":{background:"#fcfcfc",color:"#909399"}},on:{"selection-change":t.handleSelectionChange}},[n("el-table-column",{attrs:{type:"selection"}}),n("el-table-column",{attrs:{prop:"companyname",width:"300",label:"职位名称"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",{staticClass:"name_title",on:{click:function(n){return t.handlerOuterLink(e.row.job_link_url_web)}}},[t._v(t._s(e.row.jobname))]),n("p",{staticClass:"name_title2",on:{click:function(n){return t.handlerOuterLink(e.row.company_link_url_web)}}},[t._v(t._s(e.row.companyname))])]}}])}),n("el-table-column",{attrs:{prop:"education_text",width:"200",label:"职位要求"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("p",{staticClass:"name_title3"},[n("span",[t._v(t._s(e.row.education_text))]),t._v(" | "),n("span",[t._v(t._s(e.row.experience_text))]),t._v(" | "),n("span",[t._v(t._s(e.row.district_text))])])]}}])}),n("el-table-column",{attrs:{prop:"wage_text",label:"薪资"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",{staticClass:"font_color"},[t._v(t._s(e.row.wage_text)+"/月")])]}}])}),n("el-table-column",{attrs:{prop:"addtime",align:"center",label:"收藏时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",{staticClass:"date_title"},[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),n("el-table-column",{attrs:{align:"center",label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-button",{attrs:{size:"mini"},on:{click:function(n){return t.handlerCancel(e.row,e.$index)}}},[t._v("取消收藏")])]}}])})],1),n("div",{staticClass:"checkbox_wrapper"},[n("el-button",{attrs:{size:"mini"},on:{click:t.handlerCancelBatch}},[t._v("取消收藏")])],1),n("pagination",{attrs:{total:t.total,"current-page":t.params.page,"page-size":t.params.pagesize},on:{handleCurrentChange:t.doSearch}})],1)},i=[],o=n("332f"),c=(n("6afd"),n("ed08")),l=n("751a"),r=n("d722"),s={data:function(){return{total:0,checked:"",listLoading:!1,list:[],params:{page:1,pagesize:10},multipleSelection:[]}},filters:{timeFilter:function(t){return Object(c["c"])(t,"{y}-{m}-{d}",!0)}},mounted:function(){this.tableList(!0)},methods:{tableList:function(t){var e=this;this.listLoading=!0,l["a"].get(r["a"].personal_manage_fav_job).then((function(n){e.list=Object(o["a"])(n.data.items),1==t&&e.fetchTotal(),e.listLoading=!1})).catch((function(){}))},fetchTotal:function(){var t=this;l["a"].get(r["a"].personal_manage_attention_company_total).then((function(e){t.total=e.data})).catch((function(){}))},doSearch:function(t){this.params.page=t,this.tableList()},handleSelectionChange:function(t){this.multipleSelection=t},handlerCancel:function(t){var e=this;this.$confirm("确定取消收藏该职位吗？","系统提示",{type:"warning"}).then((function(){l["a"].post(r["a"].personal_manage_fav_job_cancel,{id:t.id}).then((function(){e.$message({type:"success",message:"取消收藏成功"}),e.tableList(!0)})).catch((function(){}))})).catch((function(){}))},handlerCancelBatch:function(){var t=this;if(0==this.multipleSelection.length)return this.$message.error("请选择职位"),!1;var e=[];this.multipleSelection.forEach((function(t){e.push(t.id)})),this.$confirm("确定取消收藏选中的职位吗？","系统提示",{type:"warning"}).then((function(){l["a"].post(r["a"].personal_manage_fav_job_cancel_batch,{id:e}).then((function(){t.$message({type:"success",message:"取消收藏成功"}),t.tableList(!0)})).catch((function(){}))})).catch((function(){}))},handlerOuterLink:function(t){window.open(t)}}},u=s,f=(n("59b7"),n("5d22")),d=Object(f["a"])(u,a,i,!1,null,"cd5d882e",null);e["default"]=d.exports},"59b7":function(t,e,n){"use strict";n("3ce1")}}]);