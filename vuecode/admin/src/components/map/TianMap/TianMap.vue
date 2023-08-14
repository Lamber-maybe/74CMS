<template>
  <div>
    <div v-if="!hasTMapView" id="TianMap" ref="view" style="width: 100%; height: 100%" />
    <slot />
  </div>
</template>

<script>
/**
 * https://lbs.luokuang.com/doc.html#/doc/jsapi/reference/map
 */
import bindEvents from './utils/bindEvent.js'
import { checkType } from './utils/util'
export default {
  name: 'TianMap',
  props: {
    ak: {
      type: String,
      default: ''
    },
    center: {
      type: Object,
      default: () => {
        return {}
      }
    },
    zoom: {
      type: Number,
      default: 2
    },
    dragging: {
      type: Boolean,
      default: true
    },
    scrollWheelZoom: {
      type: Boolean,
      default: true
    },
    doubleClickZoom: {
      type: Boolean,
      default: false
    },
    keyboard: {
      type: Boolean,
      default: true
    },
    inertialDragging: {
      type: Boolean,
      default: true
    },
    continuousZoom: {
      type: Boolean,
      default: true
    },
    pinchToZoom: {
      type: Boolean,
      default: true
    },
    autoResize: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      hasTMapView: false
    }
  },
  watch: {
    zoom (val, oldVal) {
      const { map } = this
      if (val !== oldVal && val >= 1 && val <= 18) {
        // console.log(map)
        if (map){
          map.setZoom(val)
        }
      }
    },
    center (val, oldVal) {
      const { map, zoom } = this
      if (checkType(val) === 'String' && val !== oldVal) {
        if (map){
          map.centerAndZoom(val, zoom)
        }
      }
    },
    'center.lng' (val, oldVal) {
      const { map, zoom, center } = this
      if (val !== oldVal && val >= -180 && val <= 180) {
        if (map){
          map.centerAndZoom(new T.LngLat(val, center.lat), zoom)
        }
      }
    },
    'center.lat' (val, oldVal) {
      const { map, zoom, center } = this
      if (val !== oldVal && val >= -74 && val <= 74) {
        if (map){
          map.centerAndZoom(new T.LngLat(center.lng, val), zoom)
        }
      }
    },
    dragging (val) {
      const { map } = this
      val ? map.enableDrag() : map.disableDrag()
    },
    scrollWheelZoom (val) {
      const { map } = this
      val ? map.enableScrollWheelZoom() : map.disableScrollWheelZoom()
    },
    doubleClickZoom (val) {
      const { map } = this
      val ? map.enableDoubleClickZoom() : map.disableDoubleClickZoom()
    },
    keyboard (val) {
      const { map } = this
      val ? map.enableKeyboard() : map.disableKeyboard()
    },
    inertialDragging (val) {
      const { map } = this
      val ? map.enableInertia() : map.disableInertia()
    },
    continuousZoom (val) {
      const { map } = this
      val ? map.enableContinuousZoom() : map.disableContinuousZoom()
    },
    pinchToZoom (val) {
      const { map } = this
      val ? map.enablePinchToZoom() : map.disablePinchToZoom()
    },
    autoResize (val) {
      const { map } = this
      val ? map.enableAutoResize() : map.disableAutoResize()
    }
  },
  mounted () {
    this.reset()
  },
  methods: {
    setMapOptions () {
      const { map, dragging, scrollWheelZoom, doubleClickZoom, keyboard, inertialDragging, continuousZoom, pinchToZoom, autoResize } = this
      // 是否启用地图拖拽
      dragging ? map.enableDrag() : map.disableDrag()
      // 是否启用滚轮放大缩小
      scrollWheelZoom ? map.enableScrollWheelZoom() : map.disableScrollWheelZoom()
      // 是否启用双击放大
      doubleClickZoom ? map.enableDoubleClickZoom() : map.disableDoubleClickZoom()
      // 是否启用键盘操作
      keyboard ? map.enableKeyboard() : map.disableKeyboard()
      // 是否启用地图惯性拖拽
      inertialDragging ? map.enableInertia() : map.disableInertia()
      // 是否启用连续缩放效果
      continuousZoom ? map.enableContinuousZoom() : map.disableContinuousZoom()
      // 是否启用双指操作缩放
      pinchToZoom ? map.enablePinchToZoom() : map.disablePinchToZoom()
      // 是否启用自动适应容器尺寸变化
      autoResize ? map.enableAutoResize() : map.disableAutoResize()
    },
    getMapScript() {
      if (!window.T){
        const ak = this.ak
        window.TianMap = {}
        window.TianMap._preloader = new Promise((resolve, reject) => {
          var head = document.getElementsByTagName('head')[0]
          var $script = document.createElement('script')
          $script.type = 'text/javascript'
          $script.src = `https://api.tianditu.gov.cn/api?v=4.0&tk=${ak}`
          head.appendChild($script)
          $script.onload = function (){
            resolve(window.T)
            window.TianMap._preloader = null
          }
        })
        return window.TianMap._preloader
      } else if (!window.TianMap._preloader) {
        return Promise.resolve(window.T)
      } else {
        return window.TianMap._preloader
      }
    },
    init(TMap){
      if (this.map) {
        return
      }
      const { setMapOptions, center, zoom } = this
      let $el = this.$refs.view
      for (const $node of this.$slots.default || []) {
        if ($node.componentOptions && $node.componentOptions.tag === 'bm-view') {
          this.hasTMapView = true
          $el = $node.elm
        }
      }
      const lat = center.lat ? center.lat : 116.40969
      const lng = center.lng ? center.lng : 39.89945
      const map = new T.Map($el)
      var lnglat = new T.LngLat(lng, lat)
      map.centerAndZoom(lnglat, zoom)
      this.map = map
      setMapOptions()
      bindEvents.call(this, map) // 绑定事件
      this.$emit('ready', { TMap, map })
    },
    initMap(TMap){
      this.TMap = TMap
      this.init(TMap)
    },
    reset(){
      const { getMapScript, initMap } = this
      getMapScript().then(initMap)
    }
  }
}
</script>

<style lang="scss" scoped></style>
