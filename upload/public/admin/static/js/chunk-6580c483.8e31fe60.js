(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-6580c483"],{"0910":function(t,e,a){"use strict";a("80f6")},"52b5":function(t,e,a){"use strict";a.d(e,"a",(function(){return o}));var n=a("b775"),s=a("d722");function o(t){return Object(n["a"])({url:s["a"].getClassify,method:"get",params:t})}},"80f6":function(t,e,a){},dcb2:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[t._v("订单管理")])]),a("div",{staticClass:"list-search"},[a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限支付状态"},on:{change:t.funSearch},model:{value:t.status,callback:function(e){t.status=e},expression:"status"}},[a("el-option",{attrs:{label:"不限支付状态",value:""}}),t._l(t.options_status,(function(t,e){return a("el-option",{key:e,attrs:{label:t,value:e}})}))],2),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限支付方式"},on:{change:t.funSearch},model:{value:t.payment,callback:function(e){t.payment=e},expression:"payment"}},[a("el-option",{attrs:{label:"不限支付方式",value:""}}),t._l(t.options_payment,(function(t,e){return a("el-option",{key:e,attrs:{label:t.name,value:t.id}})}))],2),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限订单类型"},on:{change:t.funSearch},model:{value:t.service_type,callback:function(e){t.service_type=e},expression:"service_type"}},[a("el-option",{attrs:{label:"不限订单类型",value:""}}),t._l(t.options_service_type,(function(t,e){return a("el-option",{key:e,attrs:{label:t.name,value:t.id}})}))],2),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限下单时间"},on:{change:t.funSearch},model:{value:t.add_settr,callback:function(e){t.add_settr=e},expression:"add_settr"}},[a("el-option",{attrs:{label:"不限下单时间",value:""}}),a("el-option",{attrs:{label:"3天内",value:"3"}}),a("el-option",{attrs:{label:"7天内",value:"7"}}),a("el-option",{attrs:{label:"15天内",value:"15"}}),a("el-option",{attrs:{label:"30天内",value:"30"}})],1),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限付款时间"},on:{change:t.funSearch},model:{value:t.pay_settr,callback:function(e){t.pay_settr=e},expression:"pay_settr"}},[a("el-option",{attrs:{label:"不限付款时间",value:""}}),a("el-option",{attrs:{label:"3天内",value:"3"}}),a("el-option",{attrs:{label:"7天内",value:"7"}}),a("el-option",{attrs:{label:"15天内",value:"15"}}),a("el-option",{attrs:{label:"30天内",value:"30"}})],1),a("el-select",{staticClass:"list-options",attrs:{placeholder:"按下单时间排序"},on:{change:t.funSearch},model:{value:t.sort,callback:function(e){t.sort=e},expression:"sort"}},[a("el-option",{attrs:{label:"按下单时间排序",value:""}}),a("el-option",{attrs:{label:"按付款时间排序",value:"1"}}),a("el-option",{attrs:{label:"按订单状态排序",value:"2"}})],1),a("el-input",{staticClass:"input-with-select",attrs:{placeholder:"请输入搜索内容"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword.apply(null,arguments)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[a("el-select",{staticClass:"input-sel",attrs:{slot:"prepend",placeholder:"请选择"},slot:"prepend",model:{value:t.key_type,callback:function(e){t.key_type=e},expression:"key_type"}},[a("el-option",{attrs:{label:"会员UID",value:"1"}}),a("el-option",{attrs:{label:"会员手机号",value:"2"}}),a("el-option",{attrs:{label:"订单号",value:"3"}})],1),a("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1),a("div",{staticClass:"spaceline"}),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[a("el-table-column",{attrs:{label:"会员手机号",prop:"member_mobile","min-width":"120"}}),a("el-table-column",{attrs:{label:"服务内容",prop:"service_name","min-width":"120"}}),a("el-table-column",{attrs:{label:"订单金额","min-width":"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("￥"+t._s(e.row.amount))]}}])}),a("el-table-column",{attrs:{label:"支付方式","min-width":"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[""==e.row.payment?a("span",[t._v(" - ")]):a("span",t._l(t.options_payment,(function(n,s){return a("span",{key:s},[n.id==e.row.payment?a("span",[t._v(t._s(n.name))]):t._e()])})),0)]}}])}),a("el-table-column",{attrs:{label:"订单状态","min-width":"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-tag",{attrs:{type:t._f("statusFilter")(e.row.status)}},[t._v(" "+t._s(t.options_status[e.row.status])+" ")])]}}])}),a("el-table-column",{attrs:{align:"center",label:"下单时间","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("i",{staticClass:"el-icon-time"}),a("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),a("el-table-column",{attrs:{align:"center",label:"付款时间","min-width":"160"},scopedSlots:t._u([{key:"default",fn:function(e){return[e.row.paytime>0?a("span",[a("i",{staticClass:"el-icon-time"}),t._v(" "+t._s(t._f("timeFilter")(e.row.paytime))+" ")]):a("span",[t._v("-")])]}}])}),a("el-table-column",{attrs:{fixed:"right",label:"操作",width:"340"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return t.fun_detail(e.row)}}},[t._v(" 详情 ")]),0==e.row.status?a("el-button",{attrs:{size:"small",type:"warning"},on:{click:function(a){return t.funConfirm(e.$index,e.row)}}},[t._v(" 确认付款 ")]):t._e(),0==e.row.status?a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return t.funCancel(e.$index,e.row)}}},[t._v(" 关闭 ")]):t._e(),a("el-button",{attrs:{size:"small"},on:{click:function(a){return t.funCopy(e.row.oid)}}},[t._v(" 复制单号 ")])]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-row",{attrs:{gutter:20}},[a("el-col",{attrs:{span:8}}),a("el-col",{staticStyle:{"text-align":"right"},attrs:{offset:8,span:16}},[a("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1),a("el-dialog",{attrs:{title:"订单详情",visible:t.dialogFormVisible,width:"35%"},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[a("el-row",[a("el-col",{attrs:{span:12}},[t._v("服务名称："+t._s(t.dialogContent.service_name))]),a("el-col",{attrs:{span:12}},[t._v("订单号："+t._s(t.dialogContent.oid))])],1),a("el-row",[a("el-col",{attrs:{span:12}},[t._v("服务金额："+t._s(t.dialogContent.service_amount))]),a("el-col",{attrs:{span:12}},[t._v("下单时间："+t._s(t.dialogContent.addtime))])],1),a("el-row",[a("el-col",{attrs:{span:12}},[t._v(" 折后金额："+t._s(t.dialogContent.service_amount_after_discount)+" ")]),a("el-col",{attrs:{span:12}},[t._v("支付时间："+t._s(t.dialogContent.paytime))])],1),a("el-row",[a("el-col",{attrs:{span:12}},[t._v("积分抵扣："+t._s(t.dialogContent.deduct_amount))]),a("el-col",{attrs:{span:12}},[t._v("支付状态："+t._s(t.dialogContent.status))])],1),a("el-row",[a("el-col",{attrs:{span:12}},[t._v(" 实付金额： "),a("span",{staticClass:"font_warning",staticStyle:{"font-weight":"bold"}},[t._v(" "+t._s(t.dialogContent.amount)+" ")])]),a("el-col",{attrs:{span:12}},[t._v("支付方式："+t._s(t.dialogContent.payment))])],1),""!=t.dialogContent.amount_detail?a("el-row",[a("el-col",{attrs:{span:24}},[t._v("支付详情："+t._s(t.dialogContent.amount_detail))])],1):t._e()],1)],1)},s=[],o=a("b85c"),l=a("2909"),i=(a("d3b7"),a("159b"),a("b0c0"),a("4e82"),a("52b5")),r=a("f8b7"),c=a("ed08"),u={filters:{timeFilter:function(t){return Object(c["b"])(t,"{y}-{m}-{d} {h}:{i}")},statusFilter:function(t){switch(t){case 0:return"warning";case 1:return"success";case 2:return"info";default:return"info"}}},data:function(){return{dialogFormVisible:!1,dialogContent:{oid:"",service_name:"",service_amount:"",service_amount_after_discount:"",amount:"",deduct_amount:"",deduct_points:"",payment:"",addtime:"",paytime:"",status:"",amount_detail:""},status:"",payment:"",service_type:"",add_settr:"",pay_settr:"",options_status:[],options_payment:[],options_service_type:[],list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,key_type:"1",keyword:"",sort:""}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0,Object(i["a"])({type:"orderStatus,orderPayment,orderServiceTypePersonal"}).then((function(e){var a=Object(l["a"])(e.data.orderStatus);a.forEach((function(e){t.options_status[e.id]=e.name})),t.options_payment=Object(l["a"])(e.data.orderPayment),t.options_service_type=Object(l["a"])(e.data.orderServiceTypePersonal);var n={utype:2,status:t.status,payment:t.payment,service_type:t.service_type,add_settr:t.add_settr,pay_settr:t.pay_settr,key_type:t.key_type,keyword:t.keyword,sort:t.sort,page:t.currentPage,pagesize:t.pagesize};return Object(r["c"])(n)})).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize})).catch((function(){}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearch:function(){this.fetchData()},funSearchKeyword:function(){this.currentPage=1,this.fetchData()},goto:function(t){this.$router.push(t)},fun_detail:function(t){if(this.dialogContent={oid:t.oid,service_name:t.service_name,service_amount:"￥"+t.service_amount,service_amount_after_discount:"￥"+t.service_amount_after_discount,amount:"￥"+t.amount,deduct_amount:"￥"+t.deduct_amount,deduct_points:t.deduct_points,addtime:Object(c["b"])(t.addtime,"{y}-{m}-{d} {h}:{i}"),paytime:0==t.paytime?"未支付":Object(c["b"])(t.paytime,"{y}-{m}-{d} {h}:{i}"),status:this.options_status[t.status],amount_detail:t.amount_detail},""==t.payment)this.dialogContent.payment="-";else{var e,a=Object(o["a"])(this.options_payment);try{for(a.s();!(e=a.n()).done;){var n=e.value;if(n.id==t.payment){this.dialogContent.payment=n.name;break}}}catch(s){a.e(s)}finally{a.f()}}this.dialogFormVisible=!0},funCancel:function(t,e){var a=this;a.$confirm("确定关闭该订单吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(r["a"])(t).then((function(t){return a.$message.success(t.message),a.fetchData(),!0}))})).catch((function(){}))},funConfirm:function(t,e){var a=this;this.$prompt("请输入备注","提示",{confirmButtonText:"确定",cancelButtonText:"取消",beforeClose:function(t,n,s){if("confirm"===t){var o=n.inputValue?n.inputValue:"";n.confirmButtonLoading=!0,n.confirmButtonText="执行中...";var l={id:e.id,note:o};Object(r["b"])(l).then((function(t){return n.confirmButtonLoading=!1,s(),a.$message.success(t.message),a.fetchData(),!0}))}else s()}})},funCopy:function(t){var e=document.createElement("input");e.value=t,document.body.appendChild(e),e.select(),document.execCommand("Copy"),e.className="oInput",e.style.display="none",this.$message({type:"success",message:"复制成功"})}}},d=u,p=(a("0910"),a("2877")),m=Object(p["a"])(d,n,s,!1,null,"681cc904",null);e["default"]=m.exports},f8b7:function(t,e,a){"use strict";a.d(e,"c",(function(){return o})),a.d(e,"b",(function(){return l})),a.d(e,"a",(function(){return i}));var n=a("b775"),s=a("d722");function o(t){return Object(n["a"])({url:s["a"].orderList,method:"get",params:t})}function l(t){return Object(n["a"])({url:s["a"].orderConfirm,method:"post",data:t})}function i(t){return Object(n["a"])({url:s["a"].orderCancel,method:"post",data:t})}}}]);