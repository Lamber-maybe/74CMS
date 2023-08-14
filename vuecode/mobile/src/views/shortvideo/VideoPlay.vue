<template>
  <div id="app">
    <Head :bg="'tran'" class="top">
      <div class="title" v-if="gointype=='playlist'">
        <div :class="{sel: currTab == 2}" @click="changeTab(0, 2)">热门</div>
        <div :class="{sel: currTab == 1}" @click="changeTab(1, 1)">最新</div>
        <div :class="{sel: currTab == 3}" @click="changeTab(2, 3)">附近</div>
      </div>
      <div v-if="gointype=='myplay'">我的视频</div>
      <div v-if="gointype=='mycollection'">我的收藏</div>
      <div v-if="gointype=='unitplay'">{{params.type==1?'公司视频':'个人视频'}}</div>
    </Head>
    <div class="content">
      <swiper ref="qsswiper" :options="swiperOption" v-if="$store.state.swiperLoaded" @transitionEnd="transitionEnd">
        <swiper-slide v-if="gointype=='playlist'">
          <video-swiper
              ref="slide2"
              :order-type="2"
              :video-type="params.type"
              :autoplay="currTab == 2 ? 'autoplay' : undefined"
              :get-next="fetchData"
              @share="share"
            ></video-swiper>
        </swiper-slide>
        <swiper-slide>
          <video-swiper
              ref="slide1"
              :order-type="1"
              :video-type="params.type"
              :autoplay="currTab == 1 ? 'autoplay' : undefined"
              :get-next="fetchData"
              :init-one="fetchDataone"
              @share="share"
            ></video-swiper>
        </swiper-slide>
        <swiper-slide v-if="gointype=='playlist'">
          <video-swiper
              ref="slide3"
              :order-type="3"
              :video-type="params.type"
              :autoplay="currTab == 3 ? 'autoplay' : undefined"
              :get-next="fetchData"
              @share="share"
            ></video-swiper>
        </swiper-slide>
      </swiper>
    </div>
    <BottomMenu></BottomMenu>
    <baidu-map
      class="bm-view"
      :ak="$store.state.config.map_ak"
      @ready="handlerMap"
    ></baidu-map>
    <ShareVideo v-if="shareVideo.show" :id="shareVideo.id" :type="shareVideo.type" @closeShare="shareVideo.show=false"></ShareVideo>
    <van-overlay z-index="9" :show="shareVideo.show" @click="shareVideo.show=false"/>
  </div>
</template>
<script>
import http from '@/utils/http'
import api from '@/api'
import BottomMenu from './components/BottomMenutwo.vue'
import Vue from 'vue'
import VideoSwiper from './components/VideoSwiper'
import ShareVideo from './components/ShareVideo.vue'

