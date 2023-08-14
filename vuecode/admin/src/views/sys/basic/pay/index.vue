<template>
  <div class="app-container">
    <el-tabs type="border-card">
      <el-tab-pane label="支付宝" :lazy="true">
        <alipay />
      </el-tab-pane>
      <el-tab-pane label="微信支付" :lazy="true">
        <wechat />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import alipay from './components/alipay.vue'
import wechat from './components/wechat.vue'
export default {
  components: {
    alipay,
    wechat
  }
}
</script>
