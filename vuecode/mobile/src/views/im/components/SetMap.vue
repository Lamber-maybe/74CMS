<template>
    <div>
        <Head :goback_custom="true" @gobackCustomMethod="handleBack">选择位置</Head>
        <div class="map-wrapper">
            <div class="map-search-box">
                <van-field left-icon="search" v-model="mapSearchText" label="" placeholder="搜索地点" @input="handleSearch" />
            </div>
            <div class="bm-view-con">
              <img class="img" src="../../../assets/images/im/pos.png" alt="">
              <BaiduMap
                v-if="config.is_open_map == 1 && config.map_type == 1"
                class="bm-view"
                ak="map_ak"
                :center="center"
                :zoom="zoom"
                @ready="mapReady"
                @moveend="mapMoveend"
              ></BaiduMap>
              <TianMap
                v-if="config.is_open_map == 1 && config.map_type == 2"
                class="bm-view"
                :style="{'z-index': '1','position': 'relative','height': isMap == true ? '9.466667rem' : '100vh'}"
                :ak="$store.state.config.tian_map_ak"
                :center="center"
                :zoom="zoom"
                @ready="handleTianMapReady"
                @touchend="handleTianMapTouchend"
              >
              </TianMap>
            </div>
            <div v-if="!isMap && config.map_type == 1">
              <div v-if="!isMap">
                  <van-tabs v-model="tabActive" title-active-color="#007AFF" color="#007AFF">
                    <van-tab title="全部" name="all">
                      <div class="tab-wrapper">
                        <div class="tab-item" v-for="(item,index) in nearbyAddrList[0]" :key="index" @click="handleSendMap(item)">
                          <div class="addr-title substring">{{item.title}}</div>
                          <div class="addr-text substring">{{item.address}}</div>
                        </div>
                      </div>
                    </van-tab>
                    <van-tab title="写字楼" name="office">
                      <div class="tab-wrapper">
                        <div class="tab-item" v-for="(item,index) in nearbyAddrList[1]" :key="index" @click="handleSendMap(item)">
                          <div class="addr-title substring">{{item.title}}</div>
                          <div class="addr-text substring">{{item.address}}</div>
                        </div>
                      </div>
                    </van-tab>
                    <van-tab title="小区" name="community">
                      <div class="tab-wrapper">
                        <div class="tab-item" v-for="(item,index) in nearbyAddrList[2]" :key="index" @click="handleSendMap(item)">
                          <div class="addr-title substring">{{item.title}}</div>
                          <div class="addr-text substring">{{item.address}}</div>
                        </div>
                      </div>
                    </van-tab>
                    <van-tab title="学校" name="school">
                      <div class="tab-wrapper">
                        <div class="tab-item" v-for="(item,index) in nearbyAddrList[3]" :key="index" @click="handleSendMap(item)">
                          <div class="addr-title substring">{{item.title}}</div>
                          <div class="addr-text substring">{{item.address}}</div>
                        </div>
                      </div>
                    </van-tab>
                </van-tabs>
              </div>
              <div v-else class="point-wrapper">
                <div class="point-item" v-for="(item,index) in pointAddrList" :key="index" @click="handleSendMap(item)">
                  <div class="addr-title substring">{{item.title}}</div>
                  <div  class="addr-text substring">{{item.address}}</div>
                </div>
              </div>
            </div>
            <div v-else>
              <div v-if="isMap == true" class="point-wrapper">
                <div class="point-item" v-for="(item,index) in pointAddrList" :key="index" @click="handleSendMap(item)">
                  <div class="addr-title substring">{{item.title}}</div>
                  <div  class="addr-text substring">{{item.address}}</div>
                </div>
              </div>
            </div>

        </div>
    </div>
</template>

