<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>编辑分类</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/content/article/category')"
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
        <el-form-item label="分类名称" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input v-model.number="form.sort_id" />
        </el-form-item>
        <el-form-item label="seo标题" prop="seo_title">
          <el-input v-model="form.seo_title" />
        </el-form-item>
        <el-form-item label="seo关键词" prop="seo_keywords">
          <el-input v-model="form.seo_keywords" />
        </el-form-item>
        <el-form-item label="seo描述" prop="seo_description">
          <el-input v-model="form.seo_description" type="textarea" rows="4" />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/content/article/category')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { categoryEdit } from '@/api/article_category'

export default {
  data() {
    return {
      infoLoading: true,
      form: {
        name: '',
        seo_title: '',
        seo_keywords: '',
        seo_description: '',
        sort_id: 0
      },
      rules: {
        name: [
          { required: true, message: '请输入分类名称', trigger: 'blur' },
          { max: 10, message: '长度在 1 到 10 个字符', trigger: 'blur' }
        ],
        sort_id: [
          { type: 'number', message: '排序必须为数字', trigger: 'blur' }
        ],
        seo_title: [
          { max: 100, message: '长度在 0 到 100 个字符', trigger: 'blur' }
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
  mounted() {},
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = { id: this.$route.query.id }
      categoryEdit(param, 'get')
        .then(response => {
          this.form = { ...response.data.info }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          categoryEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/content/article/category')
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
