(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-b3277094"],{"1d6e":function(t,e,s){},2909:function(t,e,s){"use strict";s.d(e,"a",(function(){return r}));var a=s("6b75");function i(t){if(Array.isArray(t))return Object(a["a"])(t)}s("a4d3"),s("e01a"),s("d28b"),s("a630"),s("d3b7"),s("3ca3"),s("ddb0");function n(t){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}var c=s("06c5");function l(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function r(t){return i(t)||n(t)||Object(c["a"])(t)||l()}},"54a7":function(t,e,s){"use strict";var a=s("1d6e"),i=s.n(a);i.a},a1fc:function(t,e,s){"use strict";var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("el-alert",{attrs:{title:t.title,type:"warning","show-icon":"",closable:!1}})},i=[],n={data:function(){return{}},props:["title"]},c=n,l=(s("a4ce"),s("2877")),r=Object(l["a"])(c,a,i,!1,null,"61586efe",null);e["a"]=r.exports},a4ce:function(t,e,s){"use strict";var a=s("fe26"),i=s.n(a);i.a},b563:function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("warning",{attrs:{title:t.title}}),s("div",{staticClass:"shield_header"},[s("span",[t._v("您已屏蔽"+t._s(t.num)+"个公司")]),s("el-button",{attrs:{icon:"el-icon-plus"},on:{click:function(e){t.dialogVisible=!0}}},[t._v("添加屏蔽企业")])],1),s("ul",{staticClass:"shield_box"},t._l(t.blacklist,(function(e){return s("li",{key:e.id},[s("span",{staticClass:"margin"},[t._v(t._s(e.companyname))]),s("span",{staticClass:"margin2",on:{click:function(s){return t.handleDelete(e.id)}}},[t._v("取消屏蔽")])])})),0),s("el-dialog",{attrs:{title:"添加屏蔽企业",visible:t.dialogVisible,width:"540px"},on:{"update:visible":function(e){t.dialogVisible=e}}},[s("shield-dialog",{ref:"sonV",attrs:{AllSelect:t.AllSelect,dialog:t.dialogVisible},on:{search:t.search,footer_btn:t.btn,selectChangebox:t.selectArr}}),s("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[s("span",{directives:[{name:"show",rawName:"v-show",value:t.switch_btn,expression:"switch_btn"}],staticClass:"footer_btn"},[s("span",[t.checkboxIndex?s("el-checkbox",{on:{change:t.handleAllSelect},model:{value:t.checked,callback:function(e){t.checked=e},expression:"checked"}},[t._v(t._s(t.checked?"取消全选":"全选"))]):t._e()],1),s("el-button",{attrs:{type:"primary"},on:{click:t.handleShield}},[t._v("屏蔽所选公司")])],1)])],1)],1)},i=[],n=(s("4160"),s("d81d"),s("159b"),s("2909")),c=s("751a"),l=s("d722"),r=s("a1fc"),o=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{staticClass:"s_bar"},[s("el-input",{staticClass:"input-with-select",style:{border:0},attrs:{placeholder:"请输入内容"},model:{value:t.search_text,callback:function(e){t.search_text=e},expression:"search_text"}}),s("el-button",{staticClass:"el_button",attrs:{icon:"el-icon-search"},on:{click:t.fetchData}})],1),t.changeBox?s("div",{staticClass:"search_wrapper"},[0==!t.search_list.length?s("div",{staticClass:"search_box"},[s("p",[t._v("与"+t._s(this.search_text)+"有关的公司共"+t._s(t.num)+"个")]),s("ul",{staticClass:"search_list"},t._l(t.search_list,(function(e,a){return s("li",{key:a},[s("div",{staticClass:"checkbox"},[e.in_blacklist?t._e():s("el-checkbox",{attrs:{checked:e.type},on:{change:function(s){return t.selectIndex(e)}},model:{value:e.type,callback:function(s){t.$set(e,"type",s)},expression:"i.type"}})],1),s("div",{staticClass:"search_list_text"},[s("p",[t._v(t._s(e.companyname))]),e.scale_text?s("p",{staticClass:"people"},[s("span",[t._v(t._s(e.scale_text))]),t._v(" | "),s("span",[t._v(t._s(e.trade_text))]),t._v(" | "),s("span",[t._v(t._s(e.nature_text))])]):t._e()]),e.in_blacklist?s("div",{staticClass:"shield_box"},[t._v(" 已屏蔽该企业 ")]):t._e()])})),0)]):s("div",{staticClass:"search_error"},[t._v(" 没有搜到您搜索的公司 ")])]):s("div",{staticClass:"text"},[s("span",[t._v("可通过以下方式搜索公司")]),s("p",[t._v("公司全称：如“百度（中国）有限公司”")]),s("p",[t._v("公司简称：如“百度”")]),s("p",[t._v("屏蔽企业后，该企业将无法查看您的简历")])])])},h=[],d={data:function(){return{search_text:"",changeBox:!1,num:0,search_list:[],selectChangebox:[]}},props:["dialog","AllSelect"],watch:{dialog:function(t){1==t&&(this.changeBox=!1,this.$emit("footer_btn",!1)),this.search_text=""},changeBox:function(){this.$emit("footer_btn",this.changeBox)}},methods:{fetchData:function(){var t=this;this.search_list=[],""!=this.search_text?(this.changeBox=!0,c["a"].get(l["a"].resume_privates_searchCompany,{keyword:this.search_text}).then((function(e){200==parseInt(e.code)&&(t.num=e.data.items.length,t.search_list=Object(n["a"])(e.data.items),t.search_list.forEach((function(t){t.type=!1})),t.$emit("search",t.search_list))}))):(this.changeBox=!1,this.$message.error("请输入搜索内容"))},selectIndex:function(t){this.$forceUpdate(),this.$emit("selectChangebox",t)},handleAllSelect:function(t){this.$set(this.search_list,this.search_list.type,t.type),this.$forceUpdate()}}},u=d,_=(s("54a7"),s("2877")),f=Object(_["a"])(u,o,h,!1,null,"21385a7d",null),p=f.exports,b={data:function(){return{checked:!1,title:"屏蔽后，关键词覆盖到的企业都不能看到我！",dialogVisible:!1,D_selectArr:[],blacklist:[],is_display:0,switch_btn:!1,AllSelect:[],num:0,checkboxIndex:0}},created:function(){this.fetchData()},computed:{},methods:{handleShield:function(){var t=this;c["a"].post(l["a"].resume_privates_add_blacklist,{id:this.D_selectArr}).then((function(){t.fetchData()})),this.dialogVisible=!1,this.checked=!1},selectArr:function(t){this.D_selectArr.push(t.id)},btn:function(t){this.switch_btn=t},search:function(t){var e=this;this.checkboxIndex=0,this.AllSelect=t,this.AllSelect.forEach((function(t){0==t.in_blacklist&&e.checkboxIndex++}))},fetchData:function(){var t=this;c["a"].get(l["a"].resume_privates_info).then((function(e){t.blacklist=Object(n["a"])(e.data.blacklist),t.num=t.blacklist.length}))},handleDelete:function(t){var e=this;c["a"].post(l["a"].resume_privates_deleteBlacklist,{id:t}).then((function(){e.fetchData()}))},handleAllSelect:function(t){var e=this;t?(this.AllSelect.map((function(t){0==t.in_blacklist&&(t.type=!0,e.D_selectArr.push(t.id))})),this.$refs.sonV.handleAllSelect(this.AllSelect)):(this.AllSelect.map((function(t){t.type=!1})),this.D_selectArr=[],this.$refs.sonV.handleAllSelect(this.AllSelect))}},components:{warning:r["a"],shieldDialog:p}},v=b,x=(s("ddea"),Object(_["a"])(v,a,i,!1,null,"e025e170",null));e["default"]=x.exports},ddea:function(t,e,s){"use strict";var a=s("fe97"),i=s.n(a);i.a},fe26:function(t,e,s){},fe97:function(t,e,s){}}]);