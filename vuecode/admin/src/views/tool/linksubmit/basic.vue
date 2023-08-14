<template>
  <div class="app-container">
    <el-card class="box-card">
      <div
        slot="header"
        class="clearfix"
      >
        <span>URL推送</span>
      </div>
      <div class="tip">
        <p>
          全部数据将以历史数据新增，建议首次使用完整的提交1次。
        </p>
        <p>
          当天数据将以实时数据新增，不包括更新数据，只包含当天新增加的数据。
        </p>
        <p>
          断点会记录在浏览器本地缓存中，更新浏览器缓存后断点将消失。
        </p>
      </div>
      <el-form
        v-if="submit_loading === false"
        ref="form"
        class="common-form"
        :model="form"
        label-width="120px"
        :rules="rules"
        :inline-message="true"
      >
        <el-form-item label="准入密钥">
          <el-input v-model="form.linksubmit_token" />
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
      <el-divider
        v-if="submit_loading === false"
        content-position="left"
      >
        推送数据
      </el-divider>
      <div v-if="submit_loading === false">
        <div
          v-for="(item, index) in list"
          :key="index"
          style="margin-bottom:20px;margin-left:20px;"
        >
          <el-button @click="linkSubmit('today', item.type, 1, 0)">
            当天{{ item.type_name }}
          </el-button>
          <el-button @click="linkSubmit('all', item.type, 1, 0)">
            全部{{ item.type_name }}
          </el-button>
        </div>
      </div>
      <el-button
        v-if="submit_loading === false && has_cache"
        type="primary"
        style="margin-bottom:20px;margin-left:20px;"
        @click="
          linkSubmit(
            cache_info.range,
            cache_info.type,
            cache_info.current_page,
            cache_info.success,
          )
        "
      >
        断点推送
      </el-button>
      <div
        v-if="submit_loading === true"
        class="submiting-block"
      >
        <el-progress
          type="circle"
          :percentage="percentage"
          :stroke-width="18"
          :width="220"
        />
        <p
          style="font-weight:bold;font-size:20px;text-align:center;margin-bottom:50px;color:#333;"
        >
          正在推送 {{ tmp_tit }} 到百度
        </p>
        <p>
          数据总量：{{ submit_info.total }} 已经推送
          {{ submit_info.success }} 条
        </p>
        <p>
          每天推送上限为 {{ submit_info.max }} 条 还可以推送
          {{ submit_info.remain }} 条
        </p>
      </div>
    </el-card>
  </div>
</template>

<script>
import { setConfig } from '@/api/configuration'
import { submit } from '@/api/linksubmit'

export default {
  data() {
    return {
      tmp_tit: '',
      has_cache: false,
      cache_info: null,
      submit_loading: false,
      submit_info: {
        max: 0,
        total: 0,
        success: 0,
        remain: 0
      },
      percentage: 0,
      infoLoading: false,
      form: {
        linksubmit_token: ''
      },
      rules: {},
      list: [
        {
          type_name: '职位',
          type: 'job'
        },
        {
          type_name: '企业',
          type: 'company'
        },
        {
          type_name: '资讯',
          type: 'article'
        }
      ]
    }
  },
  created() {
    this.fetchData()
    const success = localStorage.getItem('linksubmit_success')
    const current_page = localStorage.getItem('linksubmit_current_page')
    const range = localStorage.getItem('linksubmit_range')
    const type = localStorage.getItem('linksubmit_type')
    if (success && current_page && range && type) {
      this.has_cache = true
      this.cache_info = {
        success,
        current_page,
        range,
        type
      }
    }
  },
  methods: {
    fetchData() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const { linksubmit_token } = { ...response.data }
          this.form.linksubmit_token = linksubmit_token
          this.infoLoading = false
        })
        .catch(() => {})
    },
    onSubmit(formName) {
      const insertData = { ...this.form }

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
    },
    linkSubmit(range, type, current_page, total_success) {
      this.tmp_tit = ''
      if (range == 'today') {
        this.tmp_tit += '今日'
      } else {
        this.tmp_tit += '全部'
      }
      if (type == 'job') {
        this.tmp_tit += '职位'
      } else if (type == 'company') {
        this.tmp_tit += '企业'
      } else {
        this.tmp_tit += '资讯'
      }
      const param = {
        type,
        range,
        page: current_page
      }
      submit(param)
        .then(response => {
          if (response.data.status == 1) {
            this.submit_loading = true
            this.submit_info.max = response.data.data.max
            this.submit_info.total = response.data.data.total
            this.submit_info.success =
              parseInt(total_success) + parseInt(response.data.data.success)
            this.submit_info.remain = response.data.data.remain
            current_page++
            this.percentage =
              (Number(this.submit_info.success) /
                Number(this.submit_info.total)) *
              100
            this.percentage = this.percentage.toFixed(2)
            this.percentage = Number(this.percentage)
            localStorage.setItem('linksubmit_current_page', current_page)
            localStorage.setItem('linksubmit_range', range)
            localStorage.setItem('linksubmit_type', type)
            localStorage.setItem('linksubmit_success', this.submit_info.success)
            this.linkSubmit(range, type, current_page, this.submit_info.success)
          } else {
            this.$alert(response.message, '提示', {
              confirmButtonText: '确定'
            })
          }
        })
        .catch(() => {})
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
