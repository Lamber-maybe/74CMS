(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-58def302"],{"1f70":function(t,e,i){"use strict";i("8a7b")},6061:function(t,e,i){},"8a7b":function(t,e,i){},"95a8":function(t,e,i){"use strict";var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("el-card",{staticClass:"box-card"},[i("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[i("span",[t._v("海报设置")])]),i("div",{staticClass:"tip"},[i("p",[t._v("海报开启后前台切换下一个自动调用对应海报背景。您必须保证至少有一个海报在启用状态。")]),i("p",[t._v("您可自主添加海报模板，海报尺寸1080(宽)x1920(高)，生成的海报自带信息内容展示区域，无须预留白块区域。")])]),i("el-card",{staticClass:"img-content-box",attrs:{"body-style":{padding:"0px",height:"416px",display:"flex","justify-content":"center","align-items":"center",cursor:"pointer"}},nativeOn:{click:function(e){return t.funAdd.apply(null,arguments)}}},[i("div",[i("i",{staticClass:"el-icon-plus"}),i("span",{staticClass:"add"},[t._v("添加海报")])])]),t._l(t.list,(function(e,s){return i("el-card",{key:s,staticClass:"img-content-box",attrs:{"body-style":{padding:"0px"}}},[i("div",{staticClass:"imgbox"},[i("img",{staticClass:"image",attrs:{src:""!=e.img_src?e.img_src+"?_="+Math.random():"https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png"}}),i("span",{staticClass:"actions"},[i("el-button",{staticStyle:{float:"left","margin-right":"6px"},attrs:{type:"text"},on:{click:function(i){return t.funEdit(e)}}},[i("i",{staticClass:"el-icon-edit-outline",staticStyle:{"margin-right":"2px"}}),t._v("编辑")]),i("el-button",{staticStyle:{float:"left"},attrs:{type:"text"},on:{click:function(i){return t.funPoster(e)}}},[i("i",{staticClass:"el-icon-view",staticStyle:{"margin-right":"2px"}}),t._v("预览")]),i("el-button",{staticStyle:{float:"right"},attrs:{type:"text"},on:{click:function(i){return t.funDelete(e)}}},[i("i",{staticClass:"el-icon-delete",staticStyle:{"margin-right":"2px"}}),t._v("删除")]),i("div",{staticClass:"clearfix"})],1)]),i("div",{staticStyle:{padding:"14px"}},[i("div",{staticClass:"bottom clearfix"},[i("span",{staticClass:"name"},[t._v(t._s(e.name))]),i("span",{staticClass:"audit"},[t._v("状态：")]),i("el-switch",{staticClass:"is_display",on:{change:function(i){return t.funDisplay(e)}},model:{value:e.is_display,callback:function(i){t.$set(e,"is_display",i)},expression:"item.is_display"}})],1)])])})),i("div",{staticClass:"clearfix"})],2),t.showDialog?i("el-dialog",{attrs:{title:t.dialogTitle,visible:t.showDialog,width:"500px","close-on-click-modal":!1},on:{"update:visible":function(e){t.showDialog=e}}},[i("el-form",{ref:"dialogForm",attrs:{model:t.form,rules:t.rules,"label-width":"80px"}},[i("el-form-item",{attrs:{label:"海报名称",prop:"name"}},[i("el-input",{model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),i("el-form-item",{attrs:{label:"背景图",prop:"img"}},[i("el-upload",{staticClass:"img-uploader",attrs:{action:t.apiUpload,headers:t.headers,"show-file-list":!1,"on-success":t.handleImgSuccess,"before-upload":t.beforeImgUpload}},[t.imgUrl?i("img",{staticClass:"img",attrs:{src:t.imgUrl}}):i("i",{staticClass:"el-icon-plus img-uploader-icon"})]),i("span",{staticClass:"smalltip"},[i("i",{staticClass:"el-icon-info"}),t._v(" 建议尺寸1080*1920 ")])],1),i("el-form-item",{attrs:{label:"排序"}},[i("el-input",{model:{value:t.form.sort_id,callback:function(e){t.$set(t.form,"sort_id",e)},expression:"form.sort_id"}})],1),i("el-form-item",{attrs:{label:"是否显示"}},[i("el-switch",{model:{value:t.form.is_display,callback:function(e){t.$set(t.form,"is_display",e)},expression:"form.is_display"}})],1),i("el-form-item",[i("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.onSubmitDialog("dialogForm")}}},[t._v("保存")]),i("el-button",{on:{click:function(e){t.showDialog=!1}}},[t._v("取消")])],1)],1)],1):t._e(),t.showPoster?i("Poster",{attrs:{"poster-index":t.posterIndex,"poster-type":t.posterType},on:{closeDialog:function(e){t.showPoster=!1}}}):t._e()],1)},a=[],n=i("5530"),o=(i("d3b7"),i("3ca3"),i("ddb0"),i("2b3d"),i("9861"),i("ac1f"),i("1276"),i("caad"),i("2532"),i("159b"),i("4de4"),i("f211")),r=i("d722"),l=i("5f87"),c=i("e4bb"),d={components:{Poster:c["a"]},props:["type"],data:function(){return{showPoster:!1,posterIndex:"",posterType:"",headers:{admintoken:Object(l["e"])()},fileupload_size:"",fileupload_ext:"",apiUpload:window.global.RequestBaseUrl+r["a"].uploadPoster,showDialog:!1,dialogTitle:"",listLoading:!1,form:{id:"",type:"",name:"",img:"",sort_id:0,is_display:!0},imgUrl:"",list:[],rules:{name:[{required:!0,message:"请输入海报名称",trigger:"blur"}],img:[{required:!0,message:"请上传背景图",trigger:"change"}]}}},computed:{config:function(){return this.$store.state.config}},created:function(){this.fileupload_size=this.config.fileupload_size,this.fileupload_ext=this.config.fileupload_ext,this.form.type=this.type,this.fetchData()},methods:{funAdd:function(){this.dialogTitle="添加海报",this.form={id:"",type:this.type,name:"",img:"",sort_id:0,is_display:!0},this.imgUrl="",this.showDialog=!0},funEdit:function(t){this.dialogTitle="编辑海报",this.form=Object.assign(this.form,t),this.form.img="-1",this.imgUrl=t.img_src,this.form.type=this.type,this.showDialog=!0},handleImgSuccess:function(t,e){if(200!==t.code)return this.$message.error(t.message),!1;this.imgUrl=URL.createObjectURL(e.raw),this.form.img=t.data},beforeImgUpload:function(t){var e=t.type.split("/"),i=e[1],s=this.fileupload_ext.split(",");return s.includes(i)?!(t.size/1024>this.fileupload_size)||(this.$message.error("上传文件最大为".concat(this.fileupload_size,"kb")),!1):(this.$message.error("上传文件格式不允许"),!1)},fetchData:function(){var t=this;this.listLoading=!0,Object(o["f"])({type:this.type}).then((function(e){t.list=e.data,t.list.forEach((function(t){t.is_display=1==t.is_display}))})).catch((function(){t.listLoading=!1}))},funDelete:function(t){var e=this;if(this.list.length<=1)return this.$message.error("请至少保留一个海报模板"),!1;e.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){var i={id:t.id};Object(o["c"])(i).then((function(t){return e.$message.success(t.message),e.showDialog=!1,e.fetchData(),!0}))})).catch((function(){}))},funDisplay:function(t){var e=this;if(0==t.is_display){var i=this.list.filter((function(t,e,i){return t.is_display}));if(0==i.length)return t.is_display=!0,this.$message.error("请至少保留一个海报模板为显示状态"),!1}var s={id:t.id};Object(o["d"])(s).then((function(t){return e.$message.success(t.message),e.fetchData(),!0}))},onSubmitDialog:function(t){var e=this,i=Object(n["a"])({},this.form);this.$refs[t].validate((function(t){t&&(i.is_display=1==i.is_display?1:0,i.id>0?Object(o["e"])(i).then((function(t){return e.$message.success(t.message),e.fetchData(),e.showDialog=!1,!0})).catch((function(){})):Object(o["b"])(i).then((function(t){return e.$message.success(t.message),e.fetchData(),e.showDialog=!1,!0})).catch((function(){})))}))},funPoster:function(t){this.showPoster=!0,this.posterIndex=t.indexid,this.posterType=1==this.type?"job":2==this.type?"resume":"company"}}},u=d,p=(i("1f70"),i("2877")),f=Object(p["a"])(u,s,a,!1,null,"31adcb19",null);e["a"]=f.exports},cead:function(t,e,i){"use strict";i("6061")},e056b:function(t,e,i){"use strict";i.r(e);var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("common",{attrs:{type:"2"}})],1)},a=[],n=i("95a8"),o={components:{common:n["a"]}},r=o,l=i("2877"),c=Object(l["a"])(r,s,a,!1,null,null,null);e["default"]=c.exports},e4bb:function(t,e,i){"use strict";var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("el-dialog",{attrs:{title:"",visible:!0,width:"300px",center:"","show-close":!1,"custom-class":"poster_dialog"}},[i("div",{directives:[{name:"loading",rawName:"v-loading",value:""==t.posterImg,expression:"posterImg == ''"}],staticStyle:{height:"533px"}},[""!=t.posterImg?i("img",{staticStyle:{width:"300px"},attrs:{id:"posterImg",src:t.posterImg}}):t._e()]),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[void 0===t.posterIndex?i("el-button",{attrs:{type:"warning",size:"small"},on:{click:t.changeTpl}},[t._v("换一个")]):t._e(),void 0===t.posterIndex?i("el-button",{attrs:{type:"primary",size:"small"},on:{click:t.downloadIamge}},[t._v("保存到电脑")]):t._e(),i("el-button",{attrs:{size:"small"},on:{click:function(e){return t.$emit("closeDialog")}}},[t._v("关 闭")])],1)])],1)},a=[],n=i("f211"),o=i("5f87"),r=i("d722"),l={props:["posterId","posterType","posterIndex"],data:function(){return{currentTplIndex:1,posterImg:"",indexlist:[]}},created:function(){void 0!==this.posterIndex&&(this.currentTplIndex=this.posterIndex),this.fetchData(),this.funPoster()},methods:{fetchData:function(){var t=this;this.listLoading=!0,Object(n["g"])({type:"job"==this.posterType?1:"resume"==this.posterType?2:3}).then((function(e){t.indexlist=e.data})).catch((function(){t.listLoading=!1}))},changeTpl:function(){var t=this.indexlist.indexOf(this.currentTplIndex),e=t+1;void 0===this.indexlist[e]&&(e=0),this.currentTplIndex=this.indexlist[e],this.funPoster()},funPoster:function(){var t=this;this.posterImg="";var e={type:this.posterType,id:void 0===this.posterId?0:this.posterId,index:this.currentTplIndex};Object(n["a"])(e).then((function(e){t.posterImg=e.data+"?_="+Math.random()}))},downloadIamge:function(){var t=window.global.RequestBaseUrl+r["a"].downloadPoster+(-1==window.global.RequestBaseUrl.indexOf("?")?"?":"&")+"admintoken="+Object(o["e"])()+"&type="+this.posterType+"&id="+(void 0===this.posterId?0:this.posterId)+"&index="+this.currentTplIndex;window.location.href=t}}},c=l,d=(i("cead"),i("2877")),u=Object(d["a"])(c,s,a,!1,null,"1b48c9c8",null);e["a"]=u.exports},f211:function(t,e,i){"use strict";i.d(e,"a",(function(){return n})),i.d(e,"f",(function(){return o})),i.d(e,"b",(function(){return r})),i.d(e,"e",(function(){return l})),i.d(e,"d",(function(){return c})),i.d(e,"c",(function(){return d})),i.d(e,"g",(function(){return u}));var s=i("b775"),a=i("d722");function n(t){return Object(s["a"])({url:a["a"].makePoster,method:"get",params:t})}function o(t){return Object(s["a"])({url:a["a"].posterList,method:"get",params:t})}function r(t){return Object(s["a"])({url:a["a"].posterAdd,method:"post",data:t})}function l(t){return Object(s["a"])({url:a["a"].posterEdit,method:"post",data:t})}function c(t){return Object(s["a"])({url:a["a"].posterDisplay,method:"post",data:t})}function d(t){return Object(s["a"])({url:a["a"].posterDelete,method:"post",data:t})}function u(t){return Object(s["a"])({url:a["a"].posterTplindexList,method:"get",params:t})}}}]);