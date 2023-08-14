<template>
  <div class="app-container">
    <div class="tip">
      <p>
        本服务基于智兔引擎`易览`应用，该服务为收费服务，如需开通业务请联系您的专属客服。
      </p>
      <p>
        支持 doc、docx、xls、xlsx、ppt、pptx、pdf、dwg、zip、rar、txt、js、html、mp3、mp4等常用格式的文件实时线上预览。
      </p>
      <p>
        本场景内应用于简历附件查看。
      </p>
      <p v-if="is_check==0">
        您当前尚未开通服务，请联系官方客服开通。
      </p>
      <p v-if="is_check==1">
        服务有效期至：{{ deadline | timeFilter }}
      </p>
    </div>
    <el-form ref="form" v-loading="infoLoading" :rules="rules" class="common-form" :model="form" label-width="120px">
      <el-form-item label="AppKey" prop="appKey">
        <el-input v-model="form.appKey" class="middle" @change="inputChange" />
      </el-form-item>
      <el-form-item label="AppSecret" prop="appSecret">
        <el-input v-model="form.appSecret" class="middle" @change="inputChange" />
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {
  documentConfig, documentCheck
} from '@/api/configuration'
import { parseTime } from '@/utils'

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
      is_check: 0,
      deadline: 0
    }
  },
  mounted() {},
  created() {
    this.fetchInfo()
    this.getDocumentCheck()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      documentConfig(param, 'get')
        .then(response => {
          const {
            appKey,
            appSecret
          } = {
            ...response.data
          }
          this.form = {
            appKey: appKey,
            appSecret: appSecret
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = {
        ...this.form
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          documentConfig(insertData, 'post')
            .then(response => {
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    getDocumentCheck() {
      documentCheck().then(res => {
        this.deadline = res.data.deadline
        this.is_check = res.data.is_check
      })
    },
    inputChange() {
      if (this.form.appKey === '' && this.form.appSecret === '') {
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
      } else {
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
      }
    }
  }
}
</script>
