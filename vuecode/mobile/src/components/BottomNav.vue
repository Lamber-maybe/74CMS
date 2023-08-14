<template>
  <div class="bottom_group">
    <div class="bottom_bar_wrapper">
      <div class="d1">
        <router-link class="bar_item home" to="/index">首页</router-link>
        <router-link :class="'bar_item ' + secondClass" :to="secondTo">
          {{ secondText }}
        </router-link>
      </div>
      <div class="Aplu d2">
        <div class="AplusSign" @click="AplusSign">
          <!--          <router-link :to="plusSignTo">-->
          <!--          </router-link>-->
        </div>
      </div>
      <div class="d1">
        <!-- <div :class="'bar_item ' + thirdClass " @click="handleImjump(thirdTo)">
          {{ thirdText }}
          <div class="information" v-if="$store.state.imUnreaded"></div>
        </div> -->
        <router-link :class="'bar_item ' + thirdClass " :to="thirdTo">
          {{ thirdText }}
          <div class="information" v-if="$store.state.imUnreaded && $store.state.LoginOrNot">
                           <!-- {{NewList}} -->
                           <!-- 30 -->
          </div>
        </router-link>
        <router-link class="bar_item user" :to="mineTo">
          我的
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import {mapState, mapMutations} from 'vuex'
export default {
  name: 'BottomNav',
  data () {
    return {
      NewList: 0,
      secondClass: 'job',
      secondText: '找工作',
      secondTo: '/joblist',
      thirdClass: 'im',
      thirdText: '消息',
      thirdTo: '/im/imList',
      mineTo: '/member/login',
      plusSignTo: '/member/login'
    }
  },
  methods: {
    ...mapMutations(['setImToken']),
    handleImjump (item) {
      if (this.thirdClass == 'im') {
        this.$store.state.imUnreaded = false
        this.$router.push(item)
      } else {
        this.$router.push(item)
      }
      // console.log(item,this.thirdClass)
    },
    AplusSign () {
      // 根据登录会员类型，处理导航显示
      if (this.$store.state.LoginOrNot) {
        if (parseInt(this.$store.state.LoginType) === 1) {
          // 企业登录
          this.handlerJobadd()
          return
        } else {
          // 个人登录
          this.$router.push('/member/personal/resume')
          return
        }
      }
      // 没登陆 跳登录
      this.$router.push('/member/login')
    },
    handlerJobadd () {
      http
        .get(api.company_check_jobadd_num, {})
        .then(res => {
          if (res.data.enable_addjob_num <= 0) {
            this.$dialog
              .confirm({
                title: '系统提示',
                message:
                  '您当前是' +
                  res.data.setmeal_name +
                  '，当前可发布职位数为0，建议您立即升级套餐或将暂时不招聘的职位设为关闭！',
                confirmButtonText: '升级套餐',
                messageAlign: 'left'
              })
              .then(() => {
                this.$router.push('/member/order/add/common?type=setmeal')
              })
              .catch(() => {
                // on cancel
              })
            return false
          } else {
            this.$router.push('/member/company/jobadd')
          }
        })
        .catch(() => {
        })
    },
    /***
     * 聊天用户列表
     */
    getUserList () {
      http.post(api.chatList, {token: this.imToken}).then((res) => {
        if (res.code == 200) {
          this.$store.state.imUnreaded = false
          res.data.items.forEach(item => {
            if (item.new > 0) {
              this.$store.state.imUnreaded = true
            }
          })
        }
      })
    },
    // 全局检测
    imWindowGlobal () {
      http.post(api.im_window_global).then((res) => {
        if (res.data.next == '') {
          if (this.imToken == '') {
            this.getImToken()
          } else {
            this.getUserList()
          }
        }
      })
    },
    // 获取token
    getImToken () {
      http.get(api.imToken).then((res) => {
        this.setImToken(res.data)
        this.getUserList()
      })
    },
    /**
     * 【ID1000410】
     * 【优化】触屏端登录后底部
     * yx - 2022.11.08
     */
    initializeBarItem () {
      // 根据登录会员类型，处理导航显示
      if (this.$store.state.LoginOrNot) {
        this.imWindowGlobal()
        if (parseInt(this.$store.state.LoginType) === 1) {
          // 企业
          this.secondClass = 'resume'
          this.secondText = '找人才'
          this.secondTo = '/resumelist'
          this.thirdClass = 'im'
          this.thirdText = '消息'
          this.thirdTo = '/im/imList'
          this.mineTo = '/member/company/index'
        } else {
          // 个人
          this.plusSignTo = '/member/personal/resume'
          this.secondClass = 'job'
          this.secondText = '找工作'
          this.secondTo = '/joblist'
          this.thirdClass = 'im'
          this.thirdText = '消息'
          this.thirdTo = '/im/imList'
          this.mineTo = '/member/personal/index'
        }
      } else {
        // 未登录
        this.plusSignTo = '/member/personal/resume'
        this.secondClass = 'job'
        this.secondText = '找工作'
        this.secondTo = '/joblist'
        this.thirdClass = 'im'
        this.thirdText = '消息'
        this.thirdTo = '/im/imList'
        this.mineTo = '/member/personal/index'
      }
    }
  },
  computed: {
    ...mapState({
      imUnreaded: state => state.imUnreaded,
      imToken: state => state.imToken
    })
  },
  activated () {
    this.initializeBarItem()
  },
  mounted () {
    this.initializeBarItem()
  }
}
</script>

