<template>
  <div class="app-container">
    <div class="spaceline" />
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="200px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="允许公司名称重复" required>
        <el-switch v-model="form.company_repeat" />
      </el-form-item>
      <el-form-item label="允许企业充值积分" required>
        <el-switch v-model="form.enable_com_buy_points" />
      </el-form-item>
      <el-form-item label="申请职位间隔时间" prop="apply_jobs_space">
        <el-input v-model="form.apply_jobs_space">
          <template slot="append">天</template>
        </el-input>
        <span class="smalltip">
          <i class="el-icon-info" />
          申请同一职位的间隔天数，0表示不允许重复申请
        </span>
      </el-form-item>
      <el-form-item label="刷新职位时间间隔" prop="refresh_jobs_space">
        <el-input v-model="form.refresh_jobs_space">
          <template slot="append">分钟</template>
        </el-input>
        <span class="smalltip">
          <i class="el-icon-info" />
          刷新同一职位的间隔分钟数，0表示不限制
        </span>
      </el-form-item>
      <el-form-item label="职位列表最大显示条数" prop="job_list_max">
        <el-input v-model.number="form.job_list_max" min="0" max="1000" />
        <span class="smalltip">
          <i class="el-icon-info" />
          职位列表可展示的职位数量上限，0表示不限制
        </span>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      form: {
        company_repeat: false,
        apply_jobs_space: 0,
        refresh_jobs_space: 0,
        job_list_max: 0,
        enable_com_buy_points: false
      },
      rules: {
        apply_jobs_space: [
          {
            required: true,
            message: '请输入申请职位间隔时间',
            trigger: 'blur'
          }
        ],
        refresh_jobs_space: [
          {
            required: true,
            message: '请输入刷新职位时间间隔',
            trigger: 'blur'
          }
        ],
        job_list_max: [
          {
            required: true,
            message: '请输入职位列表最大显示条数',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            company_repeat,
            apply_jobs_space,
            refresh_jobs_space,
            job_list_max,
            enable_com_buy_points
          } = { ...response.data }
          this.form = {
            company_repeat: company_repeat == 1,
            apply_jobs_space,
            refresh_jobs_space,
            job_list_max,
            enable_com_buy_points: enable_com_buy_points == 1
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.company_repeat = insertData.company_repeat === true ? 1 : 0
          insertData.enable_com_buy_points =
            insertData.enable_com_buy_points === true ? 1 : 0
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    }
  }
}
</script>
<style scoped>
.el-input,
.el-input-group {
  width: 50%;
}
</style>
