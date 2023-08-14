<template>
  <div class="hp">
    <div class="b_13">
      <div class="micropost_img">
        <div class="j_tit">{{joblist[0].companyname}}</div>
        <div class="j_name">{{joblist[0].jobname}}</div>
        <div class="j_wage">薪资：{{joblist[0].wage_text}}/月</div>

        <div class="j_des">
        任职要求：<br>
        {{joblist[0].content}}
        </div>
        <div class="j_qr_box"><img :src="qrcode"/></div>
        <div class="j_qr_txt">长按二维码了解更多>></div>
        <div class="j_bt_cn">{{joblist[0].companyname}}</div>
        <div class="j_bt_dr">{{joblist[0].address}}</div>
        <div class="j_bt_tx">{{$store.state.config.sitename}}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'microposte_1_3',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
/*1_3*/
.b_13{line-height:1;background:url("../../assets/images/posteimages/1_3_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;position:relative}
.b_13 .j_tit{font-size:16px;text-align:center;color:#fff;padding:31px 44px;}
.b_13 .j_name{font-size:13px;position:absolute;top:180px;left:60px;color:#eacc00;width:177px;overflow:hidden;}
.b_13 .j_wage{font-size:13px;position:absolute;top:200px;left:60px;color:#eacc00;}
.b_13 .j_des{font-size:10px;position:absolute;top:225px;left:75px;color:#fff;line-height:1.1;width:180px;height:65px;overflow:hidden;word-break:break-all;line-height: 17px;}
.b_13 .j_qr_box{position:absolute;left:31px;bottom:56px;width:80px;height:80px;overflow:hidden;}
.b_13 .j_qr_box img{width:80px;height:80px;border:0;}
.b_13 .j_qr_txt{font-size:10px;position:absolute;color:#fff;left:20px;bottom:160px;}
.b_13 .j_bt_cn{font-size:12px;position:absolute;color:#fff;left:134px;bottom:74px;width:215px;line-height:1.3;}
.b_13 .j_bt_dr{font-size:12px;position:absolute;color:#fff;left:134px;bottom:55px;width:215px;line-height:1.3;}
.b_13 .j_bt_tx{font-size:10px;position:absolute;color:#959595;right:19px;bottom:25px;width:90%;text-align:center}

</style>
