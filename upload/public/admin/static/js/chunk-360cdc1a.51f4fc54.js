(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-360cdc1a"],{"47f7":function(e,t,o){},a331:function(e,t,o){"use strict";o.r(t);var i=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"app-container"},[o("el-card",{staticClass:"box-card"},[o("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[o("span",[e._v("客服配置")])]),o("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.listLoading,expression:"listLoading"}],staticStyle:{width:"100%"},attrs:{"element-loading-text":"Loading",fit:"","highlight-current-row":"",data:e.list}},[o("el-table-column",{attrs:{label:"姓名","min-width":"100"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v(" "+e._s(t.row.name)+" ")]}}])}),o("el-table-column",{attrs:{prop:"mobile",label:"手机号","min-width":"120"}}),o("el-table-column",{attrs:{prop:"tel",label:"座机","min-width":"120"}}),o("el-table-column",{attrs:{prop:"weixin",label:"微信","min-width":"120"}}),o("el-table-column",{attrs:{prop:"qq",label:"QQ","min-width":"120"}}),o("el-table-column",{attrs:{label:"状态",align:"center","min-width":"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[o("el-tag",{attrs:{type:e._f("colorFilter")(t.row.status)}},[e._v(" "+e._s(1==t.row.status?"正常":"暂停")+" ")])]}}])}),o("el-table-column",{attrs:{label:"关联企业数",align:"center","min-width":"110"},scopedSlots:e._u([{key:"default",fn:function(t){return[o("span",[e._v(" "+e._s(t.row.company_num)+" ")])]}}])}),o("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"150"},scopedSlots:e._u([{key:"default",fn:function(t){return[o("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(o){return e.funEdit(t.$index,t.row)}}},[e._v(" 修改 ")]),o("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(o){return e.funDelete(t.$index,t.row)}}},[e._v(" 删除 ")])]}}])})],1),o("div",{staticClass:"spaceline"}),o("el-button",{attrs:{size:"small",type:"primary"},on:{click:e.funAdd}},[e._v(" 添加 ")])],1),e.dialogFormVisible?o("el-dialog",{attrs:{title:e.dialogTitle,visible:e.dialogFormVisible,width:"30%","close-on-click-modal":!1},on:{"update:visible":function(t){e.dialogFormVisible=t}}},[o("diaform",{attrs:{"item-info":e.itemInfo},on:{setDialogFormVisible:e.closeDialog,pageReload:e.fetchData}})],1):e._e()],1)},a=[],l=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"app-container"},[o("el-form",{ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"100px",rules:e.rules,"inline-message":!0}},[o("el-form-item",{attrs:{label:"照片",prop:"photo"}},[o("el-upload",{staticClass:"photo-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handlePhotoSuccess,"before-upload":e.beforePhotoUpload}},[e.form.photo?o("img",{staticClass:"photo",attrs:{src:e.photoUrl}}):o("i",{staticClass:"el-icon-plus photo-uploader-icon"})])],1),o("el-form-item",{attrs:{label:"姓名",prop:"name"}},[o("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),o("el-form-item",{attrs:{label:"手机号",prop:"mobile"}},[o("el-input",{model:{value:e.form.mobile,callback:function(t){e.$set(e.form,"mobile",t)},expression:"form.mobile"}})],1),o("el-form-item",{attrs:{label:"座机",prop:"tel"}},[o("el-input",{model:{value:e.form.tel,callback:function(t){e.$set(e.form,"tel",t)},expression:"form.tel"}})],1),o("el-form-item",{attrs:{label:"微信",prop:"weixin"}},[o("el-input",{model:{value:e.form.weixin,callback:function(t){e.$set(e.form,"weixin",t)},expression:"form.weixin"}})],1),o("el-form-item",{attrs:{label:"微信二维码",prop:"wx_qrcode"}},[o("el-upload",{staticClass:"photo-uploader",attrs:{action:e.apiUpload,headers:e.headers,"show-file-list":!1,"on-success":e.handleQrcodeSuccess,"before-upload":e.beforeQrcodeUpload}},[e.form.wx_qrcode?o("img",{staticClass:"photo",attrs:{src:e.qrcodeUrl}}):o("i",{staticClass:"el-icon-plus photo-uploader-icon"})])],1),o("el-form-item",{attrs:{label:"QQ",prop:"qq"}},[o("el-input",{model:{value:e.form.qq,callback:function(t){e.$set(e.form,"qq",t)},expression:"form.qq"}})],1),o("el-form-item",{attrs:{label:"是否可用",prop:"status"}},[o("el-switch",{model:{value:e.form.status,callback:function(t){e.$set(e.form,"status",t)},expression:"form.status"}})],1),o("el-form-item",{attrs:{label:" "}},[o("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),o("el-button",{on:{click:e.closeDialog}},[e._v("取 消")])],1)],1)],1)},s=[],r=o("5530"),n=(o("d3b7"),o("3ca3"),o("ddb0"),o("2b3d"),o("9861"),o("ac1f"),o("1276"),o("caad"),o("2532"),o("b775")),c=o("d722");function u(e){return Object(n["a"])({url:c["a"].customerServiceList,method:"get",params:e})}function d(e){return Object(n["a"])({url:c["a"].customerServiceAdd,method:"post",data:e})}function f(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==t?Object(n["a"])({url:c["a"].customerServiceEdit,method:t,data:e}):Object(n["a"])({url:c["a"].customerServiceEdit,method:t,params:e})}function m(e){return Object(n["a"])({url:c["a"].customerServiceDelete,method:"post",data:e})}var p=o("5f87"),h={props:["itemInfo"],data:function(){return{headers:{admintoken:Object(p["e"])()},fileupload_size:"",fileupload_ext:"",apiUpload:window.global.RequestBaseUrl+c["a"].upload,photoUrl:"",qrcodeUrl:"",form:{id:0,photo:0,name:"",mobile:"",tel:"",weixin:"",wx_qrcode:0,qq:"",status:!0},rules:{name:[{required:!0,message:"请输入姓名",trigger:"blur"}],mobile:[{required:!0,message:"请输入手机号",trigger:"blur"}]}}},computed:{config:function(){return this.$store.state.config}},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext,this.fetchInfo()},methods:{fetchInfo:function(){null!==this.itemInfo&&(this.itemInfo.status=1==this.itemInfo.status,this.form=Object(r["a"])(Object(r["a"])({},this.form),this.itemInfo),this.photoUrl=this.itemInfo.photoUrl,this.qrcodeUrl=this.itemInfo.qrcodeUrl)},addSave:function(e,t){var o=this;this.$refs[t].validate((function(t){if(!t)return!1;e.status=!0===e.status?1:0,d(e).then((function(e){return o.$message.success(e.message),o.closeDialog(),o.pageReload(),!0})).catch((function(){}))}))},editSave:function(e,t){var o=this;this.$refs[t].validate((function(t){if(!t)return!1;e.status=!0===e.status?1:0,f(e).then((function(e){return o.$message.success(e.message),o.closeDialog(),o.pageReload(),!0})).catch((function(){}))}))},onSubmit:function(e){var t=this,o=Object(r["a"])({},t.form);parseInt(o.id)>0?t.editSave(o,e):t.addSave(o,e)},closeDialog:function(){this.$emit("setDialogFormVisible")},pageReload:function(){this.$emit("pageReload")},handlePhotoSuccess:function(e,t){if(200!=e.code)return this.$message.error(e.message),!1;this.photoUrl=URL.createObjectURL(t.raw),this.form.photo=e.data.file_id},beforePhotoUpload:function(e){var t=e.type.split("/"),o=t[1],i=this.fileupload_ext.split(",");return i.includes(o)?!(e.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)},handleQrcodeSuccess:function(e,t){if(200!=e.code)return this.$message.error(e.message),!1;this.qrcodeUrl=URL.createObjectURL(t.raw),this.form.wx_qrcode=e.data.file_id},beforeQrcodeUpload:function(e){var t=e.type.split("/"),o=t[1],i=this.fileupload_ext.split(",");return i.includes(o)?!(e.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)}}},b=h,g=(o("afc8"),o("2877")),v=Object(g["a"])(b,l,s,!1,null,"2a7cffc1",null),w=v.exports,_={filters:{colorFilter:function(e){return 1==e?"success":"danger"}},components:{diaform:w},data:function(){return{detailContent:{},itemInfo:null,dialogTitle:"",dialogFormVisible:!1,list:null,listLoading:!0}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;this.listLoading=!0;var t={};u(t).then((function(t){e.list=t.data.items,e.listLoading=!1}))},funAdd:function(e,t){this.itemInfo=null,this.dialogTitle="添加客服",this.dialogFormVisible=!0},funEdit:function(e,t){t&&(this.itemInfo=t),this.dialogTitle="编辑客服",this.dialogFormVisible=!0},funDelete:function(e,t){var o=this;o.$confirm("删除后该客服下的企业将变为无客服状态，确认删除吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.id};m(e).then((function(e){return o.$message.success(e.message),o.fetchData(),!0}))})).catch((function(){}))},closeDialog:function(){this.dialogFormVisible=!1}}},x=_,$=Object(g["a"])(x,i,a,!1,null,null,null);t["default"]=$.exports},afc8:function(e,t,o){"use strict";o("47f7")}}]);