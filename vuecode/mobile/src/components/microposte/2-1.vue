<template>
<div class="hp">
  <div class="b_21">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div v-for="(item,index) in joblist" :key="index">
        <div :class="'j_name_'+(index+1)">{{item.jobname}}</div>
        <div class="j_wage">薪资：{{item.wage_text}}/月</div>
        <div class="j_edg">{{item.education_text}}丨{{item.experience_text}}</div>
        <div class="j_des" :class="index==1?'last':''">
        任职要求：<br>
        {{item.content}}
        </div>
      </div>
      <div class="j_bt_tx_b"><div class="j_bt_tx">{{$store.state.config.sitename}}</div></div>
      <div class="j_bt_cn">{{joblist[0].companyname}}</div>
      <div class="j_bt_cn">{{joblist[0].address}}</div>
      <div class="j_qr_box"><img :src="qrcode"/></div>
      <div class="j_qr_txt">长按二维码了解更多>></div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_2_1',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
/*2_1*/
.b_21{line-height:1;background:url("../../assets/images/posteimages/2_1_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_21 .j_tit{font-size:16px;color:#000000;padding:26px 0 110px 20px;text-align:center}
.b_21 .j_name_1{font-size:13px;position:absolute;width:111px;height:22px;left:22px;top:120px;color:#000000;text-align:center;overflow:hidden;word-wrap:break-word;padding:5px 0;}
.b_21 .j_name_2{font-size:13px;position:absolute;width:111px;height:22px;left:22px;top:278px;color:#000000;text-align:center;overflow:hidden;word-wrap:break-word;padding:5px 0;}
.b_21 .j_wage{font-size:13px;padding:0 0 0 39px;color:#cd3027;margin-bottom:7px;}
.b_21 .j_wage span{color:#000000;}
.b_21 .j_edg{font-size:13px;padding:0 0 0 39px;color:#000000;margin-bottom:8px;}
.b_21 .j_des{font-size:10px;color:#000000;line-height:19px;width:180px;height:89px;overflow:hidden;word-break:break-all;padding-left:39px;margin-bottom:32px;}
.b_21 .j_des.last{margin-bottom:60px;}
.b_21 .j_bt_tx_b{margin-bottom:20px;}
.b_21 .j_bt_tx{font-size:10px;text-align:right;padding-right:17px;margin-bottom:2px;color:#818181;padding-left:175px}
.b_21 .j_bt_cn{font-size:12px;width:335px;text-align:left;padding-left:135px;margin-bottom:2px;color:#4f4f4f;overflow:hidden;line-height:20px}
.b_21 .j_qr_box{position:absolute;left:37px;bottom:20px;width:88px;height:88px;overflow:hidden;background:#fff;border-radius:4px;}
.b_21 .j_qr_box .jib{width:100%;height:100%;position:relative}
.b_21 .j_qr_box img{width:88px;height:88px}
.b_21 .jib img{position:absolute;left:50%;top:50%;width:76px;height:76px;border:0;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);}
.b_21 .j_qr_txt{font-size:10px;position:absolute;color:#d14425;left:35px;bottom:115px;}

</style>
