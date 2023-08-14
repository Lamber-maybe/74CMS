<template>
  <div class="vi_media_room">
    <div class="stream_box" title="mobile" id="video_grid">
      <!-- 等待加入 -->
      <div class="wait_join" v-if="waiting===true">正在等待对方接入，请稍候</div>
      <!-- 等待加入 end-->
      <!-- 本地视频 -->
      <div class="local_stream" title="mobile" id="local_stream">
        <div id="local_video_info" class="video-info"></div>
      </div>
      <!-- 本地视频 end-->
      <div class="handle_box">
        <div class="handle_audio" :class="disableAudio===true?'disable':''" @click="handlerAudio"></div>
        <div class="handle_call" @click="hanlderLeaveRoom"></div>
        <div class="clear"></div>
      </div>
    </div>
    <div class="stream_handle">
      <div class="sh_content">
        <div class="sh_name substring font13" v-if="utype==1">{{fullname}}</div>
        <div class="sh_name substring font13" v-else>{{jobname}}</div>
        <div class="sh_wage " v-if="utype==1">{{sexText}} | {{ageText}}岁 | {{educationText}} | {{experienceText}}经验</div>
        <div class="sh_wage " v-else>{{wageText}}</div>
        <div class="sh_tip" @click="$emit('toNotice')">发送提醒</div>
      </div>
    </div>
  </div>

</template>

