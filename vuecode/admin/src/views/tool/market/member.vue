<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>指定会员发送</span>
      </div>
      <el-form
        ref="form"
        class="common-form"
        :model="form"
        :rules="rules"
        label-width="100px"
      >
        <el-form-item label="选择会员" prop="uid">
          <el-select
            v-model="form.uid"
            filterable
            remote
            reserve-keyword
            placeholder="请输入会员UID/手机号/企业名称"
            :remote-method="memberSearch"
            :loading="search_loading"
            class="large"
          >
            <el-option
              v-for="item in options_memberlist"
              :key="item.uid"
              :label="
                '【手机号：' +
                  item.mobile +
                  '】 / 【UID：' +
                  item.uid +
                  '】 / 【会员类型：' +
                  item.utype +
                  '】'
              "
              :value="item.uid"
            >
              <span style="float: left">
                【手机号：{{ item.mobile }}】 / 【UID：{{ item.uid }}】 /
                【会员类型：{{ item.utype }}】
              </span>
            </el-option>
          </el-select>
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
import sendConfig from './components/send_config.vue'
import { marketTaskAdd, marketSearchMember } from '@/api/market'

export default {
  components: {
    sendConfig
  },
  data() {
    return {
      search_loading: false,
      submiting: false,
      options_memberlist: [],
      form: {
        uid: '',
        target: 'uid',
        title: '',
        content: '',
        send_type: [],
        condition: null
      },
      rules: {
        uid: [
          {
            required: true,
            message: '请选择会员',
            trigger: 'change'
          }
        ]
      }
    }
  },
  created() {},
  methods: {
    memberSearch(query) {
      if (query !== '') {
        this.search_loading = true
        marketSearchMember({ keyword: query })
          .then(response => {
            this.options_memberlist = response.data.items
            this.search_loading = false
          })
          .catch(() => {})
      } else {
        this.options = []
      }
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
              that.form.title = title
              that.form.content = content
              that.form.send_type = send_type
              that.form.condition = { uid: that.form.uid }
              const insertData = { ...that.form }
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
