<template>
  <div id="app">
    <Meta pagealias="articlelist" :query_data="{article_cid:cid}" />
    <Head>新闻资讯</Head>
    <div class="inv-top-menu-bar">
      <div class="top-menu" ref="manageNav">
        <div class="top-menu-list">
          <div
            v-for="(item, index) in categorylist"
            :key="index"
            :class="item.id == cid ? 't-btn active' : 't-btn'"
            @click="changeCategory(item.id)"
          >
            {{ item.name }}
          </div>
        </div>
      </div>
    </div>
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
      finished-text="没有更多了"
      @load="onLoad"
      :immediate-check="true"
    >
      <div class="list_wrapper">
        <div
          @click="jumpTo(item)"
          class="list_block"
          v-for="(item, index) in dataset"
          :key="index"
        >
          <img :src="item.thumb" :alt="item.title" class="cover" />
          <div class="title">
            {{ item.title }}
          </div>
          <div class="some">
            <div class="form" :class="item.source == 1 ? 'rep' : ''">
              {{ item.source_text }}
            </div>
            <div class="time">{{ item.addtime | timeFilter }}</div>
            <div class="num">{{ item.click }}</div>
            <div class="clear"></div>
          </div>
        </div>
      </div>
    </van-list>
  </div>
</template>

<script>
import wxshare from '@/assets/js/share.js'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'News',
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data () {
    return {
      categorylist: [],
      dataset: [],
      loading: false,
      finished: false,
      show_empty: false,
      cid: 0,
      page: 1,
      pagesize: 15
    }
  },
  watch: {
    $route (to, from) {
      // 对路由变化作出响应...
      if (from.name != 'newsShow' && to.name == 'newsList') {
        // 将地址栏中的url参数初始化到参数对象中
        this.initQuery(to.params)
        this.fetchData(true)
      }
    }
  },
  created () {
    // 请求列表数据
    this.fetchCategoryData()
    wxshare({}, 'newslist', location.href)
  },
  methods: {
    initQuery (params) {
      this.cid = params.cid !== undefined ? params.cid : 0
      if (this.cid == 0) {
        this.cid = this.categorylist[0].id
      }
    },
    fetchCategoryData () {
      http
        .get(api.news_categorylist, {})
        .then(res => {
          this.categorylist = [...res.data.items]
          this.initQuery(this.$route.params)
          this.fetchData(true)
        })
        .catch(() => {})
    },
    fetchData (init) {
      this.show_empty = false

      let conditions = { cid: this.cid }
      if (init === true) {
        this.page = 1
        this.finished = false
        this.finished_text = ''
      }
      conditions.page = this.page
      conditions.pagesize = this.pagesize

      http
        .get(api.news_list, conditions)
        .then(res => {
          if (init === true) {
            this.dataset = [...res.data.items]
          } else {
            this.dataset = this.dataset.concat(res.data.items)
          }
          // 加载状态结束
          this.loading = false

          // 数据全部加载完成
          if (res.data.items.length < this.pagesize) {
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
      this.page++
      this.fetchData(false)
    },
    changeCategory (cid) {
      this.page = 1
      this.$router.push('/newslist/' + cid)
    },
    jumpTo (item) {
      if (item.link_url == '') {
        this.$router.push('/news/' + item.id)
      } else {
        window.location.href = item.link_url
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.list_wrapper {
  .list_block {
    .cover {
      position: absolute;
      left: 0;
      top: 13px;
      width: 88px;
      height: 66px;
      border: 0;
      font-size: 10px;
    }
    .some {
      .form {
        &.rep {
          color: #ff6600;
          background-color: #fff5e8;
        }
        float: left;
        line-height: 16px;
        padding: 0 6px;
        font-size: 11px;
        color: #1787fb;
        border-radius: 3px;
        background-color: #e7f3ff;
        margin-right: 10px;
      }
      .time {
        float: left;
        padding-left: 17px;
        font-size: 12px;
        color: #999999;
        height: 16px;
        background: url("../assets/images/com_man_i3.png") 0 2px no-repeat;
        background-size: 12px;
      }
      .num {
        float: left;
        font-size: 12px;
        color: #999999;
        height: 16px;
        margin-left: 10px;
        background: url("../assets/images/com_man_ic2.png") 0 3px no-repeat;
        background-size: 13px 10px;
        padding-left: 17px;
      }
    }
    .title {
      line-height: 1.6;
      word-break: break-all;
      font-size: 15px;
      color: #333333;
      margin-bottom: 6px;
      height: 48px;
      overflow:hidden;
    }
    width: 100%;
    position: relative;
    padding: 10px 0 10px 106px;
    display: block;
    height: 94px;
    &:not(:last-child) {
      border-bottom: 1px solid #f3f3f3;
    }
  }
  background-color: #ffffff;
  padding: 0 17px;
}
.inv-top-menu-bar {
  background: #ffffff;border-bottom: 1px solid #f3f3f3;
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
      padding: 12px 13px;
      -webkit-tap-highlight-color: rgba(0, 0, 0, 0.3);
      &::after {
        position: absolute;
        left: 50%;
        bottom: 3px;
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
