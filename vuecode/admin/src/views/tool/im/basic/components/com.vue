<template>
  <div
    class="app-container"
  >

    <div class="tip">
      <p>
        不同套餐类型的企业发起次数、每天可聊次数请在 `系统 --> 企业业务配置 --> 套餐配置` 中进行配置
      </p>
    </div>
    <el-form
      ref="form"
      class="common-form"
      label-width="240px"
      :inline-message="true"
    >
      <el-form-item label="强制关注公众号">
        <el-switch v-model="bind_weixin" />
      </el-form-item>
      <el-form-item label="企业显示状态限制">
        <el-radio-group v-model="display_status">
          <el-radio label="1">显示中</el-radio>
          <el-radio label="3">不限</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="">
        <el-button
          type="primary"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setImRule } from '@/api/im'

export default {
  data() {
    return {
      infoLoading: false,
      bind_weixin: false,
      display_status: ''
    }
  },
  methods: {
    iniFun(){
      this.fetchData()
    },
    fetchData() {
      this.infoLoading = true
      const param = { utype: 1 }
      setImRule(param, 'get')
        .then(responses => {
          const { bind_weixin, display_status } = { ...responses.data }
          this.bind_weixin = bind_weixin === '1'
          this.display_status = display_status
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = {
        bind_weixin: this.bind_weixin ? '1' : '0',
        display_status: this.display_status,
        utype: 1
      }
      setImRule(insertData)
        .then(response => {
          this.$message.success(response.message)
          return true
        })
        .catch(() => {})
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
