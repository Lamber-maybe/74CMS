<template>
  <div class="app-container">
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="120px"
      :rules="rules"
      :inline-message="true"
    >
      <el-form-item label="会员积分">
        <el-input :value="form.points" disabled />
      </el-form-item>
      <el-form-item label="操作积分">
        <el-radio-group v-model="form.op">
          <el-radio label="1">增加</el-radio>
          <el-radio label="2">减少</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="增减积分" prop="points_val">
        <el-input v-model.number="form.points_val" type="number" />
        点
      </el-form-item>
      <el-form-item label="操作说明">
        <el-input v-model="form.explain" />
      </el-form-item>
      <el-form-item label="是否收费">
        <el-switch v-model="form.is_charge" />
      </el-form-item>
      <el-form-item label="收费金额">
        <el-input v-model="form.charge_val" />
        元
      </el-form-item>
      <el-form-item label=" ">
        <el-button type="primary" @click="onSubmit('form')">保存</el-button>
        <el-button @click="closeDialog">取 消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { memberPointsEdit } from '@/api/member'
export default {
  props: ['itemInfo'],
  data() {
    return {
      form: {
        uid: 0,
        points: '',
        op: '1',
        points_val: '',
        explain: '',
        is_charge: true,
        charge_val: ''
      },
      rules: {
        points_val: [
          {
            required: true,
            message: '请输入增减积分数',
            trigger: 'blur'
          }
        ]
      }
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      if (this.itemInfo !== null) {
        this.form = { ...this.form, ...this.itemInfo }
      }
    },
    onSubmit(formName) {
      const that = this
      const insertData = { ...that.form }
      insertData.is_charge = insertData.is_charge === true ? 1 : 0
      that.$refs[formName].validate(valid => {
        if (valid) {
          memberPointsEdit(insertData)
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
      this.$emit('setDialogFormVisible')
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
