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
    <div class="notice_bar">视频面试房间仅在面试当天开启，在面试时间15天之后自动关闭</div>
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
            <div class="tx2">{{ item.age }}岁 · {{ item.sex_text }} ·
              {{ item.education_text }} ·
              {{ item.experience_text }}
              <div class="right_txt" v-if="item.is_look == 1">已查看</div>
              <div class="right_txt not_download" v-else>未查看</div>
            </div>
            <div class="tx3" @click.stop="handlerDetail(item)">
              <div class="name">职位名称：{{ item.jobname }}</div>
              <div class="time_txt">面试时间：{{ item.interview_time | interviewTimeFilter }}</div>
            </div>
            <div class="tx4">
              <div class="list_btn" v-if="item.room_status=='nostart'">房间未开启</div>
              <div class="list_btn" v-if="item.room_status=='overtime'">房间已关闭</div>
              <div class="list_btn orange" @click.stop="handlerNotice(item.id)" v-if="item.room_status=='opened'">提醒上线</div>
              <div class="list_btn" @click.stop="copyUrl(item.id)" v-if="item.room_status=='opened'">复制链接</div>
              <div class="list_btn" @click.stop="$router.push('/video/'+item.id)" v-if="item.room_status=='opened'">进入房间</div>
              <div class="clear"></div>
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
          <div class="line_left">备注信息：</div>
          <div class="line_right">
            请在正式面试前30分钟做好面试准备和设备调试，准时参加视频面试
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
  name: 'ResumeVideoInvitation',
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
      options_job: [{ text: '全部职位', value: 0 }],
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
        { text: '面试邀请', href: '/member/company/interview', active: false },
        { text: '视频面试', href: '/member/company/interview_video', active: true },
        { text: '我的收藏', href: '/member/company/fav', active: false },
        { text: '浏览记录', href: '/member/company/view_resume', active: false }
      ]
    }
  },
  created () {
    this.fetchData(true)
  },
  methods: {
    handlerNotice (id) {
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定提醒求职者吗？'
        })
        .then(() => {
          http
            .post(api.company_interview_video_notice, { id })
            .then(res => {
              this.$notify({
                type: 'success',
                message: '提醒成功'
              })
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    copyUrl (id) {
      var oInput = document.createElement('input')
      oInput.value = this.$store.state.config.mobile_domain + 'video/' + id
      document.body.appendChild(oInput)
      oInput.select() // 选择对象
      document.execCommand('Copy') // 执行浏览器复制命令
      oInput.className = 'oInput'
      oInput.style.display = 'none'
      this.$toast('复制成功')
    },
    fetchData (init) {
      this.show_empty = false
      if (init === true) {
        this.params.page = 1
        this.finished = false
        this.finished_text = ''
      }
      http
        .get(api.company_interview_video_list, this.params)
        .then(res => {
          if (init === true) {
            let options_job = [...res.data.option_jobs]
            options_job.forEach(element => {
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
    max-height: 300px; background: #ffffff; overflow-x: hidden; overflow-y: auto; font-size: 13px;
    padding: 20px;
    .detail_desc {
      width: 100%; background-color: #f5f5f5; line-height: 1.5; color: #666666; padding: 8px 20px; margin-top: 10px;
    }
    .detail_line {
      line-height: 1.5; margin-bottom: 5px;
      .line_left {
        float: left; color: #999999; text-align: right; width: 80px; line-height: 1.5;
      }
      .line_right {
        float: left; color: #333333; word-break: break-all; width: 190px; text-align: left; line-height: 1.5;
        .a_black {
          color: #333333; text-decoration: none;
        }
        .a_blue {
          color: #1887fb; text-decoration: none;
        }
      }
    }
  }
  .box_1 {
    width: 100%;
    .box_item {
      width: 100%; background-color: #ffffff; padding: 0 16px 20px 16px;
      .tx4 {
        .list_btn {
          float: right;
          font-size: 12px;
          padding: 4px 18px;
          background-color: #fff;
          border: 1px solid #e2e2e2;
          margin-left: 12px;
          border-radius: 26px;
          &.orange {
            border-color: #ff5d24;
            color: #ff5d24;
          }
        }
      }
      .tx3 {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 0 5px #c2c2c2;
        position: relative;
        padding: 14px 0 12px;
        margin-bottom: 17px;
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
  .notice_bar {
    width: 100%; padding: 11px 0 11px 35px; font-size: 12px; color: #ff6600;
    background: #fffbeb url("../../../assets/images/remind_ico.svg") 16px center no-repeat;
    background-size: 16px;
  }
</style>
