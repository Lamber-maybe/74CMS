<template>
  <div class="app-container">
    <div class="tip">
      <p>
        本服务基于智兔引擎`轻阅`应用，该服务为收费服务，请联系您的专属客服开通服务。
      </p>
      <p>您可自主决定是否启用此功能，简历解析支持站外导入pdf、doc、docx、txt、html格式的文件，求职者无需繁琐输入，系统智能完成简历创建
      </p>
      <p v-if="is_check==0">
        您当前尚未开通服务，请联系官方客服开通。
      </p>
      <p v-if="is_check==1">
        当前解析剩余：{{ total }} 次
        <el-tooltip class="item" effect="dark" placement="bottom">
      <div slot="content">
        <p v-for="(item,index) in accountBalanceList" :key="index" class="tooltipItem">{{ item.num }}
          次查询余额将在 {{ item.deadline | timeFilter }} 到期</p>
      </div>
      <i class="el-icon-info" style="color: rgb(86, 82, 83);cursor: pointer;"/>
      </el-tooltip>
      </p>
    </div>
    <el-form ref="form" v-loading="infoLoading" :rules="rules" class="common-form" :model="form" label-width="120px">
      <el-form-item label="启用简历解析">
        <el-switch v-model="form.is_open" @change="switchChangeBtn(form.is_open)"/>
      </el-form-item>
      <el-form-item label="AppKey" prop="appKey">
        <el-input v-model="form.appKey" class="middle"/>
      </el-form-item>
      <el-form-item label="AppSecret" prop="appSecret">
        <el-input v-model="form.appSecret" class="middle"/>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {
  resumeConfig,
  resumeCheck
} from '@/api/configuration'
import {parseTime} from '@/utils'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}:{s}')
    }
  },
  data() {
    return {
      infoLoading: false,
      form: {
        is_open: false,
        appKey: '',
        appSecret: ''
      },
      rules: {
        appKey: [{
          required: true,
          message: '请输入AppKey',
          trigger: 'blur'
        }],
        appSecret: [{
          required: true,
          message: '请输入AppSecret',
          trigger: 'blur'
        }]
      },
      accountBalanceList: [],
      total: 0,
      is_check: 0
    }
  },
  mounted() {
  },
  created() {
    this.fetchInfo()
    this.getResumeCheck()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      resumeConfig(param, 'get')
        .then(response => {
          const {
            is_open,
            appKey,
            appSecret
          } = {
            ...response.data
          }
          this.form = {
            is_open: is_open == 1,
            appKey: appKey,
            appSecret: appSecret
          }
          if (this.form.is_open) {
            this.rules = {
              appKey: [{
                required: true,
                message: '请输入AppKey',
                trigger: 'blur'
              }],
              appSecret: [{
                required: true,
                message: '请输入AppSecret',
                trigger: 'blur'
              }]
            }
          } else {
            this.rules = {
              appKey: [{
                required: false,
                message: '请输入AppKey',
                trigger: 'blur'
              }],
              appSecret: [{
                required: false,
                message: '请输入AppSecret',
                trigger: 'blur'
              }]
            }
          }
          this.infoLoading = false
        })
        .catch(() => {
        })
    },
    onSubmit(formName) {
      const insertData = {
        ...this.form
      }
      insertData.is_open = insertData.is_open === true ? 1 : 0
      this.$refs[formName].validate(valid => {
        if (valid) {
          resumeConfig(insertData, 'post')
            .then(response => {
              this.$message.success(response.message)
              return true
            })
            .catch(() => {
            })
        } else {
          return false
        }
      })
    },
    getResumeCheck() {
      resumeCheck().then(res => {
        this.total = res.data.total
        this.accountBalanceList = res.data.list
        this.is_check = res.data.is_check
      })
    },
    switchChangeBtn(isOPen) {
      if (isOPen) {
        this.rules = {
          appKey: [{
            required: true,
            message: '请输入AppKey',
            trigger: 'blur'
          }],
          appSecret: [{
            required: true,
            message: '请输入AppSecret',
            trigger: 'blur'
          }]
        }
      } else {
        this.rules = {
          appKey: [{
            required: false,
            message: '请输入AppKey',
            trigger: 'blur'
          }],
          appSecret: [{
            required: false,
            message: '请输入AppSecret',
            trigger: 'blur'
          }]
        }
      }
    }
  }
}
</script>
