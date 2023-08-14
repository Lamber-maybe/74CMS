<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="120px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="默认服务商">
        <el-radio-group v-model="form.sendsms_type">
          <el-radio label="qscms">骑士官方</el-radio>
          <el-radio label="alisms">阿里云通信</el-radio>
        </el-radio-group>
      </el-form-item>
      <div class="spaceline" />
      <el-form-item
        v-if="form.sendsms_type == 'alisms'"
        label="签名"
        prop="signature"
      >
        <el-input v-model="form.alisms.signature" class="middle" />
      </el-form-item>
      <el-form-item
        v-if="form.sendsms_type == 'alisms'"
        label="accesskeyid"
        prop="accesskeyid"
      >
        <el-input v-model="form.alisms.accesskey_id" class="middle" />
      </el-form-item>
      <el-form-item
        v-if="form.sendsms_type == 'alisms'"
        label="accesskeysecret"
        prop="accesskeysecret"
      >
        <el-input v-model="form.alisms.accesskey_secret" class="middle" />
      </el-form-item>
      <el-form-item
        v-if="form.sendsms_type == 'qscms'"
        label="用户名"
        prop="appkey"
      >
        <el-input v-model="form.qscms.app_key" class="middle" />
      </el-form-item>
      <el-form-item
        v-if="form.sendsms_type == 'qscms'"
        label="密钥"
        prop="secretkey"
      >
        <el-input v-model="form.qscms.secret_key" class="middle" />
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="onTestDialog()">测试</el-button>
      </el-form-item>
    </el-form>
    <el-dialog
      v-if="dialogFormVisible"
      title="发送测试短信"
      :visible.sync="dialogFormVisible"
      width="35%"
      :close-on-click-modal="false"
      @close="closeDialog"
    >
      <el-form
        ref="testform"
        :model="testform"
        class="common-form"
        label-width="150px"
        :rules="testrules"
        :inline-message="true"
      >
        <el-form-item label="接收手机号" prop="mobile">
          <el-input v-model="testform.mobile" style="width:300px" />
        </el-form-item>
        <el-form-item label=" ">
          <el-button
            type="primary"
            :loading="testLoading"
            @click="onTest('testform')"
          >
            发送测试短信
          </el-button>
          <el-button @click="closeDialog">取 消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import { setConfig, sendSmsTest } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      testLoading: false,
      dialogFormVisible: false,
      testform: {
        mobile: ''
      },
      form: {
        sendsms_type: 'qscms',
        alisms: {
          signature: '',
          accesskey_id: '',
          accesskey_secret: ''
        },
        qscms: {
          app_key: '',
          secret_key: ''
        }
      },
      rules: {},
      testrules: {
        mobile: [
          {
            required: true,
            message: '请输入接收手机号',
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
          const { sendsms_type, account_alisms, account_sms } = response.data
          this.form.sendsms_type = sendsms_type
          this.form.alisms = account_alisms
          this.form.qscms = account_sms
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = {
        sendsms_type: that.form.sendsms_type
      }
      if (insertData.sendsms_type == 'qscms') {
        insertData.account_sms = that.form.qscms
      } else {
        insertData.account_alisms = that.form.alisms
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
              this.$store.dispatch('config/getConfigInfo')
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    onTestDialog() {
      this.testLoading = false
      this.dialogFormVisible = true
    },
    closeDialog() {
      this.testLoading = false
      this.dialogFormVisible = false
    },
    onTest(formName) {
      const that = this
      const testData = {
        mobile: that.testform.mobile,
        type: that.form.sendsms_type,
        account: {
          ...that.form[that.form.sendsms_type]
        }
      }
      that.$refs[formName].validate(valid => {
        if (valid) {
          that.testLoading = true
          sendSmsTest(testData)
            .then(response => {
              that.dialogFormVisible = false
              that.$message.success(response.message)
              return true
            })
            .catch(response => {
              that.testLoading = false
            })
        } else {
          // console.log('error submit!!')
          return false
        }
      })
    }
  }
}
</script>
