<template>
  <div class="app-container">
    <el-card
      v-loading="fullscreenLoading"
      class="box-card"
      element-loading-text="发放中"
    >
      <div
        slot="header"
        class="clearfix"
      >
        <span>发放优惠券</span>
      </div>
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="100px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item
          label="优惠券"
          prop="coupon_id"
          class="from_item"
        >
          <el-checkbox-group v-model="form.coupon_id">
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
        <el-form-item label="企业">
          <el-radio-group v-model="form.setmeal_id">
            <el-radio
              :key="0"
              :label="0"
              :value="0"
            >
              全部企业
            </el-radio>
            <el-radio
              v-for="item in options_setmeal"
              :key="item.id"
              :label="item.id"
              :value="item.id"
            >
              {{ item.name }}
            </el-radio>
            <el-radio
              :key="-1"
              :label="-1"
              :value="-1"
            >
              自定义
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          v-if="form.setmeal_id == -1"
          label=" "
          prop="uid"
        >
          <el-transfer
            v-model="form.uid"
            style="margin-top:22px;"
            :titles="['可选企业', '已选企业']"
            filterable
            filter-placeholder="请输入企业名称"
            :data="options_companylist"
          />
        </el-form-item>
        <el-form-item
          label=""
          style="margin-top:22px;"
        >
          <el-button
            type="primary"
            @click="onSubmit('form')"
            :disabled="issubmit"
          >
            确定发放
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { couponSend } from '@/api/coupon'
import { getClassify } from '@/api/classify'

export default {
  data() {
    var validateUid = (rule, value, callback) => {
      if (this.form.setmeal_id == -1 && value.length == 0) {
        callback(new Error('请选择企业'))
      } else {
        callback()
      }
    }
    return {
      fullscreenLoading: false,
      issubmit: false,
      options_setmeal: [],
      options_coupon: [],
      options_companylist: [],
      form: {
        coupon_id: [],
        setmeal_id: -1,
        uid: []
      },
      rules: {
        coupon_id: [
          {
            required: true,
            message: '请选择优惠券',
            trigger: 'change'
          }
        ],
        uid: [{ validator: validateUid, trigger: 'change' }]
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      getClassify({ type: 'setmealList,couponList,companyList' })
        .then(response => {
          this.options_setmeal = response.data.setmealList
          this.options_coupon = response.data.couponList
          const comlist = response.data.companyList
          comlist.forEach((item, index) => {
            this.options_companylist.push({
              label: item.name,
              key: item.id
            })
          })
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      this.fullscreenLoading = true
      const that = this
      that.issubmit = true
      const insertData = { ...that.form }
      if (insertData.setmeal_id != -1) {
        insertData.uid = []
      }
      that.$refs[formName].validate(valid => {
        if (valid) {
          couponSend(insertData)
            .then(response => {
              that.$message.success(response.message)
              setTimeout(function() {
                that.$router.push('/tool/coupon/log')
              }, 1500)
              that.fullscreenLoading = false
              return true
            })
            .catch(() => {
              that.issubmit = false
              that.fullscreenLoading = false
              return false
            })
        } else {
          that.issubmit = false
          that.fullscreenLoading = false
          return false
        }
      })
    }
  }
}
</script>
<style>
.el-transfer-panel__item.el-checkbox {
  display: block;
}
</style>
<style scoped>
.el-form-item {
  margin-bottom: 0;
}
.from_item{
  margin-bottom: 18px;
}
::v-deep .el-radio-button__inner, .el-radio-group {
  line-height: 26px;
}
::v-deep .el-form-item__content{
  line-height: 24px;
}
::v-deep .el-form-item__label{
  line-height: initial;
}
</style>
