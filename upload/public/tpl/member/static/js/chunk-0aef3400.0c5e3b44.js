(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0aef3400"],{2909:function(e,t,a){"use strict";a.d(t,"a",(function(){return c}));var r=a("6b75");function s(e){if(Array.isArray(e))return Object(r["a"])(e)}a("a4d3"),a("e01a"),a("d3b7"),a("d28b"),a("3ca3"),a("ddb0"),a("a630");function i(e){if("undefined"!==typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}var n=a("06c5");a("d9e2");function o(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function c(e){return s(e)||i(e)||Object(n["a"])(e)||o()}},"4fa6":function(e,t,a){"use strict";a.r(t);var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("Message",{attrs:{utype:"2"}})],1)},s=[],i=a("54fa"),n={components:{Message:i["a"]}},o=n,c=a("2877"),l=Object(c["a"])(o,r,s,!1,null,null,null);t["default"]=l.exports},"54fa":function(e,t,a){"use strict";var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("el-card",[2==e.utype?a("personal-title",[e._v("消息通知")]):e._e(),1==e.utype?a("div",{staticClass:"notice_title"},[e._v("消息通知")]):e._e(),a("ul",{directives:[{name:"loading",rawName:"v-loading",value:e.listLoading,expression:"listLoading"}],staticClass:"notice_list"},e._l(e.dataset,(function(t,r){return a("li",{key:r},[a("p",{staticClass:"notice_item_title"},[e._v(e._s(t.type_text)+" "),a("span",[e._v(e._s(e._f("timeFilter")(t.addtime)))])]),a("span",{staticClass:"notice_text"},[e._v(e._s(t.content)),t.inner_link?a("a",{staticStyle:{color:"#409eff"},attrs:{href:"javascript:;"},on:{click:function(a){return e.handlerDetail(t)}}},[e._v("查看详情>")]):e._e()])])})),0),a("pagination",{attrs:{total:e.total,"current-page":e.params.page,"page-size":e.params.pagesize},on:{handleCurrentChange:e.doSearch}})],1)},s=[],i=a("2909"),n=(a("ac1f"),a("5319"),a("ed08")),o=a("751a"),c=a("d722"),l={name:"Message",props:["utype"],filters:{timeFilter:function(e){return Object(n["f"])(e,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{listLoading:!1,dataset:[],total:1,params:{page:1,pagesize:10},link_url_web:{}}},mounted:function(){this.link_url_web=this.$store.state.config.link_url_web,this.fetchData(!0)},methods:{fetchData:function(e){var t=this;this.listLoading=!0,o["a"].get(c["a"].member_msglist,this.params).then((function(a){t.dataset=Object(i["a"])(a.data.items),t.listLoading=!1,1==e&&t.fetchTotal()}))},fetchTotal:function(){var e=this;o["a"].get(c["a"].member_msglist_total).then((function(t){e.total=t.data}))},doSearch:function(e){this.params.page=e,this.fetchData()},handlerDetail:function(e){if(1==e.utype)switch(e.inner_link){case"resumeshow":window.open(this.link_url_web.resumeshow.replace("_id_",e.inner_link_params));break;case"job_manage":this.$router.push("/company/joblist");break;case"company_index":this.$router.push("/company");break;case"company_auth":this.$router.push("/company/auth");break;case"ordershow":this.$router.push("/company/service/order");break;case"service":this.$router.push("/company/service/increment");break;case"coupon":this.$router.push("/company/service/coupon");break;case"service_setmeal":this.$router.push("/company/service/setmeal");break;case"interview_list":this.$router.push("/company/resume/interview");break;case"interview_list_video":this.$router.push("/company/resume/interview_video");break;case"recommend_resumelist":this.$router.push("/company/recommend");break;case"company_shortvideo":this.$router.push("/shortvideo");break;default:break}else{if(""!=e.spe_link_params){var t=e.spe_link_params;return t=t.replace("c1=","c1/"),t=t.replace("&c2=","/c2/"),t=t.replace("&c3=","/c3/"),t=t.replace("&d1=","/d1/"),t=t.replace("&d2=","/d2/"),t=t.replace("&d3=","/d3/"),t=t.replace("&w1=","/w1/"),t=t.replace("&w2=","/w2/"),window.open(this.link_url_web.joblist+t),!1}switch(e.inner_link){case"companyshow":window.open(this.link_url_web.companyshow.replace("_id_",e.inner_link_params));break;case"interview_list":this.$router.push("/personal/job/interview");break;case"interview_list_video":this.$router.push("/personal/job/interview_video");break;case"apply_list":this.$router.push("/personal/job/jobapply");break;case"ordershow":this.$router.push("/personal/service/order");break;case"joblist":window.open(this.link_url_web.joblist);break;case"resumeedit":this.$router.push("/personal/resume");break;case"recommend_joblist":this.$router.push("/personal/recommend");break;case"personal_shortvideo":this.$router.push("/shortvideo");break;default:break}}}}},u=l,p=(a("d0a5"),a("2877")),h=Object(p["a"])(u,r,s,!1,null,"051f6876",null);t["a"]=h.exports},d0a5:function(e,t,a){"use strict";a("e02a")},e02a:function(e,t,a){}}]);