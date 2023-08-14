<template>
  <div id="app">
    <Head>管理职位</Head>
    <div class="inv-top-menu-bar">
      <div class="top-menu">
        <div class="top-menu-list">
          <span
            class="t-btn"
            :class="params.type == 0 ? 'active' : ''"
            @click="changeTab(0)"
          >
            发布中
          </span>
          <span
            class="t-btn"
            :class="params.type == 1 ? 'active' : ''"
            @click="changeTab(1)"
          >
            审核中
          </span>
          <span
            class="t-btn"
            :class="params.type == 2 ? 'active' : ''"
            @click="changeTab(2)"
          >
            已下线
          </span>
        </div>
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
      v-if="dataset.length > 0"
      v-model="loading"
      :finished="finished"
      :finished-text="finished_text"
      @load="onLoad"
      :immediate-check="true"
    >
      <div class="box_wrapper">
        <div v-for="(item, index) in dataset" :key="index">
          <div class="list">
            <div class="tx1" v-if="item.resume_nolook > 0" >
              <span @click="$router.push('/job/'+item.id)">{{ item.jobname }}</span>
              <div class="right_txt" @click="$router.push('/member/company/jobapply')">
                简历
                <span :class="item.resume_nolook > 0 ? 'not' : ''">
                  {{ item.resume_nolook }}
                </span>
                /{{ item.resume_all }}
              </div>
            </div>
            <div class="tx1" v-else>
              <span @click="$router.push('/job/'+item.id)">{{ item.jobname }}</span>
              <div class="right_txt" @click="$router.push('/member/company/jobapply')">
                简历
                <span :class="item.resume_nolook > 0 ? 'not' : ''">
                  {{ item.resume_nolook }}
                </span>
                /{{ item.resume_all }}
              </div>
            </div>
            <div class="tx2">
              <div class="ico apply">被投递{{ item.resume_all }}次</div>
              <div class="ico see">浏览{{ item.click }}次</div>
              <div class="ico time">{{ item.refreshtime }}更新</div>
              <div class="mark m1" v-if="item.job_status == 1">
                {{ item.job_status_cn }}
              </div>
              <div class="mark m0" v-if="item.job_status == 0">
                {{ item.job_status_cn }}
              </div>
              <div class="mark m2" v-if="item.job_status == 2">
                {{ item.job_status_cn }}
              </div>
              <div class="clear"></div>
            </div>
            <div
              class="tag"
              v-if="
                item.job_status == 1 &&
                  (item.stick == 1 ||
                    item.emergency == 1 ||
                    item.auto_refresh == 1)
              "
            >
              <div class="mark m1" v-if="item.stick == 1">已置顶</div>
              <div class="mark m2" v-if="item.emergency == 1">已急聘</div>
              <div class="mark m3" v-if="item.auto_refresh == 1">智能刷新</div>
              <div class="clear"></div>
            </div>
            <div
              class="tx4"
              v-if="
                item.is_display == 1 &&
                  item.job_status == 2 &&
                  item.audit_reason != ''
              "
            >
              说明：{{ item.audit_reason }}，请修改
            </div>
            <div class="tx3" v-if="params.type == 0">
              <div
                class="list_btn orange"
                v-if="
                  item.stick == 0 ||
                    item.emergency == 0 ||
                    item.auto_refresh == 0
                "
                @click="handlerShowIncrement(item)"
              >
                推广
              </div>
              <div class="list_btn" @click="handlerDisplay(item, index)">
                关闭
              </div>
              <div
                class="list_btn"
                @click="$router.push('/member/company/jobedit/' + item.id)"
              >
                修改
              </div>
              <div class="list_btn" @click="handlerRefresh(item)">刷新</div>
              <div class="clear"></div>
            </div>
            <div class="tx3" v-if="params.type == 1">
              <div
                class="list_btn"
                v-if="params.type == 1 || params.type == 2"
                @click="handlerDel(item, index)"
              >
                删除
              </div>
              <div
                class="list_btn"
                @click="$router.push('/member/company/jobedit/' + item.id)"
              >
                修改
              </div>
              <div class="clear"></div>
            </div>
            <div class="tx3" v-if="params.type == 2">
              <div
                class="list_btn"
                v-if="params.type == 1 || params.type == 2"
                @click="handlerDel(item, index)"
              >
                删除
              </div>
              <div
                class="list_btn"
                v-if="item.is_display == 1"
                @click="$router.push('/member/company/jobedit/' + item.id)"
              >
                修改
              </div>
              <div
                class="list_btn"
                v-if="item.is_display == 0"
                @click="handlerDisplay(item, index)"
              >
                恢复
              </div>
              <div class="clear"></div>
            </div>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
    </van-list>
    <div class="btn_row">
      <div class="btn_wrapper">
        <van-button type="info" block round @click="handlerJobadd">
          发布职位
        </van-button>
      </div>
    </div>
    <van-action-sheet
      v-model="showIncrement"
      :actions="options_increment"
      cancel-text="取消"
      close-on-click-action
      @cancel="showIncrement = false"
      @select="confirmIncrement"
    />
    <van-dialog
      v-model="showRefreshJobDirectService"
      title="刷新职位"
      show-cancel-button
      :confirm-button-text="refreshJobDirectServiceInfo.btnCn"
      @confirm="handlerRefreshDirectService"
    >
      <div class="dialog_tip_wrapper">
        <div
          class="tx1"
          v-if="refreshJobDirectServiceInfo.use_type == 'points'"
        >
          今日免费刷新职位次数已用完，本次刷新需要支付
          <span class="red">{{ refreshJobDirectServiceInfo.need_points }}</span>
          {{ $store.state.config.points_byname }}。
        </div>
        <div class="tx1" v-if="refreshJobDirectServiceInfo.use_type == 'money'">
          今日免费刷新职位次数已用完，本次刷新需要支付
          <span class="red">{{
            refreshJobDirectServiceInfo.need_expense
          }}</span>
          元。
        </div>
        <div
          class="tx2"
          v-if="parseInt(refreshJobDirectServiceInfo.discount) > 0"
        >
          购买智能刷新低至<span class="red">{{
            refreshJobDirectServiceInfo.discount
          }}</span
          >折，<router-link
            :to="
              '/member/order/add/common?type=service&service_type=job_refresh&jobid=' +
                refreshJobDirectServiceInfo.jobid
            "
            class="blue"
            >立即了解</router-link
          >
        </div>
      </div>
    </van-dialog>
    <van-popup v-model="showPayment" closeable position="bottom">
      <PopupPayment
        :amount="refreshJobDirectServiceInfo.need_expense"
        @handlerSubmit="handlerSubmitRefreshJob"
      ></PopupPayment>
    </van-popup>
  </div>
