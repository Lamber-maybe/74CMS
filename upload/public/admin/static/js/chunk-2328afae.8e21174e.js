(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2328afae"],{"0586":function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},["invalid"==t.listtype?i("div",{staticClass:"tip"},[i("p",[t._v(" 无效会员指个人会员注册后未创建简历、企业会员未完善企业资料的会员信息 ")])]):t._e(),i("el-card",{staticClass:"box-card"},[i("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[i("span",[t._v("会员列表")])]),i("div",{staticClass:"list-search"},[!0===t.showOptionsUtype?i("el-select",{staticClass:"list-options",attrs:{placeholder:"不限身份"},on:{change:t.funSearch},model:{value:t.utype,callback:function(e){t.utype=e},expression:"utype"}},[i("el-option",{attrs:{label:"不限身份",value:""}}),i("el-option",{attrs:{label:"企业会员",value:"1"}}),i("el-option",{attrs:{label:"个人会员",value:"2"}})],1):t._e(),i("el-select",{staticClass:"list-options",attrs:{placeholder:"不限会员状态"},on:{change:t.funSearch},model:{value:t.status,callback:function(e){t.status=e},expression:"status"}},[i("el-option",{attrs:{label:"不限会员状态",value:""}}),i("el-option",{attrs:{label:"已锁定",value:"0"}})],1),i("el-select",{staticClass:"list-options",attrs:{placeholder:"按注册时间排序"},on:{change:t.funSearch},model:{value:t.sort,callback:function(e){t.sort=e},expression:"sort"}},[i("el-option",{attrs:{label:"按注册时间排序",value:""}}),i("el-option",{attrs:{label:"按登录时间排序",value:"logintime"}})],1),i("el-select",{staticClass:"list-options",attrs:{placeholder:"不限注册时间"},on:{change:t.funSearch},model:{value:t.regtime,callback:function(e){t.regtime=e},expression:"regtime"}},[i("el-option",{attrs:{label:"不限注册时间",value:""}}),i("el-option",{attrs:{label:"3天内",value:"3"}}),i("el-option",{attrs:{label:"7天内",value:"7"}}),i("el-option",{attrs:{label:"15天内",value:"15"}}),i("el-option",{attrs:{label:"30天内",value:"30"}})],1),i("el-select",{staticClass:"list-options",attrs:{placeholder:"不限注册来源"},on:{change:t.funSearch},model:{value:t.platform,callback:function(e){t.platform=e},expression:"platform"}},[i("el-option",{attrs:{label:"不限注册来源",value:""}}),t._l(t.platformOptions,(function(t,e){return i("el-option",{key:e,attrs:{label:t.name,value:t.id}})}))],2),i("el-select",{staticClass:"list-options",attrs:{placeholder:"不限微信绑定"},on:{change:t.funSearch},model:{value:t.is_openid,callback:function(e){t.is_openid=e},expression:"is_openid"}},[i("el-option",{attrs:{label:"不限微信绑定",value:""}}),i("el-option",{attrs:{label:"已绑定微信",value:"1"}}),i("el-option",{attrs:{label:"未绑定微信",value:"2"}})],1),i("el-select",{staticClass:"list-options",attrs:{placeholder:"不限邮箱绑定"},on:{change:t.funSearch},model:{value:t.is_email,callback:function(e){t.is_email=e},expression:"is_email"}},[i("el-option",{attrs:{label:"不限邮箱绑定",value:""}}),i("el-option",{attrs:{label:"已绑定邮箱",value:"1"}}),i("el-option",{attrs:{label:"未绑定邮箱",value:"2"}})],1),i("el-input",{staticClass:"input-with-select",attrs:{placeholder:"请输入搜索内容"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword.apply(null,arguments)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[i("el-select",{staticClass:"input-sel",attrs:{slot:"prepend",placeholder:"请选择"},slot:"prepend",model:{value:t.key_type,callback:function(e){t.key_type=e},expression:"key_type"}},[i("el-option",{attrs:{label:"UID",value:"1"}}),i("el-option",{attrs:{label:"用户名",value:"2"}}),i("el-option",{attrs:{label:"手机号",value:"3"}}),"company"==t.listtype?i("el-option",{attrs:{label:"企业名称",value:"4"}}):t._e(),"personal"==t.listtype?i("el-option",{attrs:{label:"姓名",value:"4"}}):t._e()],1),i("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1),i("div",{staticClass:"spaceline"}),i("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"selection-change":t.handleSelectionChange}},[i("el-table-column",{attrs:{type:"selection",width:"42"}}),i("el-table-column",{attrs:{align:"center",label:"UID",prop:"uid",width:"80"}}),"company"==t.listtype?i("el-table-column",{attrs:{label:"企业名称",prop:"companyname","min-width":"280"}}):t._e(),i("el-table-column",{attrs:{label:"用户名",prop:"username","min-width":"150"}}),"invalid"==t.listtype?i("el-table-column",{attrs:{align:"center",label:"身份类型","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.utype?i("span",[t._v("企业会员")]):t._e(),2==e.row.utype?i("span",[t._v("个人会员")]):t._e()]}}],null,!1,2660091036)}):t._e(),i("el-table-column",{attrs:{label:"手机号",prop:"mobile","min-width":"120"}}),i("el-table-column",{attrs:{align:"center",label:"注册时间","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("i",{staticClass:"el-icon-time"}),i("span",[t._v(t._s(t._f("timeFilter")(e.row.reg_time)))])]}}])}),i("el-table-column",{attrs:{align:"center",label:"最近登录","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("i",{staticClass:"el-icon-time"}),0==e.row.last_login_time?i("span",[t._v("从未登录")]):i("span",[t._v(t._s(t._f("timeFilter")(e.row.last_login_time)))])]}}])}),i("el-table-column",{attrs:{label:"会员状态",width:"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-switch",{on:{change:function(i){return t.modifyState(e.row)}},model:{value:e.row.display,callback:function(i){t.$set(e.row,"display",i)},expression:"scope.row.display"}})]}}])}),i("el-table-column",{attrs:{fixed:"right",label:"操作",width:"270"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-button",{attrs:{size:"small"},on:{click:function(i){return t.funManagement(e.row)}}},[t._v(" 管理 ")]),i("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(i){return t.funDetail(e.$index,e.row)}}},[t._v(" 查看 ")]),i("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(i){return t.funEdit(e.$index,e.row)}}},[t._v(" 修改 ")]),i("el-dropdown",{staticStyle:{"margin-left":"10px"},attrs:{trigger:"click"}},[i("el-button",{attrs:{type:"small"}},[t._v("···")]),i("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},[i("el-dropdown-item",{nativeOn:{click:function(i){return t.funLog(e.$index,e.row)}}},[t._v(" 日志 ")]),i("el-dropdown-item",{nativeOn:{click:function(i){return t.funLock(e.$index,e.row)}}},[t._v(" "+t._s(1==e.row.status?"锁定":"解锁")+" ")]),i("el-dropdown-item",{nativeOn:{click:function(i){return t.funIm(e.row)}}},[t._v(" "+t._s(1==e.row.disable_im?"解除禁聊":"禁聊")+" ")]),i("el-dropdown-item",{nativeOn:{click:function(i){return t.funDelete(e.row)}}},[t._v(" 删除 ")])],1)],1)]}}])})],1),i("div",{staticClass:"spaceline"}),i("el-row",{attrs:{gutter:20}},[i("el-col",{attrs:{span:8}},[i("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.funAdd}},[t._v(" 添加会员 ")]),i("el-button",{attrs:{size:"small",type:"danger"},on:{click:t.funDeleteBatch}},[t._v(" 删除所选 ")])],1),i("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[i("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,20,50,100],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1),t.dialogFormVisible?i("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogFormVisible,width:t.dialogWidth,"close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogFormVisible=e},close:t.closeDialog}},["form"==t.dialogContent?i("diaform",{attrs:{uid:t.childId,listtype:t.listtype},on:{setDialogFormVisible:t.closeDialog,setDialogFormVisibleCompany:t.closeDialogCompany,setDialogFormVisiblePersonal:t.closeDialogPersonal,pageReload:t.fetchData}}):t._e(),"detail"==t.dialogContent?i("detail",{attrs:{uid:t.detailUid,"detail-info":t.detailInfo},on:{setDialogFormVisible:t.closeDialog}}):t._e()],1):t._e(),t.dialogListVisible?i("el-dialog",{attrs:{title:"会员日志",visible:t.dialogListVisible,width:"50%","close-on-click-modal":!1},on:{"update:visible":function(e){t.dialogListVisible=e},close:t.closeListDialog}},[i("MemberLog",{attrs:{uid:t.listUid},on:{setDialogFormVisible:t.closeListDialog}})],1):t._e(),i("el-dialog",{attrs:{title:"将所选用户设置为禁聊",visible:t.dialogImFormVisible,width:"25%"},on:{"update:visible":function(e){t.dialogImFormVisible=e}}},[i("el-form",{staticClass:"common-form",attrs:{"label-width":"80px"}},[i("el-form-item",{attrs:{label:"原因"}},[i("el-input",{attrs:{type:"textarea",rows:"3"},model:{value:t.setImReason,callback:function(e){t.setImReason=e},expression:"setImReason"}})],1)],1),i("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(e){t.dialogImFormVisible=!1}}},[t._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:t.funSetIm}},[t._v(" 确 定 ")])],1)],1)],1)},n=[],l=i("b85c"),o=i("2909"),s=(i("4e82"),function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("el-tabs",[i("el-tab-pane",{attrs:{label:"操作日志",lazy:!0}},[i("ActionLog",{attrs:{uid:t.uid}})],1),i("el-tab-pane",{attrs:{label:"登录日志",lazy:!0}},[i("LoginLog",{attrs:{uid:t.uid}})],1)],1)],1)}),r=[],c=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("div",{staticClass:"list-search"},[i("el-input",{staticClass:"input-with-select",attrs:{placeholder:"请输入关键词",size:"small"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword.apply(null,arguments)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[i("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1),i("div",{staticClass:"spaceline"}),i("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"",size:"small","highlight-current-row":""}},[i("el-table-column",{attrs:{label:"时间",width:"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),i("el-table-column",{attrs:{label:"操作",prop:"content"}}),i("el-table-column",{attrs:{label:"IP",prop:"ip",width:"150"}}),i("el-table-column",{attrs:{label:"IP归属地",prop:"ip_addr",width:"150"}}),i("el-table-column",{attrs:{label:"来源",prop:"platform_cn",width:"150"}})],1),i("div",{staticClass:"spaceline"}),i("el-row",{attrs:{gutter:20}},[i("el-col",{staticStyle:{"text-align":"right"},attrs:{span:20}},[i("el-pagination",{attrs:{layout:"prev, pager, next",background:"",total:t.total,"current-page":t.currentPage,"page-size":t.pagesize},on:{"current-change":t.handleCurrentChange}})],1)],1)],1)},u=[],d=i("8194"),f=i("ed08"),m={filters:{timeFilter:function(t){return Object(f["b"])(t,"{y}-{m}-{d} {h}:{i}:{s}")}},props:["uid"],data:function(){return{list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,keyword:""}},created:function(){this.fetchData()},methods:{funSearchKeyword:function(){this.currentPage=1,this.fetchData()},fetchData:function(){var t=this;this.listLoading=!0;var e={keyword:this.keyword,uid:this.uid,page:this.currentPage,pagesize:this.pagesize};Object(d["b"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()}}},p=m,h=(i("1c04"),i("2877")),g=Object(h["a"])(p,c,u,!1,null,"6cbf43ae",null),b=g.exports,v=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"",size:"small","highlight-current-row":""}},[i("el-table-column",{attrs:{label:"时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("span",[t._v(t._s(t._f("timeFilter")(e.row.addtime)))])]}}])}),i("el-table-column",{attrs:{label:"IP",prop:"ip"}}),i("el-table-column",{attrs:{label:"IP归属地",prop:"ip_addr"}}),i("el-table-column",{attrs:{label:"来源",prop:"platform_cn"}})],1),i("div",{staticClass:"spaceline"}),i("el-row",{attrs:{gutter:20}},[i("el-col",{staticStyle:{"text-align":"right"},attrs:{span:20}},[i("el-pagination",{attrs:{layout:"prev, pager, next",background:"",total:t.total,"current-page":t.currentPage,"page-size":t.pagesize},on:{"current-change":t.handleCurrentChange}})],1)],1)],1)},y=[],_={filters:{timeFilter:function(t){return Object(f["b"])(t,"{y}-{m}-{d} {h}:{i}:{s}")}},props:["uid"],data:function(){return{list:null,listLoading:!0,total:0,currentPage:1,pagesize:10}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={uid:this.uid,page:this.currentPage,pagesize:this.pagesize};Object(d["j"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()}}},w=_,k=Object(h["a"])(w,v,y,!1,null,null,null),x=k.exports,D={components:{ActionLog:b,LoginLog:x},props:["uid"]},O=D,C=Object(h["a"])(O,s,r,!1,null,null,null),L=C.exports,j=i("52b5"),I=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"150px",rules:t.rules,"inline-message":!0}},[0==t.uid&&t.utypeDisabled?i("el-form-item",{attrs:{label:"身份"}},[i("el-radio-group",{model:{value:t.form.utype,callback:function(e){t.$set(t.form,"utype",e)},expression:"form.utype"}},[i("el-radio",{attrs:{label:"1"}},[t._v("企业会员")]),i("el-radio",{attrs:{label:"2"}},[t._v("个人会员")])],1)],1):t._e(),i("el-form-item",{attrs:{label:"用户名",prop:"username"}},[i("el-input",{staticClass:"small",model:{value:t.form.username,callback:function(e){t.$set(t.form,"username",e)},expression:"form.username"}})],1),i("el-form-item",{attrs:{label:"手机号",prop:"mobile"}},[i("el-input",{staticClass:"small",model:{value:t.form.mobile,callback:function(e){t.$set(t.form,"mobile",e)},expression:"form.mobile"}})],1),i("el-form-item",{attrs:{label:"密码",prop:"password"}},[i("el-input",{staticClass:"small",attrs:{"show-password":"",placeholder:t.placeholder},model:{value:t.form.password,callback:function(e){t.$set(t.form,"password",e)},expression:"form.password"}})],1),i("el-form-item",{attrs:{label:" "}},[i("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),i("el-button",{on:{click:t.closeDialog}},[t._v("取 消")])],1)],1)],1)},$=[],S=i("5530"),z=i("61f7"),F={props:["uid","listtype"],data:function(){var t=this,e=function(e,i,a){if(Object(z["e"])(i))a(new Error("用户名不能是纯数字"));else if(Object(z["b"])(i))a(new Error("用户名不能是邮箱"));else{var n={field:"username",value:i,self:t.uid,utype:t.form.utype};Object(d["d"])(n).then((function(t){0==t.data?a(new Error("用户名已被占用")):a()}))}},i=function(e,i,a){if(Object(z["c"])(i)){var n={field:"mobile",value:i,self:t.uid,utype:t.form.utype};Object(d["d"])(n).then((function(t){0==t.data?a(new Error("手机号已被占用")):a()}))}else a(new Error("请输入正确的手机号"))},a=function(e,i,a){t.uid>0?a():""==i?a(new Error("请输入密码")):a()};return{placeholder:"如不修改请留空",infoLoading:!1,options:[],form:{uid:0,utype:"1",username:"",password:"",mobile:""},rules:{username:[{required:!0,message:"请输入用户名",trigger:"blur"},{min:6,max:30,message:"长度在 6 到 30 个字",trigger:"blur"},{validator:e,trigger:"blur"}],password:[{validator:a,trigger:"blur"},{min:6,max:15,message:"长度在 6 到 15 个字",trigger:"blur"}],mobile:[{required:!0,message:"请输入手机号",trigger:"blur"},{validator:i,trigger:"blur"}]},utypeDisabled:!0}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var t=this,e=this.uid;if(e>0){var i={uid:e};Object(d["f"])(i,"get").then((function(e){t.form=Object(S["a"])({},e.data.info),t.form.utype=t.form.utype+"",t.infoLoading=!1})).catch((function(){}))}else"company"==this.listtype?(this.form.utype="1",this.utypeDisabled=!1):"personal"==this.listtype?(this.form.utype="2",this.utypeDisabled=!1):this.utypeDisabled=!0,this.placeholder="",this.infoLoading=!1},addSave:function(t,e){var i=this;this.$refs[e].validate((function(e){if(!e)return!1;Object(d["c"])(t).then((function(t){return i.$message.success(t.message),"invalid"!=i.listtype?1==i.form.utype?i.closeDialogCompany():i.closeDialogPersonal():i.closeDialog(),i.pageReload(),!0})).catch((function(){}))}))},editSave:function(t,e){var i=this;this.$refs[e].validate((function(e){if(!e)return!1;Object(d["f"])(t).then((function(t){return i.$message.success(t.message),i.closeDialog(),i.pageReload(),!0})).catch((function(){}))}))},onSubmit:function(t){var e=this,i=Object(S["a"])({},e.form);parseInt(i.uid)>0?e.editSave(i,t):e.addSave(i,t)},closeDialog:function(){this.$emit("setDialogFormVisible")},closeDialogCompany:function(){this.$emit("setDialogFormVisibleCompany")},closeDialogPersonal:function(){this.$emit("setDialogFormVisiblePersonal")},pageReload:function(){this.$emit("pageReload")}}},P=F,V=(i("9636"),Object(h["a"])(P,I,$,!1,null,"a61884d6",null)),T=V.exports,E=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{"label-width":"100px"}},[i("el-form-item",{attrs:{label:"UID"}},[t._v(" "+t._s(t.info.uid)+" ")]),i("el-form-item",{attrs:{label:"身份"}},[t._v(" "+t._s(1==t.info.utype?"企业会员":"个人会员")+" ")]),i("el-form-item",{attrs:{label:"用户名"}},[t._v(" "+t._s(t.info.username)+" ")]),i("el-form-item",{attrs:{label:"手机号"}},[t._v(" "+t._s(t.info.mobile)+" ")]),i("el-form-item",{attrs:{label:"注册时间"}},[t._v(" "+t._s(t._f("timeFilter")(t.info.reg_time))+"【IP:"+t._s(t.info.reg_ip+"（"+t.info.reg_address+"）")+"】 ")]),0==t.info.last_login_time?i("el-form-item",{attrs:{label:"最近登录"}},[t._v(" 从未登录 ")]):i("el-form-item",{attrs:{label:"最近登录"}},[t._v(" "+t._s(t._f("timeFilter")(t.info.last_login_time))+"【IP:"+t._s(t.info.last_login_ip+"（"+t.info.last_login_address+"）")+"】 ")]),i("el-form-item",{attrs:{label:" "}},[i("el-button",{on:{click:t.closeDialog}},[t._v("关闭")])],1)],1)],1)},B=[],U={filters:{timeFilter:function(t){return Object(f["b"])(t,"{y}-{m}-{d} {h}:{i}")}},props:["uid","detailInfo"],data:function(){return{infoLoading:!0,info:{}}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){this.info=Object(S["a"])({},this.detailInfo),this.infoLoading=!1},closeDialog:function(){this.$emit("setDialogFormVisible")}}},R=U,A=(i("376e"),Object(h["a"])(R,E,B,!1,null,"4c3fb2d0",null)),K=A.exports,W={components:{diaform:T,detail:K,MemberLog:L},filters:{timeFilter:function(t){return Object(f["b"])(t,"{y}-{m}-{d} {h}:{i}")}},props:["listtype","showOptionsUtype"],data:function(){return{setImVal:0,platformOptions:[],detailInfo:null,detailUid:0,dialogWidth:"35%",dialogContent:"form",tableIdarr:[],ImUid:"",childId:0,dialogTitle:"",dialogFormVisible:!1,dialogImFormVisible:!1,setImReason:"",status:"",regtime:"",is_openid:"",is_email:"",sort:"",platform:"",list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,key_type:"1",keyword:"",dialogListVisible:!1,listUid:0,utype:""}},created:function(){this.fetchData(),this.fetchPlatformOptions()},methods:{modifyState:function(t){var e=this;!1===t.display?this.$confirm("确定锁定该会员吗? 锁定后该会员将无法登录。","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){e.saveState(t)})).catch((function(){return e.fetchData(),!1})):this.saveState(t)},saveState:function(t){var e=this,i={status:t.display,uid:t.uid};Object(d["i"])(i).then((function(t){return e.$message.success(t.message),e.fetchData(),!0})).catch((function(){}))},funManagement:function(t){var e=this,i={uid:t.uid};Object(d["a"])(i).then((function(t){return 200==t.code?(Object(f["c"])(t.data),window.open(e.$store.state.config.sitedomain+e.$store.state.config.sitedir+e.$store.state.config.member_dirname),!0):(e.$message.error(t.message),!1)}))},fetchData:function(){var t=this;this.listLoading=!0;var e=this.listtype,i={list_type:e,status:this.status,regtime:this.regtime,is_openid:this.is_openid,is_email:this.is_email,sort:this.sort,platform:this.platform,utype:this.utype,key_type:this.key_type,keyword:this.keyword,page:this.currentPage,pagesize:this.pagesize};Object(d["h"])(i).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},fetchPlatformOptions:function(){var t=this,e={type:"platform"};Object(j["a"])(e).then((function(e){t.platformOptions=Object(o["a"])(e.data)}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearch:function(){this.fetchData()},funSearchKeyword:function(){this.currentPage=1,this.fetchData()},closeDialog:function(){this.dialogWidth="35%",this.dialogContent="form",this.dialogFormVisible=!1},closeDialogCompany:function(){var t=this;this.dialogFormVisible=!1,this.$confirm("该会员还没有完善企业资料，您可在无效会员列表查看","提示",{confirmButtonText:"进入无效会员列表",cancelButtonText:"留在当前页",type:"warning"}).then((function(){t.$router.push("/user/member/invalid")})).catch((function(){}))},closeDialogPersonal:function(){var t=this;this.dialogFormVisible=!1,this.$confirm("该会员还没有填写简历信息，您可在无效会员列表查看","提示",{confirmButtonText:"进入无效会员列表",cancelButtonText:"留在当前页",type:"warning"}).then((function(){t.$router.push("/user/member/invalid")})).catch((function(){}))},funAdd:function(t,e){this.childId=0,this.dialogWidth="35%","company"==this.listtype?this.dialogTitle="添加【企业】会员":"personal"==this.listtype?this.dialogTitle="添加【个人】会员":this.dialogTitle="添加【无效】会员",this.dialogFormVisible=!0},funDetail:function(t,e){this.detailInfo=e,this.dialogWidth="35%",this.dialogContent="detail",this.detailUid=e.uid,this.dialogTitle="查看会员",this.dialogFormVisible=!0},funLog:function(t,e){this.listUid=e.uid,this.dialogListVisible=!0},closeListDialog:function(){this.dialogListVisible=!1},funEdit:function(t,e){this.childId=e?e.uid:0,this.dialogWidth="35%",this.dialogTitle="编辑会员",this.dialogFormVisible=!0},funLock:function(t,e){var i=this,a="",n="";1==e.status?(a="确定锁定该会员吗？",n=0):(a="确定解锁该会员吗？",n=1),i.$confirm(a,"提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={uid:e.uid,status:n};Object(d["i"])(t).then((function(t){return i.$message.success(t.message),i.fetchData(),!0}))})).catch((function(){}))},funIm:function(t){var e=this;if(1==t.disable_im){var i="确定将该会员解除禁聊吗？",a=0;this.$confirm(i,"提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var i={uid:t.uid,disable_im:a};Object(d["g"])(i).then((function(t){return e.$message.success(t.message),e.fetchData(),!0}))})).catch((function(){}))}else this.setImReason="",this.setImVal=t.audit,this.ImUid=t.uid,this.dialogImFormVisible=!0},funSetIm:function(){var t=this,e={uid:this.ImUid,disable_im:1,reason:this.setImReason};Object(d["g"])(e).then((function(e){return t.$message.success(e.message),t.fetchData(),t.dialogImFormVisible=!1,!0}))},funDelete:function(t){var e=this,i=1==t.utype?"删除企业会员将删除此会员的一切信息，包括企业资料、在招职位、下载的简历等，删除后不可恢复。是否继续？":"删除个人会员将删除此会员的一切信息，包括简历、投递记录等信息，删除后不可恢复。是否继续？";e.$confirm(i,"提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var i={uid:[t.uid]};Object(d["e"])(i).then((function(t){return e.$message.success(t.message),e.fetchData(),!0}))})).catch((function(){}))},funDeleteBatch:function(){if(0==this.tableIdarr.length)return this.$message.error("请选择要删除的会员"),!1;var t=this,e="删除企业会员将删除选中会员的一切信息，删除后不可恢复。是否继续？";"company"==t.listtype?e="删除企业会员将删除选中会员的一切信息，包括企业资料、在招职位、下载的简历等，删除后不可恢复。是否继续？":"personal"==t.listtype&&(e="删除个人会员将删除选中会员的一切信息，包括简历、投递记录等信息，删除后不可恢复。是否继续？"),t.$confirm(e,"提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={uid:t.tableIdarr};Object(d["e"])(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0}))})).catch((function(){}))},handleSelectionChange:function(t){if(this.tableIdarr=[],t.length>0){var e,i=Object(l["a"])(t);try{for(i.s();!(e=i.n()).done;){var a=e.value;this.tableIdarr.push(a.uid)}}catch(n){i.e(n)}finally{i.f()}}}}},N=W,M=Object(h["a"])(N,a,n,!1,null,null,null);e["a"]=M.exports},"1c04":function(t,e,i){"use strict";i("8aaf")},"1e81":function(t,e,i){},"376e":function(t,e,i){"use strict";i("4bdb")},"4bdb":function(t,e,i){},"52b5":function(t,e,i){"use strict";i.d(e,"a",(function(){return l}));var a=i("b775"),n=i("d722");function l(t){return Object(a["a"])({url:n["a"].getClassify,method:"get",params:t})}},8194:function(t,e,i){"use strict";i.d(e,"h",(function(){return l})),i.d(e,"c",(function(){return o})),i.d(e,"f",(function(){return s})),i.d(e,"e",(function(){return r})),i.d(e,"d",(function(){return c})),i.d(e,"i",(function(){return u})),i.d(e,"g",(function(){return d})),i.d(e,"l",(function(){return f})),i.d(e,"k",(function(){return m})),i.d(e,"m",(function(){return p})),i.d(e,"j",(function(){return h})),i.d(e,"b",(function(){return g})),i.d(e,"a",(function(){return b}));var a=i("b775"),n=i("d722");function l(t){return Object(a["a"])({url:n["a"].memberList,method:"get",params:t})}function o(t){return Object(a["a"])({url:n["a"].memberAdd,method:"post",data:t})}function s(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(a["a"])({url:n["a"].memberEdit,method:e,data:t}):Object(a["a"])({url:n["a"].memberEdit,method:e,params:t})}function r(t){return Object(a["a"])({url:n["a"].memberDelete,method:"post",data:t})}function c(t){return Object(a["a"])({url:n["a"].memberCheckUnique,method:"post",data:t})}function u(t){return Object(a["a"])({url:n["a"].memberLock,method:"post",data:t})}function d(t){return Object(a["a"])({url:n["a"].memberIm,method:"post",data:t})}function f(t){return Object(a["a"])({url:n["a"].memberPointsList,method:"get",params:t})}function m(t){return Object(a["a"])({url:n["a"].memberPointsEdit,method:"post",data:t})}function p(t){return Object(a["a"])({url:n["a"].memberPointsLog,method:"get",params:t})}function h(t){return Object(a["a"])({url:n["a"].memberLoginLog,method:"get",params:t})}function g(t){return Object(a["a"])({url:n["a"].memberActionLog,method:"get",params:t})}function b(t){return Object(a["a"])({url:n["a"].management,method:"get",params:t})}},"8aaf":function(t,e,i){},9636:function(t,e,i){"use strict";i("1e81")}}]);