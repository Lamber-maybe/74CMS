<template>
  <el-card>
		<company-title>负责人身份验证</company-title>
		<div class="heading">
			<img src="../../../assets/images/warning_icon.png"/>
			身份证信息仅用于系统身份认证，未经允许不会对外公开
		</div>
		<el-form :model="form" label-width="120px" label-position="right" ref="form">
		<div>
				<el-form-item 
					label="负责人姓名" 
					prop="legal_person_name" 
					:rules="[
						{
						required: true,
						message: '请填写负责人姓名',
						trigger: 'blur'
						},
						{
						max: 30,
						message: '长度在 1 到 30 个字符',
						trigger: 'blur'
						}
					]"
				>
					<el-input v-model="form.legal_person_name" placeholder="请填写"></el-input>
				</el-form-item>
				<el-form-item label="身份证号" prop="legal_person_idcard_number" :rules="[
					{
					required: true,
					message: '请填写身份证号',
					trigger: 'blur'
					},
					{ validator: validateIdcard, trigger: 'blur' }
					]">
					<el-input v-model="form.legal_person_idcard_number" placeholder="请填写"></el-input>
				</el-form-item>
		</div>
		<div class="uploader__wrapper">
			<div>
				<el-upload  class="upload-demo"   drag  action="#" :show-file-list="false" :http-request="handlerUploadFront" >
					<img class="uploaded-img" :src="form.legal_person_idcard_front_img!=''?form.legal_person_idcard_front_img:require('../../../assets/images/identityCard_1.png')"/>
					<div class="uploader_text">请上传人像面</div>
				</el-upload>
			</div>
			<div>
				<el-upload  class="upload-demo"   drag  action="#" :show-file-list="false" :http-request="handlerUploadBack">
					<img class="uploaded-img" :src="form.legal_person_idcard_back_img!=''?form.legal_person_idcard_back_img:require('../../../assets/images/identityCard_2.png')"/>
					<div class="uploader_text">请上传国徽面</div>
				</el-upload>
			</div>
		</div>
		<el-button type="primary" @click="onSubmit">提交</el-button>
		</el-form>
	</el-card>
</template>

<script>
import { isIdcard } from "@/utils/index";
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'AuthPersonalCharge',
  data () {
    return {
		form:{
			legal_person_name: '',
			legal_person_idcard_number: '',
			legal_person_idcard_front: '',
			legal_person_idcard_front_img: '',
			legal_person_idcard_back: '',
			legal_person_idcard_back_img: ''
		}
    }
  },
  mounted(){
    this.initCB()
  },
  methods: {
	validateIdcard(rule, value, callback){
		if(!isIdcard(this.form.legal_person_idcard_number)){
			callback(new Error('身份证号码错误'));
		}else{
			callback();
		}
	},
    initCB () {
      this.form.legal_person_name = ''
      this.form.legal_person_idcard_number = ''
      this.form.legal_person_idcard_front = ''
      this.form.legal_person_idcard_front_img = ''
      this.form.legal_person_idcard_back = ''
      this.form.legal_person_idcard_back_img = ''
    },
    handlerUploadFront (file) {
		let fileRaw = file.file
		let filetypeArr = (fileRaw.type || "").split("/");
		let filetype = filetypeArr[1];
		let tyepArr = ['png', 'jpg', 'jpeg', 'bmp', 'gif']
		if(-1 == tyepArr.indexOf(filetype)){
			this.$message.error('文件类型不支持')
			return false
		}
		if(fileRaw.size>this.$store.state.config.fileupload_size*1024){
			let size = this.$store.state.config.fileupload_size/1024
			size = size.toFixed(1)
			this.$message.error('文件大小超出限制，最大'+size+'mb')
			return false
		}
      http.postFormData(api.uploadFile, { file: fileRaw }).then(res => {
        this.form.legal_person_idcard_front = res.data.file_id
        this.form.legal_person_idcard_front_img = res.data.file_url
      })
    },
    handlerUploadBack (file) {
		let fileRaw = file.file
		let filetypeArr = (fileRaw.type || "").split("/");
		let filetype = filetypeArr[1];
		let tyepArr = ['png', 'jpg', 'jpeg', 'bmp', 'gif']
		if(-1 == tyepArr.indexOf(filetype)){
			this.$message.error('文件类型不支持')
			return false
		}
		if(fileRaw.size>this.$store.state.config.fileupload_size*1024){
			let size = this.$store.state.config.fileupload_size/1024
			size = size.toFixed(1)
			this.$message.error('文件大小超出限制，最大'+size+'mb')
			return false
		}
      http.postFormData(api.uploadFile, { file: fileRaw }).then(res => {
        this.form.legal_person_idcard_back = res.data.file_id
        this.form.legal_person_idcard_back_img = res.data.file_url
      })
	},
    onSubmit () {
		this.$refs.form.validate(valid => {
        if (valid) {
			if (!this.form.legal_person_idcard_front) {
				this.$message.error('请上传人像面')
				return false
			}
			if (!this.form.legal_person_idcard_back) {
				this.$message.error('请上传国徽面')
				return false
			}
			http.post(api.company_auth_legal, this.form).then(() => {
				this.$emit('reload')
			})
        } else {
          return false;
        }
      });
    }
  }
}
</script>

<style scoped lang="scss">
	.heading{
		padding: 20px 20px;
		background: #fefce8;
		color: #ac9e7a;
		font-size: 14px;
		margin-bottom: 40px;
	}
	.heading img{
		vertical-align: middle;
		margin-right: 5px;
	}
	.el-input{
		width: 429px;
	}
	.el-form-item{
		margin-left: 20px;
	}
	.uploader__wrapper{
		padding: 97px 0;
		display: flex;
	}
	.uploader__wrapper>div{
		width: 225px;
		height: 195px;
	}
	.uploader__wrapper>div:nth-of-type(1){
		margin-left: 208px;
	}
	.uploader__wrapper>div:nth-of-type(2){
		margin-left: 250px;
	}
	.uploader_text{
		padding: 10px 0;
	}
	.el-button{
		width:260px;
		height: 42px;
		margin-left: 107px;
		margin-bottom: 169px;
	}
  .upload-demo .el-upload-dragger{
    border: none !important;
  }
  .uploaded-img{width:230px;height:145px;}
</style>
