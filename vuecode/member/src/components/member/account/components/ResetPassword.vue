<template>
  <div class="dialog">
    <div>
      <el-form  label-width="90px">
        <el-form-item label="原密码" v-if="isSetPassword == 1">
          <el-input type="password" class="input_width" v-model="old_password"></el-input>
        </el-form-item>
        <el-form-item :label="isSetPassword == 1 ? '新密码' : '设置密码'">
          <el-input type="password" class="input_width" v-model="password"></el-input>
        </el-form-item>
        <el-form-item label="重复密码">
            <el-input type="password" class="input_width" v-model="password_confirm"></el-input>
        </el-form-item>
      </el-form>
    </div>
    <div class="btn">
      <el-button type="primary" @click="handleSubmit">保存</el-button>
      <el-button @click="$emit('closeDialog', isSetPassword)">取消</el-button>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'ResetPassword',
  props: ['isSetPassword'],
  data () {
    return {
      old_password: '',
      password: '',
      password_confirm: ''
    }
  },
  methods: {
    // 提交
    handleSubmit () {
      if (!this.old_password && this.isSetPassword == 1) {
        this.$message.error('请填写原密码')
        return false
      }
      if (!this.password) {
        this.$message.error('请填写新密码')
        return false
      }
      if (this.old_password === this.password && this.isSetPassword == 1) {
        this.$message.error('新密码与原密码相同')
        return false
      }
      if (!this.password_confirm) {
        this.$message.error('请确认密码')
        return false
      }
      if (!Object.is(this.password_confirm, this.password)) {
        this.$message.error('两次输入的密码不一致')
        return false
      }
      http
        .post(api.reset_password, {
          old_password: this.old_password,
          password: this.password,
          password_confirm: this.password_confirm
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$message({ type: 'success', message: res.message })
            this.$emit('closeDialog', this.isSetPassword)
          } else {
            this.$message.error(res.message)
          }
        })
        .catch(err => {
          console.log(err)
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
  .input_width2{
    width: 175px;
  }
  .el_button{
    margin-left: -2px;
    padding: 12px 14px;
    color: #4d9afc;
  }
</style>
