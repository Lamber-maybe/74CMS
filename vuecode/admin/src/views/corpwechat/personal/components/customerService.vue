<template>
  <div class="box">
    <el-form ref="form" :model="form" label-width="160px" :rules="rules">
      <el-form-item label="称呼：" prop="name">
        <el-input v-model="form.name" placeholder="请输入称呼" />
      </el-form-item>
      <el-form-item label="上传头像：">
        <el-upload
          ref="avatarRef"
          class="upload_box"
          :headers="headers"
          :action="apiUpload"
          :show-file-list="false"
          :on-success="handleAvatarSuccess"
          :before-upload="beforeAvatarUpload"
        >
          <i class="el-icon-plus avatar-uploader-icon" />
          <div v-if="form.photo_url" class="avatar">
            <img :src="form.photo_url">
            <div class="avatar_icon">
              <i class="el-icon-upload2 icon_del" />
              <i class="el-icon-delete" @click.stop="delAvatar" />
            </div>
          </div>
        </el-upload>
      </el-form-item>
      <el-form-item label="手机号：">
        <el-input v-model="form.mobile" oninput="value=value.replace(/[^0-9.]/g,'')" placeholder="请输入手机号码" />
      </el-form-item>
      <el-form-item label="座机：">
        <el-input v-model="form.tel" placeholder="请输入座机号码" />
      </el-form-item>
      <el-form-item label="微信二维码：">
        <el-upload
          ref="avatarRef"
          class="upload_box"
          :headers="headers"
          :action="apiUpload"
          :show-file-list="false"
          :on-success="handleWxSuccess"
          :before-upload="beforeWxUpload"
        >
          <i class="el-icon-plus avatar-uploader-icon" />
          <div v-if="form.wx_qrcode_url" class="avatar">
            <img :src="form.wx_qrcode_url">
            <div class="avatar_icon">
              <i class="el-icon-upload2 icon_del" />
              <i class="el-icon-delete" @click.stop="delWx" />
            </div>
          </div>
        </el-upload>
      </el-form-item>
      <el-form-item label="QQ：">
        <el-input v-model="form.qq" oninput="value=value.replace(/[^0-9.]/g,'')" placeholder="请输入QQ号码" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="getData">取消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { customerServiceEdit, customerServiceMod } from '@/api/personal'
import { getToken } from '@/utils/auth'
import apiArr from '@/api'
import { validMobile } from '@/utils/validate'
import { companyAdd } from '@/api/company'

export default {
  name: 'CustomerService',
  data() {
    return {
      rules: {
        name: [
          {
            required: true,
            message: '请输入用户名',
            trigger: 'blur'
          },
          {
            max: 6,
            message: '称呼最多6个字',
            trigger: 'blur'
          }
        ]
      },
      headers: { admintoken: getToken() },
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      form: {
        'name': '',
        'photo': '',
        'photo_url': '',
        'mobile': '',
        'tel': '',
        'wx_qrcode': '',
        'wx_qrcode_url': '',
        'qq': ''
      },
      fileupload_ext: '',
      fileupload_size: '',
      submitLoading: false
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = 'png,gif,jpeg,jpg'
    this.getData()
  },
  methods: {
    getData(){
      customerServiceMod().then(res => {
        this.form.name = res.data.name ? res.data.name : ''
        this.form.photo = res.data.photo ? res.data.photo : ''
        this.form.photo_url = res.data.photo_url ? res.data.photo_url : ''
        this.form.qq = res.data.qq ? res.data.qq : ''
        this.form.tel = res.data.tel ? res.data.tel : ''
        this.form.wx_qrcode = res.data.wx_qrcode ? res.data.wx_qrcode : ''
        this.form.wx_qrcode_url = res.data.wx_qrcode_url ? res.data.wx_qrcode_url : ''
        this.form.mobile = res.data.mobile ? res.data.mobile : ''
      })
    },
    onSubmit(formName) {
      if (this.submitLoading === true) {
        return false
      }
      this.submitLoading = true
      const insertData = { ...this.form }
      console.log(insertData.mobile)
      if (insertData.mobile == '' && insertData.tel == ''){
        this.$message.error('手机号码和固定电话号码必须填写一个')
        this.submitLoading = false
        return false
      }
      if (insertData.mobile != '' && !validMobile(insertData.mobile)) {
        this.$message.error('请输入正确的手机号码')
        this.submitLoading = false
        return false
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          customerServiceEdit(insertData).then(res => {
            if (res.code == '200'){
              this.$message.success('修改成功！')
              this.getData()
            } else {
              this.$message.error(res.message)
            }
            this.submitLoading = false
          }).catch(() => {
            this.submitLoading = false
          })
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    delWx(){
      this.form.wx_qrcode_url = ''
      this.form.wx_qrcode = 0
    },
    delAvatar() {
      this.form.photo_url = ''
      this.form.photo = 0
    },
    //  头像上传
    handleAvatarSuccess(res, file) {
      this.form.photo_url = res.data.file_url
      this.form.photo = res.data.file_id
    },
    beforeAvatarUpload(file) {
      const filetypeArr = file.type.split('/')
      const filetype = filetypeArr[1]
      const configFileExtArr = this.fileupload_ext.split(',')

      if (!configFileExtArr.includes(filetype)) {
        this.$message.error('请上传png,gif,jpeg,jpg的图片格式')
        return false
      }
      if (file.size / 1024 > this.fileupload_size) {
        this.$message.error(`上传文件最大为${this.fileupload_size}kb`)
        return false
      }
      return true
    },
    //  头像上传
    handleWxSuccess(res, file) {
      this.form.wx_qrcode_url = res.data.file_url
      this.form.wx_qrcode = res.data.file_id
    },
    beforeWxUpload(file) {
      const filetypeArr = file.type.split('/')
      const filetype = filetypeArr[1]
      const configFileExtArr = this.fileupload_ext.split(',')

      if (!configFileExtArr.includes(filetype)) {
        this.$message.error('请上传png,gif,jpeg,jpg的图片格式')
        return false
      }
      return true
    }
  }
}
</script>

<style scoped lang="scss">
  .upload_box{
    position: relative;
  }
  .avatar_icon{
    width: 100%;
    height:100%;
    background:rgba(0,0,0,.5);
    color:white;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top:0;
    font-size: 18px;
    .icon_del{
      margin-right: 10px;
    }
  }
  ::v-deep .el-input__inner{
    height: 36px;
    line-height: 36px;
  }
  ::v-deep .el-input{
    width: 50%;
    height: 36px;
    line-height: 36px;
  }
  .box{
    padding: 50px 0px;
  }
  /* 头像 */
  ::v-deep .el-upload{
    border: 1px dashed #d9d9d9;
  }
  .avatar-uploader ::v-deep.el-upload {
    border: 1px dashed #d9d9d9;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 18px;
    color: #8c939d;
    width: 80px;
    height: 80px;
    line-height: 80px;
    text-align: center;
  }
  .avatar {
    z-index: 1;
    width: 80px;
    height: 80px;
    position: absolute;
    left: 1px;
    top: 1px;
  }
  .avatar img{
    width: 100%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    display: table-cell;
  }
  .avatar .words{
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding-top: 2px;
    padding-bottom: 2px;
    font-size: 12px;
    transform: scale(0.9);
    background: rgba(0,0,0,0.9);
    line-height: 1;
    color: #ffffff;
  }
</style>
