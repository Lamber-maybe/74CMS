<template>
<div class="hp">
  <div class="b_52">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="j_qr_box">
        <div class="tp"><img :src="qrcode"/></div>
        <div class="j_qr_txt">长按二维码了解更多>></div>
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
  name: 'microposte_5_2',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*5_2*/
.b_52 {line-height:1;background:url("../../assets/images/posteimages/5_2_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_52 .j_tit{color:#0e70d5;padding:16px 0 441px 13px;font-size: 16px;}
.b_52 .j_qr_box{width:76px;height:76px;overflow:hidden;text-align:center;background:#fff;border-radius:4px;margin:0 auto;}
.b_52 .j_qr_box .tp{position:relative;width:100%;height:100%;}
.b_52 .j_qr_box img{width:75px;height:75px;border:0;}
.b_52 .j_qr_txt{color:#00427b;margin-top:5px;text-align:center;}
.b_52 .j_name{font-size:10px;color:#ffffff;text-align:left;font-weight:600;position:absolute;top:0;left:0;width:110px;height:22px;overflow:hidden;padding:5px 0;}
.b_52 .j_name.n1{top:295px;left:50px;}
.b_52 .j_name.n2{top:295px;left:185px;}
.b_52 .j_name.n3{top:353px;left:50px;}
.b_52 .j_name.n4{top:353px;left:185px;}
.b_52 .j_name.n5{top:413px;left:116px;}
.b_52 .j_n1_s{font-size:10px;color:#ffffff;position:absolute;top:0;left:0;width:106px;}
.b_52 .j_n1_s.n1{top:315px;left:50px;}
.b_52 .j_n1_s.n2{top:315px;left:185px;}
.b_52 .j_n1_s.n3{top:373px;left:50px;}
.b_52 .j_n1_s.n4{top:373px;left:185px;}
.b_52 .j_n1_s.n5{top:433px;left:116px;}
.b_52 .j_n1_e{font-size:10px;color:#ffffff;position:absolute;top:0;left:0;width:106px;}
.b_52 .j_n1_e.n1{top:330px;left:50px;}
.b_52 .j_n1_e.n2{top:330px;left:185px;}
.b_52 .j_n1_e.n3{top:389px;left:50px;}
.b_52 .j_n1_e.n4{top:389px;left:185px;}
.b_52 .j_n1_e.n5{top:450px;left:116px;}
.b_52 .j_bt_cn{font-size:10px;color:#00427b;text-align:center;margin-top:7px;}
.b_52 .j_bt_tx{color:#00427b;text-align:center;margin-top:7px;}
</style>
