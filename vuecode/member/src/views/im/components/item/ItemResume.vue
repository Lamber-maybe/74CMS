<template>
  <div>
    <div class="datetime">{{ item.datetime }}</div>
    <div class="photo" :class="item.self_side == 1 ? 'mine' : 'other'">
      <img class="image" :src="item.avatar" />
    </div>
    <div
      @click="toDetail"
      class="resumecard"
      :class="item.self_side == 1 ? 'mine' : 'other'"
    >
      <div class="fullname">{{item.message.fullname}}</div>
      <div class="info">{{item.message.sex}} · {{item.message.age}}岁 · {{item.message.education}} · {{item.message.experience}}</div>
      <div class="intention">
        想找 <span class="jobname">{{item.message.intention_category}}</span> 工作
        <span class="wage">{{item.message.intention_wage}}</span>
      </div>
      <div class="line" v-if="item.message.education_school!='' || item.message.work_companyname!=''"></div>
      <div class="edu" v-if="item.message.education_school!=''">{{item.message.education_school}} · {{item.message.education_major}}</div>
      <div class="work" v-if="item.message.work_companyname!=''">{{item.message.work_companyname}} · {{item.message.work_jobname}}</div>
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
  name: 'ItemResume',
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
    toDetail(){
      let url = this.$store.state.config.link_url_web.resumeshow
      url = url.replace("_id_",this.item.message.resumeid)
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
.resumecard {
  cursor:pointer;
  width: 330px;
  max-height: 200px;
  height: auto;
  padding: 14px 20px;
  background-color: #fff;
  margin: 0 14px;
  border: 1px solid rgba(184, 185, 185, 0.3);
  box-shadow: 0px 0px 24px 0px rgba(219, 219, 219, 0.72);
  border-radius: 5px;
  line-height: 28px;
  &.mine {
    float: right;
  }
  &.other {
    float: left;
  }
  .fullname {
    font-size: 16px;
    color: #333333;
    font-weight: bold;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .info {
    font-size: 14px;
    color: #666666;
  }
  .line {
    height: 1px;
    border-top: 1px dashed #d6d6d6;
    margin: 10px 0;
  }
  .intention {
    font-size: 14px;
    color: #999;
    .jobname {
      color: #666;
    }
    .wage {
      color: #ff4f2c;
      font-weight: bold;
    }
  }
  .edu {
    font-size: 13px;
    color: #666;
    background-image: url("../../../../assets/images/im/edu.jpg");
    background-repeat: no-repeat;
    background-size: 14px 13px;
    background-position: 0 8px;
    padding-left: 20px;
  }
  .work {
    font-size: 13px;
    color: #666;
    background-image: url("../../../../assets/images/im/work.jpg");
    background-repeat: no-repeat;
    background-size: 14px 13px;
    background-position: 0 8px;
    padding-left: 20px;
  }
}
</style>
