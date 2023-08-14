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
      <el-form-item label="公众号名称">
        <el-input v-model="form.wechat_name" />
      </el-form-item>
      <el-form-item label="微信号">
        <el-input v-model="form.wechat_code" />
      </el-form-item>
      <el-form-item label="二维码图片">
        <el-upload
          class="qrcode-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="handleQrcodeSuccess"
          :before-upload="beforeUpload"
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
          建议尺寸430*430
        </span>
      </el-form-item>
      <el-form-item label="关注欢迎语">
        <el-input
          v-model="form.wechat_welcome_text"
          class="middle"
          type="textarea"
          :rows="8"
        />
      </el-form-item>
      <el-form-item label="关注欢迎图片">
        <el-upload
          class="welcomepic-uploader"
          :action="apiUploadWechatMedia"
          :headers="headers"
          :show-file-list="false"
          :on-success="handleWelcomepicSuccess"
          :before-upload="beforeUpload"
        >
          <img
            v-if="form.wechat_welcome_img"
            :src="form.wechat_welcome_img"
            class="welcomepic"
          >
          <i
            v-else
            class="el-icon-plus welcomepic-uploader-icon"
          />
        </el-upload>
      </el-form-item>
      <el-form-item label="默认信息图">
        <el-upload
          class="infopic-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="handleInfopicSuccess"
          :before-upload="beforeUpload"
        >
          <img
            v-if="infopicUrl"
            :src="infopicUrl"
            class="infopic"
          >
          <i
            v-else
            class="el-icon-plus infopic-uploader-icon"
          />
        </el-upload>
        <span class="smalltip">
          <i class="el-icon-info" />
          支持JPG、PNG格式，建议尺寸200*200
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
      apiUploadWechatMedia: window.global.RequestBaseUrl + apiArr.uploadWechatMedia,
      qrcodeUrl: '',
      infopicUrl: '',
      form: {
        wechat_name: '',
        wechat_code: '',
        wechat_qrcode: '',
        wechat_welcome_text: '',
        wechat_welcome_img: '',
        wechat_info_img: '',
        wechat_welcome_img_mediaid: ''
      },
      rules: {}
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
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
            wechat_name,
            wechat_code,
            wechat_qrcode,
            wechat_welcome_text,
            wechat_welcome_img,
            wechat_welcome_img_mediaid,
            wechat_info_img
          } = { ...response.data }
          this.form = {
            wechat_name,
            wechat_code,
            wechat_qrcode,
            wechat_welcome_text,
            wechat_welcome_img,
            wechat_welcome_img_mediaid,
            wechat_info_img
          }
          this.qrcodeUrl = response.data.qrcodeUrl
          this.infopicUrl = response.data.infopicUrl
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
    },
    handleQrcodeSuccess(res, file) {
      if (res.code === 200) {
        this.qrcodeUrl = URL.createObjectURL(file.raw)
        this.form.wechat_qrcode = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    handleWelcomepicSuccess(res, file) {
      if (res.code === 200) {
        this.form.wechat_welcome_img = res.data.url
        this.form.wechat_welcome_img_mediaid = res.data.media_id
        this.$message.success(res.message)
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    handleInfopicSuccess(res, file) {
      if (res.code === 200) {
        this.infopicUrl = URL.createObjectURL(file.raw)
        this.form.wechat_info_img = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeUpload(file) {
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
  width: 122px;
  height: 122px;
}
.qrcode-uploader img {
  width: 120px;
  height: 120px;
  border-radius: 6px;
}
.qrcode-uploader:hover {
  border-color: #409eff;
}
.qrcode-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 120px;
  height: 120px;
  line-height: 120px;
  text-align: center;
}
.welcomepic-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  width: 122px;
  height: 122px;
}
.welcomepic-uploader img {
  width: 120px;
  height: 120px;
  border-radius: 6px;
}
.welcomepic-uploader:hover {
  border-color: #409eff;
}
.welcomepic-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 120px;
  height: 120px;
  line-height: 120px;
  text-align: center;
}
.infopic-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  width: 122px;
  height: 122px;
}
.infopic-uploader img {
  width: 120px;
  height: 120px;
  border-radius: 6px;
}
.infopic-uploader:hover {
  border-color: #409eff;
}
.infopic-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 120px;
  height: 120px;
  line-height: 120px;
  text-align: center;
}
.el-input,
.el-input-group {
  width: 50%;
}
</style>
