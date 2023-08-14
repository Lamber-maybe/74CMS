<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span  style="font-weight: 600;color: #333;">照片作品</span>
      </div>
      <el-upload
        :action="apiUpload"
        :headers="headers"
        list-type="picture-card"
        :on-success="handlePhotoSuccess"
        :before-upload="beforePhotoUpload"
        :on-preview="handlePictureCardPreview"
        :on-remove="handleRemove"
        :file-list="img_src_arr"
      >
        <i class="el-icon-plus" />
      </el-upload>
      <el-dialog :visible.sync="dialogVisible"  append-to-body>
        <img width="100%" :src="dialogImageUrl" alt="">
      </el-dialog>
    </el-card>
  </div>
</template>

<script>
import { resumeImg, resumeImgAdd, resumeImgDelete } from '@/api/resume'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'

export default {
  props: ['id'],
  data() {
    return {
      dialogImageUrl: '',
      dialogVisible: false,
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      img_src_arr: []
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
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      const param = {
        rid: this.id
      }
      this.img_src_arr = []
      resumeImg(param, 'get').then(response => {
        const img_data_arr = [...response.data.items]
        for (let index = 0; index < img_data_arr.length; index++) {
          this.img_src_arr.push({
            name: img_data_arr[index].id,
            url: img_data_arr[index].img_src
          })
        }
        this.$emit('setLoading', 'resumeImg')
      })
    },
    handleRemove(file, fileList) {
      const param = {
        id: file.name,
        rid: this.id
      }
      resumeImgDelete(param).then(response => {
        this.$message.success(response.message)
        return true
      })
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url
      this.dialogVisible = true
    },
    handlePhotoSuccess(res, file) {
      if (res.code == 200) {
        const param = {
          rid: this.id,
          imgid: res.data.file_id
        }
        resumeImgAdd(param).then(response => {
          file.name = response.data
          this.$message.success(response.message)
          return true
        })
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforePhotoUpload(file) {
      const filetypeArr = file.type.split('/')
      const filetype = filetypeArr[1]
      const configFileExtArr = this.fileupload_ext.split(',')

      if (!configFileExtArr.includes(filetype)) {
        this.$message.error('上传文件格式不允许')
        return false
      }
      if (file.size / 1024 > this.fileupload_size) {
        this.$message.error(`上传文件最大为${this.fileupload_size}kb`)
        return false
      }
      return true
    }
  }
}
</script>
