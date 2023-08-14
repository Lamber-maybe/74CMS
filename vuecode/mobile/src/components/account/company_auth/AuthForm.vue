<template>
  <div id="app">
    <Head> 企业认证 </Head>
    <div>
      <div class="tips">根据相关法律法规要求，企业认证需要上传以下资料</div>
      <div class="com_name_box">
        <div class="text">企业名称</div>
        <div class="com_name">{{authinfo.companyname}}</div>
      </div>

      <div class="certificate">
        <div class="upload_txt">营业执照</div>
        <div class="upload">
          <van-uploader :after-read="handlerUploadLisence">
            <div class="box_1" v-if="license_img != ''">
              <img
                :src="license_img"
                width="100"
                height="100"
              />
            </div>
            <p v-else class="txt">点击上传</p>
          </van-uploader>
        </div>
      </div>

      <div class="idcard" v-if="auth_type == 1">
        <div class="upload_txt">经办人身份证</div>
        <div class="upload margin">
          <van-uploader :after-read="handlerUploadIdcardFrond">
            <div class="box_1" v-if="legal_person_idcard_front_img != ''">
              <img
                :src="legal_person_idcard_front_img"
                width="100"
                height="100"
              />
            </div>
            <p v-else class="txt">身份证(正)</p>
          </van-uploader>
        </div>
        <div class="upload">
          <van-uploader :after-read="handlerUploadIdcardBack">
            <div class="box_1" v-if="legal_person_idcard_back_img != ''">
              <img
                :src="legal_person_idcard_back_img"
                width="100"
                height="100"
              />
            </div>
            <p v-else class="txt">身份证(反)</p>
          </van-uploader>
        </div>
      </div>

      <div class="entrust" v-if="auth_type == 1">
        <div class="upload_txt">委托书(函)</div>
        <div class="upload">
          <van-uploader :after-read="handlerUploadProxy">
            <div class="box_1" v-if="proxy_img != ''">
              <img
                :src="proxy_img"
                width="100"
                height="100"
              />
            </div>
            <p v-else class="txt">点击上传</p>
          </van-uploader>
        </div>
      </div>

      <div class="btn_box">
        <van-button type="info" class="btn" @click="onSubmit">提交</van-button>
      </div>
    </div>
    <van-overlay :show="uploading"><van-loading type="spinner" size="24px">正在上传...</van-loading></van-overlay>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'AuthForm',
  props: ['authinfo'],
  data () {
    return {
      auth_type: 0,
      uploading: false,
      legal_person_idcard_front: '',
      legal_person_idcard_front_img: '',
      legal_person_idcard_back: '',
      legal_person_idcard_back_img: '',
      proxy: '',
      proxy_img: '',
      license: '',
      license_img: '',
      fileList: [
        {
          url: 'https://img01.yzcdn.cn/vant/leaf.jpg'
        }
      ]
    }
  },
  mounted () {
    this.auth_type = this.$store.state.config.audit_com_project
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
    handlerUploadLisence (file) {
      let fileRaw = file.file
      let filetypeArr = (fileRaw.type || '').split('/')
      let filetype = filetypeArr[1]
      let tyepArr = ['png', 'jpg', 'jpeg', 'bmp', 'gif']
      if (tyepArr.indexOf(filetype) == -1) {
        this.$notify('文件类型不支持')
        return false
      }
      if (fileRaw.size > this.$store.state.config.fileupload_size * 1024) {
        let size = this.$store.state.config.fileupload_size / 1024
        size = size.toFixed(1)
        this.$notify('文件大小超出限制，最大' + size + 'mb')
        return false
      }
      this.uploading = true
      http
        .postFormData(api.uploadFile, { file: file.file })
        .then(res => {
          this.uploading = false
          this.license = res.data.file_id
          this.license_img = res.data.file_url
        })
        .catch(() => {
          this.uploading = false
        })
    },
    handlerUploadIdcardFrond (file) {
      let fileRaw = file.file
      let filetypeArr = (fileRaw.type || '').split('/')
      let filetype = filetypeArr[1]
      let tyepArr = ['png', 'jpg', 'jpeg', 'bmp', 'gif']
      if (tyepArr.indexOf(filetype) == -1) {
        this.$notify('文件类型不支持')
        return false
      }
      if (fileRaw.size > this.$store.state.config.fileupload_size * 1024) {
        let size = this.$store.state.config.fileupload_size / 1024
        size = size.toFixed(1)
        this.$notify('文件大小超出限制，最大' + size + 'mb')
        return false
      }
      this.uploading = true
      http
        .postFormData(api.uploadFile, { file: file.file })
        .then(res => {
          this.uploading = false
          this.legal_person_idcard_front = res.data.file_id
          this.legal_person_idcard_front_img = res.data.file_url
        })
        .catch(() => {
          this.uploading = false
        })
    },
    handlerUploadIdcardBack (file) {
      let fileRaw = file.file
      let filetypeArr = (fileRaw.type || '').split('/')
      let filetype = filetypeArr[1]
      let tyepArr = ['png', 'jpg', 'jpeg', 'bmp', 'gif']
      if (tyepArr.indexOf(filetype) == -1) {
        this.$notify('文件类型不支持')
        return false
      }
      if (fileRaw.size > this.$store.state.config.fileupload_size * 1024) {
        let size = this.$store.state.config.fileupload_size / 1024
        size = size.toFixed(1)
        this.$notify('文件大小超出限制，最大' + size + 'mb')
        return false
      }
      this.uploading = true
      http
        .postFormData(api.uploadFile, { file: file.file })
        .then(res => {
          this.uploading = false
          this.legal_person_idcard_back = res.data.file_id
          this.legal_person_idcard_back_img = res.data.file_url
        })
        .catch(() => {
          this.uploading = false
        })
    },
    handlerUploadProxy (file) {
      let fileRaw = file.file
      let filetypeArr = (fileRaw.type || '').split('/')
      let filetype = filetypeArr[1]
      let tyepArr = ['png', 'jpg', 'jpeg', 'bmp', 'gif']
      if (tyepArr.indexOf(filetype) == -1) {
        this.$notify('文件类型不支持')
        return false
      }
      if (fileRaw.size > this.$store.state.config.fileupload_size * 1024) {
        let size = this.$store.state.config.fileupload_size / 1024
        size = size.toFixed(1)
        this.$notify('文件大小超出限制，最大' + size + 'mb')
        return false
      }
      this.uploading = true
      http
        .postFormData(api.uploadFile, { file: file.file })
        .then(res => {
          this.uploading = false
          this.proxy = res.data.file_id
          this.proxy_img = res.data.file_url
        })
        .catch(() => {
          this.uploading = false
        })
    },
    onSubmit () {
      if (this.license == '' || this.license == '0') {
        this.$notify('请上传营业执照照片')
        return false
      }
      if (this.auth_type == 1 && (this.legal_person_idcard_front == '' || this.legal_person_idcard_front == '0')) {
        this.$notify('请上传经办人身份证正面照')
        return false
      }
      if (this.auth_type == 1 && (this.legal_person_idcard_back == '' || this.legal_person_idcard_back == '0')) {
        this.$notify('请上传经办人身份证背面照')
        return false
      }
      if (this.auth_type == 1 && (this.proxy == '' || this.proxy == '0')) {
        this.$notify('请上传委托书(函)')
        return false
      }
      http
        .post(api.company_auth_license, { license: this.license, legal_person_idcard_front: this.legal_person_idcard_front, legal_person_idcard_back: this.legal_person_idcard_back, proxy: this.proxy })
        .then(() => {
          this.$emit('reload')
        })
        .catch(() => {})
    },
    reload () {
      this.$emit('reload')
    }
  }
}
</script>

