(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-6489c2d2"],{"0abf":function(t,a,i){},"27fb":function(t,a,i){"use strict";var e=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"payment-box"},[e("div",{staticClass:"item",class:["alipay"==t.payment?"border":""],on:{click:function(a){return t.choosePayment("alipay")}}},[e("img",{attrs:{src:i("a0f1")}}),t._v("支付宝支付"),"alipay"==t.payment?e("i",{staticClass:"el-icon-check"}):t._e()]),e("div",{staticClass:"item",class:["wxpay"==t.payment?"border":""],on:{click:function(a){return t.choosePayment("wxpay")}}},[e("img",{attrs:{src:i("f479")}}),t._v("微信支付 "),"wxpay"==t.payment?e("i",{staticClass:"el-icon-check"}):t._e()])])},n=[],s={name:"Payment",data:function(){return{payment:"alipay"}},created:function(){},methods:{choosePayment:function(t){this.payment=t,this.$emit("choosePayment",this.payment)}}},c=s,o=(i("ad20"),i("2877")),r=Object(o["a"])(c,e,n,!1,null,"398e9ee3",null);a["a"]=r.exports},5530:function(t,a,i){"use strict";i.d(a,"a",(function(){return s}));i("a4d3"),i("4de4"),i("4160"),i("e439"),i("dbb4"),i("b64b"),i("159b");function e(t,a,i){return a in t?Object.defineProperty(t,a,{value:i,enumerable:!0,configurable:!0,writable:!0}):t[a]=i,t}function n(t,a){var i=Object.keys(t);if(Object.getOwnPropertySymbols){var e=Object.getOwnPropertySymbols(t);a&&(e=e.filter((function(a){return Object.getOwnPropertyDescriptor(t,a).enumerable}))),i.push.apply(i,e)}return i}function s(t){for(var a=1;a<arguments.length;a++){var i=null!=arguments[a]?arguments[a]:{};a%2?n(Object(i),!0).forEach((function(a){e(t,a,i[a])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(i)):n(Object(i)).forEach((function(a){Object.defineProperty(t,a,Object.getOwnPropertyDescriptor(i,a))}))}return t}},"55b9":function(t,a,i){"use strict";var e=i("6a2a"),n=i.n(e);n.a},"6a2a":function(t,a,i){},9234:function(t,a,i){"use strict";var e=i("da3d"),n=i.n(e);n.a},a0f1:function(t,a){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAATCAIAAAAS8MqlAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiZDFkODdlYS0wOWRmLTk0NGItYmY4ZS03OTVlMTcwMmM4NmEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OUY5RTdFNzhFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OUY5RTdFNzdFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjEgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ZWFkOTZhMGMtMTkxMi1hNDQ1LThhMjMtMDU5OTkxNTk3MmVhIiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6OTljN2ZlMzYtNzg4NC1iOTQxLThiZjMtZDcyOTU0MjE1NjcyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+2xlCFwAAAk9JREFUeNpi/P//PwMSOPr696bHP4+8/v30218gV5qL2UaU1V+W3UqUFVkZI1zbnc9/K85/Of76NwM2YCnK2mHIo8LLjKINqDrh2Kcvf/4z4AY8LIwLrPgswdaCtAHt8dn/4fNvFD3iHExA8uWPf8iCvKyMWxwFgHYyAdUWn/2CpgcINjkKzDDnRRMEKgMqBiplOfLq95m3UP/IcjHHKnFA2PysjMzczFU63BDuxic/r374A2QAFQO1sGx+8hNu2ONvf9uufIWwA2TZnyJxkQFQC6PVjncPvvyFC0UqcBgIsQAZfKyM//4zcLMwmgmzzr7zvf/6N7gaeW5mFkj8wMHKhz82PGb8/hfq1SJNLldJtmOosfL8+z8WdibG3/9AiqQ4mer0uIEByMfGxMvCeOH9770vfofKs9/49PfUG/TIZJHgZAJGAJAVocCRd/rLL7ARQBcCuTU6XELsTBfe/3GUYDv48hfMBQxSXExM5iLQVMPKxADRAwSffv+/+ekPLyvT9me/RNmZFlvznfYSAoYqJJWYCLEwHnr5K+LwRyCnRpf73pe/u579EmRjTFTmDFdg7732bdqt70DjwhU4ctQ5gdEDVHb27R8uFgZgcP0POPARGBvMjAxxShxe0uyMDAxAhy27/+MeUgizMDGEynHkaXDKcTNDE9ftz399MRIXVgB0yCE3QaCHQQlPlZd5viUfMKXi1wNMkHMt+YB6SMg4psKsvcY8ymgZBw4Ov/oNTDvAVPf0+18mBkZJTiYzEZZAWQ47cVZGrNmUJAAQYAC19gZNyJyvPAAAAABJRU5ErkJggg=="},a445:function(t,a,i){"use strict";var e=function(){var t=this,a=t.$createElement,i=t._self._c||a;return i("div",[i("el-dialog",{attrs:{title:"支付提醒",visible:t.showWaitingPay,width:"400px","append-to-body":!0},on:{"update:visible":function(a){t.showWaitingPay=a}}},[i("span",{staticClass:"payment_text"},[t._v("请在新打开的页面上完成支付。支付完成后，请根据您支付的情况点击下面按钮。")]),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{attrs:{type:"primary"},on:{click:t.handlerPaySuccess}},[t._v("支付成功")]),i("el-button",{on:{click:t.handlerPayFail}},[t._v("支付失败")])],1)])],1)},n=[],s=i("751a"),c={name:"PaySubmit",props:["payment","showWaiting","successUrl","failUrl"],data:function(){return{showWaitingPay:!1}},created:function(){},methods:{handlerSubmit:function(t,a,i){var e=this,n=this;s["a"].post(t,a).then((function(t){if(1==t.data.pay_status)return e.$message({type:"success",message:"支付成功"}),"function"===typeof i?(i(),!1):(setTimeout((function(){n.$router.push(n.successUrl)}),1500),!1);e.handlerPay(t.data,i)})).catch((function(){}))},handlerPay:function(t,a){if(!0===this.showWaiting&&(this.showWaitingPay=!0),"wxpay"==this.payment){var i=this.$router.resolve({path:"/wxpay",query:{parameter:t.parameter,oid:t.order_oid,amount:t.order_amount}}),e=i.href;window.open(e)}else window.open(t.parameter);if("function"===typeof a)return a(),!1},handlerPaySuccess:function(){this.showWaitingPay=!1,this.$router.push(this.successUrl)},handlerPayFail:function(){this.showWaitingPay=!1,this.$router.push(this.failUrl)}}},o=c,r=i("2877"),l=Object(r["a"])(o,e,n,!1,null,"324d0179",null);a["a"]=l.exports},ad20:function(t,a,i){"use strict";var e=i("0abf"),n=i.n(e);n.a},b3ca:function(t,a,i){"use strict";var e=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("el-card",[e("div",{staticClass:"header_wrapper"},[e("div",{staticClass:"header_left"},[e("div",{staticClass:"header_img"},[e("img",{attrs:{src:t.logo}})]),e("div",{staticClass:"header_title"},[e("p",[t._v(t._s(t.companyname))]),e("div",{staticClass:"p_text"},[e("img",{attrs:{src:i("d50d")}}),t._v(" 我的"+t._s(t.$store.state.config.points_byname)+":"),e("span",[t._v(t._s(t.points))]),!0===t.hasSign?e("el-button",{staticClass:"ed",attrs:{disabled:""}},[t._v("已签到")]):e("el-button",{on:{click:t.handlerSignin}},[t._v("签到")])],1)])]),e("div",{staticClass:"header_right"},[e("span"),e("div",[e("p",[e("span",{staticClass:"lf"},[t._v("今天已获得"),e("span",{staticClass:"color_2"},[t._v(" "+t._s(t.taskPoints.obtain)+" ")]),t._v(t._s(t.$store.state.config.points_byname))]),t._v(" "),e("a",{staticClass:"color_2",attrs:{href:"javascript:;"},on:{click:t.handlerBuyPoint}},[t._v("[购买"+t._s(t.$store.state.config.points_byname)+"]")])]),e("p",[e("span",{staticClass:"lf"},[t._v("您还可免费获得"),e("span",{staticClass:"color_2"},[t._v(" "+t._s(t.taskPoints.able)+" ")]),t._v(t._s(t.$store.state.config.points_byname))]),t._v(" "),e("router-link",{staticClass:"color_1",attrs:{to:"/company/service/pointlog"}},[t._v("[收支明细]")])],1)])])]),e("el-dialog",{attrs:{title:"充值"+t.$store.state.config.points_byname,visible:t.showBuyPoint,width:"540px","close-on-press-escape":!1,"close-on-click-modal":!1,"destroy-on-close":!0},on:{"update:visible":function(a){t.showBuyPoint=a}}},[e("BuyPointDialog",{ref:"buyPoint",on:{submitPay:t.submitPay}})],1),e("el-dialog",{attrs:{title:"支付提醒",visible:t.showWaitingPay,width:"400px"},on:{"update:visible":function(a){t.showWaitingPay=a}}},[e("span",{staticClass:"payment_text"},[t._v("请在新打开的页面上完成支付。支付完成后，请根据您支付的情况点击下面按钮。")]),e("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[e("el-button",{attrs:{type:"primary"},on:{click:t.handlerPaySuccess}},[t._v("支付成功")]),e("el-button",{on:{click:t.handlerPayFail}},[t._v("支付失败")])],1)])],1)},n=[],s=(i("4160"),i("ac1f"),i("5319"),i("159b"),i("b85c")),c=i("5530"),o=function(){var t=this,a=t.$createElement,i=t._self._c||a;return i("ul",{staticClass:"dialog_wrapper"},[i("li",[i("span",{staticClass:"margin"},[t._v("选择套餐")]),i("el-select",{staticClass:"el_select_width",on:{change:t.changeItem},model:{value:t.submitData.service_id,callback:function(a){t.$set(t.submitData,"service_id",a)},expression:"submitData.service_id"}},t._l(t.dataset,(function(t,a){return i("el-option",{key:a,attrs:{label:t.name,value:t.id}})})),1)],1),i("li",[i("span",{staticClass:"margin"},[t._v("应付金额")]),i("span",{staticClass:"color"},[t._v(t._s(t.amount))]),t._v("元 ")]),t.amount>0?i("li",{staticClass:"payment_type"},[i("span",{staticClass:"margin"},[t._v("支付方式")]),i("Payment",{on:{choosePayment:t.choosePayment}})],1):t._e(),i("li",{staticClass:"btn"},[i("span",{staticClass:"margin"}),i("el-button",{attrs:{type:"primary"},on:{click:t.submit}},[t._v(t._s(0==t.amount?"立即兑换":"立即支付"))])],1),i("PaySubmit",{ref:"paySubmit",attrs:{showWaiting:!0,payment:t.submitData.payment,successUrl:"/company/service/order",failUrl:"/company/service/order"}})],1)},r=[],l=i("a445"),m=i("27fb"),u=i("751a"),h=i("d722"),p={props:["type","serviceId","jobId"],components:{PaySubmit:l["a"],Payment:m["a"]},data:function(){return{order_id:0,dataset:[],amount:0,submitData:{service_type:"points",service_id:"",payment:"alipay"}}},mounted:function(){this.initCB()},methods:{initCB:function(){this.fetchData()},fetchData:function(){var t=this;u["a"].get(h["a"].points_setmeal_list,{}).then((function(a){t.dataset=a.data.items,t.amount=t.dataset[0].expense,t.submitData.service_id=t.dataset[0].id})).catch((function(){}))},choosePayment:function(t){this.submitData.payment=t},changeItem:function(t){var a,i=null,e=Object(s["a"])(this.dataset);try{for(e.s();!(a=e.n()).done;){var n=a.value;if(n.id==t){i=n;break}}}catch(c){e.e(c)}finally{e.f()}this.submitData.service_id=i.id,this.amount=i.expense},submit:function(){this.submitData.return_url=window.location.protocol+"//"+window.location.host+"/"+this.$store.state.config.member_dirname+"/company/service/order";var t=this;this.$refs.paySubmit.handlerSubmit(h["a"].company_pay,this.submitData,(function(){t.$emit("submitPay")}))}}},b=p,d=(i("55b9"),i("2877")),y=Object(d["a"])(b,o,r,!1,null,"35bbc239",null),A=y.exports,f={components:{BuyPointDialog:A},data:function(){return{showWaitingPay:!1,showBuyPoint:!1,points:0,logo:"",companyname:"",task:[],taskPoints:{}}},created:function(){document.title=document.title.replace("积分",this.$store.state.config.points_byname),this.fetchData()},computed:{hasSign:function(){return this.$store.state.userSignin}},methods:{fetchData:function(){var t=this;u["a"].get(h["a"].member_points,{}).then((function(a){var i=Object(c["a"])({},a.data),e=i.points,n=i.logo,o=i.companyname,r=i.task,l=i.taskPoints;t.points=e,t.logo=n,t.companyname=o,t.task=r,t.taskPoints=l;var m,u=Object(s["a"])(r);try{for(u.s();!(m=u.n()).done;){var h=m.value;if("sign_in"===h.alias&&1===h.is_done){t.$store.commit("setUserSignin",{signin:!0});break}}}catch(p){u.e(p)}finally{u.f()}})).catch((function(){}))},handlerJump:function(t){switch(t.alias){case"bind_weixin":case"bind_qq":case"bind_sina":this.$router.push("/company/account");break;case"upload_logo":this.$router.push("/company/manage/baseinfo");break;case"auth":this.$router.push("/company/auth");break;case"upload_img":this.$router.push("/company/manage/img");break;case"handle_resume":this.$router.push("/company/resume/jobapply");break;case"sign_in":this.handlerSignin();break;default:break}},handlerSignin:function(){var t=this;u["a"].post(h["a"].member_signin,{}).then((function(a){t.$message({type:"success",message:a.message}),t.$store.commit("setUserSignin",{signin:!0}),t.task.forEach((function(t){"sign_in"===t.alias&&(t.is_done=1)}))})).catch((function(){}))},handlerBuyPoint:function(){this.showBuyPoint=!0},submitPay:function(t){this.showBuyPoint=!1,!0===t?this.$parent.fetchData():this.showWaitingPay=!0},handlerPaySuccess:function(){this.showWaitingPay=!1,this.$parent.fetchData()},handlerPayFail:function(){this.showWaitingPay=!1}}},v=f,g=(i("9234"),Object(d["a"])(v,e,n,!1,null,"6094c8ae",null));a["a"]=g.exports},d50d:function(t,a){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAANCAMAAACq939wAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiZDFkODdlYS0wOWRmLTk0NGItYmY4ZS03OTVlMTcwMmM4NmEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MDg5MUMxNDFEREQ0MTFFQUE5OEM5RUJFQzk4OTQzN0IiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MDg5MUMxNDBEREQ0MTFFQUE5OEM5RUJFQzk4OTQzN0IiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjEgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NzczNzI5YmItODYxNS1kNDQ4LTlmMTAtMmQzNWU5ZTRkN2I5IiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6NTA5MmZhZWQtMjRmNy04YzRiLWIzYzYtNGRiN2VlMjAzNmE1Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+3QqWIwAAAIdQTFRF/rEv/rpG/+/V//z2/rpH/////+Cs/shs/rhB/9qc/rQ4//nu/tiW//Xj//jt/teU/9+p/rtL/tOJ/sdp/rMz/+rG//nw/96n/+fA/rc//rQ2/rM1/9iX/rU6/tSM/r1P/9ma/rxM/rxN//DY/9mb/rU5/s58/rlD/s59//DW/rM0//DX/rtJTEv8eAAAAHlJREFUeNpEjlcOg0AQQ73DAkvvvSTUFLj/+WCUhPjDsi2N5kHXDFM2jTQNTQdJfCVeGHHpBvUvD5T1L9cliimOOEbxVuCtWnI8z6FWLXjy3HXsK3y3+pxUrg8555nV91aW33cMsJMgDIPEPv+QuAgIzCbSVDDbIcAA1woGElNFokUAAAAASUVORK5CYII="},da3d:function(t,a,i){},dbb4:function(t,a,i){var e=i("23e7"),n=i("83ab"),s=i("56ef"),c=i("fc6a"),o=i("06cf"),r=i("8418");e({target:"Object",stat:!0,sham:!n},{getOwnPropertyDescriptors:function(t){var a,i,e=c(t),n=o.f,l=s(e),m={},u=0;while(l.length>u)i=n(e,a=l[u++]),void 0!==i&&r(m,a,i);return m}})},e439:function(t,a,i){var e=i("23e7"),n=i("d039"),s=i("fc6a"),c=i("06cf").f,o=i("83ab"),r=n((function(){c(1)})),l=!o||r;e({target:"Object",stat:!0,forced:l,sham:!o},{getOwnPropertyDescriptor:function(t,a){return c(s(t),a)}})},f479:function(t,a){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAATCAIAAAAS8MqlAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiZDFkODdlYS0wOWRmLTk0NGItYmY4ZS03OTVlMTcwMmM4NmEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QTIzMDRDRTNFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QTIzMDRDRTJFNjdDMTFFQUJENUNCMEMzNjJERDU3NkMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjEgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ZWFkOTZhMGMtMTkxMi1hNDQ1LThhMjMtMDU5OTkxNTk3MmVhIiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6OTljN2ZlMzYtNzg4NC1iOTQxLThiZjMtZDcyOTU0MjE1NjcyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+Ox4V7AAAAhpJREFUeNpi/P//PwMSOP/mzP6nu8+/PvPy+wtmRiZhDlEdIT0XGU9jMTNGBka4Mka4tkdfHky42HnhzVkGbEBbSK/UsEaORwFF28U356pPFn37840BN+Bi4W6z6NMTNoRqA9qTfSjp6+8vDIQANyvPHIel4lySLP8Z/nedbyZGjwq/WrJmJlAPkM1y7vXpa+8u49cgwy2bqJnhIO3y/c+37vMtQAbLgad7EOHDyPT//z9kDaKcYrFqyZ7yfsyMzMBABrrr5bfn//7/ZUEOuhaznsW35t54fxXI5mcTiFSN81cMZWdm//n3x7Rr/RvurQb6CCh16d0FFmD8wLU9+Hx3os3MVXeWsjCx+MgHAgMAKHj9/ZX2cw1PvjyCK3vz/TWj2ybr3/9+QfiyPPILnVfDpf/8+73w5pwVtxf9/f8X2eUsTKxMIhwicP7jLw+vvb8CYd/9eDvzUMLSW/PR9EA8zKQrbIAstOPR5n///y2/vQioB6gTa8ACkxvjqZfHy4/nwYWYGJn42Pg//HyPJz46LCcymYiZawnqwIWAVuHXoymoYypmwQRM16WGtZBAIwjYmTnKDOuAWpiAHHleRWCMcbFw4dfDy8rbYTFBnheUCZggQvoiRhYStnj0AENuqt18oDJoHCBC//MDYHgEKoV//Pn+yruLL7+/ZGNiE+EQ1RcxtJdyAfoHJergLGYmlj7r6ZDsRBAABBgA9pjXch+ZaU8AAAAASUVORK5CYII="}}]);