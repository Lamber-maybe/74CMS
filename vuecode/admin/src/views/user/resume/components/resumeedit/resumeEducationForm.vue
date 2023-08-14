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
      <el-form-item label="学校名称" prop="school">
        <el-input v-model="form.school" />
      </el-form-item>
      <el-form-item
        v-if="live_fields.major === true"
        label="专业名称"
        prop="major"
      >
        <el-input v-model="form.major" />
      </el-form-item>
      <el-form-item label="取得学历" prop="education">
        <el-select v-model="form.education" placeholder="请选择">
          <el-option
            v-for="(item, index) in options_education"
            :key="index"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="就读开始时间" prop="starttime">
        <el-date-picker
          v-model="form.starttime"
          type="month"
          placeholder="选择就读开始时间"
          value-format="yyyy-MM"
        />
      </el-form-item>
      <el-form-item label="就读结束时间" prop="endtime" class="is-required">
        <el-date-picker
          v-model="form.endtime"
          :disabled="form.todate !== false"
          type="month"
          placeholder="选择就读结束时间"
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
import { getClassify } from '@/api/classify'
import { getFieldRule } from '@/api/configuration'
import { resumeEducationAddAndEdit } from '@/api/resume'
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
          callback(new Error('请选择就读结束时间'))
        }
      } else {
        callback()
      }
    }
    return {
      submitLoading: false,
      infoLoading: false,
      live_fields: {
        major: true
      },
      options_education: [],
      form: {
        id: 0,
        rid: 0,
        school: '',
        major: '',
        education: '',
        starttime: '',
        endtime: '',
        todate: false
      },
      rules: {
        school: [
          {
            required: true,
            message: '请输入学校名称',
            trigger: 'blur'
          }
        ],
        major: [
          {
            required: true,
            message: '请输入专业',
            trigger: 'blur'
          }
        ],
        education: [
          {
            required: true,
            message: '请选择学历',
            trigger: 'change'
          }
        ],
        starttime: [
          {
            required: true,
            message: '请选择就读开始时间',
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
      getFieldRule({}, 'get')
        .then(response => {
          const extra_rule = response.data.ResumeEducation
          if (extra_rule.major.is_display == 0) {
            this.live_fields.major = false
            this.rules.major = []
          } else if (extra_rule.major.is_require == 0) {
            this.rules.major = []
          }
          return getClassify({ type: 'education' })
        })
        .then(response => {
          this.options_education = [...response.data]

          const param = {
            id: this.itemId
          }

          return resumeEducationAddAndEdit(param, 'get')
        })
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
          resumeEducationAddAndEdit(insertData)
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
.el-form.common-form .el-input {
  width: 300px;
}
.el-select {
  width: 300px;
}
.el-checkbox {
  margin-left: 10px;
}
</style>
