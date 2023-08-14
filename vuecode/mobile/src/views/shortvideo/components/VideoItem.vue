<template>
<div>

  <div class="player" v-if="videoItem">
    <div :style="{filter: 'blur(15px)',position: 'absolute',top: '0',width:'100vw',height:'100%',background:'url('+videoItem.video_src + '?vframe/jpg/offset/0'+') 150% 150%',objectfit: 'fill'}"></div>
    <!-- :poster="videoItem.video_src + '?vframe/jpg/offset/1/w/400'" -->
    <video
      loop="loop"
      :autoplay="isPoster ? undefined : autoplay"
      ref="video"
      class="video"
      :src="videoItem.video_src"
      webkit-playsinline="true"
      x5-video-player-type="h5"
      x5-video-player-fullscreen="true"
      playsinline=""
      preload="none"
      :id="videoItem.id"
      :poster="videoItem.video_src + '?vframe/jpg/offset/0'"
      layorpause="playOrPause"
      @click="togglePlay"
    >
    </video>
    <div @click="togglePlay" v-if="!isPlay">
      <div class="videoshow" v-if="$store.state.videoShowPlay"></div>
    </div>
    <van-progress color="#FFFFFF" track-color="#999999" :percentage="progress" :show-pivot="false" stroke-width="1.5" class="progress" v-if="progress"/>
    <div class="infodiv" v-if="videoType==1">
      <div class="info">
        {{videoItem.companyname}}
      </div>
      <div class="present">{{videoItem.title}}</div>
      <div class="clearfix" v-if="videoItem.address">
        <div class="address">{{videoItem.address}}</div>
      </div>
      <div class="intent" v-if="videoItem.job_list && videoItem.job_list.length > 0">
        正在招聘:
        <van-notice-bar speed="20" color="#ffe507" background="rgb(0, 0, 0, 0)" @click.stop="company(videoItem.comid,'job')" scrollable :text="intent">
        </van-notice-bar>
        <van-icon name="arrow" color="#FFFFFF" style="margin-right: 19px;"/>
      </div>
    </div>
    <div class="rightmenu" v-if="videoType==1">
      <div class="photo">
        <img :src="videoItem.logo_src" class="logo" @click.stop="company(videoItem.comid,'com')">
        <div v-if="gointype!='myplay'" :class="videoItem.has_fav == 1?num==0?'attentiontwo':'attention':'noattention'" @click.stop="doAttention(videoItem.comid)"></div>
      </div>
      <div v-if="gointype!='myplay'" :class="videoItem.has_collect==0?'nofavorite':'favorite'" @click.stop="getlike (videoItem.id, videoItem.has_collect==0?'1':'2')">{{videoItem.like}}</div>
      <div v-if="videoItem.audit==2&&videoItem.is_public=='2'" class="share" @click.stop="shareVideo">分享</div>
      <div class="resume" @click.stop="company(videoItem.comid,'job')">投简历</div>
    </div>
    <div class="infodiv" v-if="videoType==2">
      <div class="infotwo">
        <div class="name">{{videoItem.fullname}}</div>
        <div class="centent">{{videoItem.education_text}} | {{videoItem.experience_text}}</div>
      </div>
      <div class="present">{{videoItem.title}}</div>
      <div class="clearfix" v-if="videoItem.address">
        <div class="address">{{videoItem.address}}</div>
      </div>
      <div class="intent" v-if="videoItem.intention && videoItem.intention.length > 0">
              <div>求职意向:</div>
              <van-notice-bar speed="20" color="#ffe507" background="rgb(0, 0, 0, 0)" @click.stop="resume(videoItem.resume_id)" scrollable :text="intent">
              </van-notice-bar>
              <van-icon name="arrow" color="#FFFFFF" style="margin-right: 19px;"/>
        </div>
    </div>
    <div class="rightmenu" v-if="videoType==2">
      <div class="photo">
        <img :src="videoItem.avatar_src" class="logo" @click.stop="resume(videoItem.resume_id)">
        <div v-if="gointype!='myplay'" :class="videoItem.has_fav == 1?num==0?'attentiontwo':'attention':'noattention'" @click.stop="doFav(videoItem.resume_id)"></div>
      </div>
      <div v-if="gointype!='myplay'" :class="videoItem.has_collect==0?'nofavorite':'favorite'" @click.stop="getlike (videoItem.id, videoItem.has_collect==0?'1':'2')">{{videoItem.like}}</div>
      <div v-if="videoItem.audit==2&&videoItem.is_public=='2'" class="share" @click.stop="shareVideo">分享</div>
      <div class="job" @click.stop="resume(videoItem.resume_id)">简历</div>
    </div>
    <van-popup
      v-model="showLogin"
      position="right"
      :overlay="false"
      style="width:100%;height:100%;"
      get-container="body"
    >
      <Login
        :utype="videoType == 1?2:1"
        :single_login="true"
        @afterLogin="afterLogin"
        :after_login_data="after_login_data"
      ></Login>
    </van-popup>
  </div>
