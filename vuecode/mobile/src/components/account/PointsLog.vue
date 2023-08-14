<template>
  <div id="app">
    <Head>收支明细</Head>
    <div class="inv-top-menu-bar">
      <div class="top-menu">
        <div class="top-menu-list">
          <span
            class="t-btn"
            :class="params.type == 1 ? 'active' : ''"
            @click="changeTab(1)"
          >
            获得记录
          </span>
          <span
            class="t-btn"
            :class="params.type == 2 ? 'active' : ''"
            @click="changeTab(2)"
          >
            消耗记录
          </span>
        </div>
      </div>
    </div>
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
      <div class="box_1">
        <div class="item" v-for="(item, index) in dataset" :key="index">
          <div class="tx1">
            {{ item.content }}
            <div class="right_txt" :class="item.op == 2 ? 'con' : ''">
              {{ item.op == 1 ? "+" : "-" }}{{ item.points }}
            </div>
          </div>
          <div class="tx2">{{ item.addtime | timeFilter }}</div>
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
  name: 'PointsLog',
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
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
        type: 1,
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
        .get(api.member_pointslog, this.params)
        .then(res => {
          if (init === true) {
            this.show_empty = false
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
    },
    changeTab (type) {
      if (type == this.params.type) {
        return false
      }
      this.params.type = type
      this.fetchData(true)
    }
  }
}
</script>

<style lang="scss" scoped>
.box_1 {
  .item {
    .tx2 {
      font-size: 12.5px;
      color: #999999;
    }
    .tx1 {
      .right_txt {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 14px;
        color: #009900;
        font-weight: normal;
        &.con {
          color: #ff5d24;
        }
      }
      position: relative;
      padding: 0 50px 0 0;
      font-size: 16px;
      font-weight: bold;
      color: #333333;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      margin-bottom: 8px;
    }
    width: 100%;
    padding:16px 0 13px 0;
    &:not(:last-child) {
      border-bottom: 1px solid #f7f7f7;
    }
  }
  width: 100%;
  padding: 0 15px;
}
.inv-top-menu-bar {
  background: #ffffff;
  .top-menu {
    overflow: hidden;
    overflow-x: scroll;
    -webkit-overflow-scrolling: touch;
    &::-webkit-scrollbar {
      display: none;
    }
  }
  .top-menu-list {
    white-space: nowrap;
    .t-btn {
      position: relative;
      white-space: nowrap;
      display: inline-block;
      text-align: center;
      color: #666666;
      font-size: 15px;
      text-decoration: none;
      padding: 15px 0;
      width: 50%;
      -webkit-tap-highlight-color: rgba(0, 0, 0, 0.3);
      &::after {
        position: absolute;
        left: 50%;
        bottom: 2px;
        width: 16px;
        height: 3px;
        background-color: #1787fb;
        border-radius: 100%;
        transform: translate(-50%, 0);
      }
      &.active {
        color: #1787fb;
        &::after {
          content: " ";
        }
      }
    }
  }
}
</style>
