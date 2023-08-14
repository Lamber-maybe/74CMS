<template>
<div class="hp">
  <div class="b_83">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="j_name substring" :class="'n'+(index+1)" v-for="(item,index) in joblist" :key="index">{{item.jobname}}</div>
      <div class="j_bt_cn">{{joblist[0].companyname}}</div>
      <div class="j_bt_cn last">{{joblist[0].address}}</div>
      <div class="j_bt_tx">{{$store.state.config.sitename}}</div>
      <div class="j_qr_txt">长按了解更多>></div>
      <div class="j_qr_box"><img :src="qrcode"/></div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_8_3',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*8_3*/
.b_83{line-height:1;background:url("../../assets/images/posteimages/8_3_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_83 .j_tit{color:#ffffff;padding:171px 15px 315px 0;text-align:right;font-size: 16px;}
.b_83 .j_name{font-size: 11px;color:#fff;padding-top:18px;width:54px;height:56px;text-align:center;position:absolute;border-radius:100%;line-height:1;word-wrap:break-word;}
.b_83 .j_name.n1{left:42px;top:213px;}
.b_83 .j_name.n2{left:111px;top:213px;}
.b_83 .j_name.n3{left:182px;top:213px;}
.b_83 .j_name.n4{left:252px;top:213px;}
.b_83 .j_name.n5{left:42px;top:285px;}
.b_83 .j_name.n6{left:111px;top:285px;}
.b_83 .j_name.n7{left:182px;top:285px;}
.b_83 .j_name.n8{left:252px;top:285px;}
.b_83 .j_bt_cn{text-align:right;color:#fff;margin-bottom:7px;padding-right:111px;font-size: 12px;}
.b_83 .j_bt_cn.last{margin-bottom:40px;}
.b_83 .j_bt_tx{color:#002d3f;text-align:center;font-size: 10px;}
.b_83 .j_qr_txt{position:absolute;right:110px;bottom:40px;color:#fff;font-size: 10px;}
.b_83 .j_qr_box{position:absolute;right:19px;bottom:26px;width:73px;height:73px;overflow:hidden;font-size: 12px;}
.b_83 .j_qr_box img{width:73px;height:73px;border:0;}
</style>
