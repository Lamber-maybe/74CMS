(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-5fac9576"],{"012c":function(t,e,s){t.exports=s.p+"static/img/02.7debd35f.png"},5378:function(t,e,s){},"84f2":function(t,e,s){t.exports=s.p+"static/img/04.f78b0390.png"},"9c9e":function(t,e,s){"use strict";s.r(e);var i=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("el-card",{staticClass:"box-card"},[i("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[i("span",[t._v("系统升级")])]),i("div",{staticClass:"upgrade-tip"},[i("b",[t._v("更新注意事项：")]),i("p",[t._v("1.本次更新不会删除您的用户数据，但仍然建议您在更新前做好备份。")]),i("p",[t._v("2.如当前版本太低，需要按顺序从低到高逐步升级。")]),i("p",[t._v("3.更新完成后，请及时"),i("b",[t._v("更新缓存")]),t._v("。")]),i("p",[t._v("4.授权用户可以联系官方售后客服协助升级，如果您在升级过程中遇到任何问题，请及时联系您的专属客服。")]),i("img",{staticClass:"img",attrs:{src:s("012c")}})]),i("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:""}},[i("el-table-column",{attrs:{label:"","min-width":"380"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("span",{staticClass:"row-title"},[t._v(t._s(e.row.title))])]}}])}),i("el-table-column",{attrs:{label:"","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" 最新版本："),i("span",{staticStyle:{color:"#ff6600"}},[t._v("v"+t._s(t.latest_version))])]}}])}),i("el-table-column",{attrs:{label:"",prop:"size_show","min-width":"100"}}),i("el-table-column",{attrs:{label:"","min-width":"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("span",[t._v("发布时间："+t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),i("el-table-column",{attrs:{fixed:"right",label:"","min-width":"180"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.enable?i("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(s){return t.openDialog(e.row)}}},[t._v(" 升级 ")]):i("el-button",{attrs:{size:"small",type:"info",disabled:""}},[t._v(" 升级 ")]),i("el-button",{attrs:{size:"small"},on:{click:function(s){return t.openLogDialog(e.row)}}},[t._v(" 更新日志 ")])]}}])})],1)],1),t.showDialog?i("el-dialog",{attrs:{title:t.title,width:"600px",visible:t.showDialog,"destroy-on-close":!0,"close-on-click-modal":!1,"close-on-press-escape":!1,"show-close":!1},on:{"update:visible":function(e){t.showDialog=e}}},[i("div",{staticClass:"upgrade-box"},[1==t.step?i("div",{staticClass:"pre-notice"},[i("div",{staticClass:"txt"},[t._v(" 1. 升级不可逆，为保证数据安全性，您在升级前请完成相应备份。未备份将无法还原，如因此导致的问题，我们不承担任何责任！"),i("br"),t._v(" 2. 升级过程中，请勿关闭升级页面，否则可能造成升级失败或其他未知问题！"),i("br"),t._v(" 3. 授权用户可以联系官方售后客服协助升级。 ")]),i("el-button",{attrs:{type:"primary",disabled:t.startDisable},on:{click:t.updateStart}},[t._v(" 我已备份，开始升级"+t._s(t.secondText)+" ")]),i("br"),i("el-button",{staticStyle:{"margin-top":"10px"},attrs:{type:"text"},on:{click:t.closeDialog}},[t._v(" 取消升级 ")])],1):t._e(),1!=t.step?i("div",[i("img",{staticClass:"top-bg",attrs:{src:s("84f2")}}),3!=t.step?i("div",{staticClass:"notice"},[t._v("正在为您升级更新，不要关闭界面")]):i("div",{staticClass:"notice done"},[t._v("升级成功")]),i("el-progress",{attrs:{"stroke-width":20,"text-inside":"",percentage:t.percentage}}),i("div",{staticClass:"progress-tip"},[t._v(t._s(t.progress_text))]),2==t.step?i("el-button",{staticClass:"btn",attrs:{type:"primary"}},[t._v(" 升级中 "),i("div",{staticClass:"loader"},[i("div",{staticClass:"loader-inner ball-pulse"},[i("div"),i("div"),i("div")])])]):i("el-button",{staticClass:"btn",attrs:{type:"primary"},on:{click:t.clearCache}},[t._v(" 清除缓存 ")])],1):t._e()])]):t._e(),t.showLogDialog?i("el-dialog",{attrs:{title:"更新日志",width:"600px",visible:t.showLogDialog,"destroy-on-close":!0,"close-on-click-modal":!1,"close-on-press-escape":!1},on:{"update:visible":function(e){t.showLogDialog=e}}},[i("div",{staticStyle:{"white-space":"pre-wrap","line-height":"36px","max-height":"500px","overflow-y":"auto"}},[t._v(t._s(t.logContent))])]):t._e()],1)},a=[],o=s("b775"),n=s("d722");function r(t){return Object(o["a"])({url:n["a"].newVersionList,method:"get",params:t})}function l(t){return Object(o["a"])({url:n["a"].updateStart,method:"get",params:t})}function c(t){return Object(o["a"])({url:n["a"].updateDownload,method:"get",params:t})}function p(t){return Object(o["a"])({url:n["a"].speedProgress,method:"get",params:t})}function d(t){return Object(o["a"])({url:n["a"].upgradeUnzip,method:"get",params:t})}function u(t){return Object(o["a"])({url:n["a"].upgradeUpdate,method:"get",params:t})}var h=s("1063"),g=s("ed08"),f={filters:{timeFilter:function(t){return Object(g["b"])(t,"{y}-{m}-{d}")}},data:function(){return{nextVersion:"",title:"升级提示",startDisable:!0,timer:"",timer_progress:"",second:10,secondText:"（10s）",step:1,percentage:20,waiting:!1,waitingText:"升级中",showDialog:!1,list:null,listLoading:!0,latest_version:"",filePath:"",size:0,timestamp:"",progress_text:"正在下载升级文件中，请稍等...",showLogDialog:!1,logContent:"",stop:0}},created:function(){this.fetchData()},methods:{openLogDialog:function(t){this.logContent=t.content2,this.showLogDialog=!0},clearCache:function(){var t=this;Object(h["a"])({}).then((function(e){200==e.code&&(t.$store.dispatch("config/getConfigInfo"),window.location.reload())}))},getSecond:function(){this.second>1?(this.second--,this.secondText="（".concat(this.second,"s）")):(this.secondText="",this.startDisable=!1,clearInterval(this.timer))},openDialog:function(t){if("all"!=localStorage.getItem("qscms_access"))return this.$alert("只有超级管理员才能执行系统升级操作！","系统提示",{confirmButtonText:"确定",type:"warning"}),!1;this.stop=0,this.size=t.size,this.title="升级提示",this.progress_text="正在下载升级文件中，请稍等...",this.startDisable=!0,this.second=10,this.secondText="（10s）",this.step=1,this.percentage=0,this.showDialog=!0,this.nextVersion=t.version_text,this.timer=setInterval(this.getSecond,1e3)},closeDialog:function(){this.showDialog=!1,clearInterval(this.timer)},updateStart:function(){var t=this,e=window.location.host,s={domain:e};l(s).then((function(e){t.step=2,t.filePath=e.data.path,t.timestamp=e.data.timestamp,t.updateDownload(),t.speedProgress()}))},updateDownload:function(){var t=this,e={path:this.filePath,timestamp:this.timestamp};c(e).then((function(e){500==e.data&&(t.stop=1,t.$alert(e.message,"系统提示",{confirmButtonText:"关闭",type:"warning",callback:function(e){t.closeDialog()}}))}))},speedProgress:function(){var t=this;if(console.log(this.stop),1==this.stop)return!1;var e=this,s={path:this.filePath,size:this.size,timestamp:this.timestamp};p(s).then((function(s){t.percentage=parseInt(s.data),t.percentage<80?setTimeout((function(){e.speedProgress()}),500):(t.progress_text="正在解压...",t.unzip())}))},unzip:function(){var t=this,e={path:this.filePath,timestamp:this.timestamp};d(e).then((function(e){t.percentage=90,t.progress_text="解压完成，正在执行升级...",t.update()}))},update:function(){var t=this,e={path:this.filePath,timestamp:this.timestamp};u(e).then((function(e){t.percentage=100,t.progress_text="您已成功升级到V"+t.nextVersion,t.step=3}))},fetchData:function(){var t=this;this.listLoading=!0;var e={};r(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.latest_version=e.data.latest_version}))}}},m=f,v=(s("bf87"),s("2877")),b=Object(v["a"])(m,i,a,!1,null,"2050f835",null);e["default"]=b.exports},bf87:function(t,e,s){"use strict";s("5378")}}]);