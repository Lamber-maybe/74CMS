<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加在线客服</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/sys/basic/serviceol')"
        >
          返回
        </el-button>
        <el-button
          style="float: right; padding: 0;"
          type="text"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
      </div>
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="120px"
        :inline-message="true"
        :rules="rules"
      >
        <el-form-item label="姓名" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="电话" prop="mobile">
          <el-input v-model="form.mobile" />
        </el-form-item>
        <el-form-item required label="微信" prop="weixin">
          <el-upload
            class="thumb-uploader"
            :action="apiUpload"
            :headers="headers"
            :show-file-list="false"
            :on-success="handleThumbSuccess"
            :before-upload="beforeThumbUpload"
          >
            <img v-if="form.weixin" :src="imageSrc" class="thumb">
            <i v-else class="el-icon-plus thumb-uploader-icon" />
          </el-upload>
        </el-form-item>
        <el-form-item label="QQ" prop="qq">
          <el-input v-model="form.qq" />
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input v-model="form.sort" />
        </el-form-item>
        <el-form-item label="是否显示" prop="is_display">
          <el-radio-group v-model="form.display">
            <el-radio :label="1">显示</el-radio>
            <el-radio :label="0">不显示</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/sys/basic/serviceol')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { serviceOlAdd } from '@/api/serviceol'
import { getToken } from '@/utils/auth'
import apiArr from '@/api'

export default {
  data() {
    var validateImage = (rule, value, callback) => {
      if (this.form.weixin == '' && value == '') {
        callback(new Error('请上传微信图片'))
      } else {
        callback()
      }
    }
    return {
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      form: {
        name: '',
        mobile: '',
        weixin: '',
        qq: '',
        sort: 0,
        display: 1
      },
      imageSrc: '',
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
            message: '请输入电话',
            trigger: 'blur'
          }
        ],
        weixin: [{ validator: validateImage, trigger: 'blur' }],
        qq: [
          {
            required: true,
            message: '请输入QQ',
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
  },
  methods: {
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          serviceOlAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/sys/basic/serviceol')
              }, 1500)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    goto(target) {
      this.$router.push(target)
    },
    handleThumbSuccess(res, file) {
      if (parseInt(res.code) === 200) {
        this.imageSrc = URL.createObjectURL(file.raw)
        this.form.weixin = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeThumbUpload(file) {
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
  .thumb-uploader {
    display: inline-block;
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    width: 122px;
    height: 122px;
  }
  .thumb-uploader img {
    width: 120px;
    height: 120px;
    border-radius: 6px;
  }
  .thumb-uploader:hover {
    border-color: #409eff;
  }
  .thumb-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 120px;
    height: 120px;
    line-height: 120px;
    text-align: center;
  }
  .el-select,
  .el-input,
  .el-date-editor {
    width: 450px;
  }
</style>
