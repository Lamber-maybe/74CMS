<template>
  <div class="app-container">
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span>休眠用户提醒</span>
      </div>
      <div class="tip">
        <p>
          此功能主要用于通过短信提醒一定时间范围内未登录的用户登录网站。开启此功能可能要耗费较多的短信，请按需开启。
        </p>
        <p>
          用户登录条件区间指需按照[较近]-[较远]天数设置（如配置15-30天则会发送短信给最近15-30天内未登录的用户）；提醒次数限制指符合该条件会提醒几次，满足指定次数后将不再提醒。
        </p>
      </div>
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        label-width="150px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="用户未登录条件区间">
          <el-input v-model="nologin_notice_time1" style="width:200px;">
            <template slot="append">天</template>
          </el-input>
          -
          <el-input v-model="nologin_notice_time2" style="width:200px;">
            <template slot="append">天</template>
          </el-input>
        </el-form-item>
        <el-form-item label="提醒次数限制">
          <el-input v-model="form.nologin_notice_counter" style="width:200px;">
            <template slot="append">次</template>
          </el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button
            type="primary"
            @click="onSubmit('form')"
          >
            保存
          </el-button>
        </el-form-item>
      </el-form>

    </el-card>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      nologin_notice_time1: '',
      nologin_notice_time2: '',
      infoLoading: false,
      form: {
        nologin_notice_timerange: '',
        nologin_notice_counter: ''
      },
      rules: {}
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const { nologin_notice_timerange, nologin_notice_counter } = { ...response.data }
          var i = nologin_notice_timerange.indexOf(',')
          if (i != -1){
            const nologin_notice_timerange_arr = nologin_notice_timerange.split(',')
            this.nologin_notice_time1 = nologin_notice_timerange_arr[0]
            this.nologin_notice_time2 = nologin_notice_timerange_arr[1]
          }
          this.form.nologin_notice_counter = nologin_notice_counter
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }
      insertData.nologin_notice_timerange = this.nologin_notice_time1 + ',' + this.nologin_notice_time2
      this.$refs[formName].validate(valid => {
        if (valid) {
          setConfig(insertData)
            .then(response => {
              this.$message.success(response.message)
              return true
            })
            .catch(() => {})
        } else {
          return false
        }
      })
    }
  }
}
</script>
<style scoped>
.submiting-block {
  width: 100%;
  text-align: center;
}
.submiting-block p {
  width: 350px;
  margin: 20px auto;
  color: #666;
}
</style>