</div>
</template>

<script>
import api from '@/api'
import http from '@/utils/http'
import ShareVideo from './ShareVideo.vue'
import Login from '@/components/Login'
import wxshare from '@/assets/js/share.js'

export default {
  props: ['videoItem', 'isPoster', 'videoCategoryList', 'autoplay', 'videoType'],
  components: {
    ShareVideo,
    Login
  },
  data () {
    return {
      timer: null,
      progress: 0,
      showInfoSwipe: true,
      progressLoading: false,
      isPlay: false,
      gointype: '',
      shareshow: false,
      num: 0,
      after_login_data: {},
      showLogin: false,
      hited: false
    }
  },
  watch: {
    videoItem (v) {
      var _this = this
      _this.showInfoSwipe = false
      _this.showInfoSwipe = true
      v && _this.initWH(v)
    },
    progressLoading (loading) {
      if (loading) {
        this.$refs.timer && this.$refs.timer.stopTimer()
      } else {
        this.$refs.timer && this.$refs.timer.startTimer()
      }
    }
  },
  computed: {
    intent () {
      if (this.videoType == 2) {
        if (!this.videoItem.intention) return ''
        return this.videoItem.intention.reduce((s, it) => {
          s += '想找 ' + it.category_text + ' 工作，想在 ' + it.district_text + ' 工作；'
          return s
        }, '')
      } else {
        if (!this.videoItem.job_list) return ''
        return this.videoItem.job_list.reduce((s, it) => {
          s += `${it.jobname}(${it.minwage == 0 && it.maxwage == 0 ? '面议' : (it.minwage + '-' + it.maxwage)})；`
          return s
        }, '')
      }
    }
  },
  mounted () {
    this.gointype = this.$route.query.gointype
    if (!this.isPoster) this.initVideoEvents()
  },
  methods: {
    afterLogin (data) {
      this.showLogin = false
    },
    shareVideo () {
      this.videoItem.type = this.videoType
      this.$emit('share', this.videoItem)
    },
    company (id, type) {
      this.$router.push({
        path: '/company/' + id,
        query: {
          type: type
        }
      })
    },
    resume (id) {
      if (this.videoItem.resume_audit != 1) {
        this.$notify('该简历还未审核通过，不能继续此操作')
        return false
      }
      this.$router.push('/resume/' + id)
    },
    getlike (id, action) {
      if (this.$store.state.LoginOrNot == true) {
        if (this.$store.state.LoginType == this.videoType) {
          let name = ''
          if (this.videoType == 1) {
            name = '求职者'
          } else {
            name = '企业'
          }
          this.$dialog
            .confirm({
              title: '提示',
              message: '当前操作需要登录' + name + '账号',
              confirmButtonText: '去登录'
            })
            .then(() => {
              this.showLogin = true
              this.after_login_data = {
                method: 'doAttention'
              }
            })
            .catch(() => {})
          return false
        }
        http.post(api.shortvideo_favorites, {id: id, type: this.videoType, action: action})
          .then((res) => {
            if (res.code == 200) {
              if (action == '1') {
                this.videoItem.has_collect = 1
                this.videoItem.like++
              } else {
                this.videoItem.has_collect = 0
                this.videoItem.like--
              }
              this.$notify({ type: 'success', message: res.message })
            }
          })
          .catch(() => {})
      } else {
        let name = ''
        if (this.videoType == 1) {
          name = '求职者'
        } else {
          name = '企业'
        }
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录' + name + '账号',
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
            this.after_login_data = {
              method: 'doAttention'
            }
          }).catch(() => {})
      }
    },
    doAttention (id) {
      if (this.$store.state.LoginOrNot == true) {
        this.num = 1
        if (this.$store.state.LoginType != 2) {
          this.$dialog
            .confirm({
              title: '提示',
              message: '当前操作需要登录求职者账号',
              confirmButtonText: '去登录'
            })
            .then(() => {
              this.showLogin = true
              this.after_login_data = {
                method: 'doAttention'
              }
            })
            .catch(() => {})
          return false
        }
        const params = {
          comid: id
        }
        let _api_url =
          this.videoItem.has_fav == 1
            ? api.company_attention_cancel
            : api.company_attention
        http
          .post(_api_url, params)
          .then(res => {
            this.id = this.videoItem.id
            this.fetchData(true)
          })
          .catch(() => {})
      } else {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录求职者账号',
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
            this.after_login_data = {
              method: 'doAttention'
            }
          })
          .catch(() => {})
      }
    },
    doFav (id) {
      if (this.$store.state.LoginOrNot == true) {
        this.num = 1
        if (this.$store.state.LoginType != 1) {
          this.$dialog
            .confirm({
              title: '提示',
              message: '当前操作需要登录企业账号',
              confirmButtonText: '去登录'
            })
            .then(() => {
              this.showLogin = true
              this.after_login_data = {
                method: 'doAttention'
              }
            })
            .catch(() => {})
          return false
        }
        if (this.videoItem.resume_audit != 1) {
          this.$notify('该简历还未审核通过，不能继续此操作')
          return false
        }
        const params = {
          resume_id: id
        }
        let _api_url = this.videoItem.has_fav == 1 ? api.resumefav_cancel : api.resumefav
        http
          .post(_api_url, params)
          .then(res => {
            this.id = this.videoItem.id
            this.fetchData(true)
          })
          .catch(() => {})
      } else {
        this.$dialog
          .confirm({
            title: '提示',
            message: '当前操作需要登录企业账号',
            confirmButtonText: '去登录'
          })
          .then(() => {
            this.showLogin = true
            this.after_login_data = {
              method: 'doAttention'
            }
          })
          .catch(() => {})
      }
    },
    getlook (id) {
      http.get(api.shortvideo_record, {id: id, type: this.videoType}).then((r) => {})
    },
    wxshare (v) {
      if (this.videoType == 1) {
        let wechatShareInfo = {
          comname: v.companyname,
          desc: v.title,
          imgUrl: v.video_src + '?vframe/jpg/offset/0/w/100/h/100'
        }
        let url = this.$store.state.config.mobile_domain + `shortvideo/videoplay?id=${v.id}&gointype=playlist&videotype=1`
        wxshare(wechatShareInfo, 'svcomshow', url)
      } else {
        let wechatShareInfo = {
          resumename: v.fullname,
          desc: v.title,
          imgUrl: v.video_src + '?vframe/jpg/offset/0/w/100/h/100'
        }
        let url = this.$store.state.config.mobile_domain + `shortvideo/videoplay?id=${v.id}&gointype=playlist&videotype=2`
        wxshare(wechatShareInfo, 'svpershow', url)
      }
    },
    initVideoEvents () {
      let video = this.$refs.video
      let _this = this

      function onBridgeReady () {
        window.WeixinJSBridge.invoke('getNetworkType', {}, function () {
          if (_this.autoplay) video.play()
        })
      }

      if (typeof WeixinJSBridge == 'undefined') {
        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false)
      } else {
        onBridgeReady()
      }
      video.onplay = function () {
        _this.isPlay = true
        _this.progressLoading = false
        if (!_this.hited) {
          _this.hited = true
          _this.getlook(_this.videoItem.id)
        }
        _this.startProgress()
        _this.wxshare(_this.videoItem)
      }
      video.oncanplay = function () {
        _this.progressLoading = false
        if (!_this.videoItem.videoClass) {
          _this.videoClass = video.videoHeight / video.videoWidth > 1.5 ? 'video-cover' : 'video-contain'
        }
      }
      video.onpause = function () {
        _this.isPlay = false
        _this.progressLoading = false
        _this.stopProgress()
      }
      video.onwaiting = function () {
        _this.progressLoading = true
      }
    },
    initVideo () {
      this.hited = false
      var video = this.$refs.video
      video.currentTime = 0
      this.progress = 0
      this.progressLoading = false
    },
    initWH (v) {
      if (v.style) {
        this.$refs.video.style.width = v.style.width
        this.$refs.video.style.height = v.style.height
        return
      }
      var img = new Image()
      img.src = v.video_src + '?vframe/jpg/offset/0'
      img.onload = () => {
        let cw = document.documentElement.clientWidth
        let ch = document.documentElement.clientHeight
        let width = img.width
        let height = img.height

        if (width > height) {
          v.style =
            this.$refs.video.style.width = '100%'
          this.$refs.video.style.height = ''
        }
        if (height > width) {
          if ((height / width) > (ch / cw)) {
            this.$refs.video.style.width = '100%'
            this.$refs.video.style.height = ''
          } else {
            this.$refs.video.style.height = '100%'
            this.$refs.video.style.width = ''
          }
        }
        v.style = {
          width: this.$refs.video.style.width,
          height: this.$refs.video.style.height
        }
      }
    },
    startProgress () {
      var _this = this
      var video = this.$refs.video
      _this.progress = Math.ceil(video.currentTime / video.duration * 100)
      this.timer = setInterval(function () {
        _this.progress = Math.ceil(video.currentTime / video.duration * 100)
      }, 60)
    },
    stopProgress () {
      clearInterval(this.timer)
    },
    togglePlay () {
      var video = this.$refs.video
      if (video.paused) {
        this.$store.state.videoShowPlay = false
        video.play()
      } else {
        this.$store.state.videoShowPlay = true
        video.pause()
      }
      this.isPlay = !video.paused
    },
    play () {
      var video = this.$refs.video
      video.play()
    },
    pause () {
      this.$refs.video && this.$refs.video.pause()
    }
  }
}
</script>

