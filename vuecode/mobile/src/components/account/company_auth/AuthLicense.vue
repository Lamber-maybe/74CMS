<template>
  <div id="app">
    <Head :goback_custom="true" @gobackCustomMethod="$emit('closePopup')">
      营业执照认证
    </Head>
    <div class="form_split_10"></div>
    <div class="notice_bar">注册号、企业名称、法人代表、年检章等需清晰可辨</div>
    <van-field
      v-model="companyname"
      name="fullname"
      label="企业名称"
      :placeholder="companyname"
      class="reset_after"
    />
    <div style="text-align:center;">
      <van-uploader :after-read="afterRead">
        <div class="box_1">
          <img
            v-if="license_img != ''"
            :src="license_img"
            width="100%"
            height="100%"
          />
        </div>
      </van-uploader>
    </div>

    <div class="box_2">点击上传营业执照</div>
    <div style="margin: 10px 16px 0;">
      <van-button
        round
        block
        type="info"
        native-type="button"
        @click="onSubmit"
      >
        提交认证
      </van-button>
    </div>
    <van-overlay :show="uploading"><van-loading type="spinner" size="24px">正在上传...</van-loading></van-overlay>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'AuthLicense',
  data () {
    return {
      uploading: false,
      companyname: '',
      license: '',
      license_img: ''
    }
  },
  methods: {
    initCB () {
      http
        .get(api.company_profile, {})
        .then(res => {
          this.companyname = res.data.basic.companyname
        })
        .catch(() => {})
    },
    afterRead (file) {
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
    onSubmit () {
      if (!this.license) {
        this.$notify('请上传营业执照照片')
        return false
      }
      http
        .post(api.company_auth_license, { license: this.license })
        .then(res => {
          this.$emit('reload')
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.box_2 {
  padding: 16px 0 28px;
  text-align: center;
  font-size: 14px;
  color: #333333;
}
.box_1 {
  width: 217.5px;
  height: 170px;
  margin: 20px auto 0;
  background: url("../../../assets/images/auth_card_3.png") 0 no-repeat;
  background-size: 217.5px 170px;
}
.notice_bar {
  width: 100%;
  padding: 11px 0 11px 35px;
  font-size: 12px;
  color: #ff6600;
  background: #fffbeb url("../../../assets/images/remind_ico.svg") 16px center
    no-repeat;
  background-size: 16px;
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
