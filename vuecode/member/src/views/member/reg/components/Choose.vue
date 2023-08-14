<template>
    <div class="choose_box">
      <div class="b_title">请选择注册身份</div>
      <div class="b_group">
        <router-link to="/reg/personal" class="b_item">
          <div class="b_ico"></div><div class="b_btn">注册求职者会员</div><div class="b_des">我是求职者，我要找工作</div>
        </router-link>
        <router-link to="/reg/company" class="b_item right">
          <div class="b_ico c2"></div><div class="b_btn">注册企业招聘会员</div><div class="b_des">我是企业/个体户，我要招人才</div>
        </router-link>
        <div class="clear"></div>
      </div>
    </div>
</template>

<script>
  export default {
    name: 'Choose',
  }
</script>

<style lang="scss" scoped>
  .choose_box {
    width: 1200px; height: 610px; background-color: #fff; margin-top: 15px;
    .b_title { font-size: 24px; color: #333; text-align: center; padding: 75px 0 60px; }
    .b_group {
      width: 1000px; margin: 0 auto;
      .b_item {
        float: left; width: 450px; height: 350px; background-color: #fcfcfc; border: 1px dashed #c3c3c3; display: block;
        text-align: center;
        &:hover { text-decoration: none; }
        &.right { float: right; }
        .b_ico {
          background: url("../../../../assets/images/member/1.png") center center no-repeat; width: 100%; height: 227px;
          &.c2 { background: url("../../../../assets/images/member/2.png") center center no-repeat; }
        }
        .b_btn {
          width: 220px; margin: 0 auto 12px; background-color: #1787fb; color: #fff; font-size: 18px; padding: 8.5px 0;
          border-radius: 40px;
        }
        .b_des { color: #999; }
      }
    }
  }
</style>
