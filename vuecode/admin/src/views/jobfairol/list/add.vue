<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>发布网络招聘会</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/jobfairol/list')"
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
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="标题" prop="title">
          <el-input v-model="form.title" />
          <span class="smalltip">
            <i class="el-icon-info" />
            (如：2017年5月1日医药行业主题招聘会)
          </span>
        </el-form-item>
        <el-form-item label="举办日期" prop="holddate">
          <el-date-picker
            v-model="form.holddate"
            type="datetimerange"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            format="yyyy-MM-dd HH:mm:ss"
          />
        </el-form-item>
        <el-form-item required label="缩略图" prop="thumb">
          <el-upload
            class="thumb-uploader"
            :action="apiUpload"
            :headers="headers"
            :show-file-list="false"
            :on-success="handleThumbSuccess"
            :before-upload="beforeThumbUpload"
          >
            <img v-if="form.thumb" :src="thumbUrl" class="thumb">
            <i v-else class="el-icon-plus thumb-uploader-icon" />
          </el-upload>
        </el-form-item>
        <el-form-item label="招聘会介绍" required prop="content">
          <div id="editor" class="editor" />
        </el-form-item>
        <el-form-item required label="允许报名套餐" prop="enable_setmeal_id">
          <el-checkbox-group v-model="form.enable_setmeal_id">
            <el-checkbox v-for="(item, index) in options_setmeal" :key="index" :label="item.id">{{ item.name }}</el-checkbox>
          </el-checkbox-group>
        </el-form-item>
        <el-form-item label="仅认证企业报名">
          <el-switch v-model="form.must_company_audit" />
        </el-form-item>
        <el-form-item label="要求简历完整度">
          <el-input v-model="form.min_complete_percent" type="number" />
        </el-form-item>
        <el-form-item required label="联系客服" prop="qrcode">
          <el-upload
            class="thumb-uploader"
            :action="apiUpload"
            :headers="headers"
            :show-file-list="false"
            :on-success="handleQrSuccess"
            :before-upload="beforeThumbUpload"
          >
            <img v-if="form.qrcode" :src="qrcodeUrl" class="thumb">
            <i v-else class="el-icon-plus thumb-uploader-icon" />
          </el-upload>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/jobfairol/list')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { getToken } from '@/utils/auth'
import { jobFairOnLineAdd } from '@/api/jobfairol'
import E from 'wangeditor'
import apiArr from '@/api'
import { getClassify } from '@/api/classify'
export default {
  data() {
    var validateContent = (rule, value, callback) => {
      value = this.editor.txt.text()
      if (value === '') {
        callback(new Error('请输入招聘会介绍'))
      } else {
        callback()
      }
    }
    var validateThumb = (rule, value, callback) => {
      value = this.form.thumb
      if (value === '') {
        callback(new Error('请上传缩略图'))
      } else {
        callback()
      }
    }
    var validateQrcode = (rule, value, callback) => {
      value = this.form.qrcode
      if (value === '') {
        callback(new Error('请上传客服二维码'))
      } else {
        callback()
      }
    }
    return {
      thumbUrl: '',
      qrcodeUrl: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      editor: '',
      options_setmeal: [],
      form: {
        title: '',
        thumb: '',
        holddate: '',
        content: '',
        enable_setmeal_id: [],
        must_company_audit: false,
        min_complete_percent: '',
        qrcode: ''
      },
      rules: {
        title: [
          { required: true, message: '请填写标题', trigger: 'blur' },
          { max: 200, message: '长度在 1 到 200 个字符', trigger: 'blur' }
        ],
        holddate: [
          { required: true, message: '请选择举办日期', trigger: 'blur' }
        ],
        content: [{ validator: validateContent, trigger: 'blur' }],
        thumb: [{ validator: validateThumb }],
        enable_setmeal_id: [
          { required: true, message: '请选择允许报名套餐' }
        ],
        qrcode: [{ validator: validateQrcode }]
      }
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  mounted() {
    this.editor = new E('#editor')
    this.editor.config.uploadImgServer = window.global.RequestBaseUrl + apiArr.uploadEditor
    this.editor.config.uploadImgHeaders = {
      admintoken: getToken()
    }
    this.editor.config.zIndex = 0
    this.editor.config.pasteFilterStyle = false
    this.editor.create()
    this.getSetmeal()
  },
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
  },
  methods: {
    handleThumbSuccess(res, file) {
      if (res.code === 200) {
        this.thumbUrl = URL.createObjectURL(file.raw)
        this.form.thumb = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    handleQrSuccess(res, file) {
      if (res.code === 200) {
        this.qrcodeUrl = URL.createObjectURL(file.raw)
        this.form.qrcode = res.data.file_id
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
    },
    onSubmit(formName) {
      const that = this
      this.form.content = this.editor.txt.html()
      var insertData = Object.assign({}, this.form)
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData['starttime'] = new Date(insertData.holddate[0]).getTime() / 1000
          insertData['endtime'] = new Date(insertData.holddate[1]).getTime() / 1000
          insertData['must_company_audit'] = insertData.must_company_audit === true ? 1 : 0
          insertData['enable_setmeal_id'] = insertData.enable_setmeal_id.join(',')
          jobFairOnLineAdd(insertData).then(response => {
            this.$message.success(response.message)
            setTimeout(function() {
              that.$router.push('/jobfairol/list')
            }, 1500)
            return true
          }).catch(() => {})
        } else {
          return false
        }
      })
    },
    goto(target) {
      this.$router.push(target)
    },
    getSetmeal() {
      getClassify({ type: 'setmealList' })
        .then(response => {
          this.options_setmeal = [...response.data]
        })
        .catch(() => {})
    }
  }
}
</script>
<style>
  input[type=number] {
    -moz-appearance:textfield;
  }
  input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
</style>
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
  .el-textarea,
  .el-select,
  .el-input,
  .el-date-editor {
    width: 450px;
  }
</style>

