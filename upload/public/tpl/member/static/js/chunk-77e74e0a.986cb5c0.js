(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-77e74e0a"],{2909:function(t,r,a){"use strict";a.d(r,"a",(function(){return n}));var e=a("6b75");function o(t){if(Array.isArray(t))return Object(e["a"])(t)}a("a4d3"),a("e01a"),a("d3b7"),a("d28b"),a("3ca3"),a("ddb0"),a("a630");function i(t){if("undefined"!==typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}var s=a("06c5");a("d9e2");function c(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function n(t){return o(t)||i(t)||Object(s["a"])(t)||c()}},"5f6b":function(t,r,a){"use strict";a("80f4")},"80f4":function(t,r,a){},a7c9:function(t,r,a){"use strict";a.r(r);var e=function(){var t=this,r=t.$createElement,a=t._self._c||r;return a("div",{staticClass:"main"},[a("el-form",{ref:"form",attrs:{"label-width":"80px",model:t.form,rules:t.rules}},[a("el-form-item",{attrs:{label:"期望职位",prop:"categoryArr"}},[a("el-cascader",{ref:"intCategory",attrs:{placeholder:"请选择期望职位",options:t.columnsJobcategory,filterable:""},model:{value:t.form.categoryArr,callback:function(r){t.$set(t.form,"categoryArr",r)},expression:"form.categoryArr"}})],1),a("el-form-item",{attrs:{label:"工作地区",prop:"districtArr"}},[a("el-cascader",{ref:"intDistrict",attrs:{placeholder:"请选择期望地区",options:t.columnsCitycategory,props:{checkStrictly:!0},filterable:""},model:{value:t.form.districtArr,callback:function(r){t.$set(t.form,"districtArr",r)},expression:"form.districtArr"}})],1),a("el-form-item",{attrs:{label:"最低薪资",prop:"minwage"}},[a("el-select",{staticClass:"w118",attrs:{placeholder:"请选择"},on:{change:t.handlerMinwageChange},model:{value:t.form.minwage,callback:function(r){t.$set(t.form,"minwage",r)},expression:"form.minwage"}},t._l(t.columnsMinWage,(function(t,r){return a("el-option",{key:r,attrs:{label:t.name,value:t.id}})})),1)],1),a("el-form-item",{attrs:{label:"最高薪资",prop:"maxwage"}},[a("el-select",{staticClass:"w118",attrs:{placeholder:"请选择"},model:{value:t.form.maxwage,callback:function(r){t.$set(t.form,"maxwage",r)},expression:"form.maxwage"}},t._l(t.options_maxwage,(function(t,r){return a("el-option",{key:r,attrs:{label:t.name,value:t.id}})})),1)],1),a("el-form-item",[a("el-button",{attrs:{type:"primary"},on:{click:t.onSubmit}},[t._v("确定")]),!0===t.hasInfo?a("el-button",{on:{click:t.onCancel}},[t._v("取消订阅")]):t._e()],1)],1)],1)},o=[],i=a("2909"),s=a("751a"),c=a("d722"),n={data:function(){return{form:{categoryArr:[],districtArr:[],minwage:"",maxwage:""},optionCategory:[],optionDistrict:[],options_maxwage:[],hasInfo:!1,rules:{categoryArr:[{required:!0,message:"请选择期望职位",trigger:"change"}],districtArr:[{required:!0,message:"请选择期望地区",trigger:"change"}],minwage:[{required:!0,message:"请选择最低薪资",trigger:"change"}],maxwage:[{required:!0,message:"请选择最高薪资",trigger:"change"}]}}},created:function(){this.fetchData()},computed:{columnsCitycategory:function(){return this.$store.state.classifyCityCategory},columnsJobcategory:function(){return this.$store.state.classifyJobCategory},columnsMinWage:function(){var t=[],r=Object(i["a"])(this.$store.state.classifyWage);r.length>0&&r.pop();for(var a=0;a<r.length;a++){var e={id:r[a],name:r[a]+"元/月"};t.push(e)}return t}},mounted:function(){this.$store.dispatch("getClassify","jobcategory"),this.$store.dispatch("getClassify","citycategory"),this.$store.dispatch("getClassifyWage")},methods:{fetchData:function(){var t=this;s["a"].get(c["a"].personal_subscribe_job).then((function(r){null!==r.data&&(t.hasInfo=!0,t.form.minwage=r.data.minwage>0?r.data.minwage:"",t.form.maxwage=r.data.maxwage>0?r.data.maxwage:"",""!=t.form.minwage&&t.handlerMinwageChange(t.form.minwage),r.data.category1=r.data.category1>0?r.data.category1:0,r.data.category2=r.data.category2>0?r.data.category2:0,r.data.category3=r.data.category3>0?r.data.category3:0,r.data.category1>0&&(t.form.categoryArr.push(r.data.category1),r.data.category2>0&&(t.form.categoryArr.push(r.data.category2),r.data.category3>0&&t.form.categoryArr.push(r.data.category3))),r.data.district1>0&&(t.form.districtArr.push(r.data.district1),r.data.district2>0&&(t.form.districtArr.push(r.data.district2),r.data.district3>0&&t.form.districtArr.push(r.data.district3))))}))},handlerMinwageChange:function(t){this.handleMaxwageChange(t)},handleMaxwageChange:function(t){var r=this.$store.state.classifyWage;if(!t)return!1;this.options_maxwage=[];var a=r.indexOf(t)+1;t>=this.form.maxwage&&(this.form.maxwage=r[a]);for(var e=a;e<r.length;e++){var o={id:r[e],name:r[e]+"元/月"};this.options_maxwage.push(o)}},onSubmit:function(){var t=this,r={category1:0,category2:0,category3:0,district1:0,district2:0,district3:0,minwage:this.form.minwage,maxwage:this.form.maxwage};3===this.form.categoryArr.length?(r.category1=this.form.categoryArr[0],r.category2=this.form.categoryArr[1],r.category3=this.form.categoryArr[2]):2===this.form.categoryArr.length?(r.category1=this.form.categoryArr[0],r.category2=this.form.categoryArr[1]):1===this.form.categoryArr.length&&(r.category1=this.form.categoryArr[0]),3===this.form.districtArr.length?(r.district1=this.form.districtArr[0],r.district2=this.form.districtArr[1],r.district3=this.form.districtArr[2]):2===this.form.districtArr.length?(r.district1=this.form.districtArr[0],r.district2=this.form.districtArr[1]):1===this.form.districtArr.length&&(r.district1=this.form.districtArr[0]),this.$refs.form.validate((function(a){if(!a)return!1;s["a"].post(c["a"].personal_subscribe_job_save,r).then((function(r){t.$message({type:"success",message:r.message}),t.hasInfo=!0}))}))},onCancel:function(){var t=this;s["a"].post(c["a"].personal_subscribe_job_cancel).then((function(r){t.$message({type:"success",message:r.message}),t.hasInfo=!1,t.$refs.form.resetFields()}))}}},g=n,m=(a("5f6b"),a("2877")),l=Object(m["a"])(g,e,o,!1,null,"a7dcc2e4",null);r["default"]=l.exports}}]);