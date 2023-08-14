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
        <a href="" class="hc_detect">设备检测</a>
      </div>
    </div>
    <div class="vi_container">
      <div class="tip" v-show="!isChrome">
        为提高视频面试效率，建议您使用谷歌内核浏览器并提前检测您的设备是否能够正常连通
      </div>
      <div class="device_detect_group">
        <div class="group_title">视频面试·设备检测</div>
        <div class="detect_list">
          <div class="list_line" id="video_grid">
            <div class="line_title t1">摄像头检测</div>
            <div :class="camerasList.length > 1 ? 'line_li' : 'line_li disable'">
              <el-dropdown
                placement="bottom"
                v-if="camerasList.length > 1"
              >
                <span class="el-dropdown-link">
                  {{camerasName}}
                </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item
                    v-for="(item, index) in camerasList"
                    @click.native="changeDevice(item)"
                    :key="index"
                  >
                    {{item.label}}
                  </el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
              <span v-else>{{camerasName}}</span>
            </div>
            <div class="detect_result" id="local_stream"></div>
          </div>
          <div class="list_line">
            <div class="line_title t2">
              麦克风 (请说话，并观察右侧音量条是否变化)
            </div>
            <div :class="microphonesList.length > 1 ? 'line_li' : 'line_li disable'">
              <el-dropdown
                placement="bottom"
                v-if="microphonesList.length > 1"
              >
                <span class="el-dropdown-link">
                  {{microphoneName}}
                </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item
                    v-for="(item, index) in microphonesList"
                    :key="index"
                    @click.native="changeDevice(1, index)"
                  >
                    {{item.label}}
                  </el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
              <span v-else>{{microphoneName}}</span>
            </div>
            <div class="detect_result">
              <div class="volume_bar">
                <div class="volume_contetn">
                  <div class="volume_auto" :style="{width: volume + '%'}"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="list_line">
            <div class="line_title t4">
              扬声器 (播放测试音频，并观察是否可以听到声音)
            </div>
            <div class="line_li disable">
              {{speakerName}}
            </div>
            <div class="detect_result">
              <audio
                ref="sampleMusic"
                loop="loop"
                controls="controls"
                class="audio"
              >
                <source
                  src="../../assets/images/vi/music.mp3"
                  type="audio/mp3"
                />
                Your browser does not support the audio tag.
              </audio>
              <div class="test_play" @click="audioPlay" v-if="audioPlayShow">播放测试音频</div>
              <div class="test_playing" @click="audioPaused" v-else>正在播放</div>
            </div>
          </div>
          <div class="list_line">
            <div class="line_title t3">网络检测</div>
            <div class="line_li not">
              <span :class="netCheckStatus ? 'good' : 'bad'">{{netCheckResult}}</span>
            </div>
            <div class="detect_result">
              <div class="network_check">
                <div :class="netCheckStatus ? 'check_ico checked' : 'check_ico'"></div>
                <div>{{netCheckStatus ? '网络检测完成' : '网络检测中...'}}</div>
              </div>
            </div>
          </div>
        </div>
        <router-link :to="'/video/' + interview_id" class="jump_room" v-if="error !== 1">进入面试</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import TRTC from 'trtc-js-sdk'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'Test',
  data(){
    return {
      isChrome: true,
      client: '', // 客户端服务
      userId: '',//用户id --可更改
      roomId: "74_" + parseInt(Math.random() * 100000000),
      localStream: '', // 本地流
      audio: true,
      video: true,
      interview_id: 0,
      config: {
        appid: '',
        sig: ''
      },
      netCheckResult: '网络检测中...',
      netCheckStatus: false,
      audioPlayShow: true,
      volmueTimer: '',
      volume: 1,
      error: 0,
      camerasList: [],
      camerasName: '默认 - 摄像头',
      microphonesList: [],
      microphoneName: '默认 - 麦克风阵列',
      speakersList: [],
      speakerName: '默认 - 扬声器'
    }
  },
  created(){
    this.isChrome = navigator.userAgent.indexOf("Chrome") > -1; // 是否是谷歌浏览器
    this.interview_id = this.$route.params.interview_id
    this.getConfig()
  },
  mounted() {
  },
  methods:{
    detectVolume (stream) {
      let that = this
      this.volmueTimer = window.setInterval(function() {
        that.volume = stream.getAudioLevel() * 100
      }, 600);
    },
    audioPlay () {
      this.$refs.sampleMusic.play()
      this.audioPlayShow = !this.audioPlayShow
    },
    audioPaused () {
      this.$refs.sampleMusic.pause()
      this.audioPlayShow = !this.audioPlayShow
    },
    getConfig () {
      http
        .post(api.rtc_config)
        .then(res => {
          this.config.appid = res.data.appid
          this.config.sig = res.data.sig
          this.userId = res.data.userid
          this.netCheckStatus = true
          this.netCheckResult =
          this.netCheckStatus ? "您当前的网络质量较好，可以进行视频面试" : "您当前的网络质量比较差，建议更换网络环境"
          this.error = res.data.error
          this.createClient()
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
      this.joinRoom(this.client, this.roomId) // 初始化后才能加入房间
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
      // 摄像头列表
      TRTC.getCameras().then(devices => {
        this.camerasList = devices
        this.camerasName = devices[0].label
      })
      // 麦克风列表
      TRTC.getMicrophones().then(devices => {
        this.microphonesList = devices
        this.microphoneName = devices[0].label
      })
      // 扬声器列表
      TRTC.getSpeakers().then(devices => {
        this.speakersList = devices
        this.speakerName = devices[0].label
      })
      // 初始本地化流
      localStream
        .initialize()
        .catch(error => {
          console.error('初始化本地流失败 ' + error)
        })
        .then(() => {
          console.warn('初始化本地流成功，准备发布本地流')
          this.publishStream(localStream, this.client) // 发布本地流
          localStream.play('local_stream') // 创建好后才能播放 本地流播放local_stream
          this.detectVolume(localStream)
        })
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
    // 切换设备
    changeDevice (type, index) {
      if (type === 1) {
        // 切换麦克风
        this.microphoneName = this.microphonesList[index].label
        this.switchLocalDevice(type, this.microphonesList[index].deviceId)
      } else if (type === 0) {
        // 切换摄像头
        this.camerasName = this.camerasList[index].label
        this.switchLocalDevice(type, this.camerasList[index].deviceId)
      }
    },
    switchLocalDevice(type, dId) {
      if (eval(type) === 0) {
        // 0代表摄像头
        this.localStream.switchDevice('video', dId).then(() => {
          this.$message({ type: 'success', message: '设备切换成功' })
        });
      } else if (eval(type) === 1) {
        // 1代表麦克风
        this.localStream.switchDevice('audio', dId).then(() => {
          this.$message({ type: 'success', message: '设备切换成功' })
        });
      }
    }
  }
}
</script>

<style lang="scss" scoped>
body { background: #ffffff; }
.el-dropdown{width: 287px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
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
.device_detect_group .detect_list .list_line .line_li { width: 330px; height: 48px; line-height: 48px; margin-left: 4px; padding: 0 25px 0 18px; position: relative; color: #333; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; cursor: pointer; font-size: 14px; border-bottom: 1px solid #e5e5e5; }
.device_detect_group .detect_list .list_line .line_li::after { position: absolute; right: 0; top: 0; width: 24px; height: 100%; background: url("../../assets/images/vi/12.png") center no-repeat; content: ' '; z-index: 1; transition: transform .3s; transition: transform .3s,-webkit-transform .3s; }
.device_detect_group .detect_list .list_line .line_li.pull::after { -webkit-transform: rotate(180deg); transform: rotate(180deg); }
.device_detect_group .detect_list .list_line .line_li .good{color: #333}
.device_detect_group .detect_list .list_line .line_li .bad{color: #FF7062}
.device_detect_group .detect_list .list_line .line_li.disable { background-color: #f5f7fa; color: #c5c7c9; cursor: not-allowed; }
.device_detect_group .detect_list .list_line .line_li.not { cursor: default; }
.device_detect_group .detect_list .list_line .line_li.not::after { display: none; }
.device_detect_group .detect_list .list_line .detect_result { position: absolute; right: 0; bottom: 0; z-index: 1; width: 86px; height: 60px; background: #000; }
.device_detect_group .detect_list .list_line .detect_result .volume_bar { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%); width: 70px; height: 6px; border-radius: 6px; background: #ebeef5; z-index: 2; }
.device_detect_group .detect_list .list_line .detect_result .volume_bar .volume_contetn { position: relative; width: 100%; height: 100%; }
.device_detect_group .detect_list .list_line .detect_result .volume_bar .volume_contetn .volume_auto { position: absolute; left: 0; top: 0; width: auto; height: 6px; border-radius: 6px; transition: width .6s ease; background: #19be6b; z-index: 3; -webkit-transition: width .6s ease; }
.device_detect_group .detect_list .list_line .detect_result .test_play { width: 100%; height: 60px; font-size: 12px; color: #fff; text-align: center; padding-top: 33px; background: url("../../assets/images/vi/test_play.png") center 15px no-repeat; cursor: pointer; }
.device_detect_group .detect_list .list_line .detect_result .test_playing { width: 100%; height: 60px; font-size: 12px; color: #fff; text-align: center; padding-top: 35px; background: url("../../assets/images/vi/test_playing.png") center 13px no-repeat; cursor: pointer; }
.device_detect_group .detect_list .list_line .detect_result .audio { display: none; }
.device_detect_group .detect_list .list_line .detect_result .network_check { width: 100%; height: 49px; font-size: 12px; color: #fff; text-align: center; padding-top: 11px; }
.device_detect_group .detect_list .list_line .detect_result .network_check .check_ico { display: inline-block; width: 18px; height: 18px; margin-bottom: 3px; animation: checking-data 1s linear infinite; background: url("../../assets/images/vi/network_check.png") 0 0 no-repeat; background-size: 18px; }
.device_detect_group .detect_list .list_line .detect_result .network_check .check_ico.checked { background: url("../../assets/images/vi/network.png") 0 0 no-repeat; background-size: 18px; animation: none; }
.device_detect_group .jump_room { text-decoration: none; width: 110px; line-height: 36px; border-radius: 38px; border: 1px solid #1787fb; background: #deeeff; text-align: center; color: #1787fb; font-size: 16px; transition: all .3s; -webkit-transition: all .3s; display: block; margin: 0 auto; }
.device_detect_group .jump_room:hover { text-decoration: none; background: #1787fb; color: #ffffff; }

@-webkit-keyframes checking-data { 0% { -webkit-transform: rotate(0deg); transform: rotate(0deg); }
  to { -webkit-transform: rotate(1turn); transform: rotate(1turn); } }
@keyframes checking-data { 0% { -webkit-transform: rotate(0deg); transform: rotate(0deg); }
  to { -webkit-transform: rotate(1turn); transform: rotate(1turn); } }
.sm_container { z-index: 2; }

.sm_results > li { min-width: 200px; max-width: 390px; }

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
.vi_media_room .stream_handle .to_show { position: absolute; right: 20px; bottom: 15px; height: 28px; line-height: 28px; border: 1px solid #333333; border-radius: 28px; padding: 0 12px 0 27px; background: url("../../assets/images/vi/6.png") 9px center no-repeat; display: block; text-decoration: none; color: #333; }
.vi_media_room .stream_handle .to_warn { position: absolute; right: 139px; bottom: 15px; height: 28px; line-height: 28px; border: 1px solid #333333; border-radius: 28px; padding: 0 12px 0 27px; background: url("../../assets/images/vi/5.png") 10px center no-repeat; display: block; text-decoration: none; color: #333; cursor: pointer; }
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
