<template>
  <div id="app">
    <Head>我的订单</Head>
    <van-dropdown-menu>
      <van-dropdown-item
        v-model="params.service_type"
        :options="options_type"
        @change="handlerType"
      />
      <van-dropdown-item
        v-model="params.status"
        :options="options_status"
        @change="handlerStatus"
      />
    </van-dropdown-menu>
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
      <div class="box_1">
        <div v-for="(item, index) in dataset" :key="index">
          <div class="item" @click="handlerDetail(item)">
            <div class="tx1">
              <div class="content">
                订单号：{{ item.oid }}
                <div class="right_txt" v-if="item.status == 0">
                  <span class="not_pay">待付款</span>
                </div>
                <div class="right_txt" v-if="item.status == 1">
                  <span class="over">已完成</span>
                </div>
                <div class="right_txt" v-if="item.status == 2">
                  <span class="cancel">已取消</span>
                </div>
              </div>
            </div>
            <div
              class="tx2"
              :class="$store.state.LoginType == 2 ? ' personal' : ''"
            >
              <div class="content">
                <div
                  class="order_ico"
                  :class="
                    item.service_type +
                      ($store.state.LoginType == 2 ? ' personal' : '')
                  "
                ></div>
                <div class="name" v-if="$store.state.LoginType == 1">
                  {{ item.service_type_text }}
                </div>
                <div class="name" v-else>
                  {{ item.service_name }}
                </div>
                <div class="des" v-if="$store.state.LoginType == 1">
                  {{ item.service_name }}
                </div>
                <div class="des" v-else>专享简历推荐特权</div>
              </div>
            </div>
            <div class="tx3">
              <div class="content">
                实付金额：
                <span class="num_ico">￥</span>
                <span class="num">{{ item.amount }}</span>
              </div>
            </div>
            <van-button
              v-if="item.status == 0"
              class="self_btn now_pay"
              plain
              type="info"
              round
              size="small"
              @click.stop="handlerRepay(item)"
            >
              立即支付
            </van-button>
            <van-button
              v-if="item.status == 0"
              class="self_btn cancel_pay"
              color="#999999"
              plain
              type="info"
              round
              size="small"
              @click.stop="handlerCancel(item)"
            >
              取消订单
            </van-button>
            <van-button
              v-if="item.status == 2"
              class="self_btn del_pay"
              color="#999999"
              plain
              type="info"
              round
              size="small"
              @click.stop="handlerDel(item)"
            >
              删除订单
            </van-button>
            <van-button
              v-if="item.status == 1"
              class="self_btn detail_pay"
              color="#999999"
              plain
              type="info"
              round
              size="small"
            >
              查看详情
            </van-button>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
    </van-list>
    <van-popup v-model="showPayment" closeable position="bottom">
      <PopupPayment
        :amount="order_amount"
        @handlerSubmit="handlerSubmit"
      ></PopupPayment>
    </van-popup>
    <PaySubmit ref="paySubmit" :payment="payment" success-url="/member/order/list"></PaySubmit>
  </div>
</template>

