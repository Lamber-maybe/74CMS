<template>
  <div id="app">
    <el-autocomplete
      popper-class="my-autocomplete"
      v-model="keyword"
      :fetch-suggestions="querySearchAsync"
      placeholder="请输入要定位的详细地址"
      @select="handleSelect"
      style="width: 760px"
    >
      <template slot-scope="{ item }">
        <div class="name">{{ item.value }}</div>
        <span class="addr">{{ item.address }}</span>
      </template>
    </el-autocomplete>
    <div class="map-wrapper">
      <img class="mapicon" src="@/assets/images/plus.png" />
      <div class="mapaddress">{{ mapData.address }}</div>
      <baidu-map
        class="bm-view"
        :ak="$store.state.config.map_ak"
        :center="center"
        :zoom="mapData.zoom"
        @ready="handler"
        @zoomend="handlerZoomend"
        @moveend="handlerMapMove"
      >
        <bm-navigation
          anchor="BMAP_ANCHOR_BOTTOM_LEFT"
          :offset="{ height: 80, width: 10 }"
        ></bm-navigation>
      </baidu-map>
      <div class="map-tip">
        <i class="el-icon-info"></i>拖拽地图自动获取位置
      </div>
    </div>
  </div>
</template>

<script>
import BaiduMap from 'vue-baidu-map/components/map/Map.vue'
import BmNavigation from 'vue-baidu-map/components/controls/Navigation'
export default {
  name: 'Mapset',
  props: ['mapLat', 'mapLng', 'mapZoom', 'address', 'location'],
  components: {
    BaiduMap,
    BmNavigation,
  },
  data() {
    return {
      keyword: '',
      center: { lng: 0, lat: 0 },
      BMap: {},
      map: {},
      mapData: { lat: '', lng: '', zoom: 12, address: '' },
      mapDone: false,
      timeout: null
    }
  },
  created() {
  },
  methods: {
    querySearchAsync(queryString, cb) {
      let that = this
      let searchList = []
      let BMap = that.BMap
      let map = that.map
      var options = {
        onSearchComplete: function (results) {
          // 判断状态是否正确
          if (local.getStatus() == BMAP_STATUS_SUCCESS) {
            for (var i = 0; i < results.getCurrentNumPois(); i++) {
              searchList.push({ value: results.getPoi(i).title, address: results.getPoi(i).address })
            }
          }
        }
      };
      var local = new BMap.LocalSearch(map, options)
      local.search(queryString)

      cb(searchList);
    },
    handleSelect(item) {
      let that = this
      let BMap = this.BMap
      let map = this.map
      let geoc = new BMap.Geocoder();
      // 将地址解析结果显示在地图上，并调整地图视野
      geoc.getPoint(item.address, function (point) {
        if (point) {
          map.centerAndZoom(point, 15);
          that.mapData.zoom = 15
          geoc.getLocation(point, function (rs) {
            var addComp = rs.addressComponents;
            that.mapData.address = addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber
          });
        } else {
          alert('您选择的地址没有解析到结果！');
        }
      })
    },
    initCB() {
      this.center = {
        lat: (this.mapLat !== undefined && parseFloat(this.mapLat) > 0) ? this.mapLat : 0,
        lng: (this.mapLng !== undefined && parseFloat(this.mapLng) > 0) ? this.mapLng : 0
      }
      this.mapData.zoom = (this.mapZoom !== undefined && this.mapZoom > 0) ? this.mapZoom : 12
      this.setlocation()
    },
    setlocation() {
      let BMap = this.BMap
      let map = this.map
      let point = {}
      if (this.center.lat == 0) {
        if (this.location != '' && this.location !== undefined) {
          map.centerAndZoom(this.location, this.mapData.zoom);
          var cen = map.getCenter();
          this.center = {
            lng: cen.lng.toFixed(5),
            lat: cen.lat.toFixed(5)
          }
        } else {
          this.center = {
            lng: parseFloat(this.$store.state.config.map_lng),
            lat: parseFloat(this.$store.state.config.map_lat)
          }
        }
        point = new BMap.Point(this.center.lng, this.center.lat);
      } else {
        point = new BMap.Point(this.center.lng, this.center.lat);
        map.centerAndZoom(point, this.mapData.zoom);
      }
      map.clearOverlays();
      this.mapData.zoom = map.getZoom()
      this.mapData.lat = point.lat
      this.mapData.lng = point.lng
      this.mapData.address = this.address
      this.mapDone = true
    },
    handlerMapMove(res) {
      if (this.mapDone === false) {
        return
      }
      let that = this
      let BMap = this.BMap
      let map = this.map
      const { lng, lat } = res.target.getCenter()
      this.mapData.lng = lng
      this.mapData.lat = lat
      this.mapData.zoom = res.target.getZoom()
      let geoc = new BMap.Geocoder();
      map.addEventListener("dragend", function (e) {
        geoc.getLocation(e.point, function (rs) {
          var addComp = rs.addressComponents;
          that.mapData.address = addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber
        });
      });
    },
    handler({ BMap, map }) {
      this.BMap = BMap
      this.map = map
      this.setlocation()
    },
    handlerZoomend(e) {
      this.mapData.zoom = e.target.getZoom()
    }
  }
}
</script>

<style lang="scss" scoped>
.map-wrapper {
  width: 100%;
  height: 100%;
  position: relative;
}
.bm-view {
  width: 760px;
  height: 400px;
  // position: absolute;
  // margin-top: -53px;
}

.mapicon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translateX(-50%) translateY(-100%);
  width: 52px;
  z-index: 1;
}
.mapaddress {
  position: absolute;
  top: 38%;
  left: 50%;
  transform: translateX(-50%) translateY(-100%);
  z-index: 1;
  color: #e9e9e9;
  font-size: 14px;
  height: 30px;
  line-height: 30px;
  font-family: 微软雅黑;
  background-color: #000;
  opacity: 0.75;
  border: 0;
  padding: 0 10px;
  border-radius: 30px;
}
.my-autocomplete {
  li {
    .name {
      text-overflow: ellipsis;
      overflow: hidden;
    }
    .addr {
      font-size: 12px;
      color: #b4b4b4;
    }

    .highlighted .addr {
      color: #ddd;
    }
  }
}
.el-autocomplete {
  margin-bottom: 10px;
}
.map-tip {
  color: rgb(253, 42, 42);
  margin-top: 16px;
  font-size: 16px;
  .el-icon-info {
    margin-right: 6px;
  }
}
</style>
