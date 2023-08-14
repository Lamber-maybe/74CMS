<template>
  <div>
    <div class="vi_head">
      <div class="head_content">
        <a href="">
          <img class="hc_logo" :src="$store.state.config.logo" alt="logo">
        </a>
        <div class="hc_logo_vi"></div>
        <a :href="$store.state.config.link_url_web.index" class="hc_back_home">返回网站</a>
        <a class="hc_contact">联系电话：{{$store.state.config.contact_tel}}</a>
        <router-link :to="'/video_test/' + interview_id" class="hc_detect">设备检测</router-link>
      </div>
    </div>
    <div class="vi_container">
      <div class="tip" v-show="!isChrome">
        为提高视频面试效率，建议您使用谷歌内核浏览器并提前检测您的设备是否能够正常连通
      </div>
      <div class="vi_over_box" v-if="room_status !== 'opened'">
        <div class="over_title">
          <span v-if="room_status === 'overtime'">本次面试已结束，感谢您的参与！</span>
          <span v-if="room_status === 'nostart'">本次面试未开始，敬请期待！</span>
        </div>
        <div class="clear"></div>
        <div class="over_info_box">
          <div class="box_line">
            &nbsp;&nbsp;&nbsp;候选人：<span class="con">{{fullname}}</span>
          </div>
          <div class="box_line">
            面试时间：<span class="con">{{interview_time}}</span>
          </div>
          <div class="box_line">
            面试职位：<span class="con">{{jobname}}</span>
          </div>
          <div class="box_line">
            公司名称：<span class="con">{{companyname}}</span>
          </div>
        </div>
      </div>
      <div class="vi_media_room" v-else>
        <div :class="isMobile ? 'stream_box mobile' : 'stream_box'" title="pc" id="video_grid">
          <!-- 对方视频 -->
          <!-- <div class="player_box"></div> -->
          <!-- 对方视频 end-->
          <!-- 本地视频 -->
          <div class="local_stream" id="local_stream">
            <div id="local_video_info" class="video-info"></div>
          </div>
          <!-- 本地视频 end-->
          <!-- 等待加入 -->
          <div class="wait_join" v-if="waiting === true">
            {{ utype === 1 ? '等待求职者进入…' : '等待HR进入…' }}
          </div>
          <!-- 等待加入 end-->
        </div>
        <div class="stream_handle">
          <div class="handle_box">
            <div :class="disableAudio===true ? 'handle_audio disable' : 'handle_audio'" @click="handlerAudio"></div>
            <div class="handle_call" @click="hanlderLeaveRoom"></div>
            <div class="clear"></div>
          </div>
          <div class="info_line i1">
            面试时长：<span id="J_view_time">{{timeHtml}}</span>
          </div>
          <div class="info_line i2">面试职位：{{jobname}}</div>
          <div v-if="utype === 1">
            <div class="info_line i3">
              {{fullname}} ({{ageText}}岁,{{educationText}},{{experienceText}})
            </div>
            <a :href="resumeUrl" target="_blank" class="to_show">查看简历</a>
            <div class="to_warn" @click="toNotice">发送提醒</div>
          </div>
          <div v-else>
            <div class="info_line i3">薪资待遇：{{wageText}}</div>
            <a :href="jobUrl" target="_blank" class="to_show">查看职位</a>
            <div class="to_warn" @click="toNotice">发送提醒</div>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" id="userId" value="" />
    <input type="hidden" id="roomId" value="" />
  </div>
</template>

