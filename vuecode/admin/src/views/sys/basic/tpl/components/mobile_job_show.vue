<template>
  <div class="app-container">
    <el-card v-for="(item, index) in tpllist" :key="index" shadow="hover">
      <div class="item">
        <div class="img">
          <img :src="item.thumb">
        </div>
        <div class="info">
          <span class="title">{{ item.title }}</span>
          <el-button
            v-if="item.alias == mobile_job_show_tpl"
            type="info"
            class="btn"
            size="small"
            disabled
          >使用中</el-button>
          <el-button
            v-else
            type="primary"
            class="btn"
            size="small"
            @click="setTpl(item.alias)"
          >使用模板</el-button>
          <div class="clearfix" />
        </div>
      </div>
    </el-card>
    <div class="clearfix" />
  </div>
</template>

<script>
import { showTpl } from '@/api/tpl'
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      tpllist: [],
      mobile_job_show_tpl: ''
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    }
  },
  mounted() { },
  created() {
    this.fetchInfo()
  },
  methods: {
    fetchInfo() {
      this.infoLoading = true
      const param = {}
      setConfig(param, 'get')
        .then(response => {
          const {
            mobile_job_show_tpl
          } = { ...response.data }
          this.mobile_job_show_tpl = mobile_job_show_tpl != '' ? mobile_job_show_tpl : 'def'
          return showTpl({ type: 'mobileJobShow' })
        })
        .then(response => {
          this.tpllist = response.data
        })
        .catch(() => { })
    },
    setTpl(alias) {
      const insertData = { mobile_job_show_tpl: alias }
      setConfig(insertData)
        .then(response => {
          this.$store.dispatch('config/getConfigInfo')
          this.$message.success('保存模板成功')
          this.mobile_job_show_tpl = alias
          return true
        })
        .catch(() => { })
    }
  }
}
</script>

<style lang="scss" scoped>
.el-card {
  width: 290px;
  float: left;
  margin-right: 20px;
  margin-bottom: 20px;
}
.item {
  width: 246px;
  height: 410px;
  .img {
    width: 246px;
    height: 360px;
    img {
      width: 246px;
      height: 360px;
    }
  }
  .info {
    padding: 18px 0;
    height: 30px;
    line-height: 30px;
    .title {
      color: #333;
      font-size: 16px;
      float: left;
      font-weight: bold;
    }
    .btn {
      float: right;
    }
  }
}
</style>
