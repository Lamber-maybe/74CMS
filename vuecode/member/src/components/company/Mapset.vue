<template>
  <div id="app">
    <div class="map-wrapper">
      <baidu-map
        class="bm-view"
        :ak="$store.state.config.map_ak"
        :center="center"
        :zoom="zoom"
        @ready="handler"
        @zoomend="handlerZoomend"
      >
        <bm-navigation
          anchor="BMAP_ANCHOR_BOTTOM_LEFT"
          :offset="{ height: 80, width: 10 }"
        ></bm-navigation>
        
      </baidu-map>
    </div>
  </div>
</template>

<script>
let markerStyle = {
  color : '#e9e9e9',
  fontSize : '14px',
  height : '30px',
  lineHeight : '30px',
  fontFamily: '微软雅黑',
  backgroundColor:'#000',
  opacity:'0.75',
  border:0,
  padding:'0 10px',
  borderRadius:'30px',
}
import BaiduMap from 'vue-baidu-map/components/map/Map.vue'
import BmNavigation from 'vue-baidu-map/components/controls/Navigation'
export default {
  name: 'Mapset',
  props: ['mapLat', 'mapLng', 'mapZoom', 'address'],
  components: {
    BaiduMap,
    BmNavigation,
  },
  data () {
    return {
      center: { lng: 0, lat: 0 },
      zoom: 12,
      BMap: {},
      map: {},
      mapData: { lat: '', lng: '', zoom: 0, address: '' }
    }
  },
  created () {
  },
  methods: {
    initCB(){
      this.center = {
        lat: (this.mapLat!==undefined && this.mapLat>0)?this.mapLat:parseFloat(this.$store.state.config.map_lat),
        lng: (this.mapLng!==undefined && this.mapLng>0)?this.mapLng:parseFloat(this.$store.state.config.map_lng)
      }
      this.zoom = (this.zoom!==undefined && this.zoom>0)?this.zoom:parseInt(this.$store.state.config.map_zoom)
      this.setlocation()
    },
    setlocation () {
      let BMap = this.BMap
      let map = this.map
      let point = new BMap.Point(this.center.lng,this.center.lat);
      map.centerAndZoom(point, this.zoom);
      map.clearOverlays(); 
      this.makeMarker(point,this.address);
    },
    makeMarker(point,address){
      let that = this
      let BMap = this.BMap
      let map = this.map
      let markerIcon = new BMap.Icon(require("@/assets/images/marker.png"), new BMap.Size(36,40));
      let marker = new BMap.Marker(point,{icon:markerIcon});// 创建标注
      map.addOverlay(marker);             // 将标注添加到地图中
      marker.enableDragging();           // 可拖拽
      let label = new BMap.Label(address,{offset:new BMap.Size(40,0)});
      label.setStyle(markerStyle);
      marker.setLabel(label);
      this.mapData.zoom = map.getZoom()
      this.mapData.lat = point.lat
      this.mapData.lng = point.lng
      this.mapData.address = address
      let geoc = new BMap.Geocoder(); 
      marker.addEventListener("dragend",function(e){
        geoc.getLocation(e.point, function(rs){
          var addComp = rs.addressComponents;
          map.clearOverlays(); 
          let addr = addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber
          that.makeMarker(e.point,addr)
        });
      });
    },
    handler ({ BMap, map }) {
      this.BMap = BMap
      this.map = map
      this.setlocation()
    },
    handlerZoomend (e) {
      this.zoom = e.target.getZoom()
    }
  }
}
</script>

<style lang="scss" scoped>
.map-wrapper {
  width: 100%;
  height: 100%;
}
.bm-view {
  width: 760px;
  height: 400px;
  // position: absolute;
  // margin-top:-53px;
}

// .mapicon{
//   position:fixed;
//   top:50%;
//   left:50%;
//   transform:translateX(-50%) translateY(-100%);
//   width:52px;
//   z-index:1;
// }
</style>
