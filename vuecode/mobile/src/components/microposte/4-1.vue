<template>
<div class="hp">
  <div class="b_41">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="jn_box">
        <div class="jn_box_ce" :class="index>1?'no_mb':''" v-for="(item,index) in joblist" :key="index">
          <div class="j_name substring">{{item.jobname}}</div>
          <div class="j_n1_s1">薪资：{{item.wage_text}}/月</div>
          <div class="j_n1_s1">{{item.education_text}} | {{item.experience_text}}</div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="j_qr_box">
        <div class="tp">
          <img :src="qrcode"/>
        </div>
      </div>
      <div class="j_bt_cn">地址：{{joblist[0].address}}</div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_4_1',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*4_1*/
.b_41{line-height:1;background:url("../../assets/images/posteimages/4_1_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;}
.b_41 .j_tit{font-size:16px;text-align:center;color:#fff;padding:24px 0 350px;}
.b_41 .jn_box{padding:0 0 0 51px;margin-bottom:7px;}
.b_41 .jn_box_ce{float:left;position:relative;margin-bottom:38px;}
.b_41 .jn_box_ce.no_mb{margin-bottom:0;}
.b_41 .j_name{font-size:13px;position:absolute;top:-32px;left:-12px;width:120px;height:24px;color:#ffffff;padding:5px 0;text-align:center;}
.b_41 .j_n1_s1{font-size:12px;width:139px;height:12px;color:#000000;word-wrap:break-word;margin-bottom:2px;}
.b_41 .j_qr_txt{text-align:center;color:#2c84fa;margin-bottom:7px;}
.b_41 .j_qr_box{width:72px;height:72px;overflow:hidden;margin:0 auto;background:#fff;border-radius:4px;}
.b_41 .j_qr_box .tp{position:relative;width:100%;height:100%;}
.b_41 .j_qr_box img{width:70px;height:70px;border:0;}
.b_41 .j_bt_cn{font-size:12px;color:#fff;text-align:center;margin-top:6px;}

</style>
