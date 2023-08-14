<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      label-width="200px"
      :inline-message="true"
    >
      <el-form-item label="开启采集功能">
        <el-switch v-model="is_open" />
      </el-form-item>
      <el-form-item label="匹配精准度">
        <el-input
          v-model="matching_accuracy"
          placeholder="请输入0 ~ 100之间的数"
        />
      </el-form-item>
      <el-form-item label="">
        <el-button
          type="primary"
          @click="onSubmit"
        >
          保存
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getSeting, saveSeting } from '@/api/datacollection'

export default {
  data() {
    return {
      is_open: false,
      matching_accuracy: ''
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      getSeting({}, 'get')
        .then(response => {
          this.data = response.data
          this.is_open = this.data.status == 1
          this.matching_accuracy = this.data.matching_accuracy
        })
        .catch(() => {})
    },
    onSubmit() {
      const insertData = {
        status: this.is_open ? '1' : '0',
        matching_accuracy: this.matching_accuracy ? this.matching_accuracy : '0'
      }
      saveSeting(insertData)
        .then(response => {
          this.$message.success(response.message)
          return true
        })
        .catch(() => {})
    }
  }
}
</script>

<style scoped>
.el-input,
.el-input-group {
  width: 25%;
}
</style>
