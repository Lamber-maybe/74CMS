<template>
  <div id="app">
    <Head :goback_custom="true" @gobackCustomMethod="$emit('closeMap')" :show_right="'false'" bgColor="#fff">
      地图标注
    </Head>
    <img class="mapicon" src="../assets/images/plus.png" />
    <div class="map-wrapper">
      <baidu-map
        class="bm-view"
        :ak="$store.state.config.map_ak"
        :center="center"
        :zoom="zoom"
        @ready="handler"
        @zoomend="handlerZoomend"
      >
        <bm-geolocation
          anchor="BMAP_ANCHOR_BOTTOM_RIGHT"
          :showAddressBar="true"
          :autoLocation="true"
          :offset="{ height: 80, width: 10 }"
        ></bm-geolocation>
        <bm-navigation
          anchor="BMAP_ANCHOR_BOTTOM_LEFT"
          :offset="{ height: 80, width: 10 }"
        ></bm-navigation>
        <bm-local-search
          :location="location"
          :keyword="keyword"
          :autoViewport="true"
          :panel="false"
          @searchcomplete="handlerResult"
          :pageCapacity="50"
        ></bm-local-search>
      </baidu-map>
    </div>
    <div class="btn_box">
      <van-button type="info" round class="btn" native-type="button" @click="confirm"
        >确定</van-button
      >
    </div>

    <van-search
      v-model="keyword"
      class="search search-float"
      placeholder="请输入关键词"
    />
    <van-popup
      v-model="showList"
      position="bottom"
      :lock-scroll="false"
      :style="{ height: '70%' }"
      ><van-cell
        v-for="(item, index) in dataset"
        :key="index"
        :title="item.title"
        :label="item.address"
        @click="handlerItem(item)"
    /></van-popup>
  </div>
</template>

<script>
import Vue from 'vue'
let isSpider = new RegExp('^(Baiduspider|YisouSpider|Sogou|Googlebot|Sosospider|bingbot|360Spider)').test(navigator.userAgent)
Vue.component('BaiduMap', function (resolve, reject) {
  if (!isSpider) {
    require(['vue-baidu-map/components/map/Map.vue'], resolve)
  }
})
Vue.component('BmNavigation', function (resolve, reject) {
  if (!isSpider) {
    require(['vue-baidu-map/components/controls/Navigation'], resolve)
  }
})
Vue.component('BmGeolocation', function (resolve, reject) {
  if (!isSpider) {
    require(['vue-baidu-map/components/controls/Geolocation.vue'], resolve)
  }
})
Vue.component('BmLocalSearch', function (resolve, reject) {
  if (!isSpider) {
    require(['vue-baidu-map/components/search/LocalSearch.vue'], resolve)
  }
})
export default {
  name: 'Mapset',
  props: ['mapLat', 'mapLng', 'mapZoom', 'address'],
  data () {
    return {
      location: '',
      dataset: [],
      showList: false,
      keyword: '',
      center: { lng: 0, lat: 0 },
      zoom: 12,
      BMap: {},
      map: {},
      mapData: { lat: '', lng: '', zoom: 0, address: '' }
    }
  },
  created () {
    this.center = {
      lat: parseFloat(this.$store.state.config.map_lat),
      lng: parseFloat(this.$store.state.config.map_lng)
    }
    this.zoom = parseInt(this.$store.state.config.map_zoom)
    this.dataset = []
    this.keyword = ''
  },
  methods: {
    setPosition (pt) {
      let _this = this
      var offsetY = 0
      var map = _this.map
      var BMap = _this.BMap
      console.log(pt)
      map.panTo(pt)
      let centerPixel = map.pointToOverlayPixel(map.getCenter())
      map.setCenter(map.overlayPixelToPoint({x: centerPixel.x, y: centerPixel.y + offsetY}))
      map.addEventListener('dragend', function () {
        let pixel = map.pointToOverlayPixel(map.getCenter())
        let point = map.overlayPixelToPoint({x: pixel.x, y: pixel.y - offsetY})
        _this.mapData.zoom = map.getZoom()
        let geoc = new BMap.Geocoder()
        geoc.getLocation(point, function (rs) {
          var addComp = rs.addressComponents
          _this.mapData.address = addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber
        })
        _this.mapData.lat = point.lat
        _this.mapData.lng = point.lng
      })
    },
    setlocation () {
      let _this = this
      var BMap = _this.BMap
      if (this.mapLat > 0 && this.mapLng > 0) {
        this.setPosition(new BMap.Point(this.mapLng, this.mapLat))
      } else {
        var geolocation = new BMap.Geolocation()
        geolocation.getCurrentPosition(
          function (r) {
            if (this.getStatus() === BMAP_STATUS_SUCCESS) {
              _this.setPosition(r.point)
            }
          },
          { enableHighAccuracy: true }
        )
      }
    },
    handler ({ BMap, map }) {
      this.BMap = BMap
      this.map = map
      this.setlocation()
    },
    handlerResult (result) {
      if (result === undefined) {
        return false
      }
      this.dataset = []
      for (var i = 0; i < result.getCurrentNumPois(); i++) {
        this.dataset.push(result.getPoi(i))
      }
      if (this.dataset.length > 0) {
        this.showList = true
      }
    },
    handlerItem (item) {
      this.map.panTo(item.point)
      let centerPixel = this.map.pointToOverlayPixel(this.map.getCenter())
      this.map.setCenter(this.map.overlayPixelToPoint({x: centerPixel.x, y: centerPixel.y}))
      this.mapData.zoom = this.map.getZoom()
      this.mapData.lat = item.point.lat
      this.mapData.lng = item.point.lng
      this.mapData.address = item.address
      this.showList = false
    },
    confirm () {
      if (this.mapData.lat == '') {
        this.mapData.lat = this.mapLat
      }
      if (this.mapData.lng == '') {
        this.mapData.lng = this.mapLng
      }
      if (this.mapData.address == '') {
        this.mapData.address = this.address
      }
      if (this.mapData.zoom == '') {
        this.mapData.zoom = this.mapZoom
      }
      this.keyword = ''
      this.$emit('closeMap', this.mapData)
    },
    handlerZoomend (e) {
      this.zoom = e.target.getZoom()
    }
  }
}
</script>

<style lang="scss" scoped>
.btn_box {
  width: 100%;
  position: absolute;
  bottom: 10px;
  .btn {
    width: 350px;
    margin: 0 auto;
    display: block;
  }
}
// body,
// html {
//   margin: 0;
//   height: 100%;
//   position: relative;
// }
.map-wrapper {
  width: 100%;
  height: 100%;
}
.bm-view {
  width: 100%;
  height: 100%;
  position: absolute;
  margin-top:-53px;
}

.search-float {
  position: absolute;
  left: 50%;
  top: 73px;
  transform: translate(-50%, 0);
  width: 340px;
  height: 35px;
  border-radius: 35px;
  z-index: 3001;
  .van-search__content {
    background-color: #ffffff;
  }
}
.mapicon{
  position:fixed;
  top:50%;
  left:50%;
  transform:translateX(-50%) translateY(-100%);
  width:52px;
  z-index:1;
}
</style>
