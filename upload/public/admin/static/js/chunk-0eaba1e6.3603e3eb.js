(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0eaba1e6"],{"110b":function(t,e,a){"use strict";a.d(e,"b",(function(){return r})),a.d(e,"a",(function(){return l}));var s=a("b775"),n=a("d722");function r(t){return Object(s["a"])({url:n["a"].sysToolSyncSetmeal,method:"post",data:t})}function l(t){return Object(s["a"])({url:n["a"].sysToolSyncCompanyDisplay,method:"post",data:t})}},"6b99":function(t,e,a){},a597:function(t,e,a){"use strict";a.d(e,"d",(function(){return r})),a.d(e,"a",(function(){return l})),a.d(e,"c",(function(){return i})),a.d(e,"b",(function(){return o}));var s=a("b775"),n=a("d722");function r(t){return Object(s["a"])({url:n["a"].setmealList,method:"get",params:t})}function l(t){return Object(s["a"])({url:n["a"].setmealAdd,method:"post",data:t})}function i(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(s["a"])({url:n["a"].setmealEdit,method:e,data:t}):Object(s["a"])({url:n["a"].setmealEdit,method:e,params:t})}function o(t){return Object(s["a"])({url:n["a"].setmealDelete,method:"post",data:t})}},d9de:function(t,e,a){"use strict";var s=a("6b99"),n=a.n(s);n.a},f3f2:function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-card",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}]},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[t._v("套餐权限同步")])]),a("div",{staticClass:"tip"},[a("p",[t._v("本工具操作不当会导致严重后果，使用前请注意数据备份，执行操作不可逆，请谨慎使用！")])]),a("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"20%",rules:t.rules,"inline-message":!0}},[a("el-form-item",{attrs:{label:"功能使用场景说明"}},[t._v(" 将所选套餐的权限内容同步到站内相同套餐的所有企业。 ")]),a("el-form-item",{attrs:{label:"选择要同步的套餐",prop:"setmeal_id"}},[a("el-radio-group",{on:{change:t.changeSetmeal},model:{value:t.form.setmeal_id,callback:function(e){t.$set(t.form,"setmeal_id",t._n(e))},expression:"form.setmeal_id"}},t._l(t.setMealList,(function(e){return e.is_display>0?a("el-radio",{key:e.id,attrs:{label:e.id}},[a("span",{domProps:{textContent:t._s(e.name)}})]):t._e()})),1)],1),t.form.setmeal_id>0?a("el-form-item",{attrs:{label:"套餐权限一览"}},[a("el-card",[a("el-row",[a("el-col",{attrs:{span:24}},[t._v(" 职位并发数: "),a("span",{staticClass:"val",domProps:{innerHTML:t._s(t.detail.jobs_meanwhile)}})])],1),a("el-row",[a("el-col",{attrs:{span:8}},[t._v(" 免费刷新职位: "),a("span",{staticClass:"val",domProps:{innerHTML:t._s(t.detail.refresh_jobs_free_perday)}}),t._v(" 次/天 ")]),a("el-col",{attrs:{span:8}},[t._v(" 下载简历上限: "),a("span",{staticClass:"val",domProps:{innerHTML:t._s(t.detail.download_resume_max_perday)}}),t._v(" 份/天 ")]),a("el-col",{attrs:{span:8}},[t._v(" 套餐增值包折扣: "),a("span",{staticClass:"val",domProps:{innerHTML:t._s(t.detail.service_added_discount)}}),t._v(" 折 ")])],1),a("el-row",[a("el-col",{attrs:{span:8}},[t._v(" 使用微海报: "),t.enable_poster?a("span",{staticClass:"val"},[t._v("允许")]):a("span",{staticClass:"val"},[t._v("不允许")])]),a("el-col",{attrs:{span:8}},[t._v(" 收到简历免费查看: "),t.show_apply_contact?a("span",{staticClass:"val"},[t._v("允许")]):a("span",{staticClass:"val"},[t._v("不允许")])]),a("el-col",{attrs:{span:8}},[t._v(" 使用视频面试: "),t.enable_video_interview?a("span",{staticClass:"val"},[t._v("允许")]):a("span",{staticClass:"val"},[t._v("不允许")])])],1)],1)],1):t._e(),a("el-form-item",{attrs:{label:"注意事项"}},[t._v(" 1.操作执行成功后数据不可恢复,请提前备份好数据;"),a("br"),t._v(" 2.套餐权限指您的套餐配置信息，如需修改请在[企业业务配置--\x3e套餐管理]处修改对应套餐; 立即同步 ")]),a("el-form-item",{attrs:{label:" "}},[a("el-button",{attrs:{type:"primary"},on:{click:t.onSubmitPre}},[t._v(" 提交同步 ")])],1)],1)],1),a("el-dialog",{attrs:{title:"请输入密码确认",visible:t.enterPwdShow,width:"350px"},on:{"update:visible":function(e){t.enterPwdShow=e}}},[a("el-row",[a("el-col",{attrs:{span:18}},[a("el-input",{attrs:{type:"password",placeholder:"请输入密码"},model:{value:t.form.pwd,callback:function(e){t.$set(t.form,"pwd",e)},expression:"form.pwd"}})],1),a("el-col",{attrs:{span:6}},[a("el-button",{attrs:{type:"primary"},on:{click:t.onSubmit}},[t._v("确定")])],1)],1)],1)],1)},n=[],r=(a("4160"),a("159b"),a("110b")),l=a("a597"),i={data:function(){return{loading:!0,setMealList:[],enable_video_interview:!1,show_apply_contact:!1,enable_poster:!1,enterPwdShow:!1,form:{setmeal_id:"",pwd:""},rules:{setmeal_id:[{required:!0,message:"请选择套餐",trigger:"blur"}]}}},computed:{detail:function(){var t=this,e={};return this.setMealList.forEach((function(a,s){a.id==t.form.setmeal_id&&(e=a)})),e}},created:function(){var t=this;Object(l["d"])({}).then((function(e){t.setMealList=e.data.items,t.loading=!1}))},methods:{changeSetmeal:function(){this.enable_video_interview=!!parseInt(this.detail.enable_video_interview),this.show_apply_contact=!!parseInt(this.detail.show_apply_contact),this.enable_poster=!!parseInt(this.detail.enable_poster)},onSubmitPre:function(){this.form.setmeal_id?(this.form.pwd="",this.enterPwdShow=!0):this.$message.error("请选择要同步的套餐")},onSubmit:function(){var t=this;if(0==this.form.pwd.length)return this.$message.error("密码不能为空"),!1;var e=Object.assign({},this.form);Object(r["b"])(e).then((function(e){return t.enterPwdShow=!1,t.$message.success(e.message),!0})).catch((function(){}))}}},o=i,d=(a("d9de"),a("2877")),c=Object(d["a"])(o,s,n,!1,null,"40e91702",null);e["default"]=c.exports}}]);