<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="32%"
      :inline-message="false"
      size="small"
      :inline="false"
    >
      <el-row>
        <el-col :span="11">
          <el-form-item label="到期时间" prop="deadline">
            <el-date-picker
              v-model="form.deadline"
              placeholder="请选择"
              value-format="yyyy-MM-dd"
            />
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="使用视频面试">
            <el-radio-group v-model="form.enable_video_interview">
              <el-radio :label="1">允许</el-radio>
              <el-radio :label="0">不允许</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="服务天数" prop="days">
            <el-input-number v-model="form.days" size="small" />
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="收到简历免费查看">
            <el-radio-group
              v-model="form.show_apply_contact"
              @change="fun_change_show_apply_contact"
            >
              <el-radio :label="1">允许</el-radio>
              <el-radio :label="0">不允许</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="同时在招职位数" prop="jobs_meanwhile">
            <el-input
              v-model.number="form.jobs_meanwhile"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'jobs_meanwhile')"
            />
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="收到简历查看上限" prop="resume_view_num">
            <el-input
              v-model.number="form.resume_view_num"
              type="number"
              class="small"
              min="0"
              :disabled="form.show_apply_contact == 0"
              @blur="format_number(0, 'resume_view_num')"
            >
              <template slot="append">份 / 天</template>
            </el-input>
            <el-tooltip class="item" effect="dark" placement="top-start">
              <div slot="content">0表示不限制</div>
              <i class="el-icon-info" />
            </el-tooltip>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="下载简历点数" prop="download_resume_point">
            <el-input
              v-model.number="form.download_resume_point"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'download_resume_point')"
            />
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
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
            <el-tooltip class="item" effect="dark" placement="top-start">
              <div slot="content">0表示不允许</div>
              <i class="el-icon-info" />
            </el-tooltip>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="职聊次数" prop="im_total">
            <el-input
              v-model.number="form.im_total"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'im_total')"
            />
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="是否收费">
            <el-switch v-model="form.is_charge" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label="允许发起聊天数" prop="im_max_perday">
            <el-input
              v-model.number="form.im_max_perday"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'im_max_perday')"
            >
              <template slot="append">次 / 天</template>
            </el-input>
            <el-tooltip class="item" effect="dark" placement="top-start">
              <div slot="content">0表示不允许</div>
              <i class="el-icon-info" />
            </el-tooltip>
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="收费金额" prop="charge_val">
            <el-input
              v-model.number="form.charge_val"
              type="number"
              class="small"
              min="0"
              @blur="format_number(0, 'charge_val')"
            >
              <template slot="append">元</template>
            </el-input>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
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
            <el-tooltip class="item" effect="dark" placement="top-start">
              <div slot="content">0表示不允许</div>
              <i class="el-icon-info" />
            </el-tooltip>
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="操作说明" prop="explain">
            <el-input v-model="form.explain" type="textarea" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11">
          <el-form-item label=" ">
            <el-button type="primary" @click="onSubmit('form')">保存</el-button>
            <el-button @click="closeDialog">取 消</el-button>
          </el-form-item>
        </el-col>
      </el-row>
      <hr class="dashed_hr">
      <el-row>
        <el-col :span="11">
          <el-form-item label="简历包下载点数" prop="purchase_resume_point">
            <el-input
              v-model.number="form.purchase_resume_point"
              class="small"
              :disabled="true"
              @blur="format_number(0, 'purchase_resume_point')"
            />
          </el-form-item>
        </el-col>
        <el-col :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-form-item label="职聊增值包次数" prop="im_added_package">
            <el-input
              v-model.number="form.im_added_package"
              class="small"
              :disabled="true"
              @blur="format_number(0, 'im_added_package')"
            />
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>

<script>
import { companySetmealEdit } from '@/api/company_setmeal'

export default {
  props: ['uid'],
  data() {
    return {
      infoLoading: false,
      form: {
        deadline: '',
        days: '',
        jobs_meanwhile: 0,
        refresh_jobs_free_perday: 0,
        download_resume_point: 0,
        purchase_resume_point: 0,
        download_resume_max_perday: 0,
        enable_video_interview: 1,
        show_apply_contact: 1,
        is_charge: true,
        charge_val: '',
        explain: '',
        im_total: '',
        im_max_perday: '',
        resume_view_num: 0
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      const uid = this.uid
      if (uid > 0) {
        const param = { uid }
        companySetmealEdit(param, 'get')
          .then(response => {
            Object.assign(this.form, { ...response.data })
            this.infoLoading = false
          })
          .catch(() => {})
      } else {
        this.infoLoading = false
      }
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...that.form }
      insertData.uid = this.uid
      this.$refs[formName].validate(valid => {
        if (valid) {
          companySetmealEdit(insertData)
            .then(response => {
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
    },
    fun_change_show_apply_contact(val) {
      if (val === 0) {
        this.form.resume_view_num = 0
      }
    }
  }
}
</script>
<style scoped>
.el-textarea,
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
<!--增加简历包下载点数 zch-->
<style scoped lang="scss">
.dashed_hr {
  border: #a8a2a2 1px dashed;
  margin-top: 13px;
  margin-bottom: 30px;
}
.el-tooltip {
  margin-left: 4px;
}
</style>
