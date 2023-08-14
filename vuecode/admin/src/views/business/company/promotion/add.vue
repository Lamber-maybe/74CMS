<template>
  <div class="app-container">
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="150px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="选择企业" prop="company_id">
        <el-select
          v-model="form.company_id"
          filterable
          remote
          reserve-keyword
          placeholder="请输入企业ID/企业名称"
          :remote-method="companySearch"
          :loading="loading"
          @change="selectedCompany"
        >
          <el-option
            v-for="item in options_companylist"
            :key="item.id"
            :label="item.companyname"
            :value="item.id"
          >
            <span style="float: left">{{ item.companyname }}</span>
            <span style="float: right; color: #8492a6; font-size: 13px">
              ID:{{ item.id }}
            </span>
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="选择职位" prop="jobid">
        <el-select v-model="form.jobid" placeholder="请选择">
          <el-option
            v-for="item in options_joblist"
            :key="item.id"
            :label="item.jobname"
            :value="item.id"
          >
            <span style="float: left">{{ item.jobname }}</span>
            <span style="float: right; color: #8492a6; font-size: 13px">
              ID:{{ item.id }}
            </span>
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="推广天数" prop="days">
        <!--修改推广天数可以为负数问题 zch 2022.07.06-->
        <el-input v-model.number="form.days" type="number" :min="0"/>
      </el-form-item>
      <el-form-item label="推广方案" prop="type">
        <el-select v-model="form.type" placeholder="请选择">
          <el-option label="置顶" value="jobstick" />
          <el-option label="紧急" value="emergency" />
        </el-select>
      </el-form-item>
      <el-form-item label=" ">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {
  jobPromotionSearchCompany,
  jobPromotionSearchJob,
  jobPromotionAdd
} from '@/api/promotion_job'

export default {
  data() {
    return {
      loading: false,
      options_companylist: [],
      options_joblist: [],
      form: {
        company_id: '',
        jobid: '',
        days: '',
        type: ''
      },
      rules: {
        jobid: [
          {
            required: true,
            message: '请选择职位',
            trigger: 'change'
          }
        ],
        days: [
          {
            required: true,
            message: '请填写推广天数',
            trigger: 'blur'
          }
        ],
        type: [
          {
            required: true,
            message: '请选择推广方案',
            trigger: 'change'
          }
        ]
      }
    }
  },
  created() {},
  methods: {
    onSubmit(formName) {
      const that = this
      that.$refs[formName].validate(valid => {
        if (valid) {
          const insertData = {
            pid: that.form.jobid,
            days: that.form.days,
            type: that.form.type
          }
          jobPromotionAdd(insertData)
            .then(response => {
              that.$message.success(response.message)
              that.closeDialog()
              that.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    companySearch(query) {
      if (query !== '') {
        this.loading = true
        jobPromotionSearchCompany({ keyword: query })
          .then(response => {
            this.options_companylist = response.data.items
            this.loading = false
          })
          .catch(() => {})
      } else {
        this.options = []
      }
    },
    selectedCompany(e) {
      jobPromotionSearchJob({ company_id: e })
        .then(response => {
          this.options_joblist = response.data.items
        })
        .catch(() => {})
    },
    closeDialog() {
      this.$emit('setDialogVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    }
  }
}
</script>
<style scoped>
.el-select,
.el-input {
  width: 300px;
}
</style>
