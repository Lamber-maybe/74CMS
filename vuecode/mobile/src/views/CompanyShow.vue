<template>
  <div id="app" class="my_app">
    <Meta v-if="base_info.companyname!==undefined" pagealias="companyshow" :custom_data="{companyname:base_info.companyname,content:base_info.content}" />
    <Head>公司详情</Head>
    <div class="box_1">
      <div class="top">
        <div class="tx1">
          <div class="name">{{ base_info.companyname }}</div>
          <div class="auth_ico" v-if="base_info.audit == 1"></div>
          <div class="crw_ico" v-if="base_info.setmeal_icon!=''">
            <img :src="base_info.setmeal_icon" />
          </div>
          <div class="clear"></div>
        </div>
        <div class="tx2">
          <span v-if="base_info.nature_text !== ''">{{ base_info.nature_text }}</span>
          <span v-if="base_info.scale_text !== ''"> · {{ base_info.scale_text }} </span>
          <span v-if="base_info.trade_text !== ''"> · {{ base_info.trade_text }}</span>
        </div>
        <div class="tx3">
          简历查看率：{{ watch_percent }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          企业粉丝：{{ fans }}
        </div>
        <img
          :src="base_info.logo_src"
          :alt="base_info.companyname"
          class="logo"
        />
        <div class="collect" @click="doAttention">
          {{ has_attention == 1 ? "已关注" : "关注" }}
        </div>
        <div class="share" @click="doShare">分享</div>
      </div>
      <div class="chat_bar" v-if="report == 1">
        该企业已通过实地认证
        <div class="right" @click="reportDetail">查看认证报告</div>
      </div>
    </div>
    <div class="box_2" v-if="base_info.audit == 1">
      <div class="cell">企业认证</div>
      <div class="cell">多重审核</div>
      <div class="cell">信誉担保</div>
      <div class="cell">违规严惩</div>
      <div class="auth_ico">认证</div>
      <div class="bg_box"></div>
    </div>
    <div class="form_split_10"></div>
    <div class="box_nav">
      <div
        class="item "
        :class="comShow === true ? 'active' : ''"
        @click="comShow = !comShow"
      >
        公司介绍
      </div>
      <div
        class="item "
        :class="comShow === false ? 'active' : ''"
        @click="comShow = !comShow"
      >
        在招职位
      </div>
    </div>
    <div class="box_com_some" v-if="comShow">
      <div class="box_3">
        <div class="put">公司简介</div>
        <div class="content">
          <div :class="showContentMore ? 'text auto' : 'text'" ref="companyContent">{{ base_info.content }}</div>
          <div class="more" v-if="isMore" @click="showContentMore = !showContentMore">
            <div :class="showContentMore ? 'more_arrow up' : 'more_arrow'">
              {{ showContentMore ? '收起信息' : '展开信息' }}
            </div>
          </div>
        </div>
      </div>
      <div class="box_4" v-if="base_info.address != ''">
        <div class="put">
          公司地址
          <div class="distance" v-if="distance != ''">距您{{ distance }}</div>
        </div>
        <div class="address">
          {{ base_info.address }}
        </div>
        <div class="bg">
          <div class="box" @click="locationToBdmap">
            <div class="tx1">{{ base_info.companyname }}</div>
            <div class="tx2">{{ base_info.address }}</div>
          </div>
        </div>
      </div>
      <div class="box_5" v-if="img_list.length > 0">
        <div class="put">公司风采</div>
        <div class="content">
          <swiper :options="swiperOption" :class="swiperClass">
            <swiper-slide v-for="(item, index) in img_list" :key="index">
              <img :src="item.img_src" :alt="item.title" />
            </swiper-slide>
            <div
              class="swiper-pagination my_pagination"
              slot="pagination"
            ></div>
          </swiper>
        </div>
      </div>
      <div class="form_split_10"></div>
      <Subscribe></Subscribe>
      <div class="form_split_10"></div>
    </div>
    <div class="box_job_some" v-if="!comShow">
      <van-empty description="该企业还没有发布职位" v-if="joblist.length==0" />
      <van-list
        v-else
        v-model="loading"
        :finished="finished"
        finished-text="没有更多了"
        @load="onLoad"
        :immediate-check="false"
      >
        <div class="box_7">
          <div class="list" v-for="(item,index) in joblist" :key="index" @click="toDetail(item.id)">
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
                <div class="tag_item"
                     v-for="(tag, key) in item.tag"
                     :key="key">{{ tag }}</div>
              </div>
            </div>
          </div>
        </div>
      </van-list>
    </div>
    <baidu-map
      class="bm-view"
      :ak="$store.state.config.map_ak"
      @ready="handlerMap"
    ></baidu-map>
    <van-popup
      v-model="showLogin"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
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
    <SharePoster v-if="showPoster" @closePoster="closePoster" :type="'company'" :infoid="shareid"></SharePoster>
    <van-overlay z-index="3" :show="showPoster" @click="showPoster=false"/>
    <van-popup v-model="showShare" position="bottom">
      <Share @cancelShare="cancelShare"
              @handleForward="handleForward"
              @handlePoster="handlePoster"></Share>
    </van-popup>
    <van-popup
      v-model="showReport"
      :lazy-render="false"
      position="right"
      :overlay="false"
      style="width:100%;height:100%">
      <Report @closePopout="showReport=false" :report-info="reportInfo"></Report>
    </van-popup>
    <div class="generate_posters" @click="handlePoster">生成<br />海报</div>
  </div>
</template>

<script>
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
let isSpider = new RegExp('^(Baiduspider|YisouSpider|Sogou|Googlebot|Sosospider|bingbot|360Spider)').test(navigator.userAgent)
Vue.component('BaiduMap', function (resolve, reject) {
  if (!isSpider) {
    require(['vue-baidu-map/components/map/Map.vue'], resolve)
  }
})
export default {
  name: 'CompanyShow',
  components: {
    Login,
    Subscribe,
    Share,
    SharePoster,
    Report
  },
  data () {
    return {
      query_id: '',
      loading: false,
      finished: false,
      page: 1,
      pagesize: 10,
      comShow: true,
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
      reportInfo: {}
    }
  },
  created () {
    this.query_id = this.$route.params.id
    this.is_personal_login =
      !!(this.$store.state.LoginOrNot === true && this.$store.state.LoginType === 2)
    // 请求数据
    this.fetchData()
  },
  mounted () {},
  watch: {
    // 数据渲染完成之后获取企业介绍的高度
    'base_info': function () {
      this.$nextTick(function () {
        let textHeight = this.$refs.companyContent.scrollHeight
        let styleHeight = this.$refs.companyContent.clientHeight
        this.isMore = textHeight > styleHeight
      })
    }
  },
  methods: {
    handlerMap ({ BMap, map }) {
      this.BMap = BMap
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
          pagesize: this.pagesize
        })
        .then(res => {
          this.page++
          this.joblist = [...res.data.items]
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
      this.img_list = img_list
      this.report = report
      this.fans = fans
      this.watch_percent = watch_percent
      this.has_attention = has_attention
      let wechatShareInfo = {
        companyname: base_info.companyname,
        district: base_info.district_text,
        nature: base_info.nature_text,
        trade: base_info.trade_text,
        imgUrl: base_info.logo_src
      }
      wxshare(wechatShareInfo, 'companyshow', location.href)
      this.getPosition(this.base_info.map_lat, this.base_info.map_lng)
      if (next_method !== null) {
        this[next_method]()
      } else {
        this.getJoblist()
      }
    },
    onLoad () {
      http
        .get(api.joblist, {
          company_id: this.query_id,
          page: this.page,
          pagesize: this.pagesize
        })
        .then(res => {
          for (let i = 0; i < res.data.items.length; i++) {
            let item = { ...res.data.items[i] }
            this.joblist.push(item)
          }
          this.page++
          // 加载状态结束
          this.loading = false
          // 数据全部加载完成
          if (res.data.items.length === 0) {
            this.finished = true
          }
        })
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
          .then(res => {
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
    },
    reportDetail () {
      http.get(api.company_report, {id: this.base_info.id}).then(res => {
        this.reportInfo = {
          companyname: this.base_info.companyname,
          evaluation: res.data.evaluation,
          certifier: res.data.certifier,
          addtime: res.data.addtime
        }
        this.showReport = true
      }).catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
  .generate_posters {
    position: fixed;z-index: 1;width: 41px;height: 41px;border-radius: 999px;background-color: rgba(0,0,0,0.7);
    right: 15px;bottom: 100px;font-size: 12px;color: #ffffff;line-height: 14px;text-align: center;padding-top: 7px;
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
      .tag_item{
        float: left;font-size: 10px;color: #8096a3;background-color: #e9f8ff;border-radius: 3px;padding: 3px 8px;
        margin-bottom: 5px;
        &:not(:last-child){margin-right: 5px;}
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
        width: 70px;
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
  }
  width: 100%;
  background-color: #ffffff;
  padding: 0 16px;
}
.box_4 {
  .bg {
    .box {
      &::after {
        position: absolute;
        right: 17px;
        top: 21px;
        width: 8px;
        height: 8px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      .tx2 {
        font-size: 12px;
        color: #767676;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .tx1 {
        font-size: 14px;
        font-weight: bold;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 1.5px;
      }
      width: 285px;
      margin: 0 auto;
      padding: 6.5px 40px 7px 50px;
      position: relative;
      border-radius: 53px;
      background: #ffffff url("../assets/images/job_show_add_ico.png") 5px
        center no-repeat;
      background-size: 40.5px;
    }
    width: 100%;
    height: 81px;
    background: url("../assets/images/job_show_address_bg.png") 0 no-repeat;
    background-size: 100% 81px;
    padding-top: 14px;
  }
  .address {
    padding-left: 20px;
    line-height: 1.8;
    font-size: 14px;
    color: #767676;
    word-break: break-all;
    margin-bottom: 8px;
    background: url("../assets/images/address_ico_blue.svg") 0 5px no-repeat;
    background-size: 13px;
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
        &.up{
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
    .text{
      white-space:pre-line;max-height: 100px;overflow: hidden;padding-bottom: 10px;
      &.auto{max-height: none}
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
.put {
  padding: 16.5px 0;
  font-size: 18px;
  font-weight: bold;
  color: #333333;
  position: relative;
  .distance {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 12px;
    color: #1787fb;
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
        bottom: 7px;
        left: 50%;
        transform: translate(-50%, 0);
        width: 17px;
        height: 3px;
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
    background: url("../assets/images/job_show_auth_ico.png") center 0 no-repeat;
    background-size: 13px;
  }
  .cell {
    float: left;
    font-size: 12px;
    color: #5f82a7;
    padding: 9px 15px 9px 17px;
    background: url("../assets/images/done_ico_blue.svg") 0 center no-repeat;
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
      right: 16px;
      bottom: 17.5px;
      font-size: 12px;
      color: #999999;
      background: url("../assets/images/com_show_share_ico.png") 0 3px
        no-repeat;
      background-size: 12px;
      padding-left: 15px;
    }
    .collect {
      position: absolute;
      right: 70px;
      bottom: 17.5px;
      font-size: 12px;
      color: #999999;
      background: url("../assets/images/com_show_col_ico.png") 0 3px
        no-repeat;
      background-size: 12px;
      padding-left: 15px;
    }
    .logo {
      position: absolute;
      top: 10px;
      right: 33px;
      width: 55px;
      height: 55px;
      border-radius: 7px;
      box-shadow: 0 0 5px #c2c2c2;
    }
    .tx3 {
      font-size: 12px;
      color: #999999;
    }
    .tx2 {
      margin-bottom: 7px;
      max-width: 260px;
      font-size: 14px;
      color: #666666;
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
        background: url("../assets/images/jobs_list_auth_ico.png") 0 center
          no-repeat;
        background-size: 15px 11px;
      }
      .name {
        float: left;
        font-size: 19px;
        font-weight: bold;
        color: #333333;
        max-width: 215px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
    }
    position: relative;
    padding: 15px 16px 17.5px;
  }
  width: 100%;
  background-color: #ffffff;
  border-top: 1px solid #f3f3f3;
}
</style>
