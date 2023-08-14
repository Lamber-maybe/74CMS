<template>
  <div id="app">
    <div class="list" v-if="daylist.length>0">
      <div class="item" :class="dayid&&dayid==item.index?'selitem':''" v-for="(item,index) in daylist" :key="index" @click="onday(item.index,item.fee)">
        <div class="name">发布{{item.days}}天</div>
        <div class="money">￥<span>{{item.fee}}</span></div>
        <div class="center">{{item.tip}}</div>
      </div>
    </div>
    <div class="box_pay_pop">
      <van-radio-group v-model="payment">
        <div class="pay_item wx" @click="choosePayment('wxpay')">
          微信支付
          <van-radio
            class="self_check"
            name="wxpay"
            icon-size="18px"
          ></van-radio>
        </div>
        <div class="pay_item ali" @click="choosePayment('alipay')">
          支付宝支付
          <van-radio
            class="self_check"
            name="alipay"
            icon-size="18px"
          ></van-radio>
        </div>
      </van-radio-group>
      <div class="pay_num">应付金额：<span class="for">￥{{ daymoney }}</span></div>
      <div class="pay_btn_wrapper">
        <van-button type="info" round block @click="submit"
          >立即支付</van-button
        >
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PopupPayment',
  props: ['daylist', 'dayid', 'daymoney'],
  data () {
    return {
      payment: 'wxpay'
    }
  },
  mounted () {},
  methods: {
    onday (id, money) {
      this.dayid = id + ''
      this.daymoney = money
    },
    submit () {
      this.$emit('handlerSubmit', this.payment, this.dayid)
    },
    choosePayment (payment) {
      this.payment = payment
    }
  }
}
</script>

<style lang="scss" scoped>
.list{
  margin-top: 50px;
    margin-left: 16px;
    margin-right: 16px;
    display: flex;
    overflow-x: scroll;
    .item{
        width: 123px;
        height: 126px;
        margin-right: 9px;
        border-radius: 5px;
        border: 2px solid #e5ebf2;
        background-color: #ffffff;
        &.selitem{
            border: 2px solid #fdde35;
            background-color: #fff9e0;
        }
        .name{
            width: 119px;
            line-height: 1;
            margin-top: 22px;
            font-size: 15px;
            color: #55340a;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            text-align: center;
        }
        .money{
            width: 119px;
            line-height: 1;
            margin-top: 20px;
            margin-bottom: 15px;
            font-size: 13px;
            color: #ff5a00;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            text-align: center;
            span{
                line-height: 1;
                font-size: 20px;
            }
        }
        .center{
            width: 119px;
            line-height: 1;
            font-size: 12px;
            color: #aa9675;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            text-align: center;
        }
    }
}
.list::-webkit-scrollbar{
    display: none;
}
.box_pay_pop {
  .pay_btn_wrapper {
    padding: 30px 37px;
  }
  .pay_item {
    &.wx {
      background: url("../../assets/images/wx_pay_ico.svg") 3px center no-repeat;
      background-size: 18px;
    }
    &.ali {
      background: url("../../assets/images/ali_pay_ico.svg") 3px center
        no-repeat;
      background-size: 20px;
    }
    .self_check {
      position: absolute;
      right: 15px;
      top: 19px;
    }
    position: relative;
    width: 345px;
    margin: 0 auto;
    padding: 16px 0 16px 30px;
    border-bottom: 1px solid #f8f8f8;
    font-size: 16px;
    color: #666666;
  }
  .pay_num {
    .for {
      font-size: 20px;
      color: #ff6600;
    }
    text-align: center;
    color: #555555;
    font-size: 16px;
  }
}
</style>
