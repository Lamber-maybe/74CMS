(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-73da9668"],{"16ab":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("el-card",[a("div",[a("menuheader",[t._v("浏览记录")]),a("div",{staticClass:"menu_content"},[a("menu-nav",{attrs:{"active-tab":t.params.type,list:t.navList}})],1),1==t.params.type?a("div",{staticClass:"list"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.dataset,"header-cell-style":{background:"#fcfcfc",color:"#b8babd"}},on:{"selection-change":t.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection"}}),a("el-table-column",{attrs:{"header-align":"left",align:"left",label:"基本信息"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("div",{staticClass:"information_wrapper"},[a("div",{staticClass:"portraitImg"},[a("img",{attrs:{src:e.row.photo_img_src}})]),a("div",{staticClass:"information_list"},[a("div",{staticClass:"fn_bar"},[a("a",{staticClass:"name a-link",attrs:{href:e.row.resume_link_url_web,target:"_blank"}},[t._v(t._s(e.row.fullname))]),e.row.high_quality>0?a("div",{staticClass:"hq"}):t._e(),a("div",{staticClass:"clear"})]),a("p",{staticClass:"information"},[t._v(t._s(e.row.age)+"岁 | "+t._s(e.row.education_text)+" | "+t._s(e.row.experience_text)+" | "+t._s(e.row.sex_text)+" ")])])])]}}],null,!1,3801032405)}),a("el-table-column",{attrs:{width:"340px","header-align":"left",label:"求职意向"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("p",{staticClass:"intention_post"},[t._v("想找 "),a("span",[t._v(t._s(e.row.intention_jobs))]),t._v(" 工作")]),a("p",{staticClass:"intention_local"},[t._v("想在 "),a("span",[t._v(t._s(e.row.intention_district))]),t._v(" 工作")])]}}],null,!1,459763205)}),a("el-table-column",{attrs:{"header-align":"center",align:"center",label:"浏览时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("div",{staticClass:"time"},[t._v(" "+t._s(t._f("timeFilter")(e.row.addtime))+" ")])]}}],null,!1,406272282)}),a("el-table-column",{attrs:{"header-align":"center",width:"170px",label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(a){return t.handlerDownload(e.row.resume_id)}}},[t._v("下载简历")]),a("el-button",{attrs:{size:"mini"},on:{click:function(a){return t.handlerDel(e.row)}}},[t._v("删除")])]}}],null,!1,1726646142)})],1),a("div",{staticClass:"del_btn"},[a("el-button",{attrs:{type:"mini"},on:{click:t.handlerDelBatch}},[t._v("删除")])],1),a("pagination",{attrs:{total:t.total,"current-page":t.params.page,"page-size":t.params.pagesize},on:{handleCurrentChange:t.doSearch}})],1):t._e(),0==t.params.type?a("div",{staticClass:"list"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.dataset,"header-cell-style":{background:"#fcfcfc",color:"#b8babd"}},on:{"selection-change":t.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection"}}),a("el-table-column",{attrs:{"header-align":"left",align:"left",label:"基本信息"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("div",{staticClass:"information_wrapper"},[a("div",{staticClass:"portraitImg"},[a("img",{attrs:{src:e.row.photo_img_src}})]),a("div",{staticClass:"information_list"},[a("div",{staticClass:"fn_bar"},[a("a",{staticClass:"name a-link",attrs:{href:e.row.resume_link_url_web,target:"_blank"}},[t._v(t._s(e.row.fullname))]),e.row.high_quality>0?a("div",{staticClass:"hq"}):t._e(),a("div",{staticClass:"clear"})]),a("p",{staticClass:"information"},[t._v(t._s(e.row.age)+"岁 | "+t._s(e.row.education_text)+" | "+t._s(e.row.experience_text)+" | "+t._s(e.row.sex_text)+" ")])])])]}}],null,!1,3801032405)}),a("el-table-column",{attrs:{width:"260px","header-align":"left",label:"求职意向"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("p",{staticClass:"intention_post"},[t._v("想找 "),a("span",[t._v(t._s(e.row.intention_jobs))]),t._v(" 工作")]),a("p",{staticClass:"intention_local"},[t._v("想在 "),a("span",[t._v(t._s(e.row.intention_district))]),t._v(" 工作")])]}}],null,!1,459763205)}),a("el-table-column",{attrs:{width:"100px","header-align":"left",label:"浏览职位"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("p",{staticClass:"intention_post"},[t._v(t._s(e.row.jobname))])]}}],null,!1,2790121727)}),a("el-table-column",{attrs:{"header-align":"center",align:"center",label:"浏览时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("div",{staticClass:"time"},[t._v(" "+t._s(t._f("timeFilter")(e.row.addtime))+" ")])]}}],null,!1,406272282)}),a("el-table-column",{attrs:{"header-align":"center",align:"center",width:"120px",label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(a){return t.viewresume(e.row.resume_link_url_web)}}},[t._v("查看简历")])]}}],null,!1,2110909215)})],1),a("pagination",{attrs:{total:t.total,"current-page":t.params.page,"page-size":t.params.pagesize},on:{handleCurrentChange:t.doSearch}})],1):t._e()],1)])},i=[],s=a("2909"),r=(a("d3b7"),a("159b"),a("ed08")),l=a("751a"),o=a("d722"),c=a("9dc2"),u={components:{menuheader:c["default"]},filters:{timeFilter:function(t){return Object(r["c"])(t,"{y}-{m}-{d} {h}:{i}",!0)}},data:function(){return{listLoading:!1,dataset:[],total:0,params:{type:0,page:1,pagesize:10},multipleSelection:[],showDirectService:!1,directServiceInfo:{},navList:[{label:"看过我",href:"/company/resume/browsing",name:"0",active:!0},{label:"我看过",href:"/company/resume/browsing?type=1",name:"1",active:!1}]}},watch:{$route:function(t){console.log(this.params),this.params.type=void 0===t.query.type?"0":t.query.type,this.params.page=1,this.dataset=[],this.fetchData(!0)}},created:function(){this.params.type=void 0===this.$route.query.type?"0":this.$route.query.type,this.params.page=1,this.fetchData(!0),console.log(this.params.type)},methods:{fetchData:function(t){var e=this;this.listLoading=!0,1==this.params.type?l["a"].get(o["a"].company_view_resume_list,this.params).then((function(a){e.dataset=Object(s["a"])(a.data.items),e.listLoading=!1,!0===t&&(console.log(e.params),e.fetchTotal())})).catch((function(){})):l["a"].get(o["a"].company_view_beresume_list,this.params).then((function(a){for(var n=0;n<=a.data.items.length-1;n++)null==a.data.items[n].jobname&&(a.data.items[n].jobname="职位已删除");e.dataset=Object(s["a"])(a.data.items),e.listLoading=!1,!0===t&&(console.log(e.params),e.fetchsTotal())})).catch((function(){}))},fetchsTotal:function(){var t=this;l["a"].get(o["a"].company_view_beresume_list_total,{}).then((function(e){t.total=e.data})).catch((function(){}))},fetchTotal:function(){var t=this;l["a"].get(o["a"].company_view_resume_list_total,{}).then((function(e){t.total=e.data})).catch((function(){}))},doSearch:function(t){this.params.page=t,this.fetchData()},handleSelectionChange:function(t){this.multipleSelection=t},handlerDel:function(t){var e=this;this.$confirm("确定删除该查看记录吗？","系统提示",{type:"warning"}).then((function(){l["a"].post(o["a"].company_view_resume_del,{id:t.id}).then((function(){e.$message({type:"success",message:"删除成功"}),e.fetchData(!0)})).catch((function(){}))})).catch((function(){}))},handlerDelBatch:function(){var t=this;if(0==this.multipleSelection.length)return this.$message.error("请选择记录"),!1;var e=[];this.multipleSelection.forEach((function(t){e.push(t.id)})),this.$confirm("确定删除选择的查看记录吗？","系统提示",{type:"warning"}).then((function(){l["a"].post(o["a"].company_view_resume_del_batch,{id:e}).then((function(){t.$message({type:"success",message:"删除成功"}),t.fetchData(!0)})).catch((function(){}))})).catch((function(){}))},viewresume:function(t){window.location.href=t},handlerDownload:function(t){var e=this;this.$confirm("确定下载该简历吗？","系统提示",{type:"warning"}).then((function(){l["a"].post(o["a"].resumedownload,{resume_id:t}).then((function(a){if(0==a.data.done)return e.showDirectService=!0,e.directServiceInfo={use_type:a.data.use_type,need_points:a.data.need_points,need_expense:a.data.need_expense,discount:a.data.discount,resume:t,btnCn:"points"==a.data.use_type?"立即兑换":"立即支付"},!1;e.$message({type:"success",message:a.message}),e.fetchData()})).catch((function(){}))})).catch((function(){}))}}},d=u,f=(a("1b4b"),a("2877")),p=Object(f["a"])(d,n,i,!1,null,"476540ba",null);e["default"]=p.exports},"1b4b":function(t,e,a){"use strict";a("4ba9")},2909:function(t,e,a){"use strict";a.d(e,"a",(function(){return o}));var n=a("6b75");function i(t){if(Array.isArray(t))return Object(n["a"])(t)}a("a4d3"),a("e01a"),a("d3b7"),a("d28b"),a("3ca3"),a("ddb0"),a("a630");function s(t){if("undefined"!==typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}var r=a("06c5");a("d9e2");function l(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function o(t){return i(t)||s(t)||Object(r["a"])(t)||l()}},"3a23":function(t,e,a){"use strict";var n=a("9675"),i=a.n(n);e["default"]=i.a},"4ba9":function(t,e,a){},"54ce":function(t,e,a){"use strict";a("da64")},"59ac":function(t,e,a){"use strict";a.d(e,"a",(function(){return n})),a.d(e,"b",(function(){return i}));var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"browsing_wrapper"},[a("p",[t._t("default")],2)])},i=[]},9675:function(t,e){},"9dc2":function(t,e,a){"use strict";var n=a("59ac"),i=a("3a23"),s=(a("54ce"),a("2877")),r=Object(s["a"])(i["default"],n["a"],n["b"],!1,null,"014ad7c1",null);e["default"]=r.exports},da64:function(t,e,a){}}]);