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
          :disabled="issubmit"
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
        <el-form-item label="招聘会介绍" required prop="content">
          <div style="border: 1px solid #ccc;">
            <Toolbar
              style="border-bottom: 1px solid #ccc"
              :editor="editor"
              :defaultConfig="toolbarConfig"
            />
            <Editor
              style="height: 400px; overflow-y: hidden;"
              v-model="form.content"
              @onCreated="onCreated"
            />
          </div>
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
        <el-form-item  label="上传图片" prop="thumb">
          <div class="upload-wrapper">
            <div class="upload-item">
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
              <div class="smalltips">
               缩略图
              </div>
              <div class="sp">
                <i class="el-icon-info" />
               建议尺寸：245*145
              </div>
            </div>
            <div class="upload-item">
              <el-upload
                class="thumb-uploader"
                :action="apiUpload"
                :headers="headers"
                :show-file-list="false"
                :on-success="handlePcSuccess"
                :before-upload="beforeThumbUpload"
              >
                <img v-if="form.pc_header_logo" :src="pc_header_logo_url" class="thumb">
                <i v-else class="el-icon-plus thumb-uploader-icon" />
              </el-upload>
              <span class="smalltips">
               pc头图
              </span>
              <span class="sp">
                <i class="el-icon-info" />
               建议尺寸：1200*330
              </span>
            </div>
            <div class="upload-item">
              <el-upload
                class="thumb-uploader"
                :action="apiUpload"
                :headers="headers"
                :show-file-list="false"
                :on-success="handleMobileSuccess"
                :before-upload="beforeThumbUpload"
              >
                <img v-if="form.mobile_header_logo" :src="mobile_header_logo_url" class="thumb">
                <i v-else class="el-icon-plus thumb-uploader-icon" />
              </el-upload>
              <span class="smalltips ">
               触屏头图
              </span>
              <span class=" sp">
                <i class="el-icon-info" />
               建议尺寸：750*280
              </span>
            </div>
          </div>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')" :disabled="issubmit">保存</el-button>
          <el-button @click="goto('/jobfairol/list')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { getToken } from '@/utils/auth'
import { jobFairOnLineEdit } from '@/api/jobfairol'
import apiArr from '@/api'
import { getClassify } from '@/api/classify'
import '@wangeditor/editor/dist/css/style.css'
import { Editor, Toolbar } from '@wangeditor/editor-for-vue'
export default {
  components: { Editor, Toolbar },
  data() {
    var validateContent = (rule, value, callback) => {
      value = this.editor.getText()
      if (value === '') {
        callback(new Error('请输入招聘会介绍'))
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
      issubmit:false,
      editor: null,
      toolbarConfig: {
        excludeKeys: [
          'fullScreen'
        ]
      },
      thumbUrl: '',
      qrcodeUrl: '',
      pc_header_logo_url: '',
      mobile_header_logo_url: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      headers: { admintoken: getToken() },
      fileupload_size: '',
      fileupload_ext: '',
      options_setmeal: [],
      form: {
        id: '',
        title: '',
        thumb: '',
        pc_header_logo: '',
        mobile_header_logo: '',
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
        enable_setmeal_id: [
          { required: true, message: '请选择允许报名套餐', trigger: 'blur' }
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
    this.getSetmeal()
    this.getJobfairOnline()
    this.form.id = this.$route.query.id
  },
  beforeDestroy() {
    const editor = this.editor
    if (editor == null) return
    editor.destroy() // 组件销毁时，及时销毁编辑器
  },
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fileupload_ext = this.config.fileupload_ext
  },
  methods: {
    onCreated(editor) {
      this.editor = Object.seal(editor) // 一定要用 Object.seal() ，否则会报错
      this.editor.getMenuConfig('uploadImage').headers = {
        admintoken: getToken()
      }
      this.editor.getMenuConfig('uploadImage').withCredentials = true
      this.editor.getMenuConfig('uploadImage').server = window.global.RequestBaseUrl + apiArr.uploadEditor
      this.editor.getMenuConfig('uploadVideo').headers = {
        admintoken: getToken()
      }
      this.editor.getMenuConfig('uploadVideo').withCredentials = true
      this.editor.getMenuConfig('uploadVideo').server = window.global.RequestBaseUrl + apiArr.uploadEditorVideo
    },
    getJobfairOnline: function(){
      this.infoLoading = true
      const param = { id: this.$route.query.id }
      jobFairOnLineEdit(param, 'get').then(response => {
        this.form = Object.assign(this.form, response.data.info)
        this.form.holddate = [this.form.starttime * 1000, this.form.endtime * 1000]
        this.form.enable_setmeal_id = this.form.enable_setmeal_id.split(',')
        this.form.enable_setmeal_id = this.form.enable_setmeal_id.map(item => {
          return parseInt(item)
        })
        this.form.must_company_audit = parseInt(this.form.must_company_audit) === 1
        this.thumbUrl = response.data.thumbUrl
        this.qrcodeUrl = response.data.qrcodeUrl
        this.mobile_header_logo_url = response.data.mobile_header_logo_url
        this.pc_header_logo_url = response.data.pc_header_logo_url
        this.infoLoading = false
      }).catch(() => {})
    },
    handleThumbSuccess(res, file) {
      if (res.code === 200) {
        this.thumbUrl = URL.createObjectURL(file.raw)
        this.form.thumb = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    handlePcSuccess(res, file) {
      if (res.code === 200) {
        this.pc_header_logo_url = URL.createObjectURL(file.raw)
        this.form.pc_header_logo = res.data.file_id
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    handleMobileSuccess(res, file) {
      if (res.code === 200) {
        this.mobile_header_logo_url = URL.createObjectURL(file.raw)
        this.form.mobile_header_logo = res.data.file_id
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
      this.issubmit = true
      const that = this
      this.form.content = this.editor.getHtml()
      var insertData = Object.assign({}, this.form)
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData['starttime'] = new Date(insertData.holddate[0]).getTime() / 1000
          insertData['endtime'] = new Date(insertData.holddate[1]).getTime() / 1000
          insertData['must_company_audit'] = insertData.must_company_audit === true ? 1 : 0
          insertData['enable_setmeal_id'] = insertData.enable_setmeal_id.join(',')
          jobFairOnLineEdit(insertData).then(response => {
            this.$message.success(response.message)
            setTimeout(function() {
              that.$router.push('/jobfairol/list')
            }, 1500)
            return true
          }).catch(() => {
            that.issubmit = false
          })
        } else {
          that.issubmit = false
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
  .smalltips{
    flex-shrink: 0;
    color: #8c8c8c;
    flex-shrink: 0;
    line-height: 31px;
  }
  .sp{
    flex-shrink: 0;
     font-size: 12px;
     color: #bdbdbd;
      flex-shrink: 0;
    line-height: 10px;
  }
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

  .upload-wrapper{
    display: flex;
  }
  .upload-wrapper .upload-item{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-right: 36px;
  }
</style>

