<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加资讯</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/content/article/list')"
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
        label-width="80px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="资讯标题" prop="title">
          <el-input v-model="form.title" />
        </el-form-item>
        <el-form-item label="资讯分类" prop="cid">
          <el-select v-model="form.cid" placeholder="请选择资讯分类">
            <el-option
              v-for="(item, index) in articleCategory"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="缩略图">
          <el-upload
            class="thumb-uploader"
            :action="apiUpload"
            :headers="headers"
            :show-file-list="false"
            :on-success="handleThumbSuccess"
            :before-upload="beforeThumbUpload"
          >
            <img v-if="form.thumb" :src="imageUrl" class="thumb">
            <i v-else class="el-icon-plus thumb-uploader-icon" />
          </el-upload>
        </el-form-item>
        <el-form-item label="内容" required prop="content">
          <div id="editor" class="editor" />
        </el-form-item>
        <el-form-item label="是否显示">
          <el-switch v-model="form.is_display" />
        </el-form-item>
        <el-form-item label="发布日期">
          <el-date-picker
            v-model="form.addtime"
            type="datetime"
            placeholder="请选择发布日期"
            format="yyyy-MM-dd HH:mm"
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
        <el-form-item label="来源" prop="source">
          <el-select v-model="form.source" placeholder="请选择资讯来源">
            <el-option label="原创" :value="0" />
            <el-option label="转载" :value="1" />
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/content/article/list')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { validUrl } from '@/utils/validate'
import { articleAdd } from '@/api/article'
import { getClassify } from '@/api/classify'
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
      fileupload_ext: '',
      apiUpload: window.global.RequestBaseUrl + apiArr.upload,
      editor: '',
      articleCategory: [],
      form: {
        title: '',
        cid: '',
        content: '',
        thumb: '',
        is_display: true,
        link_url: '',
        seo_keywords: '',
        seo_description: '',
        addtime: '',
        sort_id: 0,
        source: 0
      },
      imageUrl: '',
      rules: {
        title: [
          {
            required: true,
            message: '请输入资讯标题',
            trigger: 'blur'
          },
          {
            max: 100,
            message: '长度在 1 到 100 个字符',
            trigger: 'blur'
          }
        ],
        cid: [
          {
            required: true,
            message: '请选择资讯分类',
            trigger: 'change'
          }
        ],
        content: [{ validator: validateContent, trigger: 'blur' }],
        sort_id: [
          {
            type: 'number',
            message: '排序必须为数字',
            trigger: 'blur'
          }
        ],
        link_url: [
          {
            max: 200,
            message: '长度在 0 到 200 个字符',
            trigger: 'blur'
          },
          { validator: validateUrl, trigger: 'blur' }
        ],
        seo_keywords: [
          {
            max: 100,
            message: '长度在 0 到 100 个字符',
            trigger: 'blur'
          }
        ],
        seo_description: [
          {
            max: 200,
            message: '长度在 0 到 200 个字符',
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
    this.fileupload_ext = this.config.fileupload_ext
    this.fetchCategoryData()
  },
  methods: {
    fetchCategoryData() {
      const param = { type: 'articleCategory' }
      getClassify(param)
        .then(response => {
          this.articleCategory = response.data
          this.articleCategoryLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      this.form.content = this.editor.txt.html()
      const insertData = { ...this.form }
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
          articleAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/content/article/list')
              }, 1500)
              return true
            })
            .catch(() => {})
        } else {
          // console.log('error submit!!')
          return false
        }
      })
    },
    handleThumbSuccess(res, file) {
      if (res.code == 200) {
        this.imageUrl = URL.createObjectURL(file.raw)
        this.form.thumb = res.data.file_id
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
    goto(target) {
      this.$router.push(target)
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
.el-textarea,
.el-select,
.el-input,
.el-date-editor {
  width: 450px;
}
</style>
