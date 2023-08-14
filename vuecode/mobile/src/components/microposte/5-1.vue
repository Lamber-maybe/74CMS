<template>
<div class="hp">
  <div class="b_51">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="j_qr_box">
        <div class="tp"><img :src="qrcode"/></div>
        <div class="j_qr_txt">长按了解更多>></div>
      </div>
      <div v-for="(item,index) in joblist" :key="index">
        <div class="j_name substring" :class="'n'+(index+1)">{{item.jobname}}</div>
        <div class="j_n1_s substring" :class="'n'+(index+1)">薪资：{{item.wage_text}}/月</div>
        <div class="j_n1_e substring" :class="'n'+(index+1)">{{item.education_text}} | {{item.experience_text}}</div>
        <div class="j_n1_hsp"></div><div class="clear"></div>
      </div>
      <div class="j_bt_cn">{{joblist[0].companyname}}</div>
      <div class="j_bt_cn last">地址：{{joblist[0].address}}</div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_5_1',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}
/*5_1*/
.b_51{line-height:1;background:url("../../assets/images/posteimages/5_1_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_51 .j_tit{font-size:16px;text-align:center;color:#324120;padding:24px 0 500px 0;}
.b_51 .j_qr_box{width:60px;overflow:hidden;text-align:center;position:absolute;left:185px;top:200px;}
.b_51 .j_qr_box .tp{position:relative;width:60px;height:60px;background:#fff;border-radius:4px;}
.b_51 .j_qr_box img{width:60px;height:60px;border:0;}
.b_51 .j_qr_txt{font-size:10px;color:#4a6f2b;margin-top:5px;}
.b_51 .j_name{font-size:13px;color:#221717;text-align:center;font-weight:600;position:absolute;top:0;left:0;width:106px;height:22px;overflow:hidden;padding:5px 0;}
.b_51 .j_name.n1{top:340px;left:67px;}
.b_51 .j_name.n2{top:314px;left:194px;}
.b_51 .j_name.n3{top:460px;left:2px;}
.b_51 .j_name.n4{top:440px;left:110px;}
.b_51 .j_name.n5{top:426px;left:220px;}
.b_51 .j_n1_s{font-size:10px;color:#231816;position:absolute;top:0;left:0;width:106px;}
.b_51 .j_n1_s.n1{top:375px;left:80px;}
.b_51 .j_n1_s.n2{top:347px;left:203px;}
.b_51 .j_n1_s.n3{top:496px;left:16px;}
.b_51 .j_n1_s.n4{top:474px;left:128px;}
.b_51 .j_n1_s.n5{top:460px;left:236px;}
.b_51 .j_n1_e{font-size:10px;color:#231816;position:absolute;top:0;left:0;width:106px;}
.b_51 .j_n1_e.n1{top:389px;left:80px;}
.b_51 .j_n1_e.n2{top:362px;left:203px;}
.b_51 .j_n1_e.n3{top:510px;left:16px;}
.b_51 .j_n1_e.n4{top:490px;left:128px;}
.b_51 .j_n1_e.n5{top:474px;left:236px;}
.b_51 .j_bt_cn{font-size:12px;color:#231816;text-align:right;padding-right:25px;line-height:20px}
</style>
