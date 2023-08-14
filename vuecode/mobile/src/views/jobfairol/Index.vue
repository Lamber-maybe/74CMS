<template>
  <div id="app">
    <Meta pagealias="jobfairollist" />
    <Head>网络招聘会</Head>
    <div class="b1"><img src="../../assets/images/jobfairol/1.png" alt=""></div>
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
      <div class="b2">
        <div class="b_item" v-for="(item,index) in dataset" :key="index" @click="toDetail(item.id)">
          <img class="img" :src="item.thumb_src" alt="">
          <div class="t1">
            <span class="st ing" v-if="item.score === 2">预定中</span>
            <span class="st not" v-else-if="item.score === 1">未开始</span>
            <span class="st over" v-else>已结束</span>
            {{item.title}}
          </div>
          <div class="t2">
            <span class="hd">参会企业</span><span class="hn">{{item.total_company}}</span>
            <span class="hd">参会个人</span><span class="hn nm">{{item.total_personal}}</span>
          </div>
          <div class="t3 substring">举办时间：{{item.starttime | timeFilter}} 至 {{item.endtime | timeFilter}}</div>
        </div>
      </div>
    </van-list>
    <div class="bm" @click="toReserve"></div>
  </div>
</template>

<script>
import wxshare from '@/assets/js/share.js'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'JobfairolIndex',
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
    wxshare({}, 'online_jobfairlist', location.href)
  },
  methods: {
    getJobfair: function (init) {
      var t = this
      http.get(api.jobfairol, t.params).then(r => {
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
    },
    toReserve: function () {
      this.$router.push('/jobfairol/reserve')
    }
  }
}
</script>

<style lang="scss" scoped>
  .bm {
    position: fixed;right: 15px;bottom: 55px;width: 45px;height: 45px;border-radius: 45px;
    background: rgba(0,0,0,0.5) url("../../assets/images/jobfairlist/6.png") center center no-repeat;
    background-size: 28px;
  }
  .b2 {
    width: 375px;padding: 0 17px;
    .b_item {
      .t3 {
        padding-bottom: 18px;font-size: 13px;color: #999;
      }
      .t2 {
        .hn {
          margin-right: 15px;font-size: 14px;color: #333;
          &.nm {
            margin-right: 0;
          }
        }
        .hd {
          margin-right: 6px;font-size: 13px;color: #666;
        }
        line-height: 20px;padding-left: 113px;margin-bottom: 7px;
      }
      .t1 {
        padding-left: 113px;font-size: 15px;color: #333;font-weight: bold;word-break: break-all;line-height: 1.7;
        height: 50px;
        width: 9rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        .st {
          padding: 1px 6px;font-size: 10px;border-radius: 18px;margin-right: 5px;vertical-align: middle;
          &.ing {
            color: #ff6363;border: 1px solid #ff6363;
          }
          &.not {
            color: #ff8800;border: 1px solid #ff8800;
          }
          &.over {
            color: #999;border: 1px solid #999;
          }
        }
      }
      .img {
        position: absolute;left: 0;top: 27px;width: 100px;height: 59px;border: 0;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
      }
      position: relative;padding: 15px 0 0 0;border-bottom: 1px solid #f3f3f3;
    }
  }
  .b1 {
    width: 375px;height: 150px;
    img {
      width: 375px;height: 150px;border: 0;
    }
  }
</style>
