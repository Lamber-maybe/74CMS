<template>
  <div class="app-container">
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="150px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="推广简历">
        {{ itemInfo.fullname }}
      </el-form-item>
      <el-form-item label="会员手机号">
        {{ itemInfo.mobile }}
      </el-form-item>
      <el-form-item label="推广方案">
        {{ itemInfo.type == 'stick' ? '置顶' : '醒目标签' }}
      </el-form-item>
      <el-form-item label="推广期限">
        {{ itemInfo.addtime | timeFilter }} ~
        {{ itemInfo.deadline | timeFilter }}
      </el-form-item>
      <el-form-item label="延长推广天数" prop="days">
        <el-input v-model.number="form.days" type="number" :min="0"/>
      </el-form-item>
      <el-form-item label=" ">
        <el-button type="primary" @click="onSubmit('form')" :disabled="issubmit">保存</el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'
import { resumePromotionEdit } from '@/api/promotion_resume'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  props: ['itemInfo'],
  data() {
    return {
      issubmit: false,
      form: {
        days: ''
      },
      rules: {
        days: [
          {
            required: true,
            message: '请填写延长推广天数',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  created() {},
  methods: {
    onSubmit(formName) {
      const that = this
      that.issubmit = true
      that.$refs[formName].validate(valid => {
        if (valid) {
          const insertData = {
            id: that.itemInfo.id,
            days: that.form.days
          }
          resumePromotionEdit(insertData)
            .then(response => {
              that.$message.success(response.message)
              that.closeDialog()
              that.pageReload()
              return true
            })
            .catch(() => {
              that.issubmit = false
              return false
            })
        } else {
          that.issubmit = false
          return false
        }
      })
    },
    closeDialog() {
      this.$emit('setDialogVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    }
  }
}
</script>
<style scoped>
.el-select,
.el-input {
  width: 300px;
}
</style>
