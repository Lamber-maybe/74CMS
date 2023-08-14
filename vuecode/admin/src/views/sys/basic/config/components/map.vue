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

export default {
  components: {
    BaiduMap,
    BaiduMapNavigation
  },
  data() {
    return {
      infoLoading: true,
      center: { lng: 116.404, lat: 39.915 },
      zoom: 12,
      form: {
        map_ak: '',
        map_server_ak: '',
        map_lng: '',
        map_lat: '',
        map_zoom: ''
      },
      rules: {},
      setting_secrecy: false
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
  },
  methods: {
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
          const { map_ak, map_server_ak, map_lng, map_lat, map_zoom } = {
            ...response.data
          }
          this.form = {
            map_ak,
            map_server_ak,
            map_lng,
            map_lat,
            map_zoom
          }
          this.form.map_zoom = parseInt(this.form.map_zoom)
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
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
}
</style>
