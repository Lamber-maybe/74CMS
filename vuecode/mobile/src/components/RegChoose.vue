<template>
  <div id="app">
    <Head></Head>
    <div class="tx1">请选择注册身份</div>
    <div class="choose_cell">
      <div class="choose_typ1"></div>
      <div class="btn_wrapper">
        <van-button
          class="choose_btn"
          round
          type="info"
          @click="$router.push('/member/reg/personal')"
          >注册求职者会员</van-button
        >
      </div>
      <div class="tx2">我是求职者，我要找工作</div>
    </div>
    <div class="choose_cell">
      <div class="choose_typ2"></div>
      <div class="btn_wrapper2">
        <van-button
          class="choose_btn"
          round
          type="info"
          @click="$router.push('/member/reg/company')"
          >注册企业招聘会员</van-button
        >
      </div>
      <div class="tx2">我是企业/个体户，我要招人才</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegChoose'
}
</script>

<style lang="scss" scoped>
$color_3: #333333;
.tx1 {
  padding: 31px 0;
  text-align: center;
  font-size: 26px;
  color: $color_3;
}
.choose_cell {
  width: 341px;
  background-color: #fcfcfc;
  border: 1px dotted #c3c3c3;
  margin: 0 auto 18px;
  padding-top: 25px;
}
.choose_typ1 {
  width: 136px;
  height: 108.5px;
  margin: 0 auto 25px;
  background: url("../assets/images/choose_member_type1.png") 0 no-repeat;
  background-size: 136px 108.5px;
}
.tx2 {
  font-size: 13px;
  color: #999999;
  text-align: center;
  padding: 15px 0 31px;
}
.btn_wrapper {
  text-align: center;
  .van-button--normal {
    padding: 0 0.6rem;
  }
}
.choose_typ2 {
  width: 129.5px;
  height: 74.5px;
  margin: 0 auto 25px;
  background: url("../assets/images/choose_member_type2.png") 0 no-repeat;
  background-size: 129.5px 74.5px;
}
.btn_wrapper2 {
  text-align: center;
  .van-button--normal {
    padding: 0 0.8rem;
  }
}
.choose_btn {
  width: 170px;
  height: 37px;
  color: #ffffff;
  line-height: 1;
  .van-button__text {
    font-size: 13px;
  }
}
</style>
