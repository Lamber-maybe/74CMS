(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-9b3a64d6"],{"0ccb":function(t,e,s){var a=s("50c4"),n=s("1148"),i=s("1d80"),l=Math.ceil,o=function(t){return function(e,s,o){var r,c,u=String(i(e)),m=u.length,f=void 0===o?" ":String(o),d=a(s);return d<=m||""==f?u:(r=d-m,c=n.call(f,l(r/f.length)),c.length>r&&(c=c.slice(0,r)),t?u+c:c+u)}};t.exports={start:o(!1),end:o(!0)}},"4d90":function(t,e,s){"use strict";var a=s("23e7"),n=s("0ccb").start,i=s("9a0c");a({target:"String",proto:!0,forced:i},{padStart:function(t){return n(this,t,arguments.length>1?arguments[1]:void 0)}})},"53ca":function(t,e,s){"use strict";s.d(e,"a",(function(){return a}));s("a4d3"),s("e01a"),s("d28b"),s("d3b7"),s("3ca3"),s("ddb0");function a(t){return a="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},a(t)}},"9a0c":function(t,e,s){var a=s("342f");t.exports=/Version\/10\.\d+(\.\d+)?( Mobile\/\w+)? Safari\//.test(a)},"9a4d":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"app-container"},[t._m(0),s("el-tabs",{attrs:{type:"border-card"}},[s("el-tab-pane",{attrs:{label:"参数配置",lazy:!0}},[s("smsbase")],1),s("el-tab-pane",{attrs:{label:"模板设置",lazy:!0}},[s("smstpl")],1),s("el-tab-pane",{attrs:{label:"黑名单",lazy:!0}},[s("blacklist")],1)],1)],1)},n=[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"tip"},[s("p",[t._v(" 短信模块属收费模块，需申请开通后才能使用，请联系我司客服申请开通。 ")]),s("p",[t._v(" 资费标准请联系骑士销售获取，更多介绍请进入 "),s("a",{staticStyle:{color:"#1E88E5"},attrs:{href:"http://www.74cms.com",target:"_blank"}},[t._v(" 骑士人才系统官方网站 ")])]),s("p",[t._v(" 短信发送生效需要在对应服务商的模版配置中单独设置对应模版的模板报备ID（模板报备ID在短信服务商处获取） ")]),s("p",[t._v(" 阿里的短信暂时不能正常发送招聘类信息，使用前请先向阿里官方核实，谨慎使用！ ")])])}],i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"app-container"},[s("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"120px",rules:t.rules,"inline-message":!0}},[s("el-form-item",{attrs:{label:"默认服务商"}},[s("el-radio-group",{model:{value:t.form.sendsms_type,callback:function(e){t.$set(t.form,"sendsms_type",e)},expression:"form.sendsms_type"}},[s("el-radio",{attrs:{label:"qscms"}},[t._v("骑士官方")]),s("el-radio",{attrs:{label:"alisms"}},[t._v("阿里云通信")])],1)],1),s("div",{staticClass:"spaceline"}),"alisms"==t.form.sendsms_type?s("el-form-item",{attrs:{label:"签名",prop:"signature"}},[s("el-input",{staticClass:"middle",model:{value:t.form.alisms.signature,callback:function(e){t.$set(t.form.alisms,"signature",e)},expression:"form.alisms.signature"}})],1):t._e(),"alisms"==t.form.sendsms_type?s("el-form-item",{attrs:{label:"accesskeyid",prop:"accesskeyid"}},[s("el-input",{staticClass:"middle",model:{value:t.form.alisms.accesskey_id,callback:function(e){t.$set(t.form.alisms,"accesskey_id",e)},expression:"form.alisms.accesskey_id"}})],1):t._e(),"alisms"==t.form.sendsms_type?s("el-form-item",{attrs:{label:"accesskeysecret",prop:"accesskeysecret"}},[s("el-input",{staticClass:"middle",model:{value:t.form.alisms.accesskey_secret,callback:function(e){t.$set(t.form.alisms,"accesskey_secret",e)},expression:"form.alisms.accesskey_secret"}})],1):t._e(),"qscms"==t.form.sendsms_type?s("el-form-item",{attrs:{label:"用户名",prop:"appkey"}},[s("el-input",{staticClass:"middle",model:{value:t.form.qscms.app_key,callback:function(e){t.$set(t.form.qscms,"app_key",e)},expression:"form.qscms.app_key"}})],1):t._e(),"qscms"==t.form.sendsms_type?s("el-form-item",{attrs:{label:"密钥",prop:"secretkey"}},[s("el-input",{staticClass:"middle",model:{value:t.form.qscms.secret_key,callback:function(e){t.$set(t.form.qscms,"secret_key",e)},expression:"form.qscms.secret_key"}})],1):t._e(),s("el-form-item",{attrs:{label:""}},[s("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),s("el-button",{on:{click:function(e){return t.onTestDialog()}}},[t._v("测试")])],1)],1),t.dialogFormVisible?s("el-dialog",{attrs:{title:"发送测试短信",visible:t.dialogFormVisible,width:"35%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogFormVisible=e},close:t.closeDialog}},[s("el-form",{ref:"testform",staticClass:"common-form",attrs:{model:t.testform,"label-width":"150px",rules:t.testrules,"inline-message":!0}},[s("el-form-item",{attrs:{label:"接收手机号",prop:"mobile"}},[s("el-input",{staticStyle:{width:"300px"},model:{value:t.testform.mobile,callback:function(e){t.$set(t.testform,"mobile",e)},expression:"testform.mobile"}})],1),s("el-form-item",{attrs:{label:" "}},[s("el-button",{attrs:{type:"primary",loading:t.testLoading},on:{click:function(e){return t.onTest("testform")}}},[t._v(" 发送测试短信 ")]),s("el-button",{on:{click:t.closeDialog}},[t._v("取 消")])],1)],1)],1):t._e()],1)},l=[],o=s("5530"),r=s("1063"),c={data:function(){return{infoLoading:!0,testLoading:!1,dialogFormVisible:!1,testform:{mobile:""},form:{sendsms_type:"qscms",alisms:{signature:"",accesskey_id:"",accesskey_secret:""},qscms:{app_key:"",secret_key:""}},rules:{},testrules:{mobile:[{required:!0,message:"请输入接收手机号",trigger:"blur"}]}}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var t=this;this.infoLoading=!0;var e={};Object(r["j"])(e,"get").then((function(e){var s=e.data,a=s.sendsms_type,n=s.account_alisms,i=s.account_sms;t.form.sendsms_type=a,t.form.alisms=n,t.form.qscms=i,t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,s=this,a={sendsms_type:s.form.sendsms_type};"qscms"==a.sendsms_type?a.account_sms=s.form.qscms:a.account_alisms=s.form.alisms,this.$refs[t].validate((function(t){if(!t)return!1;Object(r["j"])(a).then((function(t){return e.$store.dispatch("config/getConfigInfo"),e.$message.success(t.message),!0})).catch((function(){}))}))},onTestDialog:function(){this.testLoading=!1,this.dialogFormVisible=!0},closeDialog:function(){this.testLoading=!1,this.dialogFormVisible=!1},onTest:function(t){var e=this,s={mobile:e.testform.mobile,type:e.form.sendsms_type,account:Object(o["a"])({},e.form[e.form.sendsms_type])};e.$refs[t].validate((function(t){if(!t)return!1;e.testLoading=!0,Object(r["i"])(s).then((function(t){return e.dialogFormVisible=!1,e.$message.success(t.message),!0})).catch((function(t){e.testLoading=!1}))}))}}},u=c,m=s("2877"),f=Object(m["a"])(u,i,l,!1,null,null,null),d=f.exports,p=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"app-container"},[s("el-radio-group",{on:{change:t.aliasChange},model:{value:t.alias,callback:function(e){t.alias=e},expression:"alias"}},[s("el-radio-button",{attrs:{label:"qscms"}},[t._v("骑士官方")]),s("el-radio-button",{attrs:{label:"alisms"}},[t._v("阿里云通信")])],1),s("div",{staticClass:"spaceline"}),s("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{size:"mini",data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""}},[s("el-table-column",{attrs:{label:"模板名称",prop:"title","min-width":"110"}}),s("el-table-column",{attrs:{label:"模板内容",prop:"content","min-width":"300"}}),s("el-table-column",{attrs:{label:"参数",prop:"params","min-width":"150"}}),"alisms"==t.alias?s("el-table-column",{attrs:{label:"阿里模板ID","min-width":"210"},scopedSlots:t._u([{key:"default",fn:function(e){return[s("el-input",{staticClass:"small",attrs:{size:"mini"},model:{value:e.row.alisms_tplcode,callback:function(s){t.$set(e.row,"alisms_tplcode",s)},expression:"scope.row.alisms_tplcode"}})]}}],null,!1,1704503913)}):t._e()],1),s("div",{staticClass:"spaceline"}),"alisms"==t.alias?s("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.onSubmit()}}},[t._v("保存")]):t._e()],1)},b=[],g=(s("99af"),s("4160"),s("ac1f"),s("5319"),s("159b"),{data:function(){return{alias:"qscms",list:null,listLoading:!0}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={};Object(r["p"])(e,"get").then((function(e){t.list=e.data.items,t.listLoading=!1})).catch((function(){}))},aliasChange:function(t){var e=[];this.list.forEach((function(s){s.content="alisms"==t?s.content.replace(/{/g,"${"):s.content.replace(/\${/g,"{"),e.push(s)})),this.list=[].concat(e)},onSubmit:function(){var t=this,e=[];t.list.forEach((function(t){e.push({id:t.id,alisms_tplcode:t.alisms_tplcode})})),Object(r["p"])(e).then((function(e){return t.$message.success(e.message),!0})).catch((function(){}))}}}),h=g,y=Object(m["a"])(h,p,b,!1,null,null,null),v=y.exports,_=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{staticClass:"list-search"},[s("el-input",{staticClass:"input-with-select",attrs:{placeholder:"请输入搜索内容"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword(e)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[s("el-select",{staticClass:"input-sel",attrs:{slot:"prepend",placeholder:"请选择"},slot:"prepend",model:{value:t.key_type,callback:function(e){t.key_type=e},expression:"key_type"}},[s("el-option",{attrs:{label:"手机号",value:"1"}})],1),s("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1),s("div",{staticClass:"spaceline"}),s("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],staticStyle:{width:"100%"},attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"selection-change":t.handleSelectionChange}},[s("el-table-column",{attrs:{type:"selection",width:"42"}}),s("el-table-column",{attrs:{prop:"mobile",label:"电话号"}}),s("el-table-column",{attrs:{align:"center",label:"添加时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[s("i",{staticClass:"el-icon-time"}),s("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),s("el-table-column",{attrs:{prop:"note",label:"备注"}}),s("el-table-column",{attrs:{prop:"date",label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[s("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(s){return t.funEdit(e.row)}}},[t._v("编辑")]),s("el-button",{attrs:{type:"danger",size:"small"},on:{click:function(s){return t.funDelete(e.row)}}},[t._v("删除")])]}}])})],1),s("div",{staticClass:"spaceline"}),s("el-row",{attrs:{gutter:20}},[s("el-col",{attrs:{span:8}},[s("el-button",{attrs:{type:"primary",size:"small"},on:{click:t.funAdd}},[t._v("添加")]),s("el-button",{attrs:{size:"small",type:"danger"},on:{click:t.funDeleteBatch}},[t._v(" 删除 ")])],1),s("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[s("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1),s("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogFormVisible,width:"25%"},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[s("div",{staticClass:"dialog_box"},[s("el-form",{ref:"form",attrs:{rules:t.rules,model:t.form,"label-width":"80px"}},[s("el-form-item",{attrs:{label:"电话号",prop:"mobile"}},[s("el-input",{model:{value:t.form.mobile,callback:function(e){t.$set(t.form,"mobile",e)},expression:"form.mobile"}})],1),s("el-form-item",{attrs:{label:"备注",prop:"note"}},[s("el-input",{attrs:{type:"textarea",rows:"3",resize:"none"},model:{value:t.form.note,callback:function(e){t.$set(t.form,"note",e)},expression:"form.note"}})],1),s("el-form-item",[s("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),s("el-button",{on:{click:function(e){t.dialogFormVisible=!1}}},[t._v("取 消")])],1)],1)],1)])],1)},k=[],w=s("b85c"),S=s("b775"),$=s("d722");function x(t){return Object(S["a"])({url:$["a"].smsBlacklist,method:"get",params:t})}function C(t){return Object(S["a"])({url:$["a"].smsBlacklistAdd,method:"post",data:t})}function D(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(S["a"])({url:$["a"].smsBlacklistEdit,method:e,data:t}):Object(S["a"])({url:$["a"].smsBlacklistEdit,method:e,params:t})}function j(t){return Object(S["a"])({url:$["a"].smsBlacklistDelete,method:"post",data:t})}var O=s("ed08"),z={filters:{timeFilter:function(t){return Object(O["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,key_type:"1",keyword:"",form:{id:0,mobile:"",note:""},rules:{mobile:[{required:!0,message:"请输入手机号",trigger:"blur"}]},dialogFormVisible:!1,dialogTitle:"",tableIdarr:[],regularMobile:/^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={key_type:this.key_type,keyword:this.keyword,page:this.currentPage,pagesize:this.pagesize};x(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize})).catch((function(){}))},onSubmit:function(t){var e=this,s=Object(o["a"])({},this.form);if(!this.regularMobile.test(s.mobile))return this.$message.error("手机号格式不正确"),!1;this.$refs[t].validate((function(t){if(!t)return!1;0==s.id?C(s).then((function(t){return e.$message.success(t.message),e.dialogFormVisible=!1,e.fetchData(),!0})).catch((function(){})):D(s).then((function(t){return e.$message.success(t.message),e.dialogFormVisible=!1,e.fetchData(),!0})).catch((function(){}))}))},funAdd:function(t,e){this.form={id:0,mobile:"",note:""},this.dialogTitle="添加黑名单号码",this.dialogFormVisible=!0},funEdit:function(t){this.form=JSON.parse(JSON.stringify(t)),this.dialogTitle="编辑黑名单号码",this.dialogFormVisible=!0},funDelete:function(t){var e=this;e.$confirm("从黑名单中删除此号码将会解除对该号码的限制，是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var s={id:[t.id]};j(s).then((function(t){return e.$message.success(t.message),e.fetchData(),!0})).catch((function(){}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0==t.tableIdarr.length)return t.$message.error("请选择要删除的手机号"),!1;t.$confirm("从黑名单中删除此号码将会解除对该号码的限制，是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};j(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0}))})).catch((function(){}))},funSearchKeyword:function(){this.currentPage=1,this.fetchData()},handleSelectionChange:function(t){if(this.tableIdarr=[],t.length>0){var e,s=Object(w["a"])(t);try{for(s.s();!(e=s.n()).done;){var a=e.value;this.tableIdarr.push(a.id)}}catch(n){s.e(n)}finally{s.f()}}},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()}}},L=z,q=(s("b359"),Object(m["a"])(L,_,k,!1,null,"d0fc6d00",null)),F=q.exports,I={components:{smsbase:d,smstpl:v,blacklist:F}},V=I,E=Object(m["a"])(V,a,n,!1,null,null,null);e["default"]=E.exports},b359:function(t,e,s){"use strict";var a=s("d0d2"),n=s.n(a);n.a},b85c:function(t,e,s){"use strict";s.d(e,"a",(function(){return n}));s("a4d3"),s("e01a"),s("d28b"),s("d3b7"),s("3ca3"),s("ddb0");var a=s("06c5");function n(t,e){var s="undefined"!==typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!s){if(Array.isArray(t)||(s=Object(a["a"])(t))||e&&t&&"number"===typeof t.length){s&&(t=s);var n=0,i=function(){};return{s:i,n:function(){return n>=t.length?{done:!0}:{done:!1,value:t[n++]}},e:function(t){throw t},f:i}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var l,o=!0,r=!1;return{s:function(){s=s.call(t)},n:function(){var t=s.next();return o=t.done,t},e:function(t){r=!0,l=t},f:function(){try{o||null==s["return"]||s["return"]()}finally{if(r)throw l}}}}},d0d2:function(t,e,s){},ed08:function(t,e,s){"use strict";s.d(e,"b",(function(){return l})),s.d(e,"a",(function(){return o})),s.d(e,"c",(function(){return r}));s("d3b7"),s("ac1f"),s("25f0"),s("4d90"),s("5319"),s("1276");var a=s("53ca"),n=s("a78e"),i=s.n(n);function l(t,e){if(0===arguments.length)return null;var s,n=e||"{y}-{m}-{d} {h}:{i}:{s}";"object"===Object(a["a"])(t)?s=t:("string"===typeof t&&/^[0-9]+$/.test(t)&&(t=parseInt(t)),"number"===typeof t&&t.toString().length<=10&&(t*=1e3),s=new Date(t));var i={y:s.getFullYear(),m:s.getMonth()+1,d:s.getDate(),h:s.getHours(),i:s.getMinutes(),s:s.getSeconds(),a:s.getDay()},l=n.replace(/{([ymdhisa])+}/g,(function(t,e){var s=i[e];return"a"===e?["日","一","二","三","四","五","六"][s]:s.toString().padStart(2,"0")}));return l}function o(t,e){t=(""+t).length<=10?1e3*parseInt(t):+t;var s=new Date(t),a=Date.now(),n=(a-s)/1e3;return n<30?"刚刚":n<3600?Math.ceil(n/60)+"分钟前":n<86400?Math.ceil(n/3600)+"小时前":n<172800?"1天前":e?l(t,e):s.getMonth()+1+"月"+s.getDate()+"日"+s.getHours()+"时"+s.getMinutes()+"分"}function r(t){var e={utype:t.utype,token:t.token,mobile:t.mobile,userIminfo:t.user_iminfo};i.a.set("qscms_visitor",JSON.stringify(e),{expires:7})}}}]);