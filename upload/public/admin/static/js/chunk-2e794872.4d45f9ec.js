(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2e794872"],{"09fc":function(e,t,a){},"52b5":function(e,t,a){"use strict";a.d(t,"a",(function(){return r}));var o=a("b775"),i=a("d722");function r(e){return Object(o["a"])({url:i["a"].getClassify,method:"get",params:e})}},"76fe":function(e,t,a){"use strict";a.d(t,"e",(function(){return r})),a.d(t,"c",(function(){return l})),a.d(t,"b",(function(){return n})),a.d(t,"a",(function(){return s})),a.d(t,"f",(function(){return c}));var o=a("b775"),i=a("d722");function r(e){return Object(o["a"])({url:i["a"].jobList,method:"get",params:e})}function l(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==t?Object(o["a"])({url:i["a"].jobEdit,method:t,data:e}):Object(o["a"])({url:i["a"].jobEdit,method:t,params:e})}function n(e){return Object(o["a"])({url:i["a"].jobDelete,method:"post",data:e})}function s(e){return Object(o["a"])({url:i["a"].jobAudit,method:"post",data:e})}function c(e){return Object(o["a"])({url:i["a"].jobRefresh,method:"post",data:e})}},a776:function(e,t,a){"use strict";a.r(t);for(var o=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("el-form",{directives:[{name:"loading",rawName:"v-loading",value:e.infoLoading,expression:"infoLoading"}],ref:"form",staticClass:"common-form",attrs:{model:e.form,"label-width":"120px",rules:e.rules,"inline-message":!0}},[a("el-divider",{attrs:{"content-position":"left"}},[e._v("基本信息")]),a("el-form-item",{attrs:{label:"职位名称",prop:"jobname"}},[a("el-input",{model:{value:e.form.jobname,callback:function(t){e.$set(e.form,"jobname",t)},expression:"form.jobname"}})],1),a("el-form-item",{attrs:{label:"招聘状态",prop:"is_display"}},[a("el-radio-group",{model:{value:e.form.is_display,callback:function(t){e.$set(e.form,"is_display",t)},expression:"form.is_display"}},e._l(e.options_display,(function(t,o){return a("el-radio",{key:o,attrs:{label:t.id}},[e._v(" "+e._s(t.name)+" ")])})),1)],1),a("el-form-item",{attrs:{label:"审核状态",prop:"audit"}},[a("el-radio-group",{model:{value:e.form.audit,callback:function(t){e.$set(e.form,"audit",t)},expression:"form.audit"}},e._l(e.options_audit,(function(t,o){return a("el-radio",{key:o,attrs:{label:t.id}},[e._v(" "+e._s(t.name)+" ")])})),1)],1),a("el-form-item",{attrs:{label:"职位性质",prop:"nature"}},[a("el-radio-group",{model:{value:e.form.nature,callback:function(t){e.$set(e.form,"nature",t)},expression:"form.nature"}},e._l(e.options_nature,(function(t,o){return a("el-radio",{key:o,attrs:{label:t.id}},[e._v(" "+e._s(t.name)+" ")])})),1)],1),a("el-form-item",{attrs:{label:"职位类别",prop:"jobcategory_arr"}},[a("el-cascader",{attrs:{options:e.options_jobcategory,"show-all-levels":!1,filterable:""},model:{value:e.form.jobcategory_arr,callback:function(t){e.$set(e.form,"jobcategory_arr",t)},expression:"form.jobcategory_arr"}})],1),a("el-form-item",{attrs:{label:"学历要求",prop:"education"}},[a("el-select",{attrs:{placeholder:"请选择学历要求"},model:{value:e.form.education,callback:function(t){e.$set(e.form,"education",t)},expression:"form.education"}},[a("el-option",{attrs:{label:"不限学历",value:0}}),e._l(e.options_education,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})}))],2)],1),a("el-form-item",{attrs:{label:"工作经验",prop:"experience"}},[a("el-select",{attrs:{placeholder:"请选择工作经验"},model:{value:e.form.experience,callback:function(t){e.$set(e.form,"experience",t)},expression:"form.experience"}},[a("el-option",{attrs:{label:"不限经验",value:0}}),e._l(e.options_experience,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})}))],2)],1),!0===e.live_fields.amount?a("el-form-item",{attrs:{label:"招聘人数",prop:"amount"}},[a("el-input",{model:{value:e.form.amount,callback:function(t){e.$set(e.form,"amount",e._n(t))},expression:"form.amount"}}),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),e._v(" 0表示若干 ")])],1):e._e(),a("el-form-item",{attrs:{label:"薪资待遇",prop:"minwage"}},[a("el-select",{staticClass:"wage-sel",attrs:{disabled:!1!==e.form.negotiable,placeholder:"请选择"},on:{change:e.minwage_change},model:{value:e.form.minwage,callback:function(t){e.$set(e.form,"minwage",t)},expression:"form.minwage"}},e._l(e.options_minwage,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1),e._v(" - "),a("el-select",{staticClass:"wage-sel",attrs:{disabled:!1!==e.form.negotiable,placeholder:"请选择"},model:{value:e.form.maxwage,callback:function(t){e.$set(e.form,"maxwage",t)},expression:"form.maxwage"}},e._l(e.options_maxwage,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1),!0===e.live_fields.negotiable?a("el-checkbox",{staticStyle:{"margin-left":"10px"},model:{value:e.form.negotiable,callback:function(t){e.$set(e.form,"negotiable",t)},expression:"form.negotiable"}},[e._v(" 面议 ")]):e._e()],1),a("el-form-item",{attrs:{label:"工作地区",prop:"citycategory_arr"}},[a("el-cascader",{attrs:{options:e.options_citycategory,"show-all-levels":!1},model:{value:e.form.citycategory_arr,callback:function(t){e.$set(e.form,"citycategory_arr",t)},expression:"form.citycategory_arr"}})],1),a("el-form-item",{attrs:{label:"工作地址",prop:"address"}},[a("el-input",{model:{value:e.form.address,callback:function(t){e.$set(e.form,"address",t)},expression:"form.address"}})],1),a("el-form-item",{attrs:{label:"职位描述",prop:"content"}},[a("el-input",{attrs:{type:"textarea",rows:"10"},model:{value:e.form.content,callback:function(t){e.$set(e.form,"content",t)},expression:"form.content"}})],1),a("el-divider",{attrs:{"content-position":"left"}},[e._v("其他信息")]),!0===e.live_fields.tag?a("el-form-item",{attrs:{label:"岗位福利",prop:"tag"}},[a("el-select",{staticClass:"tag-input",attrs:{multiple:"",filterable:"","allow-create":"","default-first-option":"",placeholder:"请选择岗位福利","multiple-limit":6},model:{value:e.form.tag,callback:function(t){e.$set(e.form,"tag",t)},expression:"form.tag"}},e._l(e.options_tag,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1)],1):e._e(),!0===e.live_fields.department?a("el-form-item",{attrs:{label:"部门",prop:"department"}},[a("el-input",{model:{value:e.form.department,callback:function(t){e.$set(e.form,"department",t)},expression:"form.department"}})],1):e._e(),!0===e.live_fields.age?a("el-form-item",{attrs:{label:"年龄要求",prop:"minwage"}},[a("el-select",{staticClass:"age-sel",attrs:{disabled:!1!==e.form.age_na,placeholder:"请选择"},on:{change:e.minage_change},model:{value:e.form.minage,callback:function(t){e.$set(e.form,"minage",t)},expression:"form.minage"}},e._l(e.options_minage,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1),e._v(" - "),a("el-select",{staticClass:"age-sel",attrs:{disabled:!1!==e.form.age_na,placeholder:"请选择"},model:{value:e.form.maxage,callback:function(t){e.$set(e.form,"maxage",t)},expression:"form.maxage"}},e._l(e.options_maxage,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1),a("el-checkbox",{staticStyle:{"margin-left":"10px"},model:{value:e.form.age_na,callback:function(t){e.$set(e.form,"age_na",t)},expression:"form.age_na"}},[e._v("不限")])],1):e._e(),!0===e.live_fields.custom_field_1?a("el-form-item",{attrs:{label:e.custom_field_1_cn,prop:"custom_field_1"}},[a("el-input",{model:{value:e.form.custom_field_1,callback:function(t){e.$set(e.form,"custom_field_1",t)},expression:"form.custom_field_1"}})],1):e._e(),!0===e.live_fields.custom_field_2?a("el-form-item",{attrs:{label:e.custom_field_2_cn,prop:"custom_field_2"}},[a("el-input",{model:{value:e.form.custom_field_2,callback:function(t){e.$set(e.form,"custom_field_2",t)},expression:"form.custom_field_2"}})],1):e._e(),!0===e.live_fields.custom_field_3?a("el-form-item",{attrs:{label:e.custom_field_3_cn,prop:"custom_field_3"}},[a("el-input",{model:{value:e.form.custom_field_3,callback:function(t){e.$set(e.form,"custom_field_3",t)},expression:"form.custom_field_3"}})],1):e._e(),a("el-divider",{attrs:{"content-position":"left"}},[e._v("联系方式")]),a("el-form-item",{attrs:{label:"联系方式"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:e.form.contact.use_company_contact,callback:function(t){e.$set(e.form.contact,"use_company_contact",t)},expression:"form.contact.use_company_contact"}},[a("el-option",{attrs:{label:"使用企业资料联系方式",value:1}}),a("el-option",{attrs:{label:"使用其他联系方式",value:0}})],1)],1),0==e.form.contact.use_company_contact?a("div",[a("el-form-item",{attrs:{label:"联系人",prop:"contact.contact"}},[a("el-input",{model:{value:e.form.contact.contact,callback:function(t){e.$set(e.form.contact,"contact",t)},expression:"form.contact.contact"}})],1),a("el-form-item",{attrs:{label:"联系手机",prop:"contact.mobile"}},[a("el-input",{model:{value:e.form.contact.mobile,callback:function(t){e.$set(e.form.contact,"mobile",t)},expression:"form.contact.mobile"}})],1),!0===e.live_fields.contact.telephone?a("el-form-item",{attrs:{label:"联系座机",prop:"contact.telephone"}},[a("el-input",{model:{value:e.form.contact.telephone,callback:function(t){e.$set(e.form.contact,"telephone",t)},expression:"form.contact.telephone"}})],1):e._e(),!0===e.live_fields.contact.weixin?a("el-form-item",{attrs:{label:"联系微信",prop:"contact.weixin"}},[a("el-input",{model:{value:e.form.contact.weixin,callback:function(t){e.$set(e.form.contact,"weixin",t)},expression:"form.contact.weixin"}})],1):e._e(),!0===e.live_fields.contact.qq?a("el-form-item",{attrs:{label:"联系QQ",prop:"contact.qq"}},[a("el-input",{model:{value:e.form.contact.qq,callback:function(t){e.$set(e.form.contact,"qq",t)},expression:"form.contact.qq"}})],1):e._e(),!0===e.live_fields.contact.email?a("el-form-item",{attrs:{label:"联系邮箱",prop:"contact.email"}},[a("el-input",{model:{value:e.form.contact.email,callback:function(t){e.$set(e.form.contact,"email",t)},expression:"form.contact.email"}})],1):e._e()],1):e._e(),a("el-form-item",{attrs:{label:""}},[a("el-checkbox",{model:{value:e.contactHidden,callback:function(t){e.contactHidden=t},expression:"contactHidden"}},[e._v("联系方式保密（不想受到骚扰）")])],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary",loading:e.submitLoading,disabled:e.issubmit},on:{click:function(t){return e.onSubmit("form")}}},[e._v(" 保存 ")]),a("el-button",{on:{click:function(t){return e.goto("/user/job/list")}}},[e._v("返回")])],1)],1)],1)},i=[],r=a("2909"),l=a("5530"),n=(a("ac1f"),a("5319"),a("d81d"),a("d3b7"),a("25f0"),a("1276"),a("1063")),s=a("76fe"),c=a("61f7"),m=a("52b5"),u=[],f=16;f<=65;f++)u.push(f);for(var d=[],_=0,g=500,p=5e3,b=0;b<37;b++)_+=_<15e3?g:p,d.push(_);var v={data:function(){var e=function(e,t,a){Object(c["c"])(t)?a():a(new Error("请输入正确的手机号"))},t=function(e,t,a){Object(c["b"])(t)?a():a(new Error("请输入正确的邮箱"))};return{issubmit:!1,contactHidden:!1,infoLoading:!0,submitLoading:!1,options_display:[],options_audit:[],options_nature:[],options_education:[],options_experience:[],options_jobcategory:[],options_citycategory:[],options_tag:[],options_minwage:[],options_maxwage:[],options_minage:[],options_maxage:[],live_fields:{department:!0,age:!0,tag:!0,amount:!0,negotiable:!0,custom_field_1:!0,custom_field_2:!0,custom_field_3:!0,contact:{weixin:!0,telephone:!0,qq:!0,email:!0}},custom_field_1_cn:" ",custom_field_2_cn:" ",custom_field_3_cn:" ",form:{jobname:"",is_display:"",audit:"",nature:"",jobcategory_arr:"",citycategory_arr:"",minwage:"",maxwage:"",negotiable:!1,department:"",education:0,experience:0,minage:"",maxage:"",age_na:!1,amount:"",tag:[],content:"",address:"",custom_field_1:"",custom_field_2:"",custom_field_3:"",contact:{contact:"",mobile:"",weixin:"",telephone:"",qq:"",email:"",is_display:1,use_company_contact:1}},rules:{jobname:[{required:!0,message:"请输入职位名称",trigger:"blur"},{max:50,message:"长度在 1 到 50 个字符",trigger:"blur"}],nature:[{required:!0,message:"请选择职位性质",trigger:"change"}],jobcategory_arr:[{required:!0,message:"请选择职位分类",trigger:"change"}],citycategory_arr:[{required:!0,message:"请选择工作地区",trigger:"change"}],minwage:[{required:!0,message:"请选择薪资待遇",trigger:"change"}],department:[{required:!0,message:"请输入部门",trigger:"blur"},{max:15,message:"长度在 1 到 15 个字符",trigger:"blur"}],education:[{required:!0,message:"请选择学历要求",trigger:"change"}],experience:[{required:!0,message:"请选择工作经验",trigger:"change"}],minage:[{required:!0,message:"请选择年龄要求",trigger:"change"}],amount:[{required:!0,message:"请输入招聘人数",trigger:"blur"},{type:"number",message:"请正确输入招聘人数",trigger:"blur"}],content:[{required:!0,message:"请输入职位描述",trigger:"blur"}],tag:[{required:!0,message:"请选择岗位福利",trigger:"change"}],custom_field_1:[{required:!0,message:"请填写custom_field_1",trigger:"blur"}],custom_field_2:[{required:!0,message:"请填写custom_field_2",trigger:"blur"}],custom_field_3:[{required:!0,message:"请填写custom_field_3",trigger:"blur"}],contact:{contact:[{required:!0,message:"请输入联系人",trigger:"blur"},{max:30,message:"长度在 1 到 30 个字符",trigger:"blur"}],mobile:[{required:!0,message:"请输入联系手机",trigger:"blur"},{validator:e,trigger:"blur"}],weixin:[{required:!0,message:"请输入微信",trigger:"blur"}],telephone:[{required:!0,message:"请输入联系座机",trigger:"blur"}],qq:[{required:!0,message:"请输入联系QQ",trigger:"blur"}],email:[{required:!0,message:"请输入联系邮箱",trigger:"blur"},{validator:t,trigger:"blur"}]}}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;Object(n["c"])({},"get").then((function(t){var a=Object(l["a"])({},t.data),o=a.Job,i=a.JobContact,r={Job:o,JobContact:i};return e.custom_field_1_cn=r.Job.custom_field_1.field_cn,e.custom_field_2_cn=r.Job.custom_field_2.field_cn,e.custom_field_3_cn=r.Job.custom_field_3.field_cn,0==r.Job.department.is_display?(e.live_fields.department=!1,e.rules.department=[]):0==r.Job.department.is_require&&(e.rules.department=[]),0==r.Job.age.is_display?(e.live_fields.age=!1,e.rules.minage=[]):0==r.Job.age.is_require&&(e.rules.minage=[]),0==r.Job.tag.is_display?(e.live_fields.tag=!1,e.rules.tag=[]):0==r.Job.tag.is_require&&(e.rules.tag=[]),0==r.Job.amount.is_display?(e.live_fields.amount=!1,e.rules.amount=[]):0==r.Job.amount.is_require&&(e.rules.amount=[]),0==r.Job.negotiable.is_display&&(e.live_fields.negotiable=!1),0==r.Job.custom_field_1.is_display?(e.live_fields.custom_field_1=!1,e.rules.custom_field_1=[]):0==r.Job.custom_field_1.is_require?e.rules.custom_field_1=[]:e.rules.custom_field_1[0].message=e.rules.custom_field_1[0].message.replace("custom_field_1",e.custom_field_1_cn),0==r.Job.custom_field_2.is_display?(e.live_fields.custom_field_2=!1,e.rules.custom_field_2=[]):0==r.Job.custom_field_2.is_require?e.rules.custom_field_2=[]:e.rules.custom_field_2[0].message=e.rules.custom_field_2[0].message.replace("custom_field_2",e.custom_field_2_cn),0==r.Job.custom_field_3.is_display?(e.live_fields.custom_field_3=!1,e.rules.custom_field_3=[]):0==r.Job.custom_field_3.is_require?e.rules.custom_field_3=[]:e.rules.custom_field_3[0].message=e.rules.custom_field_3[0].message.replace("custom_field_3",e.custom_field_3_cn),0==r.JobContact.weixin.is_display?(e.live_fields.contact.weixin=!1,e.rules.contact.weixin=[]):0==r.JobContact.weixin.is_require&&(e.rules.contact.weixin=[]),0==r.JobContact.telephone.is_display?(e.live_fields.contact.telephone=!1,e.rules.contact.telephone=[]):0==r.JobContact.telephone.is_require&&(e.rules.contact.telephone=[]),0==r.JobContact.qq.is_display?(e.live_fields.contact.qq=!1,e.rules.contact.qq=[]):0==r.JobContact.qq.is_require&&(e.rules.contact.qq=[]),0==r.JobContact.email.is_display?(e.live_fields.contact.email=!1,e.rules.contact.email=[]):0==r.JobContact.email.is_require&&(e.rules.contact.email=[]),Object(m["a"])({type:"jobDisplay,jobAudit,jobNature,jobcategory,citycategory,jobTag,education,experience"})})).then((function(t){e.options_display=Object(r["a"])(t.data.jobDisplay),e.options_audit=Object(r["a"])(t.data.jobAudit),e.options_nature=Object(r["a"])(t.data.jobNature),e.options_jobcategory=Object(r["a"])(t.data.jobcategory),e.options_citycategory=Object(r["a"])(t.data.citycategory),e.options_jobcategory=e.options_jobcategory.map((function(e){if(e.children.length){var t=e.children;return t=t.map((function(e){return e.children.length?{label:e.label,value:e.value,children:e.children}:{label:e.label,value:e.value}})),{value:e.value,label:e.label,children:t}}return{value:e.value,label:e.label}})),e.options_citycategory=e.options_citycategory.map((function(e){if(e.children.length){var t=e.children;return t=t.map((function(e){return e.children.length?{label:e.label,value:e.value,children:e.children}:{label:e.label,value:e.value}})),{value:e.value,label:e.label,children:t}}return{value:e.value,label:e.label}})),e.options_tag=Object(r["a"])(t.data.jobTag),e.options_education=Object(r["a"])(t.data.education),e.options_experience=Object(r["a"])(t.data.experience);for(var a=0;a<d.length;a++){var o={id:d[a],name:d[a]+"元/月"};e.options_minwage.push(o)}e.options_minwage.pop();for(var i=0;i<u.length;i++){var l={id:u[i],name:u[i]+"岁"};e.options_minage.push(l)}return Object(s["c"])({id:e.$route.query.id},"get")})).then((function(t){e.form=Object(l["a"])({},t.data.info),e.form.citycategory_arr=[],e.form.jobcategory_arr=[],0!=t.data.info.category1&&e.form.jobcategory_arr.push(t.data.info.category1),0!=t.data.info.category2&&e.form.jobcategory_arr.push(t.data.info.category2),0!=t.data.info.category3&&e.form.jobcategory_arr.push(t.data.info.category3),0!=t.data.info.district1&&e.form.citycategory_arr.push(t.data.info.district1),0!=t.data.info.district2&&e.form.citycategory_arr.push(t.data.info.district2),0!=t.data.info.district3&&e.form.citycategory_arr.push(t.data.info.district3);var a=e.form.tag.toString(),o=[];if(0!==a.length){o=a.split(",");for(var i=0;i<o.length;i++)isNaN(o[i])||0==o[i]||(o[i]=parseInt(o[i]))}e.form.minwage&&e.handle_maxwage(e.form.minwage),e.form.minage&&e.handle_maxage(e.form.minage),!1===e.live_fields.negotiable?e.form.negotiable=!1:e.form.negotiable=1==e.form.negotiable,e.form.age_na=1==e.form.age_na,e.form.tag=o,e.contactHidden=1!=e.form.contact.is_display,e.infoLoading=!1})).catch((function(){}))},onSubmit:function(e){var t=this;if(!0===this.submitLoading)return!1;var a=this;a.issubmit=!0;var o=Object(l["a"])({},this.form);o.contact.is_display=!0===this.contactHidden?0:1,this.$refs[e].validate((function(e){if(!e)return a.issubmit=!1,t.submitLoading=!1,!1;var i=t.form.jobcategory_arr;o.category1=i[0],o.category2=void 0!==i[1]?i[1]:0,o.category3=void 0!==i[2]?i[2]:0;var r=t.form.citycategory_arr;o.district1=r[0],o.district2=void 0!==r[1]?r[1]:0,o.district3=void 0!==r[2]?r[2]:0,o.contact=Object(l["a"])({},t.form.contact),o.negotiable=1==o.negotiable?1:0,o.age_na=1==o.age_na?1:0,Object(s["c"])(o).then((function(e){return t.$message.success(e.message),setTimeout((function(){a.$router.push("/user/job/list")}),1500),!0})).catch((function(){return a.issubmit=!1,!1}))}))},goto:function(e){this.$router.push(e)},minwage_change:function(e){this.handle_maxwage(e)},handle_maxwage:function(e){if(!e)return!1;this.options_maxwage=[];var t=d.indexOf(e)+1;e>=this.form.maxwage&&(this.form.maxwage=d[t]);for(var a=t;a<d.length;a++){var o={id:d[a],name:d[a]+"元/月"};this.options_maxwage.push(o)}},minage_change:function(e){this.handle_maxage(e)},handle_maxage:function(e){if(!e)return!1;this.options_maxage=[];var t=u.indexOf(e)+1;e>=this.form.maxage&&(this.form.maxage=u[t]);for(var a=t;a<u.length;a++){var o={id:u[a],name:u[a]+"岁"};this.options_maxage.push(o)}}}},h=v,y=(a("c62d"),a("2877")),x=Object(y["a"])(h,o,i,!1,null,"075f19c6",null);t["default"]=x.exports},c62d:function(e,t,a){"use strict";a("09fc")}}]);