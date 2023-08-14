<template>
  <div class="bottom_group">
    <div class="bottom_bar_wrapper">
      <router-link class="bar_item home" to="/index">首页</router-link>
      <router-link :class="'bar_item ' + secondClass" :to="secondTo">
        {{ secondText }}
      </router-link>
      <router-link :class="'bar_item ' + thirdClass" :to="thirdTo">
        {{ thirdText }}
      </router-link>
      <router-link class="bar_item user" :to="mineTo">
        我的
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BottomNav',
  data () {
    return {
      secondClass: 'job',
      secondText: '职位',
      secondTo: '/joblist',
      thirdClass: 'resume',
      thirdText: '简历',
      thirdTo: '/resumelist',
      mineTo: '/member/login'
    }
  },
  mounted () {
    // 根据登录会员类型，处理导航显示
    if (this.$store.state.LoginOrNot) {
      if (parseInt(this.$store.state.LoginType) === 1) {
        // 企业
        this.secondClass = 'resume'
        this.secondText = '找人才'
        this.secondTo = '/resumelist'
        this.thirdClass = 'personal'
        this.thirdText = '候选人'
        this.thirdTo = '/member/company/jobapply'
        this.mineTo = '/member/company/index'
      } else {
        // 个人
        this.secondClass = 'job'
        this.secondText = '找工作'
        this.secondTo = '/joblist'
        this.thirdClass = 'company'
        this.thirdText = '搜企业'
        this.thirdTo = '/companylist'
        this.mineTo = '/member/personal/index'
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bottom_group {
  position: relative;
  width: 100%;
  height: 55px;
  .bottom_bar_wrapper {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ffffff;
    display: flex;
    z-index: 9;
    &::after {
      position: fixed; box-sizing: border-box; content: ' '; pointer-events: none; right: 0; bottom: 55px; left: 0;
      border-bottom: 1PX solid #e2e2e2; -webkit-transform: scaleY(.5); transform: scaleY(.5);
    }
    .bar_item {
      &.personal {
        background: url("../assets/images/nav/personal.png") center 9px
          no-repeat;
        background-size: 29px 25px;
      }
      &.company {
        background: url("../assets/images/nav/company.png") center 9px no-repeat;
        background-size: 29px 25px;
      }
      &.resume {
        background: url("../assets/images/nav/resume.png") center 9px no-repeat;
        background-size: 29px 25px;
      }
      &.job {
        background: url("../assets/images/nav/job.png") center 8px no-repeat;
        background-size: 29px 25px;
      }
      &.user {
        background: url("../assets/images/nav/user.png") center 9px no-repeat;
        background-size: 29px 25px;
      }
      &.home {
        background: url("../assets/images/nav/home.png") center 9px no-repeat;
        background-size: 29px 25px;
      }
      &.router-link-active {
        &.personal {
          background: url("../assets/images/nav/personal_active.png") center 9px
            no-repeat;
          background-size: 29px 25px;
        }
        &.company {
          background: url("../assets/images/nav/company_active.png") center 9px
            no-repeat;
          background-size: 29px 25px;
        }
        &.resume {
          background: url("../assets/images/nav/resume_active.png") center 9px
            no-repeat;
          background-size: 29px 25px;
        }
        &.job {
          background: url("../assets/images/nav/job_active.png") center 8px
            no-repeat;
          background-size: 29px 25px;
        }
        &.user {
          background: url("../assets/images/nav/user_active.png") center 9px
            no-repeat;
          background-size: 29px 25px;
        }
        &.home {
          background: url("../assets/images/nav/home_active.png") center 9px
            no-repeat;
          background-size: 29px 25px;
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
</style>
