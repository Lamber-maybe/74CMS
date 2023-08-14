<template>
  <div class="app-container">
    <el-card class="box-card">
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="80px"
        :rules="rules"
        :inline-message="true"
      >
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
        <el-form-item label="点击量" prop="click">
          <el-input v-model.number="form.click" />
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
import { getClassify } from '@/api/classify'
import { getToken } from '@/utils/auth'
import '@wangeditor/editor/dist/css/style.css'
import { getSeting, saveArticleSeting } from '@/api/datacollection'

export default {
  data() {
    return {
      editor: null,
      toolbarConfig: {
        excludeKeys: [
          'fullScreen'
        ]
      },
      headers: { admintoken: getToken() },
      articleCategory: [],
      form: {
        cid: 0,
        source: 0,
        click: 0
      },
      rules: {
        click: [
          {
            type: 'number',
            message: '浏览量必须为数字',
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
  },
  created() {
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
      getSeting({}, 'get')
        .then(response => {
          this.form.cid = response.data.article_seting.cid
          this.form.click = response.data.article_seting.click
          this.form.source = response.data.article_seting.source
        })
    },
    onSubmit() {
      const insertData = {
        cid: this.form.cid,
        source: this.form.source,
        click: this.form.click
      }
      this.$refs.form.validate((valid) => {
        if (valid){
          saveArticleSeting(insertData)
            .then(response => {
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          this.$message({
            message: '请填写数字类型的浏览量',
            type: 'error'
          })
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
