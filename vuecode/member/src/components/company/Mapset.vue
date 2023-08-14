<template>
  <div id="app">
    <div class="map-wrapper">
      <div class="autocomplete">
        <div class="autocomplete-d1">
          搜索位置:
        </div>
      <el-autocomplete
          prefix-icon="el-icon-search"
        v-model="mapLocation.address"
        :fetch-suggestions="querySearch"
        placeholder="搜索地点、位置"
        style="width: 100%"
        :trigger-on-focus="false"
        @select="handleSelect"
      />
      </div>
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
        <bm-geolocation
          anchor="BMAP_ANCHOR_BOTTOM_RIGHT"
          :showAddressBar="true"
          :autoLocation="true"
          @locationSuccess="locationSuccess"
          @locationError="locationError"
        ></bm-geolocation>
      </baidu-map>
      <div class="location">
      <el-form ref="form" :model="form" label-width="80px">
          <el-form-item label="详细地址:">
          <el-input v-model="form.location"></el-input>
            <div class="location-prompt">
              <i class="el-icon-info"></i>
              请在地址后补足您的详细地址,如A座1603
            </div>
        </el-form-item>
      </el-form>
      </div>
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
import BmGeolocation from 'vue-baidu-map/components/controls/Geolocation.vue'; //定位
import BaiduMap from 'vue-baidu-map/components/map/Map.vue'
import BmNavigation from 'vue-baidu-map/components/controls/Navigation'


export default {
  name: 'Mapset',
  props: ['mapLat', 'mapLng', 'mapZoom', 'address'],
  components: {
    BaiduMap,
    BmNavigation,
    // eslint-disable-next-line vue/no-unused-components
    BmGeolocation
  },
  data () {
    return {

      keyword:'',
      center: { lng: 0, lat: 0 },
      zoom: 12,
      BMap: {},
      map: {},
      mapData: { lat: '', lng: '', zoom: 0, address: '' },

      form: {
        location: '',
      },

      mapLocation: {
        address: undefined,
        coordinate: undefined
      }

    }
  },
  created () {
  },
  methods: {

    onSubmit() {
      console.log('submit!');
    },

    handleSelect(item) {
      var { point,value } = item
      this.mapLocation.coordinate = point
      this.form.location = value
      this.$store.commit('setBaiduMapFrom',{
        data:this.form
      })
      this.map.clearOverlays()
      this.map.addOverlay(new this.BMap.Marker(point))
      this.center.lng = point.lng
      this.center.lat = point.lat
      this.map.clearOverlays()
      this.mapZoom = 15
      this.makeMarker(point,value)

    },

    makerCenter(point) {

      if (this.map) {
        this.map.clearOverlays()
        this.map.addOverlay(new this.BMap.Marker(point))
        this.center.lng = point.lng
        this.center.lat = point.lat
        this.mapZoom = 15

        // let label = this.BMap.Label(value,{offset:this.BMap.Size(40,0)});
        // label.setStyle(markerStyle);


      }
    },

    querySearch(queryString, cb) {
      var that = this
      var myGeo = new this.BMap.Geocoder()
      myGeo.getPoint(queryString, function(point) {
        if (point) {
          that.mapLocation.coordinate = point
          that.makerCenter(point)
        } else {
          that.mapLocation.coordinate = null
        }
      }, this.locationCity)
      var options = {
        onSearchComplete: function(results) {
          if (local.getStatus() === 0) {
            // 判断状态是否正确
            var s = []
            for (var i = 0; i < results.getCurrentNumPois(); i++) {
              var x = results.getPoi(i)
              var item = { value: x.address + x.title, point: x.point }
              s.push(item)
              cb(s)
            }
          } else {
            cb()
          }
        }
      }
      var local = new this.BMap.LocalSearch(this.map, options)
      local.search(queryString)
    },



    initCB(){
      this.center = {
        lat: (this.mapLat!==undefined && this.mapLat>0)?this.mapLat:parseFloat(this.$store.state.config.map_lat),
        lng: (this.mapLng!==undefined && this.mapLng>0)?this.mapLng:parseFloat(this.$store.state.config.map_lng)
      }
      this.zoom = (this.zoom!==undefined && this.zoom>0)?this.zoom:parseInt(this.$store.state.config.map_zoom)
      this.setlocation()
    },
    setlocation () {
      let that = this
      let BMap = this.BMap
      let map = this.map
      let point = new BMap.Point(this.center.lng,this.center.lat);
      map.centerAndZoom(point, this.zoom);
      map.clearOverlays();
      map.addEventListener('click', function(e) {
        map.clearOverlays();
        let markerIcon = new BMap.Icon(require("@/assets/images/marker.png"), new BMap.Size(36,40));
        let marker = new BMap.Marker(e.point,{icon:markerIcon});// 创建标注
        map.addOverlay(marker);             // 将标注添加到地图中
        let geoc = new BMap.Geocoder();
        geoc.getLocation(e.point, function(rs){
          var addComp = rs.addressComponents;
          map.clearOverlays();
          let addr = addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber
          that.form.location = addr
          that.makeMarker(e.point,addr)
          that.$store.commit('setBaiduMapFrom',{
            data:that.form
          })
        });
      });

      this.form.location = this.address

      this.$store.commit('setBaiduMapFrom',{
        data:this.form
      })
      this.makeMarker(point,this.address);
    },
    makeMarker(point,address){
      address = address ? address : '请在上方搜索或在地图中标记位置'
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
          that.form.location = addr
          that.makeMarker(e.point,addr)
          that.$store.commit('setBaiduMapFrom',{
            data:that.form
          })
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
    },

    //定位成功回调
    locationSuccess(point){
      let that = this
      let BMap = this.BMap
      let map = this.map
      map.clearOverlays();
      let markerIcon = new BMap.Icon(require("@/assets/images/marker.png"), new BMap.Size(36,40));
      let markers = new BMap.Marker(point.point,{icon:markerIcon});// 创建标注
      map.addOverlay(markers);             // 将标注添加到地图中
      markers.enableDragging();           // 可拖拽
      let geoc = new BMap.Geocoder();
        geoc.getLocation(point.point, function(rs){
          var addComp = rs.addressComponents;
          that.form.location = addComp.province + addComp.city + addComp.district+ addComp.street + addComp.streetNumber
          let addr = addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber
          that.$store.commit('setBaiduMapFrom',{
            data:that.form
          })
          that.makeMarker(point.point,addr)
        });
    },
    //定位失败回调
    locationError(StatusCode){
      console.log(StatusCode,'StatusCode')
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
.autocomplete{
  display: flex;
  margin-bottom: 20px;
  &-d1{
    width: 80px;
    line-height: 40px;
  }
}
.location{
  margin-top: 10px;
  &-prompt{
    color:#C6C8D0;
  }
}
</style>
