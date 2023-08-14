<template>
  <div class="app-container">
    <div class="tip">
      <p>此功能仅限于已开通微信模板消息功能的公众号使用。</p>
      <p>必须填写【模板ID】才能【开启】模板消息推送状态，否则将无法正常发送通知。</p>
      <p>【模板ID】请到 微信公众平台 >> 模板消息 >> 模板库 选择对应【模板编号】的模板，然后获取【模板ID】填写到此处。</p>
      <p>以下所有模板均属于“IT科技/互联网|电子商务”和“商业服务/中介服务”。</p>
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
