<template>
  <div class="app-container">
    <div class="tip">
      <p>未读消息提醒：指用户在未登录情况下，在收到消息时的通知信息。在未配置公众号和下方通知模板消息时建议您不要开启短信通知。</p>
    </div>
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      label-width="240px"
      :inline-message="true"
    >
      <el-form-item label="在线聊未读消息短信通知">
        <el-switch v-model="unread_reminder" class="middle" />
        <span class="smalltip smalltip_switch">
          <i class="el-icon-info" />
          如果用户已绑定微信公众号，不发送短信通知提醒；未绑定公众号，则发送短信提醒，此项关闭不发送短信。
        </span>
      </el-form-item>
      <el-form-item label="微信模板消息通知ID">
        <el-input v-model="unread_templateid" />
        <p class="smalltip">
          <i class="el-icon-info" />
          (微信模板名称：服务请求处理提醒；模板编号：OPENTM406200321)
        </p>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')"> 保存 </el-button>
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
      unread_reminder: false,
      unread_templateid: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.infoLoading = true
      const param = { utype: 0 }
      setImRule(param, 'get')
        .then((responses) => {
          const unread_reminder = responses.data.unread_reminder
          this.unread_reminder = unread_reminder === '1'
          this.unread_templateid = responses.data.unread_templateid
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = {
        unread_reminder: this.unread_reminder ? '1' : '0',
        unread_templateid: this.unread_templateid,
        utype: 0
      }
      setImRule(insertData)
        .then((response) => {
          if (response.data.sms_setting == 0) {
            this.$confirm('您未设置短信配置, 是否设置?', '提示', {
              confirmButtonText: '确定',
              cancelButtonText: '取消',
              type: 'warning'
            })
              .then(() => {
                this.$router.push('/sys/basic/sms')
              })
              .catch(() => {
                this.$message({
                  type: 'info',
                  message: '已取消设置'
                })
              })
          } else {
            this.$message.success(response.message)
            return true
          }
        })
        .catch(() => {})
    }
  }
}
</script>
<style scoped>
.el-input {
  width: 55%;
}
.el-input-group {
  width: 50%;
}
.el-form-item {
  max-width: 100%;
}
.smalltip {
  margin-left: 0;
  line-height: 20px;
}
.smalltip_switch {
  margin-left: 10px;
}
</style>
