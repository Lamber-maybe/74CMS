(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3237ad37"],{"99af":function(t,e,i){"use strict";var a=i("23e7"),n=i("da84"),s=i("d039"),o=i("e8b5"),r=i("861d"),c=i("7b0b"),l=i("07fa"),d=i("8418"),u=i("65f0"),m=i("1dde"),v=i("b622"),_=i("2d00"),h=v("isConcatSpreadable"),f=9007199254740991,p="Maximum allowed index exceeded",g=n.TypeError,b=_>=51||!s((function(){var t=[];return t[h]=!1,t.concat()[0]!==t})),w=m("concat"),C=function(t){if(!r(t))return!1;var e=t[h];return void 0!==e?!!e:o(t)},x=!b||!w;a({target:"Array",proto:!0,forced:x},{concat:function(t){var e,i,a,n,s,o=c(this),r=u(o,0),m=0;for(e=-1,a=arguments.length;e<a;e++)if(s=-1===e?o:arguments[e],C(s)){if(n=l(s),m+n>f)throw g(p);for(i=0;i<n;i++,m++)i in s&&d(r,m,s[i])}else{if(m>=f)throw g(p);d(r,m++,s)}return r.length=m,r}})},a851:function(t,e,i){"use strict";i.r(e);var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("div",{staticClass:"vi_head"},[i("div",{staticClass:"head_content"},[i("a",{attrs:{href:""}},[i("img",{staticClass:"hc_logo",attrs:{src:t.$store.state.config.logo,alt:"logo"}})]),i("div",{staticClass:"hc_logo_vi"}),i("a",{staticClass:"hc_back_home",attrs:{href:t.$store.state.config.link_url_web.index}},[t._v("返回网站")]),i("a",{staticClass:"hc_contact"},[t._v("联系电话："+t._s(t.$store.state.config.contact_tel))]),i("router-link",{staticClass:"hc_detect",attrs:{to:"/video_test/"+t.interview_id}},[t._v("设备检测")])],1)]),i("div",{staticClass:"vi_container"},[i("div",{directives:[{name:"show",rawName:"v-show",value:!t.isChrome,expression:"!isChrome"}],staticClass:"tip"},[t._v(" 为提高视频面试效率，建议您使用谷歌内核浏览器并提前检测您的设备是否能够正常连通 ")]),"opened"!==t.room_status?i("div",{staticClass:"vi_over_box"},[i("div",{staticClass:"over_title"},["overtime"===t.room_status?i("span",[t._v("本次面试已结束，感谢您的参与！")]):t._e(),"nostart"===t.room_status?i("span",[t._v("本次面试未开始，敬请期待！")]):t._e()]),i("div",{staticClass:"clear"}),i("div",{staticClass:"over_info_box"},[i("div",{staticClass:"box_line"},[t._v("    候选人："),i("span",{staticClass:"con"},[t._v(t._s(t.fullname))])]),i("div",{staticClass:"box_line"},[t._v(" 面试时间："),i("span",{staticClass:"con"},[t._v(t._s(t.interview_time))])]),i("div",{staticClass:"box_line"},[t._v(" 面试职位："),i("span",{staticClass:"con"},[t._v(t._s(t.jobname))])]),i("div",{staticClass:"box_line"},[t._v(" 公司名称："),i("span",{staticClass:"con"},[t._v(t._s(t.companyname))])])])]):i("div",{staticClass:"vi_media_room"},[i("div",{class:t.isMobile?"stream_box mobile":"stream_box",attrs:{title:"pc",id:"video_grid"}},[t._m(0),!0===t.waiting?i("div",{staticClass:"wait_join"},[t._v(" "+t._s(1===t.utype?"等待求职者进入…":"等待HR进入…")+" ")]):t._e()]),i("div",{staticClass:"stream_handle"},[i("div",{staticClass:"handle_box"},[i("div",{class:!0===t.disableAudio?"handle_audio disable":"handle_audio",on:{click:t.handlerAudio}}),i("div",{staticClass:"handle_call",on:{click:t.hanlderLeaveRoom}}),i("div",{staticClass:"clear"})]),i("div",{staticClass:"info_line i1"},[t._v(" 面试时长："),i("span",{attrs:{id:"J_view_time"}},[t._v(t._s(t.timeHtml))])]),i("div",{staticClass:"info_line i2"},[t._v("面试职位："+t._s(t.jobname))]),1===t.utype?i("div",[i("div",{staticClass:"info_line i3"},[t._v(" "+t._s(t.fullname)+" ("+t._s(t.ageText)+"岁,"+t._s(t.educationText)+","+t._s(t.experienceText)+") ")]),i("a",{staticClass:"to_show",attrs:{href:t.resumeUrl,target:"_blank"}},[t._v("查看简历")]),i("div",{staticClass:"to_warn",on:{click:t.toNotice}},[t._v("发送提醒")])]):i("div",[i("div",{staticClass:"info_line i3"},[t._v("薪资待遇："+t._s(t.wageText))]),i("a",{staticClass:"to_show",attrs:{href:t.jobUrl,target:"_blank"}},[t._v("查看职位")]),i("div",{staticClass:"to_warn",on:{click:t.toNotice}},[t._v("发送提醒")])])])])]),i("input",{attrs:{type:"hidden",id:"userId",value:""}}),i("input",{attrs:{type:"hidden",id:"roomId",value:""}})])},n=[function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"local_stream",attrs:{id:"local_stream"}},[i("div",{staticClass:"video-info",attrs:{id:"local_video_info"}})])}],s=(i("a15b"),i("ac1f"),i("1276"),i("99af"),i("4de4"),i("d3b7"),i("5781")),o=i.n(s),r=i("751a"),c=i("d722"),l={name:"Main",data:function(){return{isChrome:!0,userId:"",roomId:"",client:"",remoteStreamArr:[],localStream:"",config:{appid:"",sig:""},interview_id:0,audio:!0,video:!0,fullname:"",interview_time:"",jobname:"",jobUrl:"",resumeUrl:"",companyname:"",wageText:"",ageText:"",educationText:"",experienceText:"",sexText:"",room_status:"opened",utype:1,error:1,isMobile:!1,waiting:!0,disableAudio:!1,callTimer:"",intDiff:parseInt(0),timeHtml:"00:00"}},created:function(){this.isChrome=navigator.userAgent.indexOf("Chrome")>-1,this.interview_id=this.$route.params.interview_id,this.utype=this.$store.state.LoginType,this.getConfig()},mounted:function(){},methods:{getConfig:function(){var t=this;r["a"].post(c["a"].rtc_config,{interview_id:this.interview_id}).then((function(e){"opened"===e.data.room_status&&(t.roomId=e.data.roomid,t.userId=e.data.userid,t.config.appid=e.data.appid,t.config.sig=e.data.sig,t.fullname=e.data.fullname,t.interview_time=e.data.interview_time,t.jobname=e.data.jobname,t.jobUrl=e.data.joburl,t.resumeUrl=e.data.resumeurl,t.companyname=e.data.companyname,t.wageText=e.data.wage_text,t.ageText=e.data.age_text,t.educationText=e.data.education_text,t.experienceText=e.data.experience_text,t.sexText=e.data.sex_text,t.createClient()),t.room_status=e.data.room_status})).catch((function(){}))},createClient:function(){var t=this.config.appid,e=this.config.sig,i=this.userId;this.client=o.a.createClient({mode:"videoCall",sdkAppId:t,userId:i,userSig:e}),console.warn("链接成功，准备加入房间"),this.subscribeStream(this.client),this.joinRoom(this.client,this.roomId)},joinRoom:function(t,e){var i=this;t.join({roomId:e}).catch((function(t){console.error("进房失败 "+t)})).then((function(){console.warn("进房成功，准备创建本地流"),i.createStream(i.userId),i.playStream(i.client),i.handleEvents()}))},createStream:function(t){var e=this,i=o.a.createStream({userId:t,audio:this.audio,video:this.video,facingMode:"user"});i.setVideoProfile("720p"),this.localStream=i,i.initialize().catch((function(t){console.error("初始化本地流失败 "+t)})).then((function(){console.warn("初始化本地流成功，准备发布本地流"),i.play("local_stream"),e.publishStream(i,e.client)}))},publishStream:function(t,e){e.publish(t).catch((function(t){console.error("本地流发布失败 "+t)})).then((function(){console.warn("本地流发布成功")}))},subscribeStream:function(t){t.on("stream-added",(function(e){var i=e.stream;console.warn("远端流增加: "+i.getId()),t.subscribe(i)}))},playStream:function(t){var e=this;t.on("stream-subscribed",(function(t){var i=t.stream,a=i.getId();e.remoteStreamArr.push(i),console.warn("远端流订阅成功："+a),i.play("video_grid"),e.waiting=!1,e.timerJoin(e.intDiff),e.isMobile&&setTimeout((function(){document.getElementById("player_"+a).setAttribute("style","width: 375px; height: 100%; background: #50555c; border-radius: 0; margin: 0 auto;")}),100)}))},handleEvents:function(){var t=this;this.client.on("error",(function(t){console.error(t)})),this.client.on("client-banned",(function(t){console.error("客户端被禁言 "+t)})),this.client.on("peer-join",(function(e){var i=e.userId;console.warn("远端用户进房 - "+i),t.isMobile="mobile"===i.split("spl")[1]})),this.client.on("peer-leave",(function(e){var i=e.userId;console.warn(i+" 用户退出房间"),t.leaveRoom(),t.waiting=!0})),this.client.on("stream-removed",(function(e){var i=e.stream,a=i.getId();i.stop(),console.warn("stream-removed ID: ".concat(a,"  type: ").concat(i.getType())),console.warn("远端流删除 - "+a),t.remoteStreamArr=t.remoteStreamArr.filter((function(t){return t.getId()!==a})),t.waiting=!0,clearInterval(t.callTimer),t.intDiff=parseInt(0),t.timeHtml="00:00"})),this.client.on("stream-updated",(function(e){var i=e.stream;console.log("type: "+i.getType()+" stream-updated hasAudio: "+i.hasAudio()+" hasVideo: "+i.hasVideo()),console.warn("远端流更新！"),t.waiting=!1}))},leaveRoom:function(){var t=this;this.client&&this.client.leave().then((function(){t.localStream.stop(),t.localStream.close(),t.localStream=null})).catch((function(t){console.error("退房失败 "+t)}))},handlerAudio:function(){if(!this.client)return!1;!0===this.disableAudio?this.localStream.muteAudio():this.localStream.unmuteAudio(),this.disableAudio=!this.disableAudio},hanlderLeaveRoom:function(){var t=this;this.client?this.$confirm("挂断后将断开本次视频面试，再次开启请刷新当前页面或视频面试列表进入房间","系统提示",{confirmButtonText:"立即挂断",cancelButtonText:"取消",type:"warning"}).then((function(){t.leaveRoom()})).catch((function(){})):this.$notify("请先加入房间！")},timerJoin:function(t){var e=this;this.callTimer=window.setInterval((function(){var i=0,a=0;t>0&&(i=Math.floor(t/60),a=Math.floor(t)-60*i),i<=9&&(i="0"+i),a<=9&&(a="0"+a),e.timeHtml=i+":"+a,t++}),1e3)},toNotice:function(){var t=this,e=1===this.utype?"求职者":"企业",i=1===this.utype?c["a"].company_interview_video_notice:c["a"].personal_manage_interview_video_notice;this.$confirm("确定提醒"+e+"吗？","系统提示",{type:"warning"}).then((function(){r["a"].post(i,{id:t.interview_id}).then((function(){t.$message({type:"success",message:"提醒成功"})})).catch((function(){}))})).catch((function(){}))}}},d=l,u=(i("f14c3"),i("2877")),m=Object(u["a"])(d,a,n,!1,null,"57886d21",null);e["default"]=m.exports},b7b7:function(t,e,i){},f14c3:function(t,e,i){"use strict";i("b7b7")}}]);