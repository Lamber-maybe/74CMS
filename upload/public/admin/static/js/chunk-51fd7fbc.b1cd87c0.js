(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-51fd7fbc"],{"0f2f":function(t,e,r){"use strict";r.r(e);var a=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"app-container"},[r("el-row",{attrs:{gutter:20}},[r("el-col",{attrs:{span:12}},[r("com_nature",{attrs:{height:"240px"}})],1),r("el-col",{attrs:{span:12}},[r("com_scale",{attrs:{height:"240px"}})],1)],1),r("el-row",{staticStyle:{"margin-top":"20px"}},[r("com_district",{attrs:{height:"300px"}})],1),r("el-row",{staticStyle:{"margin-top":"20px"}},[r("com_trade",{attrs:{height:"300px"}})],1)],1)},n=[],o=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("el-card",{staticClass:"box-card"},[r("el-row",{attrs:{gutter:20}},[r("el-col",{attrs:{span:12}},[r("div",{style:"height: "+t.height+";",attrs:{id:"com_nature"}})]),r("el-col",{attrs:{span:12}},[r("el-table",{staticStyle:{width:"100%"},attrs:{data:t.dataset,border:"",stripe:"",size:"mini",height:t.height}},[r("el-table-column",{attrs:{prop:"number",label:"排名"}}),r("el-table-column",{attrs:{prop:"name",label:"企业性质"}}),r("el-table-column",{attrs:{prop:"value",label:"人数"}})],1)],1)],1)],1)],1)},u=[],i=r("2909"),c=r("9e90"),s=r("313e"),l=r.n(s),d={props:["height"],data:function(){return{dataset:[],charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("com_nature")}))},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;Object(c["q"])({}).then((function(e){t.dataset=Object(i["a"])(e.data.dataset),t.charts.setOption({series:[{data:t.dataset}]})}))},drawChart:function(t){this.charts=l.a.init(document.getElementById(t)),this.charts.setOption({title:{text:"求职者申请职位企业性质流向",left:"left"},tooltip:{trigger:"item",formatter:"{a}<br/>{b}:{c} ({d}%)"},series:[{name:"企业性质",type:"pie",radius:["40%","60%"],center:["50%","60%"],data:[],itemStyle:{normal:{color:function(t){var e=["#3aa1ff","#f2637b","#4ecb73","#fbd437","#36cbcb","#975fe5","#f263d9","#435188","#8bf263","#5254cf"],r=t.dataIndex%e.length;return e[r]}}}}]})}}},m=d,f=r("2877"),h=Object(f["a"])(m,o,u,!1,null,null,null),b=h.exports,p=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("el-card",{staticClass:"box-card"},[r("el-row",{attrs:{gutter:20}},[r("el-col",{attrs:{span:12}},[r("div",{style:"height: "+t.height+";",attrs:{id:"com_scale"}})]),r("el-col",{attrs:{span:12}},[r("el-table",{staticStyle:{width:"100%"},attrs:{data:t.dataset,border:"",stripe:"",size:"mini",height:t.height}},[r("el-table-column",{attrs:{prop:"number",label:"排名"}}),r("el-table-column",{attrs:{prop:"name",label:"企业规模"}}),r("el-table-column",{attrs:{prop:"value",label:"人数"}})],1)],1)],1)],1)],1)},g=[],O={props:["height"],data:function(){return{dataset:[],charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("com_scale")}))},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;Object(c["r"])({}).then((function(e){t.dataset=Object(i["a"])(e.data.dataset),t.charts.setOption({series:[{data:t.dataset}]})}))},drawChart:function(t){this.charts=l.a.init(document.getElementById(t)),this.charts.setOption({title:{text:"求职者申请职位企业规模流向",left:"left"},tooltip:{trigger:"item",formatter:"{a}<br/>{b}:{c} ({d}%)"},series:[{name:"企业规模",type:"pie",radius:["40%","60%"],center:["50%","60%"],data:[],itemStyle:{normal:{color:function(t){var e=["#3aa1ff","#f2637b","#4ecb73","#fbd437","#36cbcb","#975fe5","#f263d9","#435188","#8bf263","#5254cf"],r=t.dataIndex%e.length;return e[r]}}}}]})}}},v=O,j=Object(f["a"])(v,p,g,!1,null,null,null),w=j.exports,y=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("el-card",{staticClass:"box-card"},[r("el-row",{staticStyle:{"margin-top":"20px"}},[r("el-col",{attrs:{span:14}},[r("div",{style:"height: "+t.height+";",attrs:{id:"com_district"}})]),r("el-col",{attrs:{span:10}},[r("el-table",{staticStyle:{width:"100%"},attrs:{data:t.dataset,border:"",stripe:"",size:"mini",height:t.height}},[r("el-table-column",{attrs:{prop:"number",label:"排名"}}),r("el-table-column",{attrs:{prop:"name",label:"地区"}}),r("el-table-column",{attrs:{prop:"value",label:"人数"}})],1)],1)],1)],1)],1)},x=[],C={props:["height"],data:function(){return{dataset:[],charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("com_district")}))},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;Object(c["p"])({}).then((function(e){t.dataset=Object(i["a"])(e.data.dataset),t.charts.setOption({xAxis:{data:e.data.label},series:[{data:t.dataset}]})}))},drawChart:function(t){this.charts=l.a.init(document.getElementById(t)),this.charts.setOption({title:{text:"求职者申请职位地区流向",left:"0"},tooltip:{trigger:"axis",axisPointer:{type:"shadow"}},grid:{left:"6%"},xAxis:{axisLabel:{interval:0,rotate:40},type:"category",axisTick:{alignWithLabel:!0},data:[]},yAxis:{type:"value"},series:[{name:"人数",type:"bar",itemStyle:{normal:{color:function(t){var e=["#3aa1ff","#f2637b","#4ecb73","#fbd437","#36cbcb","#975fe5","#f263d9","#435188","#8bf263","#5254cf"],r=t.dataIndex%e.length;return e[r]}}},data:[]}]})}}},_=C,S=Object(f["a"])(_,y,x,!1,null,null,null),A=S.exports,D=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("el-card",{staticClass:"box-card"},[r("el-row",{staticStyle:{"margin-top":"20px"}},[r("el-col",{attrs:{span:14}},[r("div",{style:"height: "+t.height+";",attrs:{id:"com_trade"}})]),r("el-col",{attrs:{span:10}},[r("el-table",{staticStyle:{width:"100%"},attrs:{data:t.dataset,border:"",stripe:"",size:"mini",height:t.height}},[r("el-table-column",{attrs:{prop:"number",label:"排名"}}),r("el-table-column",{attrs:{prop:"name",label:"行业"}}),r("el-table-column",{attrs:{prop:"value",label:"人数"}})],1)],1)],1)],1)],1)},E=[],T={props:["height"],data:function(){return{dataset:[],charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("com_trade")}))},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;Object(c["s"])({}).then((function(e){t.dataset=Object(i["a"])(e.data.dataset),t.charts.setOption({xAxis:{data:e.data.label},series:[{data:t.dataset}]})}))},drawChart:function(t){this.charts=l.a.init(document.getElementById(t)),this.charts.setOption({title:{text:"求职者申请职位企业行业流向",left:"0"},tooltip:{trigger:"axis",axisPointer:{type:"shadow"}},grid:{left:"6%"},xAxis:{axisLabel:{interval:0,rotate:40},type:"category",axisTick:{alignWithLabel:!0},data:[]},yAxis:{type:"value"},series:[{name:"人数",type:"bar",itemStyle:{normal:{color:function(t){var e=["#3aa1ff","#f2637b","#4ecb73","#fbd437","#36cbcb","#975fe5","#f263d9","#435188","#8bf263","#5254cf"],r=t.dataIndex%e.length;return e[r]}}},data:[]}]})}}},H=T,I=Object(f["a"])(H,D,E,!1,null,null,null),k=I.exports,J={components:{com_nature:b,com_scale:w,com_district:A,com_trade:k}},$=J,L=Object(f["a"])($,a,n,!1,null,null,null);e["default"]=L.exports},"9e90":function(t,e,r){"use strict";r.d(e,"M",(function(){return o})),r.d(e,"K",(function(){return u})),r.d(e,"L",(function(){return i})),r.d(e,"J",(function(){return c})),r.d(e,"db",(function(){return s})),r.d(e,"X",(function(){return l})),r.d(e,"Y",(function(){return d})),r.d(e,"Z",(function(){return m})),r.d(e,"ab",(function(){return f})),r.d(e,"bb",(function(){return h})),r.d(e,"W",(function(){return b})),r.d(e,"cb",(function(){return p})),r.d(e,"O",(function(){return g})),r.d(e,"N",(function(){return O})),r.d(e,"P",(function(){return v})),r.d(e,"Q",(function(){return j})),r.d(e,"q",(function(){return w})),r.d(e,"r",(function(){return y})),r.d(e,"p",(function(){return x})),r.d(e,"s",(function(){return C})),r.d(e,"U",(function(){return _})),r.d(e,"S",(function(){return S})),r.d(e,"T",(function(){return A})),r.d(e,"R",(function(){return D})),r.d(e,"V",(function(){return E})),r.d(e,"l",(function(){return T})),r.d(e,"m",(function(){return H})),r.d(e,"j",(function(){return I})),r.d(e,"n",(function(){return k})),r.d(e,"k",(function(){return J})),r.d(e,"o",(function(){return $})),r.d(e,"i",(function(){return L})),r.d(e,"h",(function(){return P})),r.d(e,"x",(function(){return z})),r.d(e,"y",(function(){return B})),r.d(e,"B",(function(){return R})),r.d(e,"C",(function(){return N})),r.d(e,"w",(function(){return V})),r.d(e,"A",(function(){return W})),r.d(e,"z",(function(){return q})),r.d(e,"v",(function(){return F})),r.d(e,"c",(function(){return G})),r.d(e,"b",(function(){return K})),r.d(e,"a",(function(){return M})),r.d(e,"t",(function(){return Q})),r.d(e,"d",(function(){return U})),r.d(e,"f",(function(){return X})),r.d(e,"e",(function(){return Y})),r.d(e,"u",(function(){return Z})),r.d(e,"g",(function(){return tt})),r.d(e,"I",(function(){return et})),r.d(e,"H",(function(){return rt})),r.d(e,"D",(function(){return at})),r.d(e,"G",(function(){return nt})),r.d(e,"F",(function(){return ot})),r.d(e,"E",(function(){return ut}));var a=r("b775"),n=r("d722");function o(t){return Object(a["a"])({url:n["a"].overviewTotal,method:"get",params:t})}function u(t){return Object(a["a"])({url:n["a"].overviewOrder,method:"get",params:t})}function i(t){return Object(a["a"])({url:n["a"].overviewReg,method:"get",params:t})}function c(t){return Object(a["a"])({url:n["a"].overviewActive,method:"get",params:t})}function s(t){return Object(a["a"])({url:n["a"].resumeOverviewSex,method:"get",params:t})}function l(t){return Object(a["a"])({url:n["a"].resumeOverviewAge,method:"get",params:t})}function d(t){return Object(a["a"])({url:n["a"].resumeOverviewEdu,method:"get",params:t})}function m(t){return Object(a["a"])({url:n["a"].resumeOverviewExp,method:"get",params:t})}function f(t){return Object(a["a"])({url:n["a"].resumeOverviewIntentionDistrict,method:"get",params:t})}function h(t){return Object(a["a"])({url:n["a"].resumeOverviewIntentionJobcategory,method:"get",params:t})}function b(t){return Object(a["a"])({url:n["a"].resumeOverviewActive,method:"get",params:t})}function p(t){return Object(a["a"])({url:n["a"].resumeOverviewResumeAdd,method:"get",params:t})}function g(t){return Object(a["a"])({url:n["a"].personalEdu,method:"get",params:t})}function O(t){return Object(a["a"])({url:n["a"].personalAge,method:"get",params:t})}function v(t){return Object(a["a"])({url:n["a"].personalExp,method:"get",params:t})}function j(t){return Object(a["a"])({url:n["a"].personalJobcategory,method:"get",params:t})}function w(t){return Object(a["a"])({url:n["a"].intentionComNature,method:"get",params:t})}function y(t){return Object(a["a"])({url:n["a"].intentionComScale,method:"get",params:t})}function x(t){return Object(a["a"])({url:n["a"].intentionComDistrict,method:"get",params:t})}function C(t){return Object(a["a"])({url:n["a"].intentionComTrade,method:"get",params:t})}function _(t){return Object(a["a"])({url:n["a"].resumeHotRefresh,method:"get",params:t})}function S(t){return Object(a["a"])({url:n["a"].resumeHotJobapply,method:"get",params:t})}function A(t){return Object(a["a"])({url:n["a"].resumeHotLogin,method:"get",params:t})}function D(t){return Object(a["a"])({url:n["a"].resumeHotDown,method:"get",params:t})}function E(t){return Object(a["a"])({url:n["a"].resumeHotView,method:"get",params:t})}function T(t){return Object(a["a"])({url:n["a"].companyOverviewComNature,method:"get",params:t})}function H(t){return Object(a["a"])({url:n["a"].companyOverviewComScale,method:"get",params:t})}function I(t){return Object(a["a"])({url:n["a"].companyOverviewComAudit,method:"get",params:t})}function k(t){return Object(a["a"])({url:n["a"].companyOverviewComSetmeal,method:"get",params:t})}function J(t){return Object(a["a"])({url:n["a"].companyOverviewComDistrict,method:"get",params:t})}function $(t){return Object(a["a"])({url:n["a"].companyOverviewComTrade,method:"get",params:t})}function L(t){return Object(a["a"])({url:n["a"].companyOverviewComAdd,method:"get",params:t})}function P(t){return Object(a["a"])({url:n["a"].companyOverviewActive,method:"get",params:t})}function z(t){return Object(a["a"])({url:n["a"].jobOverviewEdu,method:"get",params:t})}function B(t){return Object(a["a"])({url:n["a"].jobOverviewExp,method:"get",params:t})}function R(t){return Object(a["a"])({url:n["a"].jobOverviewNature,method:"get",params:t})}function N(t){return Object(a["a"])({url:n["a"].jobOverviewWage,method:"get",params:t})}function V(t){return Object(a["a"])({url:n["a"].jobOverviewDistrict,method:"get",params:t})}function W(t){return Object(a["a"])({url:n["a"].jobOverviewJobcategory,method:"get",params:t})}function q(t){return Object(a["a"])({url:n["a"].jobOverviewJobAdd,method:"get",params:t})}function F(t){return Object(a["a"])({url:n["a"].jobOverviewActive,method:"get",params:t})}function G(t){return Object(a["a"])({url:n["a"].businessSetmeal,method:"get",params:t})}function K(t){return Object(a["a"])({url:n["a"].businessService,method:"get",params:t})}function M(t){return Object(a["a"])({url:n["a"].businessDown,method:"get",params:t})}function Q(t){return Object(a["a"])({url:n["a"].jobHotRefresh,method:"get",params:t})}function U(t){return Object(a["a"])({url:n["a"].comHotDown,method:"get",params:t})}function X(t){return Object(a["a"])({url:n["a"].comHotLogin,method:"get",params:t})}function Y(t){return Object(a["a"])({url:n["a"].comHotJobapply,method:"get",params:t})}function Z(t){return Object(a["a"])({url:n["a"].jobHotView,method:"get",params:t})}function tt(t){return Object(a["a"])({url:n["a"].comHotView,method:"get",params:t})}function et(t){return Object(a["a"])({url:n["a"].orderTotal,method:"get",params:t})}function rt(t){return Object(a["a"])({url:n["a"].orderPersonal,method:"get",params:t})}function at(t){return Object(a["a"])({url:n["a"].orderCompany,method:"get",params:t})}function nt(t){return Object(a["a"])({url:n["a"].orderPayType,method:"get",params:t})}function ot(t){return Object(a["a"])({url:n["a"].orderPayTotal,method:"get",params:t})}function ut(t){return Object(a["a"])({url:n["a"].orderPaySetmeal,method:"get",params:t})}}}]);