<template>
  <div id="app">
    <Head>智能匹配</Head>
    <div class="inv-top-menu-bar">
      <div class="top-menu">
        <div class="top-menu-list">
          <a
            class="t-btn "
            :class="item.id == currentIntentionItem.id ? 'active' : ''"
            v-for="(item, index) in intentionList"
            :key="index"
            @click="onIntentionSelected(item)"
          >
            {{ item.category_text }}
          </a>
          <div class="clear"></div>
        </div>
      </div>
      <div class="handle_wrapper">
        <div
          class="add"
          @click="$router.push('/member/personal/recommend_add')"
        ></div>
        <div class="search" @click="$router.push('/joblist')"></div>
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
      v-if="joblist.length > 0"
      v-model="loading"
      :finished="finished"
      :finished-text="finished_text"
      @load="onLoad"
      :immediate-check="false"
    >
      <div class="box_1">
        <div
          v-for="(item, index) in joblist"
          :key="index"
          @click="$router.push('/job/' + item.id)"
        >
          <div class="box_item">
            <div class="tx1">
              <div class="name">{{ item.jobname }}</div>
              <div class="top_ico" v-if="item.stick === 1"></div>
              <div class="urgent_ico" v-if="item.emergency === 1"></div>
              <div class="clear"></div>
              <div class="right_txt">{{ item.wage_text }}</div>
            </div>
            <div class="tx2">
              {{ item.education_text }} · {{ item.experience_text }} ·
              {{ item.district_text }}
              <div class="right_txt">{{ item.refreshtime }}</div>
            </div>
            <div
              class="tx3"
              v-if="
                item.tag_text_arr != undefined && item.tag_text_arr.length > 0
              "
            >
              <div class="tag_item" v-for="(tag, k) in item.tag_text_arr" :key="k">{{ tag }}</div>
              <div class="clear"></div>
            </div>
            <div class="tx4">
              <div class="name">{{ item.companyname }}</div>
              <div class="auth_ico" v-if="item.company_audit == 1"></div>
              <div class="clear"></div>
            </div>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
    </van-list>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'Recommend',
  data () {
    return {
      show_empty: false,
      loading: false,
      finished: false,
      finished_text: '',
      intentionList: [],
      joblist: [],
      currentIntentionItem: {},
      page: 1,
      pagesize: 10
    }
  },
  watch: {
    $route (to, from) {
      // 对路由变化作出响应...
      if (from.name != 'jobShow' && to.name == 'PersonalRecommend') {
        this.fetchDataRecommend(true)
      }
    }
  },
  beforeRouteLeave (to, from, next) {
    if (to.name == 'jobShow') {
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
    this.fetchDataIntention()
  },
  methods: {
    fetchDataIntention () {
      http
        .get(api.get_intentions, {})
        .then(res => {
          this.intentionList = [...res.data.items]
          if (this.intentionList.length > 0) {
            for (const key in this.intentionList) {
              this.intentionList[key].name = this.intentionList[key].category_text
            }
            this.currentIntentionItem = { ...this.intentionList[0] }
            this.fetchDataRecommend(true)
          }
        })
        .catch(() => {})
    },
    fetchDataRecommend (init) {
      if (this.currentIntentionItem === null) {
        this.show_empty = true
        this.finished = true
        this.finished_text = ''
        return false
      }
      this.show_empty = false
      if (init === true) {
        this.page = 1
        this.finished = false
        this.finished_text = ''
      }
      let params = {
        id: this.currentIntentionItem.id,
        page: this.page,
        pagesize: this.pagesize
      }
      http
        .get(api.recommend_joblist, params)
        .then(res => {
          if (init === true) {
            this.joblist = [...res.data.items]
          } else {
            this.joblist = this.joblist.concat(res.data.items)
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
    onIntentionSelected (item) {
      this.currentIntentionItem = item
      this.fetchDataRecommend(true)
    },
    onLoad () {
      this.page++
      this.fetchDataRecommend(false)
    }
  }
}
</script>

<style lang="scss" scoped>
.box_1 {
  width: 100%;
  .box_item {
    width: 100%;
    background-color: #ffffff;
    padding: 17px 16px 10px;
    .tx4 {
      .name {
        font-size: 13px;
        color: #999999;
        float: left;
        max-width: 310px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .auth_ico {
        float: left;
        margin-left: 7px;
        width: 25px;
        height: 18px;
        background: url("../../assets/images/tip_ico_auth.png") 0 3px
          no-repeat;
        background-size: 25px 13px;
      }
    }
    .tx3 {
      margin-bottom: 5px;
      .tag_item{
        float: left;font-size: 11px;color: #1787fb;background-color: #edf6ff;padding: 3px 7px;border-radius: 3px;
        margin-bottom: 5px;
        &:not(:first-child){margin-left: 5px;}
      }
    }
    .tx2 {
      font-size: 14px;
      color: #666666;
      padding: 0 80px 0 0;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      position: relative;
      margin-bottom: 10px;
      .right_txt {
        position: absolute;
        right: 0;
        top: 2px;
        font-size: 12px;
        color: #999999;
      }
    }
    .tx1 {
      margin-bottom: 9px;
      position: relative;
      .name {
        font-size: 16px;
        color: #333333;
        font-weight: bold;
        float: left;
        max-width: 240px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .top_ico {
        float: left;
        margin-left: 7px;
        width: 38px;
        height: 22px;
        background: url("../../assets/images/tip_ico_top.png") 0 4px
          no-repeat;
        background-size: 38px 15px;
      }
      .urgent_ico {
        float: left;
        margin-left: 7px;
        width: 38px;
        height: 22px;
        background: url("../../assets/images/tip_ico_urgent.png") 0 4px
          no-repeat;
        background-size: 38px 15px;
      }
      .right_txt {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 15px;
        color: #ff5d24;
        font-weight: bold;
      }
    }
  }
}
.inv-top-menu-bar {
  background: #ffffff;
  position: relative;
  .handle_wrapper {
    .search {
      width: 35px;
      height: 100%;
      position: relative;
      display: inline-block;
      background: url("../../assets/images/search_ico_blue.svg") center
        no-repeat;
      background-size: 14px;
      &::after {
        content: " ";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translate(0, -50%);
        width: 1px;
        height: 15px;
        background-color: #f3f3f3;
      }
    }
    .add {
      width: 35px;
      height: 100%;
      position: relative;
      display: inline-block;
      background: url("../../assets/images/add_ico_blue.svg") center no-repeat;
      background-size: 16px;
    }
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    background-color: #ffffff;
    z-index: 3;
    box-shadow: -1px 0 20px rgba(255, 255, 255, 0.7);
  }
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
      padding: 12px 16.5px;
      -webkit-tap-highlight-color: rgba(0, 0, 0, 0.3);
      &::after {
        position: absolute;
        left: 50%;
        bottom: 0;
        width: 16px;
        height: 3px;
        background-color: #1787fb;
        border-radius: 100%;
        transform: translate(-50%, 0);
      }
      &.active {
        color: #1787fb;
      }
      &:last-child {
        margin-right: 60px;
      }
    }
  }
}
</style>
