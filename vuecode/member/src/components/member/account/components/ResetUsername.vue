<template>
  <div class="dialog">
    <!-- 修改用户名 -->
    <div>
<!--      【bug】用户名信息会员中心与后台修改规则不一致
          增加新用户名验证
          zch 2022.9.20
      -->
      <el-form ref="form" :model="form" label-width="90px" :rules="rules">
        <el-form-item label="原用户名">
          {{usernameOriginal}}
        </el-form-item>
        <el-form-item label="新用户名" prop="username">
            <el-input class="input_width" v-model="form.username"></el-input>
        </el-form-item>
      </el-form>
    </div>
    <div class="btn">
      <el-button type="primary" @click="handleSubmit('form')">保存</el-button>
      <el-button @click="$emit('closeDialog', usernameOriginal)">取消</el-button>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'ResetUsername',
  props: ['usernameOriginal'],
  data () {
    return {
      form:{
        username: '',
      },
      rules: {
        username: [
          {
            required: true,
            message: '请输入用户名',
            trigger: 'blur'
          },
          {
            min: 6,
            max: 30,
            message: '长度在 6 到 30 个字符',
            trigger: 'blur'
          },
        ],
      }
    }
  },
  methods: {
    handleSubmit (formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.form.username = this.form.username.trim()
          if (this.usernameOriginal == this.form.username) {
            this.$message.error('新用户名与旧用户名相同')
            return false
          }
          http
            .post(api.reset_username, {
              username: this.form.username
            })
            .then(res => {
              if (parseInt(res.code) === 200) {
                this.$message({ type: 'success', message: res.message })
                this.$emit('closeDialog', this.form.username)
              } else {
                this.$message.error(res.message)
              }
            })
            .catch(err => {
              console.log(err)
            })
        }
      });

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
