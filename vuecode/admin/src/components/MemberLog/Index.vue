<template>
  <div class="app-container">
    <el-tabs>
      <el-tab-pane label="操作日志" :lazy="true">
        <ActionLog :uid="uid" />
      </el-tab-pane>
      <el-tab-pane label="登录日志" :lazy="true">
        <LoginLog :uid="uid" />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import ActionLog from './ActionLog'
import LoginLog from './LoginLog'

export default {
  components: {
    ActionLog,
    LoginLog
  },
  props: ['uid']
}
</script>
