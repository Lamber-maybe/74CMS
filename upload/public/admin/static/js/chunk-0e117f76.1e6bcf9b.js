(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0e117f76"],{"06ad":function(t,e,n){},4124:function(t,e,n){"use strict";n.d(e,"i",(function(){return r})),n.d(e,"f",(function(){return o})),n.d(e,"h",(function(){return i})),n.d(e,"g",(function(){return s})),n.d(e,"d",(function(){return c})),n.d(e,"b",(function(){return u})),n.d(e,"c",(function(){return f})),n.d(e,"e",(function(){return m})),n.d(e,"a",(function(){return d}));var l=n("b775"),a=n("d722");function r(t){return Object(l["a"])({url:a["a"].marketTplList,method:"get",params:t})}function o(t){return Object(l["a"])({url:a["a"].marketTplAdd,method:"post",data:t})}function i(t){return Object(l["a"])({url:a["a"].marketTplEdit,method:"post",data:t})}function s(t){return Object(l["a"])({url:a["a"].marketTplDelete,method:"post",data:t})}function c(t){return Object(l["a"])({url:a["a"].marketTaskList,method:"get",params:t})}function u(t){return Object(l["a"])({url:a["a"].marketTaskAdd,method:"post",data:t})}function f(t){return Object(l["a"])({url:a["a"].marketTaskDelete,method:"post",data:t})}function m(t){return Object(l["a"])({url:a["a"].marketTaskRun,method:"post",data:t})}function d(t){return Object(l["a"])({url:a["a"].marketSearchMember,method:"get",params:t})}},"4c7f":function(t,e,n){"use strict";n("ab88")},"52b5":function(t,e,n){"use strict";n.d(e,"a",(function(){return r}));var l=n("b775"),a=n("d722");function r(t){return Object(l["a"])({url:a["a"].getClassify,method:"get",params:t})}},"6b36":function(t,e,n){"use strict";n.r(e);var l=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-card",{staticClass:"box-card"},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[t._v("企业过期套餐提醒")])]),n("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,rules:t.rules,"label-width":"100px"}},[n("el-form-item",{attrs:{label:"会员套餐",prop:"setmeal_id"}},[n("el-checkbox",{staticStyle:{"margin-right":"20px"},on:{change:t.handleCheckAllSetmeal},model:{value:t.checkSetmealAll,callback:function(e){t.checkSetmealAll=e},expression:"checkSetmealAll"}},[t._v(" 不限 ")]),n("el-checkbox-group",{on:{change:t.handleCheckedSetmealChange},model:{value:t.form.setmeal_id,callback:function(e){t.$set(t.form,"setmeal_id",e)},expression:"form.setmeal_id"}},t._l(t.options_setmeal,(function(e){return n("el-checkbox",{key:e.id,attrs:{label:e.id}},[t._v(" "+t._s(e.name)+" ")])})),1)],1),n("el-form-item",{attrs:{label:"到期时间",prop:"overtime"}},[n("el-select",{staticClass:"large",attrs:{placeholder:"不限"},model:{value:t.form.overtime,callback:function(e){t.$set(t.form,"overtime",e)},expression:"form.overtime"}},[n("el-option",{attrs:{label:"不限",value:-1}}),n("el-option",{attrs:{label:"已到期",value:0}}),n("el-option",{attrs:{label:"3天内",value:3}}),n("el-option",{attrs:{label:"7天内",value:7}}),n("el-option",{attrs:{label:"15天内",value:15}}),n("el-option",{attrs:{label:"30天内",value:30}}),n("el-option",{attrs:{label:"90天内",value:90}}),n("el-option",{attrs:{label:"180天内",value:180}})],1)],1),n("el-divider",{attrs:{"content-position":"left"}},[t._v("发送设置")]),n("sendConfig",{ref:"sendConfig"}),n("el-form-item",{attrs:{label:""}},[n("el-button",{attrs:{type:"primary",loading:t.submiting},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 生成任务 ")])],1)],1)],1)],1)},a=[],r=n("7e2d"),o=n("52b5"),i=n("e75a"),s=n("4124"),c={components:{sendConfig:i["a"]},data:function(){return{submiting:!1,checkSetmealAll:!0,options_setmeal:[],data:{target:"setmeal",title:"",content:"",send_type:[],condition:null},form:{setmeal_id:[],overtime:-1},rules:{}}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var t=this;Object(o["a"])({type:"setmealList"}).then((function(e){t.options_setmeal=e.data})).catch((function(){}))},onSubmit:function(t){var e=this;e.$refs[t].validate((function(t){if(!t)return!1;e.$refs.sendConfig.$refs["form"].validate((function(t){if(!t)return!1;if(!0===e.submiting)return!1;e.submiting=!0;var n=Object(r["a"])({},e.$refs.sendConfig.form),l=n.title,a=n.content,o=n.send_type;e.data.title=l,e.data.content=a,e.data.send_type=o,e.data.condition=Object(r["a"])({},e.form);var i=Object(r["a"])({},e.data);Object(s["b"])(i).then((function(t){return e.submiting=!1,e.$message.success(t.message),setTimeout((function(){e.$router.push("/tool/market/list")}),1500),!0})).catch((function(){e.submiting=!1}))}))}))},handleCheckAllSetmeal:function(t){if(t)this.form.setmeal_id=[];else if(0==this.form.setmeal_id.length)return!1},handleCheckedSetmealChange:function(t){var e=t.length;this.checkSetmealAll=!(e>0)}}},u=c,f=(n("4c7f"),n("5d22")),m=Object(f["a"])(u,l,a,!1,null,"67f9b9a8",null);e["default"]=m.exports},7760:function(t,e,n){"use strict";n("06ad")},ab88:function(t,e,n){},e75a:function(t,e,n){"use strict";var l=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"100px",rules:t.rules}},[n("el-form-item",{attrs:{label:"任务标题",prop:"title"}},[n("el-input",{staticClass:"large",attrs:{placeholder:"请输入内容"},model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),n("el-form-item",{attrs:{label:"发送渠道",prop:"send_type"}},[n("el-checkbox-group",{model:{value:t.form.send_type,callback:function(e){t.$set(t.form,"send_type",e)},expression:"form.send_type"}},[n("el-checkbox",{attrs:{label:"message"}},[t._v("站内信")]),n("el-checkbox",{attrs:{label:"sms"}},[t._v("短信")]),n("el-checkbox",{attrs:{label:"email"}},[t._v("Email")])],1)],1),n("el-form-item",{attrs:{label:"选择模板"}},[t._l(t.tplList,(function(e,l){return n("el-dropdown",{key:l,attrs:{"split-button":"",type:"text"},on:{click:function(n){return t.funUseTpl(e)}}},[t._v(" "+t._s(e.name)+" "),n("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},[n("el-dropdown-item",{nativeOn:{click:function(n){return t.funUseTpl(e)}}},[t._v(" 应用 ")]),n("el-dropdown-item",{nativeOn:{click:function(n){return t.funEditTpl(e)}}},[t._v(" 编辑 ")]),n("el-dropdown-item",{nativeOn:{click:function(n){return t.funDeleteTpl(e)}}},[t._v(" 删除 ")])],1)],1)})),n("el-button",{staticClass:"button-new-tag",staticStyle:{"margin-left":"0"},attrs:{size:"small"},on:{click:t.funAddTpl}},[t._v(" + 新建模板 ")])],2),n("el-form-item",{attrs:{label:"消息内容",prop:"content"}},[n("el-input",{staticClass:"large",attrs:{type:"textarea",rows:5,placeholder:"请输入内容"},model:{value:t.form.content,callback:function(e){t.$set(t.form,"content",e)},expression:"form.content"}})],1)],1),t.dialogVisible?n("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogVisible,width:"35%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogVisible=e},close:t.closeDialog}},[n("el-form",{ref:"tplform",staticClass:"common-form",attrs:{model:t.tplform,"label-width":"100px",rules:t.tplrules}},[n("el-form-item",{attrs:{label:"模板名称",prop:"name"}},[n("el-input",{model:{value:t.tplform.name,callback:function(e){t.$set(t.tplform,"name",e)},expression:"tplform.name"}})],1),n("el-form-item",{attrs:{label:"消息内容",prop:"content"}},[n("el-input",{attrs:{type:"textarea",rows:"5"},model:{value:t.tplform.content,callback:function(e){t.$set(t.tplform,"content",e)},expression:"tplform.content"}})],1),n("el-form-item",{attrs:{label:" "}},[n("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.saveTpl("tplform")}}},[t._v(" 保存 ")]),n("el-button",{on:{click:t.closeDialog}},[t._v("取 消")])],1)],1)],1):t._e()],1)},a=[],r=n("332f"),o=n("7e2d"),i=n("4124"),s={data:function(){return{tplList:[],form:{title:"",send_type:[],content:""},dialogTitle:"",dialogVisible:!1,tplform:{id:"",name:"",content:""},rules:{title:[{required:!0,message:"请输入任务标题",trigger:"blur"},{max:30,message:"最大 30 个字符",trigger:"blur"}],send_type:[{required:!0,message:"请选择发送渠道",trigger:"change"}],content:[{required:!0,message:"请输入消息内容",trigger:"blur"}]},tplrules:{name:[{required:!0,message:"请输入模板名称",trigger:"blur"},{max:30,message:"最大 30 个字符",trigger:"blur"}],content:[{required:!0,message:"请输入消息内容",trigger:"blur"}]}}},created:function(){this.fetchTplList()},methods:{funAddTpl:function(){this.dialogTitle="新建模板",this.dialogVisible=!0},funEditTpl:function(t){this.tplform=Object(o["a"])({},t),this.dialogTitle="编辑模板",this.dialogVisible=!0},funDeleteTpl:function(t){var e=this;Object(i["g"])({id:t.id}).then((function(t){return e.fetchTplList(),!0})).catch((function(){}))},funUseTpl:function(t){this.form.content=t.content},addTplSave:function(t,e){var n=this;this.$refs[e].validate((function(e){if(!e)return!1;Object(i["f"])(t).then((function(t){return n.closeDialog(),n.fetchTplList(),!0})).catch((function(){}))}))},editTplSave:function(t,e){var n=this;this.$refs[e].validate((function(e){if(!e)return!1;Object(i["h"])(t).then((function(t){return n.closeDialog(),n.fetchTplList(),!0})).catch((function(){}))}))},saveTpl:function(t){var e=this,n=Object(o["a"])({},e.tplform);parseInt(n.id)>0?e.editTplSave(n,t):e.addTplSave(n,t)},fetchTplList:function(){var t=this;Object(i["i"])({}).then((function(e){return t.tplList=Object(r["a"])(e.data.items),!0})).catch((function(){}))},closeDialog:function(){this.dialogVisible=!1,this.tplform={id:"",name:"",content:""}}}},c=s,u=(n("7760"),n("5d22")),f=Object(u["a"])(c,l,a,!1,null,"8f0e1286",null);e["a"]=f.exports}}]);