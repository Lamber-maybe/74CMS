(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-c4105806"],{"8b1b":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[t._v("分类管理")])]),a("div",{staticClass:"tip"},[a("p",[t._v("系统默认分类不能删除！")])]),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",fit:"","highlight-current-row":""},on:{"selection-change":t.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection",width:"30"}}),a("el-table-column",{attrs:{align:"center",label:"ID",width:"95"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.id)+" ")]}}])}),a("el-table-column",{attrs:{label:"分类名称"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.name)+" ")]}}])}),a("el-table-column",{attrs:{align:"center",label:"类型",width:"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-tag",{attrs:{type:1==e.row.is_sys?"info":""}},[t._v(" "+t._s(t._f("sysFilter")(e.row.is_sys))+" ")])]}}])}),a("el-table-column",{attrs:{align:"center",label:"排序",width:"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("span",[t._v(t._s(e.row.sort_id))])]}}])}),a("el-table-column",{attrs:{fixed:"right",label:"操作",width:"220"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(a){return t.funEdit(e.$index,e.row)}}},[t._v(" 编辑 ")]),0==e.row.is_sys?a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(a){return t.funDelete(e.$index,e.row)}}},[t._v(" 删除 ")]):a("el-button",{attrs:{size:"small",type:"danger",disabled:""}},[t._v(" 删除 ")])]}}])})],1),a("div",{staticClass:"spaceline"}),a("el-row",{attrs:{gutter:20}},[a("el-col",{attrs:{span:8}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){return t.goTo("add")}}},[t._v(" 添加分类 ")]),a("el-button",{attrs:{size:"small",type:"danger"},on:{click:t.funDeleteBatch}},[t._v(" 删除所选 ")])],1),a("el-col",{staticStyle:{"text-align":"right"},attrs:{span:16}},[a("el-pagination",{attrs:{background:"","current-page":t.currentPage,"page-sizes":[10,20,50,100],"page-size":t.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1)],1)},r=[],i=a("b85c"),s=a("a0bb"),l={filters:{sysFilter:function(t){var e={1:"系统分类",0:"自定义分类"};return e[t]}},data:function(){return{tableIdarr:[],list:null,listLoading:!0,total:0,currentPage:1,pagesize:10}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={page:this.currentPage,pagesize:this.pagesize};Object(s["d"])(e).then((function(e){t.list=e.data.items,t.listLoading=!1,t.total=e.data.total,t.currentPage=e.data.current_page,t.pagesize=e.data.pagesize}))},handleSizeChange:function(t){this.pagesize=t,this.fetchData()},handleCurrentChange:function(t){this.currentPage=t,this.fetchData()},funEdit:function(t,e){this.$router.push({path:"/content/article/category/edit",query:{id:e.id}})},funDelete:function(t,e){var a=this;a.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var t={id:e.id};Object(s["b"])(t).then((function(t){return a.$message.success(t.message),a.fetchData(),!0}))})).catch((function(){}))},funDeleteBatch:function(){var t=this;if(0==t.tableIdarr.length)return t.$message.error("请选择要删除的数据"),!1;t.$confirm("此操作将永久删除选中的数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var e={id:t.tableIdarr};Object(s["b"])(e).then((function(e){return t.$message.success(e.message),t.fetchData(),!0}))})).catch((function(){}))},handleSelectionChange:function(t){if(this.tableIdarr=[],t.length>0){var e,a=Object(i["a"])(t);try{for(a.s();!(e=a.n()).done;){var n=e.value;0==n.is_sys&&this.tableIdarr.push(n.id)}}catch(r){a.e(r)}finally{a.f()}}},goTo:function(t){this.$router.push("/content/article/category/"+t)}}},c=l,o=a("2877"),u=Object(o["a"])(c,n,r,!1,null,null,null);e["default"]=u.exports},a0bb:function(t,e,a){"use strict";a.d(e,"d",(function(){return i})),a.d(e,"a",(function(){return s})),a.d(e,"c",(function(){return l})),a.d(e,"b",(function(){return c}));var n=a("b775"),r=a("d722");function i(t){return Object(n["a"])({url:r["a"].articleCategoryList,method:"get",params:t})}function s(t){return Object(n["a"])({url:r["a"].articleCategoryAdd,method:"post",data:t})}function l(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"post";return"post"==e?Object(n["a"])({url:r["a"].articleCategoryEdit,method:e,data:t}):Object(n["a"])({url:r["a"].articleCategoryEdit,method:e,params:t})}function c(t){return Object(n["a"])({url:r["a"].articleCategoryDelete,method:"post",data:t})}}}]);