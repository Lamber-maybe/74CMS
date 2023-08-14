<template>
<div class="hp">
  <div class="b_33">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div v-for="(item,index) in joblist" :key="index">
        <div class="j_name substring">{{item.jobname}}</div>
        <div class="j_n1_s1 substring">薪资：{{item.wage_text}}/月</div>
        <div class="j_n1_s1 substring" :class="index==2?'last':''">
        {{item.education_text}} | {{item.experience_text}}
        </div>
        <div class="j_n1_hsp" v-if="index<2"></div>
      </div>
      <div class="j_bt_cn comname">{{joblist[0].companyname}}</div>
      <div class="j_bt_cn">{{joblist[0].address}}</div>
      <div class="j_qr_box"><img :src="qrcode"/></div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_3_3',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}
/*3_3*/
.b_33{line-height:1;background:url("../../assets/images/posteimages/3_3_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_33 .j_tit{font-size:16px;text-align:center;color:#ffffff;padding:50px 0 235px;}
.b_33 .j_name{font-size:14px;width:210px;height:15px;padding:0 0 0 60px;color:#125fe0;word-wrap:break-word;margin-bottom:2px;}
.b_33 .j_n1_s1{font-size:12px;width:210px;height:14px;padding:0 0 0 60px;color:#125fe0;word-wrap:break-word;margin-bottom:2px;}
.b_33 .j_n1_s1.last{margin-bottom:25px;}
.b_33 .j_n1_hsp{width:140px;height:1px;margin:7px 0 10px 54px;background:#125fe0;}
.b_33 .j_bt_tx{color:#c9ddfe;margin:0 0 30px 0;padding-left:55px;}
.b_33 .j_bt_cn{font-size:12px;color:#fff;text-align:right;padding-right:90px;margin-bottom:15px;}
.b_33 .j_bt_cn.comname{padding-top:0px}
.b_33 .j_qr_box{position:absolute;right:21px;bottom:43px;width:61px;height:61px;overflow:hidden;}
.b_33 .j_qr_box img{width:61px;height:61px;border:0;}

</style>
