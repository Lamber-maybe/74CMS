(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-25237762"],{2909:function(t,a,e){"use strict";e.d(a,"a",(function(){return s}));var r=e("6b75");function n(t){if(Array.isArray(t))return Object(r["a"])(t)}e("a4d3"),e("e01a"),e("d28b"),e("a630"),e("d3b7"),e("3ca3"),e("ddb0");function i(t){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}var o=e("06c5");function l(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function s(t){return n(t)||i(t)||Object(o["a"])(t)||l()}},4383:function(t,a,e){"use strict";var r=e("8174"),n=e.n(r);n.a},"504f":function(t,a,e){"use strict";e.r(a);var r=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",[e("el-table",{staticStyle:{width:"100%"},attrs:{"header-cell-style":{background:"#fcfcfc",color:"#909399",fontSize:"14px",fontWeight:"normal"},data:t.dataset}},[e("el-table-column",{attrs:{prop:"title",label:"招聘会内容",width:"650"},scopedSlots:t._u([{key:"default",fn:function(a){return[e("div",{staticClass:"jobFair_con"},[e("a",{staticClass:"title",attrs:{href:a.row.jobfair_url,target:"_blank"}},[t._v(t._s(a.row.title))])])]}}])}),e("el-table-column",{attrs:{align:"center",label:"举办时间"},scopedSlots:t._u([{key:"default",fn:function(a){return[t._v(" "+t._s(t._f("timeFilter")(a.row.starttime))+" 至 "+t._s(t._f("timeFilter")(a.row.endtime))+" ")]}}])}),e("el-table-column",{attrs:{align:"center",label:"审核状态"},scopedSlots:t._u([{key:"default",fn:function(a){return[0===a.row.audit?e("el-tag",{attrs:{size:"small",type:"info"}},[t._v("未审核")]):1===a.row.audit?e("el-tag",{attrs:{size:"small",type:"success"}},[t._v("审核通过")]):2===a.row.audit?e("el-tag",{attrs:{size:"small",type:"warning"}},[t._v("等待审核")]):e("el-tag",{attrs:{size:"small",type:"danger"}},[t._v("审核未通过")])]}}])}),e("el-table-column",{attrs:{align:"center",label:"操作",width:"120"},scopedSlots:t._u([{key:"default",fn:function(a){return[e("el-button",{attrs:{type:"primary",size:"mini"}},[e("a",{staticClass:"jobfairUrl",attrs:{href:a.row.jobfair_url,target:"_blank"}},[t._v("查看详情")])])]}}])})],1),e("pagination",{attrs:{total:t.total,"current-page":t.params.page,"page-size":t.params.pagesize},on:{handleCurrentChange:t.doSearch}})],1)},n=[],i=e("2909"),o=e("ed08"),l=e("751a"),s=e("d722"),c={filters:{timeFilter:function(t){return Object(o["f"])(t,"{y}-{m}-{d}")}},data:function(){return{listLoading:!1,dataset:[],total:0,params:{page:1,pagesize:10}}},created:function(){this.fetchData()},methods:{doSearch:function(t){this.params.page=t,this.fetchData()},fetchData:function(){var t=this;l["a"].get(s["a"].company_jobfairol_reserve,this.params).then((function(a){t.dataset=Object(i["a"])(a.data.items),t.listLoading=!1,t.total=a.data.total})).catch((function(){}))}}},u=c,f=(e("4383"),e("2877")),d=Object(f["a"])(u,r,n,!1,null,"4bd43812",null);a["default"]=d.exports},8174:function(t,a,e){}}]);