<style lang="scss" scoped>
  .tab-swiper, .video-swiper, .swiper-wrapper, .swiper-slide {
    height: 100%;
  }
  .progress{
    width: 100vw;
    position: absolute;
    bottom: 55px;
  }
  .videoshow{
    position: absolute;
    left:50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    background: url('../../../assets/images/shortvideo/stop.png') center center no-repeat;
    background-size: 60px 60px;
  }

  .player{
    position: absolute;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    .video{
      display: block;
      object-position: center 40%;
      position: absolute;
    }
  }
  .infodiv{
    width: 100vw;
    position: absolute;
    bottom: 0;
    padding-bottom: 57px;
    padding-top: 50px;
    color: #FFFFFF;
    background-image: linear-gradient(to bottom, rgba(0,0,0,0),rgba(0,0,0,.3) , rgba(0,0,0,.5));
    .info{
      margin-left: 15px;
      margin-bottom: 10px;
      font-size: 17px;
      max-width: 300px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .infotwo{
      margin-left: 15px;
      margin-bottom: 10px;
      display: flex;
      align-items: flex-end;
      .name{
        font-size: 17px;
      }
      .centent{
        margin-left: 15px;
        font-size: 13px;
      }
    }
    .present{
      margin-left: 15px;
      margin-right: 79px;
      margin-bottom: 10px;
      font-size: 15px;
      line-height: 1.5;
    }
    .address{
      float: left;
      margin-left: 15px;
      margin-right: 15px;
      margin-bottom: 8px;
      background: url("../../../assets/images/shortvideo/address.png") 5px center no-repeat rgba($color: #000000, $alpha: 0.5);
      background-size: 9px 11px;
      padding: 3px 5px 3px 15px;
      font-size: 11px;
      border-top-right-radius: 8px;
      border-bottom-left-radius: 8px;
    }
    .intent{
      padding-left: 15px;
      display: flex;
      align-items: center;
      height: 42px;
      font-size: 14px;
      border-top: 1px solid #999999;
      .van-notice-bar{
        height: 100%;
        flex: 8;
        color: #ffe507;
        background: rgb(0, 0, 0, 0);
      }
    }
  }
  .rightmenu{
    position: absolute;
    right: 6px;
    bottom: 130px;
    color: #FFFFFF;
    font-size: 12px;
    .photo{
      position: relative;
      .logo{
        width: 49px;
        height: 49px;
        border-radius: 50px;
        background-color: #ccc;
      }
      .attentiontwo{
        opacity: 0;
      }
      .noattention{
        position: absolute;
        right: 0;
        left: 0;
        bottom: -5px;
        margin: auto;
        width: 20px;
        height: 20px;
        border-radius: 50px;
        background: url("../../../assets/images/shortvideo/noattention.png") center center no-repeat #fc583a;
        background-size: 9px;
      }
      .attention{
        position: absolute;
        right: 0;
        left: 0;
        bottom: -5px;
        margin: auto;
        width: 20px;
        height: 20px;
        border-radius: 50px;
        background: url("../../../assets/images/shortvideo/attention.png") center center no-repeat #fc583a;
        background-size: 10px 8px;
        transition: all 0.3s ease;
        animation: fadeIn 0.3s linear 1.5s backwards;
        opacity: 0;
      }
    }
    .nofavorite{
      width: 49px;
      background: url("../../../assets/images/shortvideo/nofavorite.png") center 0 no-repeat;
      background-size: 31px 28px;
      padding-top: 34px;
      text-align: center;
      margin-top: 32px;
    }
    .favorite{
      width: 49px;
      background: url("../../../assets/images/shortvideo/favorite.png") center 0 no-repeat;
      background-size: 31px 28px;
      padding-top: 34px;
      text-align: center;
      margin-top: 32px;
    }
    .share{
      width: 49px;
      background: url("../../../assets/images/shortvideo/share.png") center 0 no-repeat;
      background-size: 31px 31px;
      padding-top: 37px;
      text-align: center;
      margin-top: 19px;
    }
    .resume{
      width: 49px;
      background: url("../../../assets/images/shortvideo/resumemang.png") center 0 no-repeat;
      background-size: 31px 30px;
      padding-top: 36px;
      text-align: center;
      margin-top: 19px;
    }
    .job{
      width: 49px;
      background: url("../../../assets/images/shortvideo/jobmang.png") center 0 no-repeat;
      background-size: 31px 28px;
      padding-top: 34px;
      text-align: center;
      margin-top: 19px;
    }
  }
  .shareimg{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
  }
</style>
