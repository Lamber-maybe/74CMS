<template>
  <div class="dialog">
    <div>
      <el-form label-width="90px">
        <el-form-item label="原邮箱号" v-if="emailOriginal!=''">
          {{emailOriginal}}
        </el-form-item>
        <el-form-item :label="emailOriginal!=''?'新邮箱号':'邮箱账号'">
          <el-input class="input_width" v-model="newEmail"></el-input>
        </el-form-item>
        <el-form-item label="验证码">
            <el-input class="input_width" v-model="code">
              <template slot="append"><el-button class="el_button" @click="sendEmail">{{ $store.state.sendEmailBtnText }}</el-button></template>
            </el-input>
        </el-form-item>
      </el-form>
    </div>
    <div class="btn">
      <el-button type="primary" @click="handleSubmit">保存</el-button>
      <el-button @click="$emit('closeDialog', emailOriginal)">取消</el-button>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'ResetEmail',
  props: ['emailOriginal'],
  data () {
    return {
      checked: true,
      newEmail: '',
      code: '',
      regularEmail: /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
    }
  },
  methods: {
    // 提交
    handleSubmit () {
      if (!this.newEmail) {
        this.$message.error('请填写邮箱')
        return false
      }
      if (!this.regularEmail.test(this.newEmail)) {
        this.$message.error('邮箱格式不正确')
        return false
      }
      if (this.newEmail === this.emailOriginal) {
        this.$message.error('新邮箱与原邮箱相同')
        return false
      }
      if (!this.code) {
        this.$message.error('请填写验证码')
        return false
      }
      http
        .post(api.reset_email, {
          email: this.newEmail,
          code: this.code
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$message({ type: 'success', message: res.message })
            this.$emit('closeDialog', this.newEmail)
          } else {
            this.$message.error(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    // 发送验证码
    sendEmail () {
      if (this.$store.state.sendEmailBtnDisabled) {
        return false
      }
      if (!this.newEmail) {
        this.$message.error('请输入邮箱')
        return false
      }
      if (!this.regularEmail.test(this.newEmail)) {
        this.$message.error('邮箱格式不正确')
        return false
      }
      if (this.emailOriginal == this.newEmail) {
        this.$message.error('新邮箱与旧邮箱相同')
        return false
      }
      this.$store
        .dispatch('sendEmailFun', {
          url: api.sendmail_bind,
          email: this.newEmail,
          type: this.$store.state.LoginType
        })
        .then(res => {
          if (res.code == 200) {
            this.$message({
              type: 'success',
              message: this.$store.state.sendEmailMessage
            })
          } else {
            this.$message.error(this.$store.state.sendEmailMessage)
            return false
          }
        })
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
  .input_width{
    width: 270px;
  }
  .dialog{
    padding: 0 20px;
  }
  .el_button{
    padding: 12px 14px;
    color: #4d9afc;
  }
</style>
