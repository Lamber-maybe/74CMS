<template>
<div class="hp">
  <div class="b_72">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="j_name substring" v-for="(item,index) in joblist" :key="index">{{item.jobname}}</div>
      <div class="j_bt_cn first">{{joblist[0].companyname}}</div>
      <div class="j_bt_cn">{{joblist[0].address}}</div>
      <div class="j_qr_txt">长按二维码了解更多>></div>
      <div class="j_qr_box"><div class="tp"><img :src="qrcode"/></div></div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_7_2',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*7_2*/
.b_72{line-height:1;background:url("../../assets/images/posteimages/7_2_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_72 .j_tit{color:#ffffff;text-align:right;padding:15px 15px 260px 0;font-size: 16px;}
.b_72 .j_name{color:#ffffff;width:342px;padding-left:164px;margin-bottom:7px;word-break:break-all;font-size: 13px;}
.b_72 .j_bt_cn{color:#ffffff;margin:0 0 7px 0;padding-left:105px;font-size: 12px;}
.b_72 .j_bt_cn.first{margin-top:96px;}
.b_72 .j_bt_tx{color:#ffffff;margin:0 0 2px 0;padding-left:105px;}
.b_72 .j_qr_box{position:absolute;left:40px;bottom:22px;width:55px;height:55px;overflow:hidden;text-align:center;background:#fff;border-radius:4px;}
.b_72 .j_qr_box .tp{position:relative;width:100%;height:100%;}
.b_72 .j_qr_box img{width:55px;height:55px;border:0;}
.b_72 .j_qr_txt{position:absolute;left:105px;color:#fff;font-size: 12px;}
</style>
