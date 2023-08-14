<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>按筛选条件群发</span>
      </div>
      <el-form class="common-form" label-width="100px">
        <el-form-item label="筛选对象" style="margin-bottom:20px;">
          <el-radio-group v-model="data.target">
            <el-radio label="all">
              不限
            </el-radio>
            <el-radio label="resume">
              按个人简历
            </el-radio>
            <el-radio label="company">
              按企业信息
            </el-radio>
            <el-radio label="job">
              按企业职位
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <allMode v-if="data.target == 'all'" ref="conditionData" />
        <resumeMode
          v-if="data.target == 'resume'"
          ref="conditionData"
        />
        <companyMode
          v-if="data.target == 'company'"
          ref="conditionData"
        />
        <jobMode v-if="data.target == 'job'" ref="conditionData" />
        <el-divider content-position="left">发送设置</el-divider>
        <sendConfig ref="sendConfig" />
        <el-form-item label="">
          <el-button
            type="primary"
            :loading="submiting"
            @click="onSubmit('form')"
          >
            生成任务
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import allMode from './components/all.vue'
import resumeMode from './components/resume.vue'
import companyMode from './components/company.vue'
import jobMode from './components/job.vue'
import sendConfig from './components/send_config.vue'
import { marketTaskAdd } from '@/api/market'

export default {
  components: {
    allMode,
    resumeMode,
    companyMode,
    jobMode,
    sendConfig
  },
  data() {
    return {
      submiting: false,
      data: {
        target: 'all',
        title: '',
        content: '',
        send_type: [],
        condition: null
      }
    }
  },
  created() {},
  methods: {
    onSubmit(formName) {
      const that = this
      that.$refs.sendConfig.$refs['form'].validate(valid => {
        if (valid) {
          if (that.submiting === true) {
            return false
          }
          that.submiting = true
          const { title, content, send_type } = {
            ...that.$refs.sendConfig.form
          }
          that.data.title = title
          that.data.content = content
          that.data.send_type = send_type
          that.data.condition = { ...that.$refs.conditionData.form }
          const insertData = { ...that.data }
          marketTaskAdd(insertData)
            .then(response => {
              that.submiting = false
              that.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/tool/market/list')
              }, 1500)
              return true
            })
            .catch(() => {
              that.submiting = false
            })
        } else {
          return false
        }
      })
    }
  }
}
</script>
