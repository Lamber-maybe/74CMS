<template>
  <div>
    <Head :show-back="false" :show-admin="true">会员管理</Head>
    <div class="public_item_title">会员用户管理</div>
    <div class="b1">
      <div class="bc_line">
        <div class="bc_cell c1" @click="$router.push('/user_list/1')"><p class="t1">企业会员</p></div>
        <div class="bc_cell c2" @click="$router.push('/user_list/2')"><p class="t1">个人会员</p></div>
        <div class="bc_cell c3" @click="$router.push('/user_list/0')"><p class="t1">无效会员</p></div>
      </div>
    </div>
    <div class="public_item_title">个人简历管理</div>
    <div class="b1">
      <div class="bc_line">
        <div class="bc_cell c4" @click="$router.push('/resume_list/0')"><p class="t1">未审核简历</p></div>
        <div class="bc_cell c5" @click="$router.push('/resume_list/1')"><p class="t1">简历管理</p></div>
        <div class="bc_cell c6" @click="$router.push('/photo_list')"><p class="t1">照片/作品</p></div>
      </div>
    </div>
    <div class="public_item_title">企业信息管理</div>
    <div class="b1">
      <div class="bc_line">
        <div class="bc_cell c7" @click="$router.push('/company_list/0')"><p class="t1">待审核企业</p></div>
        <div class="bc_cell c8" @click="$router.push('/company_list/1')"><p class="t1">企业管理</p></div>
        <div class="bc_cell c9" @click="$router.push('/job_list/0')"><p class="t1">待审核职位</p></div>
      </div>
      <div class="bc_line">
        <div class="bc_cell c10" @click="$router.push('/job_list/1')"><p class="t1">职位管理</p></div>
        <div class="bc_cell c11" @click="$router.push('/companyimg_list')"><p class="t1">企业风采</p></div>
        <div class="bc_cell c12" @click="$router.push('/cancellation')"><p class="t1">账号注销申请</p></div>
      </div>
    </div>
    <BottomNav></BottomNav>
  </div>
</template>

<script>
  export default {
    name: "userIndex"
  }
</script>

<style lang="scss" scoped>
  .b1 {
    width: 100%;background-color: #ffffff;
    .bc_line {
      display: flex;border-top: 1PX solid #f2f2f2;
      &:nth-of-type(1) { border-top: 0; }
      .bc_cell {
        flex: 1;border-right: 1PX solid #f2f2f2;text-align: center;height: 210px;padding-top: 132px;
        .t1 { font-size: 26px;color: #333333; }
        &:nth-of-type(3n) { border-right:0; }
        &.c1 { background: url("../../assets/images/user/1.png") center 45px no-repeat; background-size: 73px 63px; }
        &.c2 { background: url("../../assets/images/user/2.png") center 45px no-repeat; background-size: 72px 63px; }
        &.c3 { background: url("../../assets/images/user/3.png") center 44px no-repeat; background-size: 60px 68px; }
        &.c4 { background: url("../../assets/images/user/4.png") center 50px no-repeat; background-size: 65px 60px; }
        &.c5 { background: url("../../assets/images/user/5.png") center 46px no-repeat; background-size: 65px 62px; }
        &.c6 { background: url("../../assets/images/user/6.png") center 44px no-repeat; background-size: 69px 60px; }
        &.c7 { background: url("../../assets/images/user/7.png") center 42px no-repeat; background-size: 53px 65px; }
        &.c8 { background: url("../../assets/images/user/8.png") center 43px no-repeat; background-size: 62px 60px; }
        &.c9 { background: url("../../assets/images/user/9.png") center 42px no-repeat; background-size: 60px 61px; }
        &.c10 { background: url("../../assets/images/user/10.png") center 50px no-repeat; background-size: 59px 59px; }
        &.c11 { background: url("../../assets/images/user/11.png") center 49px no-repeat; background-size: 69px 60px; }
        &.c12 { background: url("../../assets/images/user/12.png") center 49px no-repeat; background-size: 60px 60px; }
      }
    }
  }
  .public_item_title {
    width: 100%;height: 70px;line-height: 70px;padding-left: 28px;background-color: #f3f3f3;font-size: 26px;
    color: #999999;
  }
</style>
