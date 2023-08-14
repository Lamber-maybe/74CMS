<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>信息导出 - 文档资料</span>
      </div>
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="120px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="选择网络招聘会" prop="jobfair_id">
          <el-select v-model="form.jobfair_id" placeholder="选择网络招聘会">
            <el-option
              v-for="(item, index) in jobfairList"
              :key="index"
              :label="item.title"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="企业项目">
          <el-checkbox-group v-model="form.company">
            <el-checkbox :label="'company'" disabled>企业名称</el-checkbox>
            <el-checkbox :label="'contact'" disabled>联系人</el-checkbox>
            <el-checkbox :label="'mobile'" disabled>联系方式</el-checkbox>
          </el-checkbox-group>
        </el-form-item>
        <el-form-item label="职位项目">
          <el-checkbox-group v-model="form.jobs">
            <el-checkbox :label="'jobname'" disabled>职位名称</el-checkbox>
            <el-checkbox :label="'wage'" disabled>待遇</el-checkbox>
            <el-checkbox :label="'amount'" disabled>招聘人数</el-checkbox>
            <el-checkbox :label="'district'">工作地区</el-checkbox>
            <el-checkbox :label="'education'">学历要求</el-checkbox>
            <el-checkbox :label="'experience'">经验要求</el-checkbox>
            <el-checkbox :label="'age'">年龄要求</el-checkbox>
            <el-checkbox :label="'content'">职位描述</el-checkbox>
          </el-checkbox-group>
        </el-form-item>
        <el-form-item label="二维码">
          <el-radio-group v-model="form.qrcode">
            <el-radio :label="'mobile'">触屏版二维码</el-radio>
            <el-radio :label="'wx'">公众号二维码</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        </el-form-item>
      </el-form>
      <iframe id="exportResumeIframe" style="display:none;" />
    </el-card>
  </div>
</template>

<script>
import { getToken } from '@/utils/auth'
import { jobfairOlListAll, isExhibitors } from '@/api/jobfairol'

export default {
  data() {
    return {
      form: {
        jobfair_id: '',
        company: ['company', 'contact', 'mobile'],
        jobs: ['jobname', 'wage', 'district', 'amount', 'education', 'experience', 'age', 'content'],
        qrcode: 'mobile'
      },
      jobfairList: [],
      rules: {
        jobfair_id: [
          { required: true, message: '请选择招聘会', trigger: 'blur' },
          { type: 'number', message: '请正确选择招聘会', trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    this.jobfairOlListAll()
  },
  methods: {
    jobfairOlListAll: function(){
      var t = this
      jobfairOlListAll().then(res => {
        t.jobfairList = res.data.items
      })
    },
    onSubmit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          const param = {
            jobfair_id: this.form.jobfair_id
          }
          isExhibitors(param).then(res => {
            if (res.code == 200){
              document.getElementById('exportResumeIframe').src = res.data + '?jobfair_id=' + this.form.jobfair_id + '&qrcode=' + this.form.qrcode + '&admintoken=' + getToken() + '&company=' + this.form.company.join(',') + '&jobs=' + this.form.jobs.join(',')
            }
          })
        } else {
          return false
        }
      })
    }
  }
}
</script>
