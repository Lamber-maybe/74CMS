<template>
  <div class="hp">
    <div class="b_12">
      <div class="micropost_img">
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
        <img :src="qrcode"/>
        </div>
        <div class="j_qr_txt">长按二维码了解更多>></div>
        <div class="j_bt_cn">{{joblist[0].companyname}}</div>
        <div class="j_bt_cn">{{joblist[0].district}}</div>
        <div class="j_bt_tx_b">
          <div class="j_bt_tx">{{$store.state.config.sitename}}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'microposte_1_2',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
/*1_2*/
.b_12{line-height:1;background:url("../../assets/images/posteimages/1_2_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;position:relative}
.b_12 .j_name{font-size:13px;padding:218px 0 0 70px;color:#6c6c6c;margin-bottom:5px;font-size:15px;font-weight:bold}
.b_12 .j_wage{font-size:13px;padding:0 0 0 82px;color:#6c6c6c;margin-bottom:5px;}
.b_12 .j_edg{font-size:13px;padding:0 0 0 97px;color:#6c6c6c;margin-bottom:10px;}
.b_12 .j_des{font-size:10px;color:#5e5e5e;line-height:1.3;width:200px;height:145px;overflow:hidden;word-break:break-all;padding-left:35px;margin-bottom:78px;line-height: 18px;}
.b_12 .j_qr_box{position:absolute;right:33px;bottom:50px;width:47px;height:47px;overflow:hidden;}
.b_12 .j_qr_box img{width:47px;height:47px;border:0;}
.b_12 .j_qr_txt{font-size:10px;position:absolute;color:#5e5e5e;left:35px;bottom:160px;background:#dbdbdb;padding:0 7px;border-radius:3px;height: 22px;line-height: 22px;}
.b_12 .j_bt_cn{font-size:12px;text-align:right;padding-right:89px;margin-bottom:2px;color:#4f4f4f;line-height:20px}
.b_12 .j_bt_tx_b{text-align:center;width:100%;}
.b_12 .j_bt_tx{font-size:10px;display:inline-block;padding:2px;color:#999}

</style>