<script>
import TRTC from 'trtc-js-sdk'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'Main',
  data(){
    return {
      isChrome: true,
      userId: '',//用户id --可更改
      roomId: '',//房间号--加入相同房间才能聊
      client: '', // 客户端服务
      remoteStreamArr: [], // 远端流
      localStream: '', // 本地流,
      config: {
        appid: '',
        sig: ''
      },
      interview_id: 0,
      audio: true,
      video: true,
      fullname: '',
      interview_time: '',
      jobname: '',
      jobUrl: '',
      resumeUrl: '',
      companyname: '',
      wageText: '',
      ageText: '',
      educationText: '',
      experienceText: '',
      sexText: '',
      room_status: 'opened',
      utype: 1,
      error: 1,
      isMobile: false,
      waiting: true,
      disableAudio: false,
      callTimer: '',
      intDiff: parseInt(0),
      timeHtml: '00:00'
    }
  },
  created(){
    this.isChrome = navigator.userAgent.indexOf("Chrome") > -1; // 是否是谷歌浏览器
    this.interview_id = this.$route.params.interview_id
    this.utype = this.$store.state.LoginType
    this.getConfig()
  },
  mounted() {
  },
  methods:{
    getConfig () {
      http
        .post(api.rtc_config, {interview_id: this.interview_id})
        .then(res => {
          if (res.data.room_status === 'opened') {
            this.roomId = res.data.roomid
            this.userId = res.data.userid
            this.config.appid = res.data.appid
            this.config.sig = res.data.sig
            this.fullname = res.data.fullname
            this.interview_time = res.data.interview_time
            this.jobname = res.data.jobname
            this.jobUrl = res.data.joburl
            this.resumeUrl = res.data.resumeurl
            this.companyname = res.data.companyname
            this.wageText = res.data.wage_text
            this.ageText = res.data.age_text
            this.educationText = res.data.education_text
            this.experienceText = res.data.experience_text
            this.sexText = res.data.sex_text
            this.createClient()
          }
          this.room_status = res.data.room_status
        })
        .catch(() => {})
    },
    // 创建链接
    createClient () {
      const sdkAppId = this.config.appid
      const userSig = this.config.sig
      const userId = this.userId
      this.client = TRTC.createClient({
        mode: 'videoCall',
        sdkAppId,
        userId,
        userSig
      })
      console.warn('链接成功，准备加入房间')
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
          console.warn('进房成功，准备创建本地流')
          this.createStream(this.userId) // 创建本地流
          this.playStream(this.client) // 播放远端流
          this.handleEvents() // 监听远端流退房删除
        })
    },
    // 创建本地流
    createStream (userId) {
      const localStream = TRTC.createStream({
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
          console.warn('初始化本地流成功，准备发布本地流')
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
          console.warn('本地流发布成功')
        })
    },
    // 订阅远端流--加入房间之前
    subscribeStream (client) {
      client.on('stream-added', event => {
        const remoteStream = event.stream
        console.warn('远端流增加: ' + remoteStream.getId())
        // 订阅远端流
        client.subscribe(remoteStream)
      })
    },
    // 播放远端流 远端流订阅成功事件
    playStream (client) {
      client.on('stream-subscribed', event => {
        const remoteStream = event.stream
        const id = remoteStream.getId()
        this.remoteStreamArr.push(remoteStream)
        console.warn('远端流订阅成功：' + id)
        remoteStream.play('video_grid')
        this.waiting = false
        this.timerJoin(this.intDiff)
        // 如果远端流是触屏，动态改变视频显示比例
        if (this.isMobile) {
          setTimeout(function() {
            document.getElementById('player_' + id).setAttribute('style', 'width: 375px; height: 100%; background: #50555c; border-radius: 0; margin: 0 auto;')
          }, 100)
        }
      })
    },
    // 监听通知事件
    handleEvents () {
      this.client.on('error', err => {
        console.error(err)
      })
      // 处理用户被踢事件，通常是因为房间内有同名用户引起，这种问题一般是应用层逻辑错误引起的
      // 应用层请尽量使用不同用户ID进房
      this.client.on('client-banned', err => {
        console.error('客户端被禁言 ' + err)
      })
      // 远端用户进房通知 - 仅限主动推流用户
      this.client.on('peer-join', evt => {
        const userId = evt.userId
        console.warn('远端用户进房 - ' + userId)
        // 处理来自触屏的视频比例
        this.isMobile = userId.split('spl')[1] === 'mobile'
      })
      // 监听远端流退房的通知事件
      this.client.on('peer-leave', evt => {
        const userId = evt.userId
        console.warn(userId + ' 用户退出房间')
        this.leaveRoom()
        this.waiting = true
      })
      this.client.on('stream-removed', evt => {
        const remoteStream = evt.stream
        const id = remoteStream.getId()
        // 关闭远端流内部的音视频播放器
        remoteStream.stop()
        // this.leaveRoom()
        console.warn(`stream-removed ID: ${id}  type: ${remoteStream.getType()}`);
        console.warn('远端流删除 - ' + id);
        this.remoteStreamArr = this.remoteStreamArr.filter(stream => {
          return stream.getId() !== id;
        });
        this.waiting = true
        clearInterval(this.callTimer)
        this.intDiff = parseInt(0)
        this.timeHtml = '00:00'
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
        console.warn('远端流更新！')
        this.waiting = false
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
      this.$confirm('挂断后将断开本次视频面试，再次开启请刷新当前页面或视频面试列表进入房间', '系统提示', {
        confirmButtonText: '立即挂断',
        cancelButtonText: '取消',
        type: 'warning',
        }).then(() => {
          this.leaveRoom()
        }).catch(() => {
      })
    },
    timerJoin (intDiff) {
      let that = this
      this.callTimer = window.setInterval(function() {
        let minute = 0, second = 0; //时间默认值
        if (intDiff > 0) {
          minute = Math.floor(intDiff / 60)
          second = Math.floor(intDiff) - minute * 60
        }
        if (minute <= 9) minute = "0" + minute
        if (second <= 9) second = "0" + second
        that.timeHtml = minute + ":" + second
        intDiff++;
      }, 1000);
    },
    // 发送提醒
    toNotice () {
      let noticeText = this.utype === 1 ? '求职者' : '企业'
      let noticeUrl = this.utype === 1 ? api.company_interview_video_notice : api.personal_manage_interview_video_notice
      this.$confirm('确定提醒' + noticeText + '吗？', '系统提示', {type: 'warning'})
        .then(() => {
          http
            .post(noticeUrl, { id: this.interview_id })
            .then(() => {
              this.$message({
                type: 'success',
                message: '提醒成功'
              })
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    }
  }
}
</script>

<style lang="scss" scoped>
body { background: #ffffff; }

.vi_head { width: 100%; height: 93px; border-bottom: 1px solid #e5e5e5; }
.vi_head .head_content { position: relative; width: 1200px; height: 100%; margin: 0 auto; }
.vi_head .head_content .hc_logo { position: absolute; left: 0; top: 50%; transform: translate(0, -50%); -webkit-transform: translate(0, -50%); max-height: 70px; max-width: 238px; border: 0; }
.vi_head .head_content .hc_logo_vi { position: absolute; left: 175px; top: 50%; transform: translate(0, -50%); -webkit-transform: translate(0, -50%); width: 135px; height: 29px; background: url("../../assets/images/vi/vi_logo.png") 0 0 no-repeat; }
.vi_head .head_content .hc_back_home { position: absolute; right: 0; top: 50%; transform: translate(0, -50%); -webkit-transform: translate(0, -50%); display: block; text-decoration: none; width: 90px; line-height: 26px; border-radius: 26px; border: 1px solid #1787fb; background: #deeeff; text-align: center; color: #1787fb; font-size: 14px; transition: all .3s; -webkit-transition: all .3s; }
.vi_head .head_content .hc_back_home:hover { text-decoration: none; background: #1787fb; color: #ffffff; }
.vi_head .head_content .hc_contact { position: absolute; right: 128px; top: 50%; transform: translate(0, -50%); -webkit-transform: translate(0, -50%); display: block; text-decoration: none; line-height: 28px; padding-left: 19px; color: #1787fb; font-size: 15px; background: url("../../assets/images/vi/2.png") 0 5px no-repeat; }
.vi_head .head_content .hc_detect { position: absolute; right: 353px; top: 50%; transform: translate(0, -50%); -webkit-transform: translate(0, -50%); display: block; text-decoration: none; line-height: 28px; padding-left: 19px; color: #1787fb; font-size: 15px; background: url("../../assets/images/vi/1.png") 0 5px no-repeat; }

.vi_container { padding-top: 30px; }

.device_detect_group { width: 520px; margin: 0 auto; padding-top: 70px; }
.device_detect_group .group_title { width: 100%; height: 36px; line-height: 36px; font-size: 18px; color: #333333; text-align: center; }
.device_detect_group .detect_list { padding-top: 50px; }
.device_detect_group .detect_list .list_line { position: relative; margin-bottom: 36px; }
.device_detect_group .detect_list .list_line:last-child { margin-bottom: 47px; }
.device_detect_group .detect_list .list_line .line_title { padding-left: 24px; height: 18px; line-height: 18px; font-size: 14px; color: #666666; margin-bottom: 10px; }
.device_detect_group .detect_list .list_line .line_title.t1 { background: url("../../assets/images/vi/9.png") 0 center no-repeat; }
.device_detect_group .detect_list .list_line .line_title.t2 { background: url("../../assets/images/vi/10.png") 0 center no-repeat; }
.device_detect_group .detect_list .list_line .line_title.t3 { background: url("../../assets/images/vi/11.png") 0 center no-repeat; }
.device_detect_group .detect_list .list_line .line_title.t4 { background: url("../../assets/images/vi/17.png") 0 center no-repeat; }
.device_detect_group .detect_list .list_line .line_li { width: 330px; height: 48px; line-height: 48px; margin-left: 4px; padding: 0 42px 0 18px; position: relative; color: #333; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; cursor: pointer; font-size: 14px; border-bottom: 1px solid #e5e5e5; }
.device_detect_group .detect_list .list_line .line_li::after { position: absolute; right: 0; top: 0; width: 24px; height: 100%; background: url("../../assets/images/vi/12.png") center no-repeat; content: ' '; z-index: 1; transition: transform .3s; transition: transform .3s,-webkit-transform .3s; }
.device_detect_group .detect_list .list_line .line_li.pull::after { -webkit-transform: rotate(180deg); transform: rotate(180deg); }
.device_detect_group .detect_list .list_line .line_li.disable { background-color: #f5f7fa; color: #c5c7c9; cursor: not-allowed; }
.device_detect_group .detect_list .list_line .line_li.not { cursor: default; }
.device_detect_group .detect_list .list_line .line_li.not::after { display: none; }
.device_detect_group .detect_list .list_line .detect_result { position: absolute; right: 0; bottom: 0; z-index: 1; width: 86px; height: 60px; background: #000; }
.device_detect_group .detect_list .list_line .detect_result .volume_bar { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%); width: 70px; height: 6px; border-radius: 6px; background: #ebeef5; z-index: 2; }
.device_detect_group .detect_list .list_line .detect_result .volume_bar .volume_contetn { position: relative; width: 100%; height: 100%; }
.device_detect_group .detect_list .list_line .detect_result .volume_bar .volume_contetn .volume_auto { position: absolute; left: 0; top: 0; width: auto; height: 6px; border-radius: 6px; transition: width .6s ease; background: #19be6b; z-index: 3; -webkit-transition: width .6s ease; }
.device_detect_group .detect_list .list_line .detect_result .test_play { width: 100%; height: 27px; font-size: 12px; color: #fff; text-align: center; padding-top: 33px; background: url("../../assets/images/vi/test_play.png") center 15px no-repeat; cursor: pointer; }
.device_detect_group .detect_list .list_line .detect_result .test_playing { width: 100%; height: 25px; font-size: 12px; color: #fff; text-align: center; padding-top: 35px; background: url("../../assets/images/vi/test_playing.png") center 13px no-repeat; cursor: pointer; }
.device_detect_group .detect_list .list_line .detect_result .audio { display: none; }
.device_detect_group .detect_list .list_line .detect_result .network_check { width: 100%; height: 49px; font-size: 12px; color: #fff; text-align: center; padding-top: 11px; }
.device_detect_group .detect_list .list_line .detect_result .network_check .check_ico { display: inline-block; width: 18px; height: 18px; margin-bottom: 3px; animation: checking-data 1s linear infinite; background: url("../../assets/images/vi/network_check.png") 0 0 no-repeat; background-size: 18px; }
.device_detect_group .detect_list .list_line .detect_result .network_check .check_ico.checked { background: url("../../assets/images/vi/network.png") 0 0 no-repeat; background-size: 18px; animation: none; }
.device_detect_group .jump_room { text-decoration: none; width: 110px; height: 36px; line-height: 36px; border-radius: 38px; border: 1px solid #1787fb; background: #deeeff; text-align: center; color: #1787fb; font-size: 16px; transition: all .3s; -webkit-transition: all .3s; display: block; margin: 0 auto; }
.device_detect_group .jump_room:hover { text-decoration: none; background: #1787fb; color: #ffffff; }

@-webkit-keyframes checking-data { 0% { -webkit-transform: rotate(0deg); transform: rotate(0deg); }
  to { -webkit-transform: rotate(1turn); transform: rotate(1turn); } }
@keyframes checking-data { 0% { -webkit-transform: rotate(0deg); transform: rotate(0deg); }
  to { -webkit-transform: rotate(1turn); transform: rotate(1turn); } }
.sm_container { z-index: 2; }

.sm_results > li { min-width: 200px; max-width: 390px; }
video{width: 200px;}
.vi_media_room { width: 1200px; margin: 0 auto; }
.vi_media_room .stream_box { position: relative; width: 1200px; height: 600px; background: #50555c; }
.vi_media_room .stream_box .player_box { width: 100%; height: 100%; background: #50555c; border-radius: 3px 3px 0 0; }
.vi_media_room .stream_box.mobile { background: #50555c; }
.vi_media_room .stream_box.mobile .player_box { width: 375px; height: 100%; background: #50555c; border-radius: 0; margin: 0 auto; }
.vi_media_room .stream_box .local_stream { position: absolute; right: 0; bottom: 0; z-index: 1; width: 200px; height: 150px; border: 1px solid #8b8f94; background: #464646; border-right: 0; border-bottom: 0; }
.vi_media_room .stream_box .wait_join { position: absolute; left: 50%; top: 159px; transform: translate(-50%, 0); -webkit-transform: translate(-50%, 0); padding-top: 100px; line-height: 1.8; text-align: center; background: url("../../assets/images/vi/8.png") center 0 no-repeat; font-size: 16px; color: #ffffff; z-index: 1; }
.vi_media_room .stream_handle { width: 1198px; height: 99px; border-radius: 0 0 3px 3px; background: #f7f7f7; border: 1px solid #e5e5e5; border-top: 0; position: relative; }
.vi_media_room .stream_handle .handle_box { width: 200px; height: 79px; margin: 0 auto; padding-top: 19px; }
.vi_media_room .stream_handle .handle_box .handle_audio { float: left; width: 60px; height: 60px; cursor: pointer; background: url("../../assets/images/vi/3.png") 0 0 no-repeat; }
.vi_media_room .stream_handle .handle_box .handle_audio.disable { background: url("../../assets/images/vi/7.png") 0 0 no-repeat; }
.vi_media_room .stream_handle .handle_box .handle_call { float: right; width: 60px; height: 60px; cursor: pointer; background: url("../../assets/images/vi/4.png") 0 0 no-repeat; }
.vi_media_room .stream_handle .info_line { position: absolute; width: 430px; height: 18px; line-height: 18px; font-size: 14px; color: #333333; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
.vi_media_room .stream_handle .info_line.i1 { left: 20px; top: 25px; }
.vi_media_room .stream_handle .info_line.i2 { left: 20px; top: 58px; }
.vi_media_room .stream_handle .info_line.i3 { right: 20px; top: 25px; text-align: right; }
.vi_media_room .stream_handle .to_show { position: absolute; right: 20px; bottom: 15px; line-height: 28px; border: 1px solid #333333; border-radius: 28px; padding: 0 12px 0 27px; background: url("../../assets/images/vi/6.png") 9px center no-repeat; display: block; text-decoration: none; color: #333; }
.vi_media_room .stream_handle .to_warn { cursor: pointer; position: absolute; right: 139px; bottom: 15px; line-height: 28px; border: 1px solid #333333; border-radius: 28px; padding: 0 12px 0 27px; background: url("../../assets/images/vi/5.png") 10px center no-repeat; display: block; text-decoration: none; color: #333; cursor: pointer; }
.vi_media_room .stream_handle .to_warn.btn_disabled { background: #ebebeb url("../../assets/images/vi/18.png") 10px center no-repeat; border: 1px #cbcecf solid; color: #a0a0a0; cursor: default; }

.vi_over_box { width: 1200px; margin: 0 auto; padding: 160px 0 190px; text-align: center; }
.vi_over_box .over_title { display: inline-block; height: 40px; line-height: 40px; padding-left: 63px; font-size: 26px; color: #3e444c; text-align: left; background: url("../../assets/images/vi/13.png") 0 center no-repeat; background-size: 40px; margin-bottom: 66px; }
.vi_over_box .over_info_box { display: inline-block; padding: 60px 0 30px; background: #fafafa; border-radius: 10px; }
.vi_over_box .over_info_box .box_line { width: 443px; height: 20px; line-height: 20px; padding: 0 100px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; word-break: break-all; margin-bottom: 25px; font-size: 16px; color: #999; text-align: left; }
.vi_over_box .over_info_box .box_line .con { color: #3e444c; }

body.vi_sub { background: #f2f6fc; }

.vi_sub_d1 { width: 100%; height: 300px; background: url("../../assets/images/vi/sub_d1_bg.jpg") center no-repeat; }

.vi_sub_d2 { width: 1200px; height: 155px; background: #fff; margin: 15px auto; padding-top: 45px; }
.vi_sub_d2 .d_notice { position: relative; width: 960px; height: 36px; margin: 0 auto; border-radius: 36px; background: #fffaf1; }
.vi_sub_d2 .d_notice .no_ico { position: absolute; width: 83px; height: 36px; line-height: 36px; padding-left: 54px; color: #ff6600; font-size: 14px; background: url("../../assets/images/vi/14.png") 28px center no-repeat; left: 0; top: 0; }
.vi_sub_d2 .d_notice .no_up { position: relative; width: 790px; height: 36px; padding-left: 137px; overflow: hidden; }
.vi_sub_d2 .d_notice .no_up ul li { width: 700px; padding-right: 90px; height: 36px; line-height: 36px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; color: #666666; font-size: 14px; position: relative; }
.vi_sub_d2 .d_notice .no_up ul li a.link_b { color: #333; font-weight: 700; text-decoration: none; }
.vi_sub_d2 .d_notice .no_up ul li a.link_o { color: #ff6600; font-weight: 700; text-decoration: none; }
.vi_sub_d2 .d_notice .no_up ul li .f60 { color: #ff6600; }
.vi_sub_d2 .d_notice .no_up ul li .no_more { position: absolute; right: 0; top: 50%; transform: translate(0, -50%); -webkit-transform: translate(0, -50%); color: #1787fb; font-size: 14px; text-decoration: none; }

.vi_sub_d2 .b_search { width: 732px; margin: 0 auto 30px; }

.vi_sub_d2 .b_search .in_sea_inp { float: left; display: block; height: 46px; line-height: 46px; width: 588px; padding: 0 16px; border: 1px solid #e2e2e2; border-right: 0; background: #fff; border-radius: 4px 0 0 4px; }

.vi_sub_d2 .b_search .in_sea_bt { float: left; width: 110px; height: 48px; line-height: 48px; font-size: 18px; text-align: center; color: #fff; background: #1787fb; border-radius: 0 4px 4px 0; cursor: pointer; transition: all .3s; -webkit-transition: all .3s; }

.vi_sub_d2 .b_search .in_sea_bt:hover { background: #177ae0; }

.vi_sub_pt { position: relative; width: 1182px; height: 50px; margin: 0 auto 10px; padding-left: 18px; background: #fff; line-height: 50px; }
.vi_sub_pt::before { content: ' '; position: absolute; width: 5px; height: 24px; background: #1787fb; left: 0; top: 13px; }
.vi_sub_pt .pt_txt { font-size: 16px; font-weight: 700; color: #1787fb; }
.vi_sub_pt .pt_right_tip { position: absolute; right: 0; top: 0; padding-right: 23px; height: 50px; line-height: 50px; color: #999; }
.vi_sub_pt .pt_right_link { position: absolute; right: 0; top: 0; padding-right: 23px; height: 50px; line-height: 50px; color: #1787fb; text-decoration: none; font-size: 14px; }
.vi_sub_pt .pt_right_link:hover { text-decoration: underline; }

.vi_sub_d3 { width: 1200px; margin: 0 auto 20px; }
.vi_sub_d3 .vd_cell { position: relative; float: left; width: 255px; margin: 0 12px 12px 0; background: #fff; padding: 20px 18px 22px 18px; }
.vi_sub_d3 .vd_cell:hover { box-shadow: 1px 1px 11px rgba(0, 0, 0, 0.3); }
.vi_sub_d3 .vd_cell:nth-child(4n+0) { margin-right: 0; }
.vi_sub_d3 .vd_cell .j_name { width: 190px; height: 22px; line-height: 22px; word-break: break-all; }
.vi_sub_d3 .vd_cell .j_name a { font-size: 16px; font-weight: 700; color: #333; text-decoration: none; }
.vi_sub_d3 .vd_cell .j_name a:hover { text-decoration: underline; }
.vi_sub_d3 .vd_cell .j_wage { position: absolute; right: 18px; top: 20px; height: 22px; line-height: 22px; font-size: 14px; color: #ff6600; }
.vi_sub_d3 .vd_cell .j_age { width: 255px; height: 22px; line-height: 22px; font-size: 12px; color: #666; margin: 8px 0; word-break: break-all; }
.vi_sub_d3 .vd_cell .j_com { width: 255px; height: 22px; line-height: 22px; word-break: break-all; }
.vi_sub_d3 .vd_cell .j_com a { font-size: 12px; font-weight: 700; color: #999; text-decoration: none; }
.vi_sub_d3 .vd_cell .j_com a:hover { text-decoration: underline; }
.vi_sub_d3 .g_empty { width: 1200px; padding: 236px 0; text-align: center; font-size: 14px; color: #999999; background: #fff url("../../assets/images/vi/15.png") center 120px no-repeat; }
.vi_sub_d3 .vd_page { width: 1200px; height: 52px; background: #fff; }

.vi_sub_d4 { width: 1200px; height: 155px; background: #fff; padding-top: 35px; position: relative; margin: 0 auto; }
.vi_sub_d4 .d4_ico { width: 1039px; height: 64px; margin: 0 auto; background: url("../../assets/images/vi/16.png") 0 no-repeat; }
.vi_sub_d4 .d4_txt { position: absolute; line-height: 1.6; font-size: 12px; color: #999; text-align: center; width: 100px; }
.vi_sub_d4 .d4_txt.t1 { left: 67px; bottom: 38px; }
.vi_sub_d4 .d4_txt.t2 { left: 260px; bottom: 38px; }
.vi_sub_d4 .d4_txt.t3 { left: 453px; bottom: 38px; }
.vi_sub_d4 .d4_txt.t4 { left: 645px; bottom: 38px; }
.vi_sub_d4 .d4_txt.t5 { left: 841px; bottom: 38px; }
.vi_sub_d4 .d4_txt.t6 { left: 1033px; bottom: 38px; }
</style>
