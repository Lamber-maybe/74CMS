(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-639ce196"],{2909:function(t,e,a){"use strict";a.d(e,"a",(function(){return r}));var n=a("6b75");function i(t){if(Array.isArray(t))return Object(n["a"])(t)}a("a4d3"),a("e01a"),a("d3b7"),a("d28b"),a("3ca3"),a("ddb0"),a("a630");function c(t){if("undefined"!==typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}var o=a("06c5");a("d9e2");function s(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function r(t){return i(t)||c(t)||Object(o["a"])(t)||s()}},"4d1f":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("el-card",[a("div",[a("menuheader",[t._v("我的收藏")]),a("div",{staticClass:"list"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.dataset,"header-cell-style":{background:"#fcfcfc",color:"#b8babd"}},on:{"selection-change":t.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection"}}),a("el-table-column",{attrs:{"header-align":"left",align:"left",prop:"name",label:"基本信息"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("div",{staticClass:"information_wrapper"},[a("div",{staticClass:"portraitImg"},[a("img",{attrs:{src:e.row.photo_img_src}})]),a("div",{staticClass:"information_list"},[a("div",{staticClass:"fn_bar"},[a("a",{staticClass:"name a-link",attrs:{href:e.row.resume_link_url_web,target:"_blank"}},[t._v(t._s(e.row.fullname))]),e.row.high_quality>0?a("div",{staticClass:"hq"}):t._e(),a("div",{staticClass:"clear"})]),a("p",{staticClass:"information"},[t._v(t._s(e.row.age)+"岁 | "+t._s(e.row.education_text)+" | "+t._s(e.row.experience_text)+" | "+t._s(e.row.sex_text)+" ")])])])]}}])}),a("el-table-column",{attrs:{width:"340px","header-align":"left",label:"求职意向"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("p",{staticClass:"intention_post"},[t._v("想找 "),a("span",[t._v(t._s(e.row.intention_jobs))]),t._v(" 工作")]),a("p",{staticClass:"intention_local"},[t._v("想在 "),a("span",[t._v(t._s(e.row.intention_district))]),t._v(" 工作")])]}}])}),a("el-table-column",{attrs:{"header-align":"center",align:"center",label:"收藏时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("div",{staticClass:"time"},[t._v(" "+t._s(t._f("timeFilter")(e.row.addtime))+" ")])]}}])}),a("el-table-column",{attrs:{"header-align":"center",align:"center",width:"170px",label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"mini"},on:{click:function(a){return t.handlerCancel(e.row)}}},[t._v("取消收藏")])]}}])})],1),a("div",{staticClass:"del_btn"},[a("el-button",{attrs:{type:"mini"},on:{click:t.handlerCancelBatch}},[t._v("取消收藏")])],1),a("pagination",{attrs:{total:t.total,"current-page":t.params.page,"page-size":t.params.pagesize},on:{handleCurrentChange:t.doSearch}})],1)],1)])},i=[],c=a("2909"),o=(a("d3b7"),a("159b"),a("ed08")),s=a("751a"),r=a("d722"),l=a("9dc2"),d={components:{menuheader:l["a"]},filters:{timeFilter:function(t){return Object(o["c"])(t,"{y}-{m}-{d} {h}:{i}",!0)}},data:function(){return{listLoading:!1,dataset:[],total:0,params:{page:1,pagesize:10},multipleSelection:[]}},created:function(){this.fetchData(!0)},methods:{fetchData:function(t){var e=this;this.listLoading=!0,s["a"].get(r["a"].company_fav_list,this.params).then((function(a){e.dataset=Object(c["a"])(a.data.items),e.listLoading=!1,!0===t&&e.fetchTotal()})).catch((function(){}))},fetchTotal:function(){var t=this;s["a"].get(r["a"].company_fav_list_total,{}).then((function(e){t.total=e.data})).catch((function(){}))},doSearch:function(t){this.params.page=t,this.fetchData()},handleSelectionChange:function(t){this.multipleSelection=t},handlerCancel:function(t){var e=this;this.$confirm("确定取消收藏该简历吗？","系统提示",{type:"warning"}).then((function(){s["a"].post(r["a"].company_fav_cancel,{id:t.id}).then((function(){e.$message({type:"success",message:"取消收藏成功"}),e.fetchData(!0)})).catch((function(){}))})).catch((function(){}))},handlerCancelBatch:function(){var t=this;if(0==this.multipleSelection.length)return this.$message.error("请选择简历"),!1;var e=[];this.multipleSelection.forEach((function(t){e.push(t.id)})),this.$confirm("确定取消收藏选中的简历吗？","系统提示",{type:"warning"}).then((function(){s["a"].post(r["a"].company_fav_cancel_batch,{id:e}).then((function(){t.$message({type:"success",message:"取消收藏成功"}),t.fetchData(!0)})).catch((function(){}))})).catch((function(){}))}}},u=d,f=(a("f01b"),a("2877")),h=Object(f["a"])(u,n,i,!1,null,"c4e7a93a",null);e["default"]=h.exports},"665c":function(t,e,a){},"91d8":function(t,e,a){"use strict";a("f5d7")},"9dc2":function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"browsing_wrapper"},[a("p",[t._t("default")],2),t.showWeChatQrcode?a("div",{staticClass:"follow_box",on:{click:t.popupWechatQrcodeWindow}},[a("span",{staticClass:"follow_box_left"}),a("span",{staticClass:"follow_box_right"},[t._v("扫码绑定,随时查收投递信息")])]):t._e(),a("WeChatQrcode",{ref:"weChatQrcodeRef",on:{closeWeChatQrcode:t.closeWeChatQrcode}})],1)},i=[],c=a("751a"),o=a("d722"),s=a("b0b5"),r={components:{WeChatQrcode:s["a"]},data:function(){return{showWeChatQrcode:!1}},mounted:function(){var t=this;c["a"].post(o["a"].member_account,{}).then((function(e){0===parseInt(e.data.bind_weixin)&&(t.showWeChatQrcode=!0)})).catch((function(){}))},methods:{popupWechatQrcodeWindow:function(t){this.$refs.weChatQrcodeRef.handleOpen(t,"扫码绑定，随时随地接收简历信息")},closeWeChatQrcode:function(){this.showWeChatQrcode=!1}}},l=r,d=(a("91d8"),a("2877")),u=Object(d["a"])(l,n,i,!1,null,"4cdcbe84",null);e["a"]=u.exports},f01b:function(t,e,a){"use strict";a("665c")},f5d7:function(t,e,a){}}]);