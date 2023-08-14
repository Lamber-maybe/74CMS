<template>
    <div class="add_finish">
      <div class="f_wra">
        <div class="f_content">
          <div class="t1">恭喜您，简历创建完成</div>
          <div class="t2">您的求职竞争力 <span class="orange">较低</span></div>
          <div class="t3">请继续完善简历，助您快速高效面试</div>
          <div class="t4">
            <el-button type="primary" @click="$router.push('/personal/resume')">继续完善简历</el-button>
            <el-button plain @click="$router.push('/personal')">进入会员中心</el-button>
          </div>
        </div>
      </div>
      <div class="f_scan">
        <div class="f_qr">
          <div class="qr_title">微信扫一扫，满意工作轻松找</div>
          <div class="qr_t1">扫描右边二维码，关注 <span class="blue">{{$store.state.config.sitename}}</span>-官方微信公众号，</div>
          <div class="qr_t1">简历投递、人才推荐早知道</div>
          <div class="qr_box"><img :src="$store.state.config.wechat_qrcode"></div>
        </div>
      </div>
    </div>
</template>

<script>
  export default {
    name: 'AddResumeStep3',
  }
</script>

<style lang="scss" scoped>
  .add_finish {
    width: 1200px; margin-top: 15px;
    .orange { color: #ff5d24; }
    .blue { color: #1787fb; }
    .f_wra {
      width: 1200px; background-color: #fff; padding: 125px 0 159px 0;
      .f_content {
        width: 470px; margin: 0 auto; padding-left: 121px; background: url("../../assets/images/member/8.png") 0 0 no-repeat;
        .t1 { font-size: 24px; color: #333; margin-bottom: 18px; }
        .t2 { font-size: 16px; color: #333; margin-bottom: 7px; }
        .t3 { font-size: 16px; color: #333; margin-bottom: 33px; }
      }
    }
    .f_scan {
      width: 1200px; background-color: #fff; margin-top: 15px; padding: 55px 0 60px;
      .f_qr {
        width: 480px; margin: 0 auto; position: relative; padding: 9px 0;
        .qr_title { font-size: 18px; color: #333; margin-bottom: 13px; }
        .qr_t1 { color: #999; }
        .qr_box {
          position: absolute; right: 0; top: 0; width: 90px; height: 90px; padding: 5px; border: 1px solid #f3f3f3;
          img { width: 80px; height: 80px; border: 0; }
        }
      }
    }
  }
</style>
