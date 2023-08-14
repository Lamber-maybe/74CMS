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
      <el-form-item label="推广职位">
        {{ itemInfo.jobname }}
      </el-form-item>
      <el-form-item label="所属企业">
        {{ itemInfo.companyname }}
      </el-form-item>
      <el-form-item label="推广方案">
        {{ itemInfo.type == 'jobstick' ? '置顶' : '紧急' }}
      </el-form-item>
      <el-form-item label="推广期限">
        {{ itemInfo.addtime | timeFilter }} ~
        {{ itemInfo.deadline | timeFilter }}
      </el-form-item>
      <el-form-item label="延长推广天数" prop="days">
        <el-input v-model.number="form.days" type="number" />
      </el-form-item>
      <el-form-item label=" ">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'
import { jobPromotionEdit } from '@/api/promotion_job'

export default {
  filters: {
    timeFilter(timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  props: ['itemInfo'],
  data() {
    return {
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
      that.$refs[formName].validate(valid => {
        if (valid) {
          const insertData = {
            id: that.itemInfo.id,
            days: that.form.days
          }
          jobPromotionEdit(insertData)
            .then(response => {
              that.$message.success(response.message)
              that.closeDialog()
              that.pageReload()
              return true
            })
            .catch(() => {})
        } else {
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
