(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-202eb28a"],{"4bb5":function(e,a,t){"use strict";t.r(a);var c=function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",{staticClass:"app-container"},[t("el-card",{staticClass:"box-card"},[t("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[t("span",[e._v("编辑角色")]),t("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(a){return e.goto("/sys/admin/role")}}},[e._v(" 返回 ")]),t("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(a){return e.onSubmit("form")}}},[e._v(" 保存 ")])],1),t("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px",rules:e.rules,"inline-message":!0}},[t("el-form-item",{attrs:{label:"角色名称",prop:"name"}},[t("el-input",{staticClass:"middle",model:{value:e.form.name,callback:function(a){e.$set(e.form,"name",a)},expression:"form.name"}})],1),t("el-form-item",{attrs:{label:"pc后台权限"}},[t("el-tree",{ref:"tree",attrs:{"node-key":"name",data:e.accessData,props:e.props,"show-checkbox":"","default-checked-keys":e.checkKeys},on:{"check-change":e.handleCheckChange}})],1),t("el-form-item",{attrs:{label:"移动端后台权限"}},[t("el-tree",{ref:"treeMobile",attrs:{"node-key":"alias",data:e.accessDataMobile,props:e.propsMobile,"show-checkbox":"","default-checked-keys":e.checkKeysMobile},on:{"check-change":e.handleCheckChangeMobile}})],1),t("el-form-item",{attrs:{label:"操作权限"}},[t("el-checkbox",{model:{value:e.form.access_export,callback:function(a){e.$set(e.form,"access_export",a)},expression:"form.access_export"}},[e._v("导出数据")]),t("el-checkbox",{model:{value:e.form.access_delete,callback:function(a){e.$set(e.form,"access_delete",a)},expression:"form.access_delete"}},[e._v("删除数据")]),t("el-checkbox",{model:{value:e.form.access_set_service,callback:function(a){e.$set(e.form,"access_set_service",a)},expression:"form.access_set_service"}},[e._v("分配客服")])],1),t("el-form-item",{attrs:{label:""}},[t("el-button",{attrs:{type:"primary"},on:{click:function(a){return e.onSubmit("form")}}},[e._v("保存")]),t("el-button",{on:{click:function(a){return e.goto("/sys/admin/role")}}},[e._v("返回")])],1)],1)],1)],1)},s=[],l=(t("a434"),t("2909")),r=t("5530"),o=t("d00f"),i=t("2963"),n=t("d4c4"),d=t("fe3b"),m=t("55d5"),b=t("c34c"),u=t("9cf2"),f=t("7e72"),h=t("8c28"),p={data:function(){return{infoLoading:!0,checkKeys:[],checkKeysMobile:[],form:{name:"",access:[],access_mobile:[],access_export:!1,access_delete:!1,access_set_service:!1},accessData:[],accessDataMobile:[{label:"会员管理",alias:"member",children:[{label:"企业会员",alias:"member_company"},{label:"个人会员",alias:"member_personal"},{label:"无效会员",alias:"member_invalid"},{label:"简历管理",alias:"resume_manage"},{label:"照片/作品",alias:"resume_img"},{label:"企业管理",alias:"company_manage"},{label:"职位管理",alias:"job_manage"},{label:"企业风采",alias:"company_img"},{label:"账号注销申请",alias:"cancel_apply"}]},{label:"业务管理",alias:"business",children:[{label:"(企业)套餐管理",alias:"setmeal"},{label:"(企业)订单管理",alias:"order_company"},{label:"(企业)开通套餐",alias:"setmeal_add"},{label:"(企业)新增推广",alias:"promotion_job_add"},{label:"(企业)积分增减",alias:"points_company"},{label:"(个人)订单管理",alias:"order_personal"},{label:"(个人)新增推广",alias:"promotion_resume_add"}]},{label:"系统工具",alias:"system",children:[{label:"更新缓存",alias:"clearcache"},{label:"网站启停",alias:"site_status"},{label:"暂停注册",alias:"reg_status"}]}],props:{label:function(e,a){return e.meta.title},children:"children"},propsMobile:{label:"label",children:"children"},count:1,rules:{name:[{required:!0,message:"请输入角色名",trigger:"blur"},{max:15,message:"长度在 1 到 15 个字符",trigger:"blur"}]}}},created:function(){this.fetchInfo();var e=[Object(r["a"])({},i["a"]),Object(r["a"])({},n["a"]),Object(r["a"])({},d["a"]),Object(r["a"])({},m["a"]),Object(r["a"])({},b["a"]),Object(r["a"])({},u["a"]),Object(r["a"])({},f["a"]),Object(r["a"])({},h["a"])];e=this.routeTree(e),this.accessData=Object(l["a"])(e)},methods:{routeTree:function(e){for(var a=Object(l["a"])(e),t=this,c=0;c<a.length;c++){var s=Object(r["a"])({},a[c]);void 0!==s.hidden&&!0===s.hidden?(a.splice(c,1),c--):s.children&&s.children.length>0&&(s.children=t.routeTree(s.children),a[c]=s)}return a},fetchInfo:function(){var e=this;this.infoLoading=!0;var a={id:this.$route.query.id};Object(o["c"])(a,"get").then((function(a){e.form=Object(r["a"])({},a.data),e.checkKeys=""==e.form.access?[]:Object(l["a"])(e.form.access.checkedKeys),e.checkKeysMobile=""==e.form.access_mobile?[]:Object(l["a"])(e.form.access_mobile.checkedKeys),e.infoLoading=!1})).catch((function(){}))},handleCheckChange:function(e,a,t){var c=Object(l["a"])(this.$refs.tree.getHalfCheckedKeys()),s=Object(l["a"])(this.$refs.tree.getCheckedKeys());this.form.access={harfCheckedKeys:c,checkedKeys:s}},handleCheckChangeMobile:function(e,a,t){var c=Object(l["a"])(this.$refs.treeMobile.getHalfCheckedKeys()),s=Object(l["a"])(this.$refs.treeMobile.getCheckedKeys());this.form.access_mobile={harfCheckedKeys:c,checkedKeys:s}},onSubmit:function(e){var a=this,t=this,c=Object(r["a"])({},this.form);c.access_export=!0===c.access_export?1:0,c.access_delete=!0===c.access_delete?1:0,c.access_set_service=!0===c.access_set_service?1:0,this.$refs[e].validate((function(e){if(!e)return!1;Object(o["c"])(c).then((function(e){return a.$message.success(e.message),setTimeout((function(){t.$router.push("/sys/admin/role")}),1500),!0})).catch((function(){}))}))},goto:function(e){this.$router.push(e)}}},_=p,g=t("2877"),k=Object(g["a"])(_,c,s,!1,null,null,null);a["default"]=k.exports},d00f:function(e,a,t){"use strict";t.d(a,"d",(function(){return l})),t.d(a,"a",(function(){return r})),t.d(a,"c",(function(){return o})),t.d(a,"b",(function(){return i}));var c=t("b775"),s=t("d722");function l(e){return Object(c["a"])({url:s["a"].adminRoleList,method:"get",params:e})}function r(e){return Object(c["a"])({url:s["a"].adminRoleAdd,method:"post",data:e})}function o(e){var a=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==a?Object(c["a"])({url:s["a"].adminRoleEdit,method:a,data:e}):Object(c["a"])({url:s["a"].adminRoleEdit,method:a,params:e})}function i(e){return Object(c["a"])({url:s["a"].adminRoleDelete,method:"post",data:e})}}}]);