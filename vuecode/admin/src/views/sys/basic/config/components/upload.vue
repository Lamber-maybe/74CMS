<template>
  <div class="app-container">
    <div class="spaceline" />
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="150px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="文件保存位置">
        <el-radio-group v-model="form.fileupload_type">
          <el-radio label="default">本地服务器</el-radio>
          <el-radio label="qiniu">七牛云</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item
        v-if="form.fileupload_type == 'qiniu'"
        label="存储空间名"
        prop="bucket"
        class="is-required"
      >
        <el-input v-model="form.account_qiniu.bucket" />
        <span class="smalltip">
          <i class="el-icon-info" />
          创建空间时设置的名称
        </span>
      </el-form-item>
      <el-form-item
        v-if="form.fileupload_type == 'qiniu'"
        label="accessKey"
        prop="access_key"
        class="is-required"
      >
        <el-input v-model="form.account_qiniu.access_key" />
        <span class="smalltip">
          <i class="el-icon-info" />
          在七牛云管理平台【个人面板-密钥管理】获取
        </span>
      </el-form-item>
      <el-form-item
        v-if="form.fileupload_type == 'qiniu'"
        label="secretKey"
        prop="secret_key"
        class="is-required"
      >
        <el-input v-model="form.account_qiniu.secret_key" />
        <span class="smalltip">
          <i class="el-icon-info" />
          在七牛云管理平台【个人面板-密钥管理】获取
        </span>
      </el-form-item>
      <el-form-item
        v-if="form.fileupload_type == 'qiniu'"
        label="外链域名"
        prop="domain"
        class="is-required"
      >
        <el-input v-model="form.account_qiniu.domain" />
        <span class="smalltip">
          <i class="el-icon-info" />
          资源访问域名，请到七牛云管理平台设置
        </span>
      </el-form-item>
      <el-form-item
        v-if="form.fileupload_type == 'qiniu'"
        label="资源访问协议"
        prop="protocol"
        class="is-required"
      >
        <el-radio-group v-model="form.account_qiniu.protocol">
          <el-radio label="http://">http://</el-radio>
          <el-radio label="https://">https://</el-radio>
        </el-radio-group>
        <span class="smalltip">
          <i class="el-icon-info" />
          <a href="http://doc.74cms.com/#/se/qiniu?id=_4%e5%9f%9f%e5%90%8d%e5%bc%80%e5%90%afhttps" target="_blank" style="color:#409EFF">七牛云https配置</a>，(使用短视频必须使用https)
        </span>
      </el-form-item>
      <el-form-item
        v-if="form.fileupload_type == 'qiniu'"
        label="七牛云图片压缩"
        prop="quality"
        class="is-required"
      >
        <el-radio-group v-model="form.account_qiniu.quality">
          <el-radio label="0">关闭</el-radio>
          <el-radio label="1">开启</el-radio>
        </el-radio-group>
        <span class="smalltip">
          <i class="el-icon-info" />
          可节省调用流量，有一定免费额度，<a href="https://www.qiniu.com/prices/dora?source=dora&ref=developer.qiniu.com&s_path=%2Fdora%2F8259%2Fthe-quality-of-transformation" target="_blank" style="color:#409EFF">收费说明</a>
        </span>
      </el-form-item>

      <el-form-item label="文件上传最大限制" prop="fileupload_size">
        <el-input v-model.number="form.fileupload_size" type="number">
          <template slot="append">kb</template>
        </el-input>
      </el-form-item>
      <el-form-item label="允许上传的文件类型" prop="fileupload_ext">
        <el-input
          v-model="form.fileupload_ext"
          type="textarea"
          :rows="4"
        />
        <span class="smalltip">
          <i class="el-icon-info" />
          多个以英文半角逗号 “,” 隔开
        </span>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      form: {
        fileupload_type: '',
        fileupload_size: 0,
        fileupload_ext: '',
        account_qiniu: {
          bucket: '',
          access_key: '',
          secret_key: '',
          domain: '',
          protocol: '',
          quality: ''
        }
      },
      rules: {
        fileupload_size: [
          {
            required: true,
            message: '请输入文件上传最大限制',
            trigger: 'blur'
          },
          { type: 'number', message: '输入内容必须为数字', trigger: 'blur' }
        ],
        fileupload_ext: [
          {
            required: true,
            message: '请输入允许上传的文件类型',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  mounted() {},
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            fileupload_type,
            fileupload_size,
            fileupload_ext,
            account_qiniu
          } = response.data
          this.form.fileupload_type =
            fileupload_type == '' ? 'default' : fileupload_type
          this.form.fileupload_size = parseInt(fileupload_size)
          this.form.fileupload_ext = fileupload_ext
          account_qiniu.quality = account_qiniu.quality === '1' ? '1' : '0'
          this.form.account_qiniu = account_qiniu
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
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

<style scoped>
.el-input,
.el-radio-group,
.el-textarea {
  width: 55%;
}
</style>
