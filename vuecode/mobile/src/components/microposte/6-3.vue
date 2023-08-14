<template>
<div class="hp">
  <div class="b_63">
    <div class="micropost_img">
      <div class="j_tit font16">{{joblist[0].companyname}}</div>
      <div class="j_name substring" :class="'n'+(index+1)" v-for="(item,index) in joblist" :key="index">{{item.jobname}}</div>
      <div class="j_qr_txt">长按二维码了解更多>></div>
      <div class="j_bt_cn">{{joblist[0].companyname}}</div>
      <div class="j_bt_cn last">{{joblist[0].address}}</div>
      <div class="j_qr_box">
        <img :src="qrcode"/>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_6_2',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*6_3*/
.b_63{line-height:1;background:url("../../assets/images/posteimages/6_3_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_63 .j_bt_tx{color:#046e6d;text-align:center;padding:11px 0 20px;font-size:12px;}
.b_63 .j_tit{text-align:center;color:#00c8c5;padding:15px 0 495px;font-size:16px;}
.b_63 .j_name{position:absolute;width:110px;padding:12px 0;text-align:center;color:#fff;font-size:13px;}
.b_63 .j_name.n1{left:116px;top:256px;}
.b_63 .j_name.n2{left:60px;top:301px;}
.b_63 .j_name.n3{left:172px;top:302px;}
.b_63 .j_name.n4{left:60px;top:349px;}
.b_63 .j_name.n5{left:172px;top:349px;}
.b_63 .j_name.n6{left:116px;top:396px;}
.b_63 .j_qr_txt{text-align:center;color:#00c4c1;margin-bottom:5px;font-size:12px;}
.b_63 .j_bt_cn{text-align:center;color:#ffffff;margin-bottom:5px;font-size:12px;}
.b_63 .j_qr_box{position:absolute;left:146px;bottom:90px;width:61px;height:61px;overflow:hidden;}
.b_63 .j_qr_box img{width:61px;height:61px;border:0;}
</style>
