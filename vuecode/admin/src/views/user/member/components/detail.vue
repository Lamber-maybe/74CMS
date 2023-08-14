<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      label-width="100px"
    >
      <el-form-item label="UID">
        {{ info.uid }}
      </el-form-item>
      <el-form-item label="身份">
        {{ info.utype == 1 ? '企业会员' : '个人会员' }}
      </el-form-item>
      <el-form-item label="用户名">
        {{ info.username }}
      </el-form-item>
      <el-form-item label="手机号">
        {{ info.mobile }}
      </el-form-item>
      <el-form-item label="注册时间">
        {{ info.reg_time | timeFilter }}【IP:{{
          info.reg_ip + '（' + info.reg_address + '）'
        }}】
      </el-form-item>
      <el-form-item v-if="info.last_login_time == 0" label="最近登录">
        从未登录
      </el-form-item>
      <el-form-item v-else label="最近登录">
        {{ info.last_login_time | timeFilter }}【IP:{{
          info.last_login_ip + '（' + info.last_login_address + '）'
        }}】
      </el-form-item>
      <el-form-item label=" ">
        <el-button @click="closeDialog">关闭</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  props: ['uid', 'detailInfo'],
  data() {
    return {
      infoLoading: true,
      info: {}
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.info = { ...this.detailInfo }
      this.infoLoading = false
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    }
  }
}
</script>
<style scoped>
.el-form-item {
  margin-bottom: 0;
}
</style>
