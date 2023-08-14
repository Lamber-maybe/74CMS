<template>
<div class="hp">
  <div class="b_73">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="j_qr_txt">长按二维码了解更多>></div>
      <div class="j_qr_box"><img :src="qrcode"/></div>
      <div class="j_name" :class="'n'+(index+1)" v-for="(item,index) in joblist" :key="index">{{item.jobname}}</div>
      <div class="j_bt_cn">{{joblist[0].companyname}}</div>
      <div class="j_bt_cn last">{{joblist[0].address}}</div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_7_3',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*7_3*/
.b_73{line-height:1;background:url("../../assets/images/posteimages/7_3_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_73 .j_tit{color:#ffffff;padding:15px 0 75px 18px;font-size: 16px;}
.b_73 .j_qr_txt{position:absolute;color:#ffffff;left:18px;top:45px;font-size: 12px;}
.b_73 .j_qr_box{position:absolute;left:18px;top:70px;width:50px;height:50px;overflow:hidden;}
.b_73 .j_qr_box img{width:50px;height:50px;border:0;background-color: #fff;}
.b_73 .j_name{color:#fff;font-size: 13px;}
.b_73 .j_name.n1{text-align:right;padding-right:103px;margin-bottom:128px;}
.b_73 .j_name.n2{text-align:left;padding-left:235px;margin-bottom:37px;}
.b_73 .j_name.n3{text-align:left;padding-left:188px;margin-bottom:21px;}
.b_73 .j_name.n4{text-align:left;padding-left:128px;margin-bottom:45px;}
.b_73 .j_name.n5{text-align:left;padding-left:128px;margin-bottom:46px}
.b_73 .j_name.n6{text-align:left;padding-left:88px;margin-bottom:26px;}
.b_73 .j_name.n7{text-align:right;padding-right:130px;margin-bottom:45px;}
.b_73 .j_bt_cn{text-align:right;color:#7b7b7b;margin-bottom:5px;padding-right:23px;font-size: 12px;}
.b_73 .j_bt_cn.last{margin-bottom:3px;}
.b_73 .j_bt_tx{color:#3e3e3e;text-align:right;padding-right:19px;}
</style>
