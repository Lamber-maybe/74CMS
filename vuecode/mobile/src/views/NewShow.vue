<template>
  <div id="app">
    <Meta v-if="info.title!==undefined" pagealias="articleshow" :custom_data="{title:info.title,seo_keywords:info.seo_keywords,seo_description:info.seo_description}" />
    <Head>资讯详情</Head>
    <div class="form_top_border"></div>
    <div class="list_wrapper">
      <div class="list_block">
        <div class="title">
          {{ info.title }}
        </div>
        <div class="some">
          <div class="form" :class="info.source == 1 ? 'rep' : ''">
            {{ info.source_text }}
          </div>
          <div class="time">{{ info.addtime | timeFilter }}</div>
          <div class="num">{{ info.click }}</div>
          <div class="clear"></div>
        </div>
      </div>
      <div class="notice_content editor-content-view editor-content-view-img">
        <span style="white-space: pre-line;" v-html="info.content"></span>
      </div>
      <div class="attach">
        <div class="attachItem" v-for="(item,index) in attachList" :key="index">
          <a class="downloadLink" :href="item.url" :download="item.url">
            <span class="text">附件{{ index + 1}}：</span>
            {{item.name}}
          </a>
        </div>
      </div>
    </div>
    <div class="jump_block">
      <div class="jump_content">
        <div class="form_split_10"></div>
        <router-link
          class="link"
          :to="!prev.link_url ? '/news/' + prev.id : prev.link_url"
          v-if="prev !== null"
        >
          上一篇： {{ prev.title }}
        </router-link>
        <span class="link" v-else>上一篇： -</span>
        <router-link
          class="link next"
          :to="!next.link_url ? '/news/' + next.id : next.link_url"
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
      id: 0,
      info: {},
      prev: {},
      next: {},
      attachList: []
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
        .get(api.news_show, { id: this.id })
        .then(res => {
          console.log(res)
          const { info, prev, next } = { ...res.data }
          this.info = info
          this.prev = prev
          this.next = next
          let wechatShareInfo = {
            title: this.info.title,
            imgUrl: this.info.thumb
          }
          let { attach_info } = info
          let {sitedomain} = this.$store.state.config
          attach_info.forEach((item) => {
            this.attachList.push({
              name: item.name,
              status: item.status,
              uid: item.uid,
              url: sitedomain + '/upload/' + item.url
            })
          })
          console.log(this.attachList)
          wxshare(wechatShareInfo, 'newsshow', location.href)
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
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
  overflow: hidden;
}
.attach{
  .attachItem {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 5px;
    .downloadLink{
      color:#FF6600;
      font-size:13px;
      .text{
        color: #333333;
        font-size: 13px;
      }
    }
    &:last-child{
      margin-bottom: 0;
    }
  }
}
.list_wrapper {
  .list_block {
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
  padding: 0 17px 26px;
}

</style>
<style>
.notice_content.editor-content-view.editor-content-view-img img {
  max-width: 100% !important;
}
</style>
