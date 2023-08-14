<template>
  <div id="app">
    <Head>地图找工作</Head>
    <div class="map-wrapper">
      <baidu-map
        class="bm-view"
        :ak="$store.state.config.map_ak"
        :center="center"
        :zoom="zoom"
        @ready="handler"
        @moveend="syncCenterAndZoom"
        :map-click="false"
      >
        <bm-geolocation
          anchor="BMAP_ANCHOR_BOTTOM_RIGHT"
          :showAddressBar="true"
          :autoLocation="true"
        ></bm-geolocation>
        <bm-navigation anchor="BMAP_ANCHOR_BOTTOM_LEFT"></bm-navigation>
        <bml-marker-clusterer :averageCenter="true">
          <div v-for="(marker, i) of marklist" :key="i">
            <bm-marker
              :dragging="true"
              animation="BMAP_ANIMATION_BOUNCE"
              :position="{ lng: marker.map_lng, lat: marker.map_lat }"
              @click="onHandlerMark(marker)"
            ></bm-marker>
          </div>
        </bml-marker-clusterer>
      </baidu-map>
    </div>
    <van-search
      v-model="params.keyword"
      class="search search-float"
      placeholder="请输入职位或企业关键词"
      @search="onKeywordSearch"
    />
    <van-action-sheet
      v-model="show"
      :round="false"
      :overlay="false"
      class="sheet_content"
    >
      <div class="handle_bar" ref="mapbar" @click="showContent">
        <div class="self">
          <div :class="contentShow ? 'round down' : 'round up'"></div>
        </div>
      </div>
      <div ref="jobContent" v-if="contentShow">
        <van-list
          v-model="loading"
          :finished="finished"
          finished-text="没有更多了"
          @load="onLoad"
          :immediate-check="false"
        >
          <div class="content">
            <div
              class="list"
              v-for="(item,index) in joblist"
              :key="index"
              @click="$router.push('/job/' + item.id)"
            >
              <div class="tx1">
                <div class="name">{{ item.jobname }}</div>
                <div class="worry_ico" v-if="item.emergency == 1">急</div>
                <div class="clear"></div>
                <div class="wage">{{ item.wage_text }}</div>
              </div>
              <div class="tx2">
                {{ item.education_text }} · {{ item.experience_text }} ·
                {{ item.district_text }}
                <div class="time">{{ item.refreshtime }}</div>
              </div>
              <div class="tag_wrapper">
                <van-tag
                  color="#e9f8ff"
                  text-color="#8096a3"
                  v-for="(tag, key) in item.tag_text_arr"
                  :key="key"
                >
                  {{ tag }}
                </van-tag>
              </div>
              <div class="company">
                <div class="name">{{ item.companyname }}</div>
                <div class="auth_ico" v-if="item.company_audit == 1"></div>
                <div class="crw_ico" v-if="item.setmeal_icon != ''"><img :src="item.setmeal_icon" /></div>
                <div class="clear"></div>
              </div>
            </div>
          </div>
        </van-list>
      </div>
    </van-action-sheet>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import BaiduMap from 'vue-baidu-map/components/map/Map.vue'
import BmNavigation from 'vue-baidu-map/components/controls/Navigation'
import BmGeolocation from 'vue-baidu-map/components/controls/Geolocation.vue'
import BmMarker from 'vue-baidu-map/components/overlays/Marker.vue'
import { BmlMarkerClusterer } from 'vue-baidu-map'
export default {
  name: 'JobMap',
  components: {
    BaiduMap,
    BmNavigation,
    BmGeolocation,
    BmMarker,
    BmlMarkerClusterer
  },
  data () {
    return {
      show: true,
      contentShow: false,
      roundClass: 'round up',
      center: { lng: 0, lat: 0 },
      zoom: 12,
      BMap: {},
      map: {},
      map_done: false,
      params: {
        keyword: '',
        south_west_lat: '',
        south_west_lng: '',
        north_east_lat: '',
        north_east_lng: ''
      },
      page: 1,
      pagesize: 3,
      joblist: [],
      marklist: [],
      loading: false,
      finished: false
    }
  },
  created () {
    this.center = {
      lat: this.$store.state.config.map_lat,
      lng: this.$store.state.config.map_lng
    }
  },
  methods: {
    onHandlerMark (marker) {
      http.get(api.jobshow, {id: marker.id}).then(res => {
        let detail = {
          id: res.data.base_info.id,
          jobname: res.data.base_info.jobname,
          emergency: res.data.base_info.emergency,
          wage_text: res.data.base_info.wage_text,
          education_text: res.data.base_info.education_text,
          experience_text: res.data.base_info.experience_text,
          district_text: res.data.base_info.district_text,
          refreshtime: res.data.base_info.refreshtime,
          tag_text_arr: res.data.base_info.tag_text_arr,
          companyname: res.data.base_info.companyname,
          company_audit: res.data.base_info.company_audit,
          setmeal_icon: res.data.base_info.setmeal_icon
        }
        this.joblist = [detail]
      }).catch(() => {})

      this.moveShow()
    },
    syncCenterAndZoom (e) {
      if (this.map_done === false) {
        return
      }
      let cur_bssw = this.map.getBounds().getSouthWest()
      let cur_bsne = this.map.getBounds().getNorthEast()
      this.params.south_west_lat = cur_bssw.lat
      this.params.south_west_lng = cur_bssw.lng
      this.params.north_east_lat = cur_bsne.lat
      this.params.north_east_lng = cur_bsne.lng
      this.fetchData(true)
    },
    setlocation () {
      let that = this
      let BMap = that.BMap
      var geolocation = new BMap.Geolocation()
      geolocation.getCurrentPosition(
        function (r) {
          if (this.getStatus() === BMAP_STATUS_SUCCESS) {
            that.center.lat = r.point.lat
            that.center.lng = r.point.lng
            that.map_done = true
          }
        },
        { enableHighAccuracy: true }
      )
    },
    handler ({ BMap, map }) {
      this.BMap = BMap
      this.map = map
      //   console.log(this.map)
      //   return false
      this.setlocation()
    },
    moveShow () {
      this.contentShow = true
    },
    showContent () {
      this.contentShow = !this.contentShow
    },
    fetchData (init) {
      if (init === true) {
        this.page = 1
        this.finished = false
      }
      let conditions = { ...this.params }
      conditions.page = this.page
      conditions.pagesize = this.pagesize
      http
        .get(api.mapsearch, conditions)
        .then(res => {
          if (init === true) {
            this.marklist = [...res.data.marks]
            this.joblist = [...res.data.items]
          } else {
            this.joblist = this.joblist.concat(res.data.items)
          }
          // 加载状态结束
          this.loading = false
          // 数据全部加载完成
          if (res.data.items.length === 0) {
            this.finished = true
          }
        })
        .catch(() => {})
    },
    onLoad () {
      this.page++
      this.fetchData(false)
    },
    onKeywordSearch () {
      this.fetchData(true)
    }
  },
  mounted () {
    document.querySelector('.van-icon-search').classList.add('mt')
  }
}
</script>

