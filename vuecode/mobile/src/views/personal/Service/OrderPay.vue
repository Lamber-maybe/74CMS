<template>
  <div id="app">
    <ServiceSelect
      ref="child"
      v-if="submitData.service_id == 0"
      :type="type"
      :type_tip="type_tip"
      :service_list="service_list"
      @selFinish="handlerSetServiceid"
    ></ServiceSelect>
    <div v-else>
      <Head>确认订单</Head>
      <div class="form_split_10"></div>
      <div class="notice_bar">{{ type_tip }}!</div>
      <div class="form_split_10"></div>
      <div class="box_wrapper">
        <div class="box_1">
          <div class="item">
            <div class="tx1">
              <div class="order_ico" :class="type"></div>
              <div class="name">{{ selItem.name }}</div>
              <div class="des">专享简历推荐特权</div>
            </div>
          </div>
        </div>
      </div>
      <div class="form_split_10"></div>
      <div class="box_wrapper">
        <div class="box_4" v-if="type == 'tag'">
          <div class="box_head">选择醒目标签</div>
          <div class="box_content">
            <van-tag
              round
              :color="sel_tag_id === item.id ? '#edf6ff' : '#f3f3f3'"
              :text-color="sel_tag_id === item.id ? '#1787fb' : '#999999'"
              @click="handlerSelTag(item, index)"
              v-for="(item, index) in options_tag"
              :key="index"
              >{{ item.text }}</van-tag
            >
            <van-tag
              round
              :color="sel_tag_id === '' ? '#edf6ff' : '#f3f3f3'"
              :text-color="sel_tag_id === '' ? '#1787fb' : '#999999'"
              @click="showCustomTag = true"
              >{{
                custom_tag_text === "" ? "自定义" : custom_tag_text
              }}</van-tag
            >
          </div>
        </div>
      </div>
      <div class="form_split_10"></div>
      <div class="box_wrapper">
        <div class="box_2">
          <div class="item">
            服务内容
            <div class="right_txt">
              {{ type == "stick" ? "简历置顶" : "醒目标签" }}
            </div>
          </div>
          <div class="item">
            服务金额
            <div class="right_txt">
              <span class="for">￥</span>{{ selItem.expense }}
            </div>
          </div>
          <div class="item" v-if="selItem.enable_points_deduct == 0">
            {{ $store.state.config.points_byname }}抵扣
            <div class="right_txt">
              本服务暂不支持{{ $store.state.config.points_byname }}抵扣
            </div>
          </div>
          <div class="item" @click="useDeduct = !useDeduct" v-else>
            {{ $store.state.config.points_byname }}抵扣
            <div class="right_txt check">
              可使用{{ selItem.enable_points_deduct_points
              }}{{ $store.state.config.points_byname }}抵用{{
                selItem.enable_points_deduct_expense
              }}元
            </div>
            <van-checkbox
              class="self_check"
              v-model="useDeduct"
              icon-size="14px"
              @change="handlerDeduct"
            ></van-checkbox>
          </div>
          <div class="item">
            应付金额
            <div class="right_txt">
              <span class="color_or"
                ><span class="for">￥</span>{{ amount }}</span
              >
            </div>
          </div>
        </div>
        <div class="box_3">
          <van-button type="info" round block @click="showPayment = true"
            >立即支付</van-button
          >
        </div>
      </div>
    </div>
    <van-dialog
      v-model="showCustomTag"
      title="添加自定义标签"
      show-cancel-button
      @confirm="handleConfirmCustomTag"
      get-container="body"
    >
      <div class="box_custom">
        <div class="self_input">
          <label>
            <input
              type="text"
              v-model="custom_tag_text"
              maxlength="5"
              placeholder="请输入标签内容(最多5个字)"
            />
          </label>
        </div>
      </div>
    </van-dialog>
    <van-popup v-model="showPayment" closeable position="bottom">
      <PopupPayment
        :amount="amount"
        @handlerSubmit="handlerSubmit"
      ></PopupPayment>
    </van-popup>
    <PaySubmit ref="paySubmit" :payment="submitData.payment" success-url="/member/order/list"></PaySubmit>
  </div>
</template>

<script>
import PaySubmit from '@/components/service/PaySubmit'
import PopupPayment from '@/components/service/PopupPayment'
import ServiceSelect from '@/components/service/personal/ServiceSelect'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'OrderPay',
  components: {
    PaySubmit,
    ServiceSelect,
    PopupPayment
  },
  computed: {
    options_tag () {
      return this.$store.state.classifyResumeStrongTag
    }
  },
  data () {
    return {
      showCustomTag: false,
      sel_tag_id: 0,
      custom_tag_text: '',
      type: 'stick',
      type_tip: '',
      service_list: [],
      myPoints: 0,
      amount: 0.0,
      useDeduct: false,
      showPayment: false,
      submitData: {
        service_type: '',
        service_id: 0,
        deduct_points: 0,
        payment: 'wxpay',
        tag_text: ''
      },
      selItem: {},
      checked: true
    }
  },
  created () {
    this.type =
      this.$route.params.type === undefined
        ? this.type
        : this.$route.params.type
    if (this.type == 'stick') {
      this.type_tip = '简历曝光量提升5倍，专享优先推荐特权，求职快人一步!'
    } else {
      this.type_tip = '专属简历标签点亮你的个性优势，是金子就要发光！!'
    }
    this.submitData.service_type = this.type
    this.fetchServiceList()
    this.$store.dispatch('getClassify', 'resumeStrongTag')
  },
  methods: {
    fetchServiceList () {
      http
        .get(api.personal_servicelist, { type: this.type })
        .then(res => {
          this.service_list = [...res.data.items]
          this.myPoints = res.data.member_points
          if (this.service_list.length > 0) {
            this.$refs.child.service_id = this.service_list[0].id
          }
        })
        .catch(() => {})
    },
    handlerSetServiceid (item) {
      this.useDeduct = false
      this.selItem = item
      this.submitData.service_id = item.id
      this.amount = this.selItem.expense
    },
    handlerDeduct () {
      if (this.useDeduct === true) {
        this.amount = this.selItem.after_deduct_expense
        this.submitData.deduct_points = this.selItem.enable_points_deduct_points
      } else {
        this.amount = this.selItem.expense
        this.submitData.deduct_points = 0
      }
    },

    handlerSubmit (payment) {
      this.submitData.return_url = this.$store.state.config.mobile_domain + 'member/order/list'
      this.submitData.payment = payment
      this.$refs.paySubmit.handlerSubmit(api.personal_pay, this.submitData)
    },
    handlerSelTag (tag, index) {
      this.submitData.tag_text = tag.text
      this.sel_tag_id = tag.id
    },
    handleConfirmCustomTag () {
      this.showCustomTag = false
      this.sel_tag_id = ''
      this.submitData.tag_text = this.custom_tag_text
    }
  }
}
</script>

<style lang="scss" scoped>
.box_custom {
  width: 290px;
  margin: 0 auto;
  padding: 15px 0 25px;
  .self_input {
    width: 100%;
    border-bottom: 1px solid #e2e2e2;
    input {
      border: 0;
      padding: 7px 5px;
      font-size: 13px;
      color: #333333;
      width: 100%;
      &::placeholder {
        color: #c9c9c9;
      }
    }
  }
}
.box_5 {
  .item {
    .txt {
      margin-left: 16px;
      color: #333333;
    }
    position: relative;
    padding: 0 15px;
    font-size: 14px;
    color: #666666;
    &:not(:last-child) {
      margin-bottom: 15px;
    }
  }
  width: 100%;
  background-color: #ffffff;
  border-radius: 5px;
  padding: 0 0 15px;
}
.box_2 {
  .item {
    .self_check {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translate(0, -50%);
    }
    .right_txt {
      &.check {
        padding-right: 20px;
      }
      .for {
        font-size: 9px;
      }
      .color_or {
        color: #ff6600;
      }
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translate(0, -50%);
      color: #333333;
    }
    position: relative;
    padding: 0 15px;
    font-size: 14px;
    color: #666666;
    &:not(:last-child) {
      margin-bottom: 15px;
    }
  }
  width: 100%;
  background-color: #ffffff;
  border-radius: 5px;
  padding: 17px 0;
}
.box_4 {
  .box_content {
    .van-tag {
      margin: 0 0 10px 10px;
      padding: 5px 12px;vertical-align: top;
    }
    padding: 0 15px 5px 5px;
  }
  width: 100%;
  background-color: #ffffff;
  border-radius: 5px;
}
.box_head {
  width: 100%;
  background-color: #ffffff;
  border-radius: 5px 5px 0 0;
  padding: 15px 0 15px 15px;
  font-size: 14px;
  color: #333333;
  position: relative;
  &::after {
    content: " ";
    position: absolute;
    width: 2.5px;
    height: 14px;
    background-color: #1787fb;
    left: 8px;
    top: 50%;
    transform: translate(0, -50%);
  }
}
.box_3 {
  padding: 25px 25px;
}
.box_1 {
  .item {
    .tx1 {
      .order_ico {
        &.stick {
          background: url("../../../assets/images/order_ico_top.png") 0
            no-repeat;
          background-size: 80px 50px;
        }
        &.tag {
          background: url("../../../assets/images/order_ico_tag.png") 0
            no-repeat;
          background-size: 80px 50px;
        }
        position: absolute;
        left: 2px;
        top: 13px;
        width: 80px;
        height: 50px;
        border-radius: 7px;
      }
      .des {
        padding: 0 10px 18px 0;
        font-size: 13px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .name {
        padding: 15px 10px 6px 0;
        font-size: 15px;
        color: #666666;
        font-weight: bold;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      position: relative;
      padding-left: 95px;
    }
    width: 100%;
    background-color: #ffffff;
    border-radius: 5px;
    padding: 0 10px;
    position: relative;
  }
  width: 100%;
}
.box_wrapper {
  width: 100%;
  padding: 0 8px;
}
.notice_bar {
  width: 100%;
  padding: 11px 0 11px 35px;
  font-size: 12px;
  color: #ff6600;
  background: #fffbeb url("../../../assets/images/remind_ico.svg") 16px center
    no-repeat;
  background-size: 16px;
}
</style>
