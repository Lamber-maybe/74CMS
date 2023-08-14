<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="32%"
      :rules="rules"
      :inline-message="false"
      size="small"
      :inline="false"
    >
      <el-form-item label="同时在招职位数" prop="jobs_meanwhile">
        <el-input
          v-model.number="form.jobs_meanwhile"
          type="number"
          class="small"
          min="0"
          @blur="format_number(0, 'jobs_meanwhile')"
        />
      </el-form-item>
      <el-form-item label="免费刷新职位" prop="refresh_jobs_free_perday">
        <el-input
          v-model.number="form.refresh_jobs_free_perday"
          type="number"
          class="small"
          min="0"
          @blur="format_number(0, 'refresh_jobs_free_perday')"
        >
          <template slot="append">次 / 天</template>
        </el-input>
      </el-form-item>
      <el-form-item label="下载简历上限" prop="download_resume_max_perday">
        <el-input
          v-model.number="form.download_resume_max_perday"
          type="number"
          class="small"
          min="0"
          @blur="format_number(0, 'download_resume_max_perday')"
        >
          <template slot="append">份 / 天</template>
        </el-input>
      </el-form-item>
      <el-form-item label="套餐增值包折扣" prop="service_added_discount">
        <el-input
          v-model.number="form.service_added_discount"
          type="number"
          class="small"
          min="0"
          @blur="format_number(0, 'service_added_discount')"
        />
        <el-tooltip class="item" effect="dark" placement="top-start">
          <div slot="content">
            0表示无折扣
            <br>
            例：七五折请填写7.5
          </div>
          <i class="el-icon-info" />
        </el-tooltip>
      </el-form-item>
      <el-form-item label="使用视频面试">
        <el-radio-group v-model="form.enable_video_interview">
          <el-radio :label="1">允许</el-radio>
          <el-radio :label="0">不允许</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="使用微海报">
        <el-radio-group v-model="form.enable_poster">
          <el-radio :label="1">允许</el-radio>
          <el-radio :label="0">不允许</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="收到简历免费查看">
        <el-radio-group v-model="form.show_apply_contact">
          <el-radio :label="1">允许</el-radio>
          <el-radio :label="0">不允许</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label=" ">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: false,
      form: {
        service_added_discount: 0,
        jobs_meanwhile: 0,
        refresh_jobs_free_perday: 0,
        download_resume_max_perday: 0,
        enable_video_interview: 1,
        enable_poster: 1,
        show_apply_contact: 1
      },
      rules: {
        jobs_meanwhile: [
          { required: true, message: '请填写同时在招职位数', trigger: 'blur' },
          {
            type: 'number',
            message: '同时在招职位数只能填写数字',
            trigger: 'blur'
          }
        ],
        download_resume_max_perday: [
          {
            required: true,
            message: '请填写下载简历数上限',
            trigger: 'blur'
          },
          {
            type: 'number',
            message: '下载简历数上限只能填写数字',
            trigger: 'blur'
          }
        ],
        refresh_jobs_free_perday: [
          {
            required: true,
            message: '请填写免费刷新职位次数',
            trigger: 'blur'
          },
          {
            type: 'number',
            message: '免费刷新职位次数只能填写数字',
            trigger: 'blur'
          }
        ],
        service_added_discount: [
          {
            required: true,
            message: '请填写套餐增值包折扣',
            trigger: 'blur'
          },
          {
            type: 'number',
            message: '套餐增值包折扣只能填写数字',
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
      setConfig({}, 'get')
        .then(response => {
          const {
            service_added_discount,
            jobs_meanwhile,
            refresh_jobs_free_perday,
            download_resume_max_perday,
            enable_video_interview,
            enable_poster,
            show_apply_contact
          } = { ...response.data.setmeal_overtime_conf }
          this.form = {
            service_added_discount: parseInt(service_added_discount),
            jobs_meanwhile: parseInt(jobs_meanwhile),
            refresh_jobs_free_perday: parseInt(refresh_jobs_free_perday),
            download_resume_max_perday: parseInt(download_resume_max_perday),
            enable_video_interview: parseInt(enable_video_interview),
            enable_poster: parseInt(enable_poster),
            show_apply_contact: parseInt(show_apply_contact)
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = {
        setmeal_overtime_conf: { ...this.form }
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    },
    format_number(default_val, field) {
      if (this.form[field] == '' || parseInt(this.form[field]) < default_val) {
        this.form[field] = default_val
      }
    }
  }
}
</script>
<style scoped>
.el-date-editor,
.el-input,
.el-input-group {
  width: 90%;
}
.el-form-item--small .el-form-item__content,
.el-form-item--small .el-form-item__label {
  width: 100%;
}
</style>
