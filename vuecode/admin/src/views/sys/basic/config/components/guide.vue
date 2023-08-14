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
      <el-form-item
        label="标题"
        prop="guide_title"
      >
        <el-input
          v-model="form.guide_title"
          class="middle"
          maxlength="16"
          show-word-limit
        />
      </el-form-item>
      <el-form-item
        label="内容"
        prop="guide_content"
      >
        <el-input
          v-model="form.guide_content"
          class="middle"
          maxlength="25"
          show-word-limit
        />
      </el-form-item>
      <el-form-item label="是否开启">
        <el-switch v-model="form.guide_open" />
      </el-form-item>
      <el-form-item
        label="二维码图片"
        prop="guide_qrcode"
      >
        <el-upload
          class="qrcode-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="handleQrcodeSuccess"
          :before-upload="beforeQrcodeUpload"
        >
          <img
            v-if="qrcodeUrl"
            :src="qrcodeUrl"
            class="qrcode"
          >
          <i
            v-else
            class="el-icon-plus qrcode-uploader-icon"
          />
        </el-upload>
        <span class="smalltip">
          <i class="el-icon-info" />
          建议尺寸94*94
        </span>
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
import apiArr from '@/api'
import { getToken } from '@/utils/auth'

export default {
  data() {
    return {
      infoLoading: true,
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      form: {
        guide_title: '',
        guide_content: '',
        guide_open: false,
        guide_qrcode: ''
      },
      qrcodeUrl: '',
      rules: {
        guide_title: [
          { required: true, message: '请输入标题', trigger: 'blur' },
          { max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
        ],
        guide_content: [
          { required: true, message: '请输入内容', trigger: 'blur' },
          { max: 100, message: '长度在 1 到 100 个字符', trigger: 'blur' }
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
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            guide_title,
            guide_content,
            guide_open,
            guide_qrcode
          } = { ...response.data }
          this.form = {
            guide_title,
            guide_content,
            guide_open,
            guide_qrcode
          }
          this.form.guide_open = this.form.guide_open == 1
          this.qrcodeUrl = response.data.guide_qrcodeUrl
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.guide_open = insertData.guide_open === true ? 1 : 0
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
    },
    handleQrcodeSuccess(res, file) {
      if (res.code === 200) {
        this.qrcodeUrl = URL.createObjectURL(file.raw)
        this.form.guide_qrcode = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeQrcodeUpload(file) {
      const filetypeArr = file.type.split('/')
      const filetype = filetypeArr[1]
      const configFileExtArr = this.fileupload_ext.split(',')

      if (!configFileExtArr.includes(filetype)) {
        this.$message.error('上传文件格式不允许')
        return false
      }
      if (file.size / 1024 > this.fileupload_size) {
        this.$message.error(`上传文件最大为${this.fileupload_size}kb`)
        return false
      }
      return true
    }
  }
}
</script>

<style scoped>
.qrcode-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  width: 172px;
  height: 172px;
}
.qrcode-uploader img {
  width: 170px;
  height: 170px;
  border-radius: 6px;
}
.qrcode-uploader:hover {
  border-color: #409eff;
}
.qrcode-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 170px;
  height: 170px;
  line-height: 170px;
  text-align: center;
}
</style>
