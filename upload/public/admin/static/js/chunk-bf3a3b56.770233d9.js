(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-bf3a3b56"],{"0ccb":function(t,e,a){var n=a("50c4"),r=a("1148"),i=a("1d80"),l=Math.ceil,o=function(t){return function(e,a,o){var s,c,u=String(i(e)),d=u.length,f=void 0===o?" ":String(o),p=n(a);return p<=d||""==f?u:(s=p-d,c=r.call(f,l(s/f.length)),c.length>s&&(c=c.slice(0,s)),t?u+c:c+u)}};t.exports={start:o(!1),end:o(!0)}},"444d":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[t._v("广告管理")]),a("el-link",{staticStyle:{float:"right",margin:"3px 20px"},attrs:{href:"http://doc.74cms.com/#/se/content?id=pc首页广告位分布图",target:"_blank",type:"primary"}},[t._v("查看广告位图例位置说明")])],1),a("div",{staticClass:"list-search"},[a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限平台",loading:t.adCategoryLoading},on:{change:t.funPlatform},model:{value:t.platform,callback:function(e){t.platform=e},expression:"platform"}},[a("el-option",{attrs:{label:"不限平台",value:""}}),t._l(t.adPlatform,(function(t,e){return a("el-option",{key:e,attrs:{label:t.label,value:t.value}})}))],2),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限广告位"},on:{change:t.funSearch},model:{value:t.cid,callback:function(e){t.cid=e},expression:"cid"}},[a("el-option",{attrs:{label:"不限广告位",value:"0"}}),t._l(t.adCategory,(function(t,e){return a("el-option",{key:e,attrs:{label:t.label,value:t.value}})}))],2),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限显示状态"},on:{change:t.funSearch},model:{value:t.is_display,callback:function(e){t.is_display=e},expression:"is_display"}},[a("el-option",{attrs:{label:"不限显示状态",value:""}}),a("el-option",{attrs:{label:"显示",value:"1"}}),a("el-option",{attrs:{label:"隐藏",value:"0"}})],1),a("el-select",{staticClass:"list-options",attrs:{placeholder:"不限到期时间"},on:{change:t.funSearch},model:{value:t.settr,callback:function(e){t.settr=e},expression:"settr"}},[a("el-option",{attrs:{label:"不限到期时间",value:""}}),a("el-option",{attrs:{label:"已经到期",value:"1"}}),a("el-option",{attrs:{label:"三天内",value:"3"}}),a("el-option",{attrs:{label:"一周内",value:"7"}})],1),a("el-input",{staticClass:"input-with-select",attrs:{placeholder:"请输入搜索内容"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.funSearchKeyword(e)}},model:{value:t.keyword,callback:function(e){t.keyword=e},expression:"keyword"}},[a("el-select",{staticClass:"input-sel",attrs:{slot:"prepend",placeholder:"请选择"},slot:"prepend",model:{value:t.key_type,callback:function(e){t.key_type=e},expression:"key_type"}},[a("el-option",{attrs:{label:"标题",value:"1"}}),a("el-option",{attrs:{label:"广告ID",value:"2"}})],1),a("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.funSearchKeyword},slot:"append"})],1)],1),a("div",{staticClass:"spaceline"}),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"selection-change":t.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection",width:"42"}}),a("el-table-column",{attrs:{label:"广告标题","show-overflow-tooltip":"","min-width":"200"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-popover",{attrs:{placement:"right",trigger:"hover"}},[a("img",{staticStyle:{"max-width":"500px","max-height":"400px"},attrs:{src:e.row.imageurl}}),a("span",{attrs:{slot:"reference"},slot:"reference"},[t._v(" "+t._s(e.row.title)+" "),a("i",{staticClass:"el-icon-picture"})])])]}}])}),a("el-table-column",{attrs:{label:"所属平台","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.platform)+" ")]}}])}),a("el-table-column",{attrs:{label:"所属广告位","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.cname)+" ")]}}])}),a("el-table-column",{attrs:{align:"center",prop:"created_at",label:"开始时间","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("i",{staticClass:"el-icon-time"}),a("span",[t._v(t._s(t._f("timeFilter")(e.row.starttime)))])]}}])}),a("el-table-column",{attrs:{align:"center",prop:"created_at",label:"到期时间","min-width":"160"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("i",{staticClass:"el-icon-time"}),0!=e.row.deadline?a("span",[t._v(" "+t._s(t._f("timeFilter")(e.row.deadline))+" ")]):a("span",[t._v("无限期")])]}}])}),a("el-table-column",{attrs:{label:"排序","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("span",[t._v(t._s(e.row.sort_id))])]}}])}),a("el-table-column",{attrs:{label:"是否显示","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-tag",{attrs:{type:t._f("displayFilter")(e.row.is_display)}},[t._v(" "+t._s(1==e.row.is_display?"显示":"隐藏")+" ")])]}}])}),a("el-table-column",{attrs:{fixed:"right",label:"操作","min-width":"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")])]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-row",{attrs:{gutter:20}},[a("el-col",{attrs:{span:8}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.goTo}},[t._v(" 添加广告 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:t.funDeleteBatch}},[t._v(" 删除所选 ")])],1),a("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[a("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,15,20,30,40],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1)],1)},r=[],i=a("b85c"),l=a("ff02"),o=a("ed08"),s=a("52b5"),c={filters:{displayFilter:function(t){var e={1:"success",0:"info"};return e[t]},timeFilter:function(t){return Object(o["b"])(t,"{y}-{m}-{d} {h}:{i}")}},data:function(){return{tableIdarr:[],list:null,listLoading:!0,total:0,currentPage:1,pagesize:10,adPlatform:[],adCategory:[],adCategoryLoading:!0,settr:"",is_display:"",platform:"",cid:"0",key_type:"1",keyword:""}},created:function(){this.fetchData(),this.fetchCategoryData()},methods:{funPlatform:function(t){if(0==t)this.adCategory=[],this.cid="0";else{for(var e in this.adPlatform)if(this.adPlatform[e]["value"]==t){this.adCategory=this.adPlatform[e]["children"];break}this.cid="0"}this.fetchData()},fetchData:function(){var t=this;this.listLoading=!0;var e={settr:this.settr,is_display:this.is_display,platform:this.platform,cid:this.cid,key_type:this.key_type,keyword:this.keyword,page:this.currentPage,pagesize:this.pagesize};Object(l["e"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},fetchCategoryData:function(){var t=this;Object(s["a"])({type:"adCategory"}).then((function(e){t.adPlatform=e.data,t.adCategoryLoading=!1}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funSearch:function(){this.fetchData()},funSearchKeyword:function(){this.cid="0",this.currentPage=1,this.fetchData()},funEdit:function(t,e){this.$router.push({path:"/content/ad/list/edit",query:{id:e.id}})},funDelete:function(t,e){var a=this;a.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(l["b"])(t).then((function(t){return a.$message.success(t.message),a.fetchData(),!0}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0==t.tableIdarr.length)return t.$message.error("请选择要删除的数据"),!1;t.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};Object(l["b"])(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0}))})).catch((function(){}))},handleSelectionChange:function(t){if(this.tableIdarr=[],t.length>0){var e,a=Object(i["a"])(t);try{for(a.s();!(e=a.n()).done;){var n=e.value;this.tableIdarr.push(n.id)}}catch(r){a.e(r)}finally{a.f()}}},goTo:function(){this.$router.push("/content/ad/list/add")}}},u=c,d=a("2877"),f=Object(d["a"])(u,n,r,!1,null,null,null);e["default"]=f.exports},"4d90":function(t,e,a){"use strict";var n=a("23e7"),r=a("0ccb").start,i=a("9a0c");n({target:"String",proto:!0,forced:i},{padStart:function(t){return r(this,t,arguments.length>1?arguments[1]:void 0)}})},"52b5":function(t,e,a){"use strict";a.d(e,"a",(function(){return i}));var n=a("b775"),r=a("d722");function i(t){return Object(n["a"])({url:r["a"].getClassify,method:"get",params:t})}},"53ca":function(t,e,a){"use strict";a.d(e,"a",(function(){return n}));a("a4d3"),a("e01a"),a("d28b"),a("d3b7"),a("3ca3"),a("ddb0");function n(t){return n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},n(t)}},"9a0c":function(t,e,a){var n=a("342f");t.exports=/Version\/10\.\d+(\.\d+)?( Mobile\/\w+)? Safari\//.test(n)},b85c:function(t,e,a){"use strict";a.d(e,"a",(function(){return r}));a("a4d3"),a("e01a"),a("d28b"),a("d3b7"),a("3ca3"),a("ddb0");var n=a("06c5");function r(t,e){var a="undefined"!==typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!a){if(Array.isArray(t)||(a=Object(n["a"])(t))||e&&t&&"number"===typeof t.length){a&&(t=a);var r=0,i=function(){};return{s:i,n:function(){return r>=t.length?{done:!0}:{done:!1,value:t[r++]}},e:function(t){throw t},f:i}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var l,o=!0,s=!1;return{s:function(){a=a.call(t)},n:function(){var t=a.next();return o=t.done,t},e:function(t){s=!0,l=t},f:function(){try{o||null==a["return"]||a["return"]()}finally{if(s)throw l}}}}},ed08:function(t,e,a){"use strict";a.d(e,"b",(function(){return l})),a.d(e,"a",(function(){return o})),a.d(e,"c",(function(){return s}));a("d3b7"),a("ac1f"),a("25f0"),a("4d90"),a("5319"),a("1276");var n=a("53ca"),r=a("a78e"),i=a.n(r);function l(t,e){if(0===arguments.length)return null;var a,r=e||"{y}-{m}-{d} {h}:{i}:{s}";"object"===Object(n["a"])(t)?a=t:("string"===typeof t&&/^[0-9]+$/.test(t)&&(t=parseInt(t)),"number"===typeof t&&t.toString().length<=10&&(t*=1e3),a=new Date(t));var i={y:a.getFullYear(),m:a.getMonth()+1,d:a.getDate(),h:a.getHours(),i:a.getMinutes(),s:a.getSeconds(),a:a.getDay()},l=r.replace(/{([ymdhisa])+}/g,(function(t,e){var a=i[e];return"a"===e?["日","一","二","三","四","五","六"][a]:a.toString().padStart(2,"0")}));return l}function o(t,e){t=(""+t).length<=10?1e3*parseInt(t):+t;var a=new Date(t),n=Date.now(),r=(n-a)/1e3;return r<30?"刚刚":r<3600?Math.ceil(r/60)+"分钟前":r<86400?Math.ceil(r/3600)+"小时前":r<172800?"1天前":e?l(t,e):a.getMonth()+1+"月"+a.getDate()+"日"+a.getHours()+"时"+a.getMinutes()+"分"}function s(t){var e={utype:t.utype,token:t.token,mobile:t.mobile,userIminfo:t.user_iminfo};i.a.set("qscms_visitor",JSON.stringify(e),{expires:7})}},ff02:function(t,e,a){"use strict";a.d(e,"e",(function(){return i})),a.d(e,"a",(function(){return l})),a.d(e,"c",(function(){return o})),a.d(e,"b",(function(){return s})),a.d(e,"d",(function(){return c}));var n=a("b775"),r=a("d722");function i(t){return Object(n["a"])({url:r["a"].adList,method:"get",params:t})}function l(t){return Object(n["a"])({url:r["a"].adAdd,method:"post",data:t})}function o(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(n["a"])({url:r["a"].adEdit,method:e,data:t}):Object(n["a"])({url:r["a"].adEdit,method:e,params:t})}function s(t){return Object(n["a"])({url:r["a"].adDelete,method:"post",data:t})}function c(t){return Object(n["a"])({url:r["a"].adInnerLinkOptions,method:"get",params:t})}}}]);