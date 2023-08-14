<template>
  <div id="app">
    <Head>求职管理</Head>
    <PersonalManageNav :list="navList"></PersonalManageNav>
    <div class="form_split_10"></div>
    <van-empty
      image="search"
      description="没有找到对应的信息"
      style="background-color:#fff"
      v-if="show_empty === true"
    />
    <van-list
      v-if="itemList.length > 0"
      v-model="loading"
      :finished="finished"
      :finished-text="finished_text"
      @load="onLoad"
      :immediate-check="true"
    >
      <div class="box_1">
        <div
          v-for="(item, index) in itemList"
          :key="index"
          @click="toDetail(item.jobid)"
        >
          <div class="box_item">
            <div class="tx1">
              <div class="name">{{ item.jobname }}</div>
              <div class="right_txt">{{ item.wage_text }}</div>
            </div>
            <div class="tx2">
              {{ item.education_text }} · {{ item.experience_text }} ·
              {{ item.district_text }}
            </div>
            <div class="tx4">
              <div class="name">{{ item.companyname }}</div>
              <div
                class="auth_ico"
                v-if="parseInt(item.company_audit) === 1"
              ></div>
              <div class="clear"></div>
            </div>
            <div class="tx3">
              {{ item.click }} 热度
              <div class="right_txt">{{ item.addtime | timeFilter }}查看</div>
            </div>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
    </van-list>
  </div>
</template>

<script>
import { formatTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import PersonalManageNav from '@/components/ScrollNav'
export default {
  name: 'ViewJob',
  components: {
    PersonalManageNav
  },
  filters: {
    timeFilter (timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d}', true)
    }
  },
  data () {
    return {
      page: 1,
      pagesize: 15,
      itemList: [],
      loading: false,
      finished: false,
      finished_text: '',
      show_empty: false,
      navList: [
        { text: '已申请', href: '/member/personal/jobapply', active: false },
        { text: '面试邀请', href: '/member/personal/interview', active: false },
        {
          text: '视频面试',
          href: '/member/personal/interview_video',
          active: false
        },
        {
          text: '被关注',
          href: '/member/personal/attention_me',
          active: false
        },
        { text: '足迹', href: '/member/personal/viewjob', active: true }
      ]
    }
  },
  created () {
    this.getData(true)
  },
  methods: {
    /**
     * 获取数据
     */
    getData (init) {
      this.show_empty = false
      if (init === true) {
        this.page = 1
        this.finished = false
        this.finished_text = ''
      }
      http
        .get(api.personal_manage_view_job, {
          page: this.page,
          pagesize: this.pagesize
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            if (init === true) {
              this.itemList = [...res.data.items]
            } else {
              this.itemList = this.itemList.concat(res.data.items)
            }
            // 加载状态结束
            this.loading = false

            // 数据全部加载完成
            if (res.data.items.length < this.pagesize) {
              this.finished = true
              if (init === false) {
                this.finished_text = '没有更多了'
              } else if (res.data.items.length === 0) {
                this.show_empty = true
              }
            }
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    /**
     * List初始化
     */
    onLoad () {
      this.page++
      this.getData(false)
    },
    /**
     * 跳转详情页
     * @param id 职位id
     */
    toDetail (id) {
      this.$router.push('/job/' + id)
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
    padding: 17px 16px 0;
    .tx4 {
      margin-bottom: 11px;
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
        background: url("../../../assets/images/tip_ico_auth.png") 0 3px
        no-repeat;
        background-size: 25px 13px;
      }
    }
    .tx3 {
      position: relative;
      width: 100%;
      border-top: 1px solid #fafafa;
      padding: 12px 105px 12px 19px;
      font-size: 12px;
      color: #999999;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      background: url("../../../assets/images/hot_ico.svg") 0 12px no-repeat;
      background-size: 16px;
      .right_txt {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 12px;
        color: #333333;
      }
    }
    .tx2 {
      font-size: 14px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      margin-bottom: 7px;
    }
    .tx1 {
      margin-bottom: 9px;
      position: relative;
      .name {
        font-size: 16px;
        color: #333333;
        font-weight: bold;
        max-width: 240px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .right_txt {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 15px;
        color: #ff5d24;
      }
    }
  }
}
.notice_bar {
  width: 100%;
  padding: 11px 0 11px 35px;
  font-size: 12px;
  color: #ff6600;
  background: #fffbeb url("../../../assets/images/remind_ico.svg") 16px center
    no-repeat;
  background-size: 16px;
}
</style>
