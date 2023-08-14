<template>
  <div id="app">
    <Head>登录日志</Head>
    <div class="form_top_border"></div>
    <van-empty
      image="search"
      description="没有找到对应的数据"
      style="background-color:#fff"
      v-if="show_empty === true"
    />
    <van-list
      v-if="dataset.length > 0"
      v-model="loading"
      :finished="finished"
      :finished-text="finished_text"
      @load="onLoad"
      :immediate-check="true"
    >
      <div class="list_wrapper">
        <div class="list" v-for="(item, index) in dataset" :key="index">
          <div class="tx1">登录时间：{{ item.addtime | timeFilter }}</div>
          <div class="tx2">登录IP：{{ item.ip }}</div>
          <div class="tx3">{{ item.ip_addr }}</div>
          <div class="tx4">{{ item.platform_text }}</div>
        </div>
      </div>
    </van-list>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'LoginLog',
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}:{s}')
    }
  },
  data () {
    return {
      dataset: [],
      loading: false,
      finished: false,
      finished_text: '',
      show_empty: false,
      params: {
        page: 1,
        pagesize: 10
      }
    }
  },
  created () {
    this.fetchData(true)
  },
  methods: {
    fetchData (init) {
      if (init === true) {
        this.params.page = 1
        this.finished_text = ''
        this.finished = false
      }
      http
        .get(api.member_loginlog, this.params)
        .then(res => {
          if (init === true) {
            this.dataset = [...res.data.items]
          } else {
            this.dataset = this.dataset.concat(res.data.items)
          }
          // 加载状态结束
          this.loading = false
          // 数据全部加载完成
          if (res.data.items.length < this.params.pagesize) {
            this.finished = true
            if (init === false) {
              this.finished_text = '没有更多了'
            } else if (res.data.items.length == 0) {
              this.show_empty = true
            }
          }
        })
        .catch(() => {})
    },
    onLoad () {
      this.params.page++
      this.fetchData(false)
    }
  }
}
</script>

<style lang="scss" scoped>
.list_wrapper {
  .list {
    .tx4 {
      position: absolute;
      right: 16px;
      bottom: 19px;
      font-size: 12px;
      color: #999999;
    }
    .tx3 {
      position: absolute;
      right: 16px;
      top: 19px;
      font-size: 12px;
      color: #999999;
    }
    .tx2 {
      font-size: 13px;
      color: #666666;
      padding-right: 95px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .tx1 {
      margin-bottom: 8px;
      font-size: 13px;
      color: #666666;
      padding-right: 95px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    position: relative;
    width: 100%;
    background-color: #ffffff;
    padding: 15px 16px 15px;
    &:not(:last-child) {
      border-bottom: 1px solid #f3f3f3;
    }
  }
}
</style>
