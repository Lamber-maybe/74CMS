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
          <el-radio label="wechat">微信带参数二维码</el-radio>
        </el-radio-group>
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
        qrcode_type: 'normal'
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
            qrcode_type
          } = { ...response.data }
          this.form = {
            points_byname,
            points_quantifier,
            payment_rate,
            wage_unit,
            qrcode_type
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
    }
  }
}
</script>
