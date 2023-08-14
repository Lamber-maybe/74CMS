<template>
<div class="hp">
  <div class="b_32">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="j_tit_f">{{joblist[0].address}}</div>
      <div class="jn_box">
        <div v-for="(item,index) in joblist" :key="index">
          <div class="j_n1_s1">{{item.education_text}}<br />—{{item.experience_text}}</div>
          <div class="j_n1_s1">薪资<br />：{{item.wage_text}}/月</div>
          <div class="j_name" :class="index==2?'last':''">{{item.jobname}}</div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="j_qr_box">
        <div class="tp"><img :src="qrcode"/></div>
      </div>
      <div class="j_qr_txt">长按识别二维码了解更多>></div>
      <div class="j_bt_tx">{{$store.state.config.mobile_domain}}</div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_3_2',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
/*3_2*/
.b_32{line-height:1;background:url("../../assets/images/posteimages/3_2_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;}
.b_32 .j_tit{font-size:16px;color:#000000;padding:27px 0 13px 0;text-align:center;}
.b_32 .j_tit_f{font-size:12px;color:#1b1b1d;padding:0 0 305px 0;text-align:center;}
.b_32 .jn_box{width:290px;margin:0 auto 5px 60px;}
.b_32 .j_name{font-size:13px;float:left;width:18px;padding:5px 0 5px 0;color:#212026;word-wrap:break-word;text-align:center;background:#eac030;margin:0 10px 0 5px;}
.b_32 .j_name.last{margin-right:0;}
.b_32 .j_n1_s1{font-size:12px;float:left;width:15px;height:145px;text-align:center;padding:5px 0 5px 0;color:#212025;word-wrap:break-word;overflow:hidden;margin-right: 7px;}
.b_32 .j_qr_box{width:61px;height:61px;overflow:hidden;background:#fff;margin:0 auto 7px;}
.b_32 .j_qr_box .tp{position:relative;width:100%;height:100%;}
.b_32 .j_qr_box img{width:61px;height:61px;border:0;}
.b_32 .j_qr_txt{font-size:10px;text-align:center;color:#e9c12e;margin-bottom:6px;}
.b_32 .j_bt_tx{font-size:10px;text-align:center;color:#e9c12e;}

</style>