let isSpider = new RegExp('^(Baiduspider|YisouSpider|Sogou|Googlebot|Sosospider|bingbot|360Spider)').test(navigator.userAgent)
Vue.component('BaiduMap', function (resolve, reject) {
  if (!isSpider) {
    require(['vue-baidu-map/components/map/Map.vue'], resolve)
  }
})
export default {
  components: {
    BottomMenu,
    ShareVideo,
    VideoSwiper
  },
  data () {
    return {
      swiperOption: {
        initialSlide: 1,
        noSwipingClass: 'van-swipe'
      },
      num: 0,
      gointype: 'playlist', // 根据不同列表页播放展示
      id: '', // 列表点击过来的id
      getdata: 'true', // 是否循环获取数据
      currTab: 1,
      params: {
        type: 1,
        order: 1,
        page: 1,
        pagesize: 15,
        comid: 0,
        rid: 0,
        lat: 0,
        lon: 0
      },
      shareVideo: {
        id: 0,
        type: 1,
        show: false
      },
      BMap: {},
      loop: false,
      shareshow: false,
      idx2Order: {
        0: 2, 1: 1, 2: 3
      }
    }
  },
  watch: {
    gointype (val, oldVal) { // 普通的watch监听
      if (val != 'playlist' && this.$refs.qsswiper !== undefined) {
        this.$refs.qsswiper.$swiper.allowTouchMove = false // 禁止触摸
        this.$refs.qsswiper.$swiper.params.virtualTranslate = true // 定住不给回弹
        this.$refs.qsswiper.$swiper.allowSlideNext = false
      }
    }
  },
  created () {
    this.params.type = parseInt(this.$route.query.videotype) || 1
    if (this.$route.query.id) {
      this.id = this.$route.query.id
      this.gointype = this.$route.query.gointype
    }
  },
  mounted () {
  },
  methods: {
    transitionEnd () {
      let that = this
      that.tabChange(that.$refs.qsswiper.$swiper.activeIndex, that.idx2Order[that.$refs.qsswiper.$swiper.activeIndex])
    },
    share (video) {
      this.shareVideo.id = video.id
      this.shareVideo.type = video.type
      this.shareVideo.show = true
    },
    company (id) {
      this.$router.push('/company/' + id)
    },
    tabChange (idx, order, needSlide) {
      this.$store.state.videoShowPlay = false
      this.currTab = order
      let prevSlide = this.$refs['slide' + this.params.order]
      if (prevSlide) {
        prevSlide.pause()
      }
      this.params.order = order
      needSlide && this.$refs.qsswiper.$swiper.slideTo(idx)
      if (this.$refs['slide' + order]) {
        this.$refs['slide' + order].play()
      }
    },
    changeTab: function (idx, tab) {
      this.tabChange(idx, tab, true)
    },
    resume (id) {
      this.$router.push('/resume/' + id)
    },
    handlerMap ({BMap, map}) {
      this.BMap = BMap
      this.setlocation()
    },
    setlocation () {
      let that = this
      let BMap = that.BMap
      var geolocation = new BMap.Geolocation()
      geolocation.getCurrentPosition(
        function (r) {
          if (this.getStatus() == BMAP_STATUS_SUCCESS) {
            that.lat = r.point.lat
            that.lon = r.point.lng
          }
        },
        {enableHighAccuracy: true}
      )
    },

    handleClickSlide (index) {
      if (this.$refs.video[index].paused) {
        this.videoshow = false
        this.$refs.video[index].play()
      } else {
        this.videoshow = true
        this.$refs.video[index].pause()
      }
    },
    async fetchData (page, order) {
      let params = Object.assign({}, this.params)
      params.page = page
      params.order = order
      let httpurl
      if (this.gointype == 'playlist') {
        httpurl = api.shortvideo_videolist
      } else if (this.gointype == 'mycollection') {
        httpurl = api.shortvideo_myfavorites
      } else if (this.gointype == 'unitplay') {
        httpurl = api.shortvideo_videolist
        if (params.type == 1) {
          this.params.comid = this.$route.query.listid
        } else {
          this.params.rid = this.$route.query.listid
        }
      } else if (this.gointype == 'myplay') {
        httpurl = api.shortvideo_myvideo
      }
      return http.get(httpurl, params)
    },
    async fetchDataone () {
      let httpurl = api.shortvideo_detail
      return http.get(httpurl, {id: this.id, type: this.params.type})
    },
    async getlike (id, action) {
      if (this.$store.state.LoginOrNot == true && this.$store.state.LoginType == this.params.type) {
        if (this.videotype == 1) {
          this.$notify('请登录个人会员')
        } else {
          this.$notify('请登录企业会员')
        }
        return false
      }
      return http.post(api.shortvideo_favorites, {id: id, type: this.params.type, action: action})
    }
  }
}
</script>
<style lang="scss" scoped>
.swiper-container{
  height: 100%;
}
  .top {
    position: absolute;
    top: 0;
  }
  .tab-swiper, .video-swiper, .swiper-wrapper, .swiper-slide {
    height: 100%;
    width: 100%;
  }

  .title {
    display: flex;
    align-items: flex-end;
    margin-left: 15px;
    margin-right: 15px;

    div {
      font-weight: normal;
      flex: 1;
      font-size: 16px;

      &.sel {
        font-weight: bold;
        font-size: 18px;
      }
    }
  }

  .rightmenu {
    position: absolute;
    right: 6px;
    bottom: 130px;
    color: #FFFFFF;
    font-size: 12px;

    .photo {
      position: relative;

      .logo {
        width: 49px;
        height: 49px;
        border-radius: 50px;
        background-color: #ccc;
      }

      .attentiontwo {
        opacity: 0;
      }

      .noattention {
        position: absolute;
        right: 0;
        left: 0;
        bottom: -5px;
        margin: auto;
        width: 20px;
        height: 20px;
        border-radius: 50px;
        background: url("../../assets/images/shortvideo/noattention.png") center center no-repeat #fc583a;
        background-size: 9px;
      }

      .attention {
        position: absolute;
        right: 0;
        left: 0;
        bottom: -5px;
        margin: auto;
        width: 20px;
        height: 20px;
        border-radius: 50px;
        background: url("../../assets/images/shortvideo/attention.png") center center no-repeat #fc583a;
        background-size: 10px 8px;
        transition: all 0.3s ease;
        animation: fadeIn 0.3s linear 1.5s backwards;
        opacity: 0;
      }
    }

    .nofavorite {
      width: 49px;
      background: url("../../assets/images/shortvideo/nofavorite.png") center 0 no-repeat;
      background-size: 31px 28px;
      padding-top: 34px;
      text-align: center;
      margin-top: 32px;
    }

    .favorite {
      width: 49px;
      background: url("../../assets/images/shortvideo/favorite.png") center 0 no-repeat;
      background-size: 31px 28px;
      padding-top: 34px;
      text-align: center;
      margin-top: 32px;
    }

    .share {
      width: 49px;
      background: url("../../assets/images/shortvideo/share.png") center 0 no-repeat;
      background-size: 31px 31px;
      padding-top: 37px;
      text-align: center;
      margin-top: 19px;
    }

    .resume {
      width: 49px;
      background: url("../../assets/images/shortvideo/resumemang.png") center 0 no-repeat;
      background-size: 31px 30px;
      padding-top: 36px;
      text-align: center;
      margin-top: 19px;
    }

    .job {
      width: 49px;
      background: url("../../assets/images/shortvideo/jobmang.png") center 0 no-repeat;
      background-size: 31px 28px;
      padding-top: 34px;
      text-align: center;
      margin-top: 19px;
    }
  }

  @-webkit-keyframes fadeIn {
    0% {
      opacity: 1;
    }
    100% {
      opacity: 0;
    }
  }

  .infodiv {
    width: 100vw;
    position: absolute;
    bottom: 55px;
    color: #FFFFFF;

    .info {
      margin-left: 15px;
      margin-bottom: 10px;
      font-size: 17px;
      max-width: 300px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }

    .infotwo {
      margin-left: 15px;
      margin-bottom: 10px;
      display: flex;
      align-items: flex-end;

      .name {
        font-size: 17px;
      }

      .centent {
        margin-left: 15px;
        font-size: 13px;
      }
    }

    .present {
      margin-left: 15px;
      margin-right: 79px;
      margin-bottom: 10px;
      font-size: 13px;
      line-height: 1.5;
    }

    .address {
      float: left;
      margin-left: 15px;
      margin-right: 15px;
      margin-bottom: 8px;
      background: url("../../assets/images/shortvideo/address.png") 5px center no-repeat rgba($color: #000000, $alpha: 0.5);
      background-size: 9px 11px;
      padding: 3px 5px 3px 15px;
      font-size: 11px;
      border-top-right-radius: 8px;
      border-bottom-left-radius: 8px;
    }

    .intent {
      padding-left: 15px;
      display: flex;
      align-items: center;
      height: 42px;
      font-size: 14px;
      border-top: 1px solid #bebebe;

      .van-notice-bar {
        height: 100%;
        flex: 8;
        color: #ffe507;
        background: rgb(0, 0, 0, 0);
      }
    }
  }

  .content {
    width: 100vw;
    height: 100%;
    position: absolute;
  }

  .van-swipe {
    height: 100%;
    background-color: #000;
  }

  .player {
    position: absolute;
    top: 0;
    height: calc(100% - 55px);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #000;

    video {
      width: 100vw;
    }

    .videoshow {
      position: absolute;
      margin: auto;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      width: 22px;
      height: 30px;
      background: url('../../assets/images/shortvideo/stop.png') center center no-repeat;
      background-size: 22px 30px;
    }

    .progress {
      width: 100vw;
      position: absolute;
      bottom: 0px;
    }
  }
</style>
