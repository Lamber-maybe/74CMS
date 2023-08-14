<template>
  <div class="app-container">
    <div class="spaceline" />
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="120px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="关闭会员注册">
        <el-switch v-model="form.closereg" />
      </el-form-item>
      <el-form-item label="用户名前缀">
        <el-input v-model="form.reg_prefix" class="small" />
        <span class="smalltip">
          <i class="el-icon-info" />
          注册生成用户名，规则：前缀+手机号末4位+随机码，如:user_5353753951
        </span>
      </el-form-item>
      <el-form-item label="注册协议">
        <div id="editor_agreement" class="editor" />
      </el-form-item>
      <el-form-item label="隐私政策">
        <div id="editor_privacy" class="editor" />
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'
import E from 'wangeditor'

export default {
  data() {
    return {
      infoLoading: true,
      editor_agreement: '',
      editor_privacy: '',
      form: {
        closereg: '',
        reg_prefix: '',
        agreement: '',
        privacy: ''
      },
      rules: {}
    }
  },
  mounted() {
    this.editor_agreement = new E('#editor_agreement')
    this.editor_agreement.config.uploadImgShowBase64 = true
    this.editor_agreement.config.zIndex = 1
    this.editor_agreement.config.pasteFilterStyle = false
    this.editor_agreement.create()
    this.editor_privacy = new E('#editor_privacy')
    this.editor_privacy.config.uploadImgShowBase64 = true
    this.editor_privacy.config.zIndex = 1
    this.editor_privacy.config.pasteFilterStyle = false
    this.editor_privacy.create()
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const { closereg, reg_prefix, agreement, privacy } = {
            ...response.data
          }
          this.form = {
            closereg,
            reg_prefix,
            agreement,
            privacy
          }
          this.form.closereg = this.form.closereg == 1
          this.editor_agreement.txt.html(this.form.agreement)
          this.editor_privacy.txt.html(this.form.privacy)
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      this.form.agreement = this.editor_agreement.txt.html()
      this.form.privacy = this.editor_privacy.txt.html()
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.closereg = insertData.closereg === true ? 1 : 0
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    }
  }
}
</script>
