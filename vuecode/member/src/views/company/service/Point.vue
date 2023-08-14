<template>
  <div>
    <PointTop></PointTop>
    <el-card>
      <company-title>我的任务</company-title>
      <div class="task_wrapper">
        <div class="task_list" v-for="(item,index) in task" :key="index">
          <div class="task_img">
            <img :src="require('../../../assets/images/task_icon_'+item.alias+'.png')"/>
          </div>
          <div class="task_text">
              <p>{{item.name}}<span class="daily" v-if="item.daily==1">(日常任务)</span></p>
              <span class="points">{{$store.state.config.points_byname}}：{{item.points}} / {{item.daily==1?'次':'首次'}}</span>
          </div>
          <div class="task_btn">
            <el-button v-if="item.is_done==0" @click="handlerJump(item)">去完成</el-button>
            <el-button v-else disabled class="bg_btn">已完成</el-button>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </el-card>
  </div>
</template>

<script>
import PointTop from './components/PointTop.vue'
import http from '@/utils/http'
import api from '@/api'
  export default{
		components:{
      PointTop
		},
    data(){
      return{
          points: 0,
          logo: '',
          companyname: '',
          task: [],
          taskPoints:{},
      }
    },
    created () {
      document.title = document.title.replace('积分', this.$store.state.config.points_byname)
      this.fetchData()
    },
    methods: {
      fetchData () {
        http
          .get(api.member_points, {})
          .then(res => {
            const { points, logo, companyname, task,taskPoints } = { ...res.data }
            this.points = points
            this.logo = logo
            this.companyname = companyname
            this.task = task
            this.taskPoints = taskPoints
          })
          .catch(() => {})
      },
      handlerJump (item) {
        switch (item.alias) {
          case 'bind_weixin':
          case 'bind_qq':
          case 'bind_sina':
            this.$router.push('/company/account')
            break
          case 'upload_logo':
            this.$router.push('/company/manage/baseinfo')
            break
          case 'auth':
            this.$router.push('/company/auth')
            break
          case 'upload_img':
            this.$router.push('/company/manage/img')
            break
          case 'handle_resume':
            this.$router.push('/company/resume/jobapply')
            break
          case 'sign_in':
            this.handlerSignin()
            break
          default:
            break
        }
      },
      handlerSignin () {
        http
          .post(api.member_signin, {})
          .then(res => {
            this.$message({ type: 'success', message: res.message })
            this.task.forEach(element => {
              if (element.alias === 'sign_in') {
                element.is_done = 1
              }
            })
          })
          .catch(() => {})
      },
    }
  }
</script>
<style lang="scss" scoped>
  .task_wrapper{
    display: flex;
    flex-wrap: wrap;
  }
  .task_list{
    width: 48%;
    border: 1px solid #f3f3f3;
    display: flex;
    float: left;
    margin-right: 30px;
    margin-bottom: 30px;
    &:nth-of-type(2n) {
      margin-right: 0;
    }
  }
  .task_list{
    padding: 16px 0;
  }
  .task_img{
    flex: 1;
    margin-left: 28px;
  }

  .task_img img{
    width: 46px;
    height: 46px;
  }

  .task_text{
    flex: 8;
  }
  .task_text p{
    padding-bottom: 10px;
    color: #333;
    font-size:16px ;
  }
  .task_btn{
   flex:1;
  }
  .task_btn .el-button{
    width: 80px;
    border-radius: 20px;
    color: #ffffff;
    margin-right: 30px;
    color:#ff704f ;
  }
  .task_text .daily {
    color: #999;
    font-size: 16px;
  }
  .task_text .points{
    color: #999;
    font-size: 14px;
  }
  .task_btn .bg_btn{
    color: #a7a7a7;
    background:#f3f3f3;
  }
</style>
