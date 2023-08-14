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
      <el-form-item label="公司名称" prop="companyname">
        <el-input v-model="form.companyname" />
      </el-form-item>
      <el-form-item label="职位名称" prop="jobname">
        <el-input v-model="form.jobname" />
      </el-form-item>
      <el-form-item label="工作职责" prop="duty">
        <el-input v-model="form.duty" type="textarea" rows="6" />
      </el-form-item>
      <el-form-item label="任职开始时间" prop="starttime">
        <el-date-picker
          v-model="form.starttime"
          type="month"
          placeholder="选择任职开始时间"
          value-format="yyyy-MM"
        />
      </el-form-item>
      <el-form-item label="任职结束时间" prop="endtime" class="is-required">
        <el-date-picker
          v-model="form.endtime"
          :disabled="form.todate !== false"
          type="month"
          placeholder="选择任职结束时间"
          value-format="yyyy-MM"
        />
        <el-checkbox v-model="form.todate">至今</el-checkbox>
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
import { resumeWorkAddAndEdit } from '@/api/resume'
export default {
  props: ['itemId', 'rid'],
  data() {
    var validateStarttime = (rule, value, callback) => {
      if (value) {
        const now = new Date()
        const start = new Date(value)
        if (start.getTime() > now.getTime()) {
          callback(new Error('开始时间不能大于当前时间'))
        } else {
          callback()
        }
      }
    }
    var validateEndtime = (rule, value, callback) => {
      if (this.form.todate == false) {
        if (value) {
          const now = new Date()
          const start = new Date(this.form.starttime)
          const end = new Date(value)
          if (end.getTime() > now.getTime()) {
            callback(new Error('结束时间不能大于当前时间'))
          } else if (start.getTime() > end.getTime()) {
            callback(new Error('开始时间不能大于结束时间'))
          } else {
            callback()
          }
        } else {
          callback(new Error('请选择任职结束时间'))
        }
      } else {
        callback()
      }
    }
    return {
      submitLoading: false,
      infoLoading: false,
      form: {
        id: 0,
        rid: 0,
        companyname: '',
        jobname: '',
        duty: '',
        starttime: '',
        endtime: '',
        todate: false
      },
      rules: {
        companyname: [
          {
            required: true,
            message: '请输入公司名称',
            trigger: 'blur'
          }
        ],
        jobname: [
          {
            required: true,
            message: '请输入职位名称',
            trigger: 'blur'
          }
        ],
        duty: [
          {
            required: true,
            message: '请输入工作职责',
            trigger: 'blur'
          }
        ],
        starttime: [
          {
            required: true,
            message: '请选择任职开始时间',
            trigger: 'change'
          },
          { validator: validateStarttime, trigger: 'change' }
        ],
        endtime: [{ validator: validateEndtime, trigger: 'change' }]
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
      resumeWorkAddAndEdit(param, 'get')
        .then(response => {
          if (response.data.info != null) {
            this.form = { ...response.data.info }
            this.form.todate = this.form.todate == 1
          } else {
            this.form.rid = this.rid
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
          insertData.todate = insertData.todate === true ? 1 : 0
          resumeWorkAddAndEdit(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {
              this.submitLoading = false
            })
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
.el-form.common-form .el-input,
.el-textarea {
  width: 300px;
}
.el-checkbox {
  margin-left: 10px;
}
</style>
