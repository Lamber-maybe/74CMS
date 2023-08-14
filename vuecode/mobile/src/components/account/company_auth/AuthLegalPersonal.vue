<template>
  <div id="app">
    <Head :goback_custom="true" @gobackCustomMethod="$emit('closePopup')">
      负责人身份认证
    </Head>
    <div class="form_split_10"></div>
    <div class="notice_bar">
      身份证信息仅用于系统身份认证，未经允许不会对外公开
    </div>
    <van-form @submit="onSubmit">
      <van-field
        v-model="legal_person_name"
        name="legal_person_name"
        label="负责人姓名"
        placeholder="请填写"
        :rules="[{ required: true, message: '请填写负责人姓名' }]"
        class="reset_after"
      />
      <van-field
        v-model="legal_person_idcard_number"
        name="legal_person_idcard_number"
        label="身份证号"
        placeholder="请填写"
        :rules="[{ required: true, message: '请填写身份证号' }]"
        class="reset_after"
      />
      <div style="text-align:center;">
        <van-uploader :after-read="afterReadFront">
          <div class="box_1">
            <img
              v-if="legal_person_idcard_front_img != ''"
              :src="legal_person_idcard_front_img"
              width="100%"
              height="100%"
            />
          </div>
        </van-uploader>
      </div>
      <div class="box_2">请上传人像面</div>
      <div style="text-align:center;">
        <van-uploader :after-read="afterReadBack">
          <div class="box_3">
            <img
              v-if="legal_person_idcard_back_img != ''"
              :src="legal_person_idcard_back_img"
              width="100%"
              height="100%"
            />
          </div>
        </van-uploader>
      </div>
      <div class="box_2">请上传国徽面</div>
      <div style="margin: 10px 16px 0;">
        <van-button round block type="info" native-type="submit">
          提交认证
        </van-button>
      </div>
    </van-form>
    <van-overlay :show="uploading"><van-loading type="spinner" size="24px">正在上传...</van-loading></van-overlay>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'AuthPersonalCharge',
  data () {
    return {
      uploading: false,
      legal_person_name: '',
      legal_person_idcard_number: '',
      legal_person_idcard_front: '',
      legal_person_idcard_front_img: '',
      legal_person_idcard_back: '',
      legal_person_idcard_back_img: ''
    }
  },
  methods: {
    initCB () {
      this.legal_person_name = ''
      this.legal_person_idcard_number = ''
      this.legal_person_idcard_front = ''
      this.legal_person_idcard_front_img = ''
      this.legal_person_idcard_back = ''
      this.legal_person_idcard_back_img = ''
    },
    afterReadFront (file) {
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
      http.postFormData(api.uploadFile, { file: file.file }).then(res => {
        this.uploading = false
        this.legal_person_idcard_front = res.data.file_id
        this.legal_person_idcard_front_img = res.data.file_url
      })
        .catch(err => {
          this.uploading = false
          console.log(err)
        })
    },
    afterReadBack (file) {
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
      http.postFormData(api.uploadFile, { file: file.file }).then(res => {
        this.uploading = false
        this.legal_person_idcard_back = res.data.file_id
        this.legal_person_idcard_back_img = res.data.file_url
      })
        .catch(err => {
          this.uploading = false
          console.log(err)
        })
    },
    onSubmit (values) {
      if (!this.legal_person_idcard_front) {
        this.$notify('请上传人像面')
        return false
      }
      if (!this.legal_person_idcard_back) {
        this.$notify('请上传国徽面')
        return false
      }
      values.legal_person_idcard_front = this.legal_person_idcard_front
      values.legal_person_idcard_back = this.legal_person_idcard_back
      http.post(api.company_auth_legal, values).then(res => {
        this.$emit('reload')
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.box_3 {
  width: 202px;
  height: 129.5px;
  margin: 0 auto;
  background: url("../../../assets/images/auth_card_2.png") 0 no-repeat;
  background-size: 202px 129.5px;
}
.box_2 {
  padding: 12px 0 24px;
  text-align: center;
  font-size: 14px;
  color: #333333;
}
.box_1 {
  width: 200px;
  height: 127.5px;
  margin: 20px auto 0;
  background: url("../../../assets/images/auth_card_1.png") 0 no-repeat;
  background-size: 200px 127.5px;
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
