(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-47af5a3e"],{"8e35":function(t,e,a){"use strict";a.d(e,"n",(function(){return r})),a.d(e,"l",(function(){return i})),a.d(e,"m",(function(){return l})),a.d(e,"f",(function(){return o})),a.d(e,"a",(function(){return s})),a.d(e,"d",(function(){return c})),a.d(e,"c",(function(){return u})),a.d(e,"e",(function(){return d})),a.d(e,"k",(function(){return f})),a.d(e,"h",(function(){return h})),a.d(e,"j",(function(){return p})),a.d(e,"i",(function(){return g})),a.d(e,"g",(function(){return m})),a.d(e,"b",(function(){return b}));var n=a("b775");a("d722");function r(t){return Object(n["a"])({url:"/shortvideo/lists",method:"get",params:t})}function i(t){return Object(n["a"])({url:"/shortvideo/audit",method:"post",data:t})}function l(t){return Object(n["a"])({url:"/shortvideo/del",method:"post",data:t})}function o(t){return Object(n["a"])({url:"/shortvideo/ad_list",method:"get",params:t})}function s(t){return Object(n["a"])({url:"/shortvideo/ad_add",method:"post",data:t})}function c(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(n["a"])({url:"/shortvideo/ad_edit",method:e,data:t}):Object(n["a"])({url:"/shortvideo/ad_edit",method:e,params:t})}function u(t){return Object(n["a"])({url:"/shortvideo/ad_del",method:"post",data:t})}function d(t){return Object(n["a"])({url:"/shortvideo/innerLinkOptions",method:"get",params:t})}function f(t){return Object(n["a"])({url:"/shortvideo/ad_cat_list",method:"get",params:t})}function h(t){return Object(n["a"])({url:"/shortvideo/ad_cat_add",method:"post",data:t})}function p(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(n["a"])({url:"/shortvideo/ad_cat_edit",method:e,data:t}):Object(n["a"])({url:"/shortvideo/ad_cat_edit",method:e,params:t})}function g(t){return Object(n["a"])({url:"/shortvideo/ad_cat_del",method:"post",data:t})}function m(t){return Object(n["a"])({url:"/shortvideo/ad_cat_platform",method:"get",params:t})}function b(t){return Object(n["a"])({url:"/shortvideo/ad_cat_tree",method:"get",params:t})}},9427:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-tabs",{attrs:{type:"border-card"}},[a("el-tab-pane",{attrs:{label:"广告列表",lazy:!0}},[a("ad-list")],1),a("el-tab-pane",{attrs:{label:"广告位",lazy:!0}},[a("ad-cat")],1)],1)],1)},r=[],i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("div",[t._e(),a("div",{staticClass:"list-search"},[a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限平台",loading:t.adCategoryLoading},on:{change:t.funPlatform},model:{value:t.platform,callback:function(e){t.platform=e},expression:"platform"}},[a("el-option",{attrs:{label:"不限平台",value:""}}),t._l(t.adPlatform,(function(t,e){return a("el-option",{key:e,attrs:{label:t.label,value:t.value}})}))],2),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限广告位"},on:{change:t.funSearch},model:{value:t.cid,callback:function(e){t.cid=e},expression:"cid"}},[a("el-option",{attrs:{label:"不限广告位",value:"0"}}),t._l(t.adCategory,(function(t,e){return a("el-option",{key:e,attrs:{label:t.label,value:t.value}})}))],2),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限显示状态"},on:{change:t.funSearch},model:{value:t.is_display,callback:function(e){t.is_display=e},expression:"is_display"}},[a("el-option",{attrs:{label:"不限显示状态",value:""}}),a("el-option",{attrs:{label:"显示",value:"1"}}),a("el-option",{attrs:{label:"隐藏",value:"0"}})],1),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限到期时间"},on:{change:t.funSearch},model:{value:t.settr,callback:function(e){t.settr=e},expression:"settr"}},[a("el-option",{attrs:{label:"不限到期时间",value:""}}),a("el-option",{attrs:{label:"已经到期",value:"1"}}),a("el-option",{attrs:{label:"三天内",value:"3"}}),a("el-option",{attrs:{label:"一周内",value:"7"}})],1),a("el-input",{staticClass:"input-with-select",attrs:{placeholder:"请输入搜索内容"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword.apply(null,arguments)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[a("el-select",{staticClass:"input-sel",attrs:{slot:"prepend",placeholder:"请选择"},slot:"prepend",model:{value:t.key_type,callback:function(e){t.key_type=e},expression:"key_type"}},[a("el-option",{attrs:{label:"标题",value:"1"}}),a("el-option",{attrs:{label:"广告ID",value:"2"}})],1),a("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1),a("div",{staticClass:"spaceline"}),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"selection-change":t.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection",width:"42"}}),a("el-table-column",{attrs:{label:"广告标题","show-overflow-tooltip":"","min-width":"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-popover",{attrs:{placement:"right",trigger:"hover"}},[a("img",{staticStyle:{"max-width":"500px","max-height":"400px"},attrs:{src:e.row.imageurl}}),a("span",{attrs:{slot:"reference"},slot:"reference"},[t._v(" "+t._s(e.row.title)+" "),a("i",{staticClass:"el-icon-picture"})])])]}}])}),a("el-table-column",{attrs:{label:"所属平台","min-width":"90"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.platform)+" ")]}}])}),a("el-table-column",{attrs:{label:"所属广告位","min-width":"160"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.cname)+" ")]}}])}),a("el-table-column",{attrs:{align:"center",prop:"created_at",label:"开始时间","min-width":"160"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("i",{staticClass:"el-icon-time"}),a("span",[t._v(t._s(t._f("timeFilter")(e.row.starttime)))])]}}])}),a("el-table-column",{attrs:{align:"center",prop:"created_at",label:"到期时间","min-width":"160"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("i",{staticClass:"el-icon-time"}),0!=e.row.deadline?a("span",[t._v(" "+t._s(t._f("timeFilter")(e.row.deadline))+" ")]):a("span",[t._v("无限期")])]}}])}),a("el-table-column",{attrs:{label:"排序","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("span",[t._v(t._s(e.row.sort_id))])]}}])}),a("el-table-column",{attrs:{label:"是否显示","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-tag",{attrs:{type:t._f("displayFilter")(e.row.is_display)}},[t._v(" "+t._s(1==e.row.is_display?"显示":"隐藏")+" ")])]}}])}),a("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-row",{attrs:{gutter:20}},[a("el-col",{attrs:{span:8}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.goTo}},[t._v(" 添加广告 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:t.funDeleteBatch}},[t._v(" 删除所选 ")])],1),a("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[a("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1)])},l=[],o=a("b85c"),s=a("8e35"),c=a("ed08"),u={filters:{displayFilter:function(t){var e={1:"success",0:"info"};return e[t]},timeFilter:function(t){return Object(c["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{tableIdarr:[],list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,adPlatform:[],adCategory:[],adCategoryLoading:!0,settr:"",is_display:"",platform:"",cid:"0",key_type:"1",keyword:""}},created:function(){this.fetchData(),this.fetchCategoryData()},methods:{funPlatform:function(t){if(0==t)this.adCategory=[],this.cid="0";else{for(var e in this.adPlatform)if(this.adPlatform[e]["value"]==t){this.adCategory=this.adPlatform[e]["children"];break}this.cid="0"}this.fetchData()},fetchData:function(){var t=this;this.listLoading=!0;var e={settr:this.settr,is_display:this.is_display,platform:this.platform,cid:this.cid,key_type:this.key_type,keyword:this.keyword,page:this.currentPage,pagesize:this.pagesize};Object(s["f"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},fetchCategoryData:function(){var t=this;Object(s["b"])().then((function(e){t.adPlatform=e.data,t.adCategoryLoading=!1}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearch:function(){this.fetchData()},funSearchKeyword:function(){this.cid="0",this.currentPage=1,this.fetchData()},funEdit:function(t,e){this.$router.push({path:"/shortvideo/ad/list/edit",query:{id:e.id}})},funDelete:function(t,e){var a=this;a.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(s["c"])(t).then((function(t){return a.$message.success(t.message),a.fetchData(),!0}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0==t.tableIdarr.length)return t.$message.error("请选择要删除的数据"),!1;t.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};Object(s["c"])(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0}))})).catch((function(){}))},handleSelectionChange:function(t){if(this.tableIdarr=[],t.length>0){var e,a=Object(o["a"])(t);try{for(a.s();!(e=a.n()).done;){var n=e.value;this.tableIdarr.push(n.id)}}catch(r){a.e(r)}finally{a.f()}}},goTo:function(){this.$router.push("/shortvideo/ad/list/add")}}},d=u,f=a("2877"),h=Object(f["a"])(d,i,l,!1,null,null,null),p=h.exports,g=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("div",[t._e(),t._m(0),a("div",{staticClass:"spaceline"}),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"selection-change":t.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection",width:"42"}}),a("el-table-column",{attrs:{align:"center",label:"ID",width:"95"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.id)+" ")]}}])}),a("el-table-column",{attrs:{label:"广告位名称"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" ["+t._s(e.row.platform)+"]"+t._s(e.row.name)+" ")]}}])}),a("el-table-column",{attrs:{label:"调用名称"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.alias)+" ")]}}])}),a("el-table-column",{attrs:{label:"建议尺寸",width:"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.width)+"*"+t._s(e.row.height)+" ")]}}])}),a("el-table-column",{attrs:{label:"广告数量",width:"120",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.ad_num)+" ")]}}])}),a("el-table-column",{attrs:{label:"类型",width:"150",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-tag",{attrs:{type:1==e.row.is_sys?"info":""}},[t._v(" "+t._s(t._f("sysFilter")(e.row.is_sys))+" ")])]}}])}),a("el-table-column",{attrs:{fixed:"right",label:"操作",width:"220"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")]),0==e.row.is_sys?a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")]):a("el-button",{attrs:{size:"small",type:"danger",disabled:""}},[t._v(" 删除 ")])]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-row",{attrs:{gutter:20}},[a("el-col",{attrs:{span:8}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.goTo("add")}}},[t._v(" 添加广告位 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:t.funDeleteBatch}},[t._v(" 删除所选 ")])],1),a("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[a("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1)])},m=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"tip"},[a("p",[t._v("系统内置广告位不能删除！")]),a("p",[t._v("自定义广告位调用名不可以以 “QS_”开头")])])}],b={filters:{sysFilter:function(t){var e={1:"系统内置",0:"自定义"};return e[t]}},data:function(){return{tableIdarr:[],list:null,listLoading:!0,total:0,currentPage:1,pagesize:10}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={page:this.currentPage,pagesize:this.pagesize};Object(s["k"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funEdit:function(t,e){this.$router.push({path:"/shortvideo/ad/category/edit",query:{id:e.id}})},funDelete:function(t,e){var a=this;a.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(s["i"])(t).then((function(t){return a.$message.success(t.message),a.fetchData(),!0}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0==t.tableIdarr.length)return t.$message.error("请选择要删除的数据"),!1;t.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};Object(s["i"])(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0}))})).catch((function(){}))},handleSelectionChange:function(t){if(this.tableIdarr=[],t.length>0){var e,a=Object(o["a"])(t);try{for(a.s();!(e=a.n()).done;){var n=e.value;0==n.is_sys&&this.tableIdarr.push(n.id)}}catch(r){a.e(r)}finally{a.f()}}},goTo:function(t){this.$router.push("/shortvideo/ad/category/add")}}},v=b,y=Object(f["a"])(v,g,m,!1,null,null,null),_=y.exports,w={components:{adList:p,adCat:_}},k=w,C=Object(f["a"])(k,n,r,!1,null,null,null);e["default"]=C.exports},b85c:function(t,e,a){"use strict";a.d(e,"a",(function(){return r}));a("a4d3"),a("e01a"),a("d3b7"),a("d28b"),a("3ca3"),a("ddb0");var n=a("06c5");function r(t,e){var a="undefined"!==typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!a){if(Array.isArray(t)||(a=Object(n["a"])(t))||e&&t&&"number"===typeof t.length){a&&(t=a);var r=0,i=function(){};return{s:i,n:function(){return r>=t.length?{done:!0}:{done:!1,value:t[r++]}},e:function(t){throw t},f:i}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var l,o=!0,s=!1;return{s:function(){a=a.call(t)},n:function(){var t=a.next();return o=t.done,t},e:function(t){s=!0,l=t},f:function(){try{o||null==a["return"]||a["return"]()}finally{if(s)throw l}}}}}}]);