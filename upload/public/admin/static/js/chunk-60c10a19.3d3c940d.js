(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-60c10a19"],{"4ccd":function(t,e,a){"use strict";a.r(e);var r=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-row",{attrs:{gutter:20}},[a("el-col",{attrs:{span:6}},[a("el-card",[t._v("入驻企业 "+t._s(t.total.company)+" 家")])],1),a("el-col",{attrs:{span:6}},[a("el-card",[t._v("招聘岗位 "+t._s(t.total.job)+" 个")])],1),a("el-col",{attrs:{span:6}},[a("el-card",[t._v("岗位需求 "+t._s(t.total.job_amount)+" 人")])],1),a("el-col",{attrs:{span:6}},[a("el-card",[t._v("求职者数 "+t._s(t.total.resume)+" 人")])],1)],1),a("el-row",{staticStyle:{"margin-top":"20px"},attrs:{gutter:20}},[a("el-col",{attrs:{span:12}},[a("com_order",{attrs:{height:"300px"}})],1),a("el-col",{attrs:{span:12}},[a("per_order",{attrs:{height:"300px"}})],1)],1),a("el-row",{staticStyle:{"margin-top":"20px"}},[a("reg_line",{attrs:{height:"300px","platform-options":t.platformOptions}})],1),a("el-row",{staticStyle:{"margin-top":"20px"}},[a("active_line",{attrs:{height:"300px","platform-options":t.platformOptions}})],1)],1)},n=[],o=a("2909"),i=a("5530"),c=a("52b5"),u=a("9e90"),l=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("el-card",{staticClass:"box-card",staticStyle:{position:"relative"}},[a("el-date-picker",{staticStyle:{position:"absolute",right:"18px",top:"18px","z-index":"999",width:"240px"},attrs:{size:"mini",type:"daterange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期",format:"yyyy-MM-dd","value-format":"yyyy-MM-dd",editable:!1},on:{change:t.fetchData},model:{value:t.daterange,callback:function(e){t.daterange=e},expression:"daterange"}}),a("div",{style:"height: "+t.height+";",attrs:{id:"com_order"}})],1)],1)},d=[],s=a("313e"),m=a.n(s),p={props:["height"],data:function(){return{daterange:[],charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("com_order")}))},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this,e={utype:1,daterange:this.daterange};Object(u["K"])(e).then((function(e){t.charts.setOption({legend:{data:e.data.label},series:[{data:e.data.dataset}]})}))},drawChart:function(t){this.charts=m.a.init(document.getElementById(t)),this.charts.setOption({title:{text:"企业已完成订单（实收金额）",left:"0"},tooltip:{trigger:"item",formatter:"{a} <br/>{b} : {c} 元 ({d}%)"},legend:{orient:"vertical",x:"left",y:"center",data:[]},series:[{name:"订单类别",type:"pie",radius:["40%","60%"],center:["50%","60%"],data:[],itemStyle:{normal:{color:function(t){var e=["#3aa1ff","#f2637b","#4ecb73","#fbd437","#36cbcb","#975fe5","#f263d9","#435188","#8bf263","#5254cf"],a=t.dataIndex%e.length;return e[a]}}}}]})}}},f=p,h=a("2877"),g=Object(h["a"])(f,l,d,!1,null,null,null),b=g.exports,y=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("el-card",{staticClass:"box-card",staticStyle:{position:"relative"}},[a("el-date-picker",{staticStyle:{position:"absolute",right:"18px",top:"18px","z-index":"999",width:"240px"},attrs:{size:"mini",type:"daterange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期",format:"yyyy-MM-dd","value-format":"yyyy-MM-dd",editable:!1},on:{change:t.fetchData},model:{value:t.daterange,callback:function(e){t.daterange=e},expression:"daterange"}}),a("div",{style:"height: "+t.height+";",attrs:{id:"per_order"}})],1)],1)},v=[],O={props:["height"],data:function(){return{daterange:[],charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("per_order")}))},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this,e={utype:2,daterange:this.daterange};Object(u["K"])(e).then((function(e){t.charts.setOption({legend:{data:e.data.label},series:[{data:e.data.dataset}]})}))},drawChart:function(t){this.charts=m.a.init(document.getElementById(t)),this.charts.setOption({title:{text:"个人已完成订单（实收金额）",left:"0"},tooltip:{trigger:"item",formatter:"{a} <br/>{b} : {c} 元 ({d}%)"},legend:{orient:"vertical",x:"left",y:"center",data:[]},series:[{name:"订单类别",type:"pie",radius:["40%","60%"],center:["50%","60%"],data:[],itemStyle:{normal:{color:function(t){var e=["#3aa1ff","#f2637b","#4ecb73","#fbd437","#36cbcb","#975fe5","#f263d9","#435188","#8bf263","#5254cf"],a=t.dataIndex%e.length;return e[a]}}}}]})}}},j=O,x=Object(h["a"])(j,y,v,!1,null,null,null),w=x.exports,S=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("el-card",{staticClass:"box-card",staticStyle:{position:"relative"}},[a("el-date-picker",{staticStyle:{position:"absolute",right:"18px",top:"18px","z-index":"999",width:"240px"},attrs:{size:"mini",type:"daterange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期",format:"yyyy-MM-dd","value-format":"yyyy-MM-dd",editable:!1},on:{change:t.fetchData},model:{value:t.daterange,callback:function(e){t.daterange=e},expression:"daterange"}}),a("el-select",{staticStyle:{position:"absolute",right:"268px",top:"18px","z-index":"999",width:"120px"},attrs:{size:"mini",placeholder:"请选择渠道"},on:{change:t.fetchData},model:{value:t.platform,callback:function(e){t.platform=e},expression:"platform"}},[a("el-option",{attrs:{label:"不限渠道",value:""}}),t._l(t.platformOptions,(function(t,e){return a("el-option",{key:e,attrs:{label:t.name,value:t.id}})}))],2),a("div",{style:"height: "+t.height+";",attrs:{id:"reg_line"}})],1)],1)},_=[],C={props:["height","platformOptions"],data:function(){return{platform:"",daterange:[],charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("reg_line")}))},created:function(){this.fetchData(null,!0)},methods:{fetchData:function(t){var e=this,a=arguments.length>1&&void 0!==arguments[1]&&arguments[1];!1===a&&this.charts.showLoading();var r={utype:1,daterange:this.daterange,platform:this.platform};Object(u["L"])(r).then((function(t){e.charts.setOption({xAxis:{type:"category",boundaryGap:!1,data:t.data.xAxis},series:[{name:"个人数",type:"line",data:t.data.series[0]},{name:"简历数",type:"line",data:t.data.series[1]},{name:"企业数",type:"line",data:t.data.series[2]},{name:"职位数",type:"line",data:t.data.series[3]}]}),e.charts.hideLoading()}))},drawChart:function(t){this.charts=m.a.init(document.getElementById(t)),this.charts.showLoading(),this.charts.setOption({title:{text:"注册量趋势"},tooltip:{trigger:"axis"},legend:{data:["个人数","简历数","企业数","职位数"]},grid:{left:"3%",right:"4%",bottom:"3%",containLabel:!0},xAxis:{type:"category",boundaryGap:!1,data:[]},yAxis:{type:"value"},series:[{name:"个人数",type:"line",data:[],lineStyle:{color:"#3aa1ff"},itemStyle:{color:"#3aa1ff"}},{name:"简历数",type:"line",data:[],lineStyle:{color:"#f2637b"},itemStyle:{color:"#f2637b"}},{name:"企业数",type:"line",data:[],lineStyle:{color:"#4ecb73"},itemStyle:{color:"#4ecb73"}},{name:"职位数",type:"line",data:[],lineStyle:{color:"#fbd437"},itemStyle:{color:"#fbd437"}}]})}}},D=C,k=Object(h["a"])(D,S,_,!1,null,null,null),A=k.exports,M=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("el-card",{staticClass:"box-card",staticStyle:{position:"relative"}},[a("el-date-picker",{staticStyle:{position:"absolute",right:"18px",top:"18px","z-index":"999",width:"240px"},attrs:{size:"mini",type:"daterange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期",format:"yyyy-MM-dd","value-format":"yyyy-MM-dd",editable:!1},on:{change:t.fetchData},model:{value:t.daterange,callback:function(e){t.daterange=e},expression:"daterange"}}),a("el-select",{staticStyle:{position:"absolute",right:"268px",top:"18px","z-index":"999",width:"120px"},attrs:{size:"mini",placeholder:"请选择渠道"},on:{change:t.fetchData},model:{value:t.platform,callback:function(e){t.platform=e},expression:"platform"}},[a("el-option",{attrs:{label:"不限渠道",value:""}}),t._l(t.platformOptions,(function(t,e){return a("el-option",{key:e,attrs:{label:t.name,value:t.id}})}))],2),a("div",{style:"height: "+t.height+";",attrs:{id:"active_line"}})],1)],1)},E=[],z={props:["height","platformOptions"],data:function(){return{platform:"",daterange:[],charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("active_line")}))},created:function(){this.fetchData(null,!0)},methods:{fetchData:function(t){var e=this,a=arguments.length>1&&void 0!==arguments[1]&&arguments[1];!1===a&&this.charts.showLoading();var r={utype:1,daterange:this.daterange,platform:this.platform};Object(u["J"])(r).then((function(t){e.charts.setOption({xAxis:{type:"category",boundaryGap:!1,data:t.data.xAxis},series:[{name:"刷新简历",type:"line",data:t.data.series[0]},{name:"发布职位",type:"line",data:t.data.series[1]},{name:"刷新职位",type:"line",data:t.data.series[2]},{name:"投递简历",type:"line",data:t.data.series[3]},{name:"下载简历",type:"line",data:t.data.series[4]},{name:"会员登录",type:"line",data:t.data.series[5]}]}),e.charts.hideLoading()}))},drawChart:function(t){this.charts=m.a.init(document.getElementById(t)),this.charts.showLoading(),this.charts.setOption({title:{text:"活跃度分析"},tooltip:{trigger:"axis"},legend:{data:["刷新简历","发布职位","刷新职位","投递简历","下载简历","会员登录"]},grid:{left:"3%",right:"4%",bottom:"3%",containLabel:!0},xAxis:{type:"category",boundaryGap:!1,data:[]},yAxis:{type:"value"},series:[{name:"刷新简历",type:"line",data:[],lineStyle:{color:"#3aa1ff"},itemStyle:{color:"#3aa1ff"}},{name:"发布职位",type:"line",data:[],lineStyle:{color:"#f2637b"},itemStyle:{color:"#f2637b"}},{name:"刷新职位",type:"line",data:[],lineStyle:{color:"#4ecb73"},itemStyle:{color:"#4ecb73"}},{name:"投递简历",type:"line",data:[],lineStyle:{color:"#fbd437"},itemStyle:{color:"#fbd437"}},{name:"下载简历",type:"line",data:[],lineStyle:{color:"#36cbcb"},itemStyle:{color:"#36cbcb"}},{name:"会员登录",type:"line",data:[],lineStyle:{color:"#975fe5"},itemStyle:{color:"#975fe5"}}]})}}},H=z,L=Object(h["a"])(H,M,E,!1,null,null,null),T=L.exports,J={components:{com_order:b,per_order:w,reg_line:A,active_line:T},data:function(){return{platformOptions:[],total:{company:0,job:0,job_amount:0,resume:0}}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;Object(u["M"])({}).then((function(e){t.total=Object(i["a"])({},e.data)}));var e={type:"platform"};Object(c["a"])(e).then((function(e){t.platformOptions=Object(o["a"])(e.data)}))}}},I=J,$=Object(h["a"])(I,r,n,!1,null,null,null);e["default"]=$.exports},"52b5":function(t,e,a){"use strict";a.d(e,"a",(function(){return o}));var r=a("b775"),n=a("d722");function o(t){return Object(r["a"])({url:n["a"].getClassify,method:"get",params:t})}},"9e90":function(t,e,a){"use strict";a.d(e,"M",(function(){return o})),a.d(e,"K",(function(){return i})),a.d(e,"L",(function(){return c})),a.d(e,"J",(function(){return u})),a.d(e,"db",(function(){return l})),a.d(e,"X",(function(){return d})),a.d(e,"Y",(function(){return s})),a.d(e,"Z",(function(){return m})),a.d(e,"ab",(function(){return p})),a.d(e,"bb",(function(){return f})),a.d(e,"W",(function(){return h})),a.d(e,"cb",(function(){return g})),a.d(e,"O",(function(){return b})),a.d(e,"N",(function(){return y})),a.d(e,"P",(function(){return v})),a.d(e,"Q",(function(){return O})),a.d(e,"q",(function(){return j})),a.d(e,"r",(function(){return x})),a.d(e,"p",(function(){return w})),a.d(e,"s",(function(){return S})),a.d(e,"U",(function(){return _})),a.d(e,"S",(function(){return C})),a.d(e,"T",(function(){return D})),a.d(e,"R",(function(){return k})),a.d(e,"V",(function(){return A})),a.d(e,"l",(function(){return M})),a.d(e,"m",(function(){return E})),a.d(e,"j",(function(){return z})),a.d(e,"n",(function(){return H})),a.d(e,"k",(function(){return L})),a.d(e,"o",(function(){return T})),a.d(e,"i",(function(){return J})),a.d(e,"h",(function(){return I})),a.d(e,"x",(function(){return $})),a.d(e,"y",(function(){return B})),a.d(e,"B",(function(){return G})),a.d(e,"C",(function(){return P})),a.d(e,"w",(function(){return R})),a.d(e,"A",(function(){return N})),a.d(e,"z",(function(){return V})),a.d(e,"v",(function(){return K})),a.d(e,"c",(function(){return W})),a.d(e,"b",(function(){return q})),a.d(e,"a",(function(){return F})),a.d(e,"t",(function(){return Q})),a.d(e,"d",(function(){return U})),a.d(e,"f",(function(){return X})),a.d(e,"e",(function(){return Y})),a.d(e,"u",(function(){return Z})),a.d(e,"g",(function(){return tt})),a.d(e,"I",(function(){return et})),a.d(e,"H",(function(){return at})),a.d(e,"D",(function(){return rt})),a.d(e,"G",(function(){return nt})),a.d(e,"F",(function(){return ot})),a.d(e,"E",(function(){return it}));var r=a("b775"),n=a("d722");function o(t){return Object(r["a"])({url:n["a"].overviewTotal,method:"get",params:t})}function i(t){return Object(r["a"])({url:n["a"].overviewOrder,method:"get",params:t})}function c(t){return Object(r["a"])({url:n["a"].overviewReg,method:"get",params:t})}function u(t){return Object(r["a"])({url:n["a"].overviewActive,method:"get",params:t})}function l(t){return Object(r["a"])({url:n["a"].resumeOverviewSex,method:"get",params:t})}function d(t){return Object(r["a"])({url:n["a"].resumeOverviewAge,method:"get",params:t})}function s(t){return Object(r["a"])({url:n["a"].resumeOverviewEdu,method:"get",params:t})}function m(t){return Object(r["a"])({url:n["a"].resumeOverviewExp,method:"get",params:t})}function p(t){return Object(r["a"])({url:n["a"].resumeOverviewIntentionDistrict,method:"get",params:t})}function f(t){return Object(r["a"])({url:n["a"].resumeOverviewIntentionJobcategory,method:"get",params:t})}function h(t){return Object(r["a"])({url:n["a"].resumeOverviewActive,method:"get",params:t})}function g(t){return Object(r["a"])({url:n["a"].resumeOverviewResumeAdd,method:"get",params:t})}function b(t){return Object(r["a"])({url:n["a"].personalEdu,method:"get",params:t})}function y(t){return Object(r["a"])({url:n["a"].personalAge,method:"get",params:t})}function v(t){return Object(r["a"])({url:n["a"].personalExp,method:"get",params:t})}function O(t){return Object(r["a"])({url:n["a"].personalJobcategory,method:"get",params:t})}function j(t){return Object(r["a"])({url:n["a"].intentionComNature,method:"get",params:t})}function x(t){return Object(r["a"])({url:n["a"].intentionComScale,method:"get",params:t})}function w(t){return Object(r["a"])({url:n["a"].intentionComDistrict,method:"get",params:t})}function S(t){return Object(r["a"])({url:n["a"].intentionComTrade,method:"get",params:t})}function _(t){return Object(r["a"])({url:n["a"].resumeHotRefresh,method:"get",params:t})}function C(t){return Object(r["a"])({url:n["a"].resumeHotJobapply,method:"get",params:t})}function D(t){return Object(r["a"])({url:n["a"].resumeHotLogin,method:"get",params:t})}function k(t){return Object(r["a"])({url:n["a"].resumeHotDown,method:"get",params:t})}function A(t){return Object(r["a"])({url:n["a"].resumeHotView,method:"get",params:t})}function M(t){return Object(r["a"])({url:n["a"].companyOverviewComNature,method:"get",params:t})}function E(t){return Object(r["a"])({url:n["a"].companyOverviewComScale,method:"get",params:t})}function z(t){return Object(r["a"])({url:n["a"].companyOverviewComAudit,method:"get",params:t})}function H(t){return Object(r["a"])({url:n["a"].companyOverviewComSetmeal,method:"get",params:t})}function L(t){return Object(r["a"])({url:n["a"].companyOverviewComDistrict,method:"get",params:t})}function T(t){return Object(r["a"])({url:n["a"].companyOverviewComTrade,method:"get",params:t})}function J(t){return Object(r["a"])({url:n["a"].companyOverviewComAdd,method:"get",params:t})}function I(t){return Object(r["a"])({url:n["a"].companyOverviewActive,method:"get",params:t})}function $(t){return Object(r["a"])({url:n["a"].jobOverviewEdu,method:"get",params:t})}function B(t){return Object(r["a"])({url:n["a"].jobOverviewExp,method:"get",params:t})}function G(t){return Object(r["a"])({url:n["a"].jobOverviewNature,method:"get",params:t})}function P(t){return Object(r["a"])({url:n["a"].jobOverviewWage,method:"get",params:t})}function R(t){return Object(r["a"])({url:n["a"].jobOverviewDistrict,method:"get",params:t})}function N(t){return Object(r["a"])({url:n["a"].jobOverviewJobcategory,method:"get",params:t})}function V(t){return Object(r["a"])({url:n["a"].jobOverviewJobAdd,method:"get",params:t})}function K(t){return Object(r["a"])({url:n["a"].jobOverviewActive,method:"get",params:t})}function W(t){return Object(r["a"])({url:n["a"].businessSetmeal,method:"get",params:t})}function q(t){return Object(r["a"])({url:n["a"].businessService,method:"get",params:t})}function F(t){return Object(r["a"])({url:n["a"].businessDown,method:"get",params:t})}function Q(t){return Object(r["a"])({url:n["a"].jobHotRefresh,method:"get",params:t})}function U(t){return Object(r["a"])({url:n["a"].comHotDown,method:"get",params:t})}function X(t){return Object(r["a"])({url:n["a"].comHotLogin,method:"get",params:t})}function Y(t){return Object(r["a"])({url:n["a"].comHotJobapply,method:"get",params:t})}function Z(t){return Object(r["a"])({url:n["a"].jobHotView,method:"get",params:t})}function tt(t){return Object(r["a"])({url:n["a"].comHotView,method:"get",params:t})}function et(t){return Object(r["a"])({url:n["a"].orderTotal,method:"get",params:t})}function at(t){return Object(r["a"])({url:n["a"].orderPersonal,method:"get",params:t})}function rt(t){return Object(r["a"])({url:n["a"].orderCompany,method:"get",params:t})}function nt(t){return Object(r["a"])({url:n["a"].orderPayType,method:"get",params:t})}function ot(t){return Object(r["a"])({url:n["a"].orderPayTotal,method:"get",params:t})}function it(t){return Object(r["a"])({url:n["a"].orderPaySetmeal,method:"get",params:t})}}}]);