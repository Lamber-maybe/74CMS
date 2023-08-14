<template>
  <div id="app">
    <Head>面试邀请</Head>
    <ScrollNav :list="navList"></ScrollNav>
    <van-dropdown-menu class="self_drop">
      <van-dropdown-item
        v-model="params.jobid"
        :options="options_job"
        @change="doSearch"
      />
      <van-dropdown-item
        v-model="params.settr"
        :options="options_settr"
        @change="doSearch"
      />
    </van-dropdown-menu>
    <div class="form_split_10"></div>
    <van-empty
      image="search"
      description="没有找到对应的信息"
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
        <div
          v-for="(item, index) in dataset"
          :key="index"
          @click="$router.push('/resume/' + item.resume_id)"
        >
          <div class="box_item">
            <div class="tx1">
              <div class="name">{{ item.fullname }}</div>
              <div class="right_txt">{{ item.addtime | timeFilter }}</div>
            </div>
            <div class="tx2">
              {{ item.age }}岁 · {{ item.sex_text }} ·
              {{ item.education_text }} ·
              {{ item.experience_text }}
              <div class="right_txt" v-if="item.is_look == 1">已查看</div>
              <div class="right_txt not_download" v-else>未查看</div>
            </div>
            <div class="tx3" @click.stop="handlerDetail(item)">
              <div class="name">职位名称：{{ item.jobname }}</div>
              <div class="time_txt">
                面试时间：{{ item.interview_time | interviewTimeFilter }}
              </div>
            </div>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
    </van-list>
    <van-dialog v-model="showDetail" title="面试邀请详情">
      <div class="interview_details_pop_box">
        <div class="detail_line">
          <div class="line_left">面试职位：</div>
          <div class="line_right">
            <span class="a_black">{{ detailInfo.jobname }}</span>
          </div>
          <div class="clear"></div>
        </div>
        <div class="detail_line">
          <div class="line_left">公司名称：</div>
          <div class="line_right">
            <span class="a_black">{{ detailInfo.companyname }}</span>
          </div>
          <div class="clear"></div>
        </div>
        <div class="detail_line">
          <div class="line_left">面试时间：</div>
          <div class="line_right">
            {{ detailInfo.interview_time | interviewTimeFilter }}
          </div>
          <div class="clear"></div>
        </div>
        <div class="detail_line">
          <div class="line_left">联系信息：</div>
          <div class="line_right">
            <span class="a_blue">{{ detailInfo.tel }}</span>
            ({{ detailInfo.contact }})
          </div>
          <div class="clear"></div>
        </div>
        <div class="detail_line">
          <div class="line_left">面试地址：</div>
          <div class="line_right">
            {{ detailInfo.address }}
          </div>
          <div class="clear"></div>
        </div>
        <div class="detail_line">
          <div class="line_left">备注信息：</div>
          <div class="line_right">
            {{ detailInfo.note == "" ? "无" : detailInfo.note }}
          </div>
          <div class="clear"></div>
        </div>
        <div class="detail_desc">
          温馨提示：招聘企业无权收取任何费用,面试求职中请加强自我保护,避免上当受骗！
        </div>
      </div>
    </van-dialog>
  </div>
</template>

<script>
import { formatTime, parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import ScrollNav from '@/components/ScrollNav'
export default {
  name: 'ResumeInterviewInvitation',
  components: {
    ScrollNav
  },
  filters: {
    timeFilter (timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d}', true)
    },
    interviewTimeFilter (timestamp) {
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
        jobid: 0,
        settr: '',
        page: 1,
        pagesize: 15
      },
      options_job: [],
      options_settr: [
        { text: '不限发送时间', value: '' },
        { text: '3天内', value: 3 },
        { text: '7天内', value: 7 },
        { text: '15天内', value: 15 },
        { text: '30天内', value: 30 }
      ],
      showDetail: false,
      detailInfo: {},
      navList: [
        { text: '收到投递', href: '/member/company/jobapply', active: false },
        { text: '我的下载', href: '/member/company/download', active: false },
        { text: '面试邀请', href: '/member/company/interview', active: true },
        { text: '视频面试', href: '/member/company/interview_video', active: false },
        { text: '我的收藏', href: '/member/company/fav', active: false },
        { text: '浏览记录', href: '/member/company/view_resume', active: false }
      ]
    }
  },
  created () {
    this.fetchData(true)
  },
  methods: {
    fetchData (init) {
      this.show_empty = false
      if (init === true) {
        this.params.page = 1
        this.finished = false
        this.finished_text = ''
      }
      http
        .get(api.company_interview_list, this.params)
        .then(res => {
          if (init === true) {
            let op_job = [...res.data.option_jobs]
            this.options_job = []
            this.options_job.push({ text: '全部职位', value: 0 })
            op_job.forEach(element => {
              this.options_job.push({
                text: element.jobname,
                value: element.id
              })
            })
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
            } else if (res.data.items.length === 0) {
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
    doSearch () {
      this.params.page = 1
      this.fetchData(true)
    },
    handlerDetail (item) {
      this.detailInfo = item
      this.showDetail = true
    }
  }
}
</script>

<style lang="scss" scoped>
.interview_details_pop_box {
  max-height: 300px;
  background: #ffffff;
  overflow-x: hidden;
  overflow-y: auto;
  font-size: 13px;
  padding: 20px;
  .detail_desc {
    width: 100%;
    background-color: #f5f5f5;
    line-height: 1.5;
    color: #666666;
    padding: 8px 20px;
    margin-top: 10px;
  }
  .detail_line {
    line-height: 1.5;
    margin-bottom: 5px;
    .line_left {
      float: left;
      color: #999999;
      text-align: right;
      width: 80px;
      line-height: 1.5;
    }
    .line_right {
      float: left;
      color: #333333;
      word-break: break-all;
      width: 190px;
      text-align: left;
      line-height: 1.5;
      .a_black {
        color: #333333;
        text-decoration: none;
      }
      .a_blue {
        color: #1887fb;
        text-decoration: none;
      }
    }
  }
}
.self_drop {
  &::after {
    border: none;
  }
}
.box_1 {
  width: 100%;
  .box_item {
    width: 100%;
    background-color: #ffffff;
    padding: 0 16px 20px 16px;
    .tx3 {
      width: 100%;
      border-radius: 8px;
      box-shadow: 0 0 5px #c2c2c2;
      position: relative;
      padding: 14px 0 12px;
      &::after{
        position: absolute;right: 17px;top: 31px;width: 7px;height: 7px;border-top: 1px solid #999999;
        border-right: 1px solid #999999;transform: rotate(45deg);content: "";
      }
      .name {
        font-size: 14px;
        color: #333333;
        font-weight: bold;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        max-width: 290px;
        padding: 0 0 0 41px;
        background: url("../../../assets/images/interview_name_ico.png") 18px
          2px no-repeat;
        background-size: 17px 16px;
        margin-bottom: 8px;
      }
      .time_txt {
        font-size: 12px;
        color: #999999;
        padding: 0 0 0 41px;
        .time {
          color: #1787fb;
        }
      }
    }
    .tx2 {
      font-size: 14px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      position: relative;
      padding-right: 60px;
      margin-bottom: 17px;
      .right_txt {
        position: absolute;
        right: 0;
        top: -1px;
        font-size: 12px;
        padding: 3px 8px;
        background-color: #fafafa;
        border-radius: 3px;
        color: #999999;
        &.not_download {
          color: #ff5d24;
          background-color: #fffbf8;
        }
      }
    }
    .tx1 {
      margin-bottom: 9px;
      position: relative;
      padding: 19px 0 0;
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
        top: 22px;
        font-size: 12px;
        color: #999999;
      }
    }
  }
}
</style>
