<template>
  <div class="app-container">
    <div class="tip">
      <p>
        骑士人才系统已集成多种渠道安全验证，使用验证需正确配置相关渠道信息。
      </p>
    </div>
    <el-tabs type="border-card">
      <el-tab-pane label="验证码" :lazy="true">
        <captcha />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import captcha from './components/captcha.vue'
export default {
  components: {
    captcha
  }
}
</script>
