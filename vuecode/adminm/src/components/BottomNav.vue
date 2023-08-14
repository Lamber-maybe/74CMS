<template>
  <div class="bottom_group">
    <div class="bottom_bar_wrapper">
      <router-link class="bar_item home" to="/index">数据首页</router-link>
      <router-link class="bar_item user" to="/user">会员管理</router-link>
      <router-link class="bar_item business" to="/business">业务管理</router-link>
      <router-link class="bar_item stats" to="/stats">数据统计</router-link>
      <router-link class="bar_item config" to="/config">系统工具</router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BottomNav'
}
</script>

<style lang="scss" scoped>
.bottom_group {
  position: relative;
  width: 100%;
  height: 120px;
  margin-top: 10px;
  .bottom_bar_wrapper {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ffffff;
    display: flex;
    z-index: 9;
    &::after {
      position: fixed; box-sizing: border-box; content: ' '; pointer-events: none; right: 0; bottom: 120px; left: 0;
      border-bottom: 1PX solid #e2e2e2; -webkit-transform: scaleY(.5); transform: scaleY(.5);
    }
    .bar_item {
      &.stats { background: url("../assets/images/nav/stats_1.png") center 20px no-repeat; background-size: 33px 33px; }
      &.config { background: url("../assets/images/nav/config_1.png") center 20px no-repeat; background-size: 33px 33px; }
      &.business { background: url("../assets/images/nav/business_1.png") center 20px no-repeat; background-size: 35px 33px; }
      &.user { background: url("../assets/images/nav/user_1.png") center 20px no-repeat; background-size: 36px 33px; }
      &.home { background: url("../assets/images/nav/home_1.png") center 20px no-repeat; background-size: 36px 33px; }
      &.router-link-active {
        &.stats { background: url("../assets/images/nav/stats_2.png") center 20px no-repeat; background-size: 33px 33px; }
        &.config { background: url("../assets/images/nav/config_2.png") center 20px no-repeat; background-size: 33px 33px; }
        &.business { background: url("../assets/images/nav/business_2.png") center 20px no-repeat; background-size: 35px 33px; }
        &.user { background: url("../assets/images/nav/user_2.png") center 20px no-repeat; background-size: 36px 33px; }
        &.home { background: url("../assets/images/nav/home_2.png") center 20px no-repeat; background-size: 36px 33px; }
        color: #1787fb;
      }
      flex: 1;
      font-size: 26px;
      color: #666666;
      padding: 70px 0 13px;
      height: 120px;
      text-align: center;
    }
  }
}
</style>
