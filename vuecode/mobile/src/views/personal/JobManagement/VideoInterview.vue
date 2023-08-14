<template>
  <div id="app">
    <Head>求职管理</Head>
    <PersonalManageNav :list="navList"></PersonalManageNav>
    <van-dropdown-menu class="self_drop">
      <van-dropdown-item
        :title="lookTitle"
        v-model="look"
        :options="optionLook"
        @change="handleLook"
      />
      <van-dropdown-item
        :title="settrTitle"
        v-model="settr"
        :options="optionSettr"
        @change="handleSettr"
      />
    </van-dropdown-menu>
    <div class="notice_bar">视频面试房间仅在面试当天开启，面试时间15天之后自动关闭</div>
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
      <div v-for="(item, index) in itemList" :key="index">
        <div class="box_item">
          <div class="tx1">
            <div class="name">{{ item.jobname }}</div>
            <div class="right_txt">{{ item.wage_text }}</div>
          </div>
          <div class="tx2">{{ item.education_text }} · {{ item.experience_text }} · {{ item.nature_text }} <div class="right_txt">{{ item.addtime | timeFilter }}</div></div>
          <div class="tx3" @click="toShow(item)">
            <div class="name">公司名称：{{ item.companyname }}</div>
            <div class="time_txt">面试时间：{{ item.interview_time | interviewTimeFilter }}</div>
          </div>
          <div class="tx4">
            <van-button round size="small" type="default" @click.stop="$router.push('/video/'+item.id)" v-if="item.room_status=='opened'">进入房间</van-button>
            <van-button round size="small" type="default" @click.stop="copyUrl(item.id)" v-if="item.room_status=='opened'">复制链接</van-button>
            <van-button round size="small" plain color="#ff5d24" @click.stop="handlerNotice(item.id)" v-if="item.room_status=='opened'">提醒上线</van-button>
            <van-button round size="small" type="default" v-if="item.room_status=='overtime'">房间已关闭</van-button>
            <van-button round size="small" type="default" v-if="item.room_status=='nostart'">房间未开启</van-button>
          </div>
        </div>
        <div class="form_split_10"></div>
      </div>
    </div>
    </van-list>
    <van-dialog v-model="showDetail" title="面试邀请详情" show-cancel-button>
      <div class="interview_details_pop_box">
        <div class="detail_line">
          <div class="line_left">面试职位：</div>
          <div class="line_right">
            <router-link class="a_black" :to="`/job/${detailItem.jobid}`">{{
              detailItem.jobname
            }}</router-link>
          </div>
          <div class="clear"></div>
        </div>
        <div class="detail_line">
          <div class="line_left">公司名称：</div>
          <div class="line_right">
            <router-link class="a_black" :to="`/company/${detailItem.comid}`">{{
              detailItem.companyname
            }}</router-link>
          </div>
          <div class="clear"></div>
        </div>
        <div class="detail_line">
          <div class="line_left">面试时间：</div>
          <div class="line_right">{{ detailItem.interview_time|interviewTimeFilter }}</div>
          <div class="clear"></div>
        </div>
        <div class="detail_line">
          <div class="line_left">联系信息：</div>
          <div class="line_right">
            <span class="a_blue" @click="toCall(detailItem.tel)">{{
              detailItem.tel
            }}</span>
            ({{ detailItem.contact }})
          </div>
          <div class="clear"></div>
        </div>
        <div class="detail_line">
          <div class="line_left">面试地址：</div>
          <div class="line_right">{{ detailItem.address }}</div>
          <div class="clear"></div>
        </div>
        <div class="detail_line">
          <div class="line_left">备注信息：</div>
          <div class="line_right">请在正式面试前30分钟做好面试准备和设备调试，准时参加视频面试</div>
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
import PersonalManageNav from '@/components/ScrollNav'

