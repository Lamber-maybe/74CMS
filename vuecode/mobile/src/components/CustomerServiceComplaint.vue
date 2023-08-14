<template>
  <div id="app">
    <Head :goback_custom="true" @gobackCustomMethod="$emit('closeComplaint')">
      投诉客服
    </Head>
    <van-form @submit="onSubmit">
      <van-field
        type="textarea"
        rows="2"
        name="content"
        v-model="content"
        label=""
        placeholder="请输入投诉内容"
        :rules="[{ required: true, message: '请输入投诉内容' }]"
      />
      <div style="margin: 16px;">
        <van-button round block type="info" native-type="submit">
          提交
        </van-button>
      </div>
    </van-form>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'CustomerServiceComplaint',
  props: ['cs_id'],
  data () {
    return {
      content: ''
    }
  },
  methods: {
    initContent () {
      this.content = ''
    },
    onSubmit (values) {
      values.cs_id = this.cs_id
      http
        .post(api.customer_service_complaint, values)
        .then(res => {
          this.$notify({
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

<style lang="scss" scoped></style>
