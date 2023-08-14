<template>
  <div class="hp">
    <div class="b_11">
      <div class="micropost_img">
        <div class="j_tit">{{joblist[0].companyname}}</div>
        <div class="j_name">{{joblist[0].jobname}}</div>
        <div class="j_wage">薪资：{{joblist[0].wage_text}}/月</div>
        <div class="j_edg">
          {{joblist[0].education_text}}丨
          {{joblist[0].experience_text}}
        </div>
        <div class="j_des">
          任职要求：<br>
          {{joblist[0].content}}
        </div>
        <div class="j_qr_box">
        <img :src="qrcode"/></div>
        <div class="j_qr_txt">长按二维码了解更多>></div>
        <div class="j_bt_tx_b">
          <div class="j_bt_tx">{{$store.state.config.sitename}}</div>
        </div>
        <div class="j_bt_cn">{{joblist[0].companyname}}</div>
        <div class="j_bt_cn">{{joblist[0].address}}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'microposte_1_1',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
/*1_1*/
.b_11{line-height:1;background:url("../../assets/images/posteimages/1_1_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;position:relative}
.b_11 .j_tit{font-size:16px;position:absolute;top:158px;right:128px;text-align:center;color:#f6f2e7;width:20px;}
.b_11{line-height:1;background:url("../../assets/images/posteimages/1_1_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;position:relative}
.b_11 .j_name{font-size:13px;padding:235px 0 0 39px;color:#f6f2e7;margin-bottom:5px;font-weight:bold;}
.b_11 .j_wage{font-size:13px;padding:0 0 0 39px;color:#f6f2e7;margin-bottom:5px;}
.b_11 .j_edg{font-size:13px;padding:0 0 0 39px;color:#f6f2e7;margin-bottom:10px;}
.b_11 .j_des{font-size:10px;color:#f6f2e7;line-height:18px;width:200px;height:105px;overflow:hidden;word-break:break-all;padding-left:39px;margin-bottom:70px;}
.b_11 .j_qr_txt{font-size:10px;position:absolute;color:#cd3027;left:34px;bottom:165px;background:#f6f2e7;padding:0 7px;border-radius:3px;height: 22px;
    line-height: 22px;}
.b_11 .j_qr_box{position:absolute;left:41px;bottom:71px;width:70px;height:70px;overflow:hidden;}
.b_11 .j_qr_box img{width:70px;height:70px;border:0;}
.b_11 .j_bt_tx_b{margin-bottom:55px;}
.b_11 .j_bt_tx{font-size:10px;text-align:right;padding-right:20px;margin-bottom:2px;color:#cd3027;height:20px;line-height:20px;padding-left: 210px;}
.b_11 .j_bt_cn{font-size:12px;text-align:left;padding-left:38px;margin-bottom:2px;color:#ffffff;line-height:20px}

</style>
