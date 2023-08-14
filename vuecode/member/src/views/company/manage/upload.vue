<template>
  <div class="upload_con">
    <el-upload class="imgList" :file-list="imgList" :show-file-list="false" action="#" list-type="picture-card" :http-request="handlerUpload" :limit="6" :on-exceed="handlerMax">
      <div slot="file" slot-scope="{file}">
        <img class="el-upload-list__item-thumbnail" :src="file.url" alt="" style="width:148px;height:148px;">
        <div class="removeIcon" @click="handleRemove(file,$event)" >
          x
        </div>
        <div class='img-mask' v-if="parseInt(file.audit) !== 1">{{file.audit_text?file.audit_text:'待审核'}}</div>
      </div>
      <div class="img_text">
        <img src="../../../assets/images/upload_icon.png" /><br/>
        <span>点击上传</span>
      </div>
    </el-upload>
    <span>本地上传</span>
    <p>jpg/gif/png格式（大小800KB以内）</p>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
	export default {
		data(){
			return {
				imgList:[],
        dialogVisible:false
			}
		},
		created () {
			this.fetchData()
		},
		methods:{
			fetchData () {
			http
				.get(api.company_profile, {})
				.then(res => {
					const { img_list } = { ...res.data }
					this.imgList = img_list.map(function (item) {
						return {id:item.id,audit:item.audit,audit_text:item.audit_text,url:item.img_src}
					})
				})
				.catch(() => {})
			},
			handlerUpload(file){
				http
					.postFormData(api.company_img_upload, { file: file.file })
					.then(res => {
					if (parseInt(res.code) === 200) {
						this.$message({ type: 'success', message: res.message })
					} else {
						this.$message.error(res.message)
					}
					})
					.catch(err => {
						console.log(err)
					})
			},
			handleRemove(file){
				this.$confirm('确定删除该作品吗','系统提示',{})
					.then(() => {
					// 确定按钮
					http
						.post(api.company_img_del, {id: file.id})
						.then(res => {
						if (parseInt(res.code) === 200) {
							this.$message({ type: 'success', message: res.message })
							this.imgList.splice(this.imgList.findIndex(item => parseInt(item.id) === parseInt(file.id)),1)
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

<style lang="scss" scoped>
.upload_con{
  text-align: center;
}
.upload_con span{
  margin: 14px 0;
  display: inline-block;
  font-size: 14px;
  color: #a9a9a9;
}
.upload_con p{
  font-size: 13px;
  color: #a9a9a9;
}
.el-button{
  width: 260px;
  margin-top: 150px;
}
.imgList{
  text-align: center;
}

.el-upload-list__item>div{
  position: relative;
}
.removeIcon{
  width: 22px;
  height: 22px;
  border-radius: 50%;
  text-align: center;
  font-size: 12px;
  color: #fff;
  position: absolute;
  right: -10px;
  top: -10px;
  background: #7f7f7f;
  z-index: 999;
  cursor:pointer;
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
.img_text img{
  margin: 40px 0 15px 0;
}
</style>
