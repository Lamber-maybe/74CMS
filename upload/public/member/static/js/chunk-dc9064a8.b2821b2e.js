(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-dc9064a8"],{"0abf":function(t,e,a){},"27fb":function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"payment-box"},[i("div",{staticClass:"item",class:["alipay"==t.payment?"border":""],on:{click:function(e){return t.choosePayment("alipay")}}},[i("img",{attrs:{src:a("a0f1")}}),t._v("支付宝支付"),"alipay"==t.payment?i("i",{staticClass:"el-icon-check"}):t._e()]),i("div",{staticClass:"item",class:["wxpay"==t.payment?"border":""],on:{click:function(e){return t.choosePayment("wxpay")}}},[i("img",{attrs:{src:a("f479")}}),t._v("微信支付 "),"wxpay"==t.payment?i("i",{staticClass:"el-icon-check"}):t._e()])])},s=[],n={name:"Payment",data:function(){return{payment:"alipay"}},created:function(){},methods:{choosePayment:function(t){this.payment=t,this.$emit("choosePayment",this.payment)}}},c=n,o=(a("ad20"),a("2877")),r=Object(o["a"])(c,i,s,!1,null,"398e9ee3",null);e["a"]=r.exports},"65e9":function(t,e,a){"use strict";var i=a("b02a"),s=a.n(i);s.a},"846a":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("BuyIncrement",{attrs:{type:t.type}})},s=[],n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("el-card",["jobstick"==t.type?a("company-title",[t._v("职位置顶")]):t._e(),"emergency"==t.type?a("company-title",[t._v("职位紧急")]):t._e(),"job_refresh"==t.type?a("company-title",[t._v("智能刷新")]):t._e(),"resume_package"==t.type?a("company-title",[t._v("简历增值包")]):t._e(),a("div",{staticClass:"topping_warpper"},[a("ul",{staticClass:"topping_con"},["resume_package"!=t.type?a("li",[a("span",{staticClass:"margin"},[t._v("选择职位")]),a("el-select",{staticClass:"el_select",attrs:{placeholder:"请选择职位"},model:{value:t.submitData.jobid,callback:function(e){t.$set(t.submitData,"jobid",e)},expression:"submitData.jobid"}},t._l(t.joblist,(function(t,e){return a("el-option",{key:e,attrs:{label:t.jobname,value:t.id}})})),1)],1):t._e(),a("li",{staticClass:"service"},[a("span",{staticClass:"margin"},[t._v("选择服务")]),a("div",{staticClass:"service_list"},t._l(t.dataset,(function(e,i){return a("div",{key:i,class:[t.submitData.service_id==e.id?"border2":"","hoverbg"],on:{click:function(a){return t.changeItem(e)}}},[a("div",{staticClass:"item_left"},[a("p",[t._v(t._s(e.name))]),a("span",[t._v("低至 "+t._s(e.expense_avg)),1==e.enable_points_deduct?a("span",[t._v(" ，可使用"+t._s(t.$store.state.config.points_byname)+"抵扣")]):t._e()])]),a("div",{staticClass:"item_right"},[a("strong"),a("span",{staticClass:"priceColor"},[t._v("￥"+t._s(e.expense))])]),a("i",{directives:[{name:"show",rawName:"v-show",value:t.submitData.service_id==e.id,expression:"submitData.service_id==item.id"}],staticClass:"el-icon-check"})])})),0)]),"job_refresh"==t.type?a("li",[a("span",{staticClass:"margin"},[t._v("开始时间")]),a("el-date-picker",{staticClass:"el_select",attrs:{format:"yyyy-MM-dd HH:mm","value-format":"yyyy-MM-dd HH:mm",type:"datetime",placeholder:"请选择开始刷新时间"},model:{value:t.submitData.starttime,callback:function(e){t.$set(t.submitData,"starttime",e)},expression:"submitData.starttime"}})],1):t._e(),"job_refresh"==t.type?a("li",[a("span",{staticClass:"margin"},[t._v("刷新间隔")]),a("el-select",{staticClass:"el_select",model:{value:t.submitData.timerange,callback:function(e){t.$set(t.submitData,"timerange",e)},expression:"submitData.timerange"}},t._l(t.options_timerange,(function(t,e){return a("el-option",{key:e,attrs:{label:t.text,value:t.value}})})),1)],1):t._e(),t.mySetmeal.service_added_discount>0?a("li",[a("span",{staticClass:"margin"},[t._v("专享折扣")]),t._v(" 增值服务"+t._s(t.mySetmeal.service_added_discount)+"折 ")]):t._e(),1==t.enable_points_deduct?a("li",[a("span",{staticClass:"margin"},[t._v(t._s(t.$store.state.config.points_byname)+"抵扣")]),a("span",[a("el-checkbox",{on:{change:t.changeDeduct},model:{value:t.is_deduct,callback:function(e){t.is_deduct=e},expression:"is_deduct"}},[t._v("可使用"+t._s(t.enable_points_deduct_points)+t._s(t.$store.state.config.points_byname)+"抵用"+t._s(t.enable_points_deduct_expense)+"元")])],1)]):t._e(),a("li",{staticClass:"priceColor_con"},[a("span",{staticClass:"margin"},[t._v("应付金额")]),a("span",{staticClass:"priceColor"},[t._v(t._s(t.amount))]),t._v("元 ")]),t.amount>0?a("li",{staticClass:"payment_type"},[a("span",{staticClass:"margin"},[t._v("支付方式")]),a("Payment",{on:{choosePayment:t.choosePayment}})],1):t._e(),a("li",{staticClass:"btn"},[a("el-button",{attrs:{type:"primary"},on:{click:t.submit}},[t._v(t._s(0==t.amount?"立即兑换":"立即支付"))])],1)])]),a("PaySubmit",{ref:"paySubmit",attrs:{showWaiting:!0,payment:t.submitData.payment,successUrl:"/company/service/order",failUrl:"/company/service/order"}})],1)},c=[],o=(a("b680"),a("b85c")),r=a("a445"),l=a("27fb"),m=a("751a"),p=a("d722"),d={name:"CompanyBuyIncrement",props:["type"],components:{PaySubmit:r["a"],Payment:l["a"]},data:function(){return{dataset:[],old_amount:0,amount:0,is_deduct:!1,enable_points_deduct:0,enable_points_deduct_points:0,enable_points_deduct_expense:0,after_deduct_expense:0,joblist:[],submitData:{service_type:"",service_id:0,deduct_points:0,starttime:"",timerange:"",payment:"alipay",jobid:0},mySetmeal:{},options_timerange:[]}},created:function(){this.options_timerange=[];for(var t=1;t<=12;t++)this.options_timerange.push({text:t+"小时",value:3600*t});this.options_timerange.push({text:"24小时",value:86400}),this.options_timerange.push({text:"48小时",value:172800}),this.fetchSetmeal()},methods:{fetchSetmeal:function(){var t=this;m["a"].get(p["a"].member_setmeal,{}).then((function(e){t.mySetmeal=e.data.info,t.fetchData()})).catch((function(){}))},fetchData:function(){var t=this;m["a"].get(p["a"].company_servicelist,{type:this.type}).then((function(e){t.dataset=[];var a,i=e.data.items,s=Object(o["a"])(i);try{for(s.s();!(a=s.n()).done;){var n=a.value;void 0!==n.times?(n.expense_avg=n.expense/n.times,n.expense_avg=n.expense_avg.toFixed(2)+"元/次"):void 0!==n.days?(n.expense_avg=n.expense/n.days,n.expense_avg=n.expense_avg.toFixed(2)+"元/天"):(n.expense_avg=n.expense/n.download_resume_point,n.expense_avg=n.expense_avg.toFixed(2)+"元/点"),t.dataset.push(n)}}catch(c){s.e(c)}finally{s.f()}t.submitData.service_id=i[0].id,t.enable_points_deduct=i[0].enable_points_deduct,t.enable_points_deduct_points=i[0].enable_points_deduct_points,t.enable_points_deduct_expense=i[0].enable_points_deduct_expense,t.after_deduct_expense=i[0].after_deduct_expense,t.old_amount=i[0].expense,t.handlerFinalAmount(),t.joblist=e.data.joblist,void 0!==t.submitData.jobid&&t.submitData.jobid||(t.submitData.jobid=t.joblist[0].id)})).catch((function(){}))},choosePayment:function(t){this.submitData.payment=t},changeDeduct:function(){this.handlerFinalAmount()},handlerFinalAmount:function(){this.is_deduct?this.amount=this.after_deduct_expense:this.amount=this.old_amount},changeItem:function(t){this.enable_points_deduct=t.enable_points_deduct,this.enable_points_deduct_points=t.enable_points_deduct_points,this.enable_points_deduct_expense=t.enable_points_deduct_expense,this.after_deduct_expense=t.after_deduct_expense,this.old_amount=t.expense,this.handlerFinalAmount(),this.submitData.service_id=t.id},submit:function(){return this.submitData.deduct_points=this.is_deduct?this.enable_points_deduct_points:0,this.submitData.service_type="job_refresh"==this.type?"refresh_job_package":this.type,"refresh_job_package"==this.submitData.service_type&&""==this.submitData.starttime?(this.$message.error("请选择开始刷新时间"),!1):"refresh_job_package"==this.submitData.service_type&&""==this.submitData.timerange?(this.$message.error("请选择刷新时间间隔"),!1):(this.submitData.return_url=window.location.protocol+"//"+window.location.host+"/"+this.$store.state.config.member_dirname+"/company/service/order",void this.$refs.paySubmit.handlerSubmit(p["a"].company_pay,this.submitData))}}},u=d,h=(a("65e9"),a("2877")),b=Object(h["a"])(u,n,c,!1,null,"49ba5cd8",null),_=b.exports,y={components:{BuyIncrement:_},data:function(){return{type:""}},created:function(){this.type=void 0===this.$route.params.type?"jobstick":this.$route.params.type},methods:{}},v=y,A=Object(h["a"])(v,i,s,!1,null,"5ad5ebf8",null);e["default"]=A.exports},a0f1:function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAATCAIAAAAS8MqlAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiZDFkODdlYS0wOWRmLTk0NGItYmY4ZS03OTVlMTcwMmM4NmEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OUY5RTdFNzhFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OUY5RTdFNzdFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjEgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ZWFkOTZhMGMtMTkxMi1hNDQ1LThhMjMtMDU5OTkxNTk3MmVhIiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6OTljN2ZlMzYtNzg4NC1iOTQxLThiZjMtZDcyOTU0MjE1NjcyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+2xlCFwAAAk9JREFUeNpi/P//PwMSOPr696bHP4+8/v30218gV5qL2UaU1V+W3UqUFVkZI1zbnc9/K85/Of76NwM2YCnK2mHIo8LLjKINqDrh2Kcvf/4z4AY8LIwLrPgswdaCtAHt8dn/4fNvFD3iHExA8uWPf8iCvKyMWxwFgHYyAdUWn/2CpgcINjkKzDDnRRMEKgMqBiplOfLq95m3UP/IcjHHKnFA2PysjMzczFU63BDuxic/r374A2QAFQO1sGx+8hNu2ONvf9uufIWwA2TZnyJxkQFQC6PVjncPvvyFC0UqcBgIsQAZfKyM//4zcLMwmgmzzr7zvf/6N7gaeW5mFkj8wMHKhz82PGb8/hfq1SJNLldJtmOosfL8+z8WdibG3/9AiqQ4mer0uIEByMfGxMvCeOH9770vfofKs9/49PfUG/TIZJHgZAJGAJAVocCRd/rLL7ARQBcCuTU6XELsTBfe/3GUYDv48hfMBQxSXExM5iLQVMPKxADRAwSffv+/+ekPLyvT9me/RNmZFlvznfYSAoYqJJWYCLEwHnr5K+LwRyCnRpf73pe/u579EmRjTFTmDFdg7732bdqt70DjwhU4ctQ5gdEDVHb27R8uFgZgcP0POPARGBvMjAxxShxe0uyMDAxAhy27/+MeUgizMDGEynHkaXDKcTNDE9ftz399MRIXVgB0yCE3QaCHQQlPlZd5viUfMKXi1wNMkHMt+YB6SMg4psKsvcY8ymgZBw4Ov/oNTDvAVPf0+18mBkZJTiYzEZZAWQ47cVZGrNmUJAAQYAC19gZNyJyvPAAAAABJRU5ErkJggg=="},a445:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("el-dialog",{attrs:{title:"支付提醒",visible:t.showWaitingPay,width:"400px","append-to-body":!0},on:{"update:visible":function(e){t.showWaitingPay=e}}},[a("span",{staticClass:"payment_text"},[t._v("请在新打开的页面上完成支付。支付完成后，请根据您支付的情况点击下面按钮。")]),a("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{attrs:{type:"primary"},on:{click:t.handlerPaySuccess}},[t._v("支付成功")]),a("el-button",{on:{click:t.handlerPayFail}},[t._v("支付失败")])],1)])],1)},s=[],n=a("751a"),c={name:"PaySubmit",props:["payment","showWaiting","successUrl","failUrl"],data:function(){return{showWaitingPay:!1}},created:function(){},methods:{handlerSubmit:function(t,e,a){var i=this,s=this;n["a"].post(t,e).then((function(t){if(1==t.data.pay_status)return i.$message({type:"success",message:"支付成功"}),"function"===typeof a?(a(),!1):(setTimeout((function(){s.$router.push(s.successUrl)}),1500),!1);i.handlerPay(t.data,a)})).catch((function(){}))},handlerPay:function(t,e){if(!0===this.showWaiting&&(this.showWaitingPay=!0),"wxpay"==this.payment){var a=this.$router.resolve({path:"/wxpay",query:{parameter:t.parameter,oid:t.order_oid,amount:t.order_amount}}),i=a.href;window.open(i)}else window.open(t.parameter);if("function"===typeof e)return e(),!1},handlerPaySuccess:function(){this.showWaitingPay=!1,this.$router.push(this.successUrl)},handlerPayFail:function(){this.showWaitingPay=!1,this.$router.push(this.failUrl)}}},o=c,r=a("2877"),l=Object(r["a"])(o,i,s,!1,null,"324d0179",null);e["a"]=l.exports},ad20:function(t,e,a){"use strict";var i=a("0abf"),s=a.n(i);s.a},b02a:function(t,e,a){},f479:function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAATCAIAAAAS8MqlAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiZDFkODdlYS0wOWRmLTk0NGItYmY4ZS03OTVlMTcwMmM4NmEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QTIzMDRDRTNFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QTIzMDRDRTJFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjEgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ZWFkOTZhMGMtMTkxMi1hNDQ1LThhMjMtMDU5OTkxNTk3MmVhIiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6OTljN2ZlMzYtNzg4NC1iOTQxLThiZjMtZDcyOTU0MjE1NjcyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+Ox4V7AAAAhpJREFUeNpi/P//PwMSOP/mzP6nu8+/PvPy+wtmRiZhDlEdIT0XGU9jMTNGBka4Mka4tkdfHky42HnhzVkGbEBbSK/UsEaORwFF28U356pPFn37840BN+Bi4W6z6NMTNoRqA9qTfSjp6+8vDIQANyvPHIel4lySLP8Z/nedbyZGjwq/WrJmJlAPkM1y7vXpa+8u49cgwy2bqJnhIO3y/c+37vMtQAbLgad7EOHDyPT//z9kDaKcYrFqyZ7yfsyMzMBABrrr5bfn//7/ZUEOuhaznsW35t54fxXI5mcTiFSN81cMZWdm//n3x7Rr/RvurQb6CCh16d0FFmD8wLU9+Hx3os3MVXeWsjCx+MgHAgMAKHj9/ZX2cw1PvjyCK3vz/TWj2ybr3/9+QfiyPPILnVfDpf/8+73w5pwVtxf9/f8X2eUsTKxMIhwicP7jLw+vvb8CYd/9eDvzUMLSW/PR9EA8zKQrbIAstOPR5n///y2/vQioB6gTa8ACkxvjqZfHy4/nwYWYGJn42Pg//HyPJz46LCcymYiZawnqwIWAVuHXoymoYypmwQRM16WGtZBAIwjYmTnKDOuAWpiAHHleRWCMcbFw4dfDy8rbYTFBnheUCZggQvoiRhYStnj0AENuqt18oDJoHCBC//MDYHgEKoV//Pn+yruLL7+/ZGNiE+EQ1RcxtJdyAfoHJergLGYmlj7r6ZDsRBAABBgA9pjXch+ZaU8AAAAASUVORK5CYII="}}]);