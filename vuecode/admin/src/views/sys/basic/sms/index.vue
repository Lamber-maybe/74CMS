<template>
  <div class="app-container">
    <div class="tip">
      <p>
        短信模块属收费模块，需申请开通后才能使用，请联系我司客服申请开通。
      </p>
      <p>
        资费标准请联系骑士销售获取，更多介绍请进入
        <a style="color:#1E88E5" href="http://www.74cms.com" target="_blank">
          骑士人才系统官方网站
        </a>
      </p>
      <p>
        短信发送生效需要在对应服务商的模版配置中单独设置对应模版的模板报备ID（模板报备ID在短信服务商处获取）
      </p>
      <p>
        阿里的短信暂时不能正常发送招聘类信息，使用前请先向阿里官方核实，谨慎使用！
      </p>
    </div>
    <el-tabs type="border-card">
      <el-tab-pane label="参数配置" :lazy="true">
        <smsbase />
      </el-tab-pane>
      <el-tab-pane label="模板设置" :lazy="true">
        <smstpl />
      </el-tab-pane>
      <el-tab-pane label="黑名单" :lazy="true">
        <blacklist />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import smsbase from './components/base.vue'
import smstpl from './components/tpl.vue'
import blacklist from './components/blacklist.vue'
export default {
  components: {
    smsbase,
    smstpl,
    blacklist
  }
}
</script>
