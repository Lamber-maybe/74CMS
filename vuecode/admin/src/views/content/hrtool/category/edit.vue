<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>编辑分类</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/content/hrtool/category')"
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
        <el-form-item label="描述" prop="describe">
          <el-input v-model="form.describe" type="textarea" rows="4" />
        </el-form-item>
        <el-form-item label="排序" prop="sort_id">
          <el-input v-model.number="form.sort_id" />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')" :disabled="issubmit">保存</el-button>
          <el-button @click="goto('/content/hrtool/category')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { categoryEdit } from '@/api/hrtool_category'

export default {
  data() {
    return {
      issubmit: false,
      infoLoading: true,
      form: {
        name: '',
        describe: '',
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
        describe: [
          { required: true, message: '请输入分类描述', trigger: 'blur' },
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
      that.issubmit = true
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          categoryEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/content/hrtool/category')
              }, 1500)
              return true
            })
            .catch(() => {
              that.issubmit = false
              return false
            })
        } else {
          that.issubmit = false
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
