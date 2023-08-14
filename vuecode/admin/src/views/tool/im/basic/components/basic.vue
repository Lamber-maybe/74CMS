<template>
  <div
    class="app-container"
  >
    <el-form
      ref="form"
      class="common-form"
      label-width="240px"
      :inline-message="true"
    >
      <el-form-item label="开启职聊功能">
        <el-switch v-model="im_open" />
      </el-form-item>
      <el-form-item label="app_key">
        <el-input v-model="form.app_key" />
      </el-form-item>
      <el-form-item label="app_secret">
        <el-input v-model="form.app_secret" />
      </el-form-item>
      <!--<el-form-item label="开启未读通知">
        <el-switch v-model="im_unread" />
      </el-form-item>-->
      <el-form-item label="开启聊天公告">
        <el-switch v-model="im_notice_open" />
      </el-form-item>
      <el-form-item v-show="im_notice_open" label="公告内容">
        <el-input
          v-model="im_notice"
          class="middle"
          maxlength="50"
          type="textarea"
          :rows="4"
          show-word-limit
        />
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
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: false,
      im_open: false,
      form: {
        app_key: '',
        app_secret: ''
      },
      // im_unread: false,
      im_notice_open: false,
      im_notice: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const { account_im, im_open, im_unread, im_notice_open, im_notice } = { ...response.data }
          this.form = account_im
          this.im_open = im_open === '1'
          this.im_unread = im_unread === '1'
          this.im_notice_open = im_notice_open === '1'
          this.im_notice = im_notice
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = {
        im_open: this.im_open ? '1' : '0',
        account_im: { ...this.form },
        im_unread: this.im_unread ? '1' : '0',
        im_notice_open: this.im_notice_open ? '1' : '0',
        im_notice: this.im_notice
      }
      setConfig(insertData)
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
