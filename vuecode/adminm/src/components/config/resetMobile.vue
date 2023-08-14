<template>
  <div>
    <Head :go-back-custom="true" @goBackCustomMethod="$emit('closePopout')">修改手机号</Head>
    <van-form @submit="onSubmit">
    <van-cell-group>
      <van-field v-model="mobile" label="手机号" placeholder="请输入手机号" :rules="[{ required: true, message: '' }]" />
    </van-cell-group>
    <div style="margin: 16px;">
      <van-button round block type="info" native-type="submit">提交</van-button>
    </div>
    </van-form>
  </div>
</template>

<script>
  export default {
    name: "resetMobile",
    data () {
      return {
        mobile: ''
      }
    },
    methods:{
      onSubmit () {
        this.$emit('onSubmit',{mobile:this.mobile})
      },
    }
  }
</script>

<style scoped>

</style>
