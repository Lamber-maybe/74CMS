<template>
  <div id="app">
    <Meta pagealias="noticelist" />
    <Head>公告列表</Head>
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
      finished-text="没有更多了"
      @load="onLoad"
      :immediate-check="true"
    >
      <div class="list_wrapper">
        <div
          @click="jumpTo(item)"
          v-for="(item, index) in dataset"
          :key="index"
          class="list_block"
        >
          <div class="title">
            {{ item.title }}
          </div>
          <div class="time">
            {{ item.addtime | timeFilter }}
            <div class="num">{{ item.click }}</div>
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
  name: 'Notice',
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
      show_empty: false,
      page: 1,
      pagesize: 15
    }
  },
  watch: {
    $route (to, from) {
      // 对路由变化作出响应...
      if (from.name != 'noticeShow' && to.name == 'noticeList') {
        this.fetchData(true)
      }
    }
  },
  beforeRouteLeave (to, from, next) {
    if (to.name == 'noticeShow') {
      if (!from.meta.keepAlive) {
        from.meta.keepAlive = true
      }
      next()
    } else {
      from.meta.keepAlive = false
      next()
    }
  },
  created () {
    this.fetchData(true)
  },
  methods: {
    fetchData (init) {
      this.show_empty = false
      if (init === true) {
        this.page = 1
        this.finished = false
        this.finished_text = ''
      }
      let conditions = {
        page: this.page,
        pagesize: this.pagesize
      }

      http
        .get(api.notice_list, conditions)
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
    jumpTo (item) {
      if (item.link_url == '') {
        this.$router.push('/notice/' + item.id)
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
    .time {
      .num {
        position: absolute;
        right: 0;
        top: 0;
        font-size: 12px;
        color: #999999;
        height: 16px;
        background: url("../assets/images/com_man_ic2.png") 0 3px no-repeat;
        background-size: 13px 10px;
        padding-left: 17px;
      }
      position: relative;
      padding-left: 17px;
      font-size: 12px;
      color: #999999;
      height: 16px;
      background: url("../assets/images/com_man_i3.png") 0 2px no-repeat;
      background-size: 12px;
    }
    .title {
      line-height: 1.6;
      word-break: break-all;
      font-size: 15px;
      color: #333333;
      margin-bottom: 5px;
    }
    width: 100%;
    position: relative;
    padding: 10px 0 8px;
    display: block;
    &:not(:last-child) {
      border-bottom: 1px solid #f3f3f3;
    }
  }
  background-color: #ffffff;
  padding: 0 17px;
}
</style>
