<template>
  <div class="clientInfo_wrapper">
    <el-row class="main">
      <el-col :span="13">
        <div class="tables">
          <div class="title_t">企业资料</div>
          <CompayEdit :id="details.id" @companyDetails="companyDetails" />
        </div>
      </el-col>
      <el-col :span="11">
        <div class="corporateStyle">
          <div class="title_t">企业风采</div>
          <div class="upload-img">
            <div v-for="item in company_img" style="position: relative;display: inline-block">
              <div class="transparent-top" style="bottom: 5px;">
                <span v-if="item.audit == 1">审核通过</span>
                <span v-if="item.audit == 0">审核中</span>
                <span v-if="item.audit == 2">审核未通过</span>
              </div>
              <div class="transparent" style="bottom: 5px;" @click="corporateStyleDel(item.id)">
                <i class="el-icon-delete" />
                删除
              </div>
              <el-image
                cover
                :src="item.img"
                :preview-src-list="[item.img]"
              />
              <!--              <img :src="item.img" alt="">-->
            </div>
            <el-upload
              :class="company_img.length>=6 ? 'upload_hide' : ''"
              class="avatar-uploader avatar_top"
              :headers="headerObj"
              :data="{'company_id':form.id,'uid':form.uid}"
              :action="corporate_style_url"
              :show-file-list="false"
              :on-success="corporateStyle"
              :before-upload="corporateStyles"
            >
              <i class="el-icon-plus avatar-uploader-icon" />
            </el-upload>
          </div>
        </div>
        <div class="shortVideo">
          <div class="title_t">短视频招聘</div>
          <div class="upload-img">
            <img v-for="item in company_video" :src="item.img_url" alt="" @click="videoPlay(item.video_src)">
            <div v-if="company_video.length <= 0" class="empty_texts">
              <img src="../../../../../assets/images/company/crm/zanwei.png" alt="">
              <div>暂未上传短视频</div>
            </div>
          </div>
        </div>
      </el-col>
    </el-row>
    <el-dialog
      title="短视频招聘"
      append-to-body
      :visible.sync="dialogVisible"
      width="30%"
    >
      <div class="video">
        <video style="width: 100%;height:100%" autoplay controls :src="video_url" />
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { classify, deleteImg } from '@/api/company_crm'
import { getToken } from '@/utils/auth'
import CompayEdit from '@/views/user/company/edit'

export default {
  name: 'EnterpriseInformation',
  components: {
    CompayEdit
  },
  props: ['details', 'uid'],
  data() {
    return {
      form: {
        uid: '',
        id: ''
      },
      dialogVisible: false,
      companyNature: [],
      companyScale: [],
      trade: [],
      upload_url: '',
      headerObj: '',
      company_img: [],
      company_video: [],
      corporate_style_url: '',
      video_url: ''
    }
  },
  watch: {
    uid(value){
      this.form.uid = value
    },
    details(value){
      this.company_img = value.company_img
      this.company_video = value.company_video
      for (var i = 0; i <= value.company_video.length - 1; i++){
        this.company_video[i].img_url = value.company_video[i].video_src + '?vframe/jpg/offset/0'
      }
    }
  },
  created() {
    this.form.id = this.details.id
    this.company_img = this.details.company_img
    this.company_video = this.details.company_video
    for (var i = 0; i <= this.details.company_video.length - 1; i++){
      this.company_video[i].img_url = this.details.company_video[i].video_src + '?vframe/jpg/offset/0'
    }
    this.corporate_style_url = window.global.RequestBaseUrl + '/b2bcrm/Profile/uploadImg'
    this.upload_url = window.global.RequestBaseUrl + '/upload/index'
    this.headerObj = {
      'admintoken': getToken()
    }
    this.classify()
    this.form.uid = this.uid
  },
  methods: {
    videoPlay(url){
      this.video_url = url
      this.dialogVisible = true
    },
    corporateStyleDel(id){
      this.$confirm('确定将此风采删除?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteImg({ 'id': id, 'uid': this.form.uid, 'company_id': this.form.id })
          .then(response => {
            this.$message.success(response.message)
            this.$emit('companyDetails')
            return true
          })
          .catch(() => {
            this.submitLoading = false
          })
      }).catch(() => {

      })
    },
    classify(){
      classify({ 'type': 'companyNature,companyScale,trade' }).then(res => {
        this.companyNature = res.data.companyNature
        this.companyScale = res.data.companyScale
        this.trade = res.data.trade
      }).catch(() => {

      })
    },
    logoDel(){
      this.form.logo_url = ''
      this.form.logo = ''
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url
      this.dialogVisible = true
    },
    handleAvatarSuccess(res, file) {
      URL.createObjectURL(file.raw)
      this.form.logo_url = res.data.file_url
      this.form.logo = res.data.file_id
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === 'image/jpeg'
      const isPNG = file.type === 'image/png'
      const isLt2M = file.size / 1024 / 1024 < 2

      if (!isJPG && !isPNG) {
        this.$message.error('上传图片只能是 JPG / PNG 格式!')
      }
      if (!isLt2M) {
        this.$message.error('上传图片大小不能超过 2MB!')
      }
      return isJPG && isLt2M || isPNG
    },
    corporateStyle(res, file) {
      URL.createObjectURL(file.raw)
      if (res.code == 200){
        this.$message.success(res.message)
      } else {
        this.$message.error(res.message)
      }
      this.$emit('companyDetails')
    },
    corporateStyles(file) {
      const isJPG = file.type === 'image/jpeg'
      const isPNG = file.type === 'image/png'
      const isLt2M = file.size / 1024 / 1024 < 2

      if (!isJPG && !isPNG) {
        this.$message.error('上传图片只能是 JPG / PNG 格式!')
      }
      if (!isLt2M) {
        this.$message.error('上传图片大小不能超过 2MB!')
      }
      return isJPG && isLt2M || isPNG
    },
    companyDetails(){
      this.$emit('companyDetails')
    }
  }
}
</script>