<script>
import BaiduMap from 'vue-baidu-map/components/map/Map.vue'
import TianMap from '@/components/map/TianMap/TianMap'
import {debounce} from '@/utils/index.js'
import { mapState } from 'vuex'
function get_distance (current_lat, current_lng, target_lat, target_lng) {
  const PI = '3.1415926535898'
  let radLat1 = current_lat * (PI / 180)
  let radLat2 = target_lat * (PI / 180)
  let a = radLat1 - radLat2
  let b = current_lng * (PI / 180) - target_lng * (PI / 180)
  let s =
    2 *
    Math.asin(
      Math.sqrt(
        Math.pow(Math.sin(a / 2), 2) +
            Math.cos(radLat1) * Math.cos(radLat2) * Math.pow(Math.sin(b / 2), 2)
      )
    )
  return s
}
export default {
  name: 'SetMap',
  data () {
    return {
      map_ak: this.$store.state.config.map_ak, // 地图ak
      mapSearchText: '', // 搜索值
      BMap: null, // 地图对象
      map: null, // 地图对象
      center: {}, // 经纬度
      zoom: 0, // 缩放等级
      nearbyAddrList: [], // 附近 写字楼 小区 学校
      tabActive: 'all', // 全部 写字楼 小区 学校
      pointAddrList: [], // 搜索职位数组
      isMap: false,
      mapItem: {},
      TMap: {}
    }
  },
  components: {
    BaiduMap,
    TianMap
  },
  computed: {
    ...mapState(['config'])
  },
  created () {
  },
  watch: {
    center (newVal, oldVal) {
      let that = this
      if (newVal.lat != oldVal.lat || newVal.lng != oldVal.lng) {
        if (that.config.is_open_map == 1) {
          if (that.config.map_type == 1) {
            setTimeout(() => {
              that.searchNeaybyList()
            }, 100)
          } else if (that.config.map_type == 2) {

          }
        }
      }
    }
  },
  methods: {
    handleSetupBack () {},
    // 天地图加载完成
    handleTianMapReady ({TMap, map}) {
      this.TMap = TMap
      this.TianMap = map
      this.center = {
        lat: parseFloat(this.$store.state.config.map_lat),
        lng: parseFloat(this.$store.state.config.map_lng)
      }
      this.zoom = parseInt(this.$store.state.config.map_zoom)
    },
    // 天地图移动结束
    handleTianMapTouchend (e) {
      const { lng, lat } = e.target.getCenter()
      this.center = {
        lat: lat,
        lng: lng
      }
      this.zoom = e.target.getZoom()
    },
    /**
     * 地图加载完成
     */
    mapReady ({ BMap, map }) {
      this.BMap = BMap
      this.map = map
      this.center = {
        lat: parseFloat(this.$store.state.config.map_lat),
        lng: parseFloat(this.$store.state.config.map_lng)
      }
      this.zoom = parseInt(this.$store.state.config.map_zoom)
    },
    /**
     * 地图移动结束时触发此事件
     */
    mapMoveend (e) {
      const { lng, lat } = e.target.getCenter()
      this.center = {
        lat: lat,
        lng: lng
      }
      this.zoom = e.target.getZoom()
    },
    // 暂不使用
    tianMapSearchList () {
      let _this = this
      let TMap = this.TMap
      let TianMap = this.TianMap
      _this.nearbyAddrList = []
      _this.nearbyAddrList.push([])
      var options = {
        onSearchComplete (res) {
          console.log(res)
        }
      }

      var local = new TMap.LocalSearch(TianMap, options)
      local.searchInBounds(['写字楼', '小区', '学校'], TianMap.getBounds())
    },
    searchNeaybyList () {
      let _this = this
      let BMap = this.BMap
      let map = this.map
      _this.nearbyAddrList = []
      _this.nearbyAddrList.push([])
      var options = {
        onSearchComplete (results) {
          for (var i = 0; i < results.length; i++) {
            let p_arr = []
            for (var j = 0; j < results[i].getCurrentNumPois(); j++) {
              var arr = {
                title: results[i].getPoi(j).title,
                address: results[i].getPoi(j).address,
                lng: results[i].getPoi(j).point ? results[i].getPoi(j).point.lng : '',
                lat: results[i].getPoi(j).point ? results[i].getPoi(j).point.lat : ''
              }
              p_arr.push(arr)
            }
            // 重新排序-距离近的排前面
            p_arr = _this.sortList(p_arr)
            _this.nearbyAddrList.push(p_arr)
            console.log(_this.nearbyAddrList)
          }
          _this.nearbyAddrList[0] = [..._this.nearbyAddrList[1], ..._this.nearbyAddrList[2], ..._this.nearbyAddrList[3]]
          _this.nearbyAddrList[0] = _this.sortList(_this.nearbyAddrList[0])
        }
      }
      var local = new BMap.LocalSearch(map, options)
      local.searchInBounds(['写字楼', '小区', '学校'], map.getBounds())
    },
    sortList (list) {
      let len = list.length
      for (let i = 0; i < len - 1; i++) {
        for (let j = 0; j < len - 1 - i; j++) {
          // 相邻元素两两对比，元素交换，大的元素交换到后面
          if (get_distance(list[j].lat, list[j].lng, this.center.lat, this.center.lng) > get_distance(list[j + 1].lat, list[j + 1].lng, this.center.lat, this.center.lng)) {
            let temp = list[j]
            list[j] = list[j + 1]
            list[j + 1] = temp
          }
        }
      }
      return list
    },
    /**
     * 头部返回
     */
    handleBack () {
      this.mapSearchText = ''
      this.isMap = false
      this.$emit('handleMapBack', false)
    },
    /**
     * 搜索
     */
    searchKeyword () {
      let _this = this
      if (this.mapSearchText == '') {
        this.isMap = false
        return false
      }
      this.isMap = true
      let BMap = this.BMap
      let map = this.map
      var local = new BMap.LocalSearch(map, { // 智能搜索
        onSearchComplete: function (results) {
          _this.pointAddrList = []
          if (local.getStatus() == BMAP_STATUS_SUCCESS) {
            for (var i = 0; i < results.getCurrentNumPois(); i++) {
              let arr = {
                title: results.getPoi(i).title,
                address: results.getPoi(i).address,
                lng: results.getPoi(i).point.lng,
                lat: results.getPoi(i).point.lat
              }
              _this.pointAddrList.push(arr)
            }
          }
        }
      })
      local.search(this.mapSearchText)
    },
    tianSearchKeyWord () {
      let _this = this
      if (this.mapSearchText == '') {
        this.isMap = false
        return false
      }
      this.isMap = true
      let TMap = this.TMap
      let TianMap = this.TianMap
      let options = {
        pageCapacity: 1000,
        onSearchComplete (result) {
          _this.pointAddrList = []
          var list = result.getPois()
          console.log(list)
          if (list.length > 0) {
            list.forEach(element => {
              const poit = element.lonlat.split(' ')
              let arr = {
                title: element.name,
                address: element.address,
                lng: poit.lng,
                lat: poit.lat
              }
              _this.pointAddrList.push(arr)
            })
          }
        }
      }
      var local = new TMap.LocalSearch(TianMap, options)
      local.search(this.mapSearchText, 1)
    },
    handleSearch: debounce(function () {
      if (this.config.map_type == 1) {
        this.searchKeyword()
      } else if (this.config.map_type == 2) {
        this.tianSearchKeyWord()
      }
    }, 300),
    /**
     * 是否发送位置
     */
    handleSendMap (mapItem) {
      this.mapItem = mapItem
      this.$dialog.confirm({
        title: '温馨提示',
        message: '确定发送此位置给对方？',
        beforeClose: this.beforeMapClose
      })
        .then(() => {})
        .catch(() => {})
    },
    /**
     * 发送位置弹窗关闭前回调函数
     */
    beforeMapClose (action, done) {
      if (action === 'confirm') {
        this.$emit('handleSendMap', this.mapItem)
        done()
      } else {
        done()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.map-wrapper{
    position: relative;
    .bm-view-con{
      position: relative;
      .img{
        width: 20px;
        height: 25px;
        position:absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        z-index: 30;
      }
    }
    .bm-view {
        width: 100%;
        height: 355px;
    }
    .map-search-box{
        width: 341px;
        height: 50px;
        background: #FFFFFF;
        box-shadow: 0px 1.5px 3.5px 0px rgba(0, 0, 0, 0.13);
        border-radius: 3px;
        position: absolute;
        left: 50%;
        top: 23px;
        transform: translateX(-50%);
        z-index: 10;
        >>> .van-icon-search{
          font-size: 20px;
        }
    }

    .tab-wrapper,
    .point-wrapper{
      height: 370px;
      overflow-y: auto;
      .tab-item,
      .point-item{
        border-bottom: 1px solid #ebedf0;
        height: 65px;
        display: flex;
        justify-content: center;
        flex-flow: column;
        flex-shrink: 0;
        background: url('../../../assets/images/im/address.png') no-repeat 12px 15px / 13px 13px;
        .addr-title{
          color: #454545;
          font-size:15px;
          line-height: 23px;
          margin-left: 35px;
        }
        .addr-text{
          color: #999999;
          font-size: 13px;
          line-height: 23px;
          margin-left: 35px;
        }
      }
    }
    .point-wrapper{
      height: 405px;
    }
}

</style>
