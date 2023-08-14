<template>
  <div id="app" class="my_app">
    <Meta
      v-if="base_info.companyname !== undefined"
      pagealias="companyshow"
      :custom_data="{
        companyname: base_info.companyname,
        content: base_info.content,
      }"
    />
    <Head>公司详情</Head>
    <div class="box_1">
      <div class="top">
        <div class="tx1">
          <div class="name">
            {{ base_info.companyname }}
            <!-- <span class="auth_ico" v-if="base_info.audit == 1"></span> -->
            <span v-if="base_info.setmeal_icon != ''"
              ><img :src="base_info.setmeal_icon"
            /></span>
          </div>
          <!-- <div class="auth_ico" v-if="base_info.audit == 1"></div>
          <div class="crw_ico" v-if="base_info.setmeal_icon != ''">
            <img :src="base_info.setmeal_icon" />
          </div> -->
          <div class="clear"></div>
        </div>
        <div class="tx2">
          <span v-if="base_info.nature_text !== ''">{{
            base_info.nature_text
          }}</span>
          <span v-if="base_info.scale_text !== ''">
            · {{ base_info.scale_text }}
          </span>
          <span v-if="base_info.trade_text !== ''">
            · {{ base_info.trade_text }}</span
          >
        </div>
        <!-- <div class="tx3">
          简历查看率：{{ watch_percent }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          企业粉丝：{{ fans }}
        </div> -->
        <div class="tx4">
          <span v-if="base_info.audit == 1" class="audit_icon"> 企业认证</span>
          <span v-if="report == 1" class="address_icon" @click="reportDetail"
            >实地认证</span
          >
        </div>
        <img
          :src="base_info.logo_src"
          :alt="base_info.companyname"
          class="logo"
        />
        <div
          class="collect"
          :class="has_attention == 1 ? 'active_collect' : ''"
          @click="doAttention"
        >
          <!-- {{ has_attention == 1 ? "已关注" : "关注" }} -->
        </div>
        <div class="share" @click="doShare"></div>
      </div>
      <!-- <div class="chat_bar" v-if="report == 1">
        该企业已通过实地认证
        <div class="right" @click="reportDetail">查看认证报告</div>
      </div> -->
      <div>
        <div class="box_nav">
          <div
            class="item"
            :class="comShow === 'job' ? 'active' : ''"
            @click="gettab('job')"
          >
            在招职位<span style="font-size:12px;">({{ jobnum }})</span>
          </div>
          <div
            class="item"
            :class="comShow === 'com' ? 'active' : ''"
            @click="gettab('com')"
          >
            公司介绍
          </div>
          <div
            class="item"
            :class="comShow === 'video' ? 'active' : ''"
            @click="gettab('video')"
            v-if="$store.state.config.shortvideo_enable === '1'"
          >
            视频<span style="font-size:12px;">({{ videonum }})</span>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="box_2" v-if="base_info.audit == 1">
      <div class="cell">企业认证</div>
      <div class="cell">多重审核</div>
      <div class="cell">信誉担保</div>
      <div class="cell">违规严惩</div>
      <div class="auth_ico">认证</div>
      <div class="bg_box"></div>
    </div> -->
    <!-- <div class="form_split_10"></div> -->

    <div class="box_com_some" v-if="comShow === 'com'">
      <div class="box_3" v-if="base_info.content">
        <div class="put"><span class="title">公司简介</span></div>
        <div class="content">
          <div
            :class="showContentMore ? 'text auto' : 'text'"
            ref="companyContent"
          >
            {{ base_info.content }}
            <div v-if="isMore && !showContentMore" class="test"></div>
          </div>
          <div
            class="more"
            v-if="isMore"
            @click="showContentMore = !showContentMore"
          >
            <div :class="showContentMore ? 'more_arrow up' : 'more_arrow'">
              {{ showContentMore ? "收起" : "展开" }}
            </div>
          </div>
        </div>
      </div>
      <div class="box_8" v-if="tag_text_arr.length != 0">
        <div class="put"><span class="title">公司福利</span></div>
        <div class="content">
          <div class="label-item" v-for="(item, index) in tag_text_arr" v-bind:key="index">{{item}}</div>
        </div>
      </div>
      <div class="box_4" v-if="base_info.address != ''">
        <div class="put" style="padding-top: 16.5px;">
          <span class="title">公司地址</span>
        </div>
        <div class="address">
          {{ base_info.address }}
        </div>
        <div class="bg"  v-if="$store.state.config.is_open_map == 1">
          <div :class="config.is_open_map == 1 && config.map_type == 2 ?  'box ac':'box'" @click="locationToBdmap">
            <div class="tx1">{{ base_info.companyname }}</div>
            <div class="tx2 substring">{{ base_info.address }}</div>
          </div>
        </div>
        <div class="distance" v-if="distance != '' && $store.state.config.is_open_map == 1">距您{{ distance }}</div>
      </div>
      <div class="box_9">
        <div class="put">
          <span class="title">企业动态</span>
          <span class="rz_time"
            >入驻{{ companySupplementary.reg_duration }}</span
          >
        </div>
        <div class="content">
          <p class="company_status">
            <span>{{ watch_percent }}</span>
            <span>企业简历查看率</span>
          </p>
          <p class="company_status company_status2">
            <span>{{ companySupplementary.last_login_time }}</span>
            <span>企业最近登录</span>
          </p>
          <div class="clear"></div>
        </div>
      </div>
      <div class="box_5" v-if="img_list.length > 0">
        <div class="put">
          <span class="title">企业风采</span>
        </div>
        <!-- <div class="content">
          <swiper
            :options="swiperOption"
            :class="swiperClass"
            v-if="$store.state.swiperLoaded"
          >
            <swiper-slide v-for="(item, index) in img_list" :key="index">
              <img :src="item.img_src" :alt="item.title" />
            </swiper-slide>
            <div
              class="swiper-pagination my_pagination"
              slot="pagination"
            ></div>
          </swiper>
        </div> -->
        <div class="content">
          <div class="swiper-duo">
            <div
              class="swiper-item"
              v-for="(item, index) in img_list"
              :key="index"
            >
              <img :src="item.img_src" alt="" @click="previewImg(index)"/>
            </div>
          </div>
        </div>
      </div>
      <div class="form_split_10"></div>
      <Subscribe></Subscribe>
    </div>
    <div class="box_job_some" v-if="comShow === 'job'">
      <van-empty
        description="该企业还没有发布职位"
        v-if="joblist.length == 0"
      />
      <van-list
        v-else
        v-model="loading"
        :finished="finished"
        finished-text="没有更多了"
        @load="onLoad"
        :immediate-check="false"
      >
        <div class="box_7">
          <div
            class="list"
            v-for="(item, index) in joblist"
            :key="index"
            @click="toDetail(item.id)"
          >
            <div class="self_content">
              <div class="tx1">
                <div class="name">{{ item.jobname }}</div>
                <div class="worry_ico" v-if="item.emergency == 1">急</div>
                <div class="clear"></div>
                <div class="wage">{{ item.wage_text }}</div>
              </div>
              <div class="tx2">
                {{ item.education_text }} · {{ item.experience_text }} ·
                {{ item.district_text }}
              </div>
              <div class="tag_wrapper clearfix" v-if="item.tag.length">
                <div class="tag_item" v-for="(tag, key) in item.tag" :key="key">
                  {{ tag }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </van-list>
    </div>
    <div class="box_video_some" v-if="comShow === 'video'">
      <VideoList
        :videotype="1"
        :id="query_id"
        :gointype="'unitplay'"
      ></VideoList>
    </div>

    <baidu-map
      v-if="config.is_open_map == 1 && config.map_type == 1"
      class="bm-view"
      :ak="$store.state.config.map_ak"
      @ready="handlerMap"
    ></baidu-map>
    <TianMap
      v-if="config.is_open_map == 1 && config.map_type == 2"
      class="bm-view"
      :ak="$store.state.config.tian_map_ak"
      @ready="handlerTianMap"
    >

    </TianMap>

    <van-popup
      v-model="showLogin"
      position="right"
      :overlay="false"
      style="width: 100%; height: 100%"
    >
      <Login
        :utype="2"
        :single_login="true"
        @afterLogin="afterLogin"
        :after_login_data="after_login_data"
      ></Login>
    </van-popup>
    <div class="alw-wx-layer" v-if="showWxLayer" @click="cancelShare"></div>
    <div class="alw-layer" v-if="showLayer" @click="cancelShare"></div>
    <SharePoster
      v-if="showPoster"
      @closePoster="closePoster"
      :type="'company'"
      :infoid="shareid"
    ></SharePoster>
    <van-overlay z-index="3" :show="showPoster" @click="showPoster = false" />
    <van-popup v-model="showShare" position="bottom">
      <Share
        @cancelShare="cancelShare"
        @handleForward="handleForward"
        @handlePoster="handlePoster"
      ></Share>
    </van-popup>
    <van-popup
      v-model="showReport"
      :lazy-render="false"
      position="right"
      :overlay="false"
      style="width: 100%; height: 100%"
    >
      <Report
        @closePopout="showReport = false"
        :report-info="reportInfo"
      ></Report>
    </van-popup>
    <!-- <float-ball
      @leftWidth="leftWidth"
      ref="floatBall"
      :itemWidth="itemWidth"
      :textAlign="textAlign"
    >
      <div
        ref="generate_posters"
        class="generate_posters"
        :class="putAway ? 'generate_posters_all' : ''"
      >
        <p class="poster_item" @click="handlerHomePage" v-if="!putAway">首页</p>
        <p class="poster_item hb_item" @click="handlePoster" v-if="!putAway">
          海报
        </p>
        <p class="poster_item all-item" @click="widthAuto" ref="allitem">
          <span
            class="all-item-img"
            :class="putAway ? 'all-item-img-qx' : ''"
          ></span>
        </p>
      </div>
    </float-ball> -->
    <div
      class="generate_posters"
      :class="putAway ? 'generate_posters_all' : ''"
    >
      <p class="poster_item" @click="handlerHomePage" v-if="!putAway">首页</p>
      <p class="poster_item hb_item" @click="handlePoster" v-if="!putAway">
        海报
      </p>
      <p class="poster_item all-item" @click="putAway = !putAway">
        <span
          class="all-item-img"
          :class="putAway ? 'all-item-img-qx' : ''"
        ></span>
      </p>
    </div>
  </div>
</template>

<script>
import VideoList from '../../views/shortvideo/components/VideoListtwo'
import Vue from 'vue'
import wxshare from '@/assets/js/share.js'
import Subscribe from '@/components/Subscribe'
import { countDistance } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import Login from '@/components/Login'
import Share from '@/components/share/Share'
import SharePoster from '@/components/share/SharePoster'
import Report from '@/components/Report'
import FloatBall from '@/components/floatBall'
import {mapState} from 'vuex'
import TianMap from '@/components/map/TianMap/TianMap'
import {ImagePreview} from 'vant'
Vue.use(ImagePreview)
let isSpider = new RegExp(
  '^(Baiduspider|YisouSpider|Sogou|Googlebot|Sosospider|bingbot|360Spider)'
).test(navigator.userAgent)
Vue.component('BaiduMap', function (resolve, reject) {
  if (!isSpider) {
    require(['vue-baidu-map/components/map/Map.vue'], resolve)
  }
})
export default {
  name: 'CompanyShow',
  components: {
    VideoList,
    Login,
    Subscribe,
    Share,
    SharePoster,
    Report,
    FloatBall,
    TianMap
  },
  data () {
    return {
      query_id: '',
      loading: false,
      finished: false,
      page: 1,
      pagesize: 10,
      comShow: 'job',
      showLogin: false,
      is_personal_login: false,
      base_info: {},
      img_list: [],
      field_rule: { basic: {}, contact: {}, info: {} },
      report: 0,
      fans: 0,
      watch_percent: '',
      has_attention: 0,
      joblist: [],
      distance: '',
      current_lat: 0,
      current_lng: 0,
      BMap: {},
      swiperOption: {
        pagination: {
          el: '.swiper-pagination'
        },
        autoplay: true,
        freeMode: true,
        speed: 600
      },
      swiperClass: 'reset_swiper',
      after_login_data: {},
      showContentMore: false,
      isMore: false,
      shareid: 0,
      showShare: false,
      showWxLayer: false,
      showLayer: false,
      showPoster: false,
      showReport: false,
      reportInfo: {},
      finished_text: '',
      jobnum: 0,
      videonum: 0,
      tag_text_arr: [],
      putAway: true,
      companySupplementary: {},
      itemWidth: 50,
      textAlign: 'right',
      TMap: {},
      previewImgList: [],
    }
  },
  created () {
    this.query_id = this.$route.params.id
    this.is_personal_login = !!(
      this.$store.state.LoginOrNot === true && this.$store.state.LoginType === 2
    )
    if (this.$route.query.type) {
      this.comShow = this.$route.query.type
    }
    // 请求数据
    this.fetchData()
    this.getCompanySupplementary()
    if (this.$store.state.config.shortvideo_enable === '1') {
      this.fetchVideonum()
    }
  },
  mounted () {},
  watch: {
  },
  computed: {
    ...mapState(['config'])
  },
  methods: {
    // 预览作品
    previewImg (index) {
      ImagePreview({
        images: this.previewImgList,
        showIndex: true,
        loop: false,
        startPosition: index,
        closeOnPopstate: true,
        closeable: true
      })
    },
    widthAuto () {
      this.putAway = !this.putAway
      // this.itemWidth = 150;
      // this.textAlign = "left";
    },
    leftWidth (val) {
      if (val == 10) {
        this.textAlign = 'left'
        this.$refs.generate_posters.style.paddingLeft = 45 + 'px'
        this.$refs.allitem.style.left = 0
      } else {
        this.textAlign = 'right'
        this.$refs.generate_posters.style.paddingLeft = 0
        this.$refs.allitem.style.right = 0
        this.$refs.allitem.style.left = 'inherit'
      }
    },
    gettab (comShow) {
      this.comShow = comShow
      this.page = 1
      if (comShow === 'com' && !this.isMore) {
        this.$nextTick(function () {
          let textHeight = this.$refs.companyContent.scrollHeight
          let styleHeight = this.$refs.companyContent.clientHeight
          this.isMore = textHeight > styleHeight
        })
      }
    },
    handlerHomePage () {
      this.$router.push('/index')
    },
    // 一键复制
    async handlerCopy () {
      let that = this
      console.log('复制')
      //       let copy = () => {
      //         let copyMessage = `${this.com_info.companyname}
      // 招聘：${this.base_info.jobname}
      // 要求：工作经验${this.base_info.experience_text}、学历要求${this.base_info.education_text}
      // 工资：${this.base_info.wage_text}
      // 查看联系方式：${this.shortUrl}
      // -招聘求职就上${this.$store.state.config.sitename}-`
      //         this.$copyText(copyMessage).then(function (e) {
      //           that.$notify({type: 'success', message: '内容已复制到剪切板！'})
      //         }, function (e) {
      //           that.$notify({type: 'error', message: '抱歉，复制失败！'})
      //         })
      //       }
      //       if (!this.shortUrl) {
      //         const params = {
      //           jobId: this.query_id
      //         }
      //         let res = await http.get('/home/short_url/genJobShow', params)
      //         if (res.code == 200) {
      //           this.shortUrl = res.data
      //           copy()
      //         }
      //       } else {
      //         copy()
      //       }
    },
    handlerTianMap ({TMap}) {
      this.TMap = TMap
    },
    handlerMap ({ BMap, map }) {
      this.BMap = BMap
    },
    getTianMapPosition (mapLat, mapLng) {
      if (!this.TMap || this.TMap.Geolocation === undefined) {
        return
      }
      const {TMap} = this
      const that = this
      var geolocation = new TMap.Geolocation()
      geolocation.getCurrentPosition(function (r) {
        if (this.getStatus() == 0) {
          that.current_lat = r.lnglat.lat
          that.current_lng = r.lnglat.lng
          if (
            that.current_lat > 0 &&
              that.current_lng > 0 &&
              mapLat > 0 &&
              mapLng > 0
          ) {
            that.distance = countDistance(
              that.current_lat,
              that.current_lng,
              mapLat,
              mapLng
            )
          }
        }
      }, { enableHighAccuracy: true })
    },
    getPosition (mapLat, mapLng) {
      if (!this.BMap || this.BMap.Geolocation === undefined) {
        return
      }
      let BMap = this.BMap
      let that = this
      var geolocation = new BMap.Geolocation()
      geolocation.getCurrentPosition(
        function (r) {
          if (this.getStatus() === BMAP_STATUS_SUCCESS) {
            that.current_lat = r.point.lat
            that.current_lng = r.point.lng
            if (
              that.current_lat > 0 &&
              that.current_lng > 0 &&
              mapLat > 0 &&
              mapLng > 0
            ) {
              that.distance = countDistance(
                that.current_lat,
                that.current_lng,
                mapLat,
                mapLng
              )
            }
          }
        },
        { enableHighAccuracy: true }
      )
    },
    getJoblist () {
      http
        .get(api.joblist, {
          company_id: this.query_id,
          pagesize: this.pagesize,
          count_total: 1
        })
        .then((res) => {
          this.page++
          this.joblist = [...res.data.items]
          this.jobnum = res.data.total
        })
        .catch(() => {})
    },
    async fetchData (next_method = null) {
      const params = {
        id: this.query_id
      }
      let res = await http.get(api.companyshow, params)
      const {
        base_info,
        field_rule,
        img_list,
        report,
        fans,
        watch_percent,
        has_attention
      } = { ...res.data }
      this.field_rule = field_rule
      this.base_info = base_info
      // console.log(this.base_info)
      this.img_list = img_list
      this.report = report
      this.fans = fans
      this.watch_percent = watch_percent
      this.has_attention = has_attention
      this.tag_text_arr = base_info.tag_text_arr
      let wechatShareInfo = {
        companyname: base_info.companyname,
        district: base_info.district_text,
        nature: base_info.nature_text,
        trade: base_info.trade_text,
        imgUrl: base_info.logo_src
      }
      wxshare(wechatShareInfo, 'companyshow', location.href)
      if (this.config.is_open_map == 1) {
        if (this.config.map_type == 1) {
          this.getPosition(this.base_info.map_lat, this.base_info.map_lng)
        } else if (this.config.map_type == 2) {
          this.getTianMapPosition(this.base_info.map_lat, this.base_info.map_lng)
        }
      }
      if (next_method !== null) {
        this[next_method]()
      } else {
        this.getJoblist()
      }
      this.previewImgList = this.img_list.map(function (item) {
        return item.img_src
      })
    },
    getCompanySupplementary () {
      http
        .get(api.companySupplementary, { id: this.query_id })
        .then((res) => {
          let { code, data } = res
          this.companySupplementary = data
        })
        .catch(() => {})
    },
    onLoad () {
      if (this.comShow === 'video') {
      } else {
        http
          .get(api.joblist, {
            company_id: this.query_id,
            page: this.page,
            pagesize: this.pagesize
          })
          .then((res) => {
            for (let i = 0; i < res.data.items.length; i++) {
              let item = { ...res.data.items[i] }
              if (this.page != 1) {
                this.joblist.push(item)
              }
            }
            this.page++
            // 加载状态结束
            this.loading = false
            // 数据全部加载完成
            if (res.data.items.length === 0) {
              this.finished = true
            }
          })
      }
    },
    toDetail (id) {
      this.$router.push('/job/' + id)
    },
    doAttention () {
      if (this.is_personal_login === false) {
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
      } else {
        const params = {
          comid: this.query_id
        }
        let _api_url =
          this.has_attention === 1
            ? api.company_attention_cancel
            : api.company_attention
        http
          .post(_api_url, params)
          .then((res) => {
            this.has_attention = this.has_attention === 1 ? 0 : 1
            this.$notify({ type: 'success', message: res.message })
          })
          .catch(() => {})
      }
    },
    doShare () {
      this.showShare = true
    },
    cancelShare () {
      this.showShare = false
      this.showWxLayer = false
      this.showLayer = false
    },
    handleForward () {
      const agent = navigator.userAgent.toLowerCase()
      if (agent.indexOf('micromessenger') < 0) {
        setTimeout(() => {
          this.showLayer = true
        }, 150)
      } else {
        setTimeout(() => {
          this.showWxLayer = true
        }, 150)
      }
    },
    handlePoster () {
      this.shareid = this.query_id
      this.showPoster = true
    },
    closePoster () {
      this.showPoster = false
    },
    afterLogin (data) {
      this.showLogin = false
      this.is_personal_login = true
      let method = null
      if (data.method !== undefined) {
        method = data.method
      }
      this.fetchData(method)
    },
    locationToBdmap () {
      if (this.config.is_open_map == 1 && this.config.map_type == 1) {
        if (!this.base_info.map_lat || !this.base_info.map_lng) {
          return false
        }
        let url =
        'http://api.map.baidu.com/marker?location=' +
        this.base_info.map_lat +
        ',' +
        this.base_info.map_lng +
        '&title=' +
        this.base_info.companyname +
        '&content=' +
        this.base_info.address +
        '&output=html'
        window.location.href = url
      } else {
        // this.$notify('暂不支持查看')
      }
    },
    reportDetail () {
      http
        .get(api.company_report, { id: this.base_info.id })
        .then((res) => {
          this.reportInfo = {
            companyname: this.base_info.companyname,
            evaluation: res.data.evaluation,
            certifier: res.data.certifier,
            addtime: res.data.addtime
          }
          this.showReport = true
        })
        .catch(() => {})
    },
    fetchVideonum () {
      http
        .get(api.shortvideo_total, { comid: this.query_id })
        .then((res) => {
          this.videonum = res.data
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.generate_posters {
  position: fixed;
  z-index: 1;
  right: 15px;
  bottom: 80px;
  background: #e0eeff;
  border-radius: 45px;
  width: 154px;
  height: 45px;
  transition: width 0.5s;
  -moz-transition: width 0.5s; /* Firefox 4 */
  -webkit-transition: width 0.5s; /* Safari and Chrome */
  -o-transition: width 0.5s; /* Opera */
  line-height: 58px;
  padding-top: 0;
  padding-left: 10px;
  // position: relative;
  // display: inline-block;
  .poster_item {
    z-index: 2;
    width: 33%;
    height: 100%;
    font-size: 12px;
    text-align: center;
    border-radius: 45px;
    float: left;
    background: url(../../assets/images/company/index_rev.png) center 8px
      no-repeat;
    background-size: 14px;
    color: #595959;
    overflow: hidden;
    box-sizing: border-box;
    padding-top: 2px;
    span {
      display: inline-block;
      width: 100%;
      text-align: center;
      font-size: 12px;
      color: #595959;
    }
  }
  .copy_item {
    background: url(../../assets/images/company/copy_icon.png) 18px 8px
      no-repeat;
    background-size: 14px;
  }
  .hb_item {
    background: url(../../assets/images/company/hb_share.png) 18px 8px no-repeat;
    background-size: 14px;
  }
  .all-item {
    position: absolute;
    right: 0;
    top: 0;
    background: linear-gradient(to bottom, #2185ff, #0f72eb);
    box-shadow: 0 5px 5px rgba(19, 118, 240, 0.3);
    font-size: 20px;
    text-align: center;
    width: 45px;
    height: 45px;
    line-height: 45px;
    color: #ffffff;
    .all-item-img {
      display: inline-block;
      width: 25px;
      height: 25px;
      background: url("../../assets/images/company/qx.png") 0 no-repeat;
      background-size: 100% 100%;
      margin-top: 8px;
    }
    .all-item-img-qx {
      width: 25px;
      height: 25px;
      background: url("../../assets/images/company/more_icon.png") 0 no-repeat;
      background-size: 100% 100%;
      margin-top: 7px;
    }
  }
}
.generate_posters_all {
  width: 45px;
}
.box_7 {
  .list {
    &:not(:last-child) {
      .self_content {
        border-bottom: 1px solid #f3f3f3;
      }
    }
    .self_content {
      width: 100%;
    }
    .tag_wrapper {
      .tag_item {
        float: left;
        font-size: 10px;
        color: #8096a3;
        background-color: #e9f8ff;
        border-radius: 3px;
        padding: 3px 8px;
        margin-bottom: 5px;
        &:not(:last-child) {
          margin-right: 5px;
        }
      }
      width: 100%;
      padding-bottom: 10px;
    }
    .tx2 {
      .time {
        position: absolute;
        right: 0;
        top: 15px;
        font-size: 13px;
        color: #999999;
      }
      font-size: 14px;
      color: #666666;
      padding: 0 60px 0 0;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      position: relative;
      margin-bottom: 10px;
    }
    .tx1 {
      margin-bottom: 10px;
      .wage {
        position: absolute;
        right: 0;
        top: 19px;
        font-size: 14px;
        color: #ff5d24;
        font-weight: bold;
      }
      .worry_ico {
        font-size: 10px;
        color: #ffffff;
        margin-top: 4px;
        padding: 1px 3px;
        border-radius: 3px;
        background-color: #ff8b82;
        float: left;
      }
      .name {
        font-size: 16px;
        color: #333333;
        font-weight: bold;
        max-width: 240px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        float: left;
        margin-right: 6px;
      }
      position: relative;
      padding-top: 17px;
    }
    position: relative;
    width: 100%;
    background-color: #ffffff;
    padding: 0 17px;
    overflow: hidden;
  }
  width: 100%;
}
.box_follow {
  .van-button {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translate(0, -50%);
  }
  .tx2 {
    max-width: 210px;
    font-size: 13px;
    color: #999999;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  .tx1 {
    margin-bottom: 6px;
    font-size: 17px;
    font-weight: bold;
    color: #333333;
    max-width: 210px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  .logo {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translate(0, -50%);
    max-width: 45px;
    max-height: 45px;
    border: 0;
  }
  position: relative;
  width: 100%;
  background-color: #ffffff;
  padding: 13.5px 0 15px 73px;
}
.box_6 {
  .content {
    .line {
      font-size: 14px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      &:not(:first-child) {
        margin-top: 5px;
      }
    }
    &.auto {
      height: auto;
    }
    .more {
      .more_arrow {
        &::after {
          position: absolute;
          right: 3px;
          top: 15px;
          width: 6px;
          height: 6px;
          border-bottom: 1px solid #999999;
          border-right: 1px solid #999999;
          transform: rotate(45deg);
          content: " ";
        }
        width: 50px;
        margin: 0 auto;
        padding: 10.5px 18px 10.5px 0;
        font-size: 13px;
        color: #999999;
        position: relative;
      }
      width: 100%;
      background-color: #ffffff;
      text-align: center;
    }
    font-size: 14px;
    color: #666666;
    position: relative;
    overflow: hidden;
    word-break: break-all;
  }
  width: 100%;
  background-color: #ffffff;
  padding: 0 16px;
}
.box_5 {
  .content {
    img {
      height: 177px;
      width: 100%;
    }
    width: 100%;
    padding-bottom: 5px;
    .reset_swiper {
      padding-bottom: 10px;
    }
    .my_pagination {
      bottom: -7px;
    }
    .swiper-duo {
      overflow-x: auto;
      overflow-y: hidden;
      height: 90px;
      width: 100%;
      white-space: nowrap;
      .swiper-item {
        display: inline-block;
        width: 130px;
        height: 90px;
        margin-right: 10px;
        img {
          display: inline-block;
          width: 100%;
          height: 100%;
          border-radius: 3px;
        }
      }
      .swiper-item:last-child {
        margin-right: 0;
      }
    }
  }
  width: 100%;
  background-color: #ffffff;
  padding: 0 16px 16px;
}
.box_4 {
  .bg {
    .box {
      &::after {
        position: absolute;
        right: 22px;
        top: 34px;
        width: 8px;
        height: 8px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      &.ac::after{
        display: none;
      }
      .tx2 {
        width: 80%;
        font-size: 12px;
        color: #595959;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .tx1 {
        width: 80%;
        font-size: 14px;
        font-weight: bold;
        color: #222222;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      width: 270px;
      height: 84px;
      margin: 26px auto 0;
      position: relative;
      border-radius: 1.413333rem;
      background: url("../../assets/images/company/tc.png") 0.133333rem center no-repeat;
      background-size: 100% 100%;
      padding: 20px 0 0 71px;
      box-sizing: border-box;
    }
    width: 100%;
    height: 140px;
    background: url("../../assets/images/company/dtbj.png") 0 no-repeat;
    background-size: 100% 140px;
    padding-top: 1%;
  }
  .distance {
    font-size: 12px;
    color: #595959;
    width: 100%;
    box-sizing: border-box;
    padding-left: 15px;
    background: url("../../assets/images/company/address_icon2.png") 0 5px
      no-repeat;
    background-size: 12px;
    height: 25px;
    line-height: 22px;
    margin-top: 8px;
  }
  .address {
    line-height: 1.8;
    font-size: 14px;
    color: #595959;
    word-break: break-all;
    margin-bottom: 8px;
    // background: url("../../assets/images/address_ico_blue.svg") 0 5px no-repeat;
    // background-size: 13px;
  }
  width: 100%;
  background-color: #ffffff;
  padding: 0 16px;
  margin-bottom: 5px;
}
.box_3 {
  .content {
    .more {
      .more_arrow {
        &.up {
          &::after {
            position: absolute;
            right: 5px;
            top: 19px;
            width: 6px;
            height: 6px;
            border-bottom: 0;
            border-right: 0;
            border-left: 1px solid #999999;
            border-top: 1px solid #999999;
            transform: rotate(45deg);
            content: " ";
          }
        }
        &::after {
          position: absolute;
          right: 5px;
          top: 15px;
          width: 6px;
          height: 6px;
          border-left: 0;
          border-top: 0;
          border-bottom: 1px solid #999999;
          border-right: 1px solid #999999;
          transform: rotate(45deg);
          content: " ";
        }
        width: 70px;
        margin: 0 auto;
        padding: 8.5px 18px 8.5px 0;
        font-size: 13px;
        color: #999999;
        position: relative;
      }
      width: 100%;
      background-color: #ffffff;
      text-align: center;
    }
    .text {
      position: relative;
      // white-space: pre-line;
      max-height: 240px;
      overflow: hidden;
      &.auto {
        max-height: none;
      }
      .test {
        background: linear-gradient(
          top,
          rgba(255, 255, 255, 0),
          rgba(255, 255, 255, 1)
        );
        background: -webkit-gradient(
          linear,
          0 0,
          0 100%,
          from(rgba(255, 255, 255, 0)),
          to(rgba(255, 255, 255, 1))
        );
        background: -moz-linear-gradient(
          top,
          rgba(255, 255, 255, 0),
          rgba(255, 255, 255, 1)
        );
        background: -o-linear-gradient(
          top,
          rgba(255, 255, 255, 0),
          rgba(255, 255, 255, 1)
        );
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#0ff, endColorstr=#fff, GradientType=0);
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 150px;
      }
    }
    font-size: 14px;
    color: #666666;
    line-height: 1.7;
    position: relative;
    overflow: hidden;
    word-break: break-all;
  }
  width: 100%;
  background-color: #ffffff;
  padding: 0 16px;
}
.box_9 {
  width: 100%;
  background-color: #ffffff;
  padding: 0 16px 21.5px;
  .content {
    padding: 0 30px 0;
    .company_status {
      position: relative;
      text-align: center;
      background: #f8f9fa;
      width: 44%;
      padding: 10px 0;
      border-radius: 5px;
      float: left;
      span {
        display: block;
        font-size: 17px;
        font-weight: bold;
        color: #167EFF;
        margin-bottom: 4.5px;
      }
      span:nth-child(2) {
        font-weight: normal;
        font-size: 12px;
        color: #999999;
      }
      &:not(:last-child)::after {
        content: " ";
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        width: 1px;
        height: 25px;
        background-color: #f3f3f3;
      }
    }

    .company_status2 {
      float: right;
    }
  }
  .put .rz_time {
    font-size: 12px;
    color: #999999;
    float: right;
    font-weight: normal;
    margin-top: 6px;
  }
}
.box_8 {
  width: 100%;
  background-color: #ffffff;
  padding: 0 16px;
  .content {
    line-height: 14px;
    overflow: hidden;
    .label-item{
      float: left;
      font-size: 13px;
      color: #595959;
      background-color: #f1f1f1;
      padding: 5px 8px;
      border-radius: 3px;
      margin: 0 10px 10px 0;
    }
  }
}
.put {
  padding: 21.5px 0;
  font-size: 16px;
  font-weight: bold;
  color: #333333;
  position: relative;
  .title {
    background: url("../../assets/images/company/border_bg.png") 0 bottom
      no-repeat;
    background-size: 100% 3px;
  }
  .right_arrow {
    &::after {
      position: absolute;
      right: 1px;
      top: 6px;
      width: 6px;
      height: 6px;
      border-top: 1px solid #1787fb;
      border-right: 1px solid #1787fb;
      transform: rotate(45deg);
      content: " ";
    }
    position: absolute;
    right: 0;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 12px;
    color: #1787fb;
    padding-right: 10px;
  }
  .right_text {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 12px;
    color: #c9c9c9;
  }
}
.box_nav {
  .item {
    &.active {
      &::after {
        content: " ";
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translate(-50%, 0);
        width: 18px;
        height: 2.5px;
        background-color: #1787fb;
        border-radius: 3px;
      }
      color: #1787fb;
    }
    flex: 1;
    position: relative;
    padding: 15px 0;
    text-align: center;
    font-size: 17px;
    font-weight: bold;
    color: #333333;
  }
  width: 100%;
  background-color: #ffffff;
  display: flex;
  box-shadow: 0 1px 5px #f1eded;
  z-index: 1;
  position: relative;
  border-radius: 21px 21px 0 0;
}
.box_2 {
  .bg_box {
    position: absolute;
    left: -20px;
    top: 2px;
    width: 50px;
    height: 100%;
    background: linear-gradient(to right, #3b9dfe, #6fc9ff);
    z-index: 1;
    transform: rotate(35deg);
  }
  .auth_ico {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translate(0, -50%);
    padding-top: 13px;
    font-size: 10px;
    color: #ffffff;
    z-index: 2;
    background: url("../../assets/images/job_show_auth_ico.png") center 0
      no-repeat;
    background-size: 13px;
  }
  .cell {
    float: left;
    font-size: 12px;
    color: #5f82a7;
    padding: 9px 15px 9px 17px;
    background: url("../../assets/images/done_ico_blue.svg") 0 center no-repeat;
    background-size: 12px;
    &:last-child {
      padding-right: 0;
    }
  }
  position: relative;
  width: 100%;
  background-color: #eef9ff;
  overflow: hidden;
  padding-left: 50px;
}
.box_1 {
  background: url("../../assets/images/company/bj.png") no-repeat;
  background-size: 100%;
  .chat_bar {
    .right {
      &::after {
        position: absolute;
        right: 2px;
        top: 5.5px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #1787fb;
        border-right: 1px solid #1787fb;
        transform: rotate(45deg);
        content: " ";
      }
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translate(0, -50%);
      font-size: 12px;
      color: #1787fb;
      padding-right: 11px;
    }
    position: relative;
    font-size: 12px;
    color: #999999;
    padding: 10.5px 0 10.5px 16px;
    border-top: 1px solid #f5f5f5;
  }
  .top {
    .share {
      position: absolute;
      right: 15px;
      top: 90px;
      background: url("../../assets/images/company/share.png") 0 3px no-repeat;
      background-size: 15px;
      width: 20px;
      height: 20px;
    }
    .collect {
      position: absolute;
      right: 55px;
      top: 90px;
      background: url("../../assets/images/company/like.png") 0 3px no-repeat;
      background-size: 15px;
      width: 20px;
      height: 20px;
    }
    .active_collect {
      background: url("../../assets/images/company/like_act.png") 0 3px
        no-repeat;
      background-size: 15px;
    }
    .logo {
      position: absolute;
      top: 25px;
      right: 21px;
      width: 55px;
      height: 55px;
      border-radius: 6px;
      // box-shadow: 0 0 5px #c2c2c2;
    }
    .tx3 {
      font-size: 12px;
      color: #999999;
    }
    .tx4 {
      line-height: 17px;
      span {
        color: #fba506;
        border: 1px solid;
        font-size: 12px;
        border-radius: 2px;
        width: 75px;
        display: inline-block;
        text-align: center;
        padding: 1px 0 1px 12px;
        box-sizing: border-box;
        background: url("../../assets/images/company/audit_icon.png") 5px center
          no-repeat;
        background-size: 12px;
      }
      .address_icon {
        border-color: #34ba86;
        color: #34ba86;
        background: url("../../assets/images/company/address_icon.png") 5px 4px
          no-repeat;
        background-size: 12px;
      }
    }
    .tx2 {
      margin-bottom: 7px;
      max-width: 260px;
      font-size: 13px;
      color: #dcdcdc;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .tx1 {
      margin-bottom: 9px;
      .crw_ico {
        float: left;
        margin-left: 6px;
        width: 14px;
        height: 27px;
        position: relative;
        img {
          position: absolute;
          left: 0;
          top: 7px;
          width: 14px;
          height: 12px;
          border: 0;
        }
      }
      .auth_ico {
        float: left;
        margin-left: 6px;
        width: 15px;
        height: 27px;
        background: url("../../assets/images/jobs_list_auth_ico.png") 0 center
          no-repeat;
        background-size: 15px 11px;
      }
      .name {
        float: left;
        font-size: 20px;
        font-weight: bold;
        color: #fff;
        max-width: 265px;
        overflow: hidden;
        // white-space: nowrap;
        // text-overflow: ellipsis;
        // letter-spacing: 1px;
        span {
          vertical-align: middle;
          img {
            display: inline-block;
            width: 19px;
          }
        }
      }
    }
    position: relative;
    padding: 25px 21px;
  }
  width: 100%;
  background-color: #ffffff;
  border-top: 1px solid #f3f3f3;
}
</style>