<style lang="scss" scoped>

.tips {
  color: #ff6600;
  font-size: 12px;
  padding: 12px 0 12px 35px;
  background: url("../../../assets/images/remind_ico.svg") no-repeat 17px center
    #fffbeb;
  background-size: 12px;
}

.com_name_box {
  display: flex;
  color: #333333;
  font-size: 14px;
  padding: 0.346667rem 0.8rem 0.346667rem 17px;
  border-bottom: 1px solid #f3f3f3;
  .text {
    width: 2.4rem;
  }
  .com_name {
    width: 6.5rem;
    text-align: right;
  }
}

.certificate,
.idcard,
.entrust {
  padding: 21px 17px 0px;
  display: flex;
}

.upload_txt {
  width: 70px;
  color: #333333;
  font-size: 14px;
  margin-right: 18px;
}

.upload {
  width: 100px;
  height: 100px;
  background-color: #f8f8f8;
  color: #c0c0c0;
  font-size: 14px;
  .van-uploader {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    background: url("../../../assets/images/auth_upload_bg.png") no-repeat center 21px;
    background-size: 38px 34px;
  }
  >>> .van-uploader__preview{
    margin: 0;
    padding: 0;
  }

  >>> .van-uploader__preview-image{
    width: 100px;
    height: 100px;
  }
  .txt {
    margin-top: 60px;
  }
}

.margin {
  margin-right: 20px;
}

.btn_box {
  display: flex;
  justify-content: center;
  margin-top: 46px;
  .btn {
    width: 316px;
    height: 41px;
    border-radius: 20px;
  }
}
.van-overlay{
  text-align:center;
  z-index:2;
}
.van-loading{
  top:36%;
}
.van-loading__text{
  color:#c3c3c3;
}

</style>
