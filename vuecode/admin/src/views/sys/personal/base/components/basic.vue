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
      <el-form-item label="每天允许申请职位" prop="apply_jobs_max_perday">
        <el-input v-model.number="form.apply_jobs_max_perday">
          <template slot="append">个</template>
        </el-input>
      </el-form-item>
      <el-form-item label="申请职位要求简历完整度" prop="apply_job_min_percent">
        <el-input v-model.number="form.apply_job_min_percent">
          <template slot="append">%</template>
        </el-input>
        <span class="smalltip">
          <i class="el-icon-info" />
          0表示不限制
        </span>
      </el-form-item>
      <el-form-item label="简历列表最大显示条数" prop="resume_list_max">
        <el-input v-model.number="form.resume_list_max" min="0" max="1000" />
        <span class="smalltip">
          <i class="el-icon-info" />
          简历列表可展示的职位数量上限，0表示不限制
        </span>
      </el-form-item>
      <el-form-item label="刷新简历时间间隔" prop="refresh_resume_space">
        <el-input v-model.number="form.refresh_resume_space">
          <template slot="append">分钟</template>
        </el-input>
        <span class="smalltip">
          <i class="el-icon-info" />
          刷新简历的间隔分钟数，0表示不限制
        </span>
      </el-form-item>
      <el-form-item
        label="每天允许刷新简历次数"
        prop="refresh_resume_max_perday"
      >
        <el-input v-model.number="form.refresh_resume_max_perday">
          <template slot="append">次</template>
        </el-input>
      </el-form-item>
      <el-form-item label="简历姓名默认显示方式" required>
        <el-radio-group v-model="form.resume_display_name">
          <el-radio label="1">公开</el-radio>
          <el-radio label="0">先生/女士</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="简历下载要求" required>
        <el-radio-group v-model="form.down_resume_limit">
          <el-radio label="1">已登录且有发布职位的企业</el-radio>
          <el-radio label="2">已认证企业</el-radio>
          <el-radio label="3">不限</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="登录自动刷新简历" required>
        <el-switch v-model="form.resume_auto_refresh" />
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
        apply_jobs_max_perday: 0,
        apply_job_min_percent: 0,
        resume_list_max: 0,
        refresh_resume_space: 0,
        refresh_resume_max_perday: 0,
        resume_display_name: 1,
        down_resume_limit: 1,
        resume_auto_refresh: false
      },
      rules: {
        apply_jobs_max_perday: [
          {
            required: true,
            message: '请输入每天允许申请职位数',
            trigger: 'blur'
          }
        ],
        apply_job_min_percent: [
          {
            required: true,
            message: '请输入申请职位要求简历完整度',
            trigger: 'blur'
          }
        ],
        resume_list_max: [
          {
            required: true,
            message: '请输入简历列表最大显示条数',
            trigger: 'blur'
          }
        ],
        refresh_resume_space: [
          {
            required: true,
            message: '请输入刷新简历时间间隔',
            trigger: 'blur'
          }
        ],
        refresh_resume_max_perday: [
          {
            required: true,
            message: '请输入每天允许刷新简历次数',
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
            apply_jobs_max_perday,
            apply_job_min_percent,
            resume_list_max,
            refresh_resume_space,
            refresh_resume_max_perday,
            resume_display_name,
            down_resume_limit,
            resume_auto_refresh
          } = { ...response.data }
          this.form = {
            apply_jobs_max_perday,
            apply_job_min_percent,
            resume_list_max,
            refresh_resume_space,
            refresh_resume_max_perday,
            resume_display_name,
            down_resume_limit,
            resume_auto_refresh: resume_auto_refresh == 1
          }
          this.infoLoading = false
        })
        .catch(() => { })
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.resume_auto_refresh = insertData.resume_auto_refresh === true ? 1 : 0
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => { })
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
