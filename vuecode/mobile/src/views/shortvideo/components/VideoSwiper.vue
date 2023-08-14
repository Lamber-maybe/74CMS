<template>
  <swiper ref="qsswiper" :options="swiperOption" v-if="$store.state.swiperLoaded" @transitionEnd="transitionEnd" @transitionStart="transitionStart" @slidePrevTransitionEnd="slidePrevTransitionEnd" @slideNextTransitionEnd="slideNextTransitionEnd">
    <swiper-slide>
      <video-item  @share="shareVideo" v-if="prevItem" :is-poster="true" :video-item="prevItem"></video-item>
    </swiper-slide>
    <swiper-slide>
      <video-item
          v-if="currItem"
          :video-type="videoType"
          @share="shareVideo"
          ref="video"
          :video-item="currItem"
          :autoplay="autoplay"
        ></video-item>
    </swiper-slide>
    <swiper-slide>
      <video-item  @share="shareVideo" v-if="nextItem" :is-poster="true" :video-item="nextItem"></video-item>
    </swiper-slide>
  </swiper>
</template>

<script>
import VideoItem from './VideoItem'

export default {
  props: {
    videoType: {
      type: Number,
      required: true
    },
    autoplay: {
      type: String,
      default: undefined
    },
    orderType: {
      type: Number,
      required: true
    },
    initOne: {
      type: Function
    },
    getNext: {
      type: Function
    }
  },
  components: {VideoItem},
  data () {
    return {
      swiperOption: {
        initialSlide: 1,
        allowSlidePrev: false,
        noSwipingClass: 'van-swipe',
        direction: 'vertical'
      },
      activeIndex: 0,
      lastTranslate: 0,
      isPlay: false,
      isDone: false,
      currItem: {},
      data: [],
      page: 1
    }
  },
  async created () {
    this.loading = true
    let one = null
    if (this.initOne) {
      let res = await this.initOne()
      if (res.code == 200) {
        this.data.push(res.data)
        one = res.data
      }
    }
    let res = await this.getNext(1, this.orderType)
    if (one && (res.data[0].id == one.id)) {
      res.data.shift()
    }
    this.data = this.data.concat(res.data)
    if (this.data.length == 1) {
      this.$refs.qsswiper.$swiper.allowTouchMove = false // 禁止触摸
      this.$refs.qsswiper.$swiper.params.virtualTranslate = true // 定住不给回弹
      this.$refs.qsswiper.$swiper.allowSlideNext = false
    }
    this.currItem = this.data[this.activeIndex]
    this.loading = false
  },
  computed: {
    prevItem () {
      return this.data[this.activeIndex - 1] || {}
    },
    nextItem () {
      return this.data[this.activeIndex + 1] || {}
    }
  },
  watch: {
    prevItem: function () {
      this.$store.state.videoShowPlay = false
    },
    nextItem: function () {
      this.$store.state.videoShowPlay = false
    }
  },
  mounted () {
  },
  methods: {
    transitionEnd () {
      this.lastTranslate = this.$refs.qsswiper.$swiper.translate
      this.$refs.qsswiper.$swiper.allowTouchMove = true
    },
    transitionStart () {
      this.$refs.qsswiper.$swiper.allowTouchMove = false
    },
    slideNextTransitionEnd () {
      var _this = this
      if (_this.activeIndex == _this.data.length - 1) return
      _this.activeIndex++
      if (!_this.$refs.qsswiper.$swiper.allowSlidePrev) {
        _this.$refs.qsswiper.$swiper.allowSlidePrev = true
      }

      _this.currItem = _this.data[_this.activeIndex]
      if (_this.activeIndex >= (_this.data.length - 3) || (_this.data.length - 3 <= 0)) _this.getNextData()
      _this.initVideo()
      if (_this.$refs.qsswiper.$swiper.activeIndex == 2) {
        _this.$refs.qsswiper.$swiper.slideTo(1, 0, false)
      }
    },
    slidePrevTransitionEnd () {
      var _this = this
      _this.activeIndex--

      if (_this.activeIndex == 0) {
        _this.$refs.qsswiper.$swiper.allowSlidePrev = false
      }
      _this.currItem = _this.data[_this.activeIndex]
      _this.initVideo()
      if (_this.$refs.qsswiper.$swiper.activeIndex == 0) {
        _this.$refs.qsswiper.$swiper.slideTo(1, 0, false)
      }
    },
    shareVideo (videoItem) {
      this.$emit('share', videoItem)
    },
    async getNextData () {
      this.page++
      let res = await this.getNext(this.page, this.orderType)
      if (res.data.length > 0) {
        this.data = this.data.concat(res.data)
      } else {
        if (this.page == 1) return
        this.page = 0
        await this.getNextData()
      }
    },
    initVideo () {
      this.$refs.video.initVideo()
    },
    play () {
      this.$refs.video.play()
    },
    pause () {
      this.$refs.video.pause()
    }
  }
}
</script>

<style scoped>
  .tab-swiper, .video-swiper, .swiper-wrapper, .swiper-slide {
    height: 100%;
  }
</style>
