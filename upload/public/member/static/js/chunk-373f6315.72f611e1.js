(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-373f6315"],{"1d69":function(t,e,n){"use strict";n("c871")},"80d5":function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("Feedback")},s=[],a=n("d3ff"),o={components:{Feedback:a["a"]}},c=o,i=n("5d22"),l=Object(i["a"])(c,r,s,!1,null,null,null);e["default"]=l.exports},c871:function(t,e,n){},d3ff:function(t,e,n){"use strict";var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"feedbackBox"},[n("div",{staticClass:"title"},[t._v(" 用户您好，请将您的意见、想法、建议或投诉内容告诉我们，以帮助我们为全体用户提供更加优质的服务。"),n("br"),t._v("我们将在第一时间及时回复您的反馈，如您的问题比较紧急，请致电服务热线："+t._s(t.$store.state.config.contact_tel)+"。 ")]),n("div",{staticClass:"opinion_content"},[n("el-form",{ref:"form",attrs:{model:t.form,rules:t.rules,"label-width":"80px"}},[n("el-form-item",{attrs:{label:"意见类型"}},t._l(t.options_feedback,(function(e,r){return n("el-button",{key:r,staticClass:"opinionTypeItem",attrs:{size:"small",type:e.id==t.form.type?"primary":"",round:""},on:{click:function(n){return t.handlerType(e)}}},[t._v(t._s(e.text))])})),1),n("el-form-item",{attrs:{label:"反馈内容",prop:"content"}},[n("el-input",{attrs:{type:"textarea",rows:"8",placeholder:"请描述具体操作步骤及问题，有助于我们快速定位并解决"},model:{value:t.form.content,callback:function(e){t.$set(t.form,"content",e)},expression:"form.content"}})],1),n("el-button",{staticClass:"sub",attrs:{type:"primary"},on:{click:function(e){return t.handleSubmit("form")}}},[t._v("提交反馈")])],1)],1)])},s=[],a=n("751a"),o=n("d722"),c={name:"AccountFeedback",data:function(){return{form:{type:0,content:""},rules:{content:[{required:!0,message:"请输入反馈内容",trigger:"blur"},{max:200,message:"最多输入200个字",trigger:"blur"}]}}},created:function(){var t=this;this.$store.dispatch("getClassify","feedback").then((function(e){t.form.type=e.data[0].id}))},computed:{options_feedback:function(){return this.$store.state.classifyFeedback}},methods:{handlerType:function(t){this.form.type=t.id},handleSubmit:function(t){var e=this;if(0==this.form.type)return this.$message.error("请选择反馈类型"),!1;this.$refs[t].validate((function(t){if(!t)return!1;a["a"].post(o["a"].feedback,e.form).then((function(t){e.$message({type:"success",message:t.message}),e.form.content=""})).catch((function(){}))}))}}},i=c,l=(n("1d69"),n("5d22")),u=Object(l["a"])(i,r,s,!1,null,null,null);e["a"]=u.exports}}]);