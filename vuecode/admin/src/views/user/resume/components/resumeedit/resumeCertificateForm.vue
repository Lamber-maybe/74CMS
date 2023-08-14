<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="150px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="证书名称" prop="name">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="获得时间" prop="obtaintime">
        <el-date-picker
          v-model="form.obtaintime"
          type="month"
          placeholder="选择获得时间"
          value-format="yyyy-MM"
        />
      </el-form-item>
      <el-form-item label=" ">
        <el-button
          type="primary"
          :loading="submitLoading"
          @click="onSubmit('form')"
        >
          保存
        </el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
import { resumeCertificateAddAndEdit } from '@/api/resume'
export default {
  props: ['itemId'],
  data() {
    var validateObtaintime = (rule, value, callback) => {
      if (value) {
        const now = new Date()
        const start = new Date(value)
        if (start.getTime() > now.getTime()) {
          callback(new Error('获得时间不能大于当前时间'))
        } else {
          callback()
        }
      }
    }
    return {
      submitLoading: false,
      infoLoading: false,
      form: {
        id: 0,
        rid: 0,
        name: '',
        obtaintime: ''
      },
      rules: {
        name: [
          {
            required: true,
            message: '请输入项目名称',
            trigger: 'blur'
          }
        ],
        obtaintime: [
          {
            required: true,
            message: '请选择获得时间',
            trigger: 'change'
          },
          { validator: validateObtaintime, trigger: 'change' }
        ]
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      const param = {
        id: this.itemId
      }
      resumeCertificateAddAndEdit(param, 'get')
        .then(response => {
          if (response.data.info != null) {
            this.form = { ...response.data.info }
          } else {
            this.form.rid = this.$route.query.id
          }

          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      if (this.submitLoading === true) {
        return false
      }
      this.submitLoading = true
      const that = this
      const insertData = { ...that.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          resumeCertificateAddAndEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          this.submitLoading = false
          return false
        }
      })
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    }
  }
}
</script>
<style scoped>
.el-form.common-form .el-input {
  width: 300px;
}
</style>
