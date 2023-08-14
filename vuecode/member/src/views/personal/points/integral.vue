<template>
  <div>
      <ul class="integral_box">
        <li class="integral_item" v-for="(item,index) in task" :key="index">
          <div class="integral_imgBox">
            <div>
              <img :src="require('../../../assets/images/personal/task_icon_'+item.alias+'.png')" />
            </div>
            <div class="img_text">
              <p>{{item.name}}</p>
              <span>{{$store.state.config.points_byname}}：{{item.points}}/ {{item.daily==1?'次':'首次'}}</span>
            </div>
          </div>
          <div class="integral_btn">
            <el-button class="el_button"  v-if="!item.is_done" @click="handlerJump(item)">去完成</el-button>
            <el-button class="button_bg" v-else disabled>已完成</el-button>
          </div>
        </li>
      </ul>
  </div>
</template>

<script>
	import http from '@/utils/http'
	import api from '@/api'
  export default{
    data(){
      return{
          task: [],
      }
    },
	created() {
    this.fetchData()
	},
	methods:{
		fetchData(){
			http.get(api.member_points).then((res)=>{
        const { points, task } = res.data
        this.$store.commit('setUserPoints',{
          points:points
        })
        this.task=task
			})
      .catch(() => {})
		},
    handlerSignin () {
    http
        .post(api.member_signin, {})
        .then(res => {
            this.$message({ type: 'success', message: res.message })
        })
        .catch(() => {})
    },
    handlerJump(item){
      if(this.$store.state.LoginType==2){
        switch (item.alias) {
          case 'bind_weixin':
          case 'bind_qq':
            this.$router.push('/personal/account/safety')
            break
          case 'refresh_resume':
            this.$router.push('/personal/index')
            break
          case 'upload_photo':
          case 'percent60':
          case 'percent90':
            this.$router.push('/personal/resume')
            break
          case 'apply_job':
            window.open(this.$store.state.config.link_url_web.joblist)
            break
          case 'report_job':
            window.open(this.$store.state.config.link_url_web.joblist)
            break
          case 'sign_in':
            this.handlerSignin()
            break
          default:
            break
        }
      }else{
        switch (item.alias) {
          case 'bind_weixin':
          case 'bind_qq':
            this.$router.push('/company/account/safety')
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
          case 'reg':
            this.$router.push('/reg')
            break
          case 'sign_in':
            this.handlerSignin()
            break
          default:
            break
        }
      }
      
    }
	}
  }
</script>

<style lang="scss" scoped>
  .integral_box{
    display: flex;
    flex-wrap: wrap;
  }
  .integral_item{
    width: 430px;
    height: 80px;
    border: 1px solid #f3f3f3;
    display: flex;
    margin: 0 24px 10px 0;transition: linear all .2s;
    &:hover {
      -webkit-transform:translateY(-3px);transform:translateY(-3px);z-index:1;
    }
  }
  .integral_imgBox{
    display: flex;
    flex: 5;
  }
  .integral_btn{
    flex: 1;
    margin: 25px 18px 0 0;
  }
  .integral_btn .el-button{
    padding: 8px 19px;
    font-size: 12px;
    border-radius: 12px;
  }
   .integral_btn .el_button{
      border: 1px solid #ff704f;
      color: #ff704f;
   }
   .integral_btn .button_bg{
     background-color: #f3f3f3;
     color: #999999;
   }

  .img_text>p{
    padding-top: 18px;
    font-size: 15px;
    color:#333333 ;
  }
  .img_text span{
    color: #737373;
    font-size:12px ;
  }
  .integral_imgBox div:nth-of-type(1) img{
    margin: 15px 20px 0;
  }
</style>
