<template>
  <div class="app-container">
    <div class="spaceline" />
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="200px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="搜索职位登录限制" required>
        <el-switch v-model="form.job_search_login" />
      </el-form-item>
      <el-form-item label="搜索职位登录条数限制" prop="job_search_login_num">
        <el-input v-model="form.job_search_login_num" />
        <span class="smalltip">
          <i class="el-icon-info" />
          未登录会员可以搜索职位条数
        </span>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      form: {
        job_search_login: false,
        job_search_login_num: 0
      },
      rules: {
        job_search_login_num: [
          {
            required: true,
            message: '请输入搜索职位登录条数限制',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            job_search_login,
            job_search_login_num
          } = { ...response.data }
          this.form = {
            job_search_login: job_search_login == 1,
            job_search_login_num
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.job_search_login = insertData.job_search_login === true ? 1 : 0
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    }
  }
}
</script>
<style scoped>
.el-input,
.el-input-group {
  width: 50%;
}
</style>
