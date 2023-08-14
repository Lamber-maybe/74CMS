<template>
  <el-card>
    <company-title>{{this.$route.name=='PersonalPointsIntegral'?('获取'+$store.state.config.points_byname):($store.state.config.points_byname+'明细')}}</company-title>
    <div class="header_wrapper">
      <div class="header_img">
          <img :src="photo" />
          <div class="header_img_text">
              <p>{{fullname}}</p>
              <div class="header_img_interal"><img src="../../../assets/images/integral.png" />我的{{$store.state.config.points_byname}}：<span>{{points}}</span></div>
              <el-button @click="Signin">签到</el-button>
          </div>
      </div>
      <div class="header_text">
        <div class="border"></div>
        <div class="text_box">
          <p>今天已获得<span>{{taskPoints.obtain}}</span>{{$store.state.config.points_byname}}</p>
          <p>您还可免费获得<span>{{taskPoints.able}}</span>{{$store.state.config.points_byname}}</p>
          <div class="btn_box">
            <el-button @click="Jump('/personal/service/stick')">去兑换服务</el-button>
            <el-button @click="Jump('/personal/points/detailed')">收支明细</el-button>
          </div>
        </div>
      </div>
    </div>
    <router-view></router-view>
  </el-card>
</template>

<script>
	import http from '@/utils/http'
	import api from '@/api'
  export default{
    data(){return{
      fullname:'',
      photo:'',
      points:'',
      taskPoints:'',
      task:[]
    }},
    created() {
      document.title = document.title.replace('积分', this.$store.state.config.points_byname)
      this.fetchUserData()
    },
    methods:{
      fetchUserData(){
        http.get(api.member_points).then((res)=>{
          const {photo,task,fullname,points,taskPoints}=res.data
          this.fullname=fullname
          this.photo=photo
          this.points=points
          this.task=task
          this.taskPoints=taskPoints
        })
      },
      Jump(path){
          this.$router.push(path)
      },
      Signin(){
        http.get(api.member_signin).then(res=>{
          this.$store.commit('setUserSignin', {
            signin: true
          })
          this.$store.commit('setUserPoints',{
            points:res.data.points
          })
          this.$message({ type: 'success', message: res.message })
          this.task.forEach(element => {
            if (element.alias === 'sign_in') {
              element.is_done = 1
            }
          })
        })
        .catch(() => {})
      }
    }
  }
</script>

<style lang="scss" scoped>
  .header_wrapper{
    display: flex;
    margin-top: 14px;
    margin-bottom: 40px;
  }
  .header_img{
    width: 342px;
    display: flex;
    margin-left: 20px;
  }
  .header_img>img{
    width: 90px;
    height: 90px;
    margin-right: 20px;
    margin-top: 10px;
  }
  .header_img_text p{
    font-size: 27px;
    color: #333333;
    margin-bottom: 16px;
  }
  .header_img_interal{
    font-size:12px ;
    color: #333333;
    margin-bottom: 16px;
  }
  .header_img_interal>img{
    vertical-align: sub;
    margin-right: 5px;
  }
  .header_img_text .el-button{
    border-radius:30px;
    color:#ffffff ;
    font-size: 14px;
    background: #ff704f;
    height: 30px;
    padding: 8px 27px;
    border: 0;
    transition: all .3s;
    -webkit-transition: all .3s;
    &:hover {
      background: #f0694b;
    }
  }

  .header_text{
    display: flex;
  }
  .border{
    display: inline-block;
    height: 65px;
    border-left: 1px solid #f3f3f3;
    margin-right: 100px;
    margin-top: 15px;
  }
  .text_box p{
    font-size:14px ;
    color:#333333 ;
    margin-bottom: 22px;
  }
  .text_box p span{
    color: #fa7445;
    font-size:11px ;
    padding: 0 5px;
  }
  .btn_box .el-button{
    border-radius:14px;
  }
  .btn_box .el-button:nth-of-type(1){
      padding: 8px 25px;
      font-size: 12px;
      color: #ffffff;
      background: #ff704f;
    border: 1px solid #ff704f;
    transition: all .3s;
    -webkit-transition: all .3s;
    &:hover {
      background: #f0694b;
    }
  }
  .btn_box .el-button:nth-of-type(2){
      padding: 8px 33px;
      font-size: 12px;
      color: #1787fb;
    transition: all .3s;
    -webkit-transition: all .3s;
  }
</style>
