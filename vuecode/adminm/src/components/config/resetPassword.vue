<template>
  <div>
    <Head :go-back-custom="true" @goBackCustomMethod="$emit('closePopout')">修改密码</Head>
    <van-form @submit="onSubmit">
    <van-cell-group v-if="nocheck===true">
      <van-field type="password" v-model="password" label="新密码" placeholder="请输入新密码"  :rules="[{ required: true, message: '' }]" />
    </van-cell-group>
    <van-cell-group v-else>
      <van-field type="password" v-model="old_password" label="原密码" placeholder="请输入原密码"  :rules="[{ required: true, message: '' }]" />
      <van-field type="password" v-model="new_password" label="新密码" placeholder="请输入新密码"  :rules="[{ required: true, message: '' }]" />
      <van-field type="password" v-model="new_password_repeat" label="确认密码" placeholder="请确认密码"  :rules="[{ required: true, message: '' }]" />
    </van-cell-group>
    <div style="margin: 16px;">
      <van-button round block type="info" native-type="submit">提交</van-button>
    </div>
    </van-form>
  </div>
</template>

<script>
  export default {
    name: "resetPassword",
    props:['nocheck'],
    data () {
      return {
        password:'',
        old_password: '',
        new_password: '',
        new_password_repeat: ''
      }
    },
    methods:{
      onSubmit () {
        let data = {}
        if(this.nocheck===true){
          data = {password:this.password}
        }else{
          data = {old_password:this.old_password,new_password:this.new_password,new_password_repeat:this.new_password_repeat}
        }
        this.$emit('onSubmit',data)
      }
    }
  }
</script>

<style scoped>

</style>
