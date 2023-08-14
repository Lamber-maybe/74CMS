<template>
  <div class="app-container">
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span>优惠券设置</span>
      </div>
      <div class="spaceline" />
      <el-form
        ref="form"
        v-loading="infoLoading"
        class="common-form"
        :model="form"
        label-width="180px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item
          label="开启优惠券功能"
          class="is-required"
        >
          <el-switch v-model="form.is_open" />
        </el-form-item>
        <el-form-item
          label="到期提前几天提醒"
          prop="remind_days"
        >
          <el-input
            v-model.number="form.remind_days"
            type="number"
            min="0"
          />
          <span class="smalltip">
            <i class="el-icon-info" />
            0为不提醒
          </span>
        </el-form-item>
        <el-form-item
          label="新注册会员赠送优惠券"
          class="is-required"
        >
          <el-switch v-model="form.is_reg_gift" />
        </el-form-item>
        <el-form-item
          v-if="form.is_reg_gift === true"
          label="新注册会员赠送优惠券"
          prop="reg_gift_list"
          class="is-required"
        >
          <el-checkbox-group v-model="form.reg_gift_list">
            <el-checkbox
              v-for="item in options_coupon"
              :key="item.id"
              :label="item.id"
              :value="item.id"
            >
              {{ item.name }}
            </el-checkbox>
          </el-checkbox-group>
        </el-form-item>
        <el-form-item label="">
          <el-button
            type="primary"
            @click="onSubmit('form')"
          >
            保存
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'
import { getClassify } from '@/api/classify'

export default {
  data() {
    var validateGiftList = (rule, value, callback) => {
      if (this.form.is_reg_gift === true && value.length == 0) {
        callback(new Error('请选择要赠送的优惠券'))
      } else {
        callback()
      }
    }
    return {
      infoLoading: true,
      form: {
        is_open: false,
        remind_days: 0,
        is_reg_gift: true,
        reg_gift_list: []
      },
      options_coupon: [],
      rules: {
        remind_days: [
          {
            required: true,
            message: '请输入到期提前几天提醒',
            trigger: 'blur'
          },
          {
            type: 'number',
            message: '请正确输入到期提前几天提醒',
            trigger: 'blur'
          }
        ],
        reg_gift_list: [{ validator: validateGiftList, trigger: 'blur' }]
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      getClassify({ type: 'couponList' })
        .then(response => {
          this.options_coupon = response.data
          return setConfig({}, 'get')
        })
        .then(response => {
          this.form = { ...this.form, ...response.data.coupon_config }
          this.form.is_open = this.form.is_open == 1
          this.form.is_reg_gift = this.form.is_reg_gift == 1
          this.form.remind_days = parseInt(this.form.remind_days)
          this.form.reg_gift_list.forEach((item, index) => {
            this.form.reg_gift_list[index] = parseInt(item)
          })
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      this.$refs[formName].validate(valid => {
        if (valid) {
          insertData.is_open = insertData.is_open === true ? 1 : 0
          insertData.is_reg_gift = insertData.is_reg_gift === true ? 1 : 0
          setConfig({ coupon_config: insertData })
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

<style scoped>
.el-input {
  width: 450px;
}
</style>
