<template>
  <div class="mapset">
    <div class="real-search-box" v-show="showSearchInput==true">
      <div class="return" @click="showSearchInput=false"></div>
      <div class="search-input">
        <el-input
          :autofocus="true"
          placeholder="写字楼/小区/学校等"
          suffix-icon="el-icon-search"
          v-model="keyword"
          @input="searchKeyword">
        </el-input>
      </div>
      <div class="clearfix"></div>
    </div>
    <div v-show="showSearchInput==false" class="search-box" @click="showSearchInput=true">{{keyword?keyword:'写字楼/小区/学校等'}}</div>
    <div class="map-wrapper" v-show="showSearchInput==false">
      <img class="mapicon" src="../../../assets/images/marker.png" />
      <baidu-map
        class="bm-view"
        :ak="$store.state.config.map_ak"
        :center="center"
        :zoom="zoom"
        @ready="mapReady"
        @moveend="mapMoveend"
        @zoomend="mapZoomend"
      >
        <bm-navigation
          anchor="BMAP_ANCHOR_BOTTOM_LEFT"
          :offset="{ height: 80, width: 10 }"
        ></bm-navigation>

      </baidu-map>
    </div>
    <div class="address-wrapper">
      <el-tabs v-show="showSearchInput==false" v-model="activeName" :stretch="true">
        <el-tab-pane label="全部" name="all">
          <div class="address_list" v-loading="searchLoading">
            <div v-if="outerAddrList[0]!==undefined && outerAddrList[0].length>0">
              <div class="address_item" v-for="(item,index) in outerAddrList[0]" :key="index" @click="comfirmMapItem(item)">
                <div class="name">{{item.title}}</div>
                <div class="address">{{item.address}}</div>
              </div>
            </div>
            <div v-else-if="searchLoading===false" class="empty">
              当前区域暂无可选位置哦，拖动地图试试吧
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane label="写字楼" name="office">
          <div class="address_list" v-loading="searchLoading">
            <div v-if="outerAddrList[1]!==undefined && outerAddrList[1].length>0">
              <div class="address_item" v-for="(item,index) in outerAddrList[1]" :key="index" @click="comfirmMapItem(item)">
                <div class="name">{{item.title}}</div>
                <div class="address">{{item.address}}</div>
              </div>
            </div>
            <div v-else-if="searchLoading===false" class="empty">
              当前区域暂无可选位置哦，拖动地图试试吧
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane label="小区" name="community">
          <div class="address_list" v-loading="searchLoading">
            <div v-if="outerAddrList[2]!==undefined && outerAddrList[2].length>0">
              <div class="address_item" v-for="(item,index) in outerAddrList[2]" :key="index" @click="comfirmMapItem(item)">
                <div class="name">{{item.title}}</div>
                <div class="address">{{item.address}}</div>
              </div>
            </div>
            <div v-else-if="searchLoading===false" class="empty">
              当前区域暂无可选位置哦，拖动地图试试吧
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane label="学校" name="school">
          <div class="address_list" v-loading="searchLoading">
            <div v-if="outerAddrList[3]!==undefined && outerAddrList[3].length>0">
              <div class="address_item" v-for="(item,index) in outerAddrList[3]" :key="index" @click="comfirmMapItem(item)">
                <div class="name">{{item.title}}</div>
                <div class="address">{{item.address}}</div>
              </div>
            </div>
            <div v-else-if="searchLoading===false" class="empty">
              当前区域暂无可选位置哦，拖动地图试试吧
            </div>
          </div>
        </el-tab-pane>
      </el-tabs>
      <div v-show="showSearchInput==true">
        <div class="address_list long">
          <div v-if="pointAddrList.length>0">
            <div class="address_item" v-for="(item,index) in pointAddrList" :key="index" @click="setCenter(item)">
              <div class="name">{{item.title}}</div>
              <div class="address">{{item.address}}</div>
            </div>
          </div>
          <div v-else class="empty">
            当前关键词下暂无搜索结果哦，换个关键词试试吧
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
function get_distance(current_lat, current_lng, target_lat, target_lng)
{
    const PI = '3.1415926535898';
    let radLat1 = current_lat * (PI / 180);
    let radLat2 = target_lat * (PI / 180);
    let a = radLat1 - radLat2;
    let b = current_lng * (PI / 180) - target_lng * (PI / 180);
    let s =
    2 *
    Math.asin(
        Math.sqrt(
            Math.pow(Math.sin(a / 2), 2) +
            Math.cos(radLat1) * Math.cos(radLat2) * Math.pow(Math.sin(b / 2), 2)
        )
    );
    return s;
}
import BaiduMap from 'vue-baidu-map/components/map/Map.vue'
import BmNavigation from 'vue-baidu-map/components/controls/Navigation'
export default {
  name: 'Mapset',
  props:[],
  components: {
    BaiduMap,
    BmNavigation,
  },
  data(){
    return {
      searchLoading:false,
      showSearchInput:false,
      activeName: 'all',
      keyword:'',
      center: { lng: 0, lat: 0 },
      zoom: 12,
      BMap: {},
      map: {},
      pointAddrList:[],
      outerAddrList:[]
    }
  },
  watch: {
    center(newVal,oldVal) {
      let that = this
      if(newVal.lat!=oldVal.lat || newVal.lng!=oldVal.lng){
        setTimeout(() => {
          that.searchNeaybyList()
        }, 100);
      }
    }
  },
  created(){
  },
  mounted() {

  },
  methods:{
    comfirmMapItem(item){
      this.$confirm('确定要发送当前位置吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.$emit('comfirmMapItem',item)
      }).catch(() => {

      });
    },
    mapReady ({ BMap, map }) {
      this.BMap = BMap
      this.map = map
      this.center = {
        lat: parseFloat(this.$store.state.config.map_lat),
        lng: parseFloat(this.$store.state.config.map_lng)
      }
      this.zoom = parseInt(this.$store.state.config.map_zoom)
    },
    mapMoveend(e) {
      const { lng, lat } = e.target.getCenter()
      this.center = {
        lat: lat,
        lng: lng
      }
      this.zoom = e.target.getZoom()
    },
    mapZoomend (e) {
      this.zoom = e.target.getZoom()
    },
    setCenter(item){
      this.showSearchInput = false
      this.center = {
        lat: parseFloat(item.lat),
        lng: parseFloat(item.lng)
      }
      this.setlocation()
    },
    searchKeyword(){
      let that = this
      if(this.keyword==''){
        return false;
      }
      let BMap = this.BMap
      let map = this.map
      var local = new BMap.LocalSearch(map, { //智能搜索
        onSearchComplete: function(results){
          that.pointAddrList = []
          if (local.getStatus() == BMAP_STATUS_SUCCESS){
            for (var i = 0; i < results.getCurrentNumPois(); i ++){
              let arr = {
                title:results.getPoi(i).title,
                address:results.getPoi(i).address,
                lng:results.getPoi(i).point.lng,
                lat:results.getPoi(i).point.lat,
              }
              that.pointAddrList.push(arr)
            }
          }
        }
      });
      local.search(this.keyword);
    },
    setlocation () {
      let that = this
      let BMap = this.BMap
      let map = this.map
      let point = new BMap.Point(this.center.lng,this.center.lat);
      setTimeout(() => {
        map.centerAndZoom(point, that.zoom);
      }, 100);
    },
    searchNeaybyList(){
      let that = this
      that.searchLoading = true
      let BMap = this.BMap
      let map = this.map
      that.outerAddrList = []
      that.outerAddrList.push([])
        var options = {
          onSearchComplete: function(results){
            // 判断状态是否正确
            if (local.getStatus() == BMAP_STATUS_SUCCESS){
              for (var i = 0; i < results.length; i ++){
                let p_arr = []
                for (var j = 0; j < results[i].getCurrentNumPois(); j ++){
                  console.log(results[i].getPoi(j).point)
                  if(results[i].getPoi(j)===undefined || results[i].getPoi(j).point===undefined){
                    continue;
                  }
                  let arr = {
                    title:results[i].getPoi(j).title,
                    address:results[i].getPoi(j).address,
                    lng:results[i].getPoi(j).point.lng,
                    lat:results[i].getPoi(j).point.lat,
                  }
                  that.outerAddrList[0].push(arr)
                  p_arr.push(arr)
                }
                //重新排序-距离近的排前面
                p_arr = that.sortList(p_arr)
                that.outerAddrList.push(p_arr)
              }
              that.outerAddrList[0] = that.sortList(that.outerAddrList[0])
            }
            that.searchLoading = false
          }
        };
        var local = new BMap.LocalSearch(map, options);
        local.searchInBounds(['写字楼','小区','学校'],map.getBounds())
    },
    sortList(list){
      let len = list.length;
      for (let i = 0; i < len-1; i++) {
        for (let j = 0; j < len - 1 - i; j++) {
            // 相邻元素两两对比，元素交换，大的元素交换到后面
            if (get_distance(list[j].lat,list[j].lng,this.center.lat,this.center.lng) > get_distance(list[j+1].lat,list[j+1].lng,this.center.lat,this.center.lng)) {
                let temp = list[j];
                list[j] = list[j+1];
                list[j+1] = temp;
            }
        }
      }
      return list;
    },
  },
  beforeDestroy() {
  }
}
</script>

