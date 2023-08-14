<template>
  <div class="app-container">
    <el-card>
      <div slot="header" class="clearfix">
        <span>企业显示状态同步</span>
      </div>
      <el-form
        ref="form"
        :model="form"
        label-width="240px"
        :inline-message="true"
      >
        <el-form-item
          label="功能使用场景说明"
        >
          将指定认证状态的企业设置为“显示中”或者“不显示”状态
        </el-form-item>
        <el-form-item
          label="待审核-认证资料等待审核中"
        >
          <el-radio-group v-model="form.waiting">
            <el-radio :label="1">显示</el-radio>
            <el-radio :label="0">不显示</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          label="已认证-认证资料已审核通过"
        >
          <el-radio-group v-model="form.pass">
            <el-radio :label="1">显示</el-radio>
            <el-radio :label="0">不显示</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          label="未通过-认证资料审核不通过"
        >
          <el-radio-group v-model="form.nopass">
            <el-radio :label="1">显示</el-radio>
            <el-radio :label="0">不显示</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          label="未认证-未提交认证资料"
        >
          <el-radio-group v-model="form.no_audit">
            <el-radio :label="1">显示</el-radio>
            <el-radio :label="0">不显示</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label=" ">
          <el-button
            type="primary"
            @click="onSubmitPre"
          >
            提交同步
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
    <el-dialog title="验证密码" :visible.sync="enterPwdShow" width="350px">
      <el-row>
        <el-col :span="18"> <el-input v-model="form.pwd" type="password" placeholder="请输入当前管理员密码" /> </el-col>
        <el-col :span="6"> <el-button type="primary" @click="onSubmit">确定</el-button></el-col>
      </el-row>
    </el-dialog>
  </div>
</template>

<script>
import { sysToolSyncCompanyDisplay } from '@/api/systool'
export default {
  data() {
    return {
      setMealList: [],
      enterPwdShow: false,
      form: {
        pwd: '',
        waiting: 1,
        pass: 1,
        nopass: 1,
        no_audit: 1
      }
    }
  },
  created() {
  },
  methods: {
    onSubmitPre () {
      this.form.pwd = ''
      this.enterPwdShow = true
    },
    onSubmit() {
      if (this.form.pwd.length == 0){
        this.$message.error('密码不能为空')
        return false
      }
      const data = Object.assign({}, this.form)
      sysToolSyncCompanyDisplay(data)
        .then(res => {
          this.enterPwdShow = false
          this.$message.success(res.message)
          return true
        })
        .catch(() => {})
    }
  }
}
</script>
<style lang="scss" scoped>
  .val{
    color: #e29417;
  }
</style>
