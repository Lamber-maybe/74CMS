<template>
  <div class="maincontent" v-loading="loading">
    <ul class="listbox" v-if="dataset.length>0">
      <li class="li" v-for="(item,index) in dataset" :key="index">
        <div class="jobname">{{item.jobname}}</div>
        <div class="wage">{{item.wage_text}}</div>
        <div class="info">{{item.district_text}} · {{item.education_text}} · {{item.experience_text}}</div>
        <div class="btn" @click="confirm(item)">沟通</div>
      </li>
    </ul>
    <div v-else class="im-select-joblist-empty">
      <div class="emp_text">目前没有可沟通的职位，请先发布职位或等待职位通过审核</div>
      <div class="emp_btn">
        <el-button type="primary" @click="manageJob">职位管理</el-button>
      </div>
    </div>
  </div>
</template>

<script>
import http from "@/utils/http";
import api from "@/api";
export default {
  name: 'SelectJob',
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
    manageJob:function(){
      this.$router.push('/company/joblist')
    },
    confirm(item){
      http
        .post(api.im_change_job, { token:this.$store.state.imToken,jobid:item.id,chat_id:this.$store.state.imChatid })
        .then(res => {
          if(res.code==200){
            this.$message({type:'success',message:res.message})
            this.$emit('changeJob',item)
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
.jobname{
  color:#333333;
  font-size:14px;
}
.wage{
  color:#FF5017;
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
.im-select-joblist-empty{
  flex:1;
  height: 380px;
  width: 528px;
  float: left;
  position: relative;
}
.im-select-joblist-empty .emp_text {
  color: #666;
  text-align: center;
  position: absolute;
  width: 100%;
  line-height: 22px;
  left: 0;
  top: 80px;
  font-size: 16px;
}
.im-select-joblist-empty .emp_btn{
  color: #666;
  text-align: center;
  position: absolute;
  width: 100%;
  line-height: 22px;
  left: 0;
  top: 140px;
}
</style>
