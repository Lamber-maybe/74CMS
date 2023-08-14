<template>
	<el-card>

		<company-title>
      企业风采
      <div class="wxCode">
        手机传图更方便，使用微信扫码上传
        <div class="codeImg">
          <span class="triangle"></span>
          <img :src="scanQrcode" />
          <p>微信扫描二维码<br/> 快速上传手机相册图片 </p>
        </div>
      </div>
    </company-title>

		<el-alert title="最多可上传6张企业风采图片"  type="warning" :closable='false'  show-icon> </el-alert>

		<div class="imgList_warpper">
			
			<div class="img-item" v-for="(item,index) in imgList" :key="index">
				<el-image
					style="width: 148px; height: 148px"
					:src="item.img_src" 
					:preview-src-list="srcList">
				</el-image>
				<div class="removeIcon" @click="handleRemove(item,$event)"></div>
				<div class='img-mask' v-if="parseInt(item.audit) !== 1">{{item.audit_text?item.audit_text:'待审核'}}</div>
			</div>
			<el-upload :show-file-list="false" class="imgList img_up_ico" action="#" list-type="picture-card" :http-request="handlerUpload" :on-exceed="handlerMax">
				<i  class="el-icon-plus"></i>
        <p>上传风采</p>
			</el-upload>
			<div class="clear"></div>
		</div>

	</el-card>

</template>

<script>
import http from '@/utils/http'
import api from '@/api'
	export default {
		data(){
			return {
				imgList:[],
				srcList:[],
				timer:'',
				scanQrcode:''
			}
		},
		created () {
			clearInterval(this.timer);
			this.fetchData()
			let locationUrl = this.$store.state.config.mobile_domain + 'scan_upload?type=company_img&access='+this.$store.state.userToken
			locationUrl = encodeURIComponent(locationUrl)
			this.scanQrcode = window.global.RequestBaseUrl + api.get_qrcode + '?type=normal&url=' + locationUrl
		},
		mounted() {
			this.timer = setInterval(this.scanUploadResult, 5000);
		},
		beforeDestroy() {
			clearInterval(this.timer);
		},
		methods:{
			scanUploadResult () {
			http
				.post(api.scan_upload_result, {type:'company_img'})
				.then(res => {
					if(res.data!=0){
						this.fetchData()
					}
				})
				.catch(() => {})
			},
			fetchData () {
			http
				.get(api.company_profile, {})
				.then(res => {
					const { img_list } = { ...res.data }
					this.imgList = img_list
					for (let index = 0; index < this.imgList.length; index++) {
						const element = this.imgList[index];
						this.srcList[index] = element.img_src
					}
				})
				.catch(() => {})
			},
			handlerUpload(file){
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
				http
					.postFormData(api.company_img_upload, { file: fileRaw })
					.then(res => {
					if (parseInt(res.code) === 200) {
						this.$message({ type: 'success', message: res.message })
						this.fetchData()
					} else {
						this.$message.error(res.message)
					}
					})
					.catch(err => {
						console.log(err)
					})
			},
			handleRemove(file){
				this.$confirm('确定删除该风采照片吗','系统提示',{type:'warning'})
					.then(() => {
					// 确定按钮
					http
						.post(api.company_img_del, {id: file.id})
						.then(res => {
						if (parseInt(res.code) === 200) {
							this.$message({ type: 'success', message: res.message })
							this.fetchData()
						} else {
							this.$message.error(res.message)
						}
						})
						.catch(err => {
							console.log(err)
						})
					})
					.catch(() => {
					// 取消按钮
					})
			},
			handlerMax(files, fileList){
				if(fileList.length>=6){
					this.$message.error('最多可上传6张企业风采图片')
				}
			}
		}
	}
</script>

<style scoped lang="scss">
.imgList_warpper{
	margin:20px 0;
}
.img-item{
	width:148px;
	height:148px;
	float:left;
	margin-right:15px;
	position:relative;
	img{
		width:148px;
		height:148px;
	}
}
.el-alert{
	padding: 16px;
	margin-bottom:20px ;
}
.btn_wrapper{
	text-align: center;
}
.el-button{
	width: 260px;
	margin-top: 150px;
}
.removeIcon{
	width: 22px;
	height: 22px;
	line-height:18px;
	border-radius: 50%;
	position: absolute;
	right: -10px;
	top: -10px;
	z-index: 999;
	cursor:pointer;
	background: #fff url('../../../assets/images/com_img_del_ico.png') 0 0 no-repeat;
}
.img-mask{
	left: 0;
	top: 0;
	height:148px;
	width:148px;
	position:absolute;
	background:#000;
	opacity:0.75;
	color:#c0c0c0;
	text-align:center;
	line-height:148px;
	font-size:16px;
}

.el-icon-plus{
  color: #1787fb;
  margin: 45px 0 10px ;
}

.wxCode{
  color:  #02bb29 ;
  font-size:13px;
  background: url(../../../assets/images/com_img_up_wx.png) 5px 6px  no-repeat;
  position: absolute;
  right: 5px;
  top: 17px;
  cursor: pointer;
  padding: 6px 0px 6px 25px;
  text-align: center;
}
.codeImg{
  width: 180px;
  height: 230px;
  position: absolute;
  right: 22px;
  bottom: -233px;
  background: #fff;
  z-index: 10;
  box-shadow: 0px 0px 5px 5px  #F3F3F3;
  display: none;
}
.triangle{
  border: 10px solid transparent;
  border-bottom: 10px solid #fff;
  position: absolute;
  top: -20px;
  left: 45px;
}
.wxCode:hover .codeImg{
  display: block;
}
.codeImg img{
  width: 150px;
  height: 150px;
  margin: 15px 0 10px 0;
}
.codeImg p{
  color: #333333;
  font-size:12px ;
}


</style>
