<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix"><span style="font-weight: 600;color: #333;">附件简历</span></div>
      <div v-if="resumeEnclosure.enclosure" class="enclosureResume">
        <span v-if="is_check == 1" style="cursor:pointer;" @click="seeResumeAttachment()">{{ resumeEnclosure.title }}</span>
        <span v-if="is_check == 0">
          <a style="cursor:pointer;color:#409EFF;" :href="resumeEnclosure.enclosure" :download="resumeEnclosure.enclosure">{{ resumeEnclosure.title }}</a>
        </span>
        <span class="delEnclosure" @click="handleRemove()">删除</span>
      </div>
      <div v-else class="resumeEnclosureUpload">
        <el-upload
          class="avatar-uploader"
          list-type="picture-card"
          :action="apiUpload"
          :headers="headers"
          :data="uploadData"
          :on-success="handlePhotoSuccess"
          :before-upload="beforePhotoUpload"
          :show-file-list="false"
        >
          <i class="el-icon-plus" />
        </el-upload>
      </div>
    </el-card>
    <el-dialog append-to-body :visible.sync="showIframe"><iframe zIndex="3000" width="100%" height="600" frameborder="0" scrolling="auto" :src="iframeUrl" /></el-dialog>
  </div>
</template>

<script>
import { resumeImg, enclosureDelete, enclosureSave } from '@/api/resume'
import { documentConfig, documentCheck } from '@/api/configuration'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'
import md5 from 'js-md5'

export default {
  props: ['id', 'resumeEnclosure'],
  data() {
    return {
      dialogImageUrl: '',
      dialogVisible: false,
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.enclosureSave,
      uploadData: {},
      is_check: 0,
      showIframe: false,
      iframeUrl: '',
      configInfo: {}
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
    this.uploadData.rid = this.id
    this.getDocumentCheck()
  },
  methods: {
    getDocumentCheck() {
      documentCheck().then(res => {
        this.is_check = res.data.is_check
      })
    },
    // 查看简历附件
    seeResumeAttachment(row) {
      this.getDocumentConfig()
    },
    getDocumentConfig() {
      const param = {}
      documentConfig(param, 'get')
        .then(response => {
          this.configInfo = response.data
          const url = this.resumeEnclosure.enclosure // 要预览文件的访问地址
          const timeStamp = parseInt(Date.now() / 1000)
          const appKey = this.configInfo.appKey
          const appSecret = this.configInfo.appSecret
          const Base64URI = this.$Base64.encode(url)
          const serverName = document.domain
          const request = Base64URI + appKey + timeStamp + appSecret + serverName
          const sign = md5(request)
          this.iframeUrl =
            'https://document.zhitoo.cn/zhitooDocument?url=' +
            Base64URI +
            '&appKey=' +
            appKey +
            '&appSecret=' +
            appSecret +
            '&timeStamp=' +
            timeStamp +
            '&serverName=' +
            serverName +
            '&sign=' +
            sign
          this.showIframe = true
        })
        .catch(() => {})
    },
    handleRemove(file, fileList) {
      this.$confirm('确定删除附件简历吗?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          const param = {
            rid: this.id,
            enclosure_id: this.resumeEnclosure.id
          }
          enclosureDelete(param).then(response => {
            this.$emit('resumeDetails')
            this.$message.success(response.message)
            return true
          })
        })
        .catch(err => {
          console.log(err)
        })
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url
      this.dialogVisible = true
    },
    handlePhotoSuccess(res, file) {
      this.$emit('resumeDetails')
      this.$message.success(res.message)
      return true
    },
    beforePhotoUpload(file) {
      const fileArr = file?.name.split('.')
      const fileType = fileArr[fileArr.length - 1]
      const typeArr = ['txt', 'html', 'pdf', 'doc', 'docx']
      if (typeArr.indexOf(fileType) === -1) {
        this.$message.error('文件类型不支持')
        return false
      }
      const fileSize = file.size / 1024 / 1024
      const size = this.$store.state.config.fileupload_size / 1024
      const sizeLimit = size.toFixed(1)
      if (fileSize > sizeLimit) {
        this.$message.warning('上传文件大小不能超过' + sizeLimit + 'MB!')
        return false
      }
    }
  }
}
</script>
<style lang="scss">
.enclosureResume {
  width: 100%;
  color: #1787fb;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: space-between;

  .delEnclosure {
    font-size: 14px;
    color: red;
    cursor: pointer;
  }
}

.resumeEnclosureUpload {
  width: 148px;
  height: 148px;
  border: 1px dashed #c0ccda;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  .avatar-uploader{
    width: 100%;
    height: 100%;
  }
}
::v-deep .el-upload {
  display: inline-block;
    text-align: center;
    cursor: pointer;
    outline: 0;
    width: 100%;
    height: 100%;
    line-height: 148px;
}
</style>
