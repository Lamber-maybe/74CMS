(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-d056c0d4"],{5508:function(t,e,n){"use strict";var l=n("6428"),a=n.n(l);a.a},"634f":function(t,e,n){"use strict";n.r(e);var l=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[t._m(0),n("el-tabs",{attrs:{type:"border-card"}},[n("el-tab-pane",{attrs:{label:"通知企业",lazy:!0}},[n("ruleForm",{attrs:{listtype:"company"}})],1),n("el-tab-pane",{attrs:{label:"通知求职者",lazy:!0}},[n("ruleForm",{attrs:{listtype:"personal"}})],1)],1)],1)},a=[function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"tip"},[n("p",[t._v("此功能仅限于已开通微信模板消息功能的公众号使用。")]),n("p",[t._v("必须填写【模板ID】才能【开启】模板消息推送状态，否则将无法正常发送通知。")]),n("p",[t._v("【模板ID】请到 微信公众平台 >> 模板消息 >> 模板库 选择对应【模板编号】的模板，然后获取【模板ID】填写到此处。")]),n("p",[t._v("以下所有模板均属于“IT科技/互联网|电子商务”和“商业服务/中介服务”。")])])}],o=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.infoLoading,expression:"infoLoading"}],attrs:{"label-width":"0","inline-message":!0}},[n("el-form-item",[n("el-table",{attrs:{border:"",data:t.list}},[n("el-table-column",{attrs:{label:"通知内容","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.title)+" ")]}}])}),n("el-table-column",{attrs:{label:"模板名称","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.tpl_name)+" ")]}}])}),n("el-table-column",{attrs:{label:"所属行业","min-width":"180"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.tpl_trade)+" ")]}}])}),n("el-table-column",{attrs:{label:"模板编号","min-width":"170"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.tpl_number)+" ")]}}])}),n("el-table-column",{attrs:{align:"center",label:"开启/关闭"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-switch",{model:{value:e.row.is_open,callback:function(n){t.$set(e.row,"is_open",n)},expression:"scope.row.is_open"}})]}}])}),n("el-table-column",{attrs:{align:"center",label:"模板ID","min-width":"420"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-input",{attrs:{placeholder:"请输入模板ID"},model:{value:e.row.tpl_id,callback:function(n){t.$set(e.row,"tpl_id",n)},expression:"scope.row.tpl_id"}})]}}])})],1)],1),n("el-form-item",{attrs:{label:""}},[n("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmit("form")}}},[t._v("保存")])],1)],1)],1)},i=[],s=(n("4160"),n("159b"),n("2909")),r=n("1063"),c={props:["listtype"],data:function(){return{infoLoading:!0,list:[]}},created:function(){this.fetchInfo()},methods:{fetchInfo:function(){var t=this;this.infoLoading=!0;var e=this,n="";"company"==e.listtype&&(n=1),"personal"==e.listtype&&(n=2),"administrators"==e.listtype&&(n=3);var l={utype:n};Object(r["e"])(l,"get").then((function(n){var l=Object(s["a"])(n.data);console.log(1),l.forEach((function(t,n,l){t.is_open=1==t.is_open,e.list.push(t)})),t.infoLoading=!1})).catch((function(){}))},onSubmit:function(t){var e=this,n=[];this.list.forEach((function(t,e,l){var a={id:t.id,is_open:-1==t.is_open?-1:!0===t.is_open?1:0,tpl_id:t.tpl_id};n[e]=a})),Object(r["o"])(n).then((function(t){return e.$message.success(t.message),!0})).catch((function(){}))}}},u=c,p=(n("5508"),n("2877")),f=Object(p["a"])(u,o,i,!1,null,"55247c00",null),d=f.exports,_={components:{ruleForm:d}},m=_,b=Object(p["a"])(m,l,a,!1,null,null,null);e["default"]=b.exports},6428:function(t,e,n){}}]);