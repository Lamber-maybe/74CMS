(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-313f6534"],{3555:function(t,e,s){},"861b":function(module,__webpack_exports__,__webpack_require__){"use strict";var core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_0__=__webpack_require__("a15b"),core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_0___default=__webpack_require__.n(core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_0__),trtc_js_sdk__WEBPACK_IMPORTED_MODULE_1__=__webpack_require__("5781"),trtc_js_sdk__WEBPACK_IMPORTED_MODULE_1___default=__webpack_require__.n(trtc_js_sdk__WEBPACK_IMPORTED_MODULE_1__),_utils_http__WEBPACK_IMPORTED_MODULE_2__=__webpack_require__("751a"),_api__WEBPACK_IMPORTED_MODULE_3__=__webpack_require__("d722");__webpack_exports__["a"]={name:"Test",data:function(){return{isChrome:!0,client:"",userId:"",roomId:"74_"+parseInt(1e8*Math.random()),localStream:"",audio:!0,video:!0,interview_id:0,config:{appid:"",sig:""},netCheckResult:"网络检测中...",netCheckStatus:!1,audioPlayShow:!0,volmueTimer:"",volume:1,error:0,camerasList:[],camerasName:"默认 - 摄像头",microphonesList:[],microphoneName:"默认 - 麦克风阵列",speakersList:[],speakerName:"默认 - 扬声器"}},created:function(){this.isChrome=navigator.userAgent.indexOf("Chrome")>-1,this.interview_id=this.$route.params.interview_id,this.getConfig()},mounted:function(){},methods:{detectVolume:function(t){var e=this;this.volmueTimer=window.setInterval((function(){e.volume=100*t.getAudioLevel()}),600)},audioPlay:function(){this.$refs.sampleMusic.play(),this.audioPlayShow=!this.audioPlayShow},audioPaused:function(){this.$refs.sampleMusic.pause(),this.audioPlayShow=!this.audioPlayShow},getConfig:function(){var t=this;_utils_http__WEBPACK_IMPORTED_MODULE_2__["a"].post(_api__WEBPACK_IMPORTED_MODULE_3__["a"].rtc_config).then((function(e){t.config.appid=e.data.appid,t.config.sig=e.data.sig,t.userId=e.data.userid,t.netCheckStatus=!0,t.netCheckResult=t.netCheckStatus?"您当前的网络质量较好，可以进行视频面试":"您当前的网络质量比较差，建议更换网络环境",t.error=e.data.error,t.createClient()})).catch((function(){}))},createClient:function(){var t=this.config.appid,e=this.config.sig,s=this.userId;this.client=trtc_js_sdk__WEBPACK_IMPORTED_MODULE_1___default.a.createClient({mode:"videoCall",sdkAppId:t,userId:s,userSig:e}),console.warn("链接成功，准备加入房间"),this.joinRoom(this.client,this.roomId)},createStream:function(t){var e=this,s=trtc_js_sdk__WEBPACK_IMPORTED_MODULE_1___default.a.createStream({userId:t,audio:this.audio,video:this.video,facingMode:"user"});s.setVideoProfile("720p"),this.localStream=s,trtc_js_sdk__WEBPACK_IMPORTED_MODULE_1___default.a.getCameras().then((function(t){e.camerasList=t,e.camerasName=t[0].label})),trtc_js_sdk__WEBPACK_IMPORTED_MODULE_1___default.a.getMicrophones().then((function(t){e.microphonesList=t,e.microphoneName=t[0].label})),trtc_js_sdk__WEBPACK_IMPORTED_MODULE_1___default.a.getSpeakers().then((function(t){e.speakersList=t,e.speakerName=t[0].label})),s.initialize().catch((function(t){console.error("初始化本地流失败 "+t)})).then((function(){console.warn("初始化本地流成功，准备发布本地流"),e.publishStream(s,e.client),s.play("local_stream"),e.detectVolume(s)}))},joinRoom:function(t,e){var s=this;t.join({roomId:e}).catch((function(t){console.error("进房失败 "+t)})).then((function(){console.warn("进房成功，准备创建本地流"),s.createStream(s.userId)}))},publishStream:function(t,e){e.publish(t).catch((function(t){console.error("本地流发布失败 "+t)})).then((function(){console.warn("本地流发布成功")}))},changeDevice:function(t,e){1===t?(this.microphoneName=this.microphonesList[e].label,this.switchLocalDevice(t,this.microphonesList[e].deviceId)):0===t&&(this.camerasName=this.camerasList[e].label,this.switchLocalDevice(t,this.camerasList[e].deviceId))},switchLocalDevice:function switchLocalDevice(type,dId){var _this4=this;0===eval(type)?this.localStream.switchDevice("video",dId).then((function(){_this4.$message({type:"success",message:"设备切换成功"})})):1===eval(type)&&this.localStream.switchDevice("audio",dId).then((function(){_this4.$message({type:"success",message:"设备切换成功"})}))}}}},b5d0:function(t,e,s){"use strict";s.r(e);var i=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("div",{staticClass:"vi_head"},[i("div",{staticClass:"head_content"},[i("a",{attrs:{href:""}},[i("img",{staticClass:"hc_logo",attrs:{src:t.$store.state.config.logo,alt:"logo"}})]),i("div",{staticClass:"hc_logo_vi"}),i("a",{staticClass:"hc_back_home",attrs:{href:t.$store.state.config.link_url_web.index}},[t._v("返回网站")]),i("a",{staticClass:"hc_contact"},[t._v("联系电话："+t._s(t.$store.state.config.contact_tel))]),i("a",{staticClass:"hc_detect",attrs:{href:""}},[t._v("设备检测")])])]),i("div",{staticClass:"vi_container"},[i("div",{directives:[{name:"show",rawName:"v-show",value:!t.isChrome,expression:"!isChrome"}],staticClass:"tip"},[t._v(" 为提高视频面试效率，建议您使用谷歌内核浏览器并提前检测您的设备是否能够正常连通 ")]),i("div",{staticClass:"device_detect_group"},[i("div",{staticClass:"group_title"},[t._v("视频面试·设备检测")]),i("div",{staticClass:"detect_list"},[i("div",{staticClass:"list_line",attrs:{id:"video_grid"}},[i("div",{staticClass:"line_title t1"},[t._v("摄像头检测")]),i("div",{class:t.camerasList.length>1?"line_li":"line_li disable"},[t.camerasList.length>1?i("el-dropdown",{attrs:{placement:"bottom"}},[i("span",{staticClass:"el-dropdown-link"},[t._v(" "+t._s(t.camerasName)+" ")]),i("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},t._l(t.camerasList,(function(e,s){return i("el-dropdown-item",{key:s,nativeOn:{click:function(s){return t.changeDevice(e)}}},[t._v(" "+t._s(e.label)+" ")])})),1)],1):i("span",[t._v(t._s(t.camerasName))])],1),i("div",{staticClass:"detect_result",attrs:{id:"local_stream"}})]),i("div",{staticClass:"list_line"},[i("div",{staticClass:"line_title t2"},[t._v(" 麦克风 (请说话，并观察右侧音量条是否变化) ")]),i("div",{class:t.microphonesList.length>1?"line_li":"line_li disable"},[t.microphonesList.length>1?i("el-dropdown",{attrs:{placement:"bottom"}},[i("span",{staticClass:"el-dropdown-link"},[t._v(" "+t._s(t.microphoneName)+" ")]),i("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},t._l(t.microphonesList,(function(e,s){return i("el-dropdown-item",{key:s,nativeOn:{click:function(e){return t.changeDevice(1,s)}}},[t._v(" "+t._s(e.label)+" ")])})),1)],1):i("span",[t._v(t._s(t.microphoneName))])],1),i("div",{staticClass:"detect_result"},[i("div",{staticClass:"volume_bar"},[i("div",{staticClass:"volume_contetn"},[i("div",{staticClass:"volume_auto",style:{width:t.volume+"%"}})])])])]),i("div",{staticClass:"list_line"},[i("div",{staticClass:"line_title t4"},[t._v(" 扬声器 (播放测试音频，并观察是否可以听到声音) ")]),i("div",{staticClass:"line_li disable"},[t._v(" "+t._s(t.speakerName)+" ")]),i("div",{staticClass:"detect_result"},[i("audio",{ref:"sampleMusic",staticClass:"audio",attrs:{loop:"loop",controls:"controls"}},[i("source",{attrs:{src:s("e172"),type:"audio/mp3"}}),t._v(" Your browser does not support the audio tag. ")]),t.audioPlayShow?i("div",{staticClass:"test_play",on:{click:t.audioPlay}},[t._v("播放测试音频")]):i("div",{staticClass:"test_playing",on:{click:t.audioPaused}},[t._v("正在播放")])])]),i("div",{staticClass:"list_line"},[i("div",{staticClass:"line_title t3"},[t._v("网络检测")]),i("div",{staticClass:"line_li not"},[i("span",{class:t.netCheckStatus?"good":"bad"},[t._v(t._s(t.netCheckResult))])]),i("div",{staticClass:"detect_result"},[i("div",{staticClass:"network_check"},[i("div",{class:t.netCheckStatus?"check_ico checked":"check_ico"}),i("div",[t._v(t._s(t.netCheckStatus?"网络检测完成":"网络检测中..."))])])])])]),1!==t.error?i("router-link",{staticClass:"jump_room",attrs:{to:"/video/"+t.interview_id}},[t._v("进入面试")]):t._e()],1)])])},a=[],_=s("861b"),o=_["a"],c=(s("fff0"),s("2877")),n=Object(c["a"])(o,i,a,!1,null,"8770f954",null);e["default"]=n.exports},e172:function(t,e,s){t.exports=s.p+"static/media/music.b8d218cf.mp3"},fff0:function(t,e,s){"use strict";s("3555")}}]);