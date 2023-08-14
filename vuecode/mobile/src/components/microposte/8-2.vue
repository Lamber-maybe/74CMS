<template>
<div class="hp">
  <div class="b_82">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="j_name" :class="'n'+(index+1)" v-for="(item,index) in joblist" :key="index">{{item.jobname}}</div>
      <div class="j_qr_box"><div class="tp"><img :src="qrcode"/></div></div>
      <div class="j_qr_txt">长按了解更多>></div>
      <div class="j_bt_cn last">{{joblist[0].companyname}}</div>
      <div class="j_bt_tx">{{joblist[0].address}}</div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_8_2',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*8_2*/
.b_82{background:url("../../assets/images/posteimages/8_2_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_82 .j_tit{color:#000000;padding:13px 0 400px 20px;font-size: 16px;}
.b_82 .j_name{line-height:1;font-size: 13px;color:#bc0000;width:43px;height:165px;position:absolute;word-wrap:break-word;overflow:hidden;text-align:center;padding:9px;}
.b_82 .j_name.n1{left:40px;top:200px;}
.b_82 .j_name.n2{left:80px;top:200px;}
.b_82 .j_name.n3{left:121px;top:200px;}
.b_82 .j_name.n4{left:165px;top:200px;}
.b_82 .j_name.n5{left:210px;top:200px;}
.b_82 .j_name.n6{left:256px;top:200px;}
.b_82 .j_name.n7{left:153px;top:350px;}
.b_82 .j_name.n8{left:200px;top:350px;}
.b_82 .j_qr_box{width:84px;height:84px;overflow:hidden;text-align:center;background:#fff;border-radius:4px;margin:0 0 7px 240px;}
.b_82 .j_qr_box .tp{position:relative;width:100%;height:100%;}
.b_82 .j_qr_box img{width:85px;height:85px;border:0;}
.b_82 .j_qr_txt{color:#000000;text-align:right;padding-right:10px;margin-bottom:8px;font-size: 12px;}
.b_82 .j_bt_cn{color:#000000;margin-bottom:2px;text-align:center;font-size: 12px;}
.b_82 .j_bt_tx{color:#000000;margin-top:5px;text-align:center;font-size: 10px;}
</style>
