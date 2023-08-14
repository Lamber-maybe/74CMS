<template>
<div class="hp">
  <div class="b_43">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div v-for="(item,index) in joblist" :key="index">
        <div class="j_name substring">{{item.jobname}}</div>
        <div class="j_n1_s1 substring">薪资：{{item.wage_text}}/月</div>
        <div class="j_n1_s1 substring" :class="index<3?'last':'zh'">{{item.education_text}} | {{item.experience_text}}</div>
      </div>
      <div class="j_bt_cn last">{{joblist[0].address}}</div>
      <div class="j_bt_tx">网站名称</div>
      <div class="j_qr_txt">长按了解更多>></div>
      <div class="j_qr_box"><img :src="qrcode"/></div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_4_3',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*4_3*/
.b_43{line-height:1;background:url("../../assets/images/posteimages/4_3_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_43 .j_tit{font-size:16px;text-align:center;color:#f6bf77;padding:33px 0 210px;}
.b_43 .j_name{font-size:13px;width:120px;height:28px;padding:7px 15px 9px 8px;color:#002951;word-wrap:break-word;margin:0 0 5px 190px;background:url("../../assets/images/posteimages/b_43_name.png") no-repeat;background-size:121px 28px;}
.b_43 .j_n1_s1{font-size:11px;width:350px;height:14px;padding:0 0 0 190px;color:#ffffff;word-wrap:break-word;margin-bottom:4px;}
.b_43 .j_n1_s1.last{margin-bottom:5px;}
.b_43 .j_n1_s1.zh{margin-bottom:15px;}
.b_43 .j_bt_cn{font-size:12px;color:#fff;text-align:center;margin-bottom:5px;}
.b_43 .j_bt_cn.last{margin-bottom:10px;}
.b_43 .j_bt_tx{font-size:10px;color:#ceaa59;text-align:center}
.b_43 .j_qr_box{position:absolute;left:60px;bottom:95px;width:65px;height:65px;overflow:hidden;}
.b_43 .j_qr_box img{width:65px;height:65px;border:0;}
.b_43 .j_qr_txt{font-size:10px;position:absolute;color:#f3c078;left:50px;bottom:75px;}

</style>
