<template>
<div class="hp">
  <div class="b_62">
    <div class="micropost_img">
      <div class="j_tit font16">[ {{joblist[0].companyname}} ]</div>
      <div class="j_name substring" :class="'n'+(index+1)" v-for="(item,index) in joblist" :key="index">{{item.jobname}}</div>
      <div class="j_bt_cn">{{joblist[0].companyname}}</div>
      <div class="j_bt_cn last">{{joblist[0].address}}</div>
      <div class="j_qr_box"><div class="tp">
        <img :src="qrcode"/>
      </div></div>
      <div class="j_qr_txt">长按二维码了解更多>></div>
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

/*6_2*/
.b_62{line-height:1;background:url("../../assets/images/posteimages/6_2_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_62 .j_tit{text-align:center;color:#000000;padding:15px 0 455px;font-size: 16px;}
.b_62 .j_name{position:absolute;width:104px;height:35px;padding:11px 0;text-align:center;color:#da4300;font-size: 13px;}
.b_62 .j_name.n1{left:70px;top:360px;}
.b_62 .j_name.n2{left:182px;top:360px;}
.b_62 .j_name.n3{left:70px;top:400px;}
.b_62 .j_name.n4{left:182px;top:400px;}
.b_62 .j_name.n5{left:70px;top:440px;}
.b_62 .j_name.n6{left:182px;top:440px;}
.b_62 .j_bt_cn{font-size: 12px;color:#231816;margin-bottom:5px;padding-right:55px;padding-left: 150px;}
.b_62 .j_bt_tx{font-size: 12px;color:#ffec7a;text-align:center;margin-top:55px;}
.b_62 .j_qr_box{position:absolute;left:73px;bottom:50px;width:65px;height:65px;overflow:hidden;text-align:center;background:#fff;border-radius:4px;}
.b_62 .j_qr_box .tp{position:relative;width:100%;height:100%;}
.b_62 .j_qr_box img{width:65px;height:65px;border:0;}
.b_62 .j_qr_txt{font-size: 10px;position:absolute;right: 85px;bottom: 55px;;color:#010101;}
</style>
