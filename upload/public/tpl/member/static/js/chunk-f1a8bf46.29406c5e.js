(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-f1a8bf46"],{"02e5":function(e,t,a){"use strict";a("727e")},2469:function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("el-card",[a("div",[a("menuheader",[e._v("我的下载")]),a("div",{staticClass:"list"},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.listLoading,expression:"listLoading"}],attrs:{data:e.dataset,"header-cell-style":{background:"#fcfcfc",color:"#b8babd"}}},[a("el-table-column",{attrs:{"header-align":"left",align:"left",label:"基本信息"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",{staticClass:"information_wrapper"},[a("div",{staticClass:"portraitImg"},[a("img",{attrs:{src:t.row.photo_img_src}})]),a("div",{staticClass:"information_list"},[a("div",{staticClass:"fn_bar"},[a("a",{staticClass:"name a-link",attrs:{href:t.row.resume_link_url_web,target:"_blank"}},[e._v(e._s(t.row.fullname))]),t.row.high_quality>0?a("div",{staticClass:"hq"}):e._e(),a("div",{staticClass:"clear"})]),a("p",{staticClass:"information"},[e._v(" "+e._s(t.row.age)+"岁 | "+e._s(t.row.education_text)+" | "+e._s(t.row.experience_text)+" | "+e._s(t.row.sex_text)+" ")])])])]}}])}),a("el-table-column",{attrs:{"header-align":"left",prop:"address",label:"求职意向"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("p",{staticClass:"intention_post"},[e._v(" 想找 "),a("span",[e._v(e._s(t.row.intention_jobs))]),e._v(" 工作 ")]),a("p",{staticClass:"intention_local"},[e._v(" 想在 "),a("span",[e._v(e._s(t.row.intention_district))]),e._v(" 工作 ")])]}}])}),a("el-table-column",{attrs:{"header-align":"center",align:"center",label:"下载时间"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",{staticClass:"time"},[e._v(" "+e._s(e._f("timeFilter")(t.row.addtime))+" ")])]}}])}),a("el-table-column",{attrs:{"header-align":"center",align:"center",width:"300px",label:"操作"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(a){return e.handlerRemark(t.row)}}},[e._v("备注")]),a("el-button",{attrs:{size:"mini"},on:{click:function(a){return e.openAddInvitation(t.row)}}},[e._v("面试邀请")]),a("el-button",{attrs:{size:"mini",type:"warning"},on:{click:function(a){return e.handlerSave(t.row.resume_id)}}},[e._v("保存到电脑")])]}}])})],1),a("pagination",{attrs:{total:e.total,"current-page":e.params.page,"page-size":e.params.pagesize},on:{handleCurrentChange:e.doSearch}})],1)],1),a("el-dialog",{attrs:{width:"540px",title:"填写备注",visible:e.showRemark},on:{"update:visible":function(t){e.showRemark=t},opened:e.handlerRemarkOpened,closed:e.handlerRemarkClose}},[a("Remark",{ref:"remark",attrs:{remark_item:e.remark_item}}),a("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{attrs:{type:"primary",loading:e.btnLoading},on:{click:function(t){return e.handlerRemarkClose(!0)}}},[e._v("确 定")]),a("el-button",{on:{click:e.handlerRemarkClose}},[e._v("取 消")])],1)],1),a("el-dialog",{attrs:{width:"540px","destroy-on-close":!0,title:"面试邀请",visible:e.showInvite},on:{"update:visible":function(t){e.showInvite=t},opened:e.handlerInviteOpened,closed:e.handlerInviteClose}},[a("Invite",{ref:"invite",attrs:{from:"download",resume_id:e.resume_id,apply_fullname:e.apply_fullname}}),a("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{attrs:{type:"primary",loading:e.btnLoading},on:{click:function(t){return e.handlerInviteClose(!0)}}},[e._v("确 定")]),a("el-button",{on:{click:e.handlerInviteClose}},[e._v("取 消")])],1)],1)],1)},r=[],o=a("2909"),i=a("ed08"),s=a("751a"),l=a("d722"),c=a("9dc2"),d=a("884d"),m=a("5ddc"),u={components:{menuheader:c["a"],Remark:d["a"],Invite:m["a"]},filters:{timeFilter:function(e){return Object(i["d"])(e,"{y}-{m}-{d} {h}:{i}",!0)}},data:function(){return{btnLoading:!1,showInvite:!1,resume_id:0,apply_fullname:"",showRemark:!1,remark_item:{},listLoading:!1,dataset:[],total:0,params:{page:1,pagesize:10},dialogVisible:!1}},created:function(){this.fetchData(!0)},methods:{fetchData:function(e){var t=this;this.listLoading=!0,s["a"].get(l["a"].company_downresume_list,this.params).then((function(a){t.dataset=Object(o["a"])(a.data.items),t.listLoading=!1,!0===e&&t.fetchTotal()})).catch((function(){}))},fetchTotal:function(){var e=this;s["a"].get(l["a"].company_downresume_list_total,{}).then((function(t){e.total=t.data})).catch((function(){}))},doSearch:function(e){this.params.page=e,this.fetchData()},openAddInvitation:function(e){if(1!=e.audit)return this.$message.error("该简历尚未通过审核，不能继续此操作"),!1;this.resume_id=e.resume_id,this.apply_fullname=e.fullname,this.showInvite=!0},handlerInviteOpened:function(){this.$refs.invite.initCB()},handlerInviteClose:function(e){var t=this;!0===e?(this.btnLoading=!0,this.$refs.invite.$refs.form.validate((function(e){if(!e)return t.btnLoading=!1,!1;var a=t.$refs.invite.form;a.resume_id=t.resume_id,s["a"].post(l["a"].interview_add,a).then((function(){t.btnLoading=!1,t.fetchData(!0),t.$message({type:"success",message:"邀请面试成功"}),t.showInvite=!1})).catch((function(){t.btnLoading=!1}))}))):this.showInvite=!1},handlerRemark:function(e){this.showRemark=!0,this.remark_item=e},handlerRemarkOpened:function(){this.$refs.remark.initCB()},handlerRemarkClose:function(e){var t=this;if(this.showRemark=!1,!0===e){this.btnLoading=!0;var a={remark:this.$refs.remark.content,resume_id:this.remark_item.resume_id};this.remark_item.remark=this.$refs.remark.content,s["a"].post(l["a"].remark_resume,a).then((function(e){200==e.code?(t.$message({type:"success",message:e.message}),t.btnLoading=!1,t.showRemark=!1):t.$message({type:"error",message:e.message}),t.fetchData(!0)})).catch((function(){t.btnLoading=!1}))}this.$refs.remark.content=""},handlerSave:function(e){var t=this.$router.resolve({name:"ResumeSave",query:"",params:{id:e}});window.open(t.href,"_blank")}}},f=u,p=(a("d02f"),a("2877")),h=Object(p["a"])(f,n,r,!1,null,"1d3a990a",null);t["default"]=h.exports},2909:function(e,t,a){"use strict";a.d(t,"a",(function(){return l}));var n=a("6b75");function r(e){if(Array.isArray(e))return Object(n["a"])(e)}a("a4d3"),a("e01a"),a("d3b7"),a("d28b"),a("3ca3"),a("ddb0"),a("a630");function o(e){if("undefined"!==typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}var i=a("06c5");a("d9e2");function s(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function l(e){return r(e)||o(e)||Object(i["a"])(e)||s()}},"5ddc":function(e,t,a){"use strict";var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"invitaion_box"},[a("el-form",{ref:"form",attrs:{model:e.form,"label-width":"80px",rules:e.rules,"show-message":!1}},[a("el-form-item",{attrs:{label:"面试形式"}},[a("el-radio-group",{model:{value:e.form.type,callback:function(t){e.$set(e.form,"type",t)},expression:"form.type"}},[a("el-radio",{attrs:{label:1}},[e._v("面试邀请")]),a("el-radio",{attrs:{label:2,disabled:0===e.mySetmeal.enable_video_interview}},[e._v("视频面试")])],1)],1),a("el-form-item",{attrs:{label:"面试人"}},[e._v(" "+e._s(e.apply_fullname)+" ")]),"apply"!=e.from?a("el-form-item",{attrs:{label:"面试职位",prop:"jobid"}},[a("el-select",{attrs:{placeholder:"请选择职位"},model:{value:e.form.jobid,callback:function(t){e.$set(e.form,"jobid",t)},expression:"form.jobid"}},e._l(e.options_jobs,(function(t,n){return a("el-option",{key:n,attrs:{label:t.jobname,value:t.id},nativeOn:{click:function(a){return e.selectJob(t)}}})})),1)],1):e._e(),a("el-form-item",{attrs:{label:"面试日期",prop:"interview_date"}},[a("el-date-picker",{attrs:{type:"date","value-format":"yyyy-MM-dd",placeholder:"请选择面试日期"},model:{value:e.form.interview_date,callback:function(t){e.$set(e.form,"interview_date",t)},expression:"form.interview_date"}})],1),a("el-form-item",{attrs:{label:"面试时间",prop:"interview_time"}},[a("el-time-select",{attrs:{"picker-options":{start:"08:00",step:"00:10",end:"20:00"},placeholder:"选择时间"},model:{value:e.form.interview_time,callback:function(t){e.$set(e.form,"interview_time",t)},expression:"form.interview_time"}})],1),1==e.form.type?a("el-form-item",{attrs:{label:"面试地点",prop:"address"}},[a("el-input",{attrs:{placeholder:"请填写面试地点"},model:{value:e.form.address,callback:function(t){e.$set(e.form,"address",t)},expression:"form.address"}})],1):e._e(),a("el-form-item",{attrs:{label:"联系人",prop:"contact"}},[a("el-input",{attrs:{placeholder:"请填写联系人"},model:{value:e.form.contact,callback:function(t){e.$set(e.form,"contact",t)},expression:"form.contact"}})],1),a("el-form-item",{attrs:{label:"联系电话",prop:"tel"}},[a("el-input",{attrs:{type:"number",placeholder:"请填写联系电话"},model:{value:e.form.tel,callback:function(t){e.$set(e.form,"tel",t)},expression:"form.tel"}})],1),a("el-form-item",{attrs:{label:"备注",prop:"note"}},[a("el-input",{attrs:{placeholder:"请填写备注"},model:{value:e.form.note,callback:function(t){e.$set(e.form,"note",t)},expression:"form.note"}})],1)],1)],1)},r=[],o=(a("d3b7"),a("159b"),a("751a")),i=a("d722"),s={props:["from","apply_id","apply_fullname","resume_id"],data:function(){return{options_jobs:[],form:{type:1,jobid:"",interview_date:"",interview_time:"",address:"",contact:"",tel:"",note:""},mySetmeal:{},rules:{jobid:[{required:!0,message:"请选择面试职位",trigger:"change"}],interview_date:[{required:!0,message:"请选择面试日期",trigger:"change"}],interview_time:[{required:!0,message:"请选择面试时间",trigger:"change"}],address:[{required:!0,message:"请输入面试地址",trigger:"blur"}],contact:[{required:!0,message:"请输入联系人",trigger:"blur"}],tel:[{required:!0,message:"请输入联系电话",trigger:"blur"}]}}},methods:{selectJob:function(e){this.form.address=e.address,0===e.use_company_contact?(this.form.contact=e.job_contact,this.form.tel=e.job_mobile):(this.form.contact=e.company_contact,this.form.tel=e.company_mobile)},initCB:function(){var e=this,t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0;o["a"].get(i["a"].company_published_jobslist,{jobid:t}).then((function(a){if(t>0&&a.data.length>0){var n=a.data[0];e.form.address=n.address,0===n.use_company_contact?(e.form.contact=n.job_contact,e.form.tel=n.job_mobile):(e.form.contact=n.company_contact,e.form.tel=n.company_mobile)}e.options_jobs=[],a.data.forEach((function(t){e.options_jobs.push(t)}))})).catch((function(){})),this.fetchSetmeal()},fetchSetmeal:function(){var e=this;o["a"].get(i["a"].member_setmeal,{}).then((function(t){e.mySetmeal=t.data.info})).catch((function(){}))}}},l=s,c=(a("02e5"),a("2877")),d=Object(c["a"])(l,n,r,!1,null,"5909f535",null);t["a"]=d.exports},"727e":function(e,t,a){},"884d":function(e,t,a){"use strict";var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("ul",{staticClass:"dialog_box"},[a("li",[a("span",[e._v("简历姓名：")]),a("span",{staticClass:"color_1"},[e._v(e._s(e.remark_item.fullname))])]),a("li",[a("span",[e._v("基本信息：")]),a("span",{staticClass:"color_1"},[e._v(e._s(e.remark_item.age)+"岁 | "+e._s(e.remark_item.sex_text)+" | "+e._s(e.remark_item.education_text)+" | "+e._s(e.remark_item.experience_text)+" ")])]),a("li",[a("span",[e._v("备注：")]),e._v(" "),a("el-input",{attrs:{type:"textarea",rows:8,placeholder:"请输入备注内容",maxlength:"50","show-word-limit":""},model:{value:e.content,callback:function(t){e.content=t},expression:"content"}})],1)])},r=[],o={props:["remark_item"],data:function(){return{content:""}},methods:{initCB:function(){this.content=this.remark_item.remark}}},i=o,s=(a("8fd0"),a("2877")),l=Object(s["a"])(i,n,r,!1,null,"35e19ba9",null);t["a"]=l.exports},"8fd0":function(e,t,a){"use strict";a("f8f3")},"91d8":function(e,t,a){"use strict";a("f5d7")},"9dc2":function(e,t,a){"use strict";var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"browsing_wrapper"},[a("p",[e._t("default")],2),e.showWeChatQrcode?a("div",{staticClass:"follow_box",on:{click:e.popupWechatQrcodeWindow}},[a("span",{staticClass:"follow_box_left"}),a("span",{staticClass:"follow_box_right"},[e._v("扫码绑定,随时查收投递信息")])]):e._e(),a("WeChatQrcode",{ref:"weChatQrcodeRef",on:{closeWeChatQrcode:e.closeWeChatQrcode}})],1)},r=[],o=a("751a"),i=a("d722"),s=a("b0b5"),l={components:{WeChatQrcode:s["a"]},data:function(){return{showWeChatQrcode:!1}},mounted:function(){var e=this;o["a"].post(i["a"].member_account,{}).then((function(t){0===parseInt(t.data.bind_weixin)&&(e.showWeChatQrcode=!0)})).catch((function(){}))},methods:{popupWechatQrcodeWindow:function(e){this.$refs.weChatQrcodeRef.handleOpen(e,"扫码绑定，随时随地接收简历信息")},closeWeChatQrcode:function(){this.showWeChatQrcode=!1}}},c=l,d=(a("91d8"),a("2877")),m=Object(d["a"])(c,n,r,!1,null,"4cdcbe84",null);t["a"]=m.exports},a4e8:function(e,t,a){},d02f:function(e,t,a){"use strict";a("a4e8")},f5d7:function(e,t,a){},f8f3:function(e,t,a){}}]);