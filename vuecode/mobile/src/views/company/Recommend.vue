<template>
  <div id="app">
    <Head>智能匹配</Head>
    <van-empty
      image="search"
      description="您还未发布职位，暂时无法为您推荐简历"
      style="background-color:#fff"
      v-if="published_joblist.length === 0"
    />
    <div class="inv-top-menu-bar">
      <div class="top-menu">
        <div class="top-menu-list">
          <a
            class="t-btn "
            :class="item.id == currentJobItem.id ? 'active' : ''"
            v-for="(item, index) in published_joblist"
            :key="index"
            @click="onJobSelected(item)"
          >
            {{ item.jobname }}
          </a>
          <div class="clear"></div>
        </div>
      </div>
      <div class="handle_wrapper">
        <div class="choose" @click="showChoose"></div>
        <div class="search" @click="$router.push('/resumelist')"></div>
      </div>
    </div>
    <div class="form_split_10" v-if="published_joblist.length > 0"></div>
    <van-empty
      image="search"
      description="没有找到对应的数据"
      style="background-color:#fff"
      v-if="show_empty === true"
    />
    <van-list
      v-if="resumelist.length > 0"
      v-model="loading"
      :finished="finished"
      :finished-text="finished_text"
      @load="onLoad"
      :immediate-check="true"
    >
      <div class="box_1">
        <div
          v-for="(item, index) in resumelist"
          :key="index"
          @click="$router.push('/resume/' + item.id)"
        >
          <div class="list">
            <div class="up">
              <div class="avatar_box">
                <img :src="item.photo_img_src" :alt="item.fullname" />
                <div class="gender" :class="item.sex==1?'male':'female'"></div>
              </div>
              <div class="tx1">
                <div class="name">{{ item.fullname }}</div>
                <div class="level_ico" v-if="item.high_quality == 1"></div>
                <div class="clear"></div>
                <div class="wage">{{ item.intention_wage }}</div>
              </div>
              <div class="tx2">
                {{ item.age_text }}岁 · {{ item.experience_text }}经验 ·
                {{ item.education_text }}
                <div class="time">{{ item.refreshtime }}</div>
              </div>
            </div>
            <div class="tx3">
              想找
              <span>{{ item.intention_jobs }}</span>
              工作
            </div>
            <div class="tx3">
              想在
              <span>{{ item.intention_district }}</span>
              工作
            </div>
            <div class="tx4">{{ item.current_text }}</div>
            <div class="tag" v-if="item.service_tag != ''">
              {{ item.service_tag }}
            </div>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
    </van-list>
    <van-dialog
      v-model="showDetail"
      title="选择职位"
      show-cancel-button
      @confirm="confirmSel"
    >
      <div class="box_2">
        <van-radio-group v-model="selJobItemId">
          <div
            class="list"
            v-for="(item, index) in published_joblist"
            :key="index"
            @click="selJobItemId = item.id"
          >
            {{ item.jobname }}
            <van-radio :name="item.id" icon-size="15px"></van-radio>
          </div>
        </van-radio-group>
      </div>
    </van-dialog>
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
      showDetail: false,
      published_joblist: [],
      resumelist: [],
      currentJobItem: null,
      selJobItemId: '',
      page: 1,
      pagesize: 10
    }
  },
  watch: {
    $route (to, from) {
      // 对路由变化作出响应...
      if (from.name != 'resumeShow' && to.name == 'CompanyRecommend') {
        this.fetchDataRecommend(true)
      }
    }
  },
  beforeRouteLeave (to, from, next) {
    if (to.name == 'resumeShow') {
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
    this.fetchDataPublishedJoblist()
  },
  methods: {
    confirmSel () {
      for (const key in this.published_joblist) {
        if (this.published_joblist[key].id == this.selJobItemId) {
          this.currentJobItem = this.published_joblist[key]
          break
        }
      }
      this.fetchDataRecommend(true)
    },
    fetchDataPublishedJoblist () {
      http
        .get(api.get_publish_jobs, {})
        .then(res => {
          this.published_joblist = [...res.data.items]
          if (this.published_joblist.length > 0) {
            this.currentJobItem = { ...this.published_joblist[0] }
            this.selJobItemId = this.currentJobItem.id
            this.fetchDataRecommend(true)
          }
        })
        .catch(() => {})
    },
    fetchDataRecommend (init) {
      if (this.currentJobItem === null) {
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
        id: this.currentJobItem.id,
        page: this.page,
        pagesize: this.pagesize
      }
      http
        .get(api.recommend_resumelist, params)
        .then(res => {
          if (init === true) {
            this.resumelist = [...res.data.items]
          } else {
            this.resumelist = this.resumelist.concat(res.data.items)
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
      this.fetchDataRecommend(false)
    },
    onJobSelected (item) {
      this.currentJobItem = item
      this.selJobItemId = this.currentJobItem.id
      this.fetchDataRecommend(true)
    },
    showChoose () {
      this.showDetail = true
    }
  }
}
</script>

<style lang="scss" scoped>
.box_2 {
  .list {
    .van-radio {
      position: absolute;
      right: 0;
      top: 11px;
    }
    position: relative;
    font-size: 15px;
    color: #333333;
    padding: 7px 35px 7px 0;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  background: #ffffff;
  overflow-x: hidden;
  overflow-y: auto;
  font-size: 13px;
  padding: 10px 20px;
  max-height: 190px;
}
.box_1 {
  .list {
    .tag {
      padding: 3px 5px 3px 17px;
      border-radius: 3px;
      color: #ffffff;
      font-size: 10px;
      position: absolute;
      right: 17px;
      bottom: 18px;
      background: #ffa57d url("../../assets/images/fab_ico.svg") 5px center
        no-repeat;
      background-size: 10px;
    }
    .top {
      position: absolute;
      right: -25px;
      top: -25px;
      width: 50px;
      height: 50px;
      background-color: #feae41;
      color: #ffffff;
      font-weight: bold;
      text-align: center;
      transform: rotateZ(45deg);
      padding-top: 36px;
      font-size: 10px;
    }
    .tx4 {
      font-size: 13px;
      color: #999999;
      padding-right: 90px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      padding-bottom: 15px;
    }
    .tx3 {
      span {
        color: #666666;
      }
      font-size: 13px;
      color: #999999;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      padding-bottom: 10px;
    }
    .up {
      .tx2 {
        .time {
          position: absolute;
          right: 0;
          top: 7px;
          font-size: 13px;
          color: #999999;
        }
        position: relative;
        font-size: 15px;
        color: #666666;
        padding: 6px 0 13px;
      }
      .tx1 {
        .wage {
          position: absolute;
          right: 0;
          top: 24px;
          font-size: 14px;
          font-weight: bold;
          color: #ff5d24;
        }
        .level_ico {
          float: left;
          margin-left: 10px;
          width: 36px;
          height: 25px;
          background: url("../../assets/images/resume_list_level_ico.png") 0
            4px no-repeat;
          background-size: 36px 15px;
        }
        .name {
          float: left;
          font-size: 18px;
          font-weight: bold;
          color: #333333;
        }
        position: relative;
        padding-top: 21px;
      }
      .avatar_box {
        .gender {
          &.female {
            background: #ff8d65 url("../../assets/images/female_ico.svg") center
              no-repeat;
            background-size: 9px;
          }
          &.male {
            background: #4fa5fa url("../../assets/images/male_ico.svg") center
              no-repeat;
            background-size: 9px;
          }
          position: absolute;
          right: 0;
          bottom: 2px;
          width: 13px;
          height: 13px;
          border-radius: 100%;
        }
        img {
          width: 49px;
          height: 49px;
          border: 0;
          border-radius: 100%;
        }
        position: absolute;
        left: 0;
        top: 20px;
        width: 49px;
        height: 49px;
      }
      position: relative;
      padding-left: 61px;
    }
    position: relative;
    width: 100%;
    background-color: #ffffff;
    padding: 0 17px;
    overflow: hidden;
  }
  width: 100%;
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
    .choose {
      width: 35px;
      height: 100%;
      position: relative;
      display: inline-block;
      background: url("../../assets/images/choose_ico_blue.png") center
        no-repeat;
      background-size: 15px;
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