<style lang="scss" scoped>
body,
html {
  margin: 0;
  height: 100%;
}
.map-wrapper {
  width: 100%;
}
.bm-view {
  width: 100%;
  height: 88%;
  position: absolute;
}
.search-float {
  position: absolute;
  left: 50%;
  top: 73px;
  transform: translate(-50%, 0);
  width: 340px;
  height: 35px;
  border-radius: 35px;
  z-index: 1;
  .van-search__content {
    background-color: #ffffff;
  }
}
.sheet_content {
  padding-top: 28px;
}
.handle_bar {
  .self {
    .round {
      &.up {
        &::before {
          content: " ";
          position: absolute;
          width: 0;
          height: 0;
          z-index: 3;
          border: 8px solid transparent;
          border-bottom-color: #ffffff;
          left: 50%;
          bottom: 12px;
          transform: translate(-50%, 0);
        }
      }
      &.down {
        &::after {
          content: " ";
          position: absolute;
          width: 0;
          height: 0;
          z-index: 3;
          border: 8px solid transparent;
          border-top-color: #ffffff;
          left: 50%;
          top: 11px;
          transform: translate(-50%, 0);
        }
      }
      position: absolute;
      left: 50%;
      top: 0;
      transform: translate(-50%, 0);
      width: 28px;
      height: 28px;
      border-radius: 100%;
      background-color: #1787fb;
      z-index: 2;
    }
    &::after {
      content: " ";
      position: absolute;
      width: 100%;
      height: 4px;
      background-color: #1787fb;
      left: 0;
      top: 12px;
    }
    position: relative;
    width: 100%;
    height: 100%;
  }
  position: absolute;
  top: 0;
  left: 0;
  height: 28px;
  width: 100%;
  z-index: 1;
  background-color: #ffffff;
}
.content {
  .list {
    &:not(:last-child) {
      margin-bottom: 5px;
    }
    .top {
      position: absolute;
      right: -25px;
      top: -25px;
      width: 50px;
      height: 50px;
      background-color: #feae41;
      color: #ffffff;
      font-size: 10px;
      font-weight: bold;
      text-align: center;
      transform: rotateZ(45deg);
      padding-top: 36px;
    }
    .company {
      .crw_ico {
        float: left;
        margin-left: 6px;
        width: 14px;
        height: 18px;
        position: relative;
        img {
          width: 100%;
          height: 13px;
          position: absolute;
          top: 50%;
          left: 0;
          border: 0;
          transform: translate(0, -50%);
        }
      }
      .auth_ico {
        float: left;
        margin-left: 6px;
        width: 15px;
        height: 13px;
        background: url("../assets/images/jobs_list_auth_ico.png") 0 center
          no-repeat;
        background-size: 15px 11px;
      }
      .name {
        float: left;
        max-width: 300px;
        font-size: 13px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      width: 100%;
      border-top: 1px solid #f3f3f3;
      padding: 15px 0;
    }
    .tag_wrapper {
      .van-tag {
        margin-right: 5px;
        border-radius: 3px;
      }
      width: 100%;
      padding-bottom: 13px;
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
      padding: 14px 60px 11px 0;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      position: relative;
    }
    .tx1 {
      .wage {
        position: absolute;
        right: 0;
        top: 22px;
        font-size: 14px;
        color: #ff5d24;
        font-weight: bold;
      }
      .worry_ico {
        font-size: 10px;
        color: #ffffff;
        padding: 3px;
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
      padding-top: 20px;
    }
    position: relative;
    width: 100%;
    background-color: #ffffff;
    padding: 0 17px;
    overflow: hidden;
  }
  background-color: #f3f3f3;
  position: relative;
}
</style>
