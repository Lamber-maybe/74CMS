<template>
  <div>
    <el-card>
      <company-title>企业认证</company-title>
      <el-alert
        :title="tip"
        type="warning"
        show-icon
        :closable="false"
      ></el-alert>
      <div class="auth_box">
        <div class="com_title">企业名称：{{ authinfo.companyname }}</div>

        <div class="certificate marginBottom30">
          <div class="upload_box">
            <div class="margin20 upload_text">营业执照</div>

            <div class="upload">
              <el-upload
                class="avatar-uploader"
                :show-file-list="false"
                action="#"
                :http-request="handlerUploadLisence"
                :before-upload="beforeUpload"
              >
                <img v-if="license_img != ''" class="img" :src="license_img" />
                <i
                  v-if="license_img == ''"
                  class="el-icon-plus avatar-uploader-icon"
                ></i>
                <p v-if="license_img == ''" class="text">点击上传</p>
              </el-upload>
            </div>
          </div>
          <div class="tip_text">
            注册号、企业名称、法人代表、年检章等需清晰可辨；若无营业执照可上传组织机构代码证
          </div>
        </div>

        <div class="idcard marginBottom30" v-if="auth_type == 1">
          <div class="margin20 upload_text">经办人身份证</div>
          <div class="upload margin20">
            <el-upload
              class="avatar-uploader"
              :show-file-list="false"
              action="#"
              :http-request="handlerUploadIdcardFrond"
              :before-upload="beforeUpload"
            >
              <img
                v-if="legal_person_idcard_front_img != ''"
                class="img"
                :src="legal_person_idcard_front_img"
              />
              <i
                v-if="legal_person_idcard_front_img == ''"
                class="el-icon-plus avatar-uploader-icon"
              ></i>
              <p v-if="legal_person_idcard_front_img == ''" class="text">
                身份证正面
              </p>
            </el-upload>
          </div>
          <div class="upload">
            <el-upload
              class="avatar-uploader"
              :show-file-list="false"
              action="#"
              :http-request="handlerUploadIdcardBack"
              :before-upload="beforeUpload"
            >
              <img
                v-if="legal_person_idcard_back_img != ''"
                class="img"
                :src="legal_person_idcard_back_img"
              />
              <i
                v-if="legal_person_idcard_back_img == ''"
                class="el-icon-plus avatar-uploader-icon"
              ></i>
              <p v-if="legal_person_idcard_back_img == ''" class="text">
                身份证背面
              </p>
            </el-upload>
          </div>
        </div>

        <div class="entrust marginBottom30" v-if="auth_type == 1">
          <div class="upload_box">
            <div class="margin20 upload_text">委托书(函)</div>
            <div class="upload">
              <el-upload
                class="avatar-uploader"
                :show-file-list="false"
                action="#"
                :http-request="handlerUploadProxy"
                :before-upload="beforeUpload"
              >
                <img v-if="proxy_img != ''" class="img" :src="proxy_img" />
                <i
                  v-if="proxy_img == ''"
                  class="el-icon-plus avatar-uploader-icon"
                ></i>
                <p v-if="proxy_img == ''" class="text">点击上传</p>
              </el-upload>
            </div>
          </div>
          <div class="tip_text">
            上传委托书需填写相关信息后盖章并扫描成图片后上传
            <el-button type="text" @click="download">[下载模板]</el-button>
          </div>
        </div>

        <div>
          <el-button type="primary" class="btn" @click="onSubmit"
            >提交</el-button
          >
        </div>
      </div>
    </el-card>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'AuthForm',
  props: ['authinfo'],
  data() {
    return {
      auth_type: 0,
      tip: '',
      legal_person_idcard_front: '',
      legal_person_idcard_front_img: '',
      legal_person_idcard_back: '',
      legal_person_idcard_back_img: '',
      proxy: '',
      proxy_img: '',
      license: '',
      license_img: ''
    }
  },
  mounted() {
    this.auth_type = this.$store.state.config.audit_com_project
    this.tip = this.auth_type == 1 ? "根据相关法律法规要求，企业认证需要上传企业营业执照、经办人身份证、委托书(函)三项资料" : "根据相关法律法规要求，企业认证需要上传企业营业执照"
    this.legal_person_idcard_front = this.authinfo.legal_person_idcard_front
    this.legal_person_idcard_front_img = this.authinfo.legal_person_idcard_front_img
    this.legal_person_idcard_back = this.authinfo.legal_person_idcard_back
    this.legal_person_idcard_back_img = this.authinfo.legal_person_idcard_back_img
    this.proxy = this.authinfo.proxy
    this.proxy_img = this.authinfo.proxy_img
    this.license = this.authinfo.license
    this.license_img = this.authinfo.license_img
  },
  methods: {
    beforeUpload(file) {
      const isJPG = file.type === 'image/jpeg';
      const isPng = file.type === 'image/png';
      const isLtsize = file.size / 1024 < this.$store.state.config.fileupload_size;
      let size = this.$store.state.config.fileupload_size / 1024
      size = size.toFixed(1)

      if (!isJPG && !isPng) {
        this.$message.error('图片只能是 JPG/PNG 格式!');
        return false
      }
      if (!isLtsize) {
        this.$message.error('图片大小不能超过 '+size+'MB!');
        return false
      }

    },
    handlerUploadLisence(file) {
      http
        .postFormData(api.uploadFile, { file: file.file })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.license = res.data.file_id
            this.license_img = res.data.file_url
            this.$message({ type: 'success', message: res.message })
          } else {
            this.$message.error(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    handlerUploadIdcardFrond(file) {
      http
        .postFormData(api.uploadFile, { file: file.file })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.legal_person_idcard_front = res.data.file_id
            this.legal_person_idcard_front_img = res.data.file_url
            this.$message({ type: 'success', message: res.message })
          } else {
            this.$message.error(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    handlerUploadIdcardBack(file) {
      http
        .postFormData(api.uploadFile, { file: file.file })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.legal_person_idcard_back = res.data.file_id
            this.legal_person_idcard_back_img = res.data.file_url
            this.$message({ type: 'success', message: res.message })
          } else {
            this.$message.error(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    handlerUploadProxy(file) {
      http
        .postFormData(api.uploadFile, { file: file.file })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.proxy = res.data.file_id
            this.proxy_img = res.data.file_url
            this.$message({ type: 'success', message: res.message })
          } else {
            this.$message.error(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    onSubmit() {
      if (this.license == '' || this.license == '0') {
        this.$message.error('请上传营业执照照片')
        return false
      }
      if (this.auth_type == 1 && (this.legal_person_idcard_front == '' || this.legal_person_idcard_front == '0')) {
        this.$message.error('请上传经办人身份证正面照')
        return false
      }
      if (this.auth_type == 1 && (this.legal_person_idcard_back == '' || this.legal_person_idcard_back == '0')) {
        this.$message.error('请上传经办人身份证背面照')
        return false
      }
      if (this.auth_type == 1 && (this.proxy == '' || this.proxy == '0')) {
        this.$message.error('请上传委托书(函)')
        return false
      }
      http
        .post(api.company_auth_license, { license: this.license, legal_person_idcard_front: this.legal_person_idcard_front, legal_person_idcard_back: this.legal_person_idcard_back, proxy: this.proxy })
        .then(() => {
          this.$emit('reload')
        })
        .catch(() => { })
    },
    download() {
      location.href = window.global.RequestBaseUrl + api.downloadproxy
      this.$store.dispatch('getConfig')
    }
  }
}
</script>

<style scoped lang="scss">
.auth_box {
  padding: 40px 50px 0 50px;
  color: #333333;
  font-size: 14px;
}
.com_title {
  margin-bottom: 30px;
  margin-left: 37px;
}
.certificate .upload_box {
  display: flex;
  margin-bottom: 16px;
}
.entrust .upload_box {
  display: flex;
  margin-bottom: 16px;
}
.upload {
  width: 148px;
  height: 148px;
  border: 2px dashed #f3f3f3;
  cursor: pointer;
}
.upload .img {
  width: 152px;
  height: 152px;
}
.upload:hover {
  border: 2px dashed #1787fb;
}
.upload .avatar-uploader {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.upload .avatar-uploader .text {
  color: #1787fb;
  font-size: 14px;
}
.upload .avatar-uploader .el-icon-plus {
  color: #1787fb;
  font-size: 36px;
}

.tip_text {
  color: #d3d3d3;
  font-size: 14px;
  margin-left: 108px;
  padding-left: 20px;
  background: url("../../../assets/images/tips_icon.png") no-repeat left center;
}
.idcard {
  display: flex;
}
.entrust {
  // display: flex;
}
.margin20 {
  margin-right: 20px;
}

.marginBottom30 {
  margin-bottom: 30px;
}

.upload_text {
  width: 90px;
  text-align: right;
}
.btn {
  width: 260px;
  height: 42px;
  margin-left: 107px;
  margin-bottom: 20px;
}
</style>