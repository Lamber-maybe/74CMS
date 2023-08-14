<template>
<div class="hp">
  <div class="b_61">
    <div class="micropost_img">
      <div class="j_tit">{{joblist[0].companyname}}</div>
      <div class="j_tit_f">第{{dateFormat()}}期</div>
      <div class="j_tit_f">{{$store.state.config.sitename}}</div>
      <div v-for="(item,index) in joblist" :key="index">
        <div class="j_name substring" :class="'n'+(index+1)">{{item.jobname}}</div>
        <div class="j_name_w substring" :class="'n'+(index+1)">薪资：{{item.wage_text}}/月</div>
      </div>
      <div class="j_qr_txt">长按了解更多>></div>
      <div class="font12 j_bt_cn">{{joblist[0].companyname}}</div>
      <div class="font12 j_bt_cn last">{{joblist[0].address}}</div>
      <div class="j_qr_box"><div class="tp"><img :src="qrcode"/></div></div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'microposte_6_1',
  props: ['joblist', 'qrcode'],
  methods: {
    dateFormat () {
      let date = new Date()
      return date.getFullYear() + '' + (date.getMonth() + 1) + '' + date.getDate()
    }
  }
}
</script>
<style scoped>
.hp{
  padding:0 20px;
}
.substring{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}
/*6_1*/
.b_61{background:url("../../assets/images/posteimages/6_1_bg.jpg") no-repeat;width:335px;height:595px;background-size:335px 595px;padding:0;position:relative}
.b_61 .j_tit{color:#fff;padding:15px 0 64px 20px;font-size:16px;}
.b_61 .j_tit_f{width:118px;margin:0 0 0 212px;text-align:center;color:#000000;font-size: 10px;}
.b_61 .j_name{position:absolute;width:115px;height:30px;padding:11px 0 0;text-align:center;color:#ce242d;font-size: 13px;}
.b_61 .j_name.n1{left:76px;top:330px;}
.b_61 .j_name.n2{left:192px;top:330px;}
.b_61 .j_name.n3{left:76px;top:375px;}
.b_61 .j_name.n4{left:192px;top:375px;}
.b_61 .j_name.n5{left:76px;top:425px;}
.b_61 .j_name.n6{left:192px;top:425px;}
.b_61 .j_name_w{position:absolute;width:117px;height:19px;padding:5px 0 0;text-align:center;color:#221717;font-size: 12px;}
.b_61 .j_name_w.n1{left:76px;top:355px;}
.b_61 .j_name_w.n2{left:192px;top:355px;}
.b_61 .j_name_w.n3{left:76px;top:402px;}
.b_61 .j_name_w.n4{left:192px;top:402px;}
.b_61 .j_name_w.n5{left:76px;top:452px;}
.b_61 .j_name_w.n6{left:192px;top:452px;}
.b_61 .j_qr_txt{text-align:right;color:#010101;margin:398px 0 2px 0;padding-right:86px;font-size: 10px;}
.b_61 .j_bt_cn{text-align:right;color:#010101;margin-bottom:2px;padding-right:86px;font-size: 12px;}
.b_61 .j_bt_tx{color:#010101;padding:6px 0 0 16px;font-size: 12px;}
.b_61 .j_qr_box{position:absolute;right:11px;bottom:11px;width:61px;height:61px;overflow:hidden;text-align:center;background:#fff;border-radius:4px;}
.b_61 .j_qr_box .tp{position:relative;width:100%;height:100%;}
.b_61 .j_qr_box img{width:60px;height:60px;border:0;}
</style>
