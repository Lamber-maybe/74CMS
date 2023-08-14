<template>
    <el-form label-width="80px">
        <el-alert title="为了更好地为您提供高质量的服务，请您如实反馈具体情况，我们会第一时间给您答复！" type="warning" :closable="false" style="margin-bottom:20px;"></el-alert>
        <el-form-item label="投诉内容">
            <el-input type="textarea" v-model="content" rows="4"></el-input>
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="onSubmit">确定</el-button>
            <el-button @click="$emit('closeComplaint')">取消</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'CustomerServiceComplaint',
  props: ['csId'],
  data () {
    return {
      content: ''
    }
  },
  methods: {
    onSubmit () {
      this.content = this.content.trim()
      if(this.content==''){
        this.$message.error('请输入投诉内容')
        return false
      }
      let data = {
        cs_id:this.csId,
        content:this.content
      }
      http
        .post(api.customer_service_complaint, data)
        .then(() => {
          this.$message({
            type: 'success',
            message: '投诉成功，我们会尽快处理'
          })
          setTimeout(() => {
            this.$emit('closeComplaint')
          }, 2000)
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>

</style>