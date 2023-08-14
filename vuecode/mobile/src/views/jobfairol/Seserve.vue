<template>
  <div id="app">
    <Head>网络招聘会已报名</Head>
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
      <div class="b1">
        <div class="b_item" v-for="(item,index) in dataset" :key="index" @click="toDetail(item.id)">
          <div class="t1">{{ item.title }}</div>
          <div class="t2 substring">举办时间：{{item.starttime | timeFilter}} 至 {{item.endtime | timeFilter}}
            <span class="st ing" v-if="item.audit === 0">审核中</span>
            <span class="st pass" v-else-if="item.audit === 1">已预定</span>
            <span class="st not" v-else>未通过</span>
          </div>
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
  name: 'JobfairolSeserve',
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{m}月{d}日 {h}:{i}')
    }
  },
  data () {
    return {
      dataset: [],
      params: {
        page: 1,
        pagesize: 15
      },
      show_empty: !1,
      loading: !1,
      finished: !1,
      finished_text: ''
    }
  },
  created () {
    this.getJobfair(!0)
  },
  methods: {
    getJobfair: function (init) {
      var t = this
      http.get(api.jobfairol_reserve_list, t.params).then(r => {
        console.warn(r.data.items)
        if (init === true) {
          this.dataset = [...r.data.items]
        } else {
          this.dataset = this.dataset.concat(r.data.items)
        }
        // 加载状态结束
        this.loading = false

        // 数据全部加载完成
        if (r.data.items.length < this.params.pagesize) {
          this.finished = true
          if (init === false) {
            this.finished_text = '没有更多了'
          } else if (r.data.items.length === 0) {
            this.show_empty = true
          }
        }
      }).catch(() => {})
    },
    onLoad: function () {
      this.params.page++
      this.getJobfair(!1)
    },
    toDetail: function (id) {
      this.$router.push('/jobfairol/' + id)
    }
  }
}
</script>

<style lang="scss" scoped>
  .b1 {
    width: 375px;border-top: 1px solid #f3f3f3;
    .b_item {
      .t2 {
        position: relative;padding: 3px 70px 3px 17px;font-size: 12px;color: #999;
        .st {
          position: absolute;right: 17px;top: 50%;transform: translate(0, -50%);font-size: 12px;
          &.ing {
            color: #ff5d24;
          }
          &.pass {
            color: #1787fb;
          }
          &.not {
            color: #f57878;
          }
        }
      }
      .t1 {
        padding: 0 17px;font-size: 16px;color: #333;font-weight: bold;word-break: break-all;line-height: 1.8;
        margin-bottom: 5px;
      }
      position: relative;padding: 15px 0 15px 0;border-bottom: 1px solid #f3f3f3;
    }
  }
</style>
