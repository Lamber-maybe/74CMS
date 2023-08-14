<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>{{ title }}</span>
      </div>
      <div v-if="setting_secrecy === true" class="no-promise">
        <div class="notice-div">
          <p class="notice-p">暂无查看权限，请联系网站负责人</p>
        </div>
      </div>
      <div v-else>
        <el-form ref="form" :model="form" :rules="rules" label-width="120px">
          <el-form-item label="AppKey" prop="app_key">
            <el-input v-model="form.app_key" place-holder="请输入AppKey" style="width:50%" @change="inputChange" />
          </el-form-item>
          <el-form-item label="AppSecret" prop="app_secret">
            <el-input v-model="form.app_secret" place-holder="请输入AppSecret" style="width:50%" @change="inputChange" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit">保存</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
  </div>
</template>

<script>
import {
  getBaseConfig,
  saveBaseConfig
} from '@/api/directory'

export default {
  name: 'BasicConfig',
  data() {
    return {
      title: '基础配置',
      form: {
        app_key: '',
        app_secret: ''
      },
      rules: {
        app_key: [{
          required: true,
          message: '请输入AppKey',
          trigger: 'blur'
        }],
        app_secret: [{
          required: true,
          message: '请输入AppSecret',
          trigger: 'blur'
        }]
      },
      setting_secrecy: false
    }
  },
  computed: {},
  watch: {},
  mounted() {
    this.initData()
  },
  created() {
    this.setting_secrecy = window.global.SettingSecrecy ? window.global.SettingSecrecy : false
  },
  methods: {
    initData() {
      getBaseConfig().then(res => {
        if (res.code == 200) {
          this.form = res.data
        } else {
          this.$message.error(res.message)
        }
      })
    },
    onSubmit() {
      saveBaseConfig(this.form).then(res => {
        if (res.code == 200) {
          this.$message.success(res.message)
          this.initData()
        } else {
          this.$message.error(res.message)
        }
      })
    },
    inputChange() {
      if (this.form.app_key === '' && this.form.app_secret === '') {
        this.rules = {
          app_key: [{
            required: false,
            message: '请输入AppKey',
            trigger: 'blur'
          }],
          app_secret: [{
            required: false,
            message: '请输入AppSecret',
            trigger: 'blur'
          }]
        }
      } else {
        this.rules = {
          app_key: [{
            required: true,
            message: '请输入AppKey',
            trigger: 'blur'
          }],
          app_secret: [{
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

<style scoped lang="scss">

</style>
