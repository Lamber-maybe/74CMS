<template>
    <el-card>
        <div class="header_wrapper">
            <div class="header_left">
              <div class="header_img">
                <img :src="logo"/>
              </div>
              <div class="header_title">
                <p>{{companyname}}</p>
                <div class="p_text">
                  <img src="../../../../assets/images/integral.png" />
                  我的{{$store.state.config.points_byname}}:<span>{{points}}</span>
                  <el-button disabled v-if="hasSign===true" class="ed">已签到</el-button>
                  <el-button @click="handlerSignin" v-else>签到</el-button>
                </div>
              </div>
            </div>
            <div class="header_right">
              <span></span>
              <div>
                <p><span class="lf">今天已获得<span class="color_2"> {{taskPoints.obtain}} </span>{{$store.state.config.points_byname}}</span> <a class="color_2" href="javascript:;" @click="handlerBuyPoint">[购买{{$store.state.config.points_byname}}]</a></p>
                <p><span class="lf">您还可免费获得<span class="color_2"> {{taskPoints.able}} </span>{{$store.state.config.points_byname}}</span> <router-link class="color_1" to="/company/service/pointlog">[收支明细]</router-link> </p>
              </div>
            </div>
        </div>
        <el-dialog  :title="'充值'+$store.state.config.points_byname"  :visible.sync="showBuyPoint"  width="540px" :close-on-press-escape="false" :close-on-click-modal="false" :destroy-on-close="true">
          <BuyPointDialog ref="buyPoint" @submitPay="submitPay"></BuyPointDialog>
        </el-dialog>
        <el-dialog  title="支付提醒"  :visible.sync="showWaitingPay"  width="400px" >
          <span class="payment_text">请在新打开的页面上完成支付。支付完成后，请根据您支付的情况点击下面按钮。</span>
          <span slot="footer" class="dialog-footer">
          <el-button type="primary"  @click="handlerPaySuccess">支付成功</el-button>
          <el-button   @click="handlerPayFail">支付失败</el-button>
          </span>
        </el-dialog>
    </el-card>
</template>

<script>
import BuyPointDialog from '@/components/company/service/BuyPointDialog.vue'
import http from '@/utils/http'
import api from '@/api'
  export default{
		components:{
			BuyPointDialog
		},
    data(){
      return{
          showWaitingPay:false,
          showBuyPoint:false,
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
      this.userSignin()
    },
    computed: {
      hasSign(){
        return this.$store.state.userSignin
      }
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
            for (const iterator of task) {
              if (iterator.alias === 'sign_in' && iterator.is_done === 1) {
                this.$store.commit('setUserSignin', {
                  signin: true
                })
                break
              }
            }
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
            this.$store.commit('setUserSignin', {
              signin: true
            })
            this.task.forEach(element => {
              if (element.alias === 'sign_in') {
                element.is_done = 1
              }
            })
          })
          .catch(() => {})
      },
      handlerBuyPoint(){
        this.showBuyPoint = true
      },
			submitPay(isFinish){
				this.showBuyPoint = false
				if(isFinish===true){
					this.$parent.fetchData()
				}else{
					this.showWaitingPay = true
				}
      },
			handlerPaySuccess(){
				this.showWaitingPay = false
				this.$parent.fetchData()
			},
			handlerPayFail(){
				this.showWaitingPay = false
			},
      userSignin()
      {
        http.get(api.member_user_ignin).then(res => {
          if (res.data > 0)
          {
            this.$store.commit('setUserSignin', {
              signin: true
            })
          }else {
            this.$store.commit('setUserSignin', {
              signin: false
            })
          }
        }).catch(() => {})
      }
    }
  }
</script>
<style lang="scss" scoped>
  .el-card{
    margin-bottom: 10px;
  }
  .header_wrapper{
    display: flex;
    margin: 23px 0;
  }
  .header_left{
    display: flex;
    width: 700px;
  }
  .header_title p{
    font-size:18px ;
    color: #333333;
  }
  .header_title img{
    vertical-align: middle;
  }
  .header_title div .el-button{
    width:90px ;
    height: 31px;
    background: #ff704f;
    line-height: 31px;
    color: #ffffff;
    padding: 0;
    border-radius: 20px;
    margin-left: 16px;
    border: 0;
    transition: all .3s;
    -webkit-transition: all .3s;
    &:hover {
      background: #f16a4b;
    }
    &.ed {
      background: #f3f3f3;color: #a7a7a7;
    }
  }
  .header_img{
    margin: 0 20px;
    width: 70px;
    height: 70px;
  }
  .header_img img{
    width: 100%;
    height: 100%;
  }
  .header_title div img{
    vertical-align: middle;
  }
  .header_title .p_text {
    margin-top: 20px;
    color:#333 ;
    font-size: 14px;
    padding-left: 18px;
    position: relative;
    line-height: 31px;
    img {
      position: absolute;left: 0;top: 8px;
    }
  }

  .header_right{
    flex: 1;
    display: flex;
    .lf{
      width:220px;
      display:inline-block;
      color: #333;
    }
  }
  .header_right>span{
    width: 1px;
    height: 60px;
    display: inline-block;
    background: #f3f3f3;
  }
  .header_right>div{
    margin-left:37px;
  }
  .header_right>div p{
    margin: 10px 0;
    font-size: 14px;
  }
  // .header_right>div>p:nth-of-type(1) a{
  //     margin-left: 68px;
  // }

  .color_1{
    color: #3691fb;
  }
  .color_2{
    color: #fa7445;
  }

</style>
