<template>
  <div id="app" v-wechat-title="this.pageTitle">
    <Meta v-if="info.title!==undefined" pagealias="noticeshow" :custom_data="{title:info.title,seo_keywords:info.seo_keywords,seo_description:info.seo_description}" />
    <Head>公告详情</Head>
    <div class="form_top_border"></div>
    <div class="list_wrapper">
      <div class="list_block">
        <div class="title">
          {{ info.title }}
        </div>
        <div class="time">
          {{ info.addtime | timeFilter }}
          <div class="num">{{ info.click }}</div>
        </div>
      </div>
      <div class="notice_content editor-content-view">
        <span style="white-space: pre-line;" v-html="info.content"></span>
      </div>
    </div>
    <div class="jump_block">
      <div class="jump_content">
        <div class="form_split_10"></div>
        <router-link
          class="link"
          :to="!prev.link_url ? '/notice/' + prev.id : prev.link_url"
          v-if="prev !== null"
        >
          上一篇： {{ prev.title }}
        </router-link>
        <span class="link" v-else>上一篇： -</span>
        <router-link
          class="link next"
          :to="!next.link_url ? '/notice/' + next.id : next.link_url"
          v-if="next !== null"
        >
          下一篇： {{ next.title }}
        </router-link>
        <span class="link next" v-else>下一篇： -</span>
      </div>
    </div>
    <div class="form_split_10"></div>
    <Subscribe></Subscribe>
  </div>
</template>

<script>
import wxshare from '@/assets/js/share.js'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import Subscribe from '@/components/Subscribe'
export default {
  name: 'NoticeShow',
  components: {
    Subscribe
  },
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  data () {
    return {
      pageTitle: '',
      id: 0,
      info: {},
      prev: {},
      next: {}
    }
  },
  watch: {
    $route (to, from) {
      this.fetchData()
    }
  },
  created () {
    // 请求列表数据
    this.fetchData()
  },
  methods: {
    fetchData () {
      this.id = this.$route.params.id
      http
        .get(api.notice_show, { id: this.id })
        .then(res => {
          const { info, prev, next } = { ...res.data }
          this.info = info
          this.prev = prev
          this.next = next
          this.pageTitle =
            this.info.title + ' - ' + this.$store.state.config.sitename
          let wechatShareInfo = {
            title: info.title
          }
          wxshare(wechatShareInfo, 'noticeshow', location.href)
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.follow_block {
  .follow_content {
    .content {
      .logo {
        position: absolute;
        width: 45px;
        height: 45px;
        left: 17px;
        top: 24px;
        border: 0;
        font-size: 12px;
      }
      .follow_btn {
        position: absolute;
        right: 17px;
        top: 28px;
        padding: 7px 20px;
        font-size: 14px;
        color: #ffffff;
        line-height: 18px;
        background-color: #1787fb;
        border-radius: 32px;
      }
      .txt {
        font-size: 13px;
        color: #999999;
        line-height: 16px;
        padding-left: 75px;
      }
      .name {
        font-size: 17px;
        color: #333333;
        font-weight: bold;
        padding: 20px 0 8px 75px;
        line-height: 20px;
      }
      position: relative;
      width: 100%;
      height: 100%;
    }
    background-color: #ffffff;
    width: 100%;
    height: 88px;
  }
  position: relative;
  width: 100%;
  height: 88px;
}
.jump_block {
  .jump_content {
    .link {
      &.next {
        padding: 4px 17px 12px;
      }
      padding: 12px 17px 4px;
      font-size: 14px;
      color: #333333;
      line-height: 18px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      display: block;
    }
    background-color: #ffffff;
    width: 100%;
  }
  position: relative;
}
.notice_content {
  img {
    max-width: 100%;
  }
  line-height: 1.8;
  font-size: 14px;
  color: #333333;
  padding: 10px 0;
  word-break: break-all;
}
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
      font-size: 18px;
      color: #333333;
      margin-bottom: 7px;
      font-weight: bold;
    }
    width: 100%;
    position: relative;
    padding: 13px 0;
    display: block;
    border-bottom: 1px solid #f3f3f3;
  }
  background-color: #ffffff;
  padding: 0 17px;
}
</style>
