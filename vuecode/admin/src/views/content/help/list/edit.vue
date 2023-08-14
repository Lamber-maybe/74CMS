<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>编辑</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/content/help/list')"
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
        <el-form-item label="分类" prop="cid">
          <el-select v-model="form.cid" placeholder="请选择分类">
            <el-option
              v-for="(item, index) in helpCategory"
              :key="index"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="内容" required prop="content">
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
        <el-form-item label="是否显示">
          <el-switch v-model="form.is_display" />
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input v-model.number="form.sort_id" />
        </el-form-item>
        <el-form-item label="seo关键词" prop="seo_keywords">
          <el-input v-model="form.seo_keywords" />
        </el-form-item>
        <el-form-item label="seo描述" prop="seo_description">
          <el-input v-model="form.seo_description" type="textarea" rows="4" />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/content/help/list')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { helpEdit } from '@/api/help'
import { getClassify } from '@/api/classify'
import apiArr from '@/api'
import { getToken } from '@/utils/auth'
import '@wangeditor/editor/dist/css/style.css'
import { Editor, Toolbar } from '@wangeditor/editor-for-vue'

export default {
  components: { Editor, Toolbar },
  data() {
    var validateContent = (rule, value, callback) => {
      value = this.editor.getText()
      if (value === '') {
        callback(new Error('请输入内容'))
      } else {
        callback()
      }
    }
    return {
      editor: null,
      toolbarConfig: {
        excludeKeys: [
          'fullScreen'
        ]
      },
      helpCategory: [],
      form: {
        title: '',
        cid: '',
        content: '',
        is_display: true,
        seo_keywords: '',
        seo_description: '',
        sort_id: 0
      },
      rules: {
        title: [
          {
            required: true,
            message: '请输入标题',
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
            message: '请选择分类',
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
  },
  created() {
    this.fetchInfo()
  },
  beforeDestroy() {
    const editor = this.editor
    if (editor == null) return
    editor.destroy() // 组件销毁时，及时销毁编辑器
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
    fetchInfo() {
      this.infoLoading = true
      getClassify({ type: 'helpCategory' })
        .then(response => {
          this.helpCategory = response.data
          const param = { id: this.$route.query.id }
          return helpEdit(param, 'get')
        })
        .then(response => {
          this.form = { ...response.data.info }
          this.form.is_display = this.form.is_display == 1
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      this.form.content = this.editor.getHtml()
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.is_display = insertData.is_display === true ? 1 : 0
          helpEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/content/help/list')
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
