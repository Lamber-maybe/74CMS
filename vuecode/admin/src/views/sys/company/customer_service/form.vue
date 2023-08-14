<template>
  <div class="app-container">
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="100px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="照片" prop="photo">
        <el-upload
          class="photo-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="handlePhotoSuccess"
          :before-upload="beforePhotoUpload"
        >
          <img v-if="form.photo" :src="photoUrl" class="photo">
          <i v-else class="el-icon-plus photo-uploader-icon" />
        </el-upload>
      </el-form-item>
      <el-form-item label="姓名" prop="name">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="手机号" prop="mobile">
        <el-input v-model="form.mobile" />
      </el-form-item>
      <el-form-item label="座机" prop="tel">
        <el-input v-model="form.tel" />
      </el-form-item>
      <el-form-item label="微信" prop="weixin">
        <el-input v-model="form.weixin" />
      </el-form-item>
      <el-form-item label="微信二维码" prop="wx_qrcode">
        <el-upload
          class="photo-uploader"
          :action="apiUpload"
          :headers="headers"
          :show-file-list="false"
          :on-success="handleQrcodeSuccess"
          :before-upload="beforeQrcodeUpload"
        >
          <img v-if="form.wx_qrcode" :src="qrcodeUrl" class="photo">
          <i v-else class="el-icon-plus photo-uploader-icon" />
        </el-upload>
      </el-form-item>
      <el-form-item label="QQ" prop="qq">
        <el-input v-model="form.qq" />
      </el-form-item>
      <el-form-item label="是否可用" prop="status">
        <el-switch v-model="form.status" />
      </el-form-item>
      <el-form-item label=" ">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { customerServiceAdd, customerServiceEdit } from '@/api/customer_service'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'

export default {
  props: ['itemInfo'],
  data() {
    return {
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      photoUrl: '',
      qrcodeUrl: '',
      form: {
        id: 0,
        photo: 0,
        name: '',
        mobile: '',
        tel: '',
        weixin: '',
        qq: '',
        status: true
      },
      rules: {
        name: [
          {
            required: true,
            message: '请输入姓名',
            trigger: 'blur'
          }
        ],
        mobile: [
          {
            required: true,
            message: '请输入手机号',
            trigger: 'blur'
          }
        ]
      }
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
      if (this.itemInfo !== null) {
        this.itemInfo.status = this.itemInfo.status == 1
        this.form = { ...this.form, ...this.itemInfo }
        this.photoUrl = this.itemInfo.photoUrl
        this.qrcodeUrl = this.itemInfo.qrcodeUrl
      }
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.status = insertData.status === true ? 1 : 0
          customerServiceAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    editSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.status = insertData.status === true ? 1 : 0
          customerServiceEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...that.form }
      if (parseInt(insertData.id) > 0) {
        that.editSave(insertData, formName)
      } else {
        that.addSave(insertData, formName)
      }
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    },
    handlePhotoSuccess(res, file) {
      if (res.code == 200) {
        this.photoUrl = URL.createObjectURL(file.raw)
        this.form.photo = res.data.file_id
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
    },
    handleQrcodeSuccess(res, file) {
      if (res.code == 200) {
        this.qrcodeUrl = URL.createObjectURL(file.raw)
        this.form.wx_qrcode = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeQrcodeUpload(file) {
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
<style scoped>
.el-select,
.el-input {
  width: 300px;
}
.photo-uploader {
  display: inline-block;
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  width: 122px;
  height: 122px;
}
.photo-uploader img {
  width: 120px;
  height: 120px;
  border-radius: 6px;
}
.photo-uploader:hover {
  border-color: #409eff;
}
.photo-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 120px;
  height: 120px;
  line-height: 120px;
  text-align: center;
}
</style>