</template>

<script>
import { isWeiXin } from '@/utils/index'
import PopupPayment from '@/components/service/PopupPayment'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'JobList',
  components: {
    PopupPayment
  },
  data () {
    return {
      showPayment: false,
      showRefreshJobDirectService: false,
      refreshJobDirectServiceInfo: {},
      dataset: [],
      loading: false,
      finished: false,
      finished_text: '',
      show_empty: false,
      params: {
        type: 0,
        page: 1,
        pagesize: 10
      },
      showIncrement: false,
      options_increment: [],
      increment_jobid: 0,
      enable_poster: 0
    }
  },
  created () {
    this.fetchData(true)
  },
  methods: {
    handlerJobadd () {
      http
        .get(api.company_check_jobadd_num, {})
        .then(res => {
          if (res.data.enable_addjob_num <= 0) {
            this.$dialog
              .confirm({
                title: '系统提示',
                message:
                  '您当前是' +
                  res.data.setmeal_name +
                  '，当前可发布职位数为0，建议您立即升级套餐或将暂时不招聘的职位设为关闭！',
                confirmButtonText: '升级套餐',
                messageAlign: 'left'
              })
              .then(() => {
                this.$router.push('/member/order/add/common?type=setmeal')
              })
              .catch(() => {
                // on cancel
              })
            return false
          } else {
            this.$router.push('/member/company/jobadd')
          }
        })
        .catch(() => {})
    },
    fetchData (init) {
      this.show_empty = false
      if (init === true) {
        this.finished = false
        this.finished_text = ''
        this.params.page = 1
      }
      http
        .get(api.company_joblist, this.params)
        .then(res => {
          if (init === true) {
            this.dataset = [...res.data.items]
          } else {
            this.dataset = this.dataset.concat(res.data.items)
          }
          this.enable_poster = res.data.enable_poster
          // 加载状态结束
          this.loading = false

          // 数据全部加载完成
          if (res.data.items.length < this.params.pagesize) {
            this.finished = true
            if (init === false) {
              this.finished_text = '已加载全部职位'
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
    changeTab (type) {
      this.params.type = type
      this.fetchData(true)
    },
    handlerDel (item, index) {
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定删除该职位吗？'
        })
        .then(() => {
          http
            .post(api.company_job_del, { id: item.id })
            .then(res => {
              this.$notify({ type: 'success', message: res.message })
              this.dataset.splice(index, 1)
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerRefresh (item) {
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定刷新该职位吗？'
        })
        .then(() => {
          http
            .post(api.company_job_refresh, { id: item.id })
            .then(res => {
              if (res.data.done === 0) {
                this.showRefreshJobDirectService = true
                this.refreshJobDirectServiceInfo = {
                  use_type: res.data.use_type,
                  need_points: res.data.need_points,
                  need_expense: res.data.need_expense,
                  discount: res.data.discount,
                  jobid: item.id,
                  btnCn: res.data.use_type === 'points' ? '立即兑换' : '立即支付'
                }
                return false
              } else {
                item.refreshtime = '刚刚'
                this.$notify({ type: 'success', message: res.message })
              }
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerDisplay (item, index) {
      let is_display = item.is_display === 1 ? 0 : 1
      let is_display_text = is_display === 1 ? '恢复' : '关闭'
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定' + is_display_text + '该职位吗？'
        })
        .then(() => {
          http
            .post(api.company_job_set_display, { id: item.id, is_display })
            .then(res => {
              if (is_display === 0 || (is_display === 1 && item.audit !== 2)) {
                this.dataset.splice(index, 1)
              }
              this.$notify({ type: 'success', message: res.message })
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerShowIncrement (item) {
      this.options_increment = [
        { name: '职位置顶', value: 'jobstick', disabled: false },
        { name: '职位紧急', value: 'emergency', disabled: false },
        { name: '智能刷新', value: 'job_refresh', disabled: false }
      ]
      if (this.enable_poster == 1) {
        this.options_increment.push({ name: '微海报', value: 'microposte', disabled: false })
      }
      if (item.stick === 1) {
        this.options_increment[0].disabled = true
      }
      if (item.emergency === 1) {
        this.options_increment[1].disabled = true
      }
      if (item.auto_refresh === 1) {
        this.options_increment[2].disabled = true
      }
      this.showIncrement = true
      this.increment_jobid = item.id
    },
    confirmIncrement (action, index) {
      if (action.value === 'microposte') {
        this.$router.push(
          '/member/company/microposte?jobid=' +
          this.increment_jobid
        )
      } else {
        this.$router.push(
          '/member/order/add/common?type=service&service_type=' +
          action.value +
          '&jobid=' +
          this.increment_jobid
        )
      }
    },
    handlerRefreshDirectService () {
      if (this.refreshJobDirectServiceInfo.use_type === 'points') {
        this.handlerSubmitRefreshJob('points')
      } else {
        this.showPayment = true
      }
    },
    handlerSubmitRefreshJob (payment) {
      let openid = localStorage.getItem('weixinOpenid')
      let pay_data = {
        service_type: 'single_job_refresh',
        deduct_points:
          this.refreshJobDirectServiceInfo.use_type === 'points'
            ? this.refreshJobDirectServiceInfo.need_points
            : 0,
        payment,
        jobid: this.refreshJobDirectServiceInfo.jobid,
        return_url: this.$store.state.config.mobile_domain + 'member/company/joblist',
        openid: openid
      }
      http
        .post(api.company_pay_direct_service, pay_data)
        .then(res => {
          if (res.data.pay_status === 1) {
            this.$notify({ type: 'success', message: '支付成功' })
            this.fetchData(true)
            return false
          } else {
            this.handlerPay(res.data.parameter, payment)
          }
        })
        .catch(() => {})
    },
    handlerPay (parameter, payment) {
      if (payment === 'wxpay') {
        if (isWeiXin()) {
          let successUrl = this.$route.path
          let locationUrl = this.$store.state.config.mobile_domain + 'pay/jsapi?appId=' + parameter.jsApiParameters.appId + '&timeStamp=' + parameter.jsApiParameters.timeStamp + '&nonceStr=' + parameter.jsApiParameters.nonceStr + '&package=' + parameter.jsApiParameters.package + '&signType=' + parameter.jsApiParameters.signType + '&paySign=' + parameter.jsApiParameters.paySign + '&successUrl=' + successUrl
          window.location.href = locationUrl
        } else {
          window.location.href = parameter
        }
      } else {
        window.location.href = parameter
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.btn_row {
  .btn_wrapper {
    padding: 12px 27px;
    background-color: #ffffff;
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
  }
  position: relative;
  height: 68px;
}
.box_wrapper {
  .list {
    .tx4 {
      padding-bottom: 12.5px;
      font-size: 13px;
      color: #ff0000;
    }
    .tx3 {
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
      padding: 12px 0;
      text-align: right;
      position: relative;
      &::after {
        position: absolute;
        box-sizing: border-box;
        content: " ";
        pointer-events: none;
        right: 0;
        top: 0;
        left: 0;
        border-bottom: 1px solid #ebebeb;
        -webkit-transform: scaleY(0.5);
        transform: scaleY(0.5);
        z-index: 10;
      }
    }
    .tag {
      .mark {
        float: left;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 3px;
        &.m1 {
          background-color: #fafafa;
          color: #ff6600;
        }
        &.m2 {
          background-color: #fafafa;
          color: #ff4d3f;
        }
        &.m3 {
          background-color: #fafafa;
          color: #5199ff;
        }
        &:not(:first-child) {
          margin-left: 5px;
        }
      }
      padding-bottom: 15px;
    }
    .tx2 {
      .mark {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 3px;
        &.m1 {
          background-color: #f4f9ff;
          color: #1787fb;
        }
        &.m0 {
          background-color: #fffbf8;
          color: #ff7200;
        }
        &.m2 {
          background-color: #fff5f5;
          color: #ff0000;
        }
      }
      .ico {
        &.time {
          background: url("../../assets/images/com_man_i3.png") 0 center
            no-repeat;
          background-size: 10.5px;
          padding-right: 0;
        }
        &.see {
          background: url("../../assets/images/com_man_ic2.png") 0 center
            no-repeat;
          background-size: 13px 10px;
        }
        &.apply {
          background: url("../../assets/images/com_man_ic1.png") 0 center
            no-repeat;
          background-size: 11px 10px;
        }
        float: left;
        padding: 0 15px 0 17px;
        font-size: 12px;
        color: #666666;
      }
      position: relative;
      padding: 12px 3px;
    }
    .tx1 {
      .right_txt {
        .not {
          color: #ff0000;
        }
        position: absolute;
        right: 0;
        top: 21.5px;
        font-size: 12px;
        color: #333333;
        padding-right: 15px;
      }
      &::after {
        position: absolute;
        right: 5px;
        top: 27px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #999999;
        border-right: 1px solid #999999;
        transform: rotate(45deg);
        content: " ";
      }
      font-size: 16px;
      font-weight: bold;
      color: #333333;
      padding: 19.5px 80px 3px 0;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      position: relative;
    }
    width: 100%;
    background-color: #ffffff;
    padding: 0 15px;
  }
  width: 100%;
  //   padding-bottom: 68px;
}
.inv-top-menu-bar {
  background: #ffffff;
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
    display: flex;
    .t-btn {
      position: relative;
      white-space: nowrap;
      display: inline-block;
      text-align: center;
      color: #666666;
      flex: 1;
      font-size: 15px;
      text-decoration: none;
      padding: 12.5px 0;
      -webkit-tap-highlight-color: rgba(0, 0, 0, 0.3);
      &::after {
        position: absolute;
        left: 50%;
        bottom: 3px;
        width: 16px;
        height: 3px;
        background-color: #1787fb;
        border-radius: 100%;
        transform: translate(-50%, 0);
      }
      &.active {
        color: #1787fb;
        &::after {
          content: " ";
        }
      }
    }
  }
}
</style>
