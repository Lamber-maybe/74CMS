(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0fa7bd95"],{4646:function(t,o,e){},"470e":function(t,o,e){"use strict";e.r(o);var a=function(){var t=this,o=t.$createElement,e=t._self._c||o;return e("div",{staticClass:"body"},[e("Head",[t._v("新增推广")]),e("van-field",{staticClass:"form_choose reset_after big_left",attrs:{readonly:"",clickable:"",value:t.companyname,label:"选择企业",placeholder:"请选择","input-align":"right",colon:!0},on:{click:function(o){t.showSearch=!0}}}),e("van-field",{staticClass:"form_choose reset_after",attrs:{readonly:"",clickable:"",value:t.jobname,label:"选择职位",placeholder:"选择职位","input-align":"right",colon:!0},on:{click:t.funSearchJob}}),e("van-field",{staticClass:"form_choose reset_after",attrs:{readonly:"",clickable:"",value:t.promotion_name,label:"推广方案",placeholder:"请选择","input-align":"right",colon:!0},on:{click:function(o){t.showPicker=!0}}}),e("van-field",{staticClass:"reset_after",attrs:{label:"推广天数",placeholder:"输入推广天数","input-align":"right",colon:!0,type:"number"},model:{value:t.form.days,callback:function(o){t.$set(t.form,"days",o)},expression:"form.days"}}),e("div",{staticClass:"bottom"},[e("van-button",{attrs:{type:"primary",block:"",color:"#1787fb"},on:{click:t.onSubmit}},[t._v("开通推广")])],1),e("van-popup",{staticStyle:{width:"80%",height:"100%"},attrs:{"lazy-render":!1,position:"right"},model:{value:t.showSearch,callback:function(o){t.showSearch=o},expression:"showSearch"}},[e("search-company",{on:{onConfirm:t.confirmCompany}})],1),e("van-popup",{staticStyle:{width:"80%",height:"100%"},attrs:{"lazy-render":!0,position:"right"},model:{value:t.showSearchJob,callback:function(o){t.showSearchJob=o},expression:"showSearchJob"}},[e("search-job",{attrs:{comid:t.comid},on:{onConfirm:t.confirmJob}})],1),e("van-popup",{attrs:{position:"bottom"},model:{value:t.showPicker,callback:function(o){t.showPicker=o},expression:"showPicker"}},[e("van-picker",{attrs:{"show-toolbar":"",columns:t.columnsPicker},on:{confirm:t.onConfirmPicker,cancel:function(o){t.showPicker=!1}}})],1)],1)},i=[],n=(e("5f5f"),e("f253")),c=e("751a"),s=e("d722"),r=e("9bac"),l=function(){var t=this,o=t.$createElement,e=t._self._c||o;return e("div",[e("div",{staticClass:"cc_top"},[t._v(" 该企业的在招职位 ")]),t.dataset.length>0?e("div",{staticClass:"cc_content"},[e("van-radio-group",{model:{value:t.selected_id,callback:function(o){t.selected_id=o},expression:"selected_id"}},t._l(t.dataset,(function(o,a){return e("div",{key:a,staticClass:"cc_item",on:{click:function(e){return t.funSelected(o)}}},[e("p",{staticClass:"t1 substring"},[t._v(t._s(o.jobname))]),e("van-radio",{staticClass:"absolute_for_y",attrs:{name:o.id}})],1)})),0)],1):e("van-empty",{staticStyle:{"background-color":"#fff"},attrs:{image:"search",description:"该企业没有在招职位"}}),e("div",{staticClass:"bottom"},[e("van-button",{attrs:{type:"primary",block:"",color:"#1787fb"},on:{click:t.funConfirm}},[t._v("确定")])],1)],1)},h=[],f=e("2909"),d={name:"searchJob",props:["comid"],data:function(){return{selected_id:1,row:{},dataset:[]}},created:function(){this.fetchData()},watch:{comid:function(){this.fetchData()}},methods:{funSelected:function(t){this.row=t,this.selected_id=t.id},funConfirm:function(){this.$emit("onConfirm",this.row)},fetchData:function(){var t=this,o={company_id:this.comid};c["a"].get(s["a"].searchJob,o).then((function(o){t.dataset=Object(f["a"])(o.data.items)})).catch((function(){}))}}},m=d,u=(e("47ee"),e("2877")),p=Object(u["a"])(m,l,h,!1,null,"dd1f420c",null),b=p.exports,v=e("2b0e");v["a"].use(n["a"]);var k={name:"promotionAdd",components:{searchCompany:r["a"],searchJob:b},data:function(){return{columnsPicker:[{text:"置顶",id:"jobstick"},{text:"紧急",id:"emergency"}],showPicker:!1,comid:"",jobname:"",promotion_name:"",companyname:"",showSearch:!1,showSearchJob:!1,form:{pid:0,type:"",days:""}}},created:function(){},methods:{funSearchJob:function(){if(!this.comid)return this.$toast.fail("请先选择企业"),!1;this.showSearchJob=!0},confirmJob:function(t){this.form.pid=t.id,this.jobname=t.jobname,this.showSearchJob=!1},confirmCompany:function(t){this.comid=t.id,this.companyname=t.companyname,this.form.pid=0,this.jobname="",this.showSearch=!1},onConfirmPicker:function(t){this.form.type=t.id,this.promotion_name=t.text,this.showPicker=!this.showPicker},onSubmit:function(){var t=this;return""==this.form.pid||0==this.form.pid?(this.$toast.fail("请选择职位"),!1):""==this.form.type?(this.$toast.fail("请选择推广方案"),!1):""==this.form.days?(this.$toast.fail("请输入推广天数"),!1):void c["a"].post(s["a"].promotionJobAdd,this.form).then((function(o){t.$toast.success(o.message),t.$router.push("/business")})).catch((function(){}))}}},y=k,w=(e("94c8"),Object(u["a"])(y,a,i,!1,null,"78c14207",null));o["default"]=w.exports},"47ee":function(t,o,e){"use strict";e("5939")},5939:function(t,o,e){},"94c8":function(t,o,e){"use strict";e("4646")}}]);