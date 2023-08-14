<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="基础配置">
        <div class="spaceline" />
        <el-form
          ref="form"
          v-loading="infoLoading"
          class="common-form"
          :model="form"
          label-width="120px"
        >
          <el-form-item label="访问地址">
            <el-input v-model="form.mobile_domain" class="middle" />
            <span class="smalltip">
              <i class="el-icon-info" />
              请填写完整链接地址，以“/”结尾，如：https://www.74cms.com/m/
            </span>
          </el-form-item>
          <el-form-item label="注册简历引导">
            <el-radio-group v-model="form.personal_reg_mode">
              <el-radio label="1">常规列表注册
                <el-popover
                  placement="bottom-start"
                  width="250"
                  trigger="hover"
                >
                  <img src="static/reg1.jpg">
                  <span slot="reference">[预览]</span>
                </el-popover>
              </el-radio>
              <el-radio label="2">询问交互式注册
                <el-popover
                  placement="bottom-start"
                  width="250"
                  trigger="hover"
                >
                  <img src="static/reg2.jpg">
                  <span slot="reference">[预览]</span>
                </el-popover>
              </el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="">
            <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
    </el-tabs>

  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      form: {
        mobile_domain: '',
        personal_reg_mode: ''
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
            mobile_domain,
            personal_reg_mode
          } = { ...response.data }
          this.form = {
            mobile_domain,
            personal_reg_mode
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
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
</style>