<script>
import PaySubmit from '@/components/service/PaySubmit'
import PopupPayment from '@/components/service/PopupPayment'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'OrderList',
  props: ['utype'],
  components: {
    PaySubmit,
    PopupPayment
  },
  data () {
    return {
      showPayment: false,
      order_id: 0,
      order_amount: 0,
      payment: 'wxpay',
      dataset: [],
      loading: false,
      finished: false,
      finished_text: '',
      show_empty: false,
      params: {
        service_type: '',
        status: '',
        page: 1,
        pagesize: 15
      },
      options_type: [{ text: '全部类型', value: '' }],
      options_status: [{ text: '全部状态', value: '' }]
    }
  },
  watch: {
    $route (to, from) {
      // 对路由变化作出响应...
      if (this.utype == 1) {
        if (
          from.name != 'CompanyOrderDetail' &&
          to.name == 'CompanyOrderList'
        ) {
          this.fetchData(true)
        }
      } else {
        if (
          from.name != 'PersonalOrderDetail' &&
          to.name == 'PersonalOrderList'
        ) {
          this.fetchData(true)
        }
      }
    }
  },
  beforeRouteLeave (to, from, next) {
    if (to.name == 'CompanyOrderDetail' || to.name == 'PersonalOrderDetail') {
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
    this.fetchData(true)
  },
  methods: {
    fetchData (init) {
      this.show_empty = false
      if (init === true) {
        this.finished = false
        this.finished_text = ''
        this.params.page = 1
      }
      let url =
        this.utype == 1 ? api.company_orderlist : api.personal_orderlist
      http
        .get(url, this.params)
        .then(res => {
          if (init === true) {
            this.dataset = [...res.data.items]
            this.options_type = [{ text: '全部类型', value: '' }]
            res.data.filter_type.forEach(element => {
              this.options_type.push({
                value: element.value,
                text: element.label
              })
            })
            this.options_status = [{ text: '全部状态', value: '' }]
            res.data.filter_status.forEach(element => {
              this.options_status.push({
                value: element.value,
                text: element.label
              })
            })
          } else {
            this.dataset = this.dataset.concat(res.data.items)
          }
          // 加载状态结束
          this.loading = false

          // 数据全部加载完成
          if (res.data.items.length < this.params.pagesize) {
            this.finished = true
            if (init === false) {
              this.finished_text = '已加载全部订单'
            } else if (res.data.items.length == 0) {
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
    handlerType (type) {
      this.fetchData(true)
    },
    handlerStatus (status) {
      this.params.status = status
      this.fetchData(true)
    },
    handlerDetail (item) {
      this.$router.push('/member/order/' + item.id)
    },
    handlerRepay (item) {
      this.order_id = item.id
      this.order_amount = item.amount
      this.showPayment = true
    },
    handlerSubmit (payment) {
      this.payment = payment
      let url =
        this.$store.state.LoginType == 1
          ? api.company_repay
          : api.personal_repay
      this.$refs.paySubmit.handlerSubmit(url, { id: this.order_id, payment: this.payment }, function () {
        this.fetchData(true)
      })
    },
    payFinish () {
      this.fetchData(true)
    },
    handlerCancel (item) {
      let url =
        this.utype == 1 ? api.company_order_cancel : api.personal_order_cancel
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定取消该订单吗？'
        })
        .then(() => {
          http
            .post(url, { id: item.id })
            .then(res => {
              this.$notify({ type: 'success', message: res.message })
              this.fetchData(true)
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    handlerDel (item) {
      let url =
        this.utype == 1 ? api.company_order_del : api.personal_order_del
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定删除该订单吗？'
        })
        .then(() => {
          http
            .post(url, { id: item.id })
            .then(res => {
              this.$notify({ type: 'success', message: res.message })
              this.fetchData(true)
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.box_1 {
  .item {
    .content {
      padding: 0 15px;
    }
    .cancel_pay {
      position: absolute;
      right: 95px;
      bottom: 15px;
    }
    .now_pay,
    .detail_pay,
    .del_pay {
      position: absolute;
      right: 10px;
      bottom: 15px;
    }
    .self_btn {
      padding-right: 13px;
      padding-left: 13px;
    }
    .tx3 {
      .num {
        font-size: 15px;
        color: #ff6600;
      }
      .num_ico {
        font-size: 9px;
        color: #ff6600;
      }
      font-size: 14px;
      color: #666666;
      padding-bottom: 20px;
    }
    .tx2 {
      &.personal {
        padding-left: 95px;
      }
      .order_ico {
        &.personal {
          width: 80px;
          border-radius: 7px;
        }
        &.stick {
          background: url("../../assets/images/order_ico_top.png") 0 no-repeat;
          background-size: 80px 50px;
        }
        &.tag {
          background: url("../../assets/images/order_ico_tag.png") 0 no-repeat;
          background-size: 80px 50px;
        }
        &.points {
          background: url("../../assets/images/company_order_ico_jfcz.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.jlkjxz {
          background: url("../../assets/images/company_order_ico_jlkjxz.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.setmeal {
          background: url("../../assets/images/company_order_ico_tcsj.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.emergency {
          background: url("../../assets/images/company_order_ico_zwjj.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.jobstick {
          background: url("../../assets/images/company_order_ico_zwzd.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.refresh_job_package {
          background: url("../../assets/images/company_order_ico_zwznsx.png") 0
            no-repeat;
          background-size: 50px;
        }
        &.resume_package {
          background: url("../../assets/images/company_order_ico_jlzzb.png") 0
            no-repeat;
          background-size: 50px;
        }
        position: absolute;
        left: 15px;
        top: 10px;
        width: 50px;
        height: 50px;
        border-radius: 100%;
      }
      .des {
        padding: 0 10px 20px 0;
        font-size: 13px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .name {
        padding: 12px 10px 5px 0;
        font-size: 15px;
        color: #666666;
        font-weight: bold;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      position: relative;
      padding-left: 65px;
    }
    .tx1 {
      .right_txt {
        .over {
          color: #009900;
        }
        .cancel {
          color: #999999;
        }
        .time {
          color: #fe4737;
          font-size: 14px;
        }
        .not_pay {
          color: #ff5d24;
        }
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 12px;
      }
      position: relative;
      width: 100%;
      border-bottom: 1px solid #f3f3f3;
      font-size: 12px;
      color: #999999;
      padding: 12px 130px 12px 0;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    width: 100%;
    background-color: #ffffff;
    border-radius: 5px;
    position: relative;
  }
  width: 100%;
}
</style>
