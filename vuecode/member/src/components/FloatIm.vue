<template>

  <div v-if="show" class="livechat-ico animated" :class="$store.state.imUnreaded?'point':''" v-on:mouseenter="visible" @mouseleave="invisible" @click="locationTo">
    <img class="ico" src="../assets/images/im/message.png">
    <div class="livechat-hint rd-notice-tooltip rd-notice-type-success rd-notice-position-left single-line" :class="showHint?'show_hint':'hide_hint'">
      <div class="rd-notice-content">{{showText}}</div>
    </div>
    <div class="animated-circles" :class="animated?'animated':''">
      <div class="circle c-1"></div>
      <div class="circle c-2"></div>
      <div class="circle c-3"></div>
    </div>
  </div>
</template>

<script>
import { isJSON } from '@/utils/index'
import http from "@/utils/http";
import api from "@/api";
  export default{
    data(){
      return{
        showHint:false,
        animated:true,
        showText:'我的职聊',
        show:false,
        target_chat:''
      }
    },
    mounted() {
      this.$store.state.imUnreaded = false
      this.imWindowGlobal()
    },
    destroyed() {
      window.ws.close(); // 离开路由之后断开websocket连接
    },
    methods: {
      setIntTime(){
        setInterval(()=>{
          if(this.animated===true){
            this.animated = false
          }else{
            this.animated = true
          }
        },3000);
      },
      imWindowGlobal(){
        http
          .get(api.im_window_global, {})
          .then(res => {
            if(res.code==200){
              //检测通过
              if(res.data.next==''){
                this.show = true
                this.setIntTime()
                this.initWebSocket()
              }
            }else{
              console.log(res.message)
            }
          })
          .catch(() => {});
      },
      initWebSocket() {
          http
            .get(api.im_gettoken, {})
            .then(res => {
              this.$store.state.imToken = res.data
              window.ws = new WebSocket('wss://imserv.v2.74cms.com');
              window.ws.onopen = this.websocketonopen;
              window.ws.onmessage = this.websocketonmessage;
              window.ws.onerror = this.websocketonerror;
              window.ws.onclose = this.websocketclose;
            }).catch(()=>{})
        
      },
      websocketonopen() {
        var msgObj = {
          controller: "Connect",
          action: "index",
          args: {
            token:this.$store.state.imToken
          }
        };
        var msgStr = JSON.stringify(msgObj);
        window.ws.send(msgStr);
        this.getChatList()
      },
      websocketonerror() {
        console.log('client：连接websocket失败，请刷新页面重试')
      },
      websocketonmessage(e) {
        if (isJSON(e.data)) {
          var data = JSON.parse(e.data);
          if(data.error===undefined){
            this.target_chat = data.chatid
            this.showText = '您有新消息！'
            this.$store.state.imUnreaded = true
          }
        }else{
          console.log('client：'+e.data);
        }
      },
      websocketclose() {
        console.log("client：关闭连接");
      },
      getChatList() {
        this.$store.state.imUnreaded = false
        http
          .post(api.im_chat_list, {token:this.$store.state.imToken})
          .then(res => {
            for(var i=0;i<res.data.items.length;i++){
              if(res.data.items[i].new>0){
                this.target_chat = res.data.items[i].chat_id
                this.showText = '您有新消息！'
                this.$store.state.imUnreaded = true
              }
            }
          })
          .catch(() => {});
      },
      locationTo(){
        if(this.$store.state.LoginType==1){
          this.$router.push('/company/im?chat='+this.target_chat)
        }else{
          this.$router.push('/personal/im?chat='+this.target_chat)
        }
      },
      visible(){
        this.showHint = true;
      },
      invisible(){
        this.showHint = false;
      }
    }
    
  }
</script>


