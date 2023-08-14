<template>
  <div class="maincontent" v-loading="loading">
    <div class="empty" v-if="dataset.length==0">
      <div class="emp_text">您还没有屏蔽信息</div>
    </div>
    <ul class="listbox" v-else>
      <li class="li" v-for="(item,index) in dataset" :key="index">
        <div class="showname">{{item.showname}}</div>
        <div class="info">沟通职位：<span class="jobname">{{item.jobname}}</span></div>
        <div class="info">屏蔽时间：{{item.addtime}}</div>
        <div class="btn" @click="cancel(item,index)">移出</div>
      </li>
    </ul>
  </div>
</template>

<script>
import http from "@/utils/http";
import api from "@/api";
export default {
  name: 'BlacklistConf',
  props:['dataset'],
  components: {
  },
  data(){
    return {
      loading:true
    }
  },
  watch: {
    dataset() {
      this.loading = false
    }
  },
  created(){
  },
  mounted() {
  },
  methods:{
    cancel(item,index){
      http
        .post(api.im_blacklist_del, { token:this.$store.state.imToken,id:item.id })
        .then(res => {
          if(res.code==200){
            this.$message({type:'success',message:res.message})
            this.dataset.splice(index,1)
          }else{
            this.$message.error(res.message)
          }
        })
        .catch(() => {});
    }
  },
  beforeDestroy() {
  }
}
</script>

<style lang="scss" scoped>
.listbox{
}
.maincontent{
  height:380px;
  overflow-y:auto;
}
.li{
  height: 90px;
  line-height: 26px;
  position:relative;
  border-bottom:1px solid #ededed;
  margin-bottom:14px;
}
.showname{
  color:#333333;
  font-size:14px;
}
.jobname{
  color:#409EFF;
  font-size:14px;
}
.info{
  color:#999999;
  font-size:14px;
}
.btn{
  width: 70px;
  height: 30px;
  line-height:30px;
  background: #409EFF;
  border-radius: 15px;
  position:absolute;
  color:#FFFFFF;
  text-align:center;
  top:24px;
  right:20px;
  cursor:pointer;
}
.empty{
  width: 100%;
  height: 100%;
  float: left;
  background: #ffffff url(../../../assets/images/im/default_list_ico.png) center 90px no-repeat;
  border-bottom: 1px solid #f3f3f3;
  position: relative;
  .emp_text {
    color: #666;
    text-align: center;
    position: absolute;
    width: 100%;
    line-height: 22px;
    left: 0;
    top: 190px;
  }
}
</style>
