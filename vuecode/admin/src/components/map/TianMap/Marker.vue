<template>
  <div>
    <slot />
  </div>
</template>
<script>
/**
 * @icon Object
 *      @iconUrl
 *      @iconSize width height
 *      @iconAnchor width height
 * @draggable Boolean true / false
 * @title String
 * @zIndex Number
 * @opacity Number
 */
import bindEvents from './utils/bindEvent.js'
import commonMixin from './mixins/common.js'
import { createIcon, createPoint } from './utils/factory.js'
export default {
  name: 'TianMarker',
  mixins: [commonMixin('overlay')],
  props: {
    position: {
      type: Object,
      default: () => {
        return {
          lng: 116.39124,
          lat: 39.90714
        }
      }
    },
    icon: {
      type: Object
    },
    draggable: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: ''
    },
    zIndex: {
      type: Number,
      default: 1
    },
    opacity: {
      type: Number,
      default: 1
    }
  },
  watch: {
    'position.lng' (val, oldVal) {
      const { TMap, originInstance, position } = this
      if (val !== oldVal && val >= -180 && val <= 180) {
        if (originInstance){
          originInstance.setLngLat(createPoint(TMap, { lng: val, lat: position.lat }))
        }
      }
    },
    'position.lat' (val, oldVal) {
      const { TMap, originInstance, position } = this
      if (val !== oldVal && val >= -74 && val <= 74) {
        if (originInstance){
          originInstance.setLngLat(createPoint(TMap, { lng: position.lng, lat: val }))
        }
      }
    }
  },
  methods: {
    load(){
      const { TMap, map, icon, position, draggable, title, zIndex, opacity } = this
      const overlay = new TMap.Marker(new TMap.LngLat(position.lng, position.lat), {
        icon: icon ? createIcon(TMap, icon) : new TMap.Icon.Default(),
        draggable,
        title,
        zIndexOffset: zIndex,
        opacity
      })
      this.originInstance = overlay
      bindEvents.call(this, overlay)
      map.addOverLay(overlay)
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
