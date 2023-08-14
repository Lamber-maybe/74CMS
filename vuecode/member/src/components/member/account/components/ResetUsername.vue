<template>
  <div class="dialog">
    <!-- 修改用户名 -->
    <div>
      <el-form  label-width="90px">
        <el-form-item label="原用户名">
          {{usernameOriginal}}
        </el-form-item>
        <el-form-item label="新用户名" prop="username">
            <el-input class="input_width" v-model="username"></el-input>
        </el-form-item>
      </el-form>
    </div>
    <div class="btn">
      <el-button type="primary" @click="handleSubmit">保存</el-button>
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
      username: ''
    }
  },
  methods: {
    handleSubmit () {
      this.username = this.username.trim()
      if (!this.username) {
        this.$message.error('请填写用户名')
        return false
      }
      if (this.username_original == this.username) {
        this.$message.error('新用户名与旧用户名相同')
        return false
      }
      http
        .post(api.reset_username, {
          username: this.username
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$message({ type: 'success', message: res.message })
            this.$emit('closeDialog', this.username)
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
