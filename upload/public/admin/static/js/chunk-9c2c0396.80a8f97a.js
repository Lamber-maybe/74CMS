(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-9c2c0396"],{"0ccb":function(e,t,r){var a=r("50c4"),n=r("1148"),o=r("1d80"),l=Math.ceil,i=function(e){return function(t,r,i){var s,c,u=String(o(t)),f=u.length,m=void 0===i?" ":String(i),d=a(r);return d<=f||""==m?u:(s=d-f,c=n.call(m,l(s/m.length)),c.length>s&&(c=c.slice(0,s)),e?u+c:c+u)}};e.exports={start:i(!1),end:i(!0)}},"10ab":function(e,t,r){},1148:function(e,t,r){"use strict";var a=r("a691"),n=r("1d80");e.exports="".repeat||function(e){var t=String(n(this)),r="",o=a(e);if(o<0||o==1/0)throw RangeError("Wrong number of repetitions");for(;o>0;(o>>>=1)&&(t+=t))1&o&&(r+=t);return r}},"15fd":function(e,t,r){"use strict";r.d(t,"a",(function(){return n}));r("a4d3"),r("c975"),r("b64b");function a(e,t){if(null==e)return{};var r,a,n={},o=Object.keys(e);for(a=0;a<o.length;a++)r=o[a],t.indexOf(r)>=0||(n[r]=e[r]);return n}function n(e,t){if(null==e)return{};var r,n,o=a(e,t);if(Object.getOwnPropertySymbols){var l=Object.getOwnPropertySymbols(e);for(n=0;n<l.length;n++)r=l[n],t.indexOf(r)>=0||Object.prototype.propertyIsEnumerable.call(e,r)&&(o[r]=e[r])}return o}},3176:function(e,t,r){"use strict";r.r(t);var a=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-tabs",{attrs:{type:"border-card"}},[r("el-tab-pane",{attrs:{label:"基本设置",lazy:!0}},[r("config-basic")],1),r("el-tab-pane",{attrs:{label:"套餐管理",lazy:!0}},[r("config-setmeallist")],1)],1)],1)},n=[],o=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[e._m(0),r("el-divider",{attrs:{"content-position":"left"}},[e._v("简历下载点数配置")]),r("el-form",{staticClass:"common-form",staticStyle:{"margin-bottom":"80px"},attrs:{"label-width":"200px"}},[e._l(e.resume_download_points_conf,(function(t,a){return r("el-form-item",{key:a,attrs:{label:t.name}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"1"},on:{blur:function(r){return e.handle_val(t)},change:function(r){return e.handle_val(t)}},model:{value:t.value,callback:function(r){e.$set(t,"value",r)},expression:"item.value"}}),e._v(" 点 ")],1)})),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form1")}}},[e._v("保存")])],1)],2),r("el-divider",{attrs:{"content-position":"left"}},[e._v(" 优质简历下载点数配置 "),r("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:"优质简历不按上方点数配置影响",placement:"top-start"}},[r("i",{staticClass:"el-icon-info"})])],1),r("el-form",{staticClass:"common-form",attrs:{"label-width":"200px"}},[r("el-form-item",{attrs:{label:"下载优质简历所需"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"1"},on:{blur:function(t){return e.handle_val2()},change:function(t){return e.handle_val2()}},model:{value:e.resume_download_points_talent,callback:function(t){e.resume_download_points_talent=t},expression:"resume_download_points_talent"}}),e._v(" 点 ")],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form2")}}},[e._v("保存")])],1)],1)],1)},l=[function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"tip"},[r("p",[e._v(" 不同的运营阶段您可以选择不同的设置。 ")])])}],i=r("1063"),s={data:function(){return{resume_download_points_conf:[],resume_download_points_talent:1}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var e=this;this.infoLoading=!0,Object(i["j"])({},"get").then((function(t){e.resume_download_points_conf=t.data.resume_download_points_conf,e.resume_download_points_talent=t.data.resume_download_points_talent})).catch((function(){}))},onSubmit:function(e){var t=this,r={};"form1"==e?r.resume_download_points_conf=this.resume_download_points_conf:r.resume_download_points_talent=this.resume_download_points_talent,Object(i["j"])(r).then((function(e){return t.$store.dispatch("config/getConfigInfo"),t.$message.success(e.message),!0})).catch((function(){}))},handle_val:function(e){(""==e.value||parseInt(e.value)<1)&&(e.value=1)},handle_val2:function(){(""==this.resume_download_points_talent||parseInt(this.resume_download_points_talent)<1)&&(this.resume_download_points_talent=1)}}},c=s,u=(r("cf34"),r("2877")),f=Object(u["a"])(c,o,l,!1,null,"fc6edc10",null),m=f.exports,d=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[e._m(0),r("el-form",{ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"200px",rules:e.rules,"inline-message":!0}},[r("el-form-item",{attrs:{label:"新注册会员赠送服务",required:""}},[r("el-radio-group",{model:{value:e.form.reg_service,callback:function(t){e.$set(e.form,"reg_service",t)},expression:"form.reg_service"}},e._l(e.list,(function(t,a){return r("el-radio",{key:a,attrs:{label:t.id}},[e._v(" "+e._s(t.name)+" ")])})),1)],1),r("el-form-item",{attrs:{label:"套餐到期前提醒",prop:"meal_min_remind"}},[r("el-input",{staticClass:"small",model:{value:e.form.meal_min_remind,callback:function(t){e.$set(e.form,"meal_min_remind",t)},expression:"form.meal_min_remind"}},[r("template",{slot:"prepend"},[e._v("提前")]),r("template",{slot:"append"},[e._v("天")])],2),r("span",{staticClass:"smalltip"},[r("i",{staticClass:"el-icon-info"}),e._v(" 0为不提醒 ")])],1),r("el-form-item",{attrs:{label:"套餐到期使用权限"}},[r("span",{staticStyle:{color:"#3a8ee6",cursor:"pointer"},on:{click:e.fun_config_overtime}},[e._v(" [配置使用权限] ")])]),r("el-form-item",{attrs:{label:"名企套餐"}},[r("el-checkbox-group",{model:{value:e.form.famous_enterprises,callback:function(t){e.$set(e.form,"famous_enterprises",t)},expression:"form.famous_enterprises"}},e._l(e.list,(function(t,a){return r("el-checkbox",{key:a,attrs:{label:t.id}},[e._v(e._s(t.name))])})),1)],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")])],1)],1),r("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.listLoading,expression:"listLoading"}],staticStyle:{width:"100%"},attrs:{"element-loading-text":"Loading",fit:"","highlight-current-row":"",data:e.list}},[r("el-table-column",{attrs:{prop:"name",label:"套餐名称",width:"180"}}),r("el-table-column",{attrs:{label:"价格"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v(" "+e._s(0==t.row.expense?"免费":t.row.expense)+" ")]}}])}),r("el-table-column",{attrs:{label:"限时优惠"},scopedSlots:e._u([{key:"default",fn:function(t){return[0==t.row.preferential_open?r("span",[e._v("无")]):r("span",[e._v(" "+e._s(t.row.preferential_expense)+" "),r("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:e.timeFilter(t.row.preferential_expense_start)+" 至 "+e.timeFilter(t.row.preferential_expense_end),placement:"top-start"}},[r("i",{staticClass:"el-icon-time"})])],1)]}}])}),r("el-table-column",{attrs:{label:"时长"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v(" "+e._s(0==t.row.days?"无限期":t.row.days+"天")+" ")]}}])}),r("el-table-column",{attrs:{label:"内容",width:"300"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v(" 简历点 "+e._s(t.row.download_resume_point)+"；赠积分 "+e._s(t.row.gift_point)+"； "),r("span",{staticStyle:{cursor:"pointer",color:"#3a8ee6","font-size":"12px"},on:{click:function(r){return e.fun_detail(t.row)}}},[e._v(" [查看全部] ")])]}}])}),r("el-table-column",{attrs:{label:"推荐"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-tag",{attrs:{type:e._f("colorFilter")(t.row.recommend)}},[e._v(" "+e._s(1==t.row.recommend?"是":"否")+" ")])]}}])}),r("el-table-column",{attrs:{label:"排序"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("span",[e._v(e._s(t.row.sort_id))])]}}])}),r("el-table-column",{attrs:{label:"显示状态"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-tag",{attrs:{type:e._f("colorFilter")(t.row.is_display)}},[e._v(" "+e._s(1==t.row.is_display?"显示":"隐藏")+" ")])]}}])}),r("el-table-column",{attrs:{label:"会员申请"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-tag",{attrs:{type:e._f("colorFilter")(t.row.is_apply)}},[e._v(" "+e._s(1==t.row.is_apply?"允许":"不允许")+" ")])]}}])}),r("el-table-column",{attrs:{fixed:"right",label:"操作",width:"220"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(r){return e.funEdit(t.$index,t.row)}}},[e._v(" 编辑 ")]),r("el-button",{attrs:{size:"small",type:"danger",disabled:e.form.reg_service==t.row.id},on:{click:function(r){return e.funDelete(t.$index,t.row)}}},[e._v(" 删除 ")])]}}])})],1),r("div",{staticClass:"spaceline"}),r("el-button",{attrs:{size:"small",type:"primary"},on:{click:e.funAdd}},[e._v("添加套餐")]),e.dialogFormVisible?r("el-dialog",{attrs:{title:e.dialogTitle,visible:e.dialogFormVisible,width:e.dialog_width,"close-on-click-modal":!1},on:{"update:visible":function(t){e.dialogFormVisible=t}}},["overtime"==e.dialog_type?r("overtimeform",{on:{setDialogFormVisible:e.closeDialog}}):e._e(),"form"==e.dialog_type?r("diaform",{attrs:{id:e.childId},on:{setDialogFormVisible:e.closeDialog,pageReload:e.fetchSetmallistData}}):e._e(),"detail"==e.dialog_type?r("div",[r("el-row",[r("el-col",{attrs:{span:24}},[e._v(" 简历点： "),r("b",[e._v(e._s(e.detailContent.download_resume_point))])])],1),r("el-row",[r("el-col",{attrs:{span:24}},[e._v(" 赠送积分： "),r("b",[e._v(e._s(e.detailContent.gift_point))])])],1),r("el-row",[r("el-col",{attrs:{span:24}},[e._v(" 在招职位： "),r("b",[e._v(e._s(e.detailContent.jobs_meanwhile))])])],1),r("el-row",[r("el-col",{attrs:{span:24}},[e._v(" 免费刷新职位次数： "),r("b",[e._v(e._s(e.detailContent.refresh_jobs_free_perday))]),e._v(" 次/天 ")])],1),r("el-row",[r("el-col",{attrs:{span:24}},[e._v(" 下载简历数上限： "),r("b",[e._v(e._s(e.detailContent.download_resume_max_perday))]),e._v(" 份/天 ")])],1),r("el-row",[r("el-col",{attrs:{span:24}},[e._v(" 增值服务折扣： "),r("b",[e._v(e._s(e.detailContent.service_added_discount))]),e._v(" 折 ")])],1),r("el-row",[r("el-col",{attrs:{span:24}},[e._v(" 视频面试： "),r("b",[e._v(" "+e._s(1==e.detailContent.enable_video_interview?"可用":"不可用")+" ")])])],1),r("el-row",[r("el-col",{attrs:{span:24}},[e._v(" 微海报： "),r("b",[e._v(e._s(1==e.detailContent.enable_poster?"可用":"不可用"))])])],1),r("el-row",[r("el-col",{attrs:{span:24}},[e._v(" 收到简历免费查看： "),r("b",[e._v(e._s(1==e.detailContent.show_apply_contact?"是":"否"))])])],1)],1):e._e()],1):e._e()],1)},p=[function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"tip"},[r("p",[e._v(" 不同的运营阶段您可以选择不同的设置。 ")]),r("p",[e._v(" 套餐到期后，套餐类型显示已过期，会员权限按套餐到期使用权限执行。套餐到期后会员资源不改变，无有效期限制。 ")])])}],_=(r("a15b"),r("ac1f"),r("1276"),r("b85c")),b=r("5530"),g=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"32%",rules:e.rules,"inline-message":!1,size:"small",inline:!1}},[r("el-row",[r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"套餐名称",prop:"name"}},[r("el-input",{staticClass:"small",model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1)],1),r("el-col",{attrs:{span:2}}),r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"使用视频面试"}},[r("el-radio-group",{model:{value:e.form.enable_video_interview,callback:function(t){e.$set(e.form,"enable_video_interview",t)},expression:"form.enable_video_interview"}},[r("el-radio",{attrs:{label:1}},[e._v("允许")]),r("el-radio",{attrs:{label:0}},[e._v("不允许")])],1)],1)],1)],1),r("el-row",[r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"价格",prop:"expense"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"expense")}},model:{value:e.form.expense,callback:function(t){e.$set(e.form,"expense",e._n(t))},expression:"form.expense"}},[r("template",{slot:"append"},[e._v("元")])],2)],1)],1),r("el-col",{attrs:{span:2}}),r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"使用微海报"}},[r("el-radio-group",{model:{value:e.form.enable_poster,callback:function(t){e.$set(e.form,"enable_poster",t)},expression:"form.enable_poster"}},[r("el-radio",{attrs:{label:1}},[e._v("允许")]),r("el-radio",{attrs:{label:0}},[e._v("不允许")])],1)],1)],1)],1),r("el-row",[r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"时长",prop:"days"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"days")}},model:{value:e.form.days,callback:function(t){e.$set(e.form,"days",e._n(t))},expression:"form.days"}},[r("template",{slot:"append"},[e._v("天")])],2),r("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:"0表示无限期",placement:"top-start"}},[r("i",{staticClass:"el-icon-info"})])],1)],1),r("el-col",{attrs:{span:2}}),r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"收到简历免费查看"}},[r("el-radio-group",{model:{value:e.form.show_apply_contact,callback:function(t){e.$set(e.form,"show_apply_contact",t)},expression:"form.show_apply_contact"}},[r("el-radio",{attrs:{label:1}},[e._v("允许")]),r("el-radio",{attrs:{label:0}},[e._v("不允许")])],1)],1)],1)],1),r("el-row",[r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"开启限时特惠"}},[r("el-radio-group",{on:{change:e.fun_change_preferential_expense},model:{value:e.form.preferential_open,callback:function(t){e.$set(e.form,"preferential_open",t)},expression:"form.preferential_open"}},[r("el-radio",{attrs:{label:1}},[e._v("开启")]),r("el-radio",{attrs:{label:0}},[e._v("关闭")])],1)],1)],1),r("el-col",{attrs:{span:2}}),r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"是否推荐"}},[r("el-radio-group",{model:{value:e.form.recommend,callback:function(t){e.$set(e.form,"recommend",t)},expression:"form.recommend"}},[r("el-radio",{attrs:{label:1}},[e._v("推荐")]),r("el-radio",{attrs:{label:0}},[e._v("不推荐")])],1)],1)],1)],1),r("el-row",[r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"特惠购买价",prop:"preferential_expense"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0",disabled:0==e.form.preferential_open},on:{blur:function(t){return e.format_number(0,"preferential_expense")}},model:{value:e.form.preferential_expense,callback:function(t){e.$set(e.form,"preferential_expense",e._n(t))},expression:"form.preferential_expense"}},[r("template",{slot:"append"},[e._v("元")])],2)],1)],1),r("el-col",{attrs:{span:2}}),r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"允许申请"}},[r("el-radio-group",{model:{value:e.form.is_apply,callback:function(t){e.$set(e.form,"is_apply",t)},expression:"form.is_apply"}},[r("el-radio",{attrs:{label:1}},[e._v("允许")]),r("el-radio",{attrs:{label:0}},[e._v("不允许")])],1)],1)],1)],1),r("el-row",[r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"特惠时间",prop:"preferential_expense_timerange"}},[r("el-date-picker",{attrs:{type:"daterange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期","value-format":"yyyy-MM-dd",disabled:0==e.form.preferential_open},model:{value:e.form.preferential_expense_timerange,callback:function(t){e.$set(e.form,"preferential_expense_timerange",t)},expression:"form.preferential_expense_timerange"}})],1)],1),r("el-col",{attrs:{span:2}}),r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"是否显示"}},[r("el-radio-group",{model:{value:e.form.is_display,callback:function(t){e.$set(e.form,"is_display",t)},expression:"form.is_display"}},[r("el-radio",{attrs:{label:1}},[e._v("显示")]),r("el-radio",{attrs:{label:0}},[e._v("隐藏")])],1)],1)],1)],1),r("el-row",[r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"下载简历点数",prop:"download_resume_point"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"download_resume_point")}},model:{value:e.form.download_resume_point,callback:function(t){e.$set(e.form,"download_resume_point",e._n(t))},expression:"form.download_resume_point"}})],1)],1),r("el-col",{attrs:{span:2}}),r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"下载简历上限",prop:"download_resume_max_perday"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"download_resume_max_perday")}},model:{value:e.form.download_resume_max_perday,callback:function(t){e.$set(e.form,"download_resume_max_perday",e._n(t))},expression:"form.download_resume_max_perday"}},[r("template",{slot:"append"},[e._v("份 / 天")])],2)],1)],1)],1),r("el-row",[r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"赠送积分数",prop:"gift_point"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"gift_point")}},model:{value:e.form.gift_point,callback:function(t){e.$set(e.form,"gift_point",e._n(t))},expression:"form.gift_point"}})],1)],1),r("el-col",{attrs:{span:2}}),r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"免费刷新职位",prop:"refresh_jobs_free_perday"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"refresh_jobs_free_perday")}},model:{value:e.form.refresh_jobs_free_perday,callback:function(t){e.$set(e.form,"refresh_jobs_free_perday",e._n(t))},expression:"form.refresh_jobs_free_perday"}},[r("template",{slot:"append"},[e._v("次 / 天")])],2)],1)],1)],1),r("el-row",[r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"同时在招职位数",prop:"jobs_meanwhile"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"jobs_meanwhile")}},model:{value:e.form.jobs_meanwhile,callback:function(t){e.$set(e.form,"jobs_meanwhile",e._n(t))},expression:"form.jobs_meanwhile"}})],1)],1),r("el-col",{attrs:{span:2}}),r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:"套餐增值包折扣",prop:"service_added_discount"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"service_added_discount")}},model:{value:e.form.service_added_discount,callback:function(t){e.$set(e.form,"service_added_discount",e._n(t))},expression:"form.service_added_discount"}}),r("el-tooltip",{staticClass:"item",attrs:{effect:"dark",placement:"top-start"}},[r("div",{attrs:{slot:"content"},slot:"content"},[e._v(" 0表示无折扣 "),r("br"),e._v(" 例：七五折请填写7.5 ")]),r("i",{staticClass:"el-icon-info"})])],1)],1)],1),r("el-row",[r("el-col",{attrs:{span:11}},[r("el-form-item",{staticStyle:{display:"block"},attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{staticClass:"mini",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"sort_id")}},model:{value:e.form.sort_id,callback:function(t){e.$set(e.form,"sort_id",e._n(t))},expression:"form.sort_id"}}),r("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:"数字越大越靠前",placement:"top-start"}},[r("i",{staticClass:"el-icon-info"})])],1)],1),r("el-col",{attrs:{span:2}}),r("el-col",{attrs:{span:11}},[r("el-form-item",{staticStyle:{display:"block"},attrs:{label:"图标",prop:"icon"}},[r("el-upload",{staticClass:"icon-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handleIconSuccess,"before-upload":e.beforeIconUpload}},[e.form.icon?r("img",{staticClass:"icon",attrs:{src:e.iconUrl}}):r("i",{staticClass:"el-icon-plus icon-uploader-icon"})]),r("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:"建议尺寸28*28",placement:"top-start"}},[r("i",{staticClass:"el-icon-info"})]),r("el-button",{attrs:{type:"text"},on:{click:e.delIcon}},[e._v("[删除]")])],1)],1)],1),r("el-row",[r("el-col",{attrs:{span:11}},[r("el-form-item",{attrs:{label:" "}},[r("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),r("el-button",{on:{click:e.closeDialog}},[e._v("取 消")])],1)],1)],1)],1)],1)},v=[],h=(r("caad"),r("d3b7"),r("2532"),r("3ca3"),r("ddb0"),r("2b3d"),r("15fd")),y=r("b775"),w=r("d722");function x(e){return Object(y["a"])({url:w["a"].setmealList,method:"get",params:e})}function j(e){return Object(y["a"])({url:w["a"].setmealAdd,method:"post",data:e})}function k(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==t?Object(y["a"])({url:w["a"].setmealEdit,method:t,data:e}):Object(y["a"])({url:w["a"].setmealEdit,method:t,params:e})}function $(e){return Object(y["a"])({url:w["a"].setmealDelete,method:"post",data:e})}var C=r("5f87"),S={props:["id"],data:function(){var e=this,t=function(t,r,a){1==e.form.preferential_open&&0==r.length&&a(new Error("请选择特惠时间")),a()};return{infoLoading:!1,headers:{admintoken:Object(C["b"])()},fileupload_size:"",fileupload_ext:"",apiUpload:window.global.RequestBaseUrl+w["a"].upload,form:{preferential_open:0,preferential_expense_timerange:[],id:0,name:"",is_display:1,is_apply:1,days:0,expense:0,preferential_expense:0,service_added_discount:0,jobs_meanwhile:0,refresh_jobs_free_perday:0,download_resume_point:0,download_resume_max_perday:0,enable_video_interview:1,enable_poster:1,recommend:1,gift_point:0,show_apply_contact:1,sort_id:0,preferential_expense_start:"",preferential_expense_end:"",icon:0},iconUrl:"",rules:{name:[{required:!0,message:"请填写套餐名称",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"}],expense:[{required:!0,message:"请填写价格",trigger:"blur"}],days:[{required:!0,message:"请填写时长",trigger:"blur"},{type:"number",message:"时长只能填写数字",trigger:"blur"}],preferential_expense_timerange:[{validator:t,trigger:"blur"}],download_resume_point:[{required:!0,message:"请填写下载简历点数",trigger:"blur"},{type:"number",message:"下载简历点数只能填写数字",trigger:"blur"}],gift_point:[{required:!0,message:"请填写赠送积分数",trigger:"blur"},{type:"number",message:"赠送积分数只能填写数字",trigger:"blur"}],jobs_meanwhile:[{required:!0,message:"请填写同时在招职位数",trigger:"blur"},{type:"number",message:"同时在招职位数只能填写数字",trigger:"blur"}],download_resume_max_perday:[{required:!0,message:"请填写下载简历数上限",trigger:"blur"},{type:"number",message:"下载简历数上限只能填写数字",trigger:"blur"}],refresh_jobs_free_perday:[{required:!0,message:"请填写免费刷新职位次数",trigger:"blur"},{type:"number",message:"免费刷新职位次数只能填写数字",trigger:"blur"}],service_added_discount:[{required:!0,message:"请填写套餐增值包折扣",trigger:"blur"},{type:"number",message:"套餐增值包折扣只能填写数字",trigger:"blur"}],sort_id:[{required:!0,message:"请填写排序",trigger:"blur"},{type:"number",message:"排序只能填写数字",trigger:"blur"}]}}},computed:{config:function(){return this.$store.state.config}},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext,this.fetchInfo()},methods:{fetchInfo:function(){var e=this,t=this.id;if(t>0){var r={id:t};k(r,"get").then((function(t){Object.assign(e.form,Object(b["a"])({},t.data.info)),0!=t.data.info.preferential_expense_start&&0!=t.data.info.preferential_expense_end?e.form.preferential_expense_timerange=[t.data.info.preferential_expense_start,t.data.info.preferential_expense_end]:e.form.preferential_expense_timerange=[],e.iconUrl=t.data.iconUrl,e.infoLoading=!1})).catch((function(){}))}else this.infoLoading=!1},addSave:function(e,t){var r=this;this.$refs[t].validate((function(t){if(!t)return!1;j(e).then((function(e){return r.$message.success(e.message),r.closeDialog(),r.pageReload(),!0})).catch((function(){}))}))},editSave:function(e,t){var r=this;this.$refs[t].validate((function(t){if(!t)return!1;k(e).then((function(e){return r.$message.success(e.message),r.closeDialog(),r.pageReload(),!0})).catch((function(){}))}))},onSubmit:function(e){var t=this,r=Object(b["a"])({},t.form);if(0!=r.preferential_expense_timerange.length?(r.preferential_expense_start=r.preferential_expense_timerange[0],r.preferential_expense_end=r.preferential_expense_timerange[1]):(r.preferential_expense_start=0,r.preferential_expense_end=0),parseInt(r.id)>0)t.editSave(r,e);else{var a=r.id,n=Object(h["a"])(r,["id"]);console.log(a),t.addSave(n,e)}},closeDialog:function(){this.$emit("setDialogFormVisible")},pageReload:function(){this.$emit("pageReload")},format_number:function(e,t){(""==this.form[t]||parseInt(this.form[t])<e)&&(this.form[t]=e)},fun_change_preferential_expense:function(e){0==e&&(this.form.preferential_expense_timerange=[],this.form.preferential_expense=0)},handleIconSuccess:function(e,t){if(200!=e.code)return this.$message.error(e.message),!1;this.iconUrl=URL.createObjectURL(t.raw),this.form.icon=e.data.file_id},beforeIconUpload:function(e){var t=e.type.split("/"),r=t[1],a=this.fileupload_ext.split(",");return a.includes(r)?!(e.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)},delIcon:function(){this.form.icon=0,this.iconUrl=""}}},I=S,O=(r("e048"),Object(u["a"])(I,g,v,!1,null,"2edc5676",null)),D=O.exports,E=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"32%",rules:e.rules,"inline-message":!1,size:"small",inline:!1}},[r("el-form-item",{attrs:{label:"同时在招职位数",prop:"jobs_meanwhile"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"jobs_meanwhile")}},model:{value:e.form.jobs_meanwhile,callback:function(t){e.$set(e.form,"jobs_meanwhile",e._n(t))},expression:"form.jobs_meanwhile"}})],1),r("el-form-item",{attrs:{label:"免费刷新职位",prop:"refresh_jobs_free_perday"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"refresh_jobs_free_perday")}},model:{value:e.form.refresh_jobs_free_perday,callback:function(t){e.$set(e.form,"refresh_jobs_free_perday",e._n(t))},expression:"form.refresh_jobs_free_perday"}},[r("template",{slot:"append"},[e._v("次 / 天")])],2)],1),r("el-form-item",{attrs:{label:"下载简历上限",prop:"download_resume_max_perday"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"download_resume_max_perday")}},model:{value:e.form.download_resume_max_perday,callback:function(t){e.$set(e.form,"download_resume_max_perday",e._n(t))},expression:"form.download_resume_max_perday"}},[r("template",{slot:"append"},[e._v("份 / 天")])],2)],1),r("el-form-item",{attrs:{label:"套餐增值包折扣",prop:"service_added_discount"}},[r("el-input",{staticClass:"small",attrs:{type:"number",min:"0"},on:{blur:function(t){return e.format_number(0,"service_added_discount")}},model:{value:e.form.service_added_discount,callback:function(t){e.$set(e.form,"service_added_discount",e._n(t))},expression:"form.service_added_discount"}}),r("el-tooltip",{staticClass:"item",attrs:{effect:"dark",placement:"top-start"}},[r("div",{attrs:{slot:"content"},slot:"content"},[e._v(" 0表示无折扣 "),r("br"),e._v(" 例：七五折请填写7.5 ")]),r("i",{staticClass:"el-icon-info"})])],1),r("el-form-item",{attrs:{label:"使用视频面试"}},[r("el-radio-group",{model:{value:e.form.enable_video_interview,callback:function(t){e.$set(e.form,"enable_video_interview",t)},expression:"form.enable_video_interview"}},[r("el-radio",{attrs:{label:1}},[e._v("允许")]),r("el-radio",{attrs:{label:0}},[e._v("不允许")])],1)],1),r("el-form-item",{attrs:{label:"使用微海报"}},[r("el-radio-group",{model:{value:e.form.enable_poster,callback:function(t){e.$set(e.form,"enable_poster",t)},expression:"form.enable_poster"}},[r("el-radio",{attrs:{label:1}},[e._v("允许")]),r("el-radio",{attrs:{label:0}},[e._v("不允许")])],1)],1),r("el-form-item",{attrs:{label:"收到简历免费查看"}},[r("el-radio-group",{model:{value:e.form.show_apply_contact,callback:function(t){e.$set(e.form,"show_apply_contact",t)},expression:"form.show_apply_contact"}},[r("el-radio",{attrs:{label:1}},[e._v("允许")]),r("el-radio",{attrs:{label:0}},[e._v("不允许")])],1)],1),r("el-form-item",{attrs:{label:" "}},[r("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),r("el-button",{on:{click:e.closeDialog}},[e._v("取 消")])],1)],1)],1)},L=[],F={data:function(){return{infoLoading:!1,form:{service_added_discount:0,jobs_meanwhile:0,refresh_jobs_free_perday:0,download_resume_max_perday:0,enable_video_interview:1,enable_poster:1,show_apply_contact:1},rules:{jobs_meanwhile:[{required:!0,message:"请填写同时在招职位数",trigger:"blur"},{type:"number",message:"同时在招职位数只能填写数字",trigger:"blur"}],download_resume_max_perday:[{required:!0,message:"请填写下载简历数上限",trigger:"blur"},{type:"number",message:"下载简历数上限只能填写数字",trigger:"blur"}],refresh_jobs_free_perday:[{required:!0,message:"请填写免费刷新职位次数",trigger:"blur"},{type:"number",message:"免费刷新职位次数只能填写数字",trigger:"blur"}],service_added_discount:[{required:!0,message:"请填写套餐增值包折扣",trigger:"blur"},{type:"number",message:"套餐增值包折扣只能填写数字",trigger:"blur"}]}}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var e=this;Object(i["j"])({},"get").then((function(t){var r=Object(b["a"])({},t.data.setmeal_overtime_conf),a=r.service_added_discount,n=r.jobs_meanwhile,o=r.refresh_jobs_free_perday,l=r.download_resume_max_perday,i=r.enable_video_interview,s=r.enable_poster,c=r.show_apply_contact;e.form={service_added_discount:parseInt(a),jobs_meanwhile:parseInt(n),refresh_jobs_free_perday:parseInt(o),download_resume_max_perday:parseInt(l),enable_video_interview:parseInt(i),enable_poster:parseInt(s),show_apply_contact:parseInt(c)},e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,r={setmeal_overtime_conf:Object(b["a"])({},this.form)};this.$refs[e].validate((function(e){if(!e)return!1;Object(i["j"])(r).then((function(e){return t.$store.dispatch("config/getConfigInfo"),t.$message.success(e.message),t.closeDialog(),t.pageReload(),!0})).catch((function(){}))}))},closeDialog:function(){this.$emit("setDialogFormVisible")},pageReload:function(){this.$emit("pageReload")},format_number:function(e,t){(""==this.form[t]||parseInt(this.form[t])<e)&&(this.form[t]=e)}}},q=F,U=(r("fa56"),Object(u["a"])(q,E,L,!1,null,"227718be",null)),R=U.exports,z=r("ed08"),V={filters:{colorFilter:function(e){return 1==e?"success":"danger"}},components:{diaform:D,overtimeform:R},data:function(){return{dialog_width:"56%",detailContent:{},dialog_type:"form",childId:0,dialogTitle:"",dialogFormVisible:!1,list:null,listLoading:!0,form:{reg_service:1,meal_min_remind:30,famous_enterprises:[]},rules:{meal_min_remind:[{required:!0,message:"请输入套餐到期提前几天提醒",trigger:"blur"}]}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;this.listLoading=!0;var t={};x(t).then((function(t){return e.list=t.data.items,e.listLoading=!1,Object(i["j"])({},"get")})).then((function(t){var r=Object(b["a"])({},t.data),a=r.reg_service,n=r.meal_min_remind,o=r.famous_enterprises,l=""==o?[]:o.split(",");e.form={reg_service:parseInt(a),meal_min_remind:n,famous_enterprises:[]};var i,s=Object(_["a"])(l);try{for(s.s();!(i=s.n()).done;){var c=i.value;e.form.famous_enterprises.push(parseInt(c))}}catch(u){s.e(u)}finally{s.f()}})).catch((function(){}))},fetchSetmallistData:function(){var e=this;this.listLoading=!0;var t={};x(t).then((function(t){e.list=t.data.items,e.listLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this,r=Object(b["a"])({},this.form);r.famous_enterprises=r.famous_enterprises.length>0?r.famous_enterprises.join(","):"",this.$refs[e].validate((function(e){if(!e)return!1;Object(i["j"])(r).then((function(e){return t.$store.dispatch("config/getConfigInfo"),t.$message.success(e.message),!0})).catch((function(){}))}))},funAdd:function(e,t){this.dialog_width="56%",this.dialog_type="form",this.childId=0,this.dialogTitle="添加套餐",this.dialogFormVisible=!0},funEdit:function(e,t){this.dialog_width="56%",this.dialog_type="form",this.childId=t?t.id:0,this.dialogTitle="编辑套餐",this.dialogFormVisible=!0},funDelete:function(e,t){var r=this;r.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.id};$(e).then((function(e){return r.$message.success(e.message),r.fetchSetmallistData(),!0})).catch((function(){}))})).catch((function(){}))},closeDialog:function(){this.dialogFormVisible=!1},timeFilter:function(e){return Object(z["a"])(e,"{y}年{m}月{d}日")},fun_detail:function(e){this.dialog_width="25%",this.dialog_type="detail",this.detailContent=e,this.dialogTitle="套餐内容",this.dialogFormVisible=!0},fun_config_overtime:function(){this.dialog_width="30%",this.dialog_type="overtime",this.dialogTitle="套餐过期使用权限配置",this.dialogFormVisible=!0}}},T=V,A=(r("b7bb"),Object(u["a"])(T,d,p,!1,null,"2d1db35a",null)),M=A.exports,P={components:{configBasic:m,configSetmeallist:M}},N=P,B=Object(u["a"])(N,a,n,!1,null,null,null);t["default"]=B.exports},"4d90":function(e,t,r){"use strict";var a=r("23e7"),n=r("0ccb").start,o=r("9a0c");a({target:"String",proto:!0,forced:o},{padStart:function(e){return n(this,e,arguments.length>1?arguments[1]:void 0)}})},5319:function(e,t,r){"use strict";var a=r("d784"),n=r("825a"),o=r("7b0b"),l=r("50c4"),i=r("a691"),s=r("1d80"),c=r("8aa5"),u=r("14c3"),f=Math.max,m=Math.min,d=Math.floor,p=/\$([$&'`]|\d\d?|<[^>]*>)/g,_=/\$([$&'`]|\d\d?)/g,b=function(e){return void 0===e?e:String(e)};a("replace",2,(function(e,t,r,a){var g=a.REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE,v=a.REPLACE_KEEPS_$0,h=g?"$":"$0";return[function(r,a){var n=s(this),o=void 0==r?void 0:r[e];return void 0!==o?o.call(r,n,a):t.call(String(n),r,a)},function(e,a){if(!g&&v||"string"===typeof a&&-1===a.indexOf(h)){var o=r(t,e,this,a);if(o.done)return o.value}var s=n(e),d=String(this),p="function"===typeof a;p||(a=String(a));var _=s.global;if(_){var w=s.unicode;s.lastIndex=0}var x=[];while(1){var j=u(s,d);if(null===j)break;if(x.push(j),!_)break;var k=String(j[0]);""===k&&(s.lastIndex=c(d,l(s.lastIndex),w))}for(var $="",C=0,S=0;S<x.length;S++){j=x[S];for(var I=String(j[0]),O=f(m(i(j.index),d.length),0),D=[],E=1;E<j.length;E++)D.push(b(j[E]));var L=j.groups;if(p){var F=[I].concat(D,O,d);void 0!==L&&F.push(L);var q=String(a.apply(void 0,F))}else q=y(I,d,O,D,L,a);O>=C&&($+=d.slice(C,O)+q,C=O+I.length)}return $+d.slice(C)}];function y(e,r,a,n,l,i){var s=a+e.length,c=n.length,u=_;return void 0!==l&&(l=o(l),u=p),t.call(i,u,(function(t,o){var i;switch(o.charAt(0)){case"$":return"$";case"&":return e;case"`":return r.slice(0,a);case"'":return r.slice(s);case"<":i=l[o.slice(1,-1)];break;default:var u=+o;if(0===u)return t;if(u>c){var f=d(u/10);return 0===f?t:f<=c?void 0===n[f-1]?o.charAt(1):n[f-1]+o.charAt(1):t}i=n[u-1]}return void 0===i?"":i}))}}))},"53ca":function(e,t,r){"use strict";r.d(t,"a",(function(){return a}));r("a4d3"),r("e01a"),r("d28b"),r("d3b7"),r("3ca3"),r("ddb0");function a(e){return a="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"===typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},a(e)}},"9a0c":function(e,t,r){var a=r("342f");e.exports=/Version\/10\.\d+(\.\d+)?( Mobile\/\w+)? Safari\//.test(a)},a032:function(e,t,r){},a15b:function(e,t,r){"use strict";var a=r("23e7"),n=r("44ad"),o=r("fc6a"),l=r("a640"),i=[].join,s=n!=Object,c=l("join",",");a({target:"Array",proto:!0,forced:s||!c},{join:function(e){return i.call(o(this),void 0===e?",":e)}})},b7bb:function(e,t,r){"use strict";var a=r("e1d7"),n=r.n(a);n.a},b85c:function(e,t,r){"use strict";r.d(t,"a",(function(){return n}));r("a4d3"),r("e01a"),r("d28b"),r("d3b7"),r("3ca3"),r("ddb0");var a=r("06c5");function n(e,t){var r;if("undefined"===typeof Symbol||null==e[Symbol.iterator]){if(Array.isArray(e)||(r=Object(a["a"])(e))||t&&e&&"number"===typeof e.length){r&&(e=r);var n=0,o=function(){};return{s:o,n:function(){return n>=e.length?{done:!0}:{done:!1,value:e[n++]}},e:function(e){throw e},f:o}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var l,i=!0,s=!1;return{s:function(){r=e[Symbol.iterator]()},n:function(){var e=r.next();return i=e.done,e},e:function(e){s=!0,l=e},f:function(){try{i||null==r["return"]||r["return"]()}finally{if(s)throw l}}}}},c9f7:function(e,t,r){},cf34:function(e,t,r){"use strict";var a=r("c9f7"),n=r.n(a);n.a},e048:function(e,t,r){"use strict";var a=r("a032"),n=r.n(a);n.a},e1d7:function(e,t,r){},ed08:function(e,t,r){"use strict";r.d(t,"a",(function(){return l})),r.d(t,"b",(function(){return i}));r("d3b7"),r("ac1f"),r("25f0"),r("4d90"),r("5319"),r("1276");var a=r("53ca"),n=r("a78e"),o=r.n(n);function l(e,t){if(0===arguments.length)return null;var r,n=t||"{y}-{m}-{d} {h}:{i}:{s}";"object"===Object(a["a"])(e)?r=e:("string"===typeof e&&/^[0-9]+$/.test(e)&&(e=parseInt(e)),"number"===typeof e&&10===e.toString().length&&(e*=1e3),r=new Date(e));var o={y:r.getFullYear(),m:r.getMonth()+1,d:r.getDate(),h:r.getHours(),i:r.getMinutes(),s:r.getSeconds(),a:r.getDay()},l=n.replace(/{([ymdhisa])+}/g,(function(e,t){var r=o[t];return"a"===t?["日","一","二","三","四","五","六"][r]:r.toString().padStart(2,"0")}));return l}function i(e){var t={utype:e.utype,token:e.token,mobile:e.mobile,userIminfo:e.user_iminfo};o.a.set("qscms_visitor",JSON.stringify(t),{expires:7})}},fa56:function(e,t,r){"use strict";var a=r("10ab"),n=r.n(a);n.a}}]);