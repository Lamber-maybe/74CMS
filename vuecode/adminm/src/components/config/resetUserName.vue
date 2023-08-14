<template>
  <div>
    <Head :go-back-custom="true" @goBackCustomMethod="$emit('closePopout')">修改用户名</Head>
    <van-form @submit="onSubmit">
    <van-cell-group>
      <van-field v-model="username" label="用户名" placeholder="请输入用户名" :rules="[{ required: true, message: '' }]" />
    </van-cell-group>
    <div style="margin: 16px;">
      <van-button round block type="info" native-type="submit">提交</van-button>
    </div>
    </van-form>
  </div>
</template>

<script>
  export default {
    name: "resetUserName",
    data () {
      return {
        username: ''
      }
    },
    methods:{
      onSubmit () {
        this.$emit('onSubmit',{username:this.username})
      },
    }
  }
</script>

<style scoped>

</style>