export default {
  name: 'VideoInterview',
  components: {
    PersonalManageNav
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
      showDetail: false,
      detailItem: [],
      optionLook: [
        { text: '全部', value: '' },
        { text: '已查看', value: 1 },
        { text: '未查看', value: 0 }
      ],
      look: '',
      settr: '',
      lookTitle: '查看状态',
      settrTitle: '面试时间',
      optionSettr: this.$store.state.dropdownSettrOption,
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
          active: true
        },
        {
          text: '被关注',
          href: '/member/personal/attention_me',
          active: false
        },
        { text: '足迹', href: '/member/personal/viewjob', active: false }
      ]
    }
  },
  watch: {
    $route (to, from) {
      this.page = 1 // 条件改变将page重置为1
      this.getData(true)
    }
  },
  created () {
    this.getData(true)
  },
  methods: {
    handlerNotice (id) {
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定提醒企业吗？'
        })
        .then(() => {
          http
            .post(api.personal_manage_interview_video_notice, { id })
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
      var url = this.$store.state.config.mobile_domain + 'video/' + id
      var oInput = document.createElement('input')
      oInput.value = url
      document.body.appendChild(oInput)
      oInput.select() // 选择对象
      document.execCommand('Copy') // 执行浏览器复制命令
      oInput.className = 'oInput'
      oInput.style.display = 'none'
      this.$toast('复制成功')
    },
    // 查看状态
    handleLook (value) {
      this.doFilter()
    },
    // 选择时间
    handleSettr (value) {
      this.doFilter()
    },
    // 筛选
    doFilter () {
      this.page = 1 // 条件改变将page重置为1
      this.$router.push({
        path: '/member/personal/interview_video',
        query: {
          is_look: this.look,
          settr: this.settr,
          page: this.page,
          pagesize: this.pagesize
        }
      })
    },
    // 动态设置所选查看状态的title
    setLookText (value) {
      let thisLook = this.optionLook.filter(
        item => parseInt(item.value) === value
      )
      this.lookTitle = thisLook[0].text
    },
    // 动态设置所选时间的title
    setSettrText (value) {
      let thisSettr = this.optionSettr.filter(
        item => parseInt(item.value) === value
      )
      this.settrTitle = thisSettr[0].text
    },
    /**
     * 获取数据
     * @param isInit 是否为初始化
     */
    getData (isInit) {
      if (Object.keys(this.$route.query).length) {
        // 地址栏有参数，将参数与地址栏同步
        let parameter = this.$route.query
        this.look = parameter.is_look
        this.settr = parameter.settr
        this.pagesize = parameter.pagesize
      } else {
        // 地址栏无参数，将参数恢复默认值
        this.look = ''
        this.settr = ''
        this.pagesize = 15
      }
      this.show_empty = false
      if (isInit === true) {
        this.page = 1
        this.finished = false
        this.finished_text = ''
      }
      // 恢复已选条件
      if (this.look !== '') {
        this.setLookText(parseInt(this.look))
      } else {
        this.lookTitle = '查看状态'
      }
      if (this.settr !== '') {
        this.setSettrText(parseInt(this.settr))
      } else {
        this.settrTitle = '面试时间'
      }
      http
        .get(api.personal_manage_interview_video, {
          is_look: this.look,
          settr: this.settr,
          page: this.page,
          pagesize: this.pagesize
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            if (isInit === true) {
              this.itemList = [...res.data.items]
            } else {
              this.itemList = this.itemList.concat(res.data.items)
            }
            // 加载状态结束
            this.loading = false

            // 数据全部加载完成
            if (res.data.items.length < this.pagesize) {
              this.finished = true
              if (isInit === false) {
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
     * 获取对应的面试详情内容
     */
    toShow (item) {
      this.detailItem = item
      this.showDetail = true
      http
        .post(api.personal_manage_interview_video_setLook, {
          id: item.id
        })
    },
    /**
     * 拨打电话
     * @param tel
     */
    toCall (tel) {
      window.location.href = `tel://${tel}`
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
