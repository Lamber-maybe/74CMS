<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>休眠用户提醒</span>
      </div>
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        :rules="rules"
        label-width="100px"
      >
        <el-form-item label="会员类型">
          <el-radio-group v-model="form.utype">
            <el-radio :label="1">
              企业
            </el-radio>
            <el-radio :label="2">
              个人
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="未登录" prop="no_login">
          <el-select v-model="form.no_login" placeholder="不限" class="large">
            <el-option label="不限" :value="-1" />
            <el-option label="3天" :value="3" />
            <el-option label="7天" :value="7" />
            <el-option label="15天" :value="15" />
            <el-option label="30天" :value="30" />
            <el-option label="90天" :value="90" />
            <el-option label="180天" :value="180" />
          </el-select>
        </el-form-item>
        <el-form-item label="未刷新" prop="no_refresh">
          <el-select v-model="form.no_refresh" placeholder="不限" class="large">
            <el-option label="不限" :value="-1" />
            <el-option label="3天" :value="3" />
            <el-option label="7天" :value="7" />
            <el-option label="15天" :value="15" />
            <el-option label="30天" :value="30" />
            <el-option label="90天" :value="90" />
            <el-option label="180天" :value="180" />
          </el-select>
        </el-form-item>
        <el-form-item v-if="form.utype == 2" label="未完善项" prop="module">
          <el-select
            v-model="form.module"
            multiple
            placeholder="不限"
            class="large"
          >
            <el-option
              v-for="item in options_module"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item v-if="form.utype == 1" label="职位状态">
          <el-radio-group v-model="form.publish_job">
            <el-radio :label="1">
              已发布
            </el-radio>
            <el-radio :label="0">
              未发布
            </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-divider content-position="left">发送设置</el-divider>
        <sendConfig ref="sendConfig" />
        <el-form-item label="">
          <el-button
            type="primary"
            :loading="submiting"
            @click="onSubmit('form')"
          >
            生成任务
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { getClassify } from '@/api/classify'
import sendConfig from './components/send_config.vue'
import { marketTaskAdd } from '@/api/market'

export default {
  components: {
    sendConfig
  },
  data() {
    return {
      submiting: false,
      options_module: [],
      data: {
        target: 'remind',
        title: '',
        content: '',
        send_type: [],
        condition: null
      },
      form: {
        no_login: -1,
        no_refresh: -1,
        module: [],
        publish_job: 0,
        utype: 1
      },
      rules: {}
    }
  },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      getClassify({
        type: 'resumeModule'
      })
        .then(response => {
          this.options_module = response.data
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const that = this
      that.$refs[formName].validate(valid1 => {
        if (valid1) {
          that.$refs.sendConfig.$refs['form'].validate(valid => {
            if (valid) {
              if (that.submiting === true) {
                return false
              }
              that.submiting = true
              const { title, content, send_type } = {
                ...that.$refs.sendConfig.form
              }
              that.data.title = title
              that.data.content = content
              that.data.send_type = send_type
              that.data.condition = { ...that.form }
              const insertData = { ...that.data }
              marketTaskAdd(insertData)
                .then(response => {
                  that.submiting = false
                  that.$message.success(response.message)
                  setTimeout(function() {
                    that.$router.push('/tool/market/list')
                  }, 1500)
                  return true
                })
                .catch(() => {
                  that.submiting = false
                })
            } else {
              return false
            }
          })
        } else {
          return false
        }
      })
    }
  }
}
</script>
<style scoped>
.large {
  width: 608px;
}
</style>
