<template>
  <div>
    <Head :show-back="false" :show-admin="true" @accessMobile="accessMobile">业务管理</Head>
    <div v-if="access_mobile=='all' || access_mobile.setmeal == 1 || access_mobile.order_company == 1 || access_mobile.setmeal_add == 1 || access_mobile.promotion_job_add == 1 || access_mobile.points_company == 1" class="public_item_title">企业业务管理</div>
    <div class="b1">
      <div class="bc_line">
        <div v-if="access_mobile=='all' || access_mobile.setmeal == 1" class="bc_cell c1" @click="$router.push('/setmeal')"><p class="t1">套餐管理</p></div>
        <div v-if="access_mobile=='all' || access_mobile.order_company == 1" class="bc_cell c2" @click="$router.push('/order/1')"><p class="t1">订单管理</p></div>
        <div v-if="access_mobile=='all' || access_mobile.setmeal_add == 1" class="bc_cell c3" @click="$router.push('/setmeal_add')"><p class="t1">开通套餐</p></div>
      </div>
      <div class="bc_line">
        <div v-if="access_mobile=='all' || access_mobile.promotion_job_add == 1" class="bc_cell c4" @click="$router.push('/promotion_add')"><p class="t1">新增推广</p></div>
        <div v-if="access_mobile=='all' || access_mobile.points_company == 1" class="bc_cell c5" @click="$router.push('/points')"><p class="t1">积分充值</p></div>
<!--        <div class="bc_cell c9"></div>-->
      </div>
    </div>
    <div v-if="access_mobile=='all' || access_mobile.order_personal == 1 || access_mobile.promotion_resume_add == 1" class="public_item_title">个人业务管理</div>
    <div class="b1">
      <div class="bc_line">
        <div v-if="access_mobile=='all' || access_mobile.order_personal == 1" class="bc_cell c6" @click="$router.push('/order/2')"><p class="t1">订单管理</p></div>
        <div v-if="access_mobile=='all' || access_mobile.promotion_resume_add == 1" class="bc_cell c8" @click="$router.push('/promotion_resume_add')"><p class="t1">新增推广</p></div>
<!--        <div class="bc_cell c10"></div>-->
      </div>
    </div>
    <BottomNav></BottomNav>
  </div>
</template>

<script>
  export default {
    name: "businessIndex",
    data() {
      return {
        access_mobile: {
          'setmeal' : 0,
          'order_company' : 0,
          'setmeal_add' : 0,
          'promotion_job_add' : 0,
          'points_company' : 0,
          'order_personal' : 0,
          'promotion_resume_add' : 0
        }
      };
    },
    methods: {
      accessMobile(val){
        if (val == 'all'){
          this.access_mobile = val
        } else {
          let checkedKeys = val.checkedKeys
          for (const element of checkedKeys) {
            this.access_mobile[element] = 1
          }
        }
      }
    }
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
        &.c1 { background: url("../../assets/images/business/1.png") center 52px no-repeat; background-size: 70px 55px; }
        &.c2 { background: url("../../assets/images/business/2.png") center 49px no-repeat; background-size: 56px 65px; }
        &.c3 { background: url("../../assets/images/business/3.png") center 54px no-repeat; background-size: 60px 51px; }
        &.c4 { background: url("../../assets/images/business/4.png") center 50px no-repeat; background-size: 64px 60px; }
        &.c5 { background: url("../../assets/images/business/5.png") center 46px no-repeat; background-size: 60px 59px; }
        &.c6 { background: url("../../assets/images/business/6.png") center 44px no-repeat; background-size: 56px 65px; }
        &.c7 { background: url("../../assets/images/business/7.png") center 42px no-repeat; background-size: 60px 59px; }
        &.c8 { background: url("../../assets/images/business/8.png") center 43px no-repeat; background-size: 64px 60px; }
      }
    }
  }
  .public_item_title {
    width: 100%;height: 70px;line-height: 70px;padding-left: 28px;background-color: #f3f3f3;font-size: 26px;
    color: #999999;
  }
</style>
