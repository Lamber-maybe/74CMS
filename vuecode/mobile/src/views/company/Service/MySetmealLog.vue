<template>
  <div id="app">
    <Head>套餐明细</Head>
    <div class="form_split_10"></div>
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
        <div v-for="(item, index) in dataset" :key="index">
          <div class="list">
            <div class="tx1">{{ item.content }}</div>
            <div class="tx3">{{ item.addtime | timeFilter }}</div>
          </div>
          <div class="form_split_10"></div>
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
  name: 'SetmealLog',
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
        .get(api.company_setmeallog, this.params)
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
            } else if (res.data.items.length === 0) {
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
    .tx3 {
      padding-top: 10px;
      font-size: 13px;
      color: #999999;
    }
    .tx1 {
      padding-top: 15px;
      font-size: 14px;
      color: #333333;
      line-height: 22px;
    }
    width: 100%;
    padding: 0 17px 15px;
  }
  width: 100%;
  background-color: #ffffff;
}
</style>
