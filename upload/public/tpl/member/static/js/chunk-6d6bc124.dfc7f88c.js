(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-6d6bc124"],{"0279":function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAANCAIAAAD9iXMrAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiZDFkODdlYS0wOWRmLTk0NGItYmY4ZS03OTVlMTcwMmM4NmEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OUUxQjNEQjRFNUJFMTFFQTg2NEFCN0RBRjYxRDRBNEQiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OUM1OURERDhFNUJFMTFFQTg2NEFCN0RBRjYxRDRBNEQiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjEgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MjY0NWZmMDUtODA2Zi0yMjRmLTgyM2ItNjM2NTAzOGJlOTQ5IiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6ZjU0YmQ2ODItZjkzNC1mZTQzLWI3NjgtZGRmNDcyMWNjNjM3Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+UmtUUwAAAM5JREFUeNqUkdkKhDAMRW0URJGC/v//+SKKIm64zKmZKUWGgclDaHMPyW1qruuK7ljXdRxH8nmeIpKmaVEUZFWNcl3XQVhrsywzxhXneR6GAa4syzfXti25qioISvu+J0nCQSWKSEKbbdtCqK5rsutxE6gwpmkaZuV5Hn3iOI44jv11miY8uH5wURB4Da+oMMLrdKIPBoVXfRYbEL+ar4EK6va0LMsPDhVGWGbf92HL0AZ1VBg3G+O49Kvx79X9YYxV//Mfj/9V44//fQkwAAnLsDNufuJvAAAAAElFTkSuQmCC"},"172f":function(t,e,s){},"69fa":function(t,e,s){"use strict";s.r(e);var _=function(){var t=this,e=t.$createElement,_=t._self._c||e;return _("div",[_("el-card",[_("div",{staticClass:"setmeal_header"},[_("div",{staticClass:"header_left"},[_("div",{staticClass:"header_img"},[_("img",{staticClass:"logo",attrs:{src:t.companyInfo.logo_src,alt:"logo"}})]),_("div",{staticClass:"header_title"},[_("div",{staticClass:"text_1"},[t._v(t._s(t.companyInfo.companyname))]),_("p",{staticClass:"text_2"},[t._v("我的套餐："),_("span",[t._v(t._s(t.mySetmeal.name))]),_("router-link",{attrs:{to:"/company/service/setmeal/detail"}},[t._v("我的特权>")])],1),_("p",{staticClass:"text_3"},[t._v("服务期限："),0==t.mySetmeal.deadline?_("span",[t._v("无限期")]):_("span",[t._v("有效期至 "+t._s(t._f("timeFilter")(t.mySetmeal.deadline)))])])])]),_("div",{staticClass:"header_right"},[_("span"),_("div",{staticClass:"right_item"},[_("p",[t._v("职位并发数")]),_("div",[_("span",[t._v(t._s(t.mySetmeal.jobs_meanwhile)+" ")]),t._v(" 条 ")])]),_("div",{staticClass:"right_item"},[_("p",[t._v("简历剩余点数")]),_("div",[_("span",[t._v(t._s(t.mySetmeal.download_resume_point)+" ")]),t._v(" 点 ")])]),_("div",{staticClass:"right_item"},[_("p",[t._v("职聊剩余次数")]),_("div",[_("span",[t._v(t._s(t.mySetmeal.im_total)+" ")]),t._v(" 次 ")])]),_("div",{staticClass:"right_item"},[_("p",[t._v("免费刷新次数")]),_("div",[_("span",[t._v(t._s(t.mySetmeal.refresh_jobs_free_perday)+" ")]),t._v(" 次/天 ")])]),_("div",{staticClass:"right_item"},[_("p",[t._v("我的"+t._s(t.$store.state.config.points_byname))]),_("div",[_("span",[t._v(t._s(t.myPoints)+" ")]),t._v(" "+t._s(t.$store.state.config.points_quantifier)+" ")])])])])]),_("div",{staticClass:"upgradesetmeal_con"},[_("el-card",[_("company-title",[t._v("请选择套餐")]),_("div",{staticClass:"waning_text"},[_("img",{attrs:{src:s("af77")}}),t._v(" 在当前套餐未结束前，购买新的套餐，将获得套餐时间"+t._s(0==t.$store.state.config.reopen_setmeal_deadline?"不":"")+"叠加，资源"+t._s(0==t.$store.state.config.reopen_setmeal_resource?"不":"")+"叠加，权限以新购买的会员为准 ")]),_("div",{staticClass:"item_wrapper"},[_("div",{staticClass:"item_con"},[_("div",{ref:"item_conRef",staticClass:"item_com_cti"},[t._l(t.setmealList,(function(e,i){return _("div",{key:i,ref:"itemDom"+i,refInFor:!0,staticClass:"item"},[_("div",{staticClass:"item_title"},[1==e.recommend?_("img",{staticClass:"recommend",attrs:{src:s("9010")}}):t._e(),_("p",{staticClass:"st_name"},[t._v(t._s(e.name))]),_("p",{staticClass:"st_day"},[t._v(t._s(parseInt(e.days)>0?e.days+"天":"无限期"))]),_("div",{staticClass:"ellipse"})]),_("div",{staticClass:"item_box"},[_("div",{staticClass:"Price"},[_("p",[t._v("￥"),_("span",[t._v(t._s(e.expense))])])]),1===parseInt(e.preferential_open)?_("p",{staticClass:"or_price"},[t._v("原价: ￥"+t._s(e.original_expense))]):t._e(),_("div",{staticClass:"btn"},[_("el-button",{staticClass:"el_button",on:{click:function(s){return t.buyNow(e)}}},[t._v("立即购买")])],1),1===parseInt(e.preferential_open)?_("p",{staticClass:"or_pre_date"},[t._v(" 限时特惠 : "+t._s(t._f("timeFilterTwo")(e.preferential_expense_start))+" 至 "+t._s(t._f("timeFilterTwo")(e.preferential_expense_end))+" ")]):t._e()]),_("div",{staticClass:"details"},[_("div",{staticClass:"border"}),_("p",[_("span",[t._v("职位并发数")]),_("span",[t._v(t._s(e.jobs_meanwhile)+"条")])]),_("p",[_("span",[t._v("赠送简历点数")]),_("span",[t._v(t._s(e.download_resume_point)+"点")])]),_("p",[_("span",[t._v("增值服务折扣")]),e.service_added_discount>0?_("span",[t._v(t._s(e.service_added_discount)+"折")]):_("span",[t._v("无折扣")])]),_("div",{staticClass:"type",on:{mouseover:function(s){return t.hover(e,i)},mouseout:function(s){return t.removehove(e,i)}}},[_("span",{staticClass:"type_left"},[t._v("查看更多特权")]),_("span",{staticClass:"type_right"},[_("img",{attrs:{src:s("0279")}})]),_("div",{directives:[{name:"show",rawName:"v-show",value:e.show_box,expression:"item.show_box"}],class:[t.hoverIndex?"padding2":"padding","hover_box"]},[_("span",{class:[t.hoverIndex?"arrow_right":"arrow_left","arrow"]}),_("div",[_("div",{staticClass:"hover_title"},[t._v("套餐资源")]),_("ul",[_("li",[t._v("职位并发数："),_("span",[t._v(t._s(e.jobs_meanwhile))])]),_("li",[t._v("赠送简历点数："),_("span",[t._v(t._s(e.download_resume_point))])]),_("li",[t._v("职聊次数："),_("span",[t._v(t._s(e.im_total))])]),_("li",[t._v("赠送会员"+t._s(t.$store.state.config.points_byname)+"："),_("span",[t._v(t._s(e.gift_point))])]),_("li",[t._v("免费刷新职位："),_("span",[t._v(t._s(e.refresh_jobs_free_perday)+"次/天")])]),_("li",[t._v("下载简历上限："),_("span",[t._v(t._s(e.download_resume_max_perday)+"份/天")])]),_("li",[t._v("每日职聊上限："),_("span",[t._v(t._s(e.im_max_perday)+"次/天")])])])]),e.service_added_discount>0||1==e.enable_video_interview||1==e.show_apply_contact||""!=e.note?_("div",[_("div",{staticClass:"hover_title"},[t._v("套餐特权")]),_("ul",[e.service_added_discount>0?_("li",[t._v("套餐增值包折扣"+t._s(e.service_added_discount)+"折")]):t._e(),1===e.show_apply_contact?_("li",[_("span",[t._v("收到简历免费查看：允许")])]):t._e()]),""!=e.note?_("div",{staticClass:"hover_otd"},[_("span",[t._v("其他说明：")]),_("span",{staticClass:"dec"},[t._v(t._s(e.note))])]):_("div",{staticClass:"hover_otd"},[_("span",[t._v("其他说明：")]),_("span",{staticClass:"dec"},[t._v("无")])])]):t._e()])])])])})),_("div",{staticClass:"clear"})],2)]),t._e()])],1)],1)],1)},i=[],a=s("99c8"),n=a["a"],c=(s("9dd2"),s("2877")),o=Object(c["a"])(n,_,i,!1,null,"a271e35a",null);e["default"]=o.exports},9010:function(t,e){t.exports="data:image/png;base64,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"},"99c8":function(module,__webpack_exports__,__webpack_require__){"use strict";var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__=__webpack_require__("d3b7"),core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0___default=__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__),core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_1__=__webpack_require__("159b"),core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_1___default=__webpack_require__.n(core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_1__),_utils_index__WEBPACK_IMPORTED_MODULE_2__=__webpack_require__("ed08"),_utils_http__WEBPACK_IMPORTED_MODULE_3__=__webpack_require__("751a"),_api__WEBPACK_IMPORTED_MODULE_4__=__webpack_require__("d722");__webpack_exports__["a"]={filters:{timeFilter:function(t){return void 0===t?"":Object(_utils_index__WEBPACK_IMPORTED_MODULE_2__["g"])(t,"{y}-{m}-{d} {h}:{i}:{s}")},timeFilterTwo:function(t){return Object(_utils_index__WEBPACK_IMPORTED_MODULE_2__["g"])(t,"{m}-{d}")}},data:function(){return{companyInfo:{},mySetmeal:{},myPoints:0,setmealList:[],hoverIndex:!1,index:0,startNum:5}},created:function(){this.fetchSetmeal()},mounted:function(){console.log(this.$refs)},methods:{buyNow:function(t){1===t.is_apply?this.$router.push("/company/service/setmeal/add?setmeal_id="+t.id):this.$message({message:"此套餐暂不允许购买",type:"error"})},fetchSetmealList:function(){var t=this;this.setmealList=[],_utils_http__WEBPACK_IMPORTED_MODULE_3__["a"].get(_api__WEBPACK_IMPORTED_MODULE_4__["a"].company_setmeallist,{}).then((function(e){var s=e.data.items;s.forEach((function(e){e.show_box=!1,t.setmealList.push(e)}))})).catch((function(){}))},fetchSetmeal:function(){var t=this;_utils_http__WEBPACK_IMPORTED_MODULE_3__["a"].get(_api__WEBPACK_IMPORTED_MODULE_4__["a"].member_setmeal,{}).then((function(e){t.mySetmeal=e.data.info,t.companyInfo=e.data.company_info,t.myPoints=e.data.points,t.fetchSetmealList()})).catch((function(){}))},hover:function hover(item,index){item.show_box=!0;var itemDom=eval("this.$refs.itemDom"+index)[0],docWidth=document.documentElement.clientWidth,itemDomOff=itemDom.getBoundingClientRect();this.hoverIndex=docWidth-itemDomOff.right<450},removehove:function(t){t.show_box=!1},next:function(){this.index>=this.setmealList.length-1||this.startNum===this.setmealList.length?(this.index=0,this.startNum=5):(this.index++,this.startNum++),this.$refs.item_conRef.style.transform="translate3d(-".concat(267*this.index,"px, 0px, 0px)")}}}},"9dd2":function(t,e,s){"use strict";s("172f")},af77:function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAIAAADZrBkAAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiZDFkODdlYS0wOWRmLTk0NGItYmY4ZS03OTVlMTcwMmM4NmEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NEUxMEM1REZFMTFCMTFFQTk5QjNFQjg1NTIwQ0JFQzciIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NEUxMEM1REVFMTFCMTFFQTk5QjNFQjg1NTIwQ0JFQzciIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjEgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NjA0NGFiNGItZjI3Yi0wZTQ0LTg5NDctZTc2NWEyMTNkZWE5IiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6ODAxNTdjMjAtMjQ2Yi1jNDRkLTgxMzMtZjNjY2MyNDNlY2I1Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+hd7SlwAAAbBJREFUeNqUk0tLQkEYht/vnGO1Sw0qowvezYKE+hXtughBl0XboHX/QFpKtRXKgi7UplX/ocBFaKYJUmR2UZcZ2vRNeT1K1MtwYD7eZ2bON+/QZ+kJjUreUOQSyTjyOTk1meFwC98UHJ5GF9Wx5yc6PUAqAbtbuL0wmmWxkKN4FHdx2JxibhG9/c1YKkGhbViGxOwCLIPQKfNAZ4d4vBera7C7qthLloIBjE8I/wpUtWItvstvZ1dlWi7TSRjXEbG+wXsqEIKOdnkH4V+uM7xeMCDXqklVhX+JbXS8x4jCPUA6JeYXoWpNBxv3YWyiqaJqkkynGNEocgWXF30W3e+I6Rm0ilvi8jKicJeE09NqoItzHq11aebd5P389Fqn12e0FZsLeQWahv9LQbeR7/QfBJuNJgVWJyVu/k5Js8OjCN8kbqOcrD9BL1k2M6LIjI7Y6GQf5VKTw2CQo1HlEh2HMWxl5Dtc2QxtberD9VGU347OtuGqRvnulkI7GBiSOajGvC5+HHwcfZT1D8cl3GMw9chi/u3Xh1NTIibjlozxncqUGk3catk252ij60uAAQDK+r7g7CPs2wAAAABJRU5ErkJggg=="}}]);