(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3e50671e"],{"0abf":function(t,e,n){},"17b89":function(t,e,n){t.exports=n.p+"static/img/payment_icon_3.5c3f6c6d.png"},"27fb":function(t,e,n){"use strict";var i=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"payment-box"},[i("div",{staticClass:"item",class:["alipay"==t.payment?"border":""],on:{click:function(e){return t.choosePayment("alipay")}}},[i("img",{attrs:{src:n("a0f1")}}),t._v("支付宝支付"),"alipay"==t.payment?i("i",{staticClass:"el-icon-check"}):t._e()]),i("div",{staticClass:"item",class:["wxpay"==t.payment?"border":""],on:{click:function(e){return t.choosePayment("wxpay")}}},[i("img",{attrs:{src:n("f479")}}),t._v("微信支付 "),"wxpay"==t.payment?i("i",{staticClass:"el-icon-check"}):t._e()])])},a=[],s={name:"Payment",data:function(){return{payment:"alipay"}},created:function(){},methods:{choosePayment:function(t){this.payment=t,this.$emit("choosePayment",this.payment)}}},c=s,o=(n("ad20"),n("2877")),r=Object(o["a"])(c,i,a,!1,null,"398e9ee3",null);e["a"]=r.exports},"311f":function(t,e,n){t.exports=n.p+"static/img/payment_icon_1.63ee9a7e.png"},"3e9f":function(t,e,n){"use strict";n.r(e);var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("OrderDetail")},a=[],s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("el-card",[i("div",{staticClass:"payment_wrapper"},[i("div",{staticClass:"payment_title"},[i("h3",[t._v(t._s(t.info.service_name))]),i("span",[t._v(t._s(t.shortDesc))])]),i("div",{staticClass:"payment_img"},[0==t.info.status?i("img",{attrs:{src:n("311f")}}):t._e(),1==t.info.status?i("img",{attrs:{src:n("5105")}}):t._e(),2==t.info.status?i("img",{attrs:{src:n("17b89")}}):t._e()])])]),i("el-card",[i("div",[i("div",{staticClass:"payment_con"},[i("div",{staticClass:"con_title"},[t._v("订单内容")]),i("ul",{staticClass:"con_list"},[void 0!==t.info.extra.jobname?i("li",{staticClass:"con_width"},[i("span",[t._v("推广职位：")]),t._v(t._s(t.info.extra.jobname)+" ")]):t._e(),void 0!==t.info.extra.tag_text?i("li",{staticClass:"con_width"},[i("span",[t._v("使用标签：")]),t._v(t._s(t.info.extra.tag_text)+" ")]):t._e(),i("li",[i("span",[t._v("服务内容：")]),t._v(t._s(t.info.service_type_text))]),i("li",[i("span",[t._v("服务金额：")]),t._v(t._s(t.info.service_amount)+" 元")]),void 0!==t.info.extra.preferential_info?i("li",[i("span",[t._v("限时特惠：")]),t._v(t._s(t.info.extra.preferential_info.amount)+" 元 "),i("span",[t._v("("+t._s(t._f("dateFilter")(t.info.extra.preferential_info.starttime))+"至"+t._s(t._f("dateFilter")(t.info.extra.preferential_info.endtime))+")")])]):t._e(),void 0!==t.info.extra.coupon_info?i("li",[i("span",[t._v("优惠券：")]),t._v(t._s(t.info.extra.coupon_info.name)+" ")]):t._e(),void 0!==t.info.extra.coupon_info?i("li",[i("span",[t._v("优惠券折扣：")]),t._v("-"+t._s(t.info.extra.coupon_info.face_value)+" 元 ")]):t._e(),void 0!==t.info.extra.service_added_discount&&t.info.extra.service_added_discount>0?i("li",[i("span",[t._v("专享折扣：")]),t._v("增值服务"+t._s(t.info.extra.service_added_discount)+" 折 ")]):t._e(),void 0!==t.info.extra.service_added_discount&&t.info.extra.service_added_discount>0?i("li",[i("span",[t._v("折扣金额：")]),t._v("-"+t._s(t.info.service_amount_after_discount)+" 元 ")]):t._e(),t.info.deduct_points>0?i("li",[i("span",[t._v(t._s(t.$store.state.config.points_byname)+"抵扣：")]),t._v("-"+t._s(t.info.deduct_amount)+" 元 ")]):t._e(),"points"==t.info.service_type?i("li",[i("span",[t._v("充值"+t._s(t.$store.state.config.points_byname)+"：")]),t._v(t._s(t.info.extra.add_points)+" "+t._s(t.$store.state.config.points_byname)+" ")]):t._e(),i("li",[i("span",[t._v("应付金额：")]),i("span",{staticClass:"con_font_color"},[t._v(t._s(t.info.amount))]),t._v(" 元 ")])]),i("div",{staticClass:"con_title"},[t._v("订单信息")]),i("ul",{staticClass:"con_list"},[i("li",[i("span",[t._v("订单编号：")]),t._v(t._s(t.info.oid))]),i("li",[i("span",[t._v("创建时间：")]),t._v(t._s(t._f("timeFilter")(t.info.addtime)))]),0==t.info.status?i("li",[i("span",[t._v("付款时间：")]),t._v("待付款")]):t._e(),1==t.info.status?i("li",[i("span",[t._v("付款时间：")]),t._v(t._s(t._f("timeFilter")(t.info.paytime))+" ")]):t._e(),i("li",[i("span",[t._v("支付渠道：")]),i("span",[t._v(t._s(""==t.info.payment_text?"无":t.info.payment_text))])]),0==t.info.status?i("li",{staticClass:"payment_type"},[i("span",[t._v("支付方式：")]),i("Payment",{on:{choosePayment:t.choosePayment}})],1):t._e(),i("li",{staticClass:"payment_btn"},[0==t.info.status?i("el-button",{attrs:{type:"primary"},on:{click:t.handlerSubmit}},[t._v("继续支付")]):t._e(),0==t.info.status?i("el-button",{on:{click:t.handlerCancel}},[t._v("取消订单")]):t._e(),2==t.info.status?i("el-button",{staticClass:"single",on:{click:t.handlerDel}},[t._v("删除订单")]):t._e()],1)])])])]),i("PaySubmit",{ref:"paySubmit",attrs:{showWaiting:!0,payment:t.payment,successUrl:"/company/service/order",failUrl:"/company/service/order"}})],1)},c=[],o=n("5530"),r=n("a445"),l=n("27fb"),p=n("ed08"),u=n("751a"),f=n("d722"),m={name:"CompanyOrderDetail",components:{PaySubmit:r["a"],Payment:l["a"]},filters:{timeFilter:function(t){return Object(p["e"])(t,"{y}-{m}-{d} {h}:{i}:{s}")},dateFilter:function(t){return Object(p["e"])(t,"{m}月{d}日")}},data:function(){return{order_id:0,info:{extra:{}},payment:"alipay",shortDesc:"",showWaitingPay:!1}},created:function(){this.order_id=this.$route.params.id,this.fetchData()},methods:{choosePayment:function(t){this.payment=t},fetchData:function(){var t=this;u["a"].get(f["a"].company_orderdetail,{id:this.order_id}).then((function(e){t.info=Object(o["a"])({},e.data),t.shortDesc=t.filterDesc(t.info)})).catch((function(){}))},filterDesc:function(t){var e="";switch(t.service_type){case"points":e="做任务免费得"+this.$store.state.config.points_byname+"，小"+this.$store.state.config.points_byname+"大用处";break;case"setmeal":e="套餐服务期："+t.extra.days+"天";break;case"jobstick":e="锁定好位，提高职位曝光量";break;case"emergency":e="急聘职位优先推荐，专属标识";break;case"resume_package":e="超值简历下载叠加包";break;case"refresh_job_package":e="自动抢占招聘先机";break;case"single_job_refresh":e="单次刷新职位-快捷支付";break;case"single_resume_down":e="单次下载简历-快捷支付";break}return e},handlerSubmit:function(){this.$refs.paySubmit.handlerSubmit(f["a"].company_repay,{id:this.order_id,payment:this.payment})},handlerCancel:function(){var t=this;this.$confirm("确定取消该订单吗？","系统提示",{type:"warning"}).then((function(){u["a"].post(f["a"].company_order_cancel,{id:t.order_id}).then((function(e){t.$message({type:"success",message:e.message}),t.$router.push("/company/service/order")})).catch((function(){}))})).catch((function(){}))},handlerDel:function(){var t=this;this.$confirm("确定删除该订单吗？","系统提示",{type:"warning"}).then((function(){u["a"].post(f["a"].company_order_del,{id:t.order_id}).then((function(e){t.$message({type:"success",message:e.message}),t.$router.push("/company/service/order")})).catch((function(){}))})).catch((function(){}))}}},d=m,h=(n("c6d4"),n("2877")),y=Object(h["a"])(d,s,c,!1,null,null,null),_=y.exports,v={name:"CompanyOrderView",components:{OrderDetail:_}},b=v,A=Object(h["a"])(b,i,a,!1,null,null,null);e["default"]=A.exports},5105:function(t,e,n){t.exports=n.p+"static/img/payment_icon_2.b5d21e95.png"},5530:function(t,e,n){"use strict";n.d(e,"a",(function(){return s}));n("a4d3"),n("4de4"),n("4160"),n("e439"),n("dbb4"),n("b64b"),n("159b");function i(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function a(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(t);e&&(i=i.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,i)}return n}function s(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?a(Object(n),!0).forEach((function(e){i(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):a(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}},"8c50":function(t,e,n){},a0f1:function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAATCAIAAAAS8MqlAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiZDFkODdlYS0wOWRmLTk0NGItYmY4ZS03OTVlMTcwMmM4NmEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OUY5RTdFNzhFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OUY5RTdFNzdFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjEgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ZWFkOTZhMGMtMTkxMi1hNDQ1LThhMjMtMDU5OTkxNTk3MmVhIiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6OTljN2ZlMzYtNzg4NC1iOTQxLThiZjMtZDcyOTU0MjE1NjcyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+2xlCFwAAAk9JREFUeNpi/P//PwMSOPr696bHP4+8/v30218gV5qL2UaU1V+W3UqUFVkZI1zbnc9/K85/Of76NwM2YCnK2mHIo8LLjKINqDrh2Kcvf/4z4AY8LIwLrPgswdaCtAHt8dn/4fNvFD3iHExA8uWPf8iCvKyMWxwFgHYyAdUWn/2CpgcINjkKzDDnRRMEKgMqBiplOfLq95m3UP/IcjHHKnFA2PysjMzczFU63BDuxic/r374A2QAFQO1sGx+8hNu2ONvf9uufIWwA2TZnyJxkQFQC6PVjncPvvyFC0UqcBgIsQAZfKyM//4zcLMwmgmzzr7zvf/6N7gaeW5mFkj8wMHKhz82PGb8/hfq1SJNLldJtmOosfL8+z8WdibG3/9AiqQ4mer0uIEByMfGxMvCeOH9770vfofKs9/49PfUG/TIZJHgZAJGAJAVocCRd/rLL7ARQBcCuTU6XELsTBfe/3GUYDv48hfMBQxSXExM5iLQVMPKxADRAwSffv+/+ekPLyvT9me/RNmZFlvznfYSAoYqJJWYCLEwHnr5K+LwRyCnRpf73pe/u579EmRjTFTmDFdg7732bdqt70DjwhU4ctQ5gdEDVHb27R8uFgZgcP0POPARGBvMjAxxShxe0uyMDAxAhy27/+MeUgizMDGEynHkaXDKcTNDE9ftz399MRIXVgB0yCE3QaCHQQlPlZd5viUfMKXi1wNMkHMt+YB6SMg4psKsvcY8ymgZBw4Ov/oNTDvAVPf0+18mBkZJTiYzEZZAWQ47cVZGrNmUJAAQYAC19gZNyJyvPAAAAABJRU5ErkJggg=="},a445:function(t,e,n){"use strict";var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("el-dialog",{attrs:{title:"支付提醒",visible:t.showWaitingPay,width:"400px","append-to-body":!0},on:{"update:visible":function(e){t.showWaitingPay=e}}},[n("span",{staticClass:"payment_text"},[t._v("请在新打开的页面上完成支付。支付完成后，请根据您支付的情况点击下面按钮。")]),n("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[n("el-button",{attrs:{type:"primary"},on:{click:t.handlerPaySuccess}},[t._v("支付成功")]),n("el-button",{on:{click:t.handlerPayFail}},[t._v("支付失败")])],1)])],1)},a=[],s=n("751a"),c={name:"PaySubmit",props:["payment","showWaiting","successUrl","failUrl","customLocation"],data:function(){return{showWaitingPay:!1}},created:function(){},methods:{handlerSubmit:function(t,e,n){var i=this;if("alipay"==e.payment&&!this.$store.state.config.account_alipay_appid)return this.$alert("暂不支持支付宝付款，请选择其他付款方式","提示",{confirmButtonText:"确定",type:"warning",callback:function(){}}),!1;if("wxpay"==e.payment&&!this.$store.state.config.payment_wechat_appid)return this.$alert("暂不支持微信付款，请选择其他付款方式","提示",{confirmButtonText:"确定",type:"warning",callback:function(){}}),!1;var a=this;s["a"].post(t,e).then((function(t){if(1==t.data.pay_status)return i.$message({type:"success",message:"支付成功"}),"function"===typeof n?(n(),!1):(setTimeout((function(){a.$route.path===a.successUrl?location.reload():a.$router.push(a.successUrl)}),1500),!1);i.handlerPay(t.data,n)})).catch((function(){setTimeout((function(){a.$route.path===a.successUrl?location.reload():a.$router.push(a.successUrl)}),1500)}))},handlerPay:function(t,e){if(!0===this.showWaiting&&(this.showWaitingPay=!0),"wxpay"==this.payment){var n=this.$router.resolve({path:"/wxpay",query:{parameter:t.parameter,oid:t.order_oid,amount:t.order_amount,custom_location:void 0===this.customLocation?0:1,success_url:this.successUrl}}),i=n.href;window.open(i)}else window.open(t.parameter);if("function"===typeof e)return e(),!1},handlerPaySuccess:function(){var t=this;t.showWaitingPay=!1,t.$route.path===t.successUrl?location.reload():t.$router.push(t.successUrl)},handlerPayFail:function(){var t=this;t.showWaitingPay=!1,t.$route.path===t.failUrl?location.reload():t.$router.push(t.failUrl)}}},o=c,r=n("2877"),l=Object(r["a"])(o,i,a,!1,null,"1d301a43",null);e["a"]=l.exports},ad20:function(t,e,n){"use strict";var i=n("0abf"),a=n.n(i);a.a},c6d4:function(t,e,n){"use strict";var i=n("8c50"),a=n.n(i);a.a},dbb4:function(t,e,n){var i=n("23e7"),a=n("83ab"),s=n("56ef"),c=n("fc6a"),o=n("06cf"),r=n("8418");i({target:"Object",stat:!0,sham:!a},{getOwnPropertyDescriptors:function(t){var e,n,i=c(t),a=o.f,l=s(i),p={},u=0;while(l.length>u)n=a(i,e=l[u++]),void 0!==n&&r(p,e,n);return p}})},e439:function(t,e,n){var i=n("23e7"),a=n("d039"),s=n("fc6a"),c=n("06cf").f,o=n("83ab"),r=a((function(){c(1)})),l=!o||r;i({target:"Object",stat:!0,forced:l,sham:!o},{getOwnPropertyDescriptor:function(t,e){return c(s(t),e)}})},f479:function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAATCAIAAAAS8MqlAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiZDFkODdlYS0wOWRmLTk0NGItYmY4ZS03OTVlMTcwMmM4NmEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QTIzMDRDRTNFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QTIzMDRDRTJFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjEgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ZWFkOTZhMGMtMTkxMi1hNDQ1LThhMjMtMDU5OTkxNTk3MmVhIiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6OTljN2ZlMzYtNzg4NC1iOTQxLThiZjMtZDcyOTU0MjE1NjcyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+Ox4V7AAAAhpJREFUeNpi/P//PwMSOP/mzP6nu8+/PvPy+wtmRiZhDlEdIT0XGU9jMTNGBka4Mka4tkdfHky42HnhzVkGbEBbSK/UsEaORwFF28U356pPFn37840BN+Bi4W6z6NMTNoRqA9qTfSjp6+8vDIQANyvPHIel4lySLP8Z/nedbyZGjwq/WrJmJlAPkM1y7vXpa+8u49cgwy2bqJnhIO3y/c+37vMtQAbLgad7EOHDyPT//z9kDaKcYrFqyZ7yfsyMzMBABrrr5bfn//7/ZUEOuhaznsW35t54fxXI5mcTiFSN81cMZWdm//n3x7Rr/RvurQb6CCh16d0FFmD8wLU9+Hx3os3MVXeWsjCx+MgHAgMAKHj9/ZX2cw1PvjyCK3vz/TWj2ybr3/9+QfiyPPILnVfDpf/8+73w5pwVtxf9/f8X2eUsTKxMIhwicP7jLw+vvb8CYd/9eDvzUMLSW/PR9EA8zKQrbIAstOPR5n///y2/vQioB6gTa8ACkxvjqZfHy4/nwYWYGJn42Pg//HyPJz46LCcymYiZawnqwIWAVuHXoymoYypmwQRM16WGtZBAIwjYmTnKDOuAWpiAHHleRWCMcbFw4dfDy8rbYTFBnheUCZggQvoiRhYStnj0AENuqt18oDJoHCBC//MDYHgEKoV//Pn+yruLL7+/ZGNiE+EQ1RcxtJdyAfoHJergLGYmlj7r6ZDsRBAABBgA9pjXch+ZaU8AAAAASUVORK5CYII="}}]);