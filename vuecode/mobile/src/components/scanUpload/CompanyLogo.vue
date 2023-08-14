<template>
    <div class="up-page">
    <div class="up-page-logo">
      <div class="logo-img">
        <img :src="basic.logo_src" border="0"/>
      </div>
    </div>
    <div class="up-sp-37"></div>
    <div class="up-txt-line font_3 font16 qs-center">{{basic.companyname}}</div>
    <div class="up-sp-45"></div>
    <div class="up-txt-line font_3 font14 qs-center">请点击下方区域上传您的 <span class="font_yellow">Logo</span></div>
    <div class="up-sp-40"></div>
    <van-uploader
        :preview-image="false"
        :after-read="uploadImg"
        :accept="'image/*'"
      >
      <div class="up-input-box">
        <div class="icon-add"></div>
        <div class="up-sp-29"></div>
        <div class="up-txt-line font_gray9">点击此区域上传</div>
        <div class="up-sp-29"></div>
        <div class="up-txt-line font12 font_yellow">图片大小{{($store.state.config.fileupload_size/1024).toFixed()}}M以内</div>
    </div>
    </van-uploader>
    <van-overlay :show="uploading"><van-loading type="spinner" size="24px">正在上传...</van-loading></van-overlay>

  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  props: ['access'],
  data () {
    return {
      uploading: false,
      basic: {}
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      http
        .get(api.company_profile + '?user-token=' + this.access + '&platform=mobile', {})
        .then(res => {
          const { basic } = { ...res.data }
          this.basic = basic
        })
        .catch(() => {})
    },
    // 作品上传
    uploadImg (file) {
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
        .postFormData(api.uploadFile + '?user-token=' + this.access + '&platform=mobile', { extra: 'company_logo', file: fileRaw })
        .then(res => {
          this.uploading = false
          this.basic.logo_src = res.data.file_url
          this.$notify({ type: 'success', message: res.message })
        })
        .catch(() => {
          this.uploading = false
        })
    }
  }
}
</script>

<style lang="scss" scoped>
/*扫码上传*/
.up-page{
  background:#FFF;
  font-size:14px;
  .van-uploader{
    display:block;
    width:300px;
    margin:0 auto;
  }
  .qs-center{
    text-align:center;
  }
  .font16{
    font-size:16px;
  }
  .font15{
    font-size:15px;
  }
  .font14{
    font-size:14px;
  }
  .font12{
    font-size:12px;
  }
  .up-page-logo{width:375px;height:91px;padding-top:59px;position:relative;background:#3eb1f8;text-align:center;margin-bottom:40px;border-radius:0 0 100% 100% / 100%;}
  .up-page-logo .logo-img{display:inline-block;width:70px;height:70px;-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;box-shadow:0px 0px 5px rgba(153,153,153,0.42);border:0;}
  .up-page-logo .logo-img img{width:70px;height:70px;border:0;-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;}
  .img-box{width:300px;margin:0 auto;text-align:center;position:relative;}
  .img-box .img-cell{width:300px;border:1px solid #ededed;}
  .img-box .img-cell img{max-width:260px;}
  .img-box .img-cell .img-del{position:absolute;top:-8.5px;right:-8.5px;width:17px;height:17px;-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;background-image:url(../../assets/images/287.png);background-size:17px;background-repeat:no-repeat;}
  .up-input-box{width:300px;height:157.5px;padding-top:34.5px;margin:0 auto;background:#f7f7f7;text-align:center;position:relative;}
  .up-input-box .file-input{position:absolute;top:0;left:0;display:block;width:300px;height:157.5px;border:0;opacity:0;}
  .up-input-box .icon-add{display:inline-block;width:45px;height:45px;background-image:url(../../assets/images/286.png);background-size:45px;background-repeat:no-repeat;}
  .up-txt-line{line-height:1;}
  .block-sp2{width:335px;height:2px;margin:0 auto;background:#e2e2e2;}
  .img-callback-box{padding:0 19.5px;}
  .img-callback-cell{float:left;width:100px;height:100px;line-height:100px;border:1px solid #ededed;position:relative;margin-right:18px;margin-bottom:18px;text-align:center;position:relative;}
  .img-callback-cell img{display:inline-block;max-width:100px;max-height:100px;vertical-align:middle;}
  .img-callback-cell .img-del{position:absolute;top:-8.5px;right:-8.5px;width:17px;height:17px;-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;background-image:url(../../assets/images/287.png);background-size:17px;background-repeat:no-repeat;}
  .img-callback-cell:nth-child(3n){margin-right:0;}
  .pd-left-45{padding-left:22.5px;}
  .up-sp-29{width:100%;height:14.5px;}
  .up-sp-37{width:100%;height:18.5px;}
  .up-sp-39{width:100%;height:19.5px;}
  .up-sp-40{width:100%;height:20px;}
  .up-sp-45{width:100%;height:22.5px;}
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
