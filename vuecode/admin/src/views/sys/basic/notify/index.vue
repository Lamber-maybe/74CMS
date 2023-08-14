<template>
  <div class="app-container">
    <div class="tip">
      <p>
        因阿里云短信平台审核规则，系统配置阿里云短信后仅支持发送系统基础短信，无法使用更丰富的短信通知配置。
      </p>
    </div>
    <el-tabs type="border-card">
      <el-tab-pane label="通知企业" :lazy="true">
        <ruleForm listtype="company" />
      </el-tab-pane>
      <el-tab-pane label="通知求职者" :lazy="true">
        <ruleForm listtype="personal" />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import ruleForm from './ruleForm.vue'
export default {
  components: {
    ruleForm
  }
}
</script>
