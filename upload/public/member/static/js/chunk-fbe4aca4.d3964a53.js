(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-fbe4aca4"],{"2d58":function(t,i,s){},"2e3b":function(t,i,s){"use strict";var e=s("2d58"),a=s.n(e);a.a},e6da:function(t,i,s){"use strict";s.r(i);var e=function(){var t=this,i=t.$createElement,s=t._self._c||i;return s("div",[!1===t.showResult?s("JobForm",{ref:"child",attrs:{type:"add",enable_addjob_num:t.enable_addjob_num,enable_addjob_num_total:t.enable_addjob_num_total},on:{submit:t.submit}}):t._e(),!0===t.showResult?s("div",{staticClass:"success"},[s("el-card",[s("div",{staticClass:"success_text"},[s("span",[t._v("发布职位成功")])]),s("p",[t._v("推荐使用职位置顶，立即提升招聘效果")]),s("div",{staticClass:"btns_wrapper"},[s("el-button",{attrs:{type:"primary"},on:{click:function(i){return t.$router.push("/company")}}},[t._v("会员中心")]),s("el-button",{on:{click:t.addAgain}},[t._v("再发一条")]),s("el-button",{on:{click:function(i){return t.$router.push("/company/joblist")}}},[t._v("职位管理")])],1),1==t.result_audit?s("div",{staticClass:"Topping_con"},[s("div",{staticClass:"Topping_left"},[s("p",{on:{click:function(i){return t.handlerService(2)}}},[t._v("置顶职位 "),s("span",[t._v("信息黄金位置展示，曝光率提升")]),t._v(" "),s("a",[t._v("10倍 ")])]),s("div",{staticClass:"Topping_days_con"},t._l(t.result_service_list,(function(i,e){return s("div",{key:e,on:{click:function(s){return t.handlerService(i.id)}}},[t._v(" "+t._s(i.days)+"天 "),1==i.recommend?s("span",{staticClass:"recommend"},[t._v("推荐"),s("i")]):t._e()])})),0)]),s("div",{staticClass:"Topping_right"},[s("span"),s("div",[s("p",[t._v("置顶效果")]),s("div",{staticClass:"Topping_effect"},[s("div",{staticClass:"Topping_box"},[s("div",{staticClass:"Topping_box_bg1"})]),s("div",{staticClass:"Topping_line"},[s("div",{staticClass:"Topping_box_bg1"}),s("div",{staticClass:"Topping_box_bg1"}),s("div",{staticClass:"Topping_box_bg1"})])]),s("div",{staticClass:"Topping_effect"},[s("div",{staticClass:"Topping_box"},[s("div",{staticClass:"Topping_box_bg2"})]),s("div",{staticClass:"Topping_line"},[s("div",{staticClass:"Topping_box_bg2"}),s("div",{staticClass:"Topping_box_bg2"}),s("div",{staticClass:"Topping_box_bg2"})])])])])]):t._e()])],1):t._e(),s("el-dialog",{attrs:{title:"购买"+t.serviceTitle,visible:t.showBuyService,width:"540px"},on:{"update:visible":function(i){t.showBuyService=i},opened:t.initBuyService}},[s("BuyIncrementDialog",{ref:"buyService",attrs:{"job-id":t.result_id,"service-id":t.serviceId,type:t.serviceType},on:{submitPay:t.submitPay}})],1),s("el-dialog",{attrs:{title:"支付提醒",visible:t.showWaitingPay,width:"400px"},on:{"update:visible":function(i){t.showWaitingPay=i}}},[s("span",{staticClass:"payment_text"},[t._v("请在新打开的页面上完成支付。支付完成后，请根据您支付的情况点击下面按钮。")]),s("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[s("el-button",{attrs:{type:"primary"},on:{click:t.handlerPaySuccess}},[t._v("支付成功")]),s("el-button",{on:{click:t.handlerPayFail}},[t._v("支付失败")])],1)])],1)},a=[],n=s("2909"),o=s("0f13"),c=s("981b"),l=s("751a"),d=s("d722"),u={components:{JobForm:c["a"],BuyIncrementDialog:o["a"]},data:function(){return{showWaitingPay:!1,showBuyService:!1,serviceTitle:"",serviceType:"",serviceId:0,showResult:!1,result_id:0,result_audit:0,result_service_list:[],enable_addjob_num_total:0,enable_addjob_num:0}},created:function(){this.fetchData()},methods:{addAgain:function(){this.fetchData(),this.showResult=!this.showResult},fetchData:function(){var t=this;l["a"].get(d["a"].company_check_jobadd_num,{}).then((function(i){t.enable_addjob_num=i.data.enable_addjob_num,t.enable_addjob_num_total=i.data.enable_addjob_num_total})).catch((function(){})),l["a"].get(d["a"].company_jobadd_pre,{}).then((function(i){t.$refs.child.field_rule=i.data.field_rule})).catch((function(){}))},submit:function(t){var i=this;l["a"].post(d["a"].company_jobadd_save,t).then((function(t){i.result_id=t.data.id,i.result_audit=Object(n["a"])(t.data.audit),i.result_service_list=Object(n["a"])(t.data.service_stick_list),i.showResult=!0})).catch((function(){}))},handlerService:function(t){this.serviceId=t,this.serviceTitle="职位置顶",this.serviceType="jobstick",this.showBuyService=!0},initBuyService:function(){this.$refs.buyService.initCB()},submitPay:function(t){this.showBuyService=!1,!0===t?(this.showResult=!1,this.$router.push("/company/joblist")):this.showWaitingPay=!0},handlerPaySuccess:function(){this.showWaitingPay=!1,this.$router.push("/company/joblist")},handlerPayFail:function(){this.showWaitingPay=!1,this.showResult=!1}}},r=u,_=(s("2e3b"),s("2877")),p=Object(_["a"])(r,e,a,!1,null,"0ef25cda",null);i["default"]=p.exports}}]);