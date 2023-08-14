<template>
  <div class="app-container">
    <div class="tip">
      <p>
        骑士人才系统已集成多种渠道安全验证，使用验证需正确配置相关渠道信息。
      </p>
    </div>
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="140px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="开启验证">
        <el-switch v-model="form.captcha_open" />
      </el-form-item>
      <el-form-item label="验证渠道">
        <el-radio-group v-model="form.captcha_type">
          <el-radio label="picture">图片验证码</el-radio>
          <el-radio label="vaptcha">手势验证</el-radio>
          <el-radio label="tencent">腾讯防水墙</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item
        v-if="form.captcha_type == 'picture'"
        label="验证码形式"
      >
        <el-radio-group v-model="form.picture.code_mode">
          <el-radio label="1">中文</el-radio>
          <el-radio label="2">数字</el-radio>
          <el-radio label="3">英文</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item
        v-if="form.captcha_type == 'picture'"
        label="是否画混淆曲线"
      >
        <el-switch v-model="form.picture.useCurve" />
      </el-form-item>
      <el-form-item v-if="form.captcha_type == 'picture'" label="是否添加杂点">
        <el-switch v-model="form.picture.useNoise" />
      </el-form-item>
      <el-form-item
        v-if="form.captcha_type == 'picture'"
        label="验证码位数"
        prop="length"
      >
        <el-input v-model="form.picture.length" />
      </el-form-item>
      <div v-if="setting_secrecy === true && form.captcha_type !== 'picture'" class="no-promise" style="margin-bottom: 22px;">
        <div class="notice-div">
          <p class="notice-p">暂无查看权限，请联系网站负责人</p>
        </div>
      </div>
      <div v-else>
        <el-form-item
          v-if="form.captcha_type == 'vaptcha'"
          label="vid"
          prop="captcha_vaptcha_vid"
        >
          <el-input v-model="form.captcha_vaptcha_vid" />
        </el-form-item>
        <el-form-item
          v-if="form.captcha_type == 'vaptcha'"
          label="key"
          prop="captcha_vaptcha_key"
        >
          <el-input v-model="form.captcha_vaptcha_key" />
        </el-form-item>
        <el-form-item
          v-if="form.captcha_type == 'tencent'"
          label="appid"
          prop="captcha_tencent_appid"
        >
          <el-input v-model="form.captcha_tencent_appid" />
        </el-form-item>
        <el-form-item
          v-if="form.captcha_type == 'tencent'"
          label="secretkey"
          prop="captcha_tencent_appsecret"
        >
          <el-input v-model="form.captcha_tencent_appsecret" />
        </el-form-item>
      </div>
      <el-form-item
        label="密码登录错误次数"
        prop="length"
      >
        <el-input v-model="form.captcha_show_by_pwd_error" />
        <span class="smalltip">
          <i class="el-icon-info" />
          填写数字，0为开启验证码，如设置为3，则错误3次才发起验证
        </span>
      </el-form-item>
      <el-form-item
        label="验证码登录错误次数"
        prop="length"
      >
        <el-input v-model="form.captcha_show_by_code_error" />
        <span class="smalltip">
          <i class="el-icon-info" />
          填写数字，0为开启验证码，如设置为3，则错误3次才发起验证
        </span>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      form: {
        captcha_open: true,
        captcha_type: 'picture',
        picture: {
          code_mode: '3',
          useZh: true,
          useCurve: true,
          useNoise: true,
          length: 0
        },
        captcha_tencent_appid: '',
        captcha_tencent_appsecret: '',
        captcha_vaptcha_vid: '',
        captcha_vaptcha_key: '',
        captcha_show_by_pwd_error: '',
        captcha_show_by_code_error: ''
      },
      rules: {},
      setting_secrecy: false
    }
  },
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
            captcha_open,
            captcha_type,
            captcha_picture_rule,
            captcha_tencent_appid,
            captcha_tencent_appsecret,
            captcha_vaptcha_vid,
            captcha_vaptcha_key,
            captcha_show_by_pwd_error,
            captcha_show_by_code_error
          } = {
            ...response.data
          }
          this.form.captcha_open = captcha_open == 1
          this.form.captcha_type = captcha_type
          this.form.picture = {
            code_mode: captcha_picture_rule.code_mode == undefined ? '3' : captcha_picture_rule.code_mode,
            useZh: captcha_picture_rule.useZh == 1,
            useCurve: captcha_picture_rule.useCurve == 1,
            useNoise: captcha_picture_rule.useNoise == 1,
            length: captcha_picture_rule.length
          }
          this.form.captcha_tencent_appid = captcha_tencent_appid
          this.form.captcha_tencent_appsecret = captcha_tencent_appsecret
          this.form.captcha_vaptcha_vid = captcha_vaptcha_vid
          this.form.captcha_vaptcha_key = captcha_vaptcha_key
          this.form.captcha_show_by_pwd_error = captcha_show_by_pwd_error
          this.form.captcha_show_by_code_error = captcha_show_by_code_error
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      const insertData = {
        captcha_open: that.form.captcha_open == true ? 1 : 0,
        captcha_type: that.form.captcha_type,
        captcha_picture_rule: {
          code_mode: that.form.picture.code_mode,
          useZh: that.form.picture.useZh == true ? 1 : 0,
          useCurve: that.form.picture.useCurve == true ? 1 : 0,
          useNoise: that.form.picture.useNoise == true ? 1 : 0,
          length: that.form.picture.length
        },
        captcha_tencent_appid: that.form.captcha_tencent_appid,
        captcha_tencent_appsecret: that.form.captcha_tencent_appsecret,
        captcha_vaptcha_vid: that.form.captcha_vaptcha_vid,
        captcha_vaptcha_key: that.form.captcha_vaptcha_key,
        captcha_show_by_pwd_error: that.form.captcha_show_by_pwd_error,
        captcha_show_by_code_error: that.form.captcha_show_by_code_error
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
    }
  }
}
</script>