<style lang="scss" scoped>
.mapset{
  height:700px;
}
.map-wrapper {
  width: 100%;
  // height: 100%;
  margin-top:20px;
  position:relative;
  .mapicon{
    position:absolute;
    z-index:1;
    top: 127px;
    left: 380px;
  }
}
.bm-view {
  height: 294px;
}
.address-wrapper{
  margin-top:10px;

}
.address_list{
  height:290px;
  overflow-y:auto;
  &.long{
    height:650px;
  }
}
.address_item{
  height:67px;
  line-height: 24px;
  padding:10px 34px;
  background-image:url("../../../assets/images/im/loc.png");
  background-size:18px 24px;
  background-repeat:no-repeat;
  background-position: 4px center;
  border-bottom:1px solid #E2E2E2;
  cursor:pointer;
  .name{
    color:#666666;
    font-size:14px;
  }
  .address{
    color:#999999;
    font-size:12px;
  }
}
.search-box{
  height:40px;
  border:1px solid #e8e8e8;
  cursor:text;
  background-image:url("../../../assets/images/im/search1.png");
  background-repeat:no-repeat;
  background-size:18px;
  background-position: 10px center;
  line-height: 40px;
  padding-left: 36px;
  color:#CCCCCC;
  font-size:14px;
  border-radius: 3px;
}
.return{
  background-image:url("../../../assets/images/im/return.png");
  background-repeat:no-repeat;
  background-size:24px;
  background-position: 6px center;
  width:24px;
  height:40px;
  float:left;
  margin-right: 13px;
  cursor:pointer;
}
.search-input{
  float:left;
  width: 760px;
}
.real-search-box{
  height:40px;
}
.empty{
  background-image:url("../../../assets/images/im/map_address_empty.png");
  background-repeat:no-repeat;
  background-size:54px 52px;
  background-position: center;
  text-align: center;
  height: 210px;
  padding-top: 146px;
  color:#999;
}
</style>
