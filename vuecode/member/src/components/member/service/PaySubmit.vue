<template>
  <div>
    <el-dialog title="支付提醒" :visible.sync="showWaitingPay" width="400px" :append-to-body="true"
    :show-close="false" :close-on-click-modal="false" :close-on-press-escape="false">
      <span class="payment_text">请在新打开的页面上完成支付。支付完成后，请根据您支付的情况点击下面按钮。</span>
      <span slot="footer" class="dialog-footer">
            <el-button type="primary" @click="handlerPaySuccess">支付成功</el-button>
            <el-button @click="handlerPayFail">支付失败</el-button>
            </span>
    </el-dialog>
  </div>
</template>

<script>
import http from '@/utils/http'

export default {
  name: 'PaySubmit',
  props: ['payment', 'showWaiting', 'successUrl', 'failUrl', 'customLocation'],
  data() {
    return {
      showWaitingPay: false
    }
  },
  created() {

  },
  methods: {
    handlerSubmit(url, data, callback) {
      if (data.payment == 'alipay' && !this.$store.state.config.account_alipay_appid) {
        this.$alert('暂不支持支付宝付款，请选择其他付款方式', '提示', {
          confirmButtonText: '确定',
          type: 'warning',
          callback: () => {
          }
        });
        return false
      }
      if (data.payment == 'wxpay' && !this.$store.state.config.payment_wechat_appid) {
        this.$alert('暂不支持微信付款，请选择其他付款方式', '提示', {
          confirmButtonText: '确定',
          type: 'warning',
          callback: () => {
          }
        });
        return false
      }
      let that = this
      http
        .post(url, data)
        .then(res => {
          if (res.data.pay_status == 1) {
            this.$message({type: 'success', message: '支付成功'})
            if (typeof callback === "function") {
              callback()
              return false
            } else {
              setTimeout(() => {
                if (that.$route.path === that.successUrl) {
                  location.reload()
                } else {
                  that.$router.push(that.successUrl)
                }
              }, 1500)
            }
            return false
          } else {
            this.handlerPay(res.data, callback)
          }
        })
        .catch(() => {
          setTimeout(() => {
            if (that.$route.path === that.successUrl) {
              location.reload()
            } else {
              that.$router.push(that.successUrl)
            }
          }, 1500)
        })
    },
    handlerPay(data, callback) {
      if (this.showWaiting === true) {
        this.showWaitingPay = true
      }
      if (this.payment == 'wxpay') {
        const {href} = this.$router.resolve({
          path: '/wxpay',
          query: {
            parameter: data.parameter,
            oid: data.order_oid,
            amount: data.order_amount,
            custom_location: this.customLocation === undefined ? 0 : 1,
            success_url: this.successUrl
          }
        });
        window.open(href);
      } else {
        window.open(data.parameter)
      }
      if (typeof callback === "function") {
        callback()
        return false
      }
    },
    handlerPaySuccess() {
      var that = this
      that.showWaitingPay = false
      if (that.$route.path === that.successUrl) {
        location.reload()
      } else {
        that.$router.push(that.successUrl)
      }
    },
    handlerPayFail() {
      var that = this
      that.showWaitingPay = false
      if (that.$route.path === that.failUrl) {
        location.reload()
      } else {
        that.$router.push(that.failUrl)
      }
    }
  }
}
</script>
<style lang="scss" scoped>
</style>
