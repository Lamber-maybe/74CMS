(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-6ec231bc"],{"671d":function(t,a,e){},9234:function(t,a,e){"use strict";var s=e("671d"),i=e.n(s);i.a},9406:function(t,a,e){"use strict";e.r(a);var s=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"app-container"},[1==t.baseinfo.warning.rewrite?e("div",{staticClass:"tip danger"},[t._m(0)]):t._e(),1==t.baseinfo.warning.install?e("div",{staticClass:"tip danger"},[e("p",[t._v(" 您还没有删除 install 文件夹，出于安全的考虑，我们建议您删除 install 文件夹。install文件夹位于 /public/ 目录下 ")])]):t._e(),1==t.new_version_notice?e("div",{staticClass:"tip"},[t._m(1)]):t._e(),e("el-row",{attrs:{gutter:20}},[e("el-col",{attrs:{span:18}},[e("el-row",[e("el-card",[e("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[e("span",[t._v("今日信息统计")])]),e("el-row",{attrs:{gutter:20}},[e("el-col",{attrs:{span:-4}},[e("el-card",{staticClass:"no-border",attrs:{shadow:"hover"}},[e("div",{staticClass:"today-info",on:{click:function(a){return t.$router.push("/user/member/personal")}}},[e("div",{staticClass:"tit1"},[t._v("新增个人会员")]),e("div",{staticClass:"num"},[t._v(" "+t._s(t.baseinfo.today_data.reg_personal_today)+" ")]),e("div",{staticClass:"tit2"},[t._v(" 昨日："+t._s(t.baseinfo.today_data.reg_personal_yesterday)+" ")])])])],1),e("el-col",{attrs:{span:-4}},[e("el-card",{staticClass:"no-border",attrs:{shadow:"hover"}},[e("div",{staticClass:"today-info",on:{click:function(a){return t.$router.push("/user/resume/list")}}},[e("div",{staticClass:"tit1"},[t._v("新增简历")]),e("div",{staticClass:"num"},[t._v(" "+t._s(t.baseinfo.today_data.resume_add_today)+" ")]),e("div",{staticClass:"tit2"},[t._v(" 昨日："+t._s(t.baseinfo.today_data.resume_add_yesterday)+" ")])])])],1),e("el-col",{attrs:{span:-4}},[e("el-card",{staticClass:"no-border",attrs:{shadow:"hover"}},[e("div",{staticClass:"today-info",on:{click:function(a){return t.$router.push("/user/resume/list")}}},[e("div",{staticClass:"tit1"},[t._v("简历刷新数")]),e("div",{staticClass:"num"},[t._v(" "+t._s(t.baseinfo.today_data.resume_refresh_today)+" ")]),e("div",{staticClass:"tit2"},[t._v(" 昨日："+t._s(t.baseinfo.today_data.resume_refresh_yesterday)+" ")])])])],1),e("el-col",{attrs:{span:-4}},[e("el-card",{staticClass:"no-border",attrs:{shadow:"hover"}},[e("div",{staticClass:"today-info",on:{click:function(a){return t.$router.push("/user/job_apply")}}},[e("div",{staticClass:"tit1"},[t._v("投递数")]),e("div",{staticClass:"num"},[t._v(" "+t._s(t.baseinfo.today_data.job_apply_today)+" ")]),e("div",{staticClass:"tit2"},[t._v(" 昨日："+t._s(t.baseinfo.today_data.job_apply_yesterday)+" ")])])])],1),e("el-col",{attrs:{span:-4}},[e("el-card",{staticClass:"no-border",attrs:{shadow:"hover"}},[e("div",{staticClass:"today-info",on:{click:function(a){return t.$router.push("/business/personal/order")}}},[e("div",{staticClass:"tit1"},[t._v("个人完成订单")]),e("div",{staticClass:"num"},[t._v(" "+t._s(t.baseinfo.today_data.orderpay_personal_today)+" ")]),e("div",{staticClass:"tit2"},[t._v(" 昨日："+t._s(t.baseinfo.today_data.orderpay_personal_yesterday)+" ")])])])],1)],1),e("el-row",{staticStyle:{"margin-top":"20px"},attrs:{gutter:20}},[e("el-col",{attrs:{span:-4}},[e("el-card",{staticClass:"no-border",attrs:{shadow:"hover"}},[e("div",{staticClass:"today-info",on:{click:function(a){return t.$router.push("/user/member/company")}}},[e("div",{staticClass:"tit1"},[t._v("新增企业会员")]),e("div",{staticClass:"num"},[t._v(" "+t._s(t.baseinfo.today_data.reg_company_today)+" ")]),e("div",{staticClass:"tit2"},[t._v(" 昨日："+t._s(t.baseinfo.today_data.reg_company_yesterday)+" ")])])])],1),e("el-col",{attrs:{span:-4}},[e("el-card",{staticClass:"no-border",attrs:{shadow:"hover"}},[e("div",{staticClass:"today-info",on:{click:function(a){return t.$router.push("/user/job/list")}}},[e("div",{staticClass:"tit1"},[t._v("新增职位")]),e("div",{staticClass:"num"},[t._v(" "+t._s(t.baseinfo.today_data.job_add_today)+" ")]),e("div",{staticClass:"tit2"},[t._v(" 昨日："+t._s(t.baseinfo.today_data.job_add_yesterday)+" ")])])])],1),e("el-col",{attrs:{span:-4}},[e("el-card",{staticClass:"no-border",attrs:{shadow:"hover"}},[e("div",{staticClass:"today-info",on:{click:function(a){return t.$router.push("/user/job/list")}}},[e("div",{staticClass:"tit1"},[t._v("职位刷新数")]),e("div",{staticClass:"num"},[t._v(" "+t._s(t.baseinfo.today_data.job_refresh_today)+" ")]),e("div",{staticClass:"tit2"},[t._v(" 昨日："+t._s(t.baseinfo.today_data.job_refresh_yesterday)+" ")])])])],1),e("el-col",{attrs:{span:-4}},[e("el-card",{staticClass:"no-border",attrs:{shadow:"hover"}},[e("div",{staticClass:"today-info",on:{click:function(a){return t.$router.push("/user/company_down")}}},[e("div",{staticClass:"tit1"},[t._v("下载数")]),e("div",{staticClass:"num"},[t._v(" "+t._s(t.baseinfo.today_data.down_resume_today)+" ")]),e("div",{staticClass:"tit2"},[t._v(" 昨日："+t._s(t.baseinfo.today_data.down_resume_yesterday)+" ")])])])],1),e("el-col",{attrs:{span:-4}},[e("el-card",{staticClass:"no-border",attrs:{shadow:"hover"}},[e("div",{staticClass:"today-info",on:{click:function(a){return t.$router.push("/business/company/order")}}},[e("div",{staticClass:"tit1"},[t._v("企业完成订单")]),e("div",{staticClass:"num"},[t._v(" "+t._s(t.baseinfo.today_data.orderpay_company_today)+" ")]),e("div",{staticClass:"tit2"},[t._v(" 昨日："+t._s(t.baseinfo.today_data.orderpay_company_yesterday)+" ")])])])],1)],1)],1)],1),e("el-row",{staticStyle:{"margin-top":"20px"}},[e("el-card",[e("el-tabs",{model:{value:t.chartTabSelected,callback:function(a){t.chartTabSelected=a},expression:"chartTabSelected"}},[e("el-tab-pane",{attrs:{label:"个人/企业注册",name:"0"}}),e("el-tab-pane",{attrs:{label:"下载简历",name:"1"}}),e("el-tab-pane",{attrs:{label:"投递职位",name:"2"}}),e("el-tab-pane",{attrs:{label:"收入金额",name:"3"}})],1),"0"==t.chartTabSelected?e("reg_line"):t._e(),"1"==t.chartTabSelected?e("down_resume_line"):t._e(),"2"==t.chartTabSelected?e("jobapply_line"):t._e(),"3"==t.chartTabSelected?e("income_line"):t._e()],1)],1)],1),e("el-col",{attrs:{span:6}},[e("el-row",[e("el-card",[e("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[e("span",[t._v("今日待办")])]),e("el-table",{staticStyle:{width:"100%",cursor:"pointer"},attrs:{"show-header":!1,data:t.baseinfo.pending_data},on:{"row-click":t.handlerClickPending}},[e("el-table-column",{attrs:{prop:"title",label:"待办事项",width:"180"}}),e("el-table-column",{attrs:{prop:"num",label:"数量",align:"right"},scopedSlots:t._u([{key:"default",fn:function(a){return[e("span",{staticClass:"num-circle"},[t._v(t._s(a.row.num))]),e("i",{staticClass:"el-icon-arrow-right"})]}}])})],1)],1)],1),e("el-row",{staticStyle:{"margin-top":"20px"}},[e("el-card",[e("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[e("span",[t._v("更新日志")])]),e("el-timeline",{attrs:{reverse:!1}},t._l(t.upgradeLog,(function(a,s){return e("el-timeline-item",{key:s,attrs:{timestamp:a.time}},[e("el-link",{attrs:{target:"_blank",href:a.url}},[t._v(t._s(a.title))])],1)})),1)],1)],1)],1)],1),e("el-row",{staticStyle:{"margin-top":"20px"}},[e("el-card",[e("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[e("span",[t._v("服务器信息")])]),e("el-row",{staticStyle:{"font-size":"13px"},attrs:{gutter:20}},[e("el-col",{attrs:{span:6}},[t._v(" 操作系统："+t._s(t.baseinfo.server_info.os)+" ")]),e("el-col",{attrs:{span:6}},[t._v(" PHP版本："+t._s(t.baseinfo.server_info.php_version)+" ")]),e("el-col",{attrs:{span:6}},[t._v(" MySQL版本："+t._s(t.baseinfo.server_info.mysql_version)+" ")]),e("el-col",{attrs:{span:6}},[t._v(" web服务器："+t._s(t.baseinfo.server_info.web_server)+" ")])],1),e("el-row",{staticStyle:{"font-size":"13px","margin-top":"20px"},attrs:{gutter:20}},[e("el-col",{attrs:{span:6}},[t._v(" 允许最大上传文件："+t._s(t.baseinfo.server_info.upload_max)+" ")]),e("el-col",{attrs:{span:6}},[t._v(" curl版本："+t._s(t.baseinfo.server_info.curl_version)+" ")]),e("el-col",{attrs:{span:6}},[t._v(" ThinkPHP框架版本："+t._s(t.baseinfo.server_info.framework_version)+" ")]),e("el-col",{attrs:{span:6}},[t._v(" 服务器当前时间："+t._s(t.baseinfo.server_info.server_time)+" ")])],1)],1)],1),e("el-row",{staticStyle:{"margin-top":"20px"}},[e("el-card",[e("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[e("span",[t._v("系统信息")])]),e("el-row",{staticStyle:{"font-size":"13px"},attrs:{gutter:20}},[e("el-col",{attrs:{span:6}},[t._v(" 当前版本："),e("span",{staticClass:"color-link"},[t._v("v"+t._s(t.baseinfo.version))])]),e("el-col",{attrs:{span:6}},[t._v(" 授权类型："),e("a",{staticClass:"a-link color-link",attrs:{href:"https://www.74cms.com",target:"_blank"}},[t._v(t._s(t.authorize.authorize))])]),e("el-col",{attrs:{span:6}},[t._v(" 骑士人才系统官网："),e("a",{staticClass:"a-link",attrs:{href:"https://www.74cms.com",target:"_blank"}},[t._v("www.74cms.com")])]),e("el-col",{attrs:{span:6}},[t._v(" 程序开发：74CMS程序开发组 ")])],1),e("el-row",{staticStyle:{"font-size":"13px","margin-top":"10px"},attrs:{gutter:20}},[e("el-col",[e("div",{domProps:{innerHTML:t._s(t.authorize.copyright)}})])],1)],1)],1),e("el-row",{staticStyle:{"margin-top":"20px"}},[e("el-card",[e("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[e("span",[t._v("官方动态")])]),e("el-col",{attrs:{span:14}},[e("div",{staticClass:"official-news-list"},t._l(t.officialNews,(function(a,s){return e("div",{key:s,staticClass:"item"},[e("a",{staticClass:"a-link",attrs:{href:a.link,target:"_blank"}},[t._v(t._s(a.title))]),e("span",{staticClass:"time"},[t._v(t._s(a.addtime))])])})),0),e("div",{staticClass:"clearfix"})]),e("el-col",{staticStyle:{"text-align":"center"},attrs:{span:10}},[e("img",{staticClass:"qrcode",attrs:{src:"static/wechat.jpg"}})])],1)],1)],1)},i=[function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("p",[t._v(" 系统检测到您的伪静态还没配置完成，将影响系统的正常运行，请先配置 >> "),e("a",{staticStyle:{color:"#1e88e5"},attrs:{href:"http://doc.74cms.com/#/se/quickstart?id=%e9%85%8d%e7%bd%aeurl%e9%87%8d%e5%86%99%e8%a7%84%e5%88%99",target:"_blank"}},[t._v(" 配置教程 ")])])},function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("p",[t._v(" 系统检测到新版本，为了更好的使用体验，建议您立即升级程序 "),e("a",{staticStyle:{color:"#1e88e5"},attrs:{href:"https://74cms.com/downloadse/index.html",target:"_blank"}},[t._v(" 立即升级 ")])])}],r=e("b775"),n=e("d722");function o(t){return Object(r["a"])({url:n["a"].dashboardBaseinfo,method:"get",params:t})}function l(t){return Object(r["a"])({url:n["a"].officialData,method:"get",params:t})}function c(t){return Object(r["a"])({url:n["a"].dashboardChart,method:"get",params:t})}var d=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticStyle:{height:"300px"},attrs:{id:"reg_line"}})},u=[],_=e("313e"),h=e.n(_),p={data:function(){return{charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("reg_line")}))},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this,a={type:"0"};c(a).then((function(a){t.charts.setOption({xAxis:{type:"category",boundaryGap:!1,data:a.data.xAxis},series:[{name:"个人数",type:"line",data:a.data.series[0]},{name:"企业数",type:"line",data:a.data.series[1]}]}),t.charts.hideLoading()}))},drawChart:function(t){this.charts=h.a.init(document.getElementById(t)),this.charts.showLoading(),this.charts.setOption({tooltip:{trigger:"axis"},legend:{data:["个人数","企业数"]},grid:{left:"3%",right:"4%",bottom:"3%",containLabel:!0},xAxis:{type:"category",boundaryGap:!1,data:[]},yAxis:{type:"value"},series:[{name:"个人数",type:"line",data:[],lineStyle:{color:"#3aa1ff"},itemStyle:{color:"#3aa1ff"}},{name:"企业数",type:"line",data:[],lineStyle:{color:"#f2637b"},itemStyle:{color:"#f2637b"}}]})}}},f=p,v=e("2877"),y=Object(v["a"])(f,d,u,!1,null,null,null),m=y.exports,b=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticStyle:{height:"300px"},attrs:{id:"down_resume_line"}})},g=[],w={data:function(){return{charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("down_resume_line")}))},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this,a={type:"1"};c(a).then((function(a){t.charts.setOption({xAxis:{type:"category",boundaryGap:!1,data:a.data.xAxis},series:[{type:"line",data:a.data.series}]}),t.charts.hideLoading()}))},drawChart:function(t){this.charts=h.a.init(document.getElementById(t)),this.charts.showLoading(),this.charts.setOption({tooltip:{trigger:"axis"},grid:{left:"3%",right:"4%",bottom:"3%",containLabel:!0},xAxis:{type:"category",boundaryGap:!1,data:[]},yAxis:{type:"value"},series:[{type:"line",data:[],lineStyle:{color:"#3aa1ff"},itemStyle:{color:"#3aa1ff"}}]})}}},C=w,x=Object(v["a"])(C,b,g,!1,null,null,null),k=x.exports,S=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticStyle:{height:"300px"},attrs:{id:"jobapply_line"}})},$=[],j={data:function(){return{charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("jobapply_line")}))},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this,a={type:"2"};c(a).then((function(a){t.charts.setOption({xAxis:{type:"category",boundaryGap:!1,data:a.data.xAxis},series:[{type:"line",data:a.data.series}]}),t.charts.hideLoading()}))},drawChart:function(t){this.charts=h.a.init(document.getElementById(t)),this.charts.showLoading(),this.charts.setOption({tooltip:{trigger:"axis"},grid:{left:"3%",right:"4%",bottom:"3%",containLabel:!0},xAxis:{type:"category",boundaryGap:!1,data:[]},yAxis:{type:"value"},series:[{type:"line",data:[],lineStyle:{color:"#3aa1ff"},itemStyle:{color:"#3aa1ff"}}]})}}},L=j,A=Object(v["a"])(L,S,$,!1,null,null,null),O=A.exports,T=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticStyle:{height:"300px"},attrs:{id:"income_line"}})},D=[],E={data:function(){return{charts:""}},mounted:function(){this.$nextTick((function(){this.drawChart("income_line")}))},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this,a={type:"3"};c(a).then((function(a){t.charts.setOption({xAxis:{type:"category",boundaryGap:!1,data:a.data.xAxis},series:[{type:"line",data:a.data.series}]}),t.charts.hideLoading()}))},drawChart:function(t){this.charts=h.a.init(document.getElementById(t)),this.charts.showLoading(),this.charts.setOption({tooltip:{trigger:"axis"},grid:{left:"3%",right:"4%",bottom:"3%",containLabel:!0},xAxis:{type:"category",boundaryGap:!1,data:[]},yAxis:{type:"value"},series:[{type:"line",data:[],lineStyle:{color:"#3aa1ff"},itemStyle:{color:"#3aa1ff"}}]})}}},z=E,G=Object(v["a"])(z,T,D,!1,null,null,null),P=G.exports,B={name:"Dashboard",components:{reg_line:m,down_resume_line:k,jobapply_line:O,income_line:P},data:function(){return{baseinfo:{today_data:{},pending_data:[],server_info:{},version:"",warning:{rewrite:0,install:0}},upgradeLog:[],authorize:{},officialNews:[],chartTabSelected:"0",new_version_notice:0}},computed:{},mounted:function(){this.fetchData()},methods:{fetchData:function(){var t=this;o({}).then((function(a){t.baseinfo=a.data})),l({}).then((function(a){t.upgradeLog=a.data.upgrade_log,t.authorize=a.data.authorize_info,t.officialNews=a.data.official_news,t.new_version_notice=a.data.new_version_notice}))},handlerClickPending:function(t){switch(t.alias){case"company_audit":return void this.$router.push("/user/company/noaudit");case"job_audit":return void this.$router.push("/user/job/noaudit");case"resume_audit":return void this.$router.push("/user/resume/noaudit");case"cancel_apply":return void this.$router.push("/user/cancel_apply");case"tipoff":return void this.$router.push("/content/feedback/tipoff");case"feedback":return void this.$router.push("/content/feedback/suggest")}}}},I=B,q=(e("9234"),Object(v["a"])(I,s,i,!1,null,"c9269478",null));a["default"]=q.exports}}]);