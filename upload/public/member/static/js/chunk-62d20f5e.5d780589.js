(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-62d20f5e"],{"0e0a":function(t,a,e){},1984:function(t,a,e){"use strict";e.r(a);var i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"p_recommend"},[e("personal-title",[t._v("智能推荐")]),t.intentionList.length>0?e("menu-nav",{attrs:{list:t.intentionList,"active-tab":t.params.id}}):t._e(),e("div",{staticClass:"p_group"},[t._l(t.dataset,(function(a,i){return e("div",{key:i,staticClass:"p_item"},[e("div",{staticClass:"p_name"},[1===a.emergency?e("div",{staticClass:"worry"},[t._v("急")]):t._e(),e("div",{staticClass:"name substring"},[e("a",{attrs:{href:a.job_link_url_web,target:"_blank"}},[t._v(t._s(a.jobname))])]),e("div",{staticClass:"clear"}),e("div",{staticClass:"wage"},[t._v(t._s(a.wage_text))])]),e("div",{staticClass:"p_info"},[e("div",{staticClass:"info_item"},[t._v(t._s(a.education_text))]),e("div",{staticClass:"info_item"},[t._v(t._s(a.experience_text))]),e("div",{staticClass:"info_item substring"},[t._v(t._s(a.district_text))]),e("div",{staticClass:"clear"})]),e("div",{staticClass:"p_tag"},[t._l(a.tag_text_arr.slice(0,5),(function(a,i){return e("div",{key:i,staticClass:"tag_item"},[t._v(t._s(a))])})),e("div",{staticClass:"clear"})],2)])})),e("div",{staticClass:"clear"})],2),e("pagination",{attrs:{total:t.total,"current-page":t.params.page,"page-size":t.params.pagesize},on:{handleCurrentChange:t.doSearch}})],1)},n=[],s=(e("4160"),e("159b"),e("2909")),r=e("751a"),c=e("d722"),o={name:"PersonalRecommend",data:function(){return{intentionList:[],listLoading:!1,dataset:[],total:0,params:{id:0,page:1,pagesize:10}}},watch:{$route:function(t){void 0!==t.query.id&&(this.params.id=t.query.id,this.dataset=[],this.fetchData(!0))}},created:function(){void 0!==this.$route.query.id&&(this.params.id=this.$route.query.id),this.fetchDataIntention()},methods:{fetchDataIntention:function(){var t=this;r["a"].get(c["a"].get_intentions,{}).then((function(a){var e=0;a.data.items.forEach((function(a){var i={label:a.category_text,href:"/personal/recommend?id="+a.id,name:a.id+"",active:0==e};t.intentionList.push(i),0!=e||t.params.id||(t.params.id=a.id),e++})),t.intentionList.length>0&&t.fetchData(!0)})).catch((function(){}))},fetchData:function(t){var a=this;this.listLoading=!0,r["a"].get(c["a"].recommend_joblist,this.params).then((function(e){a.dataset=Object(s["a"])(e.data.items),a.listLoading=!1,!0===t&&a.fetchDataTotal()})).catch((function(){}))},fetchDataTotal:function(){var t=this;r["a"].get(c["a"].recommend_joblist_total,this.params).then((function(a){t.total=a.data})).catch((function(){}))},doSearch:function(t){this.params.page=t,this.fetchData()}}},d=o,l=(e("cdca"),e("2877")),u=Object(l["a"])(d,i,n,!1,null,"646354a6",null);a["default"]=u.exports},2909:function(t,a,e){"use strict";e.d(a,"a",(function(){return o}));var i=e("6b75");function n(t){if(Array.isArray(t))return Object(i["a"])(t)}e("a4d3"),e("e01a"),e("d28b"),e("a630"),e("d3b7"),e("3ca3"),e("ddb0");function s(t){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}var r=e("06c5");function c(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function o(t){return n(t)||s(t)||Object(r["a"])(t)||c()}},cdca:function(t,a,e){"use strict";var i=e("0e0a"),n=e.n(i);n.a}}]);