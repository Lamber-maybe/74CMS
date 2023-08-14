<template>
  <div class="app-container">
    <div v-if="setting_secrecy === true" class="no-promise">
      <div class="notice-div">
        <p class="notice-p">暂无查看权限，请联系网站负责人</p>
      </div>
    </div>
    <el-form
      v-else
      ref="form"
      :v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="120px"
      :rules="rules"
      :inline-message="true"
    >
      <div class="spaceline" />
      <el-form-item label="默认发送方式">
        <el-radio-group v-model="form.sendmail_type">
          <el-radio label="smtp">SMTP</el-radio>
          <el-radio label="aliyundm">阿里云邮件推送</el-radio>
          <el-radio label="sendcloud">SendCloud</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item v-if="form.sendmail_type == 'smtp'" label="SMTP服务器">
        <el-input v-model="form.smtp.host" class="middle" />
      </el-form-item>
      <el-form-item v-if="form.sendmail_type == 'smtp'" label="SMTP端口">
        <el-input v-model="form.smtp.port" class="middle" />
      </el-form-item>
      <el-form-item v-if="form.sendmail_type == 'smtp'" label="SMTP用户名">
        <el-input v-model="form.smtp.username" class="middle" />
      </el-form-item>
      <el-form-item v-if="form.sendmail_type == 'smtp'" label="SMTP密码">
        <el-input v-model="form.smtp.password" class="middle" />
      </el-form-item>
      <el-form-item v-if="form.sendmail_type == 'smtp'" label="发送方地址">
        <el-input v-model="form.smtp.sender_address" class="middle" />
      </el-form-item>
      <el-form-item v-if="form.sendmail_type == 'aliyundm'" label="AccessKey">
        <el-input v-model="form.aliyundm.access_key" class="middle" />
      </el-form-item>
      <el-form-item
        v-if="form.sendmail_type == 'aliyundm'"
        label="AccessSecret"
      >
        <el-input v-model="form.aliyundm.access_secret" class="middle" />
      </el-form-item>
      <el-form-item v-if="form.sendmail_type == 'aliyundm'" label="发送方地址">
        <el-input v-model="form.aliyundm.sender_address" class="middle" />
      </el-form-item>
      <el-form-item v-if="form.sendmail_type == 'sendcloud'" label="用户名">
        <el-input v-model="form.sendcloud.api_user" class="middle" />
      </el-form-item>
      <el-form-item v-if="form.sendmail_type == 'sendcloud'" label="Key">
        <el-input v-model="form.sendcloud.api_key" class="middle" />
      </el-form-item>
      <el-form-item v-if="form.sendmail_type == 'sendcloud'" label="发送方地址">
        <el-input v-model="form.sendcloud.sender_address" class="middle" />
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="onTestDialog()">测试</el-button>
      </el-form-item>
    </el-form>
    <el-dialog
      v-if="dialogFormVisible"
      title="发送测试邮件"
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
        <el-form-item label="接收邮箱" prop="email">
          <el-input v-model="testform.email" style="width:300px" />
        </el-form-item>
        <el-form-item label=" ">
          <el-button
            type="primary"
            :loading="testLoading"
            @click="onTest('testform')"
          >
            发送测试邮件
          </el-button>
          <el-button @click="closeDialog">取 消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import { setConfig, sendMailTest } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      testLoading: false,
      dialogFormVisible: false,
      testform: {
        email: ''
      },
      form: {
        sendmail_type: 'smtp',
        smtp: {
          host: '',
          port: '',
          username: '',
          password: '',
          sender_address: ''
        },
        sendcloud: {
          api_user: '',
          api_key: '',
          sender_address: ''
        },
        aliyundm: {
          access_key: '',
          access_secret: '',
          sender_address: ''
        }
      },
      rules: {},
      testrules: {
        email: [
          {
            required: true,
            message: '请输入接收邮箱',
            trigger: 'blur'
          }
        ]
      },
      setting_secrecy: false
    }
  },
  mounted() {},
  created() {
    this.setting_secrecy = window.global.SettingSecrecy ? window.global.SettingSecrecy : false
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            sendmail_type,
            account_smtp,
            account_sendcloud,
            account_aliyundm
          } = response.data
          this.form.sendmail_type = sendmail_type
          this.form.smtp = account_smtp
          this.form.sendcloud = account_sendcloud
          this.form.aliyundm = account_aliyundm
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = {
        sendmail_type: that.form.sendmail_type,
        ['account_' + that.form.sendmail_type]: {
          ...that.form[that.form.sendmail_type]
        }
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
        email: that.testform.email,
        type: that.form.sendmail_type,
        account: {
          ...that.form[that.form.sendmail_type]
        }
      }
      that.$refs[formName].validate(valid => {
        if (valid) {
          that.testLoading = true
          sendMailTest(testData)
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
