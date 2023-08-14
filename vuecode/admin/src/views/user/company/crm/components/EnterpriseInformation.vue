<template>
  <div v-loading="loading" class="clientInfo_wrapper">
    <el-row class="main">
      <el-col :span="13">
        <div class="tables">
          <div class="title_t">企业资料</div>
          <CompayEdit :id="details.id" />
        </div>
        <!--        <div class="tables">-->
        <!--          <div class="title_t">企业资料</div>-->
        <!--          <el-form ref="form" class="tableForm" :model="form" label-width="120px">-->
        <!--            <el-form-item label="公司名称：">-->
        <!--              <el-input v-model="form.companyname" />-->
        <!--            </el-form-item>-->
        <!--            <el-form-item label="公司简称：">-->
        <!--              <el-input v-model="form.short_name" />-->
        <!--            </el-form-item>-->
        <!--            <el-form-item label="公司性质：">-->
        <!--              <el-select v-model="form.nature" placeholder="请选择公司性质" style="width:100%">-->
        <!--                <el-option v-for="item in companyNature" :label="item.name" :value="item.id" />-->
        <!--              </el-select>-->
        <!--            </el-form-item>-->
        <!--            <el-form-item label="公司规模：">-->
        <!--              <el-select v-model="form.scale" placeholder="请选择公司规模" style="width:100%">-->
        <!--                <el-option v-for="item in companyScale" :label="item.name" :value="item.id" />-->
        <!--              </el-select>-->
        <!--            </el-form-item>-->
        <!--            <el-form-item label="所属行业：">-->
        <!--              <el-select v-model="form.trade" placeholder="请选择所属行业" style="width:100%">-->
        <!--                <el-option v-for="item in trade" :label="item.name" :value="item.id" />-->
        <!--              </el-select>-->
        <!--            </el-form-item>-->
        <!--                    <el-form-item label="企业Logo：">-->
        <!--                      <div style="position: relative">-->
        <!--                        <div v-if="form.logo_url" class="transparent" @click="logoDel">-->
        <!--                          <i class="el-icon-delete" />-->
        <!--                          删除-->
        <!--                        </div>-->
        <!--                        <el-upload-->
        <!--                          class="avatar-uploader"-->
        <!--                          :headers="headerObj"-->
        <!--                          :action="upload_url"-->
        <!--                          :show-file-list="false"-->
        <!--                          :on-success="handleAvatarSuccess"-->
        <!--                          :before-upload="beforeAvatarUpload"-->
        <!--                        >-->
        <!--                          <img v-if="form.logo_url" :src="form.logo_url" class="avatar">-->
        <!--                          <i v-else class="el-icon-plus avatar-uploader-icon" />-->
        <!--                        </el-upload>-->
        <!--                      </div>-->
        <!--                    </el-form-item>-->
        <!--            <el-form-item>-->
        <!--              <el-button type="primary" @click="onSubmit">保存资料</el-button>-->
        <!--            </el-form-item>-->
        <!--          </el-form>-->
        <!--        </div>-->
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
import { classify, companyCrmEdit, deleteImg } from '@/api/company_crm'
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
      loading: true,
      form: {
        uid: '',
        id: '',
        companyname: '',
        short_name: '',
        nature: '',
        scale: '',
        trade: '',
        logo_url: '',
        logo: 0
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
      this.form.id = value.id
      this.form.companyname = value.companyname
      this.form.short_name = value.short_name
      this.form.nature = value.nature
      this.form.scale = value.scale
      this.form.trade = value.trade
      this.form.logo = value.logo
      this.form.logo_url = value.logo_url
      this.loading = false
    }
  },
  created() {
    this.corporate_style_url = window.global.RequestBaseUrl + '/b2bcrm/Profile/uploadImg'
    this.upload_url = window.global.RequestBaseUrl + '/upload/index'
    this.headerObj = {
      'admintoken': getToken()
    }
    this.classify()
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
    onSubmit() {
      companyCrmEdit(this.form)
        .then(response => {
          this.$message.success(response.message)
          this.$emit('companyDetails')
          return true
        })
        .catch(() => {
          this.submitLoading = false
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
</style>
