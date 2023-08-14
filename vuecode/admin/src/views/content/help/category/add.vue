<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>添加分类</span>
        <el-button
          style="float: right; padding: 0;margin-left:14px"
          type="text"
          @click="goto('/content/help/category')"
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
        <el-form-item label="分类名称" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
          <el-button @click="goto('/content/help/category')">返回</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { categoryAdd } from '@/api/help_category'

export default {
  data() {
    return {
      form: {
        name: ''
      },
      rules: {
        name: [
          { required: true, message: '请输入分类名称', trigger: 'blur' },
          { max: 10, message: '长度在 1 到 10 个字符', trigger: 'blur' }
        ]
      }
    }
  },
  mounted() {},
  created() {},
  methods: {
    onSubmit(formName) {
      const that = this
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          categoryAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/content/help/category')
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
