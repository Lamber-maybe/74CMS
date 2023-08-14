<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>信息导出 - 公众号模板</span>
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
        <el-form-item label="">
          <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { jobfairOlListAll } from '@/api/jobfairol'

export default {
  data() {
    return {
      form: {
        jobfair_id: ''
      },
      jobfairList: [],
      rules: {
        jobfair_id: [
          { required: true, message: '请选择网络招聘会', trigger: 'blur' },
          { type: 'number', message: '请正确选择网络招聘会', trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    this.getJobfair()
  },
  methods: {
    getJobfair: function(){
      var t = this
      jobfairOlListAll().then(res => {
        t.jobfairList = res.data.items
      })
    },
    onSubmit(formName) {
      var insertData = Object.assign({}, this.form)
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.$router.push({
            path: '/jobfairol/export/weixin_preview',
            query: {
              id: insertData.jobfair_id
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
