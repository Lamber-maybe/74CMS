<template>
    <div id="app">
        <Head>
            {{ utype == 1 ? "注销企业账号" : "注销个人账号" }}
        </Head>
        <div class="form_split_10"></div>
        <div class="notice_bar">
           注销账号后将删除您的所有会员数据，永久注销。请谨慎操作！
        </div>
        <div class="log_wrapper">
            <div class="field_cell_group">
                <div v-if="utype == 1" class="tx1">
                    <p>注销之后，您将放弃以下权益：</p>
                    <p>1.您下载的简历将被清空且无法恢复，请提前备份。</p>
                    <p>2.请的企业信息、职位信息、网站积分等会员权益将被清空且无法恢复。</p>
                    <p>3.注销申请提交后，请耐心等待平台处理。</p>
                </div>
                <div class="tx1" v-else >
                    <p>注销之后，您将放弃以下权益：</p>
                    <p>1.您的简历和求职记录将被清空且无法恢复。</p>
                    <p>2.您的身份信息、资料信息、网站积分等会员权益将被清空且无法恢复。</p>
                    <p>3.注销申请提交后，请耐心等待平台处理。</p>
                </div>
                <div class="hr"></div>
                <div class="yz">
                    <span>验证身份</span>
                </div>
                <div class="yanz">
                    <div class="phone_div">
                        <div class="phone_text">
                            <span>手机号</span>
                        </div>
                        <div class="phone">
                            <span>{{ phone }}</span>
                        </div>
                    </div>
                    <div class="t2">
                      <div style="display: flex;">
                            <div class="phone_text">
                              验证码
                            </div>
                            <van-field class="code_input" v-model="code" placeholder="请输入验证码"></van-field>
                      </div>
                            <van-button size="small" type="primary" :disabled="is_submit" @click="sendSms">{{ $store.state.sendSmsBtnText }}</van-button>
                    </div>
                </div>
                <div class="btn_group">
                    <van-button
                            class="btn_mb"
                            type="info"
                            size="large"
                            round
                            @click="isSubmit"
                    >
                        提交申请
                    </van-button>
                </div>
            </div>
        </div>
        <Captcha ref="captcha" @setSubmitFun="setSubmitFun"></Captcha>
    </div>

</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import Captcha from '@/components/captcha/index'
export default {
  name: 'ResetPassword',
  props: ['utype', 'mobile', 'phone'],
  components: {
    Captcha
  },
  data () {
    return {
      code: '',
      is_submit: false
    }
  },
  methods: {
    sendSms () {
      this.is_submit = true
      this.$refs.captcha.show(res => {
        this.$store
          .dispatch('sendSmsFun', {
            url: api.cancelApply,
            mobile: this.mobile,
            type: this.utype,
            captcha: res
          })
          .then(response => {
            if (response.code === 200) {
              this.is_submit = false
              this.$notify({
                type: 'success',
                message: this.$store.state.sendSmsMessage
              })
            } else {
              this.is_submit = false
              this.$notify(this.$store.state.sendSmsMessage)
            }
          })
      })
    },

    isSubmit () {
      this.$dialog
        .confirm({
          title: '提示',
          message: '您确定要注销当前账号吗？',
          confirmButtonText: '注销'
        })
        .then(() => {
          this.handleSubmit()
        })
        .catch(() => {})
    },
    // 提交
    handleSubmit () {
      let that = this
      if (!this.code) {
        this.$notify('请填写验证码')
        return false
      }
      http
        .post(api.account_cancel_apply, {
          phone: this.phone,
          code: this.code
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            setTimeout(() => {
              that.$emit('closePopout', this.is_set_password)
            }, 2000)
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    setSubmitFun () {
      this.is_submit = false
    }
  }
}
</script>

<style lang="scss" scoped>

    .notice_bar {
      width: 100%;
      padding: 15px 0 0.373333rem 30px;
      font-size: 0.32rem;
      color: #FF7109;
      background: #FFF7F2 url('../../assets/images/remind_ico.svg') 11px center no-repeat;
      background-size: 0.426667rem;
      margin-bottom: 0.4rem;
    }
    .log_wrapper {
        width: 323px;
        margin: 0 auto;
    }
    .tx1 {
        width: 333px;
        height: 126.5px;
        font-size: 14px;
        font-weight: 250;
        color: #636363;
        line-height: 28px;
    }
    .field_cell_group {
        padding-top: 7px;
    }
    .yz {
        font-size: 16px;
        font-family: PingFang SC;
        font-weight: bold;
        color: #444444;
        text-align: center;
        margin-top: 35px;
    }
    .yanz {
        margin-top: 35px;
        font-size: 15px;
        .phone_div{
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            .phone_text{
                width: 50px;
                line-height: 35px;
                span{
                    font-size: 14px;
                    color: #444444;
                    line-height: 10.5px;
                }
            }
            .phone{
                line-height: 40px;
                width: 278px;
                height: 42px;
                padding-left: 20px;
                background-color: #F6F6F6;
                border-radius: 2px;
                span{
                    font-size: 14px;
                    color: #A6A6A6;
                    line-height: 10.5px;
                }
            }
        }
    }
    .hr{
        width: 119%;
        height: 0;
        background: #FFFFFF;
        border: 1px dashed #E0E0E0;
        margin-left: -28px;
        margin-top: 61px;
    }
    .btn_group{
        margin-top: 44px;
    }
    .yanz .t2 >>>.van-cell__title, .van-cell__value {
        margin-left: -28px;
        width: 50px;
        line-height: 38px;
    }
    .yanz .t2 >>>.van-cell {
      padding: 13px 0 0 32px;
    }
    .yanz .t2 >>>.van-button--primary{
        background-color: #F1F8FF;
        border-radius: 2px;
        border: 1px solid #409EFF;
        color: #409EFF;
        width: 91px;
        height: 42px;
    }
    .yanz .t2 >>>.van-cell__value {
        background: #F6F6F6;
        padding-left: 18px;
    }

    .yanz .t2{
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 18px;
      box-sizing: border-box;
      .phone_text{
        width: 50px;
        line-height: 40px;
        font-size: 14px;
        color: #444444;
      }
      .code_input{
        width: 175px !important;
        background: #f6f6f6;
        padding: 0;
        height: 42px;
        line-height: 42px;
      }
    }
</style>
