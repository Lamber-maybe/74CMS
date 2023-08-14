<template>
<div class="hp">
  <div class="b_42">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="jn_box">
        <div class="jn_box_ce" :class="index>1?'no_mb':''" v-for="(item,index) in joblist" :key="index">
          <div class="j_name substring">{{item.jobname}}</div>
          <div class="j_n1_s1">薪资：{{item.wage_text}}/月</div>
          <div class="j_n1_s1">{{item.education_text}} | {{item.experience_text}}</div>
        </div>
        <div class="clear"></div>
        </div>
        <div class="j_bt_cn">{{joblist[0].companyname}}</div>
        <div class="j_bt_cn">{{joblist[0].address}}</div>
        <div class="j_bt_tx">{{$store.state.config.sitename}}</div>
        <div class="j_qr_box">
        <div class="tp"><img :src="qrcode"/></div>
        <div class="j_qr_txt"></div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_4_2',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}

/*4_2*/
.b_42{line-height:1;background:url("../../assets/images/posteimages/4_2_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_42 .j_tit{font-size:16px;text-align:center;color:#dfbc62;padding:29px 0 296px;}
.b_42 .jn_box{padding:0 0 0 51px}
.b_42 .jn_box_ce{float:left;position:relative;margin-bottom:46px;}
.b_42 .jn_box_ce.no_mb{margin-bottom:21px;}
.b_42 .j_name{font-size:13px;position:absolute;top:-29px;left:0;width:126px;height:24px;color:#e0bc64;padding:0;}
.b_42 .j_n1_s1{font-size:12px;width:126px;height:14px;color:#9b9b9b;word-wrap:break-word;margin-bottom:4px;}
.b_42 .j_bt_cn{font-size:12px;color:#9b9b9b;width:361px;margin-bottom:15px;padding-left:141px}
.b_42 .j_bt_tx{font-size:10px;text-align:center;color:#777777;margin-top:40px;}
.b_42 .j_qr_box{width:72px;overflow:hidden;text-align:center;position:absolute;left:55px;bottom:50px;}
.b_42 .j_qr_box .tp{position:relative;width:72px;height:72px;background:#fff;border-radius:4px;}
.b_42 .j_qr_box img{width:70px;height:70px;border:0;}
.b_42 .j_qr_txt{font-size:10px;color:#949494;margin-top:5px;}

</style>
