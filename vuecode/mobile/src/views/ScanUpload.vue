<template>
<div>
  <CompanyImg :access="access" v-if="type=='company_img'"></CompanyImg>
  <CompanyLogo :access="access" v-if="type=='company_logo'"></CompanyLogo>
  <ResumeImg :access="access" v-if="type=='resume_img'"></ResumeImg>
  <ResumePhoto :access="access" v-if="type=='resume_photo'"></ResumePhoto>
    <BottomNav></BottomNav>
</div>
</template>

<script>
import CompanyImg from '@/components/scanUpload/CompanyImg'
import CompanyLogo from '@/components/scanUpload/CompanyLogo'
import ResumeImg from '@/components/scanUpload/ResumeImg'
import ResumePhoto from '@/components/scanUpload/ResumePhoto'
export default {
  data () {
    return {
      type: '',
      access: ''
    }
  },
  components: {
    CompanyImg,
    CompanyLogo,
    ResumeImg,
    ResumePhoto
  },
  created () {
    this.type = this.$route.query.type
    this.access = this.$route.query.access
    this.$store.commit('setLoginState', {
      whether: false,
      utype: 0,
      token: '',
      mobile: '',
      userIminfo: {}
    })
  },
  methods: {

  }
}
</script>

<style lang="scss" scoped>
/*扫码上传*/
.up-page{
  background:#FFF;
  font-size:14px;
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
  .img-box .img-cell .img-del{position:absolute;top:-8.5px;right:-8.5px;width:17px;height:17px;-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;background-image:url(../assets/images/287.png);background-size:17px;background-repeat:no-repeat;}
  .up-input-box{width:300px;height:157.5px;padding-top:34.5px;margin:0 auto;background:#f7f7f7;text-align:center;position:relative;}
  .up-input-box .file-input{position:absolute;top:0;left:0;display:block;width:300px;height:157.5px;border:0;opacity:0;}
  .up-input-box .icon-add{display:inline-block;width:45px;height:45px;background-image:url(../assets/images/286.png);background-size:45px;background-repeat:no-repeat;}
  .up-txt-line{line-height:1;}
  .block-sp2{width:335px;height:2px;margin:0 auto;background:#e2e2e2;}
  .img-callback-box{padding:0 19.5px;}
  .img-callback-cell{float:left;width:100px;height:100px;line-height:100px;border:1px solid #ededed;position:relative;margin-right:18px;margin-bottom:18px;text-align:center;position:relative;}
  .img-callback-cell img{display:inline-block;max-width:100px;max-height:100px;vertical-align:middle;}
  .img-callback-cell .img-del{position:absolute;top:-8.5px;right:-8.5px;width:17px;height:17px;-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;background-image:url(../assets/images/287.png);background-size:17px;background-repeat:no-repeat;}
  .img-callback-cell:nth-child(3n){margin-right:0;}
  .pd-left-45{padding-left:22.5px;}
  .up-sp-29{width:100%;height:14.5px;}
  .up-sp-37{width:100%;height:18.5px;}
  .up-sp-39{width:100%;height:19.5px;}
  .up-sp-40{width:100%;height:20px;}
  .up-sp-45{width:100%;height:22.5px;}
}

</style>
