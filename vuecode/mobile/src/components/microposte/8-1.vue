<template>
<div class="hp">
  <div class="b_81" id="micropost">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="j_name substring" :class="'n'+(index+1)" v-for="(item,index) in joblist" :key="index">{{item.jobname}}</div>
      <div class="j_qr_box"><div class="tp"><img :src="qrcode"/></div></div>
      <div class="j_qr_txt">长按二维码了解更多>></div>
      <div class="j_bt_cn last">{{joblist[0].companyname}}</div>
      <div class="j_bt_tx">{{joblist[0].address}}</div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_8_1',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*8_1*/
.b_81{line-height:1;background:url("../../assets/images/posteimages/8_1_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_81 .j_tit{color:#2d3f3f;padding:100px 31px 260px 0;text-align:center;font-size: 16px;}
.b_81 .j_name{color:#d0327c;width:103px;position:absolute;word-wrap:break-word;overflow:hidden;font-size: 13px;}
.b_81 .j_name.n1{left:60px;top:272px;text-align:right;}
.b_81 .j_name.n2{left:178px;top:272px;}
.b_81 .j_name.n3{left:60px;top:296px;text-align:right;}
.b_81 .j_name.n4{left:178px;top:296px;}
.b_81 .j_name.n5{left:60px;top:321px;text-align:right;}
.b_81 .j_name.n6{left:178px;top:321px;}
.b_81 .j_name.n7{left:60px;top:343px;text-align:right;}
.b_81 .j_name.n8{left:178px;top:343px;}
.b_81 .j_qr_box{width:72px;height:72px;overflow:hidden;text-align:center;background:#fff;border-radius:4px;margin:0 auto 7px;}
.b_81 .j_qr_box .tp{position:relative;width:100%;height:100%;}
.b_81 .j_qr_box img{width:71px;height:71px;border:0;}
.b_81 .j_qr_txt{color:#005063;text-align:center;margin-bottom:7px;font-size: 12px;}
.b_81 .j_bt_cn{text-align:center;color:#005063;margin-bottom:3px;font-size: 12px;}
.b_81 .j_bt_tx{color:#005063;text-align:center;margin-top:5px;font-size: 10px;}
</style>
