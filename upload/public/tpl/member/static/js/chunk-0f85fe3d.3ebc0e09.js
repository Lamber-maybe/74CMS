(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0f85fe3d"],{1087:function(t,e,a){"use strict";a("ecfe")},2909:function(t,e,a){"use strict";a.d(e,"a",(function(){return l}));var n=a("6b75");function r(t){if(Array.isArray(t))return Object(n["a"])(t)}a("a4d3"),a("e01a"),a("d3b7"),a("d28b"),a("3ca3"),a("ddb0"),a("a630");function i(t){if("undefined"!==typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}var o=a("06c5");a("d9e2");function s(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function l(t){return r(t)||i(t)||Object(o["a"])(t)||s()}},"35b1":function(t,e,a){},"3a23":function(t,e,a){"use strict";var n=a("9675"),r=a.n(n);e["default"]=r.a},"3b5a":function(t,e,a){},"54ce":function(t,e,a){"use strict";a("da64")},"59ac":function(t,e,a){"use strict";a.d(e,"a",(function(){return n})),a.d(e,"b",(function(){return r}));var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"browsing_wrapper"},[a("p",[t._t("default")],2)])},r=[]},"5ddc":function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"invitaion_box"},[a("el-form",{ref:"form",attrs:{model:t.form,"label-width":"80px",rules:t.rules,"show-message":!1}},[a("el-form-item",{attrs:{label:"面试形式"}},[a("el-radio-group",{model:{value:t.form.type,callback:function(e){t.$set(t.form,"type",e)},expression:"form.type"}},[a("el-radio",{attrs:{label:1}},[t._v("面试邀请")]),a("el-radio",{attrs:{label:2,disabled:0===t.mySetmeal.enable_video_interview}},[t._v("视频面试")])],1)],1),a("el-form-item",{attrs:{label:"面试人"}},[t._v(" "+t._s(t.apply_fullname)+" ")]),"apply"!=t.from?a("el-form-item",{attrs:{label:"面试职位",prop:"jobid"}},[a("el-select",{attrs:{placeholder:"请选择职位"},model:{value:t.form.jobid,callback:function(e){t.$set(t.form,"jobid",e)},expression:"form.jobid"}},t._l(t.options_jobs,(function(t,e){return a("el-option",{key:e,attrs:{label:t.jobname,value:t.id}})})),1)],1):t._e(),a("el-form-item",{attrs:{label:"面试日期",prop:"interview_date"}},[a("el-date-picker",{attrs:{type:"date","value-format":"yyyy-MM-dd",placeholder:"请选择面试日期"},model:{value:t.form.interview_date,callback:function(e){t.$set(t.form,"interview_date",e)},expression:"form.interview_date"}})],1),a("el-form-item",{attrs:{label:"面试时间",prop:"interview_time"}},[a("el-time-select",{attrs:{"picker-options":{start:"08:00",step:"00:10",end:"20:00"},placeholder:"选择时间"},model:{value:t.form.interview_time,callback:function(e){t.$set(t.form,"interview_time",e)},expression:"form.interview_time"}})],1),1==t.form.type?a("el-form-item",{attrs:{label:"面试地点",prop:"address"}},[a("el-input",{attrs:{placeholder:"请填写面试地点"},model:{value:t.form.address,callback:function(e){t.$set(t.form,"address",e)},expression:"form.address"}})],1):t._e(),a("el-form-item",{attrs:{label:"联系人",prop:"contact"}},[a("el-input",{attrs:{placeholder:"请填写联系人"},model:{value:t.form.contact,callback:function(e){t.$set(t.form,"contact",e)},expression:"form.contact"}})],1),a("el-form-item",{attrs:{label:"联系电话",prop:"tel"}},[a("el-input",{attrs:{type:"number",placeholder:"请填写联系电话"},model:{value:t.form.tel,callback:function(e){t.$set(t.form,"tel",e)},expression:"form.tel"}})],1),a("el-form-item",{attrs:{label:"备注",prop:"note"}},[a("el-input",{attrs:{placeholder:"请填写备注"},model:{value:t.form.note,callback:function(e){t.$set(t.form,"note",e)},expression:"form.note"}})],1)],1)],1)},r=[],i=(a("d3b7"),a("159b"),a("751a")),o=a("d722"),s={props:["from","apply_id","apply_fullname","resume_id"],data:function(){return{options_jobs:[],form:{type:1,jobid:"",interview_date:"",interview_time:"",address:"",contact:"",tel:"",note:""},mySetmeal:{},rules:{jobid:[{required:!0,message:"请选择面试职位",trigger:"change"}],interview_date:[{required:!0,message:"请选择面试日期",trigger:"change"}],interview_time:[{required:!0,message:"请选择面试时间",trigger:"change"}],address:[{required:!0,message:"请输入面试地址",trigger:"blur"}],contact:[{required:!0,message:"请输入联系人",trigger:"blur"}],tel:[{required:!0,message:"请输入联系电话",trigger:"blur"}]}}},methods:{initCB:function(){var t=this;i["a"].get(o["a"].company_published_jobslist,{}).then((function(e){t.options_jobs=[],e.data.forEach((function(e){t.options_jobs.push(e)}))})).catch((function(){})),this.fetchSetmeal(),this.fetchIterviewPre()},fetchSetmeal:function(){var t=this;i["a"].get(o["a"].member_setmeal,{}).then((function(e){t.mySetmeal=e.data.info})).catch((function(){}))},fetchIterviewPre:function(){var t=this;i["a"].get(o["a"].interview_add_pre,{}).then((function(e){t.form.address=e.data.address,t.form.contact=e.data.contact,t.form.tel=e.data.tel})).catch((function(){}))}}},l=s,c=(a("bd0b"),a("2877")),u=Object(c["a"])(l,n,r,!1,null,"3320f895",null);e["a"]=u.exports},"884d":function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("ul",{staticClass:"dialog_box"},[a("li",[a("span",[t._v("简历姓名：")]),a("span",{staticClass:"color_1"},[t._v(t._s(t.remark_item.fullname))])]),a("li",[a("span",[t._v("基本信息：")]),a("span",{staticClass:"color_1"},[t._v(t._s(t.remark_item.age)+"岁 | "+t._s(t.remark_item.sex_text)+" | "+t._s(t.remark_item.education_text)+" | "+t._s(t.remark_item.experience_text)+" ")])]),a("li",[a("span",[t._v("备注：")]),t._v(" "),a("el-input",{attrs:{type:"textarea",rows:8,placeholder:"请填写"},model:{value:t.content,callback:function(e){t.content=e},expression:"content"}})],1)])},r=[],i={props:["remark_item"],data:function(){return{content:""}},methods:{initCB:function(){this.content=this.remark_item.remark}}},o=i,s=(a("1087"),a("2877")),l=Object(s["a"])(o,n,r,!1,null,"688dfef4",null);e["a"]=l.exports},"94d6":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("el-card",[a("menuheader",[t._v("收到投递")]),a("div",{staticClass:"resumedetails"},[a("div",{staticClass:"resume_header"},[a("div",[a("span",{staticClass:"resume_header_font"},[t._v("应聘职位：")]),a("el-select",{attrs:{placeholder:"请选择"},on:{change:function(e){return t.doSearch(1,!0)}},model:{value:t.params.jobid,callback:function(e){t.$set(t.params,"jobid",e)},expression:"params.jobid"}},t._l(t.options_job,(function(t,e){return a("el-option",{key:e,attrs:{label:t.text,value:t.value}})})),1),a("span",{staticClass:"resume_header_font resume_header_margin"},[t._v("处理状态：")]),a("el-select",{attrs:{placeholder:"全部"},on:{change:function(e){return t.doSearch(1,!0)}},model:{value:t.params.status,callback:function(e){t.$set(t.params,"status",e)},expression:"params.status"}},t._l(t.options_status,(function(t,e){return a("el-option",{key:e,attrs:{label:t.text,value:t.value}})})),1)],1),a("div",[a("span",{staticClass:"resume_header_font"},[t._v("查看状态：")]),a("el-select",{attrs:{placeholder:"全部"},on:{change:function(e){return t.doSearch(1,!0)}},model:{value:t.params.is_look,callback:function(e){t.$set(t.params,"is_look",e)},expression:"params.is_look"}},t._l(t.options_look,(function(t,e){return a("el-option",{key:e,attrs:{label:t.text,value:t.value}})})),1),a("span",{staticClass:"resume_header_font resume_header_margin"},[t._v("简历来源：")]),a("el-select",{attrs:{placeholder:"全部"},on:{change:function(e){return t.doSearch(1,!0)}},model:{value:t.params.source,callback:function(e){t.$set(t.params,"source",e)},expression:"params.source"}},t._l(t.options_source,(function(t,e){return a("el-option",{key:e,attrs:{label:t.text,value:t.value}})})),1)],1)]),a("div",{staticClass:"resume_list"},[a("div",{staticClass:"resume_handle"},[a("span",{staticClass:"bar"}),t._v("近两周简历处理情况 ")]),a("div",{staticClass:"resume_header_font"},[t._v(" 收到简历："),a("span",[t._v(t._s(t.watchSituation.total))]),t._v(" 待查看简历："),a("span",[t._v(t._s(t.watchSituation.noWatch))]),t._v(" 查看率："),a("span",[t._v(t._s(t.watchSituation.percent))])])])]),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.dataset,"header-cell-style":{background:"#fcfcfc",color:"#b8babd"}}},[a("el-table-column",{attrs:{width:"280","header-align":"left",align:"left",prop:"name",label:"基本信息"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("div",{staticClass:"information_wrapper"},[a("div",{staticClass:"portraitImg"},[a("img",{attrs:{src:e.row.photo_img_src}})]),a("div",{staticClass:"information_list"},[a("div",{staticClass:"fn_bar"},[a("div",{staticClass:"name a-link",on:{click:function(a){return t.handlerLook(e.row)}}},[t._v(t._s(e.row.fullname))]),e.row.high_quality>0?a("div",{staticClass:"hq"}):t._e(),0===parseInt(e.row.is_look)?a("div",{staticClass:"no_look"}):t._e(),a("div",{staticClass:"clear"})]),a("p",{staticClass:"information"},[t._v(" "+t._s(e.row.age)+"岁 | "+t._s(e.row.education_text)+" | "+t._s(e.row.experience_text)+" | "+t._s(e.row.sex_text)+" ")])])])]}}])}),a("el-table-column",{attrs:{label:"应聘职位",align:"center",prop:"jobname"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("a",{staticClass:"stations_fonts a-link",attrs:{href:e.row.job_link_url_web,target:"_blank"}},[t._v(t._s(e.row.jobname))])]}}])}),a("el-table-column",{attrs:{width:"300px","header-align":"left",label:"求职意向"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("p",{staticClass:"intention_post"},[t._v(" 想找 "),a("span",[t._v(t._s(e.row.intention_jobs))]),t._v(" 工作 ")]),a("p",{staticClass:"intention_local"},[t._v(" 想在 "),a("span",[t._v(t._s(e.row.intention_district))]),t._v(" 工作 ")])]}}])}),a("el-table-column",{attrs:{"header-align":"center",align:"center",width:"200px",label:"应聘时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("p",{staticClass:"time"},[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),a("el-table-column",{attrs:{"header-align":"center",align:"center",label:"操作状态"},scopedSlots:t._u([{key:"default",fn:function(e){return[0==e.row.handle_status?a("div",{staticClass:"btnsColor1 btn",attrs:{type:"warning",size:"mini"}},[t._v(" 待处理 ")]):t._e(),1==e.row.handle_status?a("div",{staticClass:"btnsColor2 btn",attrs:{type:"primary",size:"mini"}},[t._v(" 已同意 ")]):t._e(),2==e.row.handle_status?a("div",{staticClass:"btnsColor3 btn",attrs:{type:"danger",size:"mini"}},[t._v(" 已婉拒 ")]):t._e()]}}])}),a("el-table-column",{attrs:{"header-align":"center",align:"right",width:"380",label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[0==e.row.handle_status?a("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(a){return a.stopPropagation(),t.handlerAgree(e.row)}}},[t._v("同意")]):t._e(),0==e.row.handle_status?a("el-button",{attrs:{size:"mini",type:"danger"},on:{click:function(a){return t.handlerRefuse(e.row)}}},[t._v("婉拒")]):t._e(),a("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(a){return t.handlerRemark(e.row)}}},[t._v("备注")]),a("el-button",{attrs:{size:"mini",type:"warning"},on:{click:function(a){return t.handlerSave(e.row.resume_id)}}},[t._v("保存到电脑")]),a("el-button",{attrs:{size:"mini"},on:{click:function(a){return t.handlerDel(e.row)}}},[t._v("删除")])]}}])})],1),a("pagination",{attrs:{total:t.total,"current-page":t.params.page,"page-size":t.params.pagesize},on:{handleCurrentChange:t.doSearch}}),a("el-dialog",{attrs:{width:"540px",title:"面试邀请",visible:t.showInvite},on:{"update:visible":function(e){t.showInvite=e},opened:t.handlerInviteOpened,closed:t.handlerInviteClose}},[a("Invite",{ref:"invite",attrs:{from:"apply",apply_id:t.apply_id,apply_fullname:t.apply_fullname}}),a("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{attrs:{type:"primary",loading:t.btnLoading},on:{click:function(e){return t.handlerInviteClose(!0)}}},[t._v("确 定")]),a("el-button",{on:{click:t.handlerInviteClose}},[t._v("取 消")])],1)],1),a("el-dialog",{attrs:{width:"540px",title:"填写备注",visible:t.showRemark},on:{"update:visible":function(e){t.showRemark=e},opened:t.handlerRemarkOpened,closed:t.handlerRemarkClose}},[a("Remark",{ref:"remark",attrs:{remark_item:t.remark_item}}),a("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{attrs:{type:"primary",loading:t.btnLoading},on:{click:function(e){return t.handlerRemarkClose(!0)}}},[t._v("确 定")]),a("el-button",{on:{click:t.handlerRemarkClose}},[t._v("取 消")])],1)],1)],1)},r=[],i=a("2909"),o=(a("d3b7"),a("159b"),a("ed08")),s=a("751a"),l=a("d722"),c=a("9dc2"),u=a("884d"),d=a("5ddc"),f={components:{menuheader:c["default"],Remark:u["a"],Invite:d["a"]},filters:{timeFilter:function(t){return Object(o["c"])(t,"{y}-{m}-{d}",!0)}},data:function(){return{btnLoading:!1,showInvite:!1,apply_id:0,apply_fullname:"",showRemark:!1,remark_item:{},listLoading:!1,dataset:[],total:0,params:{jobid:0,status:"",source:"",is_look:"",page:1,pagesize:10},watchSituation:{total:0,noWatch:0,percent:"100%"},options_job:[{text:"全部",value:0}],options_status:[{text:"全部",value:""},{text:"待处理",value:0},{text:"已同意",value:1},{text:"已拒绝",value:2}],options_look:[{text:"全部",value:""},{text:"未查看",value:0},{text:"已查看",value:1}],options_source:[{text:"全部",value:""},{text:"自主投递",value:0},{text:"委托投递",value:1}]}},created:function(){this.fetchData(!0)},methods:{fetchData:function(t){var e=this;this.listLoading=!0,s["a"].get(l["a"].company_jobapply_list,this.params).then((function(a){e.options_job=[{text:"全部",value:0}];var n=Object(i["a"])(a.data.option_jobs);n.forEach((function(t){e.options_job.push({text:t.jobname,value:t.id})})),e.dataset=Object(i["a"])(a.data.items),e.listLoading=!1,!0===t&&(e.fetchTotal(),e.fetchWatchSituation())})).catch((function(){}))},fetchTotal:function(){var t=this;s["a"].get(l["a"].company_jobapply_list_total,this.params).then((function(e){t.total=e.data})).catch((function(){}))},fetchWatchSituation:function(){var t=this;s["a"].get(l["a"].company_jobapply_watch_situation,{}).then((function(e){t.watchSituation={total:e.data.total,noWatch:e.data.noWatch,percent:e.data.watch_percent}})).catch((function(){}))},doSearch:function(t,e){this.params.page=t,this.fetchData(),!0===e&&this.fetchTotal()},handlerAgree:function(t){var e=this;if(1!=t.audit)return this.$message.error("该简历尚未通过审核，不能继续此操作"),!1;this.btnLoading=!0,s["a"].post(l["a"].company_jobapply_set_agree_pre,{id:t.id}).then((function(a){e.btnLoading=!1,1===a.data.finish?(e.fetchData(!0),e.$message({type:"success",message:"简历处理成功"})):e.openAddInvitation(t)})).catch((function(){}))},openAddInvitation:function(t){this.apply_id=t.id,this.apply_fullname=t.fullname,this.showInvite=!0},handlerInviteOpened:function(){this.$refs.invite.initCB()},handlerInviteClose:function(t){var e=this;!0===t?(this.btnLoading=!0,this.$refs.invite.$refs.form.validate((function(t){if(!t)return e.btnLoading=!1,!1;var a=e.$refs.invite.form;a.apply_id=e.apply_id,s["a"].post(l["a"].company_jobapply_set_agree,a).then((function(){e.btnLoading=!1,e.fetchData(!0),e.$message({type:"success",message:"邀请面试成功"}),e.showInvite=!1})).catch((function(){e.btnLoading=!1}))}))):this.showInvite=!1},handlerRefuse:function(t){var e=this;if(1!=t.audit)return this.$message.error("该简历尚未通过审核，不能继续此操作"),!1;this.$confirm("确定将处理状态更改为【已婉拒】吗？","系统提示",{type:"warning"}).then((function(){s["a"].post(l["a"].company_jobapply_set_refuse,{id:t.id}).then((function(){e.$message({type:"success",message:"简历处理成功"}),e.fetchData(!0)})).catch((function(){}))})).catch((function(){}))},handlerDel:function(t){var e=this;this.$confirm("确定删除该简历吗？","系统提示",{type:"warning"}).then((function(){s["a"].post(l["a"].company_jobapply_del,{id:t.id}).then((function(){e.$message({type:"success",message:"简历删除成功"}),e.fetchData(!0)})).catch((function(){}))})).catch((function(){}))},handlerLook:function(t){0===t.is_look?(t.is_look=1,s["a"].post(l["a"].company_jobapply_set_looked,{id:t.id}).then((function(){window.open(t.resume_link_url_web)})).catch((function(){}))):window.open(t.resume_link_url_web)},handlerRemark:function(t){this.showRemark=!0,this.remark_item=t},handlerRemarkOpened:function(){this.$refs.remark.initCB()},handlerRemarkClose:function(t){var e=this;if(this.showRemark=!1,!0===t){this.btnLoading=!0;var a={remark:this.$refs.remark.content,resume_id:this.remark_item.resume_id};this.remark_item.remark=this.$refs.remark.content,s["a"].post(l["a"].remark_resume,a).then((function(){e.btnLoading=!1,e.showRemark=!1})).catch((function(){e.btnLoading=!1}))}this.$refs.remark.content=""},handlerSave:function(t){var e=this.$router.resolve({name:"ResumeSave",query:"",params:{id:t}});window.open(e.href,"_blank")}}},p=f,m=(a("9b9b"),a("2877")),_=Object(m["a"])(p,n,r,!1,null,"5df36830",null);e["default"]=_.exports},9675:function(t,e){},"9b9b":function(t,e,a){"use strict";a("35b1")},"9dc2":function(t,e,a){"use strict";var n=a("59ac"),r=a("3a23"),i=(a("54ce"),a("2877")),o=Object(i["a"])(r["default"],n["a"],n["b"],!1,null,"014ad7c1",null);e["default"]=o.exports},bd0b:function(t,e,a){"use strict";a("3b5a")},da64:function(t,e,a){},ecfe:function(t,e,a){}}]);