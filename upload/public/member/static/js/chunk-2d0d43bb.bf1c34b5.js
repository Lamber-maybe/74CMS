(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0d43bb"],{"5fc7":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("JobForm",{ref:"child",attrs:{type:"edit"},on:{submit:t.submit}})],1)},c=[],r=(a("d3b7"),a("ac1f"),a("25f0"),a("1276"),a("981b")),o=a("751a"),s=a("d722"),n={components:{JobForm:r["a"]},data:function(){return{}},created:function(){this.fetchData(this.$route.params.id)},methods:{fetchData:function(t){var e=this,a=this.$loading({lock:!0,text:"Loading"});o["a"].get(s["a"].company_jobedit_pre,{id:t}).then((function(t){e.$refs.child.field_rule=t.data.field_rule;var i=t.data.basic,c=t.data.contact;i.citycategory_arr=[],i.jobcategory_arr=[],0!=i.category1&&i.jobcategory_arr.push(i.category1),0!=i.category2&&i.jobcategory_arr.push(i.category2),0!=i.category3&&i.jobcategory_arr.push(i.category3),0!=i.district1&&i.citycategory_arr.push(i.district1),0!=i.district2&&i.citycategory_arr.push(i.district2),0!=i.district3&&i.citycategory_arr.push(i.district3);for(var r=i.tag.toString(),o=r.split(","),s=0;s<o.length;s++)isNaN(o[s])||(o[s]=parseInt(o[s]));i.minwage&&e.$refs.child.handleMaxwageChange(i.minwage),i.minage&&e.$refs.child.handleMaxageChange(i.minage),!1===e.$refs.child.field_rule.basic.negotiable?i.negotiable=!1:i.negotiable=1==i.negotiable,i.age_na=1==i.age_na,i.tag=o,c.mobile_show=1==c.mobile_show,c.telephone_show=1==c.telephone_show,e.$refs.child.form.basic=i,e.$refs.child.form.contact=c,e.$refs.child.contactHidden=1!=c.is_display,e.$refs.child.weixin_sync_mobile=c.weixin===c.mobile,a.close()})).catch((function(){}))},submit:function(t){var e=this;o["a"].post(s["a"].company_jobedit_save,t).then((function(t){e.$message({type:"success",message:t.message}),e.$router.push("/company/joblist")})).catch((function(){}))}}},d=n,h=a("2877"),l=Object(h["a"])(d,i,c,!1,null,"4f087f8a",null);e["default"]=l.exports}}]);