<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加公告</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/content/notice/list')"
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
        v-loading="infoLoading"
        class="common-form"
        :model="form"
        label-width="80px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="标题" prop="title">
          <el-input v-model="form.title" />
        </el-form-item>
        <el-form-item label="内容" required prop="content">
          <div id="editor" class="editor" />
        </el-form-item>
        <el-form-item label="附件" prop="attach">
          <el-upload
            class="upload-demo"
            :action="apiAttachUpload"
            :headers="headers"
            :on-remove="handleRemove"
            :file-list="form.attach"
            :on-success="handleAttachSuccess"
            :before-upload="beforeAttachUpload"
          >
            <el-button size="small" type="primary">点击上传</el-button>
            <div slot="tip" class="el-upload__tip">只能上传excel,word,ppt文件，且不超过{{ fileupload_size }}kb</div>
          </el-upload>
        </el-form-item>
        <el-form-item label="是否显示">
          <el-switch v-model="form.is_display" />
        </el-form-item>
        <el-form-item label="发布日期">
          <el-date-picker
            v-model="form.addtime"
            type="datetime"
            format="yyyy-MM-dd HH:mm"
            placeholder="请选择发布日期"
          />
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input v-model.number="form.sort_id" />
        </el-form-item>
        <el-form-item label="外部链接" prop="link_url">
          <el-input v-model="form.link_url" />
        </el-form-item>
        <el-form-item label="seo关键词" prop="seo_keywords">
          <el-input v-model="form.seo_keywords" />
        </el-form-item>
        <el-form-item label="seo描述" prop="seo_description">
          <el-input v-model="form.seo_description" type="textarea" rows="4" />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/content/notice/list')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { validUrl } from '@/utils/validate'
import { noticeEdit } from '@/api/notice'
import E from 'wangeditor'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'

export default {
  data() {
    var validateContent = (rule, value, callback) => {
      value = this.editor.txt.text()
      if (value === '') {
        callback(new Error('请输入内容'))
      } else {
        callback()
      }
    }
    var validateUrl = (rule, value, callback) => {
      if (value == '') {
        callback()
      }
      if (!validUrl(value)) {
        callback(new Error('请输入正确的网址'))
      } else {
        callback()
      }
    }
    return {
      headers: { admintoken: getToken() },
      fileupload_size: '',
      apiAttachUpload: window.global.RequestBaseUrl + apiArr.uploadAttach,
      infoLoading: true,
      editor: '',
      form: {
        title: '',
        content: '',
        attach:[],
        is_display: true,
        link_url: '',
        seo_keywords: '',
        seo_description: '',
        addtime: '',
        sort_id: 0
      },
      rules: {
        title: [
          { required: true, message: '请输入资讯标题', trigger: 'blur' },
          { max: 100, message: '长度在 1 到 100 个字符', trigger: 'blur' }
        ],
        content: [{ validator: validateContent, trigger: 'blur' }],
        sort_id: [
          { type: 'number', message: '排序必须为数字', trigger: 'blur' }
        ],
        link_url: [
          { max: 200, message: '长度在 0 到 200 个字符', trigger: 'blur' },
          { validator: validateUrl, trigger: 'blur' }
        ],
        seo_keywords: [
          { max: 100, message: '长度在 0 到 100 个字符', trigger: 'blur' }
        ],
        seo_description: [
          { max: 200, message: '长度在 0 到 200 个字符', trigger: 'blur' }
        ]
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
  },
  created() {
    this.fileupload_size = this.config.fileupload_size
    this.fetchInfo()
  },
  methods: {
    handleRemove(file, fileList) {
      let index = this.form.attach.indexOf({name:file.name,url:file.url})
      this.form.attach.splice(index,1)
    },
    handleAttachSuccess(res, file) {
      if (res.code == 200) {
        let info = {name:res.data.name,url:res.data.url}
        this.form.attach.push(info)
      } else {
        this.$message.error(res.message)
        return false
      }
    },
    beforeAttachUpload(file) {
      const configFileExtArr = 'doc,docx,xls,xlsx,csv,ppt,pptx,pdf'
      const filename_arr = file.name.split('.')
      const filetype = filename_arr[filename_arr.length-1]
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
    fetchInfo() {
      this.infoLoading = true
      const param = { id: this.$route.query.id }
      noticeEdit(param, 'get')
        .then(response => {
          // this.form = Object.assign({},this.form,response.data.info)
          this.form = { ...response.data.info }
          this.form.addtime = this.form.addtime * 1000
          this.form.is_display = this.form.is_display == 1
          this.editor.txt.html(this.form.content)
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      this.form.content = this.editor.txt.html()
      const insertData = { ...this.form }
      // const insertData = Object.assign({},this.form)
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.is_display = insertData.is_display === true ? 1 : 0
          if (insertData.addtime) {
            const d = new Date(insertData.addtime)
            insertData.addtime =
              d.getFullYear() +
              '-' +
              (d.getMonth() + 1) +
              '-' +
              d.getDate() +
              ' ' +
              d.getHours() +
              ':' +
              d.getMinutes()
          }
          noticeEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/content/notice/list')
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
    }
  }
}
</script>
<style scoped>
.el-textarea,
.el-select,
.el-input,
.el-date-editor {
  width: 450px;
}
</style>
