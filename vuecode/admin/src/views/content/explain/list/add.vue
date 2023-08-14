<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加说明页</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/content/explain/list')"
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
        <el-form-item label="标题" prop="title">
          <el-input v-model="form.title" />
        </el-form-item>
        <el-form-item label="内容" required prop="content">
          <div id="editor" class="editor" />
        </el-form-item>
        <el-form-item label="是否显示">
          <el-switch v-model="form.is_display" />
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
          <el-button @click="goto('/content/explain/list')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { validUrl } from '@/utils/validate'
import { explainAdd } from '@/api/explain'
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
      editor: '',
      explainCategory: [],
      form: {
        title: '',
        content: '',
        is_display: true,
        link_url: '',
        seo_keywords: '',
        seo_description: '',
        sort_id: 0
      },
      rules: {
        title: [
          { required: true, message: '请输入标题', trigger: 'blur' },
          {
            max: 100,
            message: '长度在 1 到 100 个字符',
            trigger: 'blur'
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
  },
  methods: {
    onSubmit(formName) {
      const that = this
      this.form.content = this.editor.txt.html()
      const insertData = { ...this.form }
      // const insertData = Object.assign({},this.form)
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.is_display = insertData.is_display === true ? 1 : 0
          explainAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/content/explain/list')
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
