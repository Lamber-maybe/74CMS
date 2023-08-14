<template>
  <div class="app-container">
    <el-card class="box-card">
      <el-form v-if="chooseDone===false" class="common-form" label-width="100px">
        <el-form-item label="信息类型" style="margin-bottom:20px;">
          <el-radio-group v-model="form.type">
            <el-radio :label="'joblist'">筛选职位</el-radio>
            <el-radio :label="'tojob'">指定职位</el-radio>
            <el-radio :label="'tocompany'">指定企业</el-radio>
          </el-radio-group>
        </el-form-item>
        <components :is="form.type" ref="conditionData" />
        <el-form-item label="">
          <el-button
            type="primary"
            :loading="submiting"
            @click="onSubmit('form')"
          >
            确定
          </el-button>
        </el-form-item>
      </el-form>
      <showform v-if="chooseDone" :datalist="datalist" @goback="chooseDone=false" />
    </el-card>
  </div>
</template>

<script>
import joblist from './components/joblist.vue'
import tojob from './components/tojob.vue'
import tocompany from './components/tocompany.vue'
import showform from './components/showform.vue'
import { tweetsJoblist } from '@/api/tweets_template'

export default {
  components: {
    joblist,
    tojob,
    tocompany,
    showform
  },
  data() {
    return {
      chooseDone: false,
      options_platform: [],
      submiting: false,
      form: {
        type: 'joblist',
        condition: null
      },
      datalist: [],
      options_temtype: []
    }
  },
  created() {
  },
  methods: {
    onSubmit(formName) {
      const that = this
      if (that.form.type == 'joblist'){
        that.chooseDone = true
        if (that.submiting === true) {
          return false
        }
        that.submiting = true
        that.form.condition = { ...that.$refs.conditionData.form }
      }
      if (that.form.type == 'tojob'){
        if (that.$refs.conditionData.submitTag[0] === undefined || that.$refs.conditionData.submitTag[0] === '' || !that.$refs.conditionData.submitTag[0]){
          that.$message.error('请选择职位')
          that.submiting = false
          return false
        } else {
          that.chooseDone = true
          if (that.submiting === true) {
            return false
          }
          that.submiting = true
          that.form.condition = { ...that.$refs.conditionData.submitTag }
        }
      }
      if (that.form.type == 'tocompany'){
        if (that.$refs.conditionData.submitTag[0] === undefined || that.$refs.conditionData.submitTag[0] === '' || !that.$refs.conditionData.submitTag[0]){
          that.$message.error('请选择企业')
          that.submiting = false
          return false
        } else {
          that.chooseDone = true
          if (that.submiting === true) {
            return false
          }
          that.submiting = true
          that.form.condition = { ...that.$refs.conditionData.submitTag }
        }
      }
      const insertData = { ...that.form }
      tweetsJoblist(insertData)
        .then(response => {
          that.submiting = false
          that.datalist = response.data.items
          return true
        })
        .catch(() => {
          that.submiting = false
        })
    }
  }
}
</script>

<style scoped>
.el-select,
.el-input,
.el-date-editor {
  width: 450px;
}
</style>
