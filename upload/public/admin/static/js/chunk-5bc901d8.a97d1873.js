(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-5bc901d8"],{"03f2":function(t,e,i){"use strict";i("6a70")},"6a70":function(t,e,i){},"85f1":function(t,e,i){},be61:function(t,e,i){"use strict";i("85f1")},ee1b:function(t,e,i){"use strict";i.r(e);var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("el-tabs",{attrs:{type:"border-card"}},[i("el-tab-pane",{attrs:{label:"企业信息",lazy:!0}},[i("fieldForm",{attrs:{listtype:"company"}})],1),i("el-tab-pane",{attrs:{label:"职位信息",lazy:!0}},[i("fieldForm",{attrs:{listtype:"job"}})],1),i("el-tab-pane",{attrs:{label:"简历信息",lazy:!0}},[i("fieldForm",{attrs:{listtype:"resume"}})],1),i("el-tab-pane",{attrs:{label:"简历模块",lazy:!0}},[i("resumeModule")],1)],1)],1)},n=[],a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],staticClass:"common-form",attrs:{"label-width":"0","inline-message":!0}},[i("el-form-item",[i("el-table",{attrs:{border:"",data:t.list}},[i("el-table-column",{attrs:{label:"字段名"},scopedSlots:t._u([{key:"default",fn:function(e){return[!1===e.row.is_custom?i("span",[t._v(" "+t._s(e.row.field_cn)+" ")]):i("el-form-item",[i("el-input",{staticStyle:{width:"120px"},attrs:{maxlength:"4"},model:{value:e.row.field_cn,callback:function(i){t.$set(e.row,"field_cn",i)},expression:"scope.row.field_cn"}})],1)]}}])}),i("el-table-column",{attrs:{align:"center",label:"是否显示"},scopedSlots:t._u([{key:"default",fn:function(e){return[!1===e.row.enable_close?i("div",[i("span",{staticStyle:{color:"#67C23A"}},[t._v("显示")]),i("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:"系统不允许关闭此项",placement:"top-start"}},[i("i",{staticClass:"el-icon-question"})])],1):i("el-switch",{on:{change:function(i){return t.switchChangeBtn(e.row)}},model:{value:e.row.is_display,callback:function(i){t.$set(e.row,"is_display",i)},expression:"scope.row.is_display"}})]}}])}),i("el-table-column",{attrs:{align:"center",label:"是否必填"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("div",{on:{click:function(i){return t.switchBtn(e.row)}}},[i("el-switch",{attrs:{disabled:!e.row.is_display},model:{value:e.row.is_require,callback:function(i){t.$set(e.row,"is_require",i)},expression:"scope.row.is_require"}})],1)]}}])})],1)],1),i("el-form-item",{attrs:{label:""}},[i("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")])],1)],1)],1)},o=[],l=i("2909"),r=(i("d3b7"),i("159b"),i("1063")),c={props:["listtype"],data:function(){return{infoLoading:!0,list:[]}},created:function(){this.fetchInfo()},methods:{switchBtn:function(t){t.is_display||this.$message.warning("请先打开"+t.field_cn+"显示开关！")},switchChangeBtn:function(t){t.is_display||(t.is_require=!1)},fetchInfo:function(){var t=this;this.infoLoading=!0;var e=this,i={type:this.listtype};Object(r["k"])(i,"get").then((function(i){var s=Object(l["a"])(i.data);s.forEach((function(t,i,s){t.is_display=1==t.is_display,t.is_require=1==t.is_require,t.enable_close=1==t.enable_close,t.is_custom=1==t.is_custom,e.list.push(t)})),t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,i=this,s=!1,n=[];if(this.list.forEach((function(t,e,a){var o={id:t.id,field_cn:t.field_cn,is_display:!0===t.is_display?1:0,is_require:!0===t.is_require?1:0};if(""==o.field_cn)return i.$message.error("字段名不能为空"),s=!0,!1;n[e]=o})),!0===s)return!1;Object(r["k"])(n).then((function(t){return e.$message.success(t.message),!0})).catch((function(){}))}}},u=c,f=(i("be61"),i("2877")),d=Object(f["a"])(u,a,o,!1,null,"4bc3b04c",null),p=d.exports,m=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],staticClass:"common-form",attrs:{"label-width":"0","inline-message":!0}},[i("el-form-item",[i("el-table",{attrs:{border:"",data:t.list}},[i("el-table-column",{attrs:{label:"模块名称",prop:"module_cn"}}),i("el-table-column",{attrs:{align:"center",label:"是否显示"},scopedSlots:t._u([{key:"default",fn:function(e){return[!1===e.row.enable_close?i("div",[i("span",{staticStyle:{color:"#67C23A"}},[t._v("显示")]),i("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:"系统不允许关闭此项",placement:"top-start"}},[i("i",{staticClass:"el-icon-question"})])],1):i("el-switch",{on:{change:function(i){return t.switchChangeBtn(e.row)}},model:{value:e.row.is_display,callback:function(i){t.$set(e.row,"is_display",i)},expression:"scope.row.is_display"}})]}}])}),i("el-table-column",{attrs:{label:"完整度"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-form-item",[i("div",{on:{click:function(i){return t.switchBtn(e.row)}}},[i("el-input",{staticStyle:{width:"120px"},attrs:{disabled:!e.row.is_display,type:"number"},on:{change:t.handle_total,blur:t.handle_total},model:{value:e.row.score,callback:function(i){t.$set(e.row,"score",i)},expression:"scope.row.score"}})],1)])]}}])})],1),i("el-card",{staticStyle:{"border-top":"0"},attrs:{shadow:"never"}},[t._v(" 完整度总和： "),i("b",{style:"color:"+t.total_color},[t._v(t._s(t.total))]),!1===t.infoLoading&&100!=t.total?i("el-alert",{attrs:{title:"完整度总和必须等于100",type:"error",closable:!1}}):t._e()],1)],1),i("el-form-item",{attrs:{label:""}},[i("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")])],1)],1)],1)},h=[],_={data:function(){return{infoLoading:!0,list:[],total:0,total_color:"#67C23A"}},created:function(){this.fetchInfo()},methods:{switchChangeBtn:function(t){t.is_display||(t.score=0),this.handle_total()},switchBtn:function(t){t.is_display||this.$message.warning("请先打开"+t.module_cn+"显示开关！")},fetchInfo:function(){var t=this;this.infoLoading=!0;var e=this;Object(r["n"])({},"get").then((function(i){var s=Object(l["a"])(i.data);s.forEach((function(t,i,s){t.is_display=1==t.is_display,t.enable_close=1==t.enable_close,e.list.push(t),!0===t.is_display&&(e.total=e.total+parseInt(t.score))})),t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,i=this,s=!1,n=[];if(this.list.forEach((function(t,e,i){""==t.score&&(t.score=0);var s={id:t.id,is_display:!0===t.is_display?1:0,score:t.score};n[e]=s})),100!=this.total&&(i.$message.error("完整度总和必须等于100，当前总和"+this.total),s=!0),!0===s)return!1;Object(r["n"])(n).then((function(t){return e.$message.success(t.message),!0})).catch((function(){}))},handle_total:function(){var t=0;this.list.forEach((function(e){1==e.is_display&&(""==e.score&&(e.score=0),t+=parseInt(e.score))})),this.total_color=100==t?"#67C23A":"#F56C6C",this.total=t}}},b=_,w=(i("03f2"),Object(f["a"])(b,m,h,!1,null,"0ac7ad18",null)),y=w.exports,v={components:{fieldForm:p,resumeModule:y}},g=v,k=Object(f["a"])(g,s,n,!1,null,null,null);e["default"]=k.exports}}]);