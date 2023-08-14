<template>
  <div id="app">
    <Head>专属客服</Head>
    <div class="box_1">
      <div class="avatar_box">
        <img :src="info.photo" :alt="info.name" />
      </div>
      <div class="name">{{ info.name }}</div>
    </div>
    <div class="form_split_10"></div>
    <div class="box_2">
      <div class="handle_line l1">
        手机号
        <div class="right_txt">{{ info.mobile ? info.mobile : "暂无" }}</div>
      </div>
      <div class="handle_line l2">
        联系电话
        <div class="right_txt">{{ info.tel ? info.tel : "暂无" }}</div>
      </div>
      <div class="handle_line arrow l3" @click="weiXinQrcode = true" v-if="info.wx_qrcode">
        微信
        <div class="right_txt">点击查看二维码</div>
      </div>
      <div class="handle_line l4">
        联系QQ
        <div class="right_txt">{{ info.qq ? info.qq : "暂无" }}</div>
      </div>
    </div>
    <div class="form_split_10"></div>
    <div class="box_2">
      <div class="handle_line arrow l5">
        投诉客服
        <div class="right_txt" @click="openComplaint">请填写投诉信息</div>
      </div>
    </div>
    <van-dialog v-model="weiXinQrcode" title="专属客户" confirm-button-text="知道了">
      <div class="dialog_wx_qr">
        <div class="qr_img"><img v-if="info.wx_qrcode" :src="info.wx_qrcode"/></div>
      </div>
    </van-dialog>
    <van-popup
      :lazy-render="false"
      v-model="showComplaint"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <CustomerServiceComplaint
        ref="child"
        :cs_id="info.id"
        @closeComplaint="closeComplaint"
      ></CustomerServiceComplaint>
    </van-popup>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import CustomerServiceComplaint from '@/components/CustomerServiceComplaint'
export default {
  name: 'ExclusiveService',
  components: {
    CustomerServiceComplaint
  },
  data () {
    return {
      showComplaint: false,
      weiXinQrcode: false,
      info: {}
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      http
        .get(api.customer_service, {})
        .then(res => {
          this.info = { ...res.data.info }
        })
        .catch(() => {})
    },
    openComplaint () {
      this.showComplaint = true
      this.$refs.child.initContent()
    },
    closeComplaint () {
      this.showComplaint = false
    }
  }
}
</script>

<style lang="scss" scoped>
.dialog_wx_qr{
  padding: 20px 0px;
  .qr_img{
    margin:0 auto;
  }
}
.handle_line {
  &.l5 {
    background: #ffffff url("../../../assets/images/exc_l5.png") 20px center
      no-repeat;
    background-size: 18px;
  }
  &.l4 {
    background: #ffffff url("../../../assets/images/exc_l4.png") 20px center
      no-repeat;
    background-size: 18px;
  }
  &.l3 {
    background: #ffffff url("../../../assets/images/exc_l3.png") 20px center
      no-repeat;
    background-size: 20px 18.5px;
  }
  &.l2 {
    background: #ffffff url("../../../assets/images/exc_l2.png") 20px center
      no-repeat;
    background-size: 19px;
  }
  &.l1 {
    background: #ffffff url("../../../assets/images/exc_l1.png") 21px center
      no-repeat;
    background-size: 16px 19px;
  }
  font-size: 16px;
  color: #333333;
  padding: 15px 0 15px 52px;
  position: relative;
  .right_txt {
    position: absolute;
    right: 34px;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 14px;
    color: #c9c9c9;
  }
  &.arrow::before {
    position: absolute;right: 23px;top: 22px;width: 7px;height: 7px;border-top: 1px solid #999999;
    border-right: 1px solid #999999;transform: rotate(45deg);content: "";
  }
  &:not(:last-child)::after {
    content: " ";
    position: absolute;
    pointer-events: none;
    right: 0;
    bottom: 0;
    left: 52px;
    border-bottom: 1px solid #f3f3f3;
  }
}
.box_2 {
  width: 100%;
  background-color: #ffffff;
}
.box_1 {
  .name {
    padding: 11px 0 31px;
    text-align: center;
    font-size: 17px;
    color: #333333;
    font-weight: bold;
  }
  .avatar_box {
    width: 70px;
    height: 70px;
    margin: 0 auto;
    img {
      width: 70px;
      height: 70px;
      border: 0;
      border-radius: 100%;
    }
  }
  width: 100%;
  background-color: #ffffff;
  padding-top: 35px;
}
</style>
