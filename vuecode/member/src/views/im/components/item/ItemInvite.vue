<template>
<div>
  <div class="datetime">{{ item.datetime }}</div>
  <div class="photo" :class="item.self_side == 1 ? 'mine' : 'other'">
    <img class="image" :src="item.avatar" />
  </div>
  <div class="invite" :class="item.self_side == 1 ? 'mine' : 'other'">
    <div class="title">{{item.self_side == 1?'邀请对方投递简历':'邀请您投递简历'}}</div>
    <div class="content">
      <div class="jobname">{{item.message.jobname}}</div>
      <div class="info">{{item.message.education_text}} · {{item.message.experience_text}} · {{item.message.district_text}}</div>
      <div class="bottom" v-if="item.self_side == 1">
        <div class="btn" v-if="item.message.status==0">等待对方投递</div>
        <div class="btn done" v-else @click="toDetail">对方已投递 立即查看</div>
      </div>
      <div class="bottom" v-else>
        <div class="btn done" v-if="item.message.status==0" @click="apply(item)">投递简历</div>
        <div class="btn" v-else>已投递</div>
      </div>
      <div class="wage">{{item.message.wage_text}}</div>
      <div class="clear"></div>
    </div>
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
import http from "@/utils/http";
import api from "@/api";
export default {
  name: 'ItemInvite',
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
    },
    apply(item){
      http
        .post(api.im_job_apply, {jobid:this.$store.state.imJobid})
        .then(res => {
          if(res.code==200){
            this.$emit('apply',item)
          }else{
            this.$message({
              message: res.message,
              type: 'warning'
            });
          }
        }).catch(()=>{})
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
.invite {
  background-color: #fff;
  margin: 0 14px;
  width: 300px;
  height: 190px;
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
  .title{
    height:44px;
    line-height:44px;
    background-color:#F8F8F8;
    font-size:16px;
    color:#333;
    padding-left:14px;
  }
  .content{
    padding: 14px 16px;
    position: relative;
    .jobname {
      font-size: 14px;
      color: #111111;
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
    .companyname {
      font-size: 12px;
      color: #999999;
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .addtime {
      border-top: 1px solid #ededed;
      font-size: 13px;
      color: #999999;
      margin-top: 4px;
    }
    .wage {
      position: absolute;
      top: 14px;
      right: 12px;
      color: #ff4f2c;
      font-size: 14px;
      font-weight:bold;
    }
    .btn{
      margin:16px auto;
      width:260px;
      height:35px;
      line-height:35px;
      background: #F8F8F8;
      border-radius: 18px;
      font-size:14px;
      color:#7A7A7A;
      text-align:center;
      cursor:pointer;
      &.done{
        background-color:#1787FB;
        color:#fff;
        &:hover{
          opacity:0.8;
        }
      }
    }
  }
  
}
</style>
