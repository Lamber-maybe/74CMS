<template>
  <div>
    <div class="datetime">{{ item.datetime }}</div>
    <div class="photo" :class="item.self_side == 1 ? 'mine' : 'other'">
      <img class="image" :src="item.avatar" />
    </div>
    <div
     @click="toDetail"
      class="jobcard"
      :class="item.self_side == 1 ? 'mine' : 'other'"
    >
      <div class="jobname">{{item.message.jobname}}</div>
      <div class="info">{{item.message.district_text}} · {{item.message.education_text}} · {{item.message.experience_text}}</div>
      <div class="companyname">{{item.message.companyname}}</div>
      <div class="line"></div>
      <div class="addtime">{{chat_starttime}} 由{{item.self_side==1?'你':'对方'}}发起沟通</div>
      <div class="wage">{{item.message.wage_text}}</div>
      <div class="clear"></div>
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
  name: 'ItemJob',
  props:['item','chat_starttime'],
  data(){
    return {
    }
  },
  created(){
    
  },
  mounted() {
    
  },
  methods:{
    toDetail(){
      let url = this.$store.state.config.link_url_web.jobshow
      url = url.replace("_id_",this.item.message.jobid)
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
.jobcard {
  cursor:pointer;
  width: 300px;
  height: 141px;
  height: auto;
  padding: 16px 18px;
  background-color: #fff;
  margin: 0 14px;
  border: 1px solid rgba(184, 185, 185, 0.3);
  box-shadow: 0px 0px 24px 0px rgba(219, 219, 219, 0.72);
  border-radius: 5px;
  line-height: 26px;
  position: relative;
  &.mine {
    float: right;
  }
  &.other {
    float: left;
  }
  .jobname {
    font-size: 16px;
    color: #111111;
    font-weight: bold;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    margin-bottom:2px;
  }
  .info {
    font-size: 14px;
    color: #666666;
  }
  .companyname {
    font-size: 12px;
    color: #999999;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .line {
    height: 1px;
    border-top: 1px solid #ededed;
    margin: 4px 0;
  }
  .addtime {
    font-size: 12px;
    color: #999999;
    margin-bottom: -4px;
  }
  .wage {
    position: absolute;
    top: 14px;
    right: 16px;
    color: #ff4f2c;
    font-size: 14px;
    font-weight:bold;
  }
}
</style>
