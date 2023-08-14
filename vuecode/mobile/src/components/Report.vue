<template>
  <div id="app">
    <Head :goback_custom="true" @gobackCustomMethod="$emit('closePopout')">企业实地认证报告</Head>
    <div class="re_box">
      <div class="re_ico"></div>
      <div class="re_tx1">企业实地认证报告</div>
      <div class="re_tx2">认证情况</div>
      <div class="re_tx3">{{reportInfo.companyname}}</div>
      <div class="re_tx4">
        <div class="t_bar">企业营业执照</div><div class="t_bar">企业地址信息</div>
        <div class="t_bar">企业办公环境</div><div class="t_bar">企业招聘信息</div>
        <div class="t_bar">企业福利待遇</div><div class="t_bar">企业资料信息</div>
        <div class="clear"></div>
      </div>
      <div class="re_tx5">人力资源师评价</div>
      <div class="re_tx6">{{reportInfo.evaluation}}</div>
      <div class="re_tx7">认证时间:{{reportInfo.addtime}}</div>
      <div class="re_tx7">{{$store.state.config.sitename}}认证师：{{reportInfo.certifier}}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Report',
  props: ['reportInfo']
}
</script>

<style lang="scss" scoped>
  .re_box {
    width: 375px;
    height: auto;
    background: url("../assets/images/report_bg.png") center center no-repeat;
    background-size: 375px auto;
    padding-top: 15px;
    .re_ico {
      width: 58px;height: 48px;margin: 0 auto 5px;
      background: url("../assets/images/report_sd_ico.png") 0 0 no-repeat;
      background-size: 58px 48px;
    }
    .re_tx1 {
      text-align: center;font-size: 24px;font-weight: bold;color: #cc3e34;margin-bottom: 8px;
    }
    .re_tx2 {
      text-align: center;font-size: 14px;color: #666;margin-bottom: 8px;
    }
    .re_tx3 {
      text-align: center;font-size: 15px;color: #333;margin-bottom: 30px;
    }
    .re_tx4 {
      padding: 0 40px;margin-bottom: 15px;
      .t_bar {
        float: left;width: 50%;position: relative;font-size: 18px;font-weight: bold;color: #333;
        background: url("../assets/images/report_finish_ico.png") 113px center no-repeat;
        background-size: 19px 15px;margin-bottom: 8px;
        &:nth-of-type(2n){
          padding-left: 15px;
          background: url("../assets/images/report_finish_ico.png") 128px center no-repeat;
          background-size: 19px 15px;
        }
      }
    }
    .re_tx5 {
      text-align: center;font-size: 18px;font-weight: bold;color: #cc3e34;margin-bottom: 15px;
    }
    .re_tx6 {
      font-size: 15px;color: #666;padding-bottom: 30px;line-height: 1.7;margin: 0 auto 20px;width: 340px;
      background: url("../assets/images/report_seal.png") right bottom no-repeat;
      background-size: 75px 75px;
    }
    .re_tx7 {
      font-size: 13px;color: #999;padding: 0 17px;margin-bottom: 5px;text-align: right;
    }
  }
</style>
