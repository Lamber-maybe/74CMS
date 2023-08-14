<template>
    <div class="im-item-box">
        <!-- 时间开始 -->
        <div class="im-time">
        {{itemMap.datetime}}
        </div>
        <!-- 时间结束 -->
        <div :class="itemMap.self_side ? 'im-item im-right' : 'im-item im-left'">
        <div class="photo">
            <img :src="itemMap.avatar" alt="">
        </div>
        <div class="position-box"  @click="handlejumpLink">
            <div class="position-top">
            <div class="top-txt1 substring">{{itemMap.message.title}}</div>
            <div class="top-txt2 substring">{{itemMap.message.address}}</div>
            </div>
            <div class="position-bottom">
            <img src="../../../../assets/images/im/map.png" alt="">
            </div>
        </div>
        </div>
    </div>
</template>
<script>
import {parseTime, formatTime} from '@/utils/index.js'
export default {
  props: ['itemMap'],
  data () {
    return {
    }
  },
  filters: {
    timeFilter (timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d}', true)
    },
    interviewTimeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  methods: {
    handlejumpLink () {
      console.log(this.itemMap.message.lat, this.itemMap.message.lng)
      if (!this.itemMap.message.lat || !this.itemMap.message.lng) {
        return false
      }
      let url =
        'http://api.map.baidu.com/marker?location=' +
        this.itemMap.message.lat +
        ',' +
        this.itemMap.message.lng +
        '&title=' +
        this.itemMap.message.address +
        '&content=' +
        this.itemMap.message.address +
        '&output=html'
      window.location.href = url
    }
  }
}
</script>
<style lang="scss" scoped>
// 发送位置开始
.position-box{
  width: 225px;
  height: 158px;
  background: #FFFFFF;
  border: 1px solid #FFFFFF;
  border-radius: 10px 0px 10px 10px;
  .position-top{
    max-height: 63px;
    padding: 15px 22px 13px 11px;
    .top-txt1{
      color:#333333 ;
      font-size: 13px;
      margin-bottom: 3px;
      font-weight: 500;
    }
    .top-txt2{
      color:#999999 ;
      font-size: 12px;
    }
  }
  .position-bottom{
    width: 100%;
    height: 95px;
    img{
      width: 100%;
      height: 100%;
    }
  }
}
// 发送位置结束
</style>
