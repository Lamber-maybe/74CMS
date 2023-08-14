<template>
  <div>
    <el-form
      ref="form"
      class="common-form"
      label-width="200px"
      :inline-message="true"
    >
      <el-form-item label="生成用户名前缀">
        <el-input
          v-model="form.name_prefix"
        />
      </el-form-item>
      <el-form-item label="生成用户名规则" prop="name_rule">
        <el-radio-group v-model="form.name_rule">
          <el-radio
            v-for="(item, index) in options_name_rule"
            :key="index"
            :label="item.id"
          >
            {{ item.name }}
          </el-radio>
        </el-radio-group>
        <br>
        <span class="smalltip">
          <i class="el-icon-info" />
          前缀 + 规则
        </span>
      </el-form-item>
      <el-form-item label="生成密码规则" prop="pwd_rule">
        <el-radio-group v-model="form.pwd_rule">
          <el-radio
            v-for="(item, index) in options_pwd_rule"
            :key="index"
            :label="item.id"
          >
            {{ item.name }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item v-show="form.pwd_rule==2" label="生成指定密码">
        <el-input
          v-model="form.password"
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
import { getSeting, saveAccountSeting } from '@/api/datacollection'

export default {
  data() {
    return {
      options_name_rule: [
        {
          'id': 1,
          'name': '随机字符串'
        },
        {
          'id': 2,
          'name': '手机号'
        }
      ],
      options_pwd_rule: [
        {
          'id': 1,
          'name': '与用户名相同'
        },
        {
          'id': 2,
          'name': '指定密码'
        }
      ],
      form: {
        name_prefix: '',
        name_rule: 1,
        pwd_rule: 1,
        password: ''
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      getSeting({}, 'get')
        .then(response => {
          this.accountSeting = response.data.account_seting
          if (Object.keys(this.accountSeting).length) {
            this.form = this.accountSeting
          }
        })
        .catch(() => {})
    },
    onSubmit() {
      const insertData = {
        name_prefix: this.form.name_prefix,
        name_rule: this.form.name_rule,
        pwd_rule: this.form.pwd_rule,
        password: this.form.password
      }
      saveAccountSeting(insertData)
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