<script>
let CTRTC = null
// import TRTC from 'trtc-js-sdk'
let isSpider = new RegExp('^(Baiduspider|YisouSpider|Sogou|Googlebot|Sosospider|bingbot|360Spider)').test(navigator.userAgent)
if (!isSpider) {
  Promise.all([
    import('trtc-js-sdk')
  ]).then(([
    TRTC
  ]) => {
    CTRTC = TRTC
  })
} else {
}
export default {
  name: 'Trtc',
  data () {
    return {
      config: {
        appid: '',
        sig: ''
      },
      client: '', // 客户端服务
      remoteStreamArr: [], // 远端流
      localStream: '', // 本地流,
      disableAudio: false,
      waiting: true,
      message: '123'
    }
  },
  props: [
    'video',
    'audio',
    'userId', // 用户id
    'roomId', // 房间号(两端相同时才能加入房间)
    'appId',
    'sig',
    'utype',
    'jobname',
    'wageText',
    'fullname',
    'ageText',
    'educationText',
    'experienceText',
    'sexText'
  ],
  created () {
    this.config.appid = this.appId
    this.config.sig = this.sig
    this.createClient()
  },
  methods: {
    // 创建链接
    createClient () {
      const sdkAppId = this.config.appid
      const userSig = this.config.sig
      const userId = this.userId
      this.client = CTRTC.createClient({
        mode: 'videoCall',
        sdkAppId,
        userId,
        userSig
      })
      this.subscribeStream(this.client) // 订阅远端音视频流
      this.joinRoom(this.client, this.roomId) // 初始化后才能加入房间
    },
    // 加入房间
    joinRoom (client, roomId) {
      client
        .join({ roomId })
        .catch(error => {
          console.error('进房失败 ' + error)
        })
        .then(() => {
          this.message += '进房成功'
          console.log('进房成功')
          this.createStream(this.userId) // 创建本地流
          this.playStream(this.client) // 播放远端流
          this.handleEvents() // 监听远端流退房删除
        })
    },
    // 创建本地流
    createStream (userId) {
      const localStream = CTRTC.createStream({
        userId,
        audio: this.audio,
        video: this.video,
        facingMode: 'user'
      })
      localStream.setVideoProfile('720p')
      this.localStream = localStream
      // 初始本地化流
      localStream
        .initialize()
        .catch(error => {
          console.error('初始化本地流失败 ' + error)
        })
        .then(() => {
          this.message += '初始化本地流成功'
          console.log('初始化本地流成功')
          localStream.play('local_stream') // 创建好后才能播放 本地流播放local_stream
          this.publishStream(localStream, this.client) // 发布本地流
        })
    },
    // 发布本地音视频流
    publishStream (localStream, client) {
      client
        .publish(localStream)
        .catch(error => {
          console.error('本地流发布失败 ' + error)
        })
        .then(() => {
          this.message += '本地流发布成功'
          console.log('本地流发布成功')
        })
    },
    // 订阅远端流--加入房间之前
    subscribeStream (client) {
      client.on('stream-added', event => {
        const remoteStream = event.stream
        this.message += '远端流增加' + remoteStream.getId()
        console.log('远端流增加: ' + remoteStream.getId())
        // 订阅远端流
        client.subscribe(remoteStream)
      })
    },
    // 播放远端流 远端流订阅成功事件
    playStream (client) {
      client.on('stream-subscribed', event => {
        const remoteStream = event.stream
        this.message += '远端流订阅成功：' + remoteStream.getId()
        console.log('远端流订阅成功：' + remoteStream.getId())
        remoteStream.play('video_grid')
        this.waiting = false
        // 做了dom操作 需要使用$nextTick(),否则找不到创建的标签无法进行播放
        // this.$nextTick(() => {
        //   remoteStream.play('video_grid') // 播放
        // })
      })
    },
    // 监听通知事件
    handleEvents () {
      this.client.on('error', err => {
        console.error(err)
        // layer.msg('客户端错误：' + err)
      // location.reload();
      })
      // 处理用户被踢事件，通常是因为房间内有同名用户引起，这种问题一般是应用层逻辑错误引起的
      // 应用层请尽量使用不同用户ID进房
      this.client.on('client-banned', err => {
        console.error('client has been banned for ' + err)
        // layer.msg('用户被踢出房间！')
      // location.reload();
      })
      // 远端用户进房通知 - 仅限主动推流用户
      this.client.on('peer-join', evt => {
        const userId = evt.userId
        console.log('远端用户进房 - ' + userId)
      })
      // 监听远端流退房的通知事件
      this.client.on('peer-leave', evt => {
        const userId = evt.userId
        console.log('peer-leave ' + userId)
        this.leaveRoom()
        this.waiting = true
        // this.remoteStreamArr = []
      })
      this.client.on('stream-removed', evt => {
        const remoteStream = evt.stream
        // 关闭远端流内部的音视频播放器
        remoteStream.stop()
        this.leaveRoom()
        this.waiting = true
      })
      // 远程流更新事件
      this.client.on('stream-updated', evt => {
        const remoteStream = evt.stream
        console.log(
          'type: ' +
        remoteStream.getType() +
        ' stream-updated hasAudio: ' +
        remoteStream.hasAudio() +
        ' hasVideo: ' +
        remoteStream.hasVideo()
        )
        console.log('远端流更新！')
        this.waiting = false
        // this.unAllAudio()
      })
    },
    // 退出音视频房间
    leaveRoom () {
      if (!this.client) {
        return
      }
      this.client
        .leave()
        .then(() => {
          this.localStream.stop() // 停止本地流，关闭本地流内部的音视频播放器
          this.localStream.close() // 关闭本地流，释放摄像头和麦克风访问权限
          this.localStream = null
          this.client = ''
        })
        .catch(error => {
          console.error('退房失败 ' + error) // 错误不可恢复，需要刷新页面
        })
    },
    handlerAudio () {
      if (!this.client) {
        return false
      }
      if (this.disableAudio === true) {
        // 解除静音
        this.localStream.muteAudio()
      } else {
        // 静音
        this.localStream.unmuteAudio()
      }
      this.disableAudio = !this.disableAudio
    },
    hanlderLeaveRoom () {
      if (!this.client) {
        this.$notify('请先加入房间！')
        return
      }
      this.$dialog
        .confirm({
          title: '系统提示',
          message:
              '挂断后将断开本次视频面试，再次开启请刷新当前页面或视频面试列表进入房间',
          confirmButtonText: '立即挂断'
        })
        .then(() => {
          this.leaveRoom()
        })
        .catch(() => {
          // on cancel
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.vi_media_room { position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: #202020; z-index: 1; padding: 53px 0 72.5px;
  .stream_box { position: relative; width: 100%; height: 100%;
    .wait_join {font-size:14px; position: absolute; left: 50%; top: 203.5px; transform: translate(-50%, 0); -webkit-transform: translate(-50%, 0); padding-top: 87.5px; text-align: center; background: url("../assets/images/vi/8.png") center 0 no-repeat; color: #ffffff; z-index: 3; background-size: 70px; }
    .local_stream { position: absolute; right: 10px; top: 10px; z-index: 3; width: 120px; height: 160px; background: #323232; }
    .player_box { width: 100%; height: 100%; background: #202020; }
    .handle_box {width: 150px; height: 45px; position: absolute; left: 50%; bottom: 35px; transform: translate(-50%, 0); -webkit-transform: translate(-50%, 0); z-index: 3;
      .handle_audio { float: left; width: 45px; height: 45px; background: url("../assets/images/vi/3.png") 0 0 no-repeat; background-size: 45px;
        &.disable { background: url("../assets/images/vi/7.png") 0 0 no-repeat; background-size: 45px; }
      }
      .handle_call { float: right; width: 45px; height: 45px; background: url("../assets/images/vi/4.png") 0 0 no-repeat; background-size: 45px; }
    }
  }
  .stream_handle { position: fixed; left: 0; bottom: 0; background: #fff; z-index: 2; width: 100%; height: 72.5px;
    .sh_content { position: relative; width: 100%; height: 100%;
      .sh_name {font-size:16px; position: absolute; left:15px; top:15px; width: 230px; color: #333; }
      .sh_wage {font-size:14px; position: absolute; left:15px; bottom:15px; color: #ff6600; }
      .sh_tip {font-size:14px; position: absolute; right:15px; top: 50%; transform: translate(0, -50%); -webkit-transform: translate(0, -50%); padding: 6.5px 17.5px 6.5px 30px; border: 1px solid #1787fb; color: #1787fb; background: url("../assets/images/vi/5.png") 15px center no-repeat; background-size: 12px 13px; border-radius: 36px;
        &.btn_disabled { background: #ebebeb url("../assets/images/vi/9.png") 15px center no-repeat; background-size: 12px 13px; border: 1px #cbcecf solid; color: #a0a0a0; cursor: default; }
      }
    }
  }
}
</style>