<style lang="scss" scoped>
.bottom_group {
  position: relative;
  width: 100%;
  height: 100px;

  .bottom_bar_wrapper {
    height: 60px;
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    background-image: url("../assets/images/nav/bottomurl.png");
    background-position: center;
    background-size: 100% 100%;
    z-index: 9;

    &::after {
      position: fixed;
      box-sizing: border-box;
      content: ' ';
      pointer-events: none;
      right: 0;
      bottom: 55px;
      left: 0;
      //border-bottom: 1PX solid #e2e2e2; -webkit-transform: scaleY(.5); transform: scaleY(.5);
    }

    .bar_item {
      &.im {
        background: url("../assets/images/nav/im.png") center 12px no-repeat;
        background-size: 20px 20px;
      }

      &.resume {
        background: url("../assets/images/nav/resume.png") center 12px no-repeat;
        background-size: 20px 20px;
      }

      &.job {
        background: url("../assets/images/nav/job.png") center 12px no-repeat;
        background-size: 20px 20px;
      }

      &.user {
        background: url("../assets/images/nav/user.png") center 12px no-repeat;
        background-size: 20px 20px;
      }

      &.home {
        background: url("../assets/images/nav/home.png") center 12px no-repeat;
        background-size: 20px 20px;
      }

      &.router-link-active {
        &.im {
          background: url("../assets/images/nav/im_active.png") center 12px no-repeat;
          background-size: 20px 20px;
        }

        &.resume {
          background: url("../assets/images/nav/resume_active.png") center 12px no-repeat;
          background-size: 20px 20px;
        }

        &.job {
          background: url("../assets/images/nav/job_active.png") center 12px no-repeat;
          background-size: 20px 20px;
        }

        &.user {
          background: url("../assets/images/nav/user_active.png") center 12px no-repeat;
          background-size: 20px 20px;
        }

        &.home {
          background: url("../assets/images/nav/home_active.png") center 12px no-repeat;
          background-size: 20px 20px;
        }

        color: #333333;
      }

      flex: 1;
      font-size: 10px;
      color: #999999;
      padding: 36.5px 0 6.5px;
      height: 55px;
      text-align: center;
    }
  }
}

.AplusSign {
  width: 72px;
  height: 72px;
  margin-top: -25px;
  background-image: url("../assets/images/nav/boturl.png");
  background-size: 100% 100%;
}

.d1 {
  width: 40%;
  display: flex;
}

.d2 {
  width: 20%;
  text-align: center;
  margin: 0 auto;
  display: flex;
}

.positon {
  width: 100%;
  height: 100%;
  position: relative;
  display: flex;
}

.informationNum {
  padding: 0px 5px;
  height: 20px;
  background: red;
  position: absolute;
  top: 3px;
  margin-left: 10%;
  border-radius: 50px;
  color: #ffffff;
}

.information {
  height: 7px;
  width: 7px;
  background: red;
  position: absolute;
  top: 10px;
  margin-left: 11%;
  border-radius: 50px;
  color: #ffffff;
}
</style>
