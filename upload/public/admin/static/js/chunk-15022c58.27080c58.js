(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-15022c58"],{"0088":function(e,t,a){},"12b1":function(e,t,a){"use strict";a.r(t);for(var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("el-tabs",{attrs:{type:"border-card"}},[a("el-tab-pane",{attrs:{label:"采集设置",lazy:!0}},[a("seting")],1),a("el-tab-pane",{attrs:{label:"职位设置",lazy:!0}},[a("job_seting")],1),a("el-tab-pane",{attrs:{label:"企业设置",lazy:!0}},[a("company_seting")],1),a("el-tab-pane",{attrs:{label:"账号设置",lazy:!0}},[a("account_seting")],1)],1)],1)},o=[],i=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("el-form",{ref:"form",staticClass:"common-form",attrs:{"label-width":"200px","inline-message":!0}},[a("el-form-item",{attrs:{label:"开启采集功能"}},[a("el-switch",{model:{value:e.is_open,callback:function(t){e.is_open=t},expression:"is_open"}})],1),a("el-form-item",{attrs:{label:"匹配精准度"}},[a("el-input",{attrs:{placeholder:"请输入0 ~ 100之间的数"},model:{value:e.matching_accuracy,callback:function(t){e.matching_accuracy=t},expression:"matching_accuracy"}})],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:e.onSubmit}},[e._v(" 保存 ")])],1)],1)],1)},n=[],s=a("8fd1"),l={data:function(){return{is_open:!1,matching_accuracy:""}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;Object(s["a"])({},"get").then((function(t){e.data=t.data,e.is_open=1==e.data.status,e.matching_accuracy=e.data.matching_accuracy})).catch((function(){}))},onSubmit:function(){var e=this,t={status:this.is_open?"1":"0",matching_accuracy:this.matching_accuracy?this.matching_accuracy:"0"};Object(s["e"])(t).then((function(t){return e.$message.success(t.message),!0})).catch((function(){}))}}},c=l,m=(a("1e22"),a("2877")),u=Object(m["a"])(c,i,n,!1,null,"4667e234",null),f=u.exports,p=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("el-form",{ref:"form",staticClass:"common-form",attrs:{"label-width":"200px","inline-message":!0}},[a("el-form-item",{attrs:{label:"招聘状态",prop:"recruit_status"}},[a("el-radio-group",{model:{value:e.form.recruit_status,callback:function(t){e.$set(e.form,"recruit_status",t)},expression:"form.recruit_status"}},e._l(e.options_display,(function(t,r){return a("el-radio",{key:r,attrs:{label:t.id}},[e._v(" "+e._s(t.name)+" ")])})),1)],1),a("el-form-item",{attrs:{label:"审核状态",prop:"audit_status"}},[a("el-radio-group",{model:{value:e.form.audit_status,callback:function(t){e.$set(e.form,"audit_status",t)},expression:"form.audit_status"}},e._l(e.options_audit,(function(t,r){return a("el-radio",{key:r,attrs:{label:t.id}},[e._v(" "+e._s(t.name)+" ")])})),1)],1),a("el-form-item",{attrs:{label:"职位性质无法匹配时为",prop:"nature"}},[a("el-radio-group",{model:{value:e.form.nature,callback:function(t){e.$set(e.form,"nature",t)},expression:"form.nature"}},e._l(e.options_nature,(function(t,r){return a("el-radio",{key:r,attrs:{label:t.id}},[e._v(" "+e._s(t.name)+" ")])})),1)],1),a("el-form-item",{attrs:{label:"职位类别无法匹配时为",prop:"jobcategory_arr"}},[a("el-cascader",{attrs:{options:e.options_jobcategory,"show-all-levels":!1,filterable:""},model:{value:e.form.jobcategory_arr,callback:function(t){e.$set(e.form,"jobcategory_arr",t)},expression:"form.jobcategory_arr"}})],1),a("el-form-item",{attrs:{label:"学历要求无法匹配时为",prop:"education"}},[a("el-select",{attrs:{placeholder:"请选择学历要求"},model:{value:e.form.education,callback:function(t){e.$set(e.form,"education",t)},expression:"form.education"}},[a("el-option",{attrs:{label:"不限学历",value:0}}),e._l(e.options_education,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})}))],2)],1),a("el-form-item",{attrs:{label:"工作经验无法匹配时为",prop:"experience"}},[a("el-select",{attrs:{placeholder:"请选择工作经验"},model:{value:e.form.experience,callback:function(t){e.$set(e.form,"experience",t)},expression:"form.experience"}},[a("el-option",{attrs:{label:"不限经验",value:0}}),e._l(e.options_experience,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})}))],2)],1),a("el-form-item",{attrs:{label:"招聘人数无法匹配时为",prop:"recruit_num"}},[a("el-input",{model:{value:e.form.recruit_num,callback:function(t){e.$set(e.form,"recruit_num",e._n(t))},expression:"form.recruit_num"}}),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),e._v(" 0表示若干 ")])],1),a("el-form-item",{attrs:{label:"薪资待遇无法匹配时为",prop:"minwage"}},[a("el-select",{staticClass:"wage-sel",attrs:{placeholder:"请选择"},on:{change:e.minwage_change},model:{value:e.form.minwage,callback:function(t){e.$set(e.form,"minwage",t)},expression:"form.minwage"}},e._l(e.options_minwage,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1),e._v(" - "),a("el-select",{staticClass:"wage-sel",attrs:{placeholder:"请选择"},model:{value:e.form.maxwage,callback:function(t){e.$set(e.form,"maxwage",t)},expression:"form.maxwage"}},e._l(e.options_maxwage,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1)],1),a("el-form-item",{attrs:{label:"工作地区无法匹配时为",prop:"citycategory_arr"}},[a("el-cascader",{attrs:{options:e.options_citycategory,"show-all-levels":!1},model:{value:e.form.citycategory_arr,callback:function(t){e.$set(e.form,"citycategory_arr",t)},expression:"form.citycategory_arr"}})],1),a("el-form-item",{attrs:{label:"岗位福利无法匹配时为",prop:"welfare"}},[a("el-select",{staticClass:"tag-input",attrs:{multiple:"",filterable:"","allow-create":"","default-first-option":"",placeholder:"请选择岗位福利","multiple-limit":6},model:{value:e.form.welfare,callback:function(t){e.$set(e.form,"welfare",t)},expression:"form.welfare"}},e._l(e.options_tag,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1)],1),a("el-form-item",{attrs:{label:"年龄要求无法匹配时为",prop:"minwage"}},[a("el-select",{staticClass:"age-sel",attrs:{disabled:!0===e.form.age_na,placeholder:"请选择"},on:{change:e.minage_change},model:{value:e.form.minage,callback:function(t){e.$set(e.form,"minage",t)},expression:"form.minage"}},e._l(e.options_minage,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1),e._v(" - "),a("el-select",{staticClass:"age-sel",attrs:{disabled:!0===e.form.age_na,placeholder:"请选择"},model:{value:e.form.maxage,callback:function(t){e.$set(e.form,"maxage",t)},expression:"form.maxage"}},e._l(e.options_maxage,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1),a("el-checkbox",{staticStyle:{"margin-left":"10px"},model:{value:e.form.age_na,callback:function(t){e.$set(e.form,"age_na",t)},expression:"form.age_na"}},[e._v("不限 ")])],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:e.onSubmit}},[e._v(" 保存 ")])],1)],1)],1)},_=[],d=(a("c975"),a("d81d"),a("b64b"),a("d3b7"),a("ac1f"),a("25f0"),a("1276"),a("52b5")),g=[],b=16;b<=65;b++)g.push(b);for(var y=[],h=0,v=500,x=5e3,w=0;w<37;w++)h+=h<15e3?v:x,y.push(h);var j={data:function(){return{options_display:[],options_audit:[],options_nature:[],options_jobcategory:[],options_education:[],options_experience:[],options_minwage:[],options_maxwage:[],options_minage:[],options_maxage:[],options_citycategory:[],options_tag:[],form:{recruit_status:"",audit_status:"",nature:"",jobcategory_arr:"",education:"",experience:"",recruit_num:"",citycategory_arr:"",welfare:"",minwage:"",maxwage:"",minage:"",maxage:"",age_na:!1}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;Object(d["a"])({type:"jobDisplay,jobAudit,jobNature,jobcategory,citycategory,jobTag,education,experience"},"get").then((function(t){e.options_display=t.data.jobDisplay,e.options_audit=t.data.jobAudit,e.options_nature=t.data.jobNature,e.options_jobcategory=t.data.jobcategory,e.options_education=t.data.education,e.options_experience=t.data.experience;for(var a=0;a<y.length;a++){var r={id:y[a],name:y[a]+"元/月"};e.options_minwage.push(r)}e.options_citycategory=t.data.citycategory,e.options_citycategory=e.options_citycategory.map((function(e){if(e.children.length){var t=e.children;return t=t.map((function(e){return e.children.length?{label:e.label,value:e.value,children:e.children}:{label:e.label,value:e.value}})),{value:e.value,label:e.label,children:t}}return{value:e.value,label:e.label}})),e.options_tag=t.data.jobTag;for(var o=0;o<g.length;o++){var i={id:g[o],name:g[o]+"岁"};e.options_minage.push(i)}})).catch((function(){})),Object(s["a"])({},"get").then((function(t){if(e.job_seting=t.data.job_seting,Object.keys(e.job_seting).length){e.form=e.job_seting,e.form.jobcategory_arr=[],e.form.citycategory_arr=[],0!=e.job_seting.category1&&e.form.jobcategory_arr.push(e.job_seting.category1),0!=e.job_seting.category2&&e.form.jobcategory_arr.push(e.job_seting.category2),0!=e.job_seting.category3&&e.form.jobcategory_arr.push(e.job_seting.category3),0!=e.job_seting.district1&&e.form.citycategory_arr.push(e.job_seting.district1),0!=e.job_seting.district2&&e.form.citycategory_arr.push(e.job_seting.district2),0!=e.job_seting.district3&&e.form.citycategory_arr.push(e.job_seting.district3);for(var a=e.form.welfare.split(","),r=0;r<a.length;r++)isNaN(a[r])||(a[r]=parseInt(a[r]));e.form.welfare=a,e.form.minwage&&e.handle_maxwage(e.form.minwage),e.form.minage&&e.handle_maxage(e.form.minage),e.form.age_na=1==e.form.age_na}})).catch((function(){}))},minwage_change:function(e){this.handle_maxwage(e)},handle_maxwage:function(e){if(!e)return!1;this.options_maxwage=[];var t=y.indexOf(e)+1;e>=this.form.maxwage&&(this.form.maxwage=y[t]);for(var a=t;a<y.length;a++){var r={id:y[a],name:y[a]+"元/月"};this.options_maxwage.push(r)}},minage_change:function(e){this.handle_maxage(e)},handle_maxage:function(e){if(!e)return!1;this.options_maxage=[];var t=g.indexOf(e)+1;e>=this.form.maxage&&(this.form.maxage=g[t]);for(var a=t;a<g.length;a++){var r={id:g[a],name:g[a]+"岁"};this.options_maxage.push(r)}},onSubmit:function(){var e=this,t={recruit_status:this.form.recruit_status,audit_status:this.form.audit_status,nature:this.form.nature,category1:this.form.jobcategory_arr[0],category2:this.form.jobcategory_arr[1],category3:this.form.jobcategory_arr[2],experience:this.form.experience,education:this.form.education,recruit_num:this.form.recruit_num,minwage:this.form.minwage,maxwage:this.form.maxwage,district1:this.form.citycategory_arr[0],district2:this.form.citycategory_arr[1],district3:this.form.citycategory_arr[2],welfare:this.form.welfare.toString(),minage:this.form.minage,maxage:this.form.maxage,age_na:1==this.form.age_na?1:0};Object(s["d"])(t).then((function(t){return e.$message.success(t.message),!0})).catch((function(){}))}}},k=j,$=(a("3118"),Object(m["a"])(k,p,_,!1,null,"19a63cdd",null)),O=$.exports,C=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("el-form",{ref:"form",staticClass:"common-form",attrs:{"label-width":"200px","inline-message":!0}},[a("el-form-item",{attrs:{label:"显示状态",prop:"is_display"}},[a("el-radio-group",{model:{value:e.form.is_display,callback:function(t){e.$set(e.form,"is_display",t)},expression:"form.is_display"}},e._l(e.options_display,(function(t,r){return a("el-radio",{key:r,attrs:{label:t.id}},[e._v(" "+e._s(t.name)+" ")])})),1)],1),a("el-form-item",{attrs:{label:"认证状态",prop:"audit_status"}},[a("el-radio-group",{model:{value:e.form.audit_status,callback:function(t){e.$set(e.form,"audit_status",t)},expression:"form.audit_status"}},e._l(e.options_audit,(function(t,r){return a("el-radio",{key:r,attrs:{label:t.id}},[e._v(" "+e._s(t.name)+" ")])})),1)],1),a("el-form-item",{attrs:{label:"企业性质无法匹配时为",prop:"nature"}},[a("el-select",{attrs:{placeholder:"请选择企业性质"},model:{value:e.form.nature,callback:function(t){e.$set(e.form,"nature",t)},expression:"form.nature"}},e._l(e.options_nature,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1)],1),a("el-form-item",{attrs:{label:"公司规模无法匹配时为",prop:"scale"}},[a("el-select",{attrs:{placeholder:"请选择公司规模"},model:{value:e.form.scale,callback:function(t){e.$set(e.form,"scale",t)},expression:"form.scale"}},e._l(e.options_scale,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1)],1),a("el-form-item",{attrs:{label:"所属行业无法匹配时为",prop:"trade"}},[a("el-select",{attrs:{placeholder:"请选择所属行业"},model:{value:e.form.trade,callback:function(t){e.$set(e.form,"trade",t)},expression:"form.trade"}},e._l(e.options_trade,(function(e,t){return a("el-option",{key:t,attrs:{label:e.name,value:e.id}})})),1)],1),a("el-form-item",{attrs:{label:"注册资金无法匹配时为",prop:"registered"}},[a("el-input",{staticClass:"input-with-select",attrs:{placeholder:"请输入注册资金"},model:{value:e.form.registered,callback:function(t){e.$set(e.form,"registered",t)},expression:"form.registered"}},[a("el-select",{staticClass:"input-sel",attrs:{slot:"append",placeholder:"请选择"},slot:"append",model:{value:e.form.currency,callback:function(t){e.$set(e.form,"currency",t)},expression:"form.currency"}},[a("el-option",{attrs:{label:"万人民币",value:"0"}}),a("el-option",{attrs:{label:"万美元",value:"1"}})],1)],1)],1),a("el-form-item",{attrs:{label:"所在地区无法匹配时为",prop:"citycategory_arr"}},[a("el-cascader",{attrs:{options:e.options_citycategory,"show-all-levels":!1},model:{value:e.form.citycategory_arr,callback:function(t){e.$set(e.form,"citycategory_arr",t)},expression:"form.citycategory_arr"}})],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:e.onSubmit}},[e._v(" 保存 ")])],1)],1)],1)},S=[],D={data:function(){return{options_display:[{id:1,name:"显示"},{id:0,name:"不显示"}],options_audit:[],options_nature:[],options_scale:[],options_trade:[],options_citycategory:[],form:{is_display:"",audit_status:"",nature:"",scale:"",trade:"",registered:"",currency:"0",citycategory_arr:""}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;Object(d["a"])({type:"companyDisplay,companyAudit,companyNature,trade,citycategory,companyScale,jobTag,setmealList"},"get").then((function(t){e.options_audit=t.data.companyAudit,e.options_nature=t.data.companyNature,e.options_scale=t.data.companyScale,e.options_trade=t.data.trade,e.options_citycategory=t.data.citycategory,e.options_citycategory=e.options_citycategory.map((function(e){if(e.children.length){var t=e.children;return t=t.map((function(e){return e.children.length?{label:e.label,value:e.value,children:e.children}:{label:e.label,value:e.value}})),{value:e.value,label:e.label,children:t}}return{value:e.value,label:e.label}}))})).catch((function(){})),Object(s["a"])({},"get").then((function(t){e.company_seting=t.data.company_seting,Object.keys(e.company_seting).length&&(e.form=e.company_seting,e.form.currency=String(e.form.currency),e.form.citycategory_arr=[],0!=e.company_seting.district1&&e.form.citycategory_arr.push(e.company_seting.district1),0!=e.company_seting.district2&&e.form.citycategory_arr.push(e.company_seting.district2),0!=e.company_seting.district3&&e.form.citycategory_arr.push(e.company_seting.district3))})).catch((function(){}))},onSubmit:function(){var e=this,t={is_display:this.form.is_display,audit_status:this.form.audit_status,nature:this.form.nature,scale:this.form.scale,trade:this.form.trade,registered:this.form.registered,currency:this.form.currency,district1:this.form.citycategory_arr[0],district2:this.form.citycategory_arr[1],district3:this.form.citycategory_arr[2]};Object(s["c"])(t).then((function(t){return e.$message.success(t.message),!0})).catch((function(){}))}}},N=D,E=(a("ee4d"),Object(m["a"])(N,C,S,!1,null,"5de9d8aa",null)),z=E.exports,A=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("el-form",{ref:"form",staticClass:"common-form",attrs:{"label-width":"200px","inline-message":!0}},[a("el-form-item",{attrs:{label:"生成用户名前缀"}},[a("el-input",{model:{value:e.form.name_prefix,callback:function(t){e.$set(e.form,"name_prefix",t)},expression:"form.name_prefix"}})],1),a("el-form-item",{attrs:{label:"生成用户名规则",prop:"name_rule"}},[a("el-radio-group",{model:{value:e.form.name_rule,callback:function(t){e.$set(e.form,"name_rule",t)},expression:"form.name_rule"}},e._l(e.options_name_rule,(function(t,r){return a("el-radio",{key:r,attrs:{label:t.id}},[e._v(" "+e._s(t.name)+" ")])})),1),a("br"),a("span",{staticClass:"smalltip"},[a("i",{staticClass:"el-icon-info"}),e._v(" 前缀 + 规则 ")])],1),a("el-form-item",{attrs:{label:"生成密码规则",prop:"pwd_rule"}},[a("el-radio-group",{model:{value:e.form.pwd_rule,callback:function(t){e.$set(e.form,"pwd_rule",t)},expression:"form.pwd_rule"}},e._l(e.options_pwd_rule,(function(t,r){return a("el-radio",{key:r,attrs:{label:t.id}},[e._v(" "+e._s(t.name)+" ")])})),1)],1),a("el-form-item",{directives:[{name:"show",rawName:"v-show",value:2==e.form.pwd_rule,expression:"form.pwd_rule==2"}],attrs:{label:"生成指定密码"}},[a("el-input",{model:{value:e.form.password,callback:function(t){e.$set(e.form,"password",t)},expression:"form.password"}})],1),a("el-form-item",{attrs:{label:""}},[a("el-button",{attrs:{type:"primary"},on:{click:e.onSubmit}},[e._v(" 保存 ")])],1)],1)],1)},T=[],J={data:function(){return{options_name_rule:[{id:1,name:"随机字符串"},{id:2,name:"手机号"}],options_pwd_rule:[{id:1,name:"与用户名相同"},{id:2,name:"指定密码"}],form:{name_prefix:"",name_rule:"",pwd_rule:"",password:""}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;Object(s["a"])({},"get").then((function(t){e.accountSeting=t.data.account_seting,Object.keys(e.accountSeting).length&&(e.form=e.accountSeting)})).catch((function(){}))},onSubmit:function(){var e=this,t={name_prefix:this.form.name_prefix,name_rule:this.form.name_rule,pwd_rule:this.form.pwd_rule,password:this.form.password};Object(s["b"])(t).then((function(t){return e.$message.success(t.message),!0})).catch((function(){}))}}},I=J,L=(a("7164"),Object(m["a"])(I,A,T,!1,null,"c81bb6be",null)),q=L.exports,B={components:{seting:f,job_seting:O,company_seting:z,account_seting:q}},F=B,G=Object(m["a"])(F,r,o,!1,null,null,null);t["default"]=G.exports},"1e22":function(e,t,a){"use strict";var r=a("c983"),o=a.n(r);o.a},3118:function(e,t,a){"use strict";var r=a("66a7"),o=a.n(r);o.a},"52b5":function(e,t,a){"use strict";a.d(t,"a",(function(){return i}));var r=a("b775"),o=a("d722");function i(e){return Object(r["a"])({url:o["a"].getClassify,method:"get",params:e})}},"66a7":function(e,t,a){},7164:function(e,t,a){"use strict";var r=a("0088"),o=a.n(r);o.a},b2da:function(e,t,a){},c983:function(e,t,a){},ee4d:function(e,t,a){"use strict";var r=a("b2da"),o=a.n(r);o.a}}]);