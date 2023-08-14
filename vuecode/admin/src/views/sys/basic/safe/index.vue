<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="验证码" :lazy="true">
        <captcha />
      </el-tab-pane>
      <el-tab-pane label="IP黑名单" :lazy="true">
        <ip />
      </el-tab-pane>
      <el-tab-pane label="敏感词过滤" :lazy="true">
        <badword />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import captcha from './components/captcha.vue'
import ip from './components/ip.vue'
import badword from './components/badword.vue'
export default {
  components: {
    captcha,
    ip,
    badword
  }
}
</script>