<style lang="scss" scoped>
@keyframes scaleToggleOne { 
0% {
transform:scale(1);
-webkit-transform:scale(1)
}
50% {
transform:scale(2);
-webkit-transform:scale(2)
}
100% {
transform:scale(1);
-webkit-transform:scale(1)
}
}
@keyframes scaleToggleTwo { 
0% {
transform:scale(1);
-webkit-transform:scale(1)
}
20% {
transform:scale(1);
-webkit-transform:scale(1)
}
60% {
transform:scale(2);
-webkit-transform:scale(2)
}
100% {
transform:scale(1);
-webkit-transform:scale(1)
}
}
@keyframes scaleToggleThree { 
0% {
transform:scale(1);
-webkit-transform:scale(1)
}
33% {
transform:scale(1);
-webkit-transform:scale(1)
}
66% {
transform:scale(2);
-webkit-transform:scale(2)
}
100% {
transform:scale(1);
-webkit-transform:scale(1)
}
}
.animated { -webkit-animation-duration: .5s; animation-duration: .5s; -webkit-animation-fill-mode: both; animation-fill-mode: both }
.livechat-ico { width: 60px; height: 60px; border-radius: 50%; position: fixed; bottom: 0; right: 40px; opacity: 0; -webkit-box-shadow: 0 5px 10px 0 rgba(35,50,56,.3); box-shadow: 0 5px 10px 0 rgba(35,50,56,.3); z-index: 700; transform: translateY(0); -webkit-transform: translateY(0); -ms-transform: translateY(0); cursor: pointer; -webkit-transition: all 1s cubic-bezier(.86, 0, .07, 1); transition: all 1s cubic-bezier(.86, 0, .07, 1) }
.livechat-ico:focus { outline: 0 }
.livechat-ico.animated { opacity: 1; transform: translateY(-40px); -webkit-transform: translateY(-40px); -ms-transform: translateY(-40px) }
.livechat-ico.point:after { content: ''; width: 12px; height: 12px; border-radius: 50%; background-image: linear-gradient(to bottom, #ff420a, #ff420a); position: absolute; right: 1px; top: 1px; z-index: 50 }
.livechat-ico .ico { position: absolute; top: 0; left: 0; width: 100%; height: auto; z-index: 50 }
.livechat-ico .animated-circles .circle { background: rgba(25,188,80,.25); width: 60px; height: 60px; border-radius: 50%; position: absolute; z-index: 49; transform: scale(1); -webkit-transform: scale(1) }
.livechat-ico .animated-circles.animated .c-1 { animation: 2s scaleToggleOne cubic-bezier(.25, .46, .45, .94) forwards }
.livechat-ico .animated-circles.animated .c-2 { animation: 2.5s scaleToggleTwo cubic-bezier(.25, .46, .45, .94) forwards }
.livechat-ico .animated-circles.animated .c-3 { animation: 3s scaleToggleThree cubic-bezier(.25, .46, .45, .94) forwards }
.livechat-ico.animation-stopped .circle { opacity: 0!important }
.livechat-ico.animation-stopped .circle { opacity: 0!important }
.livechat-ico .livechat-hint { position: absolute; right: 40px; top: 50%; margin-top: -20px; opacity: 0; z-index: 0; -webkit-transition: all .3s cubic-bezier(.86, 0, .07, 1); transition: all .3s cubic-bezier(.86, 0, .07, 1) }
.livechat-ico .livechat-hint.show_hint { -webkit-transform: translateX(-40px); transform: translateX(-40px); opacity: 1 }
.livechat-ico .livechat-hint.hide_hint { opacity: 0; -webkit-transform: translateX(0); transform: translateX(0) }
.livechat-ico .livechat-hint.rd-notice-tooltip { max-width: 1296px!important }
.livechat-ico .livechat-hint.rd-notice-tooltip .rd-notice-content { width: auto; overflow: hidden; text-overflow: ellipsis }
@media only screen and (max-width:1599px) {
.livechat-ico .livechat-hint.rd-notice-tooltip { max-width: 1060px!important }
}
@media only screen and (max-width:1309px) {
.livechat-ico .livechat-hint.rd-notice-tooltip { max-width: 984px!important }
}
@media only screen and (max-width:1124px) {
.livechat-ico .livechat-hint.rd-notice-tooltip { max-width: 600px!important }
}
.rd-notice-tooltip { -webkit-box-shadow: 0 2px 2px rgba(0,0,0,.2); box-shadow: 0 2px 2px rgba(0,0,0,.2); font-size: 14px; border-radius: 3px; line-height: 1.25; position: absolute; z-index: 65; max-width: 350px; opacity: 1 }
.rd-notice-tooltip:after { position: absolute; display: block; content: ''; height: 20px; width: 20px; -webkit-box-shadow: none; box-shadow: none; -webkit-transform: rotate(-45deg); -moz-transform: rotate(-45deg); -ms-transform: rotate(-45deg); -o-transform: rotate(-45deg); transform: rotate(-45deg); -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; z-index: 50 }
.rd-notice-tooltip .rd-notice-content { background: 0; border-radius: 3px; width: 100%; color: #fff; position: relative; z-index: 60; padding: 20px; font-weight: 400; line-height: 1.45 }
.rd-notice-type-success { background-color: #fc9326; -webkit-box-shadow: 0 5px 10px 0 rgba(38,199,252,.2); box-shadow: 0 5px 10px 0 rgba(38,199,252,.2) }
.rd-notice-type-success .rd-notice-content { background-color: #fc9326 }
.rd-notice-type-success:after { background-color: #fc9326; -webkit-box-shadow: 0 5px 10px 0 rgba(38,199,252,.2); box-shadow: 0 5px 10px 0 rgba(38,199,252,.2) }
.rd-notice-position-left { margin-left: -20px }
.rd-notice-position-left:after { right: -6px; top: 50%; margin-top: -10px }
.rd-notice-tooltip.single-line .rd-notice-content { height: 40px; padding: 0 20px; line-height: 40px; white-space: nowrap }
</style>
