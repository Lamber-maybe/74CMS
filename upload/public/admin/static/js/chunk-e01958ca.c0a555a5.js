(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-e01958ca"],{1064:function(t,e,r){"use strict";var a=r("8ce3"),i=r.n(a);i.a},"52b5":function(t,e,r){"use strict";r.d(e,"a",(function(){return o}));var a=r("b775"),i=r("d722");function o(t){return Object(a["a"])({url:i["a"].getClassify,method:"get",params:t})}},"7dee":function(t,e,r){"use strict";r.r(e);var a=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"app-container"},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("span",[t._v("添加分站")]),r("el-button",{staticStyle:{float:"right",padding:"0","margin-left":"14px"},attrs:{type:"text"},on:{click:function(e){return t.goto("/subsite/index")}}},[t._v(" 返回 ")]),r("el-button",{staticStyle:{float:"right",padding:"0"},attrs:{type:"text"},on:{click:function(e){return t.onSubmit("form")}}},[t._v(" 保存 ")])],1),r("el-form",{ref:"form",staticClass:"common-form",attrs:{model:t.form,"label-width":"120px","inline-message":!0,rules:t.rules}},[r("el-form-item",{attrs:{label:"分站名称",prop:"sitename"}},[r("el-input",{model:{value:t.form.sitename,callback:function(e){t.$set(t.form,"sitename",e)},expression:"form.sitename"}})],1),r("el-form-item",{attrs:{label:"绑定地区",prop:"citycategory_arr"}},[r("el-cascader",{attrs:{filterable:"",props:{checkStrictly:!0},options:t.options_citycategory,"show-all-levels":!1},model:{value:t.form.citycategory_arr,callback:function(e){t.$set(t.form,"citycategory_arr",e)},expression:"form.citycategory_arr"}})],1),r("el-form-item",{attrs:{label:"seo标题",prop:"title"}},[r("el-input",{model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),r("el-form-item",{attrs:{label:"seo关键词",prop:"keywords"}},[r("el-input",{model:{value:t.form.keywords,callback:function(e){t.$set(t.form,"keywords",e)},expression:"form.keywords"}})],1),r("el-form-item",{attrs:{label:"seo描述",prop:"description"}},[r("el-input",{attrs:{type:"textarea"},model:{value:t.form.description,callback:function(e){t.$set(t.form,"description",e)},expression:"form.description"}})],1),r("el-form-item",{attrs:{label:"模板",prop:"tpl"}},[r("el-select",{attrs:{placeholder:"请选择模板"},model:{value:t.form.tpl,callback:function(e){t.$set(t.form,"tpl",e)},expression:"form.tpl"}},t._l(t.options_tpl,(function(t,e){return r("el-option",{key:e,attrs:{label:t.name,value:t.id}})})),1)],1),r("el-form-item",{attrs:{label:"是否显示",prop:"is_display"}},[r("el-radio-group",{model:{value:t.form.is_display,callback:function(e){t.$set(t.form,"is_display",e)},expression:"form.is_display"}},[r("el-radio",{attrs:{label:1}},[t._v("显示")]),r("el-radio",{attrs:{label:0}},[t._v("不显示")])],1)],1),r("el-form-item",{attrs:{label:"排序",prop:"sort_id"}},[r("el-input",{model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",e)},expression:"form.sort_id"}})],1),r("el-form-item",{attrs:{label:""}},[r("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")]),r("el-button",{on:{click:function(e){return t.goto("/subsite/index")}}},[t._v("返回")])],1)],1)],1)],1)},i=[],o=(r("d81d"),r("2909")),l=r("a8c9"),n=r("52b5"),s={data:function(){return{form:{sitename:"",district1:0,district2:0,district3:0,citycategory_arr:[],tpl:"",title:"",keywords:"",description:"",is_display:1,sort_id:0},options_citycategory:[],options_tpl:[],rules:{sitename:[{required:!0,message:"请输入分站名称",trigger:"blur"}],citycategory_arr:[{required:!0,message:"请选择绑定地区",trigger:"change"}],tpl:[{required:!0,message:"请选择模板",trigger:"change"}]}}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;Object(n["a"])({type:"citycategory,tplOfIndex"}).then((function(e){t.options_tpl=Object(o["a"])(e.data.tplOfIndex),t.options_citycategory=Object(o["a"])(e.data.citycategory),t.options_citycategory=t.options_citycategory.map((function(t){if(t.children.length){var e=t.children;return e=e.map((function(t){return t.children.length?{label:t.label,value:t.value,children:t.children}:{label:t.label,value:t.value}})),{value:t.value,label:t.label,children:e}}return{value:t.value,label:t.label}}))}))},onSubmit:function(t){var e=this,r=this;this.$refs[t].validate((function(t){if(!t)return!1;var a=JSON.parse(JSON.stringify(e.form)),i=a.citycategory_arr;a.district1=i[0],a.district2=void 0!==i[1]?i[1]:0,a.district3=void 0!==i[2]?i[2]:0,Object(l["a"])(a).then((function(t){return e.$message.success(t.message),setTimeout((function(){r.$router.push("/subsite/index")}),1500),!0})).catch((function(){}))}))},goto:function(t){this.$router.push(t)}}},c=s,u=(r("1064"),r("2877")),d=Object(u["a"])(c,a,i,!1,null,"0ac76b2e",null);e["default"]=d.exports},"8ce3":function(t,e,r){},a8c9:function(t,e,r){"use strict";r.d(e,"d",(function(){return o})),r.d(e,"a",(function(){return l})),r.d(e,"c",(function(){return n})),r.d(e,"b",(function(){return s}));var a=r("b775"),i=r("d722");function o(t){return Object(a["a"])({url:i["a"].subsiteList,method:"get",params:t})}function l(t){return Object(a["a"])({url:i["a"].subsiteAdd,method:"post",data:t})}function n(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(a["a"])({url:i["a"].subsiteEdit,method:e,data:t}):Object(a["a"])({url:i["a"].subsiteEdit,method:e,params:t})}function s(t){return Object(a["a"])({url:i["a"].subsiteDelete,method:"post",data:t})}}}]);