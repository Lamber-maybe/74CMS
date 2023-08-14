<template>
  <div class="app-container">
    <div class="spaceline" />
    <div v-if="setting_secrecy === true" class="no-promise">
      <div class="notice-div">
        <p class="notice-p">暂无查看权限，请联系网站负责人</p>
      </div>
    </div>
    <el-form
      v-else
      ref="form"
      :v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="150px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="是否开启">
        <el-switch v-model="form.is_open_map" />
      </el-form-item>
      <el-form-item v-if="form.is_open_map == 1" label="地图类型">
        <el-radio-group v-model="form.map_type">
          <el-radio label="1">百度地图</el-radio>
          <el-radio label="2">天地图</el-radio>
        </el-radio-group>
      </el-form-item>
      <div v-if="form.is_open_map == 1 && form.map_type == 1">
        <el-form-item label="默认地图标注">
          <baidu-map
            :ak="config.map_ak"
            class="map"
            :scroll-wheel-zoom="true"
            :center="center"
            :zoom="zoom"
            @ready="handlerMap"
            @moveend="handlerMapMove"
            @zoomend="handlerMapScroll"
          >
            <baidu-map-navigation />
          </baidu-map>
        </el-form-item>
        <el-form-item label="百度地图客户端AK">
          <el-input v-model="form.map_ak" class="small" />
        </el-form-item>
        <el-form-item label="百度地图服务端AK">
          <el-input v-model="form.map_server_ak" class="small" />
        </el-form-item>
        <el-form-item label="默认中心点X坐标">
          <el-input v-model="form.map_lng" class="small" />
        </el-form-item>
        <el-form-item label="默认中心点Y坐标">
          <el-input v-model="form.map_lat" class="small" />
        </el-form-item>
        <el-form-item label="默认缩放级别">
          <el-input v-model="form.map_zoom" class="small" />
        </el-form-item>
      </div>
      <div v-if="form.is_open_map == 1 && form.map_type == 2">
        <el-form-item label="天地图标注">
          <TianMap
            :ak="config.tian_map_ak"
            class="map"
            :center="tianCenter"
            :zoom="tianZoom"
            @ready="handleTianMapready"
            @moveend="handlerTianMapMove"
            @zoomend="handlerTianMapScroll"
          >
            <TianNavigation />
          </TianMap>
        </el-form-item>
        <el-form-item label="天地图客户端AK">
          <el-input v-model="form.tian_map_ak" class="small" />
        </el-form-item>
        <el-form-item label="天地图服务端AK">
          <el-input v-model="form.tian_map_server_ak" class="small" />
        </el-form-item>
        <el-form-item label="默认中心点X坐标">
          <el-input v-model="form.map_lng" class="small" />
        </el-form-item>
        <el-form-item label="默认中心点Y坐标">
          <el-input v-model="form.map_lat" class="small" />
        </el-form-item>
        <el-form-item label="默认缩放级别">
          <el-input v-model="form.map_zoom" class="small" />
        </el-form-item>
      </div>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
import { setConfig } from '@/api/configuration'
import BaiduMap from 'vue-baidu-map/components/map/Map.vue'
import BaiduMapNavigation from 'vue-baidu-map/components/controls/Navigation.vue'
import TianMap from '@/components/map/TianMap/TianMap.vue'
import TianNavigation from '@/components/map/TianMap/Navigation.vue'
import { transfromWGS84toBaidu, transfromBaidutoWGS84 } from '@/utils/map.js'
export default {
  components: {
    BaiduMap,
    BaiduMapNavigation,
    TianMap,
    TianNavigation
  },
  data() {
    return {
      infoLoading: true,
      center: { lng: 116.404, lat: 39.915 },
      tianCenter: { lng: 116.404, lat: 39.915 },
      zoom: 12,
      form: {
        map_ak: '',
        map_server_ak: '',
        map_lng: '',
        map_lat: '',
        map_zoom: '',
        is_open_map: '',
        map_type: '1',
        tian_map_ak: '',
        tian_map_server_ak: ''
      },
      rules: {},
      setting_secrecy: false,
      tianZoom: 12
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  mounted() {},
  created() {
    this.setting_secrecy = window.global.SettingSecrecy ? window.global.SettingSecrecy : false
    this.fetchInfo()
    this.mapType = this.config.map_type
    this.publicMapCenter = {
      lng: this.config.map_lng,
      lat: this.config.map_lat
    }
  },
  methods: {
    handleMapSwitch(val){
      // 地图类型  1-百度地图 2-天地图
      if (this.mapType == 2){
        // 展示百度地图 需要将天地图wgs84转为百度地图db09
        const { lng, lat } = this.publicMapCenter
        const tianMap = transfromWGS84toBaidu(lat, lng)
        this.tianCenter = { lng: tianMap.longitude, lat: tianMap.latitude }
        this.form.map_lng = tianMap.longitude
        this.form.map_lat = tianMap.latitude
        this.mapType = 1
        this.$set(this.publicMapCenter, 'lng', (tianMap.longitude * 1))
        this.$set(this.publicMapCenter, 'lat', (tianMap.latitude * 1))
      } else if (this.mapType == 1){
        // 展示天地图 需要将百度地图db09转为天地图坐wgs84
        const { lng, lat } = this.publicMapCenter
        const baiduMap = transfromBaidutoWGS84(lat, lng)
        this.tianCenter = { lng: baiduMap.longitude, lat: baiduMap.latitude }
        this.form.map_lng = baiduMap.longitude
        this.form.map_lat = baiduMap.latitude
        this.mapType = 2
        this.$set(this.publicMapCenter, 'lng', (baiduMap.longitude * 1))
        this.$set(this.publicMapCenter, 'lat', (baiduMap.latitude * 1))
      }
    },
    handleTianMapready(){
      const { map_lng, map_lat, map_zoom } = this.config
      this.tianCenter = { lng: map_lng, lat: map_lat }
      this.tianZoom = parseInt(map_zoom)
    },
    handlerTianMapMove(res){
      const { lng, lat } = res.target.getCenter()
      this.form.map_lng = lng
      this.form.map_lat = lat
      this.form.map_zoom = res.target.getZoom()
    },
    handlerTianMapScroll(res){
      this.form.map_zoom = res.target.getZoom()
    },
    handlerMap({ BMap, map }) {
      var that = this
      setTimeout(function() {
        that.center.lng = that.config.map_lng
        that.center.lat = that.config.map_lat
        that.zoom = parseInt(that.config.map_zoom)
      }, 1000)
    },
    handlerMapMove(res) {
      const { lng, lat } = res.target.getCenter()
      this.form.map_lng = lng
      this.form.map_lat = lat
      this.form.map_zoom = res.target.getZoom()
    },
    handlerMapScroll(res) {
      this.form.map_zoom = res.target.getZoom()
    },
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const { map_ak, map_server_ak, map_lng, map_lat, map_zoom, is_open_map, map_type, tian_map_ak, tian_map_server_ak } = {
            ...response.data
          }
          this.form = {
            map_ak,
            map_server_ak,
            map_lng,
            map_lat,
            map_zoom,
            is_open_map,
            map_type,
            tian_map_ak,
            tian_map_server_ak
          }
          this.form.is_open_map = this.form.is_open_map == 1
          this.form.map_zoom = parseInt(this.form.map_zoom)
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.is_open_map = insertData.is_open_map === true ? 1 : 0
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    }
  }
}
</script>
<style>
.map {
  height: 400px;
  max-width: 800px;
  position: relative;
  z-index: 1;
}
</style>