<style lang="scss" scoped>
  .empty_texts{
    height:100%;
    text-align: center;
    font-size: 14px;
    color:#d1cfcf;
    line-height: 30px;
  }
  ::v-deep .el-image{
    width: 137px;
    height: 137px;
    margin-right: 10px;
  }
  .video{
    text-align: center;
  }
    .transparent-top{
      cursor:pointer;
      z-index: 1;
      font-size: 12px;
      border-radius: 3px;
      text-align: center;
      line-height: 25px;
      position: absolute;
      top: 0px;
      color: #d5cece;
      left: 0;
      width: 136px;
      height: 25px;
      background: rgba(0, 0, 0, 0.4);
    }
    .transparent{
      cursor:pointer;
      z-index: 1;
      font-size: 12px;
      border-radius: 3px;
      text-align: center;
      line-height: 25px;
      position: absolute;
      bottom: 16px;
      color: #d5cece;
      left: 0;
      width: 136px;
      height: 25px;
      background: rgba(0, 0, 0, 0.4);
    }
    ::v-deep .el-form-item{
      margin-bottom: 12px;
    }
    ::v-deep .el-form-item__label{
      font-size: 14px;
      color: #606266;
      font-weight:normal;
    }
  .clientInfo_wrapper{
    height: calc(100vh - 194px - 52px);
    overflow-y: auto;
  }
  .el-col-13{
    border-right: 1px solid #ebeef5;
  }
  .avatar-uploader ::v-deep .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader ::v-deep .el-upload:hover {
    border-color: #409EFF;
  }
  .title_t{
    font-weight: bold;
    font-size: 16px;
    margin-left: 20px
  }
  .upload-img{
    margin-left: 64px;
    img{
      width: 135px;
      height: 135px;
      margin-right: 15px;
      margin-top: 10px;
    }
    margin-left: 20px;
    margin-top: 20px;
  }
  .avatar_top{
    margin-top: 10px;
  }
  .avatar-uploader{
    display: inline-block;
  }
  .shortVideo{
    margin-top: 20px;
    margin-left: 20px;
    img{
      width: 135px;
      height: 135px;
      margin-right: 15px;
      margin-top: 10px;
    }
  }
  .corporateStyle{
    padding-bottom: 50px;
    margin-top: 20px;
    margin-left: 20px;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 135px;
    height: 135px;
    line-height: 135px;
    text-align: center;
  }
  .avatar {
    width: 135px;
    height: 135px;
    display: block;
  }
  .tableForm{
    padding:30px 0px;
  }
  .tables{
    padding:20px 30px 30px 30px;
    .title{
      font-weight: bold;
    }
  }
  .upload_hide{
    ::v-deep .el-upload--text{
      display: none;
    }
  }
</style>
