<template>
  <div class="dialog">
    <div>
      <div class="remark">注销账号后将删除您的所有会员数据，永久注销。请谨慎操作！</div>
      <div class="jg" v-if="utype === 1">
        <span>注销之后，您将放弃以下权益：<br></span>
        <span>1、您下载的简历将被清空且无法恢复，请提前注意备份。<br></span>
        <span>2、请的企业信息、职位信息、网站积分等会员权益将被清空且无法恢复。<br></span>
        <span>3、注销申请提交后，请耐心等待平台处理。</span>
      </div>
      <div class="jg" v-else >
        <span>注销之后，您将放弃以下权益：<br></span>
        <span>1、您的简历和求职记录将被清空且无法恢复。<br></span>
        <span>2、您的身份信息、资料信息、网站积分等会员权益将被清空且无法恢复。<br></span>
        <span>3、注销申请提交后，请耐心等待平台处理。</span>
      </div>
      <div class="card3">
      </div>
      <div class="title">
        验证身份
      </div>

      <el-form>

        <el-form-item label="手机号" >
          <el-input class="input_width_phone" v-model="phone" disabled></el-input>
        </el-form-item>
        <div style="display: flex;align-items: center;margin-top: 23px">
          <div>验证码</div>
          <el-form-item>
            <el-input class="input_width_code" placeholder="请输入验证码" v-model="code"></el-input>
          </el-form-item>
          <button class="el_button el_button_code" :disabled="is_submit" @click="sendSms">
            {{ $store.state.sendSmsBtnText }}
          </button>
        </div>
      </el-form>
    </div>
    <div class="btn">
      <el-button type="primary" @click="handleSubmit">提交申请</el-button>
    </div>
    <Captcha ref="captcha" :mask="true" @setSubmitFun="setSubmitFun"></Captcha>
  </div>
</template>

<script>
import http from '@/utils/http'
import Captcha from '@/components/captcha/index'
import api from '@/api'
export default {
  name: 'ResetEmail',
  props: ['utype', 'mobile','phone'],
  components: {
    Captcha
  },
  data () {
    return {
      checked: true,
      code: '',
      sendEmailLimit: false,
      is_submit: false
    }
  },
  created () {
    this.$store.commit('clearCountDownFunEmail')
  },
  methods: {
    // 提交
    handleSubmit () {
      let that = this
      if (!this.code) {
        this.$message('请填写验证码')
        return false
      }
      this.$confirm('确定要注销吗','系统提示',{type:'warning'})
        .then(() => {
          http
            .post(api.account_cancel_apply, {
              phone: this.mobile,
              code: this.code
            })
            .then(res => {
              if (parseInt(res.code) === 200) {
                this.$message({ type: 'success', message: res.message })
                setTimeout(() => {
                  that.$emit('closePopout', this.is_set_password)
                }, 2000)
              } else {
                this.$message(res.message)
              }
            })
            .catch(err => {
              console.log(err)
            })
        })
    },

    sendSms () {
      this.is_submit = true
      this.sendSmsLimit = true
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
              this.sendSmsLimit = false
              this.$message({
                type: 'success',
                message: this.$store.state.sendSmsMessage
              })
            } else {
              this.is_submit = false
              this.sendSmsLimit = false
              this.$message.error(this.$store.state.sendSmsMessage)
            }
          })
      })
    },
    setSubmitFun () {
      this.is_submit = false
    }
  }
}
</script>

<style scoped lang="scss">
.btn{
  text-align: center;
  margin-top:40px;
}
.btn .el-button{
  padding: 11px 39px;
  margin-left: 15px;
}
.el-form {
  width: 380px;
  margin: 0 auto;
}
.el-form-item {
  margin: 0;
}
.input_width_phone{
  width: 313px;
}
.input_width_code{
  margin-left: 10px;
  width: 199px;
}
.el_button_code {
  margin-left: 10px !important;
}
.dialog{
  padding: 0 20px;
  width: 626px;
}
.el_button{
  box-sizing: border-box;
  width: 104px;
  height: 41px;
  color: #4d9afc;
  margin-left: 34px;
  background: #F1F8FF;
  border: 1px solid #409EFF;
  border-radius: 2px;
  text-align: center;
  line-height: 37px;
  cursor: pointer;
}
.remark{
  height: 44px;
  font-size: 7px;
  font-weight: 400;
  color: #FF7109;
  line-height: 43px;
  margin-top: -14px;
  padding-left: 28px;
  background: #FFF7F2 url("../../../../assets/images/jingshi.png") left center no-repeat;
  background-position: 10px;
  opacity: 0.6;
  border-radius: 2px;
}
.jg{
  height: 98px;
  font-size: 14px;
  font-weight: 400;
  color: #636363;
  line-height: 28px;
  padding-top: 12px;
}
.card3 {
  border: 1px dashed #EEEEEE;
  margin-top: 75px;
}
.title{
  font-size: 16px;
  font-weight: 400;
  color: #333333;
  line-height: 53px;
  text-align: center;
}
</style>
