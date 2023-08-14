<template>
  <div id="app">
    <Head>求职管理</Head>
    <PersonalManageNav :list="navList"></PersonalManageNav>
    <van-dropdown-menu class="self_drop">
      <van-dropdown-item
        :title="statusTitle"
        v-model="status"
        :options="optionStatus"
        @change="handleStatus"
      />
      <van-dropdown-item
        :title="settrTitle"
        v-model="settr"
        :options="optionSettr"
        @change="handleSettr"
      />
    </van-dropdown-menu>
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
              <div class="right_txt">{{ item.status_text }}</div>
            </div>
            <div class="tx3">
              {{ item.click }} 热度
              <div class="right_txt">{{ item.addtime | timeFilter }}申请</div>
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
  name: 'JobApply',
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
      status: '',
      settr: '',
      statusTitle: '投递反馈',
      settrTitle: '投递时间',
      optionStatus: [
        { text: '不限', value: 0 },
        { text: 'hr未查看', value: 1 },
        { text: 'hr已查看', value: 2 },
        { text: '同意面试', value: 3 },
        { text: '已被婉拒', value: 4 },
        { text: '停止招聘', value: 5 }
      ],
      optionSettr: this.$store.state.dropdownSettrOption,
      page: 1,
      pagesize: 15,
      itemList: [],
      loading: false,
      finished: false,
      finished_text: '',
      show_empty: false,
      navList: [
        { text: '已申请', href: '/member/personal/jobapply', active: true },
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
    // 选择反馈
    handleStatus (value) {
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
        path: '/member/personal/jobapply',
        query: {
          status: this.status,
          settr: this.settr,
          page: this.page,
          pagesize: this.pagesize
        }
      })
    },
    // 动态设置所选反馈状态的title
    setStatusText (value) {
      let thisStatus = this.optionStatus.filter(
        item => parseInt(item.value) === value
      )
      this.statusTitle = thisStatus[0].text
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
        this.status = parameter.status
        this.settr = parameter.settr
        this.pagesize = parameter.pagesize
      } else {
        // 地址栏无参数，将参数恢复默认值
        this.status = ''
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
      if (this.status !== '') {
        this.setStatusText(parseInt(this.status))
      } else {
        this.statusTitle = '投递反馈'
      }
      if (this.settr !== '') {
        this.setSettrText(parseInt(this.settr))
      } else {
        this.settrTitle = '投递时间'
      }
      http
        .get(api.personal_manage_job_apply, {
          status: this.status,
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
      position: relative;
      .name {
        font-size: 13px;
        color: #999999;
        float: left;
        max-width: 250px;
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
      .right_txt {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 13px;
        color: #333333;
      }
    }
    .tx3 {
      position: relative;
      width: 100%;
      border-top: 1px solid #fafafa;
      padding: 12px 80px 12px 19px;
      font-size: 12px;
      color: #999999;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      background: url("../../../assets/images/hot_ico.svg") 0 11px no-repeat;
      background-size: 16px;
      .right_txt {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 12px;
        color: #999999;
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
