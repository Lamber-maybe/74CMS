(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-4136a3e2"],{"332f":function(t,a,e){"use strict";e.d(a,"a",(function(){return l}));var n=e("78b3");function i(t){if(Array.isArray(t))return Object(n["a"])(t)}e("79fd"),e("45af"),e("b1fa"),e("1ec5"),e("2e6f"),e("af21"),e("0a4f");function s(t){if("undefined"!==typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}var r=e("ec81");function o(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function l(t){return i(t)||s(t)||Object(r["a"])(t)||o()}},"3e6a":function(t,a,e){"use strict";e.r(a);var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"main_container"},[e("company-title",[t._v("智能推荐")]),t.published_joblist.length>0?e("menu-nav",{attrs:{list:t.published_joblist,"active-tab":t.params.id}}):t._e(),e("div",{staticClass:"my_table bg_th"},[e("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],staticStyle:{width:"100%"},attrs:{data:t.dataset}},[e("el-table-column",{attrs:{width:"380",prop:"information",label:"基本信息"},scopedSlots:t._u([{key:"default",fn:function(a){return[e("div",{staticClass:"tab_information"},[e("div",{staticClass:"information_img"},[e("img",{attrs:{src:a.row.photo_img_src}})]),e("div",{staticClass:"information_text"},[e("p",[e("a",{attrs:{target:"_blank",href:a.row.resume_link_url_web}},[t._v(t._s(a.row.fullname))])]),e("span",[t._v(t._s(a.row.age_text)+"岁 · "+t._s(a.row.sex_text)+" · "+t._s(a.row.education_text)+" · "+t._s(a.row.experience_text)+"经验")])])])]}}])}),e("el-table-column",{attrs:{prop:"intention_wage",label:"期望薪资"},scopedSlots:t._u([{key:"default",fn:function(a){return[e("span",{staticClass:"salary_text"},[t._v(t._s(a.row.intention_wage)+"/月")])]}}])}),e("el-table-column",{attrs:{prop:"current_text",label:"求职状态"},scopedSlots:t._u([{key:"default",fn:function(a){return[e("span",{staticClass:"current_text"},[t._v(t._s(a.row.current_text))])]}}])}),e("el-table-column",{attrs:{label:"求职意向"},scopedSlots:t._u([{key:"default",fn:function(a){return[e("p",{staticClass:"intention_type"},[e("span",[t._v("想找")]),t._v(" "+t._s(a.row.intention_jobs)+" "),e("span",[t._v("工作")])]),e("p",{staticClass:"intention_region"},[e("span",[t._v("想在")]),t._v(" "+t._s(a.row.intention_district)+" "),e("span",[t._v("工作")])])]}}])}),e("el-table-column",{attrs:{width:"190",align:"center",prop:"refreshtime",label:"刷新时间"},scopedSlots:t._u([{key:"default",fn:function(a){return[e("p",{staticClass:"date_text"},[t._v(t._s(a.row.refreshtime))])]}}])})],1)],1),e("pagination",{attrs:{total:t.total,"current-page":t.params.page,"page-size":t.params.pagesize},on:{handleCurrentChange:t.doSearch}})],1)},i=[],s=e("332f"),r=(e("6afd"),e("751a")),o=e("d722"),l={data:function(){return{published_joblist:[],listLoading:!1,dataset:[],total:0,params:{id:0,page:1,pagesize:10}}},watch:{$route:function(t){void 0!==t.query.id&&(this.params.id=t.query.id,this.dataset=[],this.fetchData(!0))}},created:function(){void 0!==this.$route.query.id&&(this.params.id=this.$route.query.id),this.fetchDataPublishedJoblist()},methods:{fetchDataPublishedJoblist:function(){var t=this;r["a"].get(o["a"].get_publish_jobs,{}).then((function(a){var e=0;a.data.items.forEach((function(a){var n={label:a.jobname,href:"/company/recommend?id="+a.id,name:a.id+"",active:0==e};t.published_joblist.push(n),0!=e||t.params.id||(t.params.id=a.id),e++})),t.published_joblist.length>0&&t.fetchData(!0)})).catch((function(){}))},fetchData:function(t){var a=this;this.listLoading=!0,r["a"].get(o["a"].recommend_resumelist,this.params).then((function(e){a.dataset=Object(s["a"])(e.data.items),a.listLoading=!1,!0===t&&a.fetchDataTotal()})).catch((function(){}))},fetchDataTotal:function(){var t=this;r["a"].get(o["a"].recommend_resumelist_total,this.params).then((function(a){t.total=a.data})).catch((function(){}))},doSearch:function(t){this.params.page=t,this.fetchData()}}},c=l,u=(e("d63b"),e("5d22")),d=Object(u["a"])(c,n,i,!1,null,"228d716c",null);a["default"]=d.exports},d63b:function(t,a,e){"use strict";e("fc0d")},fc0d:function(t,a,e){}}]);