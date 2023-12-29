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
        <div style="border: 1px solid #ccc;">
          <Toolbar
            style="border-bottom: 1px solid #ccc"
            :editor="editor_agreement"
            :defaultConfig="toolbarConfig"
          />
          <Editor
            style="height: 400px; overflow-y: hidden;"
            v-model="form.agreement"
            @onCreated="onCreated1"
          />
        </div>
      </el-form-item>
      <el-form-item label="隐私政策">
        <div style="border: 1px solid #ccc;">
          <Toolbar
            style="border-bottom: 1px solid #ccc"
            :editor="editor_privacy"
            :defaultConfig="toolbarConfig"
          />
          <Editor
            style="height: 400px; overflow-y: hidden;"
            v-model="form.privacy"
            @onCreated="onCreated2"
          />
        </div>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'
import '@wangeditor/editor/dist/css/style.css'
import { Editor, Toolbar } from '@wangeditor/editor-for-vue'
import {getToken} from "@/utils/auth";
import apiArr from "@/api";

export default {
  components: { Editor, Toolbar },
  data() {
    return {
      infoLoading: true,
      editor_agreement: null,
      toolbarConfig: {
        excludeKeys: [
          'fullScreen',
          'insertVideo'
        ]
      },
      editor_privacy: null,
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
  },
  beforeDestroy() {
    const editor = this.editor
    if (editor == null) return
    editor.destroy() // 组件销毁时，及时销毁编辑器
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    onCreated1(editor) {
      this.editor_agreement = Object.seal(editor) // 一定要用 Object.seal() ，否则会报错
      console.log(this.editor_agreement.getConfig())
      this.editor_agreement.getMenuConfig('uploadImage').headers = {
        admintoken: getToken()
      }
      this.editor_agreement.getMenuConfig('uploadImage').withCredentials = true
      this.editor_agreement.getMenuConfig('uploadImage').server = window.global.RequestBaseUrl + apiArr.uploadEditor
    },
    onCreated2(editor) {
      this.editor_privacy = Object.seal(editor) // 一定要用 Object.seal() ，否则会报错
      this.editor_privacy.getMenuConfig('uploadImage').headers = {
        admintoken: getToken()
      }
      this.editor_privacy.getMenuConfig('uploadImage').withCredentials = true
      this.editor_privacy.getMenuConfig('uploadImage').server = window.global.RequestBaseUrl + apiArr.uploadEditor
    },
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
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      this.form.agreement = this.editor_agreement.getHtml()
      this.form.privacy = this.editor_privacy.getHtml()
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
