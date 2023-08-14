<template>
<div class="hp">
  <div class="b_22">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="j_qr_box">
      <img :src="qrcode"/>
      <div class="j_qr_txt">长按了解更多>></div>
      </div>
      <div class="jn_box" :class="index==1?'t':''" v-for="(item,index) in joblist" :key="index">
        <div class="j_name">{{item.jobname}}</div>
        <div class="j_wage">薪资：{{item.wage_text}}/月</div>
        <div class="j_edg">{{item.education_text}}丨{{item.experience_text}}</div>
      </div>
      <div class="j_bt_cn">{{joblist[0].companyname}}</div>
      <div class="j_bt_cn">{{joblist[0].address}}</div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_2_2',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
/*2_2*/
.b_22{line-height:1;background:url("../../assets/images/posteimages/2_2_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_22 .j_tit{font-size:16px;color:#ffffff;padding:30px 0 318px 0;text-align:center}
.b_22 .j_qr_box{position:absolute;left:114px;top:222px;overflow:hidden;width:81px;text-align:center;transform: rotate(15deg)}
.b_22 .j_qr_box img{width:64px;height:64px}
.b_22 .j_qr_box .j_qr_txt{font-size:10px;color:#004995;}
.b_22 .jn_box{width:194px;height:74px;border:1px dotted #00cbf0;border-radius:4px;margin:0 auto 25px;padding:0 7px;position:relative;overflow:hidden;}
.b_22 .j_name{font-size:14px;width:178px;height:20px;color:#ffffff;text-align:right;overflow:hidden;word-wrap:break-word;font-weight:bold;line-height:20px;margin-top:6px}
.b_22 .j_wage{font-size:13px;color:#ffab86;height:22px;text-align:right;line-height:22px}
.b_22 .j_edg{font-size:13px;color:#ffab86;height:22px;text-align:right;margin-bottom:7px;line-height:22px}
.b_22 .jn_box .j_rz{position:absolute;color:#ffffff;top:46px;left:7px;font-size:10px;}
.b_22 .j_des{font-size:10px;color:#ffffff;line-height:1.3;height:16px;width:199px;word-break:break-all;overflow:hidden}
.b_22 .j_des.last{margin-bottom:75px;}
.b_22 .jn_box.t{margin-bottom:10px;}
.b_22 .jn_box.t .j_name{text-align:left}
.b_22 .jn_box.t .j_wage{text-align:left}
.b_22 .jn_box.t .j_edg{text-align:left}
.b_22 .jn_box.t .j_rz{position:absolute;color:#ffffff;top:46px;right:7px;text-align:right}
.b_22 .j_bt_cn{font-size:10px;text-align:center;margin-bottom:6px;color:#00cbf0;}
.b_22 .j_bt_tx{text-align:center;margin-top:10px;color:#0068d5;}

</style>
