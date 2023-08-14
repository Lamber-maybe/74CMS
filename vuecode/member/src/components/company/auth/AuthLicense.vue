<template>
  <el-card>
		<company-title>营业执照认证</company-title>
		<div class="heading">
			<img src="../../../assets/images/warning_icon.png"/>
			注册号、企业名称、法人代表、年检章等需清晰可辨
		</div>
		<div class="enterpriseName">
			企业名称:<span>{{companyname}}</span>
		</div>
		<div class="uload_content">
			<el-upload  class="upload-demo"  drag :show-file-list="false"  action="#" :http-request="handlerUpload">
				<img class="img_upload" :src="license_img!=''?license_img:require('../../../assets/images/certification.png')" />
			</el-upload>
			<div class="el-upload__text">{{license_img==''?'请上传营业执照':'点击重新上传营业执照'}}</div>
		</div>
			<div class="btn">
				<el-button type="primary" @click="onSubmit">提交</el-button>
			</div>
	</el-card>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'AuthLicense',
  data () {
    return {
      companyname: '',
      license: '',
      license_img: ''
    }
  },
  mounted(){
    this.initCB()
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
    handlerUpload(file){
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
    onSubmit () {
      if (!this.license) {
        this.$message.error('请上传营业执照照片')
        return false
      }
      http
        .post(api.company_auth_license, { license: this.license })
        .then(() => {
          this.$emit('reload')
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
	.heading{
		padding: 20px 20px;
		background: #fefce8;
		color: #ac9e7a;
		font-size: 14px;
	}
	.heading img{
		vertical-align: middle;
		margin-right: 5px;
	}
	.enterpriseName{
		margin: 50px 0;
	}
	.enterpriseName span{
		margin-left: 10px;
	}
	.uload_content{
		text-align: center;
	}
	.uload_content .upload-demo{
		width:325px ;
		height: 250px;
		margin: auto;
	}
	.el-upload__text{
		height: 58px;
		line-height: 58px;
	}
	
	.btn{
		text-align: center;
		padding: 0 20px;
	}
	.el-button{
		width: 260px;
		height: 42px;
	}
	.img_upload{
		margin-top: 10px;
    width:200px;
    height:232px;
	}
</style>