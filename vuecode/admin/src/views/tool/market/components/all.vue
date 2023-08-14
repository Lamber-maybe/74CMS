<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      :model="form"
      label-width="100px"
      :rules="rules"
    >
      <el-form-item label="注册时间" prop="reg_time">
        <el-select v-model="form.reg_time" placeholder="不限" class="large">
          <el-option label="不限" :value="-1" />
          <el-option label="3天内" :value="3" />
          <el-option label="7天内" :value="7" />
          <el-option label="15天内" :value="15" />
          <el-option label="30天内" :value="30" />
          <el-option label="90天内" :value="90" />
          <el-option label="180天内" :value="180" />
        </el-select>
      </el-form-item>
      <el-form-item label="登录时间" prop="login_time">
        <el-select v-model="form.login_time" placeholder="不限" class="large">
          <el-option label="不限" :value="-1" />
          <el-option label="3天内" :value="3" />
          <el-option label="7天内" :value="7" />
          <el-option label="15天内" :value="15" />
          <el-option label="30天内" :value="30" />
          <el-option label="90天内" :value="90" />
          <el-option label="180天内" :value="180" />
        </el-select>
      </el-form-item>
      <el-form-item label="账号选项">
        <el-select v-model="form.auth_email" placeholder="不限绑定邮箱">
          <el-option label="不限绑定邮箱" :value="-1" />
          <el-option label="已绑定" :value="1" />
          <el-option label="未绑定" :value="0" />
        </el-select>
        <el-select v-model="form.auth_mobile" placeholder="不限认证手机">
          <el-option label="不限认证手机" :value="-1" />
          <el-option label="已认证" :value="1" />
          <el-option label="未认证" :value="0" />
        </el-select>
        <el-select v-model="form.auth_weixin" placeholder="不限绑定微信">
          <el-option label="不限绑定微信" :value="-1" />
          <el-option label="已绑定" :value="1" />
          <el-option label="未绑定" :value="0" />
        </el-select>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        reg_time: -1,
        login_time: -1,
        auth_email: -1,
        auth_mobile: -1,
        auth_weixin: -1
      },
      rules: {}
    }
  },
  created() {},
  methods: {}
}
</script>
<style scoped>
.large {
  width: 608px;
}
.el-select{
  margin-right:10px;
}
</style>
