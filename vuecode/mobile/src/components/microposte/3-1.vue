<template>
<div class="hp">
  <div class="b_31">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div v-for="(item,index) in joblist" :key="index">
        <div class="j_name substring">{{item.jobname}}</div>
        <div class="j_n1_s1 substring">薪资：{{item.wage_text}}/月</div>
        <div class="j_n1_s1 substring">{{item.education_text}} | {{item.experience_text}}</div>
        <div class="j_n1_hsp" v-if="index<2"></div>
      </div>
      <div class="j_qr_box">
        <div class="tp"><img :src="qrcode"/></div>
      </div>
      <div class="j_qr_txt">长按了解更多>></div>
      <div class="j_bt_cn">{{joblist[0].companyname}}</div>
      <div class="j_bt_cn">{{joblist[0].address}}</div>
      <div class="j_bt_tx">{{$store.state.config.sitename}}</div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_3_1',
  props: ['joblist', 'qrcode']
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}
/*3_1*/
.b_31{line-height:1;background:url("../../assets/images/posteimages/3_1_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;}
.b_31 .j_tit{font-size:16px;color:#000000;padding:28px 0 0 38px;line-height:40px;width:193px;height:80px;overflow:hidden;margin-bottom:124px;}
.b_31 .j_name{font-size:13px;width:210px;height:15px;padding:0 0 0 22px;color:#fff;word-wrap:break-word;margin-bottom:2px;}
.b_31 .j_n1_s1{font-size:12px;width:210px;height:14px;padding:0 0 0 22px;color:#fff;word-wrap:break-word;margin-bottom:2px;}
.b_31 .j_n1_s1.last{margin-bottom:18px;}
.b_31 .j_n1_hsp{width:168px;height:1px;margin:7px 0 10px 22px;background:#fff;}
.b_31 .j_qr_box{width:74px;height:74px;overflow:hidden;background:#fff;border-radius:4px;margin:20px 0 7px 22px;}
.b_31 .j_qr_box .tp{position:relative;width:100%;height:100%;}
.b_31 .j_qr_box img{width:74px;height:74px;border:0}
.b_31 .j_qr_txt{font-size:10px;padding-left:22px;color:#fff;margin-bottom:24px;}
.b_31 .j_bt_cn{font-size:12px;color:#fff;padding-left:22px;margin-bottom:10px;}
.b_31 .j_bt_tx{font-size:10px;color:#fff;padding-right:7px;text-align:center;margin-top:20px;}

</style>
