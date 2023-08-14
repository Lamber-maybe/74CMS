<template>
  <div id="app" v-wechat-title="this.pageTitle">
    <Head>{{ $store.state.config.points_byname }}充值</Head>
    <div class="box_1">
      <div class="item">
        <div class="tx1">
          <div class="order_ico points"></div>
          <div class="name">{{ $store.state.config.points_byname }}充值</div>
          <div class="des">
            我的{{ $store.state.config.points_byname }}：{{ myPoints }}
          </div>
        </div>
      </div>
    </div>
    <div class="form_split_10"></div>
    <div class="box_2">
      <div class="item">选择充值套餐</div>
    </div>
    <div class="inv-top-menu-bar">
      <div class="top-menu">
        <div class="top-menu-list">
          <div
            class="item"
            :class="{
              active: active_index == index,
              time: item.recommend == 1
            }"
            v-for="(item, index) in dataset"
            :key="index"
            @click="changeItem(item, index)"
          >
            <div class="tx1">{{ item.name }}</div>
            <div class="tx2">
              <span class="unit">￥</span>{{ item.expense1 }}.<span
                class="tofix"
                >{{ item.expense2 }}</span
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="form_split_10"></div>
    <div class="box_pay_pop">
      <van-radio-group v-model="submitData.payment">
        <div class="pay_item wx" @click="choosePayment('wxpay')">
          微信支付
          <van-radio
            class="self_check"
            name="wxpay"
            icon-size="18px"
          ></van-radio>
        </div>
        <div class="pay_item ali" @click="choosePayment('alipay')" v-if="$store.state.showAlipay">
          支付宝支付
          <van-radio
            class="self_check"
            name="alipay"
            icon-size="18px"
          ></van-radio>
        </div>
      </van-radio-group>
    </div>
    <div class="box_5">
      <div class="content">
        应付金额：
        <div class="price">
          <span>￥</span>
          {{ amount }}
        </div>
        <van-button
          color="linear-gradient(to right, #f1cd91, #dab577)"
          text-color="#92672c"
          round
          size="small"
          @click="submit"
        >
          立即支付
        </van-button>
      </div>
    </div>
    <PaySubmit ref="paySubmit" :payment="submitData.payment" success-url="/member/order/list"></PaySubmit>
  </div>
</template>

<script>
import PaySubmit from '@/components/service/PaySubmit'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'BuyPoints',
  components: {
    PaySubmit
  },
  data () {
    return {
      pageTitle: '',
      active_index: 0,
      myPoints: 0,
      dataset: [],
      amount: 0.0,
      submitData: {
        service_type: 'points',
        service_id: 0,
        payment: 'wxpay'
      }
    }
  },
  created () {
    this.pageTitle = this.$route.meta.title
    this.pageTitle.replace('积分', this.$store.state.config.points_byname)
    this.fetchData()
  },
  methods: {
    choosePayment (payment) {
      this.submitData.payment = payment
    },
    fetchData () {
      http
        .get(api.points_setmeal_list, {})
        .then(res => {
          let list = res.data.items
          this.myPoints = res.data.member_points
          this.amount = list[0].expense
          this.submitData.service_id = list[0].id
          for (const iterator of list) {
            let expense_arr = iterator.expense.split('.')
            iterator.expense1 = expense_arr[0]
            iterator.expense2 = expense_arr[1]
            this.dataset.push(iterator)
          }
        })
        .catch(() => {})
    },
    changeItem (item, index) {
      this.active_index = index
      this.amount = item.expense
      this.submitData.service_id = item.id
    },
    submit () {
      this.submitData.return_url =
        this.$store.state.config.mobile_domain +
        'member/orderlist'
      this.$refs.paySubmit.handlerSubmit(api.company_pay, this.submitData)
    }
  }
}
</script>

<style lang="scss" scoped>
.box_5 {
  .content {
    .van-button {
      position: absolute;
      right: 17px;
      top: 50%;
      transform: translate(0, -50%);
      padding: 11px 28px;
      font-size: 16px;
      height: auto;
      line-height: 1;
    }
    .price {
      span {
        font-size: 12px;
        font-weight: normal;
      }
      position: absolute;
      left: 88px;
      top: 50%;
      transform: translate(0, -50%);
      color: #ff6600;
      font-size: 18px;
      font-weight: bold;
    }
    position: relative;
    width: 100%;
    height: 100%;
    padding: 18px 17px;
    font-size: 15px;
    color: #333333;
  }
  width: 100%;
  background-color: #ffffff;
  position: fixed;
  box-shadow: 0 -1px 10px #c2c2c2;
  left: 0;
  right: 0;
  bottom: 0;
}
.box_pay_pop {
  .pay_item {
    &.wx {
      background: url("../../../assets/images/wx_pay_ico.svg") 3px center
        no-repeat;
      background-size: 18px;
    }
    &.ali {
      background: url("../../../assets/images/ali_pay_ico.svg") 3px center
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
  background-color: #ffffff;
  width: 100%;
  padding-bottom: 78px;
}
.inv-top-menu-bar {
  background-color: #ffffff;
  padding: 0 17px 15px;
  .top-menu {
    overflow: hidden;
    overflow-x: scroll;
    -webkit-overflow-scrolling: touch;
    &::-webkit-scrollbar {
      display: none;
    }
  }
  .top-menu-list {
    white-space: nowrap;
    .item {
      &.time::after {
        content: "推荐";
        position: absolute;
        left: -2px;
        top: -2px;
        background-color: #ff0000;
        color: #ffffff;
        font-size: 12px;
        border-radius: 5px 0 5px 0;
        padding: 3.5px;
      }
      .tx2 {
        font-size: 20px;
        font-weight: bold;
        color: #92672c;
        padding-top: 9px;
        .unit {
          font-weight: normal;
          font-size: 10px;
        }
        .tofix {
          font-size: 14px;
        }
      }
      .tx1 {
        font-size: 14px;
        font-weight: bold;
        color: #666666;
      }
      position: relative;
      width: 113px;
      height: 95px;
      text-align: center;
      padding-top: 17px;
      background-color: #ffffff;
      border: 2px solid #e2e2e2;
      border-radius: 5px;
      display: inline-block;
      white-space: nowrap;
      vertical-align: top;
      &.active {
        .tx1 {
          color: #92672c;
        }
        border: 2px solid #dab577;
        background-color: #fffcf5;
      }
      &:not(:first-child) {
        margin-left: 10px;
      }
    }
  }
}
.box_2 {
  .item {
    padding: 17px 0 17px 15px;
    font-size: 16px;
    color: #333333;
    font-weight: bold;
  }
  width: 100%;
  background-color: #ffffff;
  border-radius: 18px 18px 0 0;
  display: flex;
}
.box_1 {
  .item {
    .tx1 {
      .order_ico {
        &.points {
          background: url("../../../assets/images/company_order_ico_jfcz.png") 0
            no-repeat;
          background-size: 50px;
        }
        position: absolute;
        left: 3px;
        top: 15px;
        width: 50px;
        height: 50px;
        border-radius: 100%;
      }
      .des {
        padding: 0 10px 15px 0;
        font-size: 13px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .name {
        padding: 15px 10px 9px 0;
        font-size: 15px;
        color: #666666;
        font-weight: bold;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      position: relative;
      padding-left: 65px;
    }
    width: 100%;
    background-color: #ffffff;
    border-radius: 5px;
    padding: 0 15px;
    position: relative;
  }
  width: 100%;
  border-top: 1px solid #f3f3f3;
}
</style>
