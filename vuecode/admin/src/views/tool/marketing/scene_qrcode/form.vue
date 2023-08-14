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
      <el-form-item label="场景码名称" prop="title">
        <el-input v-model="form.title" />
      </el-form-item>
      <el-form-item label="场景码类型" prop="platform">
        <el-radio-group v-model="form.platform">
          <el-radio v-for="(item,index) in options_platform" :key="index" :label="item.value">{{ item.name }}</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item v-if="form.platform==0" label="有效期至" prop="deadline" required>
        <el-date-picker
          v-model="form.deadline"
          type="date"
          placeholder="选择日期"
          format="yyyy-MM-dd"
          :picker-options="pickerOptions"
        />
      </el-form-item>
      <el-form-item label="场景码页面" prop="type">
        <el-select v-model="form.type" placeholder="请选择" @change="changeType">
          <el-option
            v-for="item in options_type"
            :key="item.alias"
            :label="item.name"
            :value="item.alias"
          />
        </el-select>
      </el-form-item>
      <el-form-item v-if="showParam" :label="paramLabel" prop="paramid" required>
        <el-select
          v-model="form.paramid"
          filterable
          remote
          reserve-keyword
          placeholder="请输入关键词"
          :remote-method="searchList"
          :loading="loading"
        >
          <el-option
            v-for="item in options_search_list"
            :key="item.id"
            :label="item.label"
            :value="item.id"
          >
            <span style="float: left">{{ item.label }}</span>
            <span v-if="item.label_small!=''" style="float: right; color: #8492a6; font-size: 13px">{{ item.label_small }}</span>
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label=" ">
        <el-button
          type="primary"
          @click="onSubmit('form')"
        >
          确定
        </el-button>
        <el-button @click="closeDialog">
          取 消
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { sceneQrcodeAdd, sceneQrcodePlatformList, sceneQrcodeTypeList, sceneSearchList } from '@/api/scene_qrcode'
export default {
  props: ['itemInfo'],
  data() {
    var validateParam = (rule, value, callback) => {
      if (this.showParam === true && value === '') {
        callback(new Error('请输入' + this.paramLabel))
      } else {
        callback()
      }
    }
    var validateDeadline = (rule, value, callback) => {
      if (this.form.platform == 0 && value === '') {
        callback(new Error('请选择有效期'))
      } else {
        callback()
      }
    }
    return {
      loading: false,
      pickerOptions: {
        disabledDate: (time) => {
          const beginDate = new Date()
          const endDate = new Date()
          endDate.setDate(beginDate.getDate() + 30)
          return time.getTime() > endDate.getTime() || time.getTime() < beginDate.getTime()
        }
      },
      paramLabel: '标题/ID',
      showParam: false,
      form: {
        title: '',
        type: '',
        deadline: '',
        platform: 0,
        paramid: ''
      },
      options_search_list: [],
      options_platform: [],
      options_type: [],
      rules: {
        title: [
          { required: true, message: '请输入场景码名称', trigger: 'blur' }
        ],
        platform: [
          { required: true, message: '请选择平台', trigger: 'change' }
        ],
        deadline: [
          { validator: validateDeadline, trigger: 'change' }
        ],
        type: [
          { required: true, message: '请选择类型', trigger: 'change' }
        ],
        paramid: [
          { validator: validateParam, trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData(){
      sceneQrcodePlatformList({})
        .then(response => {
          this.options_platform = response.data
        })
        .catch(() => {})
      sceneQrcodeTypeList({})
        .then(response => {
          this.options_type = response.data
        })
        .catch(() => {})
    },
    addSave(insertData, formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          sceneQrcodeAdd(insertData)
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
    onSubmit(formName) {
      const that = this
      const insertData = { ...that.form }
      that.addSave(insertData, formName)
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    },
    changeType(e){
      const item = this.options_type.find(obj => obj.alias == e)
      if (item.offiaccount_param_name != ''){
        this.showParam = true
      } else {
        this.showParam = false
      }
      switch (e){
        case 'job':
          this.paramLabel = '职位名称/ID'
          break
        case 'company':
          this.paramLabel = '企业名称/ID'
          break
        case 'resume':
          this.paramLabel = '姓名/ID'
          break
        case 'notice':
          this.paramLabel = '标题/ID'
          break
        case 'jobfairol':
          this.paramLabel = '标题/ID'
          break
        case 'news':
          this.paramLabel = '标题/ID'
          break
      }
      // 如果类型选择招聘会或者网络招聘会，同时平台选择了小程序，则把平台重置为默认，因为小程序目前没有招聘会和网络招聘会
      if (item.alias == 'jobfairol'){
        if (this.form.platform == 2){
          this.form.platform = 0
        }
        const index = this.options_platform.findIndex(i => i.value === 2)
        if (index >= 0){
          this.options_platform.splice(index, 1)
        }
      } else {
        this.fetchData()
      }
    },
    searchList(e){
      if (e == '') {
        this.options_search_list = []
        return false
      }
      const type = this.form.type
      const param = {
        keyword: e,
        type
      }
      sceneSearchList(param)
        .then(response => {
          this.options_search_list = response.data
        })
        .catch(() => {})
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
