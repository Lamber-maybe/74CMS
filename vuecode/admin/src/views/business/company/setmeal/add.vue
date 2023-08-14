<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="infoLoading"
      class="common-form"
      :model="form"
      label-width="80px"
      :inline-message="false"
      :inline="false"
    >
      <el-form-item label="选择套餐">
        <el-select v-model="form.setmeal_id" placeholder="请选择">
          <el-option
            v-for="item in optionsSetmeal"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label=" ">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>

import { getClassify } from '@/api/classify'
import { companySetmealAdd } from '@/api/company_setmeal'

export default {
  props: ['uid'],
  data() {
    return {
      infoLoading: false,
      form: {
        setmeal_id: ''
      },
      optionsSetmeal: []
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      const uid = this.uid
      if (uid > 0) {
        getClassify({ type: 'setmealList' })
          .then(res => {
            this.optionsSetmeal = res.data
            this.infoLoading = false
          })
          .catch(() => {})
      } else {
        this.infoLoading = false
      }
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...that.form }
      insertData.uid = this.uid
      this.$refs[formName].validate(valid => {
        if (valid) {
          companySetmealAdd(insertData)
            .then(response => {
              this.$message.success(response.message)
              this.closeDialog()
              this.pageReload()
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    },
    closeDialog() {
      this.$emit('setDialogFormVisible')
    },
    pageReload() {
      this.$emit('pageReload')
    }
  }
}
</script>
<style scoped>
</style>
