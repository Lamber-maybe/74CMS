<template>
<div class="hp">
  <div class="b_23">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="j_qr_box"><img :src="qrcode"/></div>
      <!--职位1-->
      <div class="j_name_1">{{joblist[0].jobname}}</div>
      <div class="j_n1_sl">{{joblist[0].education_text}}<br />—{{joblist[0].experience_text}}</div>
      <div class="j_n1_s2">薪资<br />：{{joblist[0].wage_text}}/月</div>

      <!--职位2-->
      <div class="j_name_2">{{joblist[1].jobname}}</div>
      <div class="j_n1_s3">{{joblist[1].education_text}}<br />—{{joblist[1].experience_text}}</div>
      <div class="j_n1_s4">薪资<br />：{{joblist[1].wage_text}}/月</div>

      <div class="j_qr_txt">长按了解更多>></div>
      <div class="j_bt_cn">{{joblist[0].companyname}}</div>
      <div class="j_bt_dr">{{joblist[0].address}}</div>

    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_2_3',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
/*2_3*/
.b_23{line-height:1;background:url("../../assets/images/posteimages/2_3_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_23 .j_tit{font-size:16px;text-align:center;color:#712c25;padding:31px 25px;}
.b_23 .j_qr_box{position:absolute;left:25px;bottom:32px;width:60px;height:60px;overflow:hidden;}
.b_23 .j_qr_box img{width:60px;height:60px;border:0;}
.b_23 .j_qr_txt{font-size:10px;position:absolute;color:#792926;left:15px;bottom:107px;}
.b_23 .j_bt_cn{font-size:12px;position:absolute;color:#352839;left:100px;bottom:70px;width:235px;line-height:1.3;}
.b_23 .j_bt_dr{font-size:12px;position:absolute;color:#352839;left:100px;bottom:40px;width:235px;line-height:1.3;}
.b_23 .j_bt_tx{position:absolute;color:#352839;right:19px;bottom:9px;}
.b_23 .j_name_1{font-size:13px;position:absolute;width:16px;height:120px;left:28px;top:164px;color:#742e28;text-align:center;padding:9px 0;overflow:hidden;word-wrap:break-word;}
.b_23 .j_name_2{font-size:13px;position:absolute;width:16px;height:120px;right:26px;top:320px;color:#352639;text-align:center;padding:9px 0;overflow:hidden;word-wrap:break-word;}
.b_23 .j_n1_sl{font-size:12px;position:absolute;width:15px;left:80px;top:220px;color:#732c26;text-align:center;word-wrap:break-word;}
.b_23 .j_n1_s2{font-size:12px;position:absolute;width:15px;left:60px;top:220px;color:#732c26;text-align:center;word-wrap:break-word;}
.b_23 .j_n1_s3{font-size:12px;position:absolute;width:15px;right:60px;top:360px;color:#352839;text-align:center;word-wrap:break-word;}
.b_23 .j_n1_s4{font-size:12px;position:absolute;width:15px;right:80px;top:360px;color:#352839;text-align:center;word-wrap:break-word;}

</style>
