(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-78883693"],{"2d0b":function(e,t,i){"use strict";i("5396")},5396:function(e,t,i){},"730b":function(e,t,i){},"933e":function(e,t,i){},beb9:function(e,t,i){"use strict";i.r(t);var a=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"box-card"},[a("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[a("span",[e._v(e._s(e.title))])]),a("div",{staticStyle:{float:"right","z-index":"1",position:"relative"}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){return e.goto("/user/company/crm/wholeAdd")}}},[e._v(" 新增线索 ")])],1),a("div",{staticClass:"list-search"},[a("el-input",{staticClass:"input-with-select",staticStyle:{width:"440px"},attrs:{placeholder:"请输入搜索内容"},nativeOn:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.funSearchKeyword.apply(null,arguments)}},model:{value:e.keyword,callback:function(t){e.keyword=t},expression:"keyword"}},[a("el-select",{staticClass:"input-sel",attrs:{slot:"prepend",placeholder:"请选择"},slot:"prepend",model:{value:e.key_type,callback:function(t){e.key_type=t},expression:"key_type"}},[a("el-option",{attrs:{label:"线索名称",value:"1"}}),a("el-option",{attrs:{label:"手机号码",value:"2"}})],1),a("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:e.funSearchKeyword},slot:"append"})],1)],1),""!=e.saleFilter||""!=e.industryFilter||""!=e.regionFilter||""!=e.createdByFilter?a("div",{staticClass:"filterCriteria"},[a("div",{staticStyle:{float:"left",display:"inline-block","margin-top":"6px"}},[e._v("已选条件：")]),a("div",{staticStyle:{float:"left",display:"inline-block",width:"80%"}},[""!=e.saleFilter?a("div",{staticClass:"selected"},[a("div",{staticClass:"name"},[e._v("所属销售："+e._s(e.saleFilter.name))]),a("div",{staticClass:"closes",on:{click:function(t){return e.reset(e.saleFilter.field)}}},[a("i",{staticClass:"el-icon-close"})])]):e._e(),""!=e.industryFilter?a("div",{staticClass:"selected"},[a("div",{staticClass:"name"},[e._v("客户行业："+e._s(e.industryFilter.name))]),a("div",{staticClass:"closes",on:{click:function(t){return e.reset(e.industryFilter.field)}}},[a("i",{staticClass:"el-icon-close"})])]):e._e(),""!=e.regionFilter?a("div",{staticClass:"selected"},[a("div",{staticClass:"name"},[e._v("所在地区："+e._s(e.regionFilter.name))]),a("div",{staticClass:"closes",on:{click:function(t){return e.reset(e.regionFilter.field)}}},[a("i",{staticClass:"el-icon-close"})])]):e._e(),""!=e.createdByFilter?a("div",{staticClass:"selected"},[a("div",{staticClass:"name"},[e._v("创建人："+e._s(e.createdByFilter.name))]),a("div",{staticClass:"closes",on:{click:function(t){return e.reset(e.createdByFilter.field)}}},[a("i",{staticClass:"el-icon-close"})])]):e._e()]),a("div",{staticStyle:{float:"right",display:"inline-block","margin-top":"6px",color:"#409eff","font-size":"13px"},on:{click:function(t){return e.reset("all")}}},[a("i",{staticClass:"el-icon-delete"}),e._v(" 清空条件 ")]),a("div",{staticStyle:{clear:"both"}})]):e._e(),a("div",{staticStyle:{position:"relative"}},[a("div",{class:""!=e.saleFilter||""!=e.industryFilter||""!=e.regionFilter||""!=e.createdByFilter?"setField_s":"setField"},[a("el-popover",{attrs:{placement:"bottom-start",width:"220",trigger:"manual"},model:{value:e.visiblepop,callback:function(t){e.visiblepop=t},expression:"visiblepop"}},[a("div",{staticClass:"setField_h"},e._l(e.fieldData,(function(t){return a("div",{staticClass:"setFields"},[1==t.select?a("input",{attrs:{type:"checkbox",name:t.name,checked:""},domProps:{value:t.field},on:{change:function(i){return e.select(t.field)}}}):e._e(),0==t.select?a("input",{attrs:{type:"checkbox",name:t.name},domProps:{value:t.field},on:{change:function(i){return e.select(t.field)}}}):e._e(),a("span",{staticStyle:{"margin-left":"10px"}},[e._v(e._s(t.name))])])})),0),a("el-button",{class:"menu"==e.menu_icon?"field_s":"field_s_select",attrs:{slot:"reference"},on:{click:e.menu},slot:"reference"},["menu"==e.menu_icon?a("img",{attrs:{src:i("2de5"),alt:""}}):e._e(),"menu_select"==e.menu_icon?a("img",{attrs:{src:i("5ab5"),alt:""}}):e._e()]),a("div",{staticStyle:{"margin-top":"10px","margin-right":"10px"}},[a("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(t){return e.setFieldClose()}}},[e._v("关闭")]),a("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(t){return e.setFieldConfirm()}}},[e._v("确认")])],1)],1)],1),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.loading,expression:"loading"}],ref:"multipleTable",staticStyle:{width:"100%"},attrs:{data:e.tableData,"tooltip-effect":"dark","header-cell-style":{background:"#f9f9f9","border-right":"1px solid #e4e6eb"},border:"",stripe:""},on:{"sort-change":e.sortTable,"selection-change":e.handleSelectionChange}},[a("el-table-column",{attrs:{fixed:"",type:"selection",width:"55"}}),e._l(e.fieldData,(function(t){return 1==t.is_locking&&1==t.select?a("el-table-column",{attrs:{fixed:"",sortable:t.is_sortable,prop:t.field,width:t.width},scopedSlots:e._u([{key:"header",fn:function(i){return[a("div",{class:"custom"==t.is_sortable?"sotrTime":"",on:{mouseenter:function(i){return e.locks(t.field)},mouseleave:function(i){return e.locks(t.field)}}},[a("span",[e._v(e._s(t.name))]),a("el-popover",{attrs:{placement:"bottom",trigger:"hover"}},["admin_username"==t.field?a("div",[a("el-checkbox-group",{staticStyle:{height:"250px"}},e._l(e.saleScreenData,(function(t){return a("div",{staticClass:"screenStyle"},[a("el-radio",{key:t.id,attrs:{label:t.id},model:{value:e.saleScreen,callback:function(t){e.saleScreen=t},expression:"saleScreen"}},[e._v(e._s(t.name))])],1)})),0)],1):e._e(),"trade"==t.field?a("div",[a("el-checkbox-group",e._l(e.industryScreenData,(function(t){return a("div",{staticClass:"screenStyle"},[a("el-radio",{key:t.id,attrs:{label:t.id},model:{value:e.industryScreen,callback:function(t){e.industryScreen=t},expression:"industryScreen"}},[e._v(e._s(t.name))])],1)})),0)],1):e._e(),"district"==t.field?a("div",[a("el-cascader-panel",{attrs:{props:e.props,clearable:"",options:e.regionScreenData},model:{value:e.regionScreen,callback:function(t){e.regionScreen=t},expression:"regionScreen"}})],1):e._e(),"creat_username"==t.field?a("div",[a("el-checkbox-group",e._l(e.createdByScreenData,(function(t){return a("div",{staticClass:"screenStyle"},[a("el-radio",{key:t.id,attrs:{label:t.id},model:{value:e.createdByScreen,callback:function(t){e.createdByScreen=t},expression:"createdByScreen"}},[e._v(e._s(t.name))])],1)})),0)],1):e._e(),a("div",{staticStyle:{"margin-top":"10px"}},[a("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(i){return e.reset(t.field)}}},[e._v("重置")]),a("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(t){return e.confirm()}}},[e._v("确认")])],1),"admin_username"==t.field||"trade"==t.field||"district"==t.field||"creat_username"==t.field?a("div",{staticClass:"drop_down",attrs:{slot:"reference"},slot:"reference"}):e._e()]),1==t.is_lock_display?a("div",{staticClass:"unlock_display",attrs:{slot:"reference"},on:{click:function(i){return e.locking(t.field)}},slot:"reference"}):e._e(),0==t.is_lock_display?a("div",{staticClass:"unlock",attrs:{slot:"reference"},on:{click:function(i){return e.locking(t.field)}},slot:"reference"}):e._e()],1)]}},{key:"default",fn:function(i){return["id"==t.field?a("div",[""==i.row.id?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.id)+" ")])]):e._e(),"name"==t.field?a("div",[""==i.row.name?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.name)+" ")])]):e._e(),"admin_username"==t.field?a("div",[""==i.row.admin_username?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.admin_username)+" ")])]):e._e(),"contacts"==t.field?a("div",[""==i.row.contacts?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.contacts)+" ")])]):e._e(),"mobile"==t.field?a("div",[""==i.row.mobile||null==i.row.mobile?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.mobile)+" "),a("div",{staticClass:"dial",attrs:{title:"拨打联系人手机号"}},[a("div",{staticClass:"disal_img",on:{click:function(t){return e.clickDial(i.row.mobile,i.row.name)}}})])])]):e._e(),"weixin"==t.field?a("div",[""==i.row.weixin?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.weixin)+" ")])]):e._e(),"collection_time"==t.field?a("div",[""==i.row.collection_time?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.collection_time)+" ")])]):e._e(),"trade"==t.field?a("div",[""==i.row.trade?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.trade)+" ")])]):e._e(),"district"==t.field?a("div",[""==i.row.district?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.district)+" ")])]):e._e(),"address"==t.field?a("div",[""==i.row.address?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.address)+" ")])]):e._e(),"updatetime"==t.field?a("div",[""==i.row.updatetime?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.updatetime)+" ")])]):e._e(),"follow_count"==t.field?a("div",[""==i.row.follow_count?a("span",[e._v(" 0 ")]):a("span",[e._v(" "+e._s(i.row.follow_count)+" ")])]):e._e(),"creat_username"==t.field?a("div",[""==i.row.creat_username?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.creat_username)+" ")])]):e._e(),"remark"==t.field?a("div",[""==i.row.remark?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.remark)+" ")])]):e._e()]}}],null,!0)}):e._e()})),e._l(e.fieldData,(function(t){return 0==t.is_locking&&1==t.select?a("el-table-column",{attrs:{prop:t.field,sortable:t.is_sortable,width:t.width},scopedSlots:e._u([{key:"header",fn:function(i){return[a("div",{class:"custom"==t.is_sortable?"sotrTime":"",on:{mouseenter:function(i){return e.locks(t.field)},mouseleave:function(i){return e.locks(t.field)}}},[a("span",[e._v(e._s(t.name))]),a("el-popover",{attrs:{placement:"bottom",trigger:"hover"}},["admin_username"==t.field?a("div",[a("el-checkbox-group",e._l(e.saleScreenData,(function(t){return a("div",{staticClass:"screenStyle"},[a("el-radio",{key:t.id,attrs:{label:t.id},model:{value:e.saleScreen,callback:function(t){e.saleScreen=t},expression:"saleScreen"}},[e._v(e._s(t.name))])],1)})),0)],1):e._e(),"trade"==t.field?a("div",[a("el-checkbox-group",e._l(e.industryScreenData,(function(t){return a("div",{staticClass:"screenStyle"},[a("el-radio",{key:t.id,attrs:{label:t.id},model:{value:e.industryScreen,callback:function(t){e.industryScreen=t},expression:"industryScreen"}},[e._v(e._s(t.name))])],1)})),0)],1):e._e(),"district"==t.field?a("div",[a("el-cascader-panel",{attrs:{props:e.props,clearable:"",options:e.regionScreenData},model:{value:e.regionScreen,callback:function(t){e.regionScreen=t},expression:"regionScreen"}})],1):e._e(),"creat_username"==t.field?a("div",[a("el-checkbox-group",e._l(e.createdByScreenData,(function(t){return a("div",{staticClass:"screenStyle"},[a("el-radio",{key:t.id,attrs:{label:t.id},model:{value:e.createdByScreen,callback:function(t){e.createdByScreen=t},expression:"createdByScreen"}},[e._v(e._s(t.name))])],1)})),0)],1):e._e(),a("div",{staticStyle:{"margin-top":"10px"}},[a("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(i){return e.reset(t.field)}}},[e._v("重置")]),a("el-button",{staticClass:"filterOperation",attrs:{type:"text",size:"small"},on:{click:function(t){return e.confirm()}}},[e._v("确认")])],1),"creat_username"==t.field||"admin_username"==t.field||"trade"==t.field||"district"==t.field?a("div",{staticClass:"drop_down",attrs:{slot:"reference"},slot:"reference"}):e._e()]),1==t.is_lock_display?a("div",{staticClass:"lock_display",attrs:{slot:"reference"},on:{click:function(i){return e.locking(t.field)}},slot:"reference"}):e._e(),0==t.is_lock_display?a("div",{staticClass:"lock",attrs:{slot:"reference"},on:{click:function(i){return e.locking(t.field)}},slot:"reference"}):e._e()],1)]}},{key:"default",fn:function(i){return["id"==t.field?a("div",[""==i.row.id?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.id)+" ")])]):e._e(),"name"==t.field?a("div",[""==i.row.name?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.name)+" ")])]):e._e(),"admin_username"==t.field?a("div",[""==i.row.admin_username?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.admin_username)+" ")])]):e._e(),"contacts"==t.field?a("div",[""==i.row.contacts?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.contacts)+" ")])]):e._e(),"mobile"==t.field?a("div",[""==i.row.mobile||null==i.row.mobile?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.mobile)+" "),a("div",{staticClass:"dial",attrs:{title:"拨打联系人手机号"}},[a("div",{staticClass:"disal_img",on:{click:function(t){return e.clickDial(i.row.mobile,i.row.name)}}})])])]):e._e(),"weixin"==t.field?a("div",[""==i.row.weixin?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.weixin)+" ")])]):e._e(),"collection_time"==t.field?a("div",[""==i.row.collection_time?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.collection_time)+" ")])]):e._e(),"trade"==t.field?a("div",[""==i.row.trade?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.trade)+" ")])]):e._e(),"district"==t.field?a("div",[""==i.row.district?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.district)+" ")])]):e._e(),"address"==t.field?a("div",[""==i.row.address?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.address)+" ")])]):e._e(),"updatetime"==t.field?a("div",[""==i.row.updatetime?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.updatetime)+" ")])]):e._e(),"follow_count"==t.field?a("div",[""==i.row.follow_count?a("span",[e._v(" 0 ")]):a("span",[e._v(" "+e._s(i.row.follow_count)+" ")])]):e._e(),"creat_username"==t.field?a("div",[""==i.row.creat_username?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.creat_username)+" ")])]):e._e(),"remark"==t.field?a("div",[""==i.row.remark?a("span",[e._v(" - ")]):a("span",[e._v(" "+e._s(i.row.remark)+" ")])]):e._e()]}}],null,!0)}):e._e()})),a("el-table-column",{attrs:{label:"操作",width:"140",fixed:"right"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-button",{staticClass:"operation",attrs:{type:"text",size:"small"},on:{click:function(i){return e.see(t.row.id)}}},[e._v("查看")]),2==e.list_type?a("el-button",{staticClass:"operation",attrs:{type:"text",size:"small"},on:{click:function(i){return e.release(t.row.id)}}},[e._v("释放")]):e._e(),2==e.list_type?a("el-button",{staticClass:"operation",attrs:{type:"text",size:"small"},on:{click:function(i){return e.see(t.row.id)}}},[e._v("跟进")]):e._e(),1==e.list_type?a("el-button",{staticClass:"operation",attrs:{type:"text",size:"small"},on:{click:function(i){return e.funAuditBatch("single",t.row.id)}}},[e._v("领取")]):e._e(),1==e.list_type?a("el-button",{staticClass:"operation",attrs:{type:"text",size:"small"},on:{click:function(i){return e.wholeDel("single",t.row.id)}}},[e._v("删除")]):e._e()]}}])})],2)],1),a("div",{staticClass:"bortton-page"},[a("el-col",{attrs:{span:12}},[a("el-button",{attrs:{size:"small",type:"primary"},on:{click:e.choose}},[e._v(" 全选/反选 ")]),1==e.list_type?a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){return e.funAuditBatch("all")}}},[e._v(" 领取 ")]):e._e(),1==e.list_type?a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){return e.wholeDel("all")}}},[e._v(" 删除 ")]):e._e(),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:e.funExport}},[e._v(" 导出 ")])],1),a("el-col",{staticStyle:{"text-align":"right"},attrs:{span:12}},[a("el-pagination",{attrs:{background:"","destroy-on-close":"","current-page":e.currentPage,"page-sizes":[10,15,20,30,40],"page-size":e.pagesize,layout:"total, sizes, prev, pager, next, jumper",total:e.total},on:{"size-change":e.handleSizeChange,"current-change":e.handleCurrentChange}})],1)],1)]),e.drawer?a("el-drawer",{attrs:{size:"75%","show-close":!1,"with-header":!1,"wrapper-closable":!1,visible:e.drawer,"before-close":e.handleClose},on:{"update:visible":function(t){e.drawer=t}}},[a("show",{attrs:{"row-id":e.rowId}}),a("div",{staticClass:"close",on:{click:e.handleClose}},[a("i",{staticClass:"el-icon-close"})])],1):e._e(),a("div",{staticClass:"call"},[a("el-dialog",{attrs:{visible:e.callDialogVisible,width:"30%","before-close":e.callHandleClose},on:{"update:visible":function(t){e.callDialogVisible=t}}},[a("div",{staticClass:"box-content"},[a("div",{staticClass:"content"},[a("div",{staticClass:"title1"},[e._v("您还尚未开通云呼叫服务")]),a("div",{staticClass:"title2"},[e._v("扫码添加客服")]),a("div",{staticClass:"title3"},[e._v("快速开通呼叫业务")])]),a("div",{staticClass:"code"},[a("img",{attrs:{src:i("4e94"),alt:""}})]),a("div",{staticStyle:{clear:"both"}}),a("div",{staticClass:"slogan"},[e._v("一键发起云呼叫，自动录音，助力提升沟通效率！")]),a("div",{staticClass:"advantage"},[a("div",{staticClass:"advantage-box"},[a("div",{staticClass:"img"},[a("img",{attrs:{src:i("068e"),alt:""}})]),a("div",{staticClass:"title"},[e._v("免硬件设备")])]),a("div",{staticClass:"advantage-box"},[a("div",{staticClass:"img"},[a("img",{attrs:{src:i("068e"),alt:""}})]),a("div",{staticClass:"title"},[e._v("录音清晰无杂音")])]),a("div",{staticClass:"advantage-box"},[a("div",{staticClass:"img"},[a("img",{attrs:{src:i("068e"),alt:""}})]),a("div",{staticClass:"title"},[e._v("外显销售手机号")])]),a("div",{staticClass:"advantage-box"},[a("div",{staticClass:"img"},[a("img",{attrs:{src:i("068e"),alt:""}})]),a("div",{staticClass:"title"},[e._v("招聘行业专用线路")])]),a("div",{staticClass:"advantage-box"},[a("div",{staticClass:"img"},[a("img",{attrs:{src:i("068e"),alt:""}})]),a("div",{staticClass:"title"},[e._v("稳定性更高")])]),a("div",{staticClass:"advantage-box"},[a("div",{staticClass:"img"},[a("img",{attrs:{src:i("068e"),alt:""}})]),a("div",{staticClass:"title"},[e._v("防封强、接通率高")])])])])])],1),a("div",{staticClass:"meet"},[a("el-dialog",{attrs:{visible:e.meetDialogVisible,width:"30%","before-close":e.meetHandleClose},on:{"update:visible":function(t){e.meetDialogVisible=t}}},[a("div",{staticClass:"box-content"},[a("div",{staticClass:"img",attrs:{id:"animation"}}),a("div",{staticClass:"hu"},[e._v("正在呼叫客户")]),a("div",{staticClass:"telephone"},[e._v(e._s(e.dialPhone))]),a("div",{staticClass:"company"},[e._v(e._s(e.dialName))]),a("div",{staticClass:"tips"},[e._v("请您留意手机来电接听")])])])],1)],1)},s=[],l=(i("b0c0"),i("4e82"),i("e9c4"),i("d81d"),i("d3b7"),i("159b"),function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",[i("div",{directives:[{name:"loading",rawName:"v-loading",value:e.loading,expression:"loading"}],staticClass:"popup_box"},[i("div",{staticClass:"base"},[i("div",{staticClass:"info"},[i("div",{staticClass:"title"},[i("div",{staticClass:"name"},[e._v(" "+e._s(e.details.name?e.details.name:"-")+" ")]),i("div",{staticClass:"user"},[e._v(" （"),i("span",[e._v("线索ID： "+e._s(e.details.id?e.details.id:"-")+"； ")]),i("span",{staticClass:"logoin",on:{click:e.conversion}},[e._v("转化为客户")]),e._v("） ")]),i("div",{staticClass:"users",staticStyle:{"margin-left":"30px"}},[e._v(" 转为客户将自动注册企业会员 ")])]),i("div",{staticClass:"details"},[i("el-row",{staticClass:"line"},[i("el-col",{attrs:{span:8}},[i("label",{staticClass:"line_label"},[e._v("所属销售：")]),i("span",{staticClass:"text"},[e._v(" "+e._s(e.details.admin_username?e.details.admin_username:"-")+" ")])]),i("el-col",{attrs:{span:8}},[i("label",{staticClass:"line_label"},[e._v("跟进记录：")]),i("span",{staticClass:"text"},[e._v(" "+e._s(e.details.visit_count)+" ")])]),i("el-col",{attrs:{span:8}},[i("label",{staticClass:"line_label"},[e._v("最后跟进：")]),i("span",{staticClass:"text"},[e._v(" "+e._s(e.details.last_visit_time?e.details.last_visit_time:"-")+" ")])])],1),i("el-row",{staticClass:"line"},[i("el-col",{attrs:{span:8}},[i("label",{staticClass:"line_label"},[e._v("所在地区：")]),i("span",{staticClass:"text"},[e._v(" "+e._s(e.details.district)+" ")])]),i("el-col",{attrs:{span:8}},[i("label",{staticClass:"line_label"},[e._v("创建人：")]),i("span",{staticClass:"text"},[e._v(" "+e._s(e.details.creat_username?e.details.creat_username:"-")+" ")])]),i("el-col",{attrs:{span:8}},[i("label",{staticClass:"line_label"},[e._v("创建时间：")]),i("span",{staticClass:"text"},[e._v(" "+e._s(e.details.createtime?e.details.createtime:"-")+" ")])])],1)],1)])]),i("div",{staticClass:"tab_box"},[i("el-tabs",{model:{value:e.activeName,callback:function(t){e.activeName=t},expression:"activeName"}},[i("el-tab-pane",{attrs:{label:"跟进记录",name:"first"}},[i("followUpRecord",{attrs:{clue_id:e.rowId,contacts:e.details.contacts,mobile:e.details.mobile},on:{clueDetails:e.clueDetails}})],1),i("el-tab-pane",{attrs:{label:"线索详情",name:"second"}},["second"==e.activeName?i("clueDetailsEdit",{attrs:{clue_id:e.rowId,details:e.details},on:{clueDetails:e.clueDetails,conversion:e.conversion}}):e._e()],1)],1)],1)])])}),n=[],r=i("0e2b"),c=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",[i("div",{staticClass:"box"},[i("el-form",{ref:"form",attrs:{model:e.form,"label-width":"100px",rules:e.rules}},[i("el-form-item",{attrs:{label:"线索名称：",prop:"name"}},[i("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),i("el-form-item",{attrs:{label:"联系人：",prop:"contacts"}},[i("el-input",{model:{value:e.form.contacts,callback:function(t){e.$set(e.form,"contacts",t)},expression:"form.contacts"}})],1),i("el-form-item",{attrs:{label:"联系手机：",prop:"mobile"}},[i("el-input",{attrs:{oninput:"value=value.replace(/[^0-9.]/g,'')"},model:{value:e.form.mobile,callback:function(t){e.$set(e.form,"mobile",t)},expression:"form.mobile"}})],1),i("el-form-item",{attrs:{label:"联系微信："}},[i("el-input",{model:{value:e.form.weixin,callback:function(t){e.$set(e.form,"weixin",t)},expression:"form.weixin"}})],1),i("el-form-item",{attrs:{label:"公司规模："}},[i("el-select",{attrs:{placeholder:"请选择"},model:{value:e.form.scale,callback:function(t){e.$set(e.form,"scale",t)},expression:"form.scale"}},e._l(e.scaleData,(function(e){return i("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})),1)],1),i("el-form-item",{attrs:{label:"所属行业："}},[i("el-select",{attrs:{placeholder:"请选择"},model:{value:e.form.trade,callback:function(t){e.$set(e.form,"trade",t)},expression:"form.trade"}},e._l(e.industryScreenData,(function(e){return i("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})),1)],1),i("el-form-item",{attrs:{label:"所在地区",prop:"citycategory_arr"}},[i("el-cascader",{attrs:{options:e.options_citycategory,"show-all-levels":!1},model:{value:e.form.district,callback:function(t){e.$set(e.form,"district",t)},expression:"form.district"}})],1),i("el-form-item",{attrs:{label:"详细地址："}},[i("el-input",{model:{value:e.form.address,callback:function(t){e.$set(e.form,"address",t)},expression:"form.address"}})],1),i("el-form-item",{attrs:{label:"备注："}},[i("el-input",{staticStyle:{width:"558px"},attrs:{rows:3,type:"textarea"},model:{value:e.form.remark,callback:function(t){e.$set(e.form,"remark",t)},expression:"form.remark"}})],1),i("el-form-item",{staticClass:"e_button"},[i("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("保存")]),i("el-button",{attrs:{type:"primary"},on:{click:e.conversion}},[e._v("转化为客户")])],1)],1)],1)])},o=[],d=i("5530"),u=i("2909"),f=i("39f6"),m=i("61f7"),_={name:"ClueDetails",props:["clue_id","details"],data:function(){var e=this,t=function(e,t,i){Object(m["c"])(t)?i():i(new Error("请输入正确的手机号"))},i=function(t,i,a){var s={name:i,id:e.clue_id};Object(f["N"])(s).then((function(e){0==e.data.sys_configs&&e.data.clue_count>0?a(new Error("线索名称已重复")):a()}))};return{rules:{name:[{required:!0,message:"请输入线索名称",trigger:"blur"},{max:20,message:"长度在 0 到 20 个字符",trigger:"blur"},{validator:i,trigger:"blur"}],contacts:[{required:!0,message:"请输入联系人",trigger:"blur"},{max:6,message:"长度在 0 到 6 个字符",trigger:"blur"}],mobile:[{required:!0,message:"请输入联系电话",trigger:"blur"},{validator:t,trigger:"blur"}]},form:{name:"",contacts:"",mobile:"",weixin:"",scale:"",industry:"",region:"",address:"",trade:"",remark:"",district:[]},submitLoading:!1,scaleData:[],options_citycategory:[],industryScreenData:[]}},created:function(){this.form.name=this.details.name,this.form.contacts=this.details.contacts,this.form.mobile=this.details.mobile,this.form.weixin=this.details.weixin,this.form.scale=this.details.scale,this.form.district=this.details.citycategory_arr,this.form.trade=this.details.trade,this.form.address=this.details.address,this.form.remark=this.details.remark,this.classify()},methods:{classify:function(){var e=this;Object(f["d"])({type:"citycategory,trade,companyScale"}).then((function(t){e.options_citycategory=Object(u["a"])(t.data.citycategory),e.options_citycategory=e.options_citycategory.map((function(e){if(e.children.length){var t=e.children;return t=t.map((function(e){return e.children.length?{label:e.label,value:e.value,children:e.children}:{label:e.label,value:e.value}})),{value:e.value,label:e.label,children:t}}return{value:e.value,label:e.label}})),e.industryScreenData=t.data.trade,e.scaleData=t.data.companyScale})).catch((function(){}))},conversion:function(){this.$emit("conversion")},onSubmit:function(e){var t=this;if(!0===this.submitLoading)return!1;this.submitLoading=!0;var i=this,a=Object(d["a"])({},this.form);a.clue_id=this.clue_id,0==a.district.length&&(a.district1=0,a.district2=0,a.district3=0,a.district=0),1==a.district.length&&(a.district1=a.district[0],a.district=a.district[0],a.district2=0,a.district3=0),2==a.district.length&&(a.district1=a.district[0],a.district2=a.district[1],a.district3=0,a.district=a.district[1]),3==a.district.length&&(a.district1=a.district[0],a.district2=a.district[1],a.district3=a.district[2],a.district=a.district[2]),this.$refs[e].validate((function(e){if(!e)return t.submitLoading=!1,!1;Object(f["i"])(a).then((function(e){200==e.code?(t.$message.success(e.message),t.$emit("clueDetails")):t.$message.error(e.message),i.submitLoading=!1})).catch((function(){i.submitLoading=!1}))}))}}},p=_,v=(i("2d0b"),i("2877")),h=Object(v["a"])(p,c,o,!1,null,"37ee597f",null),g=h.exports,b={name:"Show",components:{clueDetailsEdit:g,followUpRecord:r["a"]},props:{rowId:{default:""}},data:function(){return{details:[],activeName:"first",loading:!1}},created:function(){this.clueDetails()},methods:{conversion:function(){this.$router.push("/user/company/add?id="+this.details.id)},clueDetails:function(){var e=this;this.loading=!0,Object(f["h"])({clue_id:this.rowId}).then((function(t){e.details=t.data;var i=[];0!=t.data.district1&&i.push(t.data.district1),0!=t.data.district2&&i.push(t.data.district2),0!=t.data.district3&&i.push(t.data.district3),e.details.citycategory_arr=i,e.details.citycategory_arr=i,e.loading=!1})).catch((function(){}))}}},y=b,w=(i("f602"),Object(v["a"])(y,l,n,!1,null,"d1282a80",null)),k=w.exports,S=i("39a0"),C=i("f09a"),x={name:"Whole",components:{show:k},data:function(){return{key_type:"1",loading:!1,props:{multiple:!1,checkStrictly:!0},visiblepop:!1,menu_icon:"menu",keyword:"",drawer:!1,fieldData:[],createdByScreen:"",createdByScreenData:[],regionScreen:"",regionScreenData:[],industryScreen:"",industryScreenData:[],saleScreen:"",saleScreenData:[],pagesize:10,total:0,currentPage:1,saleKey:[{text:"aa",value:"aa"},{text:"bb",value:"bb"},{text:"cc",value:"cc"},{text:"dd",value:"dd"}],multipleSelection:[],exportData:[],tableData:[],title:"",currentNav:"",list_type:0,rowId:0,draggableOption:{sort:!1,scroll:!0,disabled:!0},export_field:[],export_name:[],callDialogVisible:!1,meetDialogVisible:!1,dialPhone:"",dialName:"",sort_type:"",sort:"",saleFilter:"",industryFilter:"",regionFilter:"",createdByFilter:""}},computed:{clueTime:function(){var e=this.$route.query.time;return e}},watch:{clueTime:function(){var e=localStorage.getItem("clue_type"),t=localStorage.getItem("clue_id");""!=e&&"follow"==e&&(this.drawer=!0,this.rowId=t,localStorage.setItem("clue_type",""),localStorage.setItem("clue_id",""))}},mounted:function(){},created:function(){this.currentNav=this.$route.name,"wholeClue"==this.currentNav?(this.title="全部线索",this.list_type=0):"wholeMy"==this.currentNav?(this.title="我的线索",this.list_type=2):"wholeInternationalWaters"==this.currentNav&&(this.title="线索公海",this.list_type=1),this.clueList(),this.crmCustomList(),this.clueAdminLists(),this.classify();var e=localStorage.getItem("clue_type"),t=localStorage.getItem("clue_id");""!=e&&"follow"==e&&(this.drawer=!0,this.rowId=t,localStorage.setItem("clue_type",""),localStorage.setItem("clue_id",""))},methods:{sortTable:function(e){var t=e.column,i=e.order;this.sortType="ascending"==i?"asc":"descending"==i?"desc":"",this.sort_type=t.property,this.sort=this.sortType,this.clueList()},classify:function(){var e=this;Object(f["d"])({type:"citycategory,trade,companyScale"}).then((function(t){e.regionScreenData=t.data.citycategory,e.industryScreenData=t.data.trade})).catch((function(){}))},clueAdminLists:function(){var e=this;Object(f["f"])().then((function(t){e.saleScreenData=t.data,e.createdByScreenData=t.data})).catch((function(){}))},clueList:function(){var e=this;this.loading=!0,Object(f["j"])({page:this.currentPage,pagesize:this.pagesize,list_type:this.list_type,key_type:this.key_type,keyword:this.keyword,trade:this.industryScreen,district:this.regionScreen,creat_id:this.createdByScreen,admin_id:this.saleScreen,sort_type:this.sort_type,sort:this.sort}).then((function(t){e.tableData=t.data.items,e.total=t.data.total,e.currentPage=t.data.current_page,e.loading=!1})).catch((function(){e.loading=!1}))},setFieldClose:function(){this.menu_icon="menu",this.visiblepop=!1},setFieldConfirm:function(){var e=this,t=this.list_type,i=0;0==t&&(i=1),1==t&&(i=2),2==t&&(i=3),Object(f["A"])({menu:i,type:1,value:JSON.stringify(this.fieldData)},"post").then((function(t){200==t.code?e.$message.success(t.message):e.$message.error(t.message)})).catch((function(){}))},crmCustomList:function(){var e=this,t=this.list_type,i=0;0==t&&(i=1),1==t&&(i=2),2==t&&(i=3),Object(f["z"])({menu:i,type:1}).then((function(t){e.fieldData=JSON.parse(t.data);for(var i=0;i<=e.fieldData.length-1;i++)"collection_time"==e.fieldData[i].field?e.fieldData[i].is_sortable="custom":e.fieldData[i].is_sortable=!1,e.export_name.push(e.fieldData[i].name),e.export_field.push(e.fieldData[i].field)})).catch((function(){}))},locking:function(e){for(var t=0;t<=this.fieldData.length-1;t++)this.fieldData[t].field==e&&(1==this.fieldData[t].is_locking?(this.fieldData[t].is_locking=!1,this.fieldData[t].is_lock_display=!1,this.fieldData[t].icon="el-icon-lock"):(this.fieldData[t].icon="el-icon-unlock",this.fieldData[t].is_locking=!0,this.fieldData[t].is_lock_display=!1));this.setFieldConfirm()},select:function(e){for(var t=0;t<=this.fieldData.length-1;t++)this.fieldData[t].field==e&&(1==this.fieldData[t].select?this.fieldData[t].select=!1:this.fieldData[t].select=!0)},menu:function(){"menu"==this.menu_icon?(this.visiblepop=!0,this.menu_icon="menu_select"):(this.visiblepop=!1,this.menu_icon="menu")},confirm:function(){if(""!=this.saleScreen)for(var e=0;e<=this.saleScreenData.length-1;e++)this.saleScreen==this.saleScreenData[e].id&&(this.saleFilter={field:"admin_username",name:this.saleScreenData[e].name});else this.saleFilter="";if(""!=this.industryScreen)for(e=0;e<=this.industryScreenData.length-1;e++)this.industryScreen==this.industryScreenData[e].id&&(this.industryFilter={field:"trade",name:this.industryScreenData[e].name});else this.industryFilter="";if(""!=this.regionScreen){if(1==this.regionScreen.length)for(e=0;e<=this.regionScreenData.length-1;e++)this.regionScreen[0]==this.regionScreenData[e].value&&(this.regionFilter={field:"district",name:this.regionScreenData[e].label});if(2==this.regionScreen.length)for(e=0;e<=this.regionScreenData.length-1;e++)for(var t=0;t<=this.regionScreenData[e].children.length-1;t++)this.regionScreen[1]==this.regionScreenData[e].children[t].value&&(this.regionFilter={field:"district",name:this.regionScreenData[e].children[t].label});if(3==this.regionScreen.length)for(e=0;e<=this.regionScreenData.length-1;e++)for(t=0;t<=this.regionScreenData[e].children.length-1;t++)for(var i=0;i<=this.regionScreenData[e].children[t].children.length-1;i++)this.regionScreen[2]==this.regionScreenData[e].children[t].children[i].value&&(this.regionFilter={field:"district",name:this.regionScreenData[e].children[t].children[i].label})}else this.regionFilter="";if(""!=this.createdByScreen)for(e=0;e<=this.createdByScreenData.length-1;e++)this.createdByScreen==this.createdByScreenData[e].id&&(this.createdByFilter={field:"creat_username",name:this.createdByScreenData[e].name});else this.createdByFilter="";this.currentPage=1,this.clueList()},see:function(e){this.drawer=!0,this.rowId=e},handleClose:function(){this.clueList(),this.drawer=!1},handleSizeChange:function(e){this.pagesize=e,this.clueList()},handleCurrentChange:function(e){this.currentPage=e,this.clueList()},release:function(e){var t=this;this.$confirm("您确认释放该线索？","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){Object(f["l"])({clue_id:e}).then((function(e){200==e.code?t.$message.success(e.message):t.$message.error(e.message),t.clueList()}))})).catch((function(){}))},funAuditBatch:function(e,t){var i=this;if("all"==e){var a=this;if(0==a.multipleSelection.length)return a.$message.error("请选择要领取的线索"),!1;var s=a.multipleSelection}else s=t;this.$confirm("您确认要领取此线索？","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){Object(f["k"])({clue_id:s}).then((function(e){200==e.code?i.$message.success(e.message):i.$message.error(e.message),i.clueList()}))})).catch((function(){}))},wholeDel:function(e,t){var i=this;if("all"==e){var a=this;if(0==a.multipleSelection.length)return a.$message.error("请选择要删除的线索"),!1;var s=a.multipleSelection}else s=t;this.$confirm("您确认要删除此线索?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){Object(f["g"])({clue_id:s}).then((function(e){200==e.code?i.$message.success(e.message):i.$message.error(e.message),i.clueList()}))})).catch((function(){}))},funExport:function(){var e=this;if(0==e.multipleSelection.length)return e.$message.error("请选择要导出的线索"),!1;e.exportExcel(this.exportData)},exportExcel:function(e){var t=this;Promise.resolve().then(function(){var a=i("39a0"),s=(a.export_json_to_excel,t.export_name),l=t.export_field,n=t.formatJson(l,e);Object(S["export_json_to_excel"])(s,n,"线索导出")}.bind(null,i)).catch(i.oe)},formatJson:function(e,t){return t.map((function(t){return e.map((function(e){return t[e]}))}))},funSearchKeyword:function(){this.currentPage=1,this.clueList()},goto:function(e){this.$router.push(e)},locks:function(e){for(var t=0;t<=this.fieldData.length-1;t++)this.fieldData[t].field==e&&(1==this.fieldData[t].is_lock_display?this.fieldData[t].is_lock_display=!1:this.fieldData[t].is_lock_display=!0)},choose:function(){var e=this;this.tableData.forEach((function(t){e.$refs.multipleTable.toggleRowSelection(t)}))},handleSelectionChange:function(e){var t=[];e.forEach((function(e){t.push(e.id)})),this.exportData=e,this.multipleSelection=t},reset:function(e){"all"==e&&(this.saleScreen=[],this.industryScreen=[],this.regionScreen=[],this.createdByScreen=[],this.key_type="1",this.keyword=""),"admin_username"==e&&(this.saleScreen=[]),"trade"==e&&(this.industryScreen=[]),"district"==e&&(this.regionScreen=[]),"creat_username"==e&&(this.createdByScreen=[]),this.confirm()},clickDial:function(e,t){var i=this;this.$confirm("是否对【"+t+"】"+e+" 发起呼叫？","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){Object(C["i"])({mobile:e}).then((function(a){200==a.code&&(i.meetDialogVisible=!0,i.dialPhone=e,i.dialName=t),4040==a.code&&(i.callDialogVisible=!0)})).catch((function(e){}))})).catch((function(){}))},callHandleClose:function(){this.callDialogVisible=!1},meetHandleClose:function(){this.meetDialogVisible=!1}}},D=x,F=(i("daf6"),Object(v["a"])(D,a,s,!1,null,"9c909daa",null));t["default"]=F.exports},daf6:function(e,t,i){"use strict";i("933e")},f602:function(e,t,i){"use strict";i("730b")}}]);