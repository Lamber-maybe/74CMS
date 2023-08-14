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
            v-if="item.alias == index_tpl"
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
import { tplIndex } from '@/api/tpl'
import { setConfig } from '@/api/configuration'

export default {
  data() {
    return {
      infoLoading: true,
      tpllist: [],
      index_tpl: ''
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
            index_tpl
          } = { ...response.data }
          this.index_tpl = index_tpl != '' ? index_tpl : 'def'
          return tplIndex({})
        })
        .then(response => {
          this.tpllist = response.data
        })
        .catch(() => { })
    },
    setTpl(alias) {
      const insertData = { index_tpl: alias }
      setConfig(insertData)
        .then(response => {
          this.$store.dispatch('config/getConfigInfo')
          this.$message.success('保存模板成功')
          this.index_tpl = alias
          return true
        })
        .catch(() => { })
    }
  }
}
</script>

<style lang="scss" scoped>
.el-card {
  width: 392px;
  float: left;
  margin-right: 20px;
  margin-bottom: 20px;
}
.item {
  width: 350px;
  height: 250px;
  .img {
    width: 350px;
    height: 200px;
    img {
      width: 350px;
      height: 200px;
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
