<template>
<div class="hp">
  <div class="b_53">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="fivaddress">{{joblist[0].address}}</div>
      <div v-for="(item,index) in joblist" :key="index">
        <div class="j_name_1 substring">{{item.jobname}}</div>
        <div class="j_n1_s1 substring">薪资：{{item.wage_text}}/月</div>
        <div class="j_n1_s1">{{item.education_text}} | {{item.experience_text}}</div>
        <div class="j_n1_hsp"></div><div class="clear"></div>
      </div>
      <div class="fontag j_bt_cn">了解更多>></div>
      <div class="j_bt_tx font10">{{$store.state.config.sitename}}</div>
      <div class="j_qr_txt fontag"></div>
      <div class="j_qr_box"><img :src="qrcode"/></div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_5_3',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*5_3*/
.b_53{background:url("../../assets/images/posteimages/5_3_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_53 .j_tit{text-align:right;color:#000000;padding:15px 20px 3px;font-size:16px;}
.b_53 .j_name_1{font-size:13px;padding:0 20px 0 0;color:#000000;word-wrap:break-word;margin-bottom:2px;text-align:right;font-weight:600;}
.b_53 .j_n1_s1{font-size:12px;padding:0 20px 0 0;color:#000000;word-wrap:break-word;margin-bottom:2px;text-align:right;}
.b_53 .j_n1_hsp{width:7px;height:3px;margin:5px 32px 8px 0;background:#000000;float:right;}
.b_53 .j_bt_cn{font-size:12px;color:#000000;text-align:right;padding-right:117px;margin:50px 0 35px 0;}
.b_53 .j_bt_cn.last{margin:0 0 25px 0;}
.b_53 .j_bt_tx{font-size:12px;color:#000000;text-align:center;}
.b_53 .j_qr_txt{font-size:12px;position:absolute;color:#000000;right:12px;bottom:35px;}
.b_53 .j_qr_box{position:absolute;right:30px;bottom:50px;width:65px;height:65px;overflow:hidden;}
.b_53 .j_qr_box img{width:65px;height:65px;border:0;}
.fivaddress{text-align:right;margin-right:20px;color:#000000;margin-bottom:30px;font-size:12px;}
</style>
