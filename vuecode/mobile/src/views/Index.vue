<template>
  <div id="app">
    <Meta pagealias="index" />
    <index_header
      v-if="moduleRule.header.is_display == 1"
      :plan_id="moduleRule.header.plan_id"
    ></index_header>
    <index_menu
      v-if="moduleRule.menu.is_display == 1"
      :plan_id="moduleRule.menu.plan_id"
      :dataset="menu_list"
    ></index_menu>
    <index_link_block
      v-if="moduleRule.section.is_display == 1"
      :plan_id="moduleRule.section.plan_id"
    ></index_link_block>
    <index_notice
      v-if="moduleRule.notice.is_display == 1"
      :plan_id="moduleRule.notice.plan_id"
      :dataset="notice_list"
    ></index_notice>
    <index_famous
      v-if="moduleRule.famous.is_display == 1"
      :plan_id="moduleRule.famous.plan_id"
      :dataset="famous_list"
    ></index_famous>
    <index_hotword
      v-if="moduleRule.hotword.is_display == 1"
      :plan_id="moduleRule.hotword.plan_id"
      :dataset="hotword_list"
    ></index_hotword>
    <Ad
      v-if="ad_dataset_banner_a.items.length > 0"
      :dataset="ad_dataset_banner_a"
    ></Ad>
    <index_joblist
      v-if="moduleRule.joblist.is_display == 1"
      :plan_id="moduleRule.joblist.plan_id"
    ></index_joblist>
    <Ad
      v-if="ad_dataset_banner_b.items.length > 0"
      :dataset="ad_dataset_banner_b"
    ></Ad>
    <index_article
      v-if="moduleRule.article.is_display == 1"
      :plan_id="moduleRule.article.plan_id"
      :dataset="article_list"
    ></index_article>
    <BottomNav></BottomNav>
    <!-- 弹窗 -->
    <IndexPopup v-if="showPopup" :list="ad_dataset_popup"></IndexPopup>
  </div>
</template>

<script>
import wxshare from '@/assets/js/share.js'
import http from '@/utils/http'
import api from '@/api'
import index_header from '@/components/index/Header'
import index_menu from '@/components/index/Menu'
import index_link_block from '@/components/index/components/LinkBlock'
import index_notice from '@/components/index/Notice'
import index_famous from '@/components/index/Famous'
import index_hotword from '@/components/index/Hotword'
import index_article from '@/components/index/Article'
import index_joblist from '@/components/index/JobList'
import Ad from '@/components/Ad'
import IndexPopup from '@/components/index/IndexPopup'
export default {
  name: 'Index',
  data () {
    return {
      moduleRule: {
        header: {
          alias: '',
          is_display: 0,
          plan_id: 0
        },
        article: {
          alias: '',
          is_display: 0,
          plan_id: 0
        },
        famous: {
          alias: '',
          is_display: 0,
          plan_id: 0
        },
        hotword: {
          alias: '',
          is_display: 0,
          plan_id: 0
        },
        joblist: {
          alias: '',
          is_display: 0,
          plan_id: 0
        },
        menu: {
          alias: '',
          is_display: 0,
          plan_id: 0
        },
        notice: {
          alias: '',
          is_display: 0,
          plan_id: 0
        },
        section: {
          alias: '',
          is_display: 0,
          plan_id: 0
        }
      },
      menu_list: [],
      notice_list: [],
      famous_list: [],
      hotword_list: [],
      article_list: [],
      swiperOption: {
        autoplay: true,
        freeMode: true,
        speed: 600
      },
      ad_dataset_top: { alias: 'QS_top_slide', items: [] },
      ad_dataset_banner_a: { alias: 'QS_index_banner_a', items: [] },
      ad_dataset_banner_b: { alias: 'QS_index_banner_b', items: [] },
      ad_dataset_popup: { alias: 'QS_index_popup', items: [] },
      showPopup: false
    }
  },
  components: {
    index_header,
    index_menu,
    index_link_block,
    index_notice,
    index_famous,
    index_hotword,
    index_article,
    index_joblist,
    IndexPopup,
    Ad
  },
  created () {
    this.initModule()
    this.fetchAd()
    wxshare({}, 'index', location.href.split('#')[0])
    this.cronRun()
  },
  methods: {
    initModule () {
      http
        .get(api.index_common, {})
        .then(res => {
          const { module_rule, data } = res.data
          this.moduleRule = { ...module_rule }
          const {
            menu_list,
            article_list,
            famous_list,
            hotword_list,
            notice_list
          } = data
          this.menu_list = menu_list
          this.notice_list = notice_list
          this.famous_list = famous_list
          this.hotword_list = hotword_list
          this.article_list = article_list
        })
        .catch(() => {})
    },
    fetchAd () {
      http
        .post(api.ad_list, {
          alias: [
            'QS_top_slide@mobile',
            'QS_index_banner_a@mobile',
            'QS_index_banner_b@mobile',
            'QS_index_popup@mobile'
          ]
        })
        .then(res => {
          this.ad_dataset_top.items = res.data.items['QS_top_slide@mobile']
          this.ad_dataset_banner_a.items =
            res.data.items['QS_index_banner_a@mobile']
          this.ad_dataset_banner_b.items =
            res.data.items['QS_index_banner_b@mobile']
          this.ad_dataset_popup.items =
            res.data.items['QS_index_popup@mobile']
          this.showPopupAdOrNot()
        })
        .catch(() => {})
    },
    showPopupAdOrNot () {
      let that = this
      let current = new Date()
      let expire_time = localStorage.getItem('index_popup_expire_time_mobile')
      if ((expire_time === undefined || !expire_time || current.getTime() > new Date(expire_time)) && this.ad_dataset_popup.items.length > 0) {
        setTimeout(() => {
          that.showPopup = true
          expire_time = new Date(new Date(new Date().toLocaleDateString()).getTime() + 24 * 60 * 60 * 1000 - 1)
          localStorage.setItem('index_popup_expire_time_mobile', expire_time)
        }, 500)
      }
    },
    cronRun () {
      http
        .get(api.cron_run)
        .then(() => {
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.ba {
  img {
    width: 100%;
    height: 63px;
  }
  width: 100%;
  height: 63px;
  margin-bottom: 9px;
}
</style>
