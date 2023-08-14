<template>
  <div class="payment_wrapper">
      <div class="payment_header">
          <p>
            <span>订单编号：</span>{{$route.query.oid}}
          </p>
          <p>
            <span>支付金额：</span> <span class="price_text">￥{{$route.query.amount}}</span>
          </p>
      </div>
      <div class="payment_content">
          <div>
            <div class="payment_img">
              <img :src="qrcode" style="width:174px;height:174px;"/>
              <span></span>
              <span></span>
              <span></span>
              <span></span>
            </div>
            <div class="img_btn">打开微信扫一扫完成付款</div>
          </div>
          <div class="process">
              <div>
                <span>1</span>
                <img src="../assets/images/personal/wx_payment_icon_1.png" />
                <p>打开微信<br/>扫一扫</p>
              </div>
              <div>
                <span>2</span>
                <img src="../assets/images/personal/wx_payment_icon_2.png" />
                <p>扫描左侧<br/>二维码</p>
              </div>
              <div>
                <span>3</span>
                <img src="../assets/images/personal/wx_payment_icon_3.png" />
                <p>点击立即支付<br/>完成付款</p>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'WxpayQrcode',
  data(){
    return {
      timer:'',
      qrcode:'',
      custom_location: 0,
      success_url: ''
    }
  },
  created(){
    clearInterval(this.timer);
    this.qrcode = window.global.RequestBaseUrl + api.get_qrcode + '?type=normal&url=' + this.$route.query.parameter
    this.custom_location = this.$route.query.custom_location
    this.success_url = this.$route.query.success_url
  },
  mounted() {
    this.timer = setInterval(this.scan, 3000);
  },
  methods:{
    scan () {
      var that = this
      http
        .post(api.scan_wxpay_result, {oid:this.$route.query.oid})
        .then(res => {
          if(parseInt(res.data) === 1){
            if(parseInt(that.custom_location)) {
              if (that.success_url.startsWith('http')) {
                location.href = that.success_url
              } else {
                that.$router.push(this.success_url)
              }
            } else {
              if (parseInt(this.$store.state.LoginType) === 1) {
                this.$router.push('/company/service/order')
              } else {
                this.$router.push('/personal/service/order')
              }
            }
          }
        })
        .catch(() => {})
    },
  },
  beforeDestroy() {
    clearInterval(this.timer);
  }
}
</script>

<style lang="scss" scoped>
  .payment_wrapper{
    background: #fff;
    margin-top: 10px;
    padding: 20px 0;
  }
  .payment_header{
      padding: 50px 68px;
      border-bottom: 1px solid #eeeeee;
  }
  .payment_header p:nth-of-type(1){
    margin-bottom: 18px;
  }
  .payment_header p{
   color: #333333;
  //  font-size: 12px;
   }
  .payment_header p span{
    font-size: 14px;
  }
  .payment_header .price_text{
    color:#ff6600 ;
    font-size:14px ;
    font-weight: bold;
  }
  .payment_content{
    padding:  70px 110px 110px 110px ;
    display: flex;
  }
  .payment_img{
    position: relative;
    width: 246px;
    height: 246px;
    border: 1px solid #eeeeee;
  }
  .payment_img span{
    display: block;
    width: 36px;
    height: 36px;
    position: absolute;
  }
  .payment_img span:nth-of-type(1){
    left: -4px;
    top: -3px;
    border-top:7px solid #eeeeee;
    border-left:7px solid #eeeeee;
  }
  .payment_img span:nth-of-type(2){
    right: -4px;
    top: -3px;
    border-top:7px solid #eeeeee;
    border-right:7px solid #eeeeee;
  }
  .payment_img span:nth-of-type(3){
    left: -4px;
    bottom: -3px;
    border-bottom:7px solid #eeeeee;
    border-left:7px solid #eeeeee;
  }
  .payment_img span:nth-of-type(4){
    right: -4px;
    bottom: -3px;
    border-bottom:7px solid #eeeeee;
    border-right:7px solid #eeeeee;
  }
  .payment_img img{
    margin: 36px 36px;
  }
  .img_btn{
    width:240px ;
    background:#00a13a ;
    color:#ffffff ;
    font-size:12px ;
    padding: 14px 44px;
    text-align: center;
    margin-top:17px ;
  }
  .process{
    width: 692px;
    background:#00a13a ;
    height: 300px;
    margin-left: 20px;
    display: flex;
    padding: 0 40px;
   }
   .process div{
     flex: 1;
     text-align: center;
     color: #ffffff;
     font-size: 14px;
   }
   .process div img{
     margin-bottom: 32px;
   }
   .process div span{
     display: block;
     width: 40px;
     height: 40px;
     border-radius: 50%;
     background: #fff;
     color:#00a13a ;
     line-height: 40px;
     font-size: 14px;
     font-weight: bold;
     margin: 48px auto 30px;
   }
</style>
