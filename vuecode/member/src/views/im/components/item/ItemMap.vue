<template>
  <div>
    <div class="datetime">{{ item.datetime }}</div>
    <div class="photo" :class="item.self_side == 1 ? 'mine' : 'other'">
      <img class="image" :src="item.avatar" />
    </div>
    <div
      class="mapcard"
      :class="item.self_side == 1 ? 'mine' : 'other'"
      @click="locationToBaidu"
    >
      <div class="title">{{item.message.title}}</div>
      <div class="address">{{item.message.address}}</div>
      <img style="width: 294px;" src="../../../../assets/images/im/map.png" />
    </div>     
    <div
      class="status"
      :class="item.readed == 1 ? 'readed' : ''"
      v-if="item.self_side == 1"
    >
      {{ item.readed == 1 ? "已读" : "送达" }}
    </div>
    <div class="clear"></div>
  </div>
</template>

<script>
export default {
  name: 'ItemMap',
  props:['item'],
  data(){
    return {
    }
  },
  created(){
    
  },
  mounted() {
    
  },
  methods:{
    locationToBaidu(){
      let url =
        'http://api.map.baidu.com/marker?location=' +
        this.item.message.lat +
        ',' +
        this.item.message.lng +
        '&title=' +
        this.item.message.title +
        '&content=' +
        this.item.message.address +
        '&output=html'
      window.open(url)
    }
  },
  beforeDestroy() {
  }
}
</script>

<style lang="scss" scoped>

.status {
  color: #1787fb;
  font-size: 12px;
  float: right;
  &.readed {
    color: #c9c9c9;
  }
}
.datetime {
  text-align: center;
  font-size: 12px;
  color: #999999;
  margin-bottom: 18px;
}
.photo {
  &.mine {
    float: right;
  }
  &.other {
    float: left;
  }
  .image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }
}
.mapcard {
  cursor:pointer;
  width: 295px;
  height: 176px;
  background-color: #fff;
  margin: 0 14px;
  border: 1px solid rgba(184, 185, 185, 0.3);
  box-shadow: 0px 0px 24px 0px rgba(219, 219, 219, 0.72);
  border-radius: 5px;
  line-height: 24px;
  &.mine {
    float: right;
  }
  &.other {
    float: left;
  }
  .title {
    margin: 8px 20px 0;
    font-size: 16px;
    color: #333333;
    max-width: 280px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .address {
    padding: 2px 20px 6px;
    font-size: 14px;
    color: #999999;
    max-width: 280px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
}
</style>
