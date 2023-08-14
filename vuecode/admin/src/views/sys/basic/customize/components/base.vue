<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="180px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="积分代替名" prop="points_byname">
        <el-input v-model="form.points_byname" class="small" />
        <span class="smalltip">
          <i class="el-icon-info" />
          如：铜板、元宝等，默认为“积分”
        </span>
      </el-form-item>
      <el-form-item label="积分量词" prop="points_quantifier">
        <el-input v-model="form.points_quantifier" class="small" />
        <span class="smalltip">
          <i class="el-icon-info" />
          如：个、只、把等，默认为“点”
        </span>
      </el-form-item>
      <el-form-item label="换算比例" prop="payment_rate">
        <el-input v-model="form.payment_rate" class="small">
          <template slot="prepend">1元等于</template>
          <template slot="append">点积分</template>
        </el-input>
        <span class="smalltip">
          <i class="el-icon-info" />
          充值人民币与积分兑换比例，在购买服务时积分抵扣现金也是此比例
        </span>
      </el-form-item>
      <el-form-item label="薪资显示方式">
        <el-radio-group v-model="form.wage_unit">
          <el-radio label="0">3000~5000</el-radio>
          <el-radio label="1">3K~5K</el-radio>
          <el-radio label="2">3千~5千</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="二维码展示方式">
        <el-radio-group v-model="form.qrcode_type">
          <el-radio label="normal">普通</el-radio>
          <el-radio label="miniprogram">小程序码</el-radio>
          <el-radio label="wechat">微信带参数二维码</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="职位浏览量随机值" prop="rand_click_job">
        <el-input v-model="form.rand_click_job" class="small" @keyup.native="keyup()" />
        <span class="smalltip">
          <i class="el-icon-info" />
          浏览量在“1~设置值”之间随机增加，0为不干预
        </span>
      </el-form-item>
      <el-form-item label="企业浏览量随机值" prop="rand_click_company">
        <el-input v-model="form.rand_click_company" class="small" @keyup.native="keyup()" />
        <span class="smalltip">
          <i class="el-icon-info" />
          浏览量在“1~设置值”之间随机增加，0为不干预
        </span>
      </el-form-item>
      <el-form-item label="简历浏览量随机值" prop="rand_click_resume">
        <el-input v-model="form.rand_click_resume" class="small" @keyup.native="keyup()" />
        <span class="smalltip">
          <i class="el-icon-info" />
          浏览量在“1~设置值”之间随机增加，0为不干预
        </span>
      </el-form-item>
<!--      新增资讯浏览量随机值-->
      <el-form-item label="资讯浏览量随机值" prop="rand_click_article">
        <el-input v-model="form.rand_click_article" class="small" @keyup.native="keyup()" />
        <span class="smalltip">
          <i class="el-icon-info" />
          浏览量在“1~设置值”之间随机增加，0为不干预
        </span>
      </el-form-item>
<!--      【ID1000446】【新增】后台控制登录时效，默认7天-->
      <el-form-item label="登录时效" prop="rand_click_article">
        <el-input v-model="form.token_expire" class="small" type="number" min="10" :controls="false" @blur="format_number(10, 'token_expire')">
          <template slot="append">分</template>
        </el-input>
        <span class="smalltip">
          <i class="el-icon-info" />
          指登录状态的有效期，登录状态失效后会自动退出账号，最小10分钟。如：7天为10080分。
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
        points_byname: '',
        points_quantifier: '',
        payment_rate: 1,
        wage_unit: 0,
        qrcode_type: 'normal',
        rand_click_job: 0,
        rand_click_company: 0,
        rand_click_resume: 0,
        rand_click_article: 0,
        token_expire: 0
      },
      rules: {
        points_byname: [
          {
            required: true,
            message: '请输入积分代替名',
            trigger: 'blur'
          }
        ],
        points_quantifier: [
          {
            required: true,
            message: '请输入积分量词',
            trigger: 'blur'
          }
        ],
        payment_rate: [
          {
            required: true,
            message: '请输入充值比例',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  mounted() {},
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            points_byname,
            points_quantifier,
            payment_rate,
            wage_unit,
            qrcode_type,
            rand_click_job,
            rand_click_company,
            rand_click_resume,
            rand_click_article,
            token_expire
          } = { ...response.data }
          this.form = {
            points_byname,
            points_quantifier,
            payment_rate,
            wage_unit,
            qrcode_type,
            rand_click_job,
            rand_click_company,
            rand_click_resume,
            rand_click_article,
            token_expire
          }
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
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
    keyup(){
      if (this.form.rand_click_job == ''){
        this.form.rand_click_job = 0
      }
      if (this.form.rand_click_company == ''){
        this.form.rand_click_company = 0
      }
      if (this.form.rand_click_resume == ''){
        this.form.rand_click_resume = 0
      }
      if (this.rand_click_article == ''){
        this.rand_click_article = 0
      }
    },
    format_number(default_val, field) {
      if (this.form[field] == '' || parseInt(this.form[field]) < default_val) {
        this.form[field] = default_val
      }
    }
  }
}
</script>

<style scoped>
/deep/ input::-webkit-outer-spin-button,
/deep/ input::-webkit-inner-spin-button {
  -webkit-appearance: none !important;
}
/deep/ input[type='number'] {
  -moz-appearance: textfield !important;
}
</style>
