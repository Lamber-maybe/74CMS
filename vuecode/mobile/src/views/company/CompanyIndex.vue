<template>
  <div id="app" class="my_app">
    <Head :bg="'tran'">会员中心</Head>
    <van-pull-refresh v-model="pullLoading" @refresh="handlerPullRefresh">
      <template #pulling="props">
        <span :style="{'color':'#ffffff'}">下拉即可刷新...</span>
      </template>
      <!-- 释放提示 -->
      <template #loosing>
        <span :style="{'color':'#ffffff'}">释放即可刷新...</span>
      </template>
      <!-- 加载提示 -->
      <template #loading>
        <van-loading type="spinner" color="#ffffff" size="24px"><span :style="{'color':'#ffffff'}">加载中...</span></van-loading>
      </template>
      <div class="box_1">
        <div class="box_info" :class="top_loading === true ? 'wait' : ''">
          <van-skeleton avatar :row="3" :loading="top_loading">
            <div class="avatar_box" @click="handlerJump('/member/company/manage',1)">
              <img v-if="companyinfo.logo" :src="companyinfo.logo?companyinfo.logo:require('@/assets/images/empty_logo.jpg')" />
            </div>
            <div class="name" @click="handlerJump('/member/company/manage',1)">{{ companyinfo.companyname?companyinfo.companyname:"-" }}</div>
            <div class="some" @click="handlerJump('/member/company/manage',1)">
              {{ companyinfo.nature_text?(companyinfo.nature_text+' |'):'' }}{{ companyinfo.scale_text?(companyinfo.scale_text+' |'):'' }}{{ companyinfo.trade_text?companyinfo.trade_text:'' }}
            </div>
            <div class="auth not" v-if="companyinfo.bind_weixin === 0">
              微信未认证
              <div class="right_txt" @click="handlerBindWeixin">
                立即认证 >>
              </div>
            </div>
            <div class="auth" v-else>微信已认证</div>
          </van-skeleton>
        </div>
      </div>
      <div class="box_4">
        <div class="item" @click="handlerJobadd">
          <div class="ico ic1"></div>
          <div class="txt">发布职位</div>
        </div>
        <div class="item" @click="$router.push('/shortvideo/videoManage')" v-if="$store.state.config.shortvideo_enable === '1'">
          <div class="ico ic6"></div>
          <div class="txt">视频招聘</div>
        </div>
        <div class="item" @click="handlerRefreshBatch">
          <div class="ico ic2"></div>
          <div class="txt">一键刷新</div>
        </div>
        <div class="item" @click="handlerJump('/member/company/joblist',1)">
          <div class="ico ic3"></div>
          <div class="txt">职位管理</div>
        </div>
        <div class="item" @click="$router.push('/resumelist')">
          <div class="ico ic4"></div>
          <div class="txt">人才搜索</div>
        </div>
      </div>
      <div class="form_split_10"></div>
      <div class="box_3" @click="$router.push('/member/company/msglist')">
        <div class="title">通知</div>
        <ul :class="{ 'animate-up': animateUp }">
          <li v-for="(item, index) in message_list" :key="index">
            {{ item.content }}
          </li>
        </ul>
      </div>
      <div class="form_split_10"></div>
      <div
        class="box_2"
        v-if="companyinfo.company_audit == 0"
        @click="handlerJump('/member/company/auth',0)"
      >
        认证后的企业更容易获得求职者信任，招聘更快速！
        <div class="right_txt">去认证</div>
      </div>
      <div class="box_title">
        求职管理
        <div class="right_txt">
          简历量较少，试试
          <span class="for_link" @click="handlerJump('/member/order/add/common?type=service',1)">置顶</span>
        </div>
      </div>
      <div class="box_5">
        <div class="item" @click="handlerJump('/member/company/jobapply',1)">
          <div
            class="num"
            :class="manage.job_apply.red_point == 1 ? 'new' : ''"
          >
            {{ manage.job_apply.number }}
          </div>
          <div class="txt">收到投递</div>
        </div>
        <div class="item" @click="handlerJump('/member/company/download',1)">
          <div
            class="num"
            :class="manage.down_resume.red_point == 1 ? 'new' : ''"
          >
            {{ manage.down_resume.number }}
          </div>
          <div class="txt">我的下载</div>
        </div>
        <div class="item" @click="handlerJump('/member/company/interview',1)">
          <div
            class="num"
            :class="manage.interview.red_point == 1 ? 'new' : ''"
          >
            {{ manage.interview.number }}
          </div>
          <div class="txt">面试邀请</div>
        </div>
        <div class="item" @click="handlerJump('/member/company/fav',1)">
          <div class="num" :class="manage.fav.red_point == 1 ? 'new' : ''">
            {{ manage.fav.number }}
          </div>
          <div class="txt">我的收藏</div>
        </div>
        <div class="item" @click="handlerJump('/member/company/be_browsed',1)">
          <div class="num" :class="manage.view.red_point == 1 ? 'new' : ''">
            {{ manage.view.number }}
          </div>
          <div class="txt">谁看过我</div>
        </div>
      </div>
      <div class="box_title" v-if="setmeal">
        <van-skeleton :row="1" :loading="top_loading">
          {{ setmeal.name }}
          <span class="after_txt" v-if="setmeal.deadline == 0">(无限期)</span>
          <span class="after_txt" v-else>
            ({{ setmeal.deadline | timeFilter }}到期)
          </span>
        </van-skeleton>
        <div class="right_txt">
          <span
            class="for_link"
            @click="handlerJump('/member/order/add/common?type=setmeal',1)"
            >升级套餐</span
          >
        </div>
      </div>
      <div class="box_6">
        <div class="item" @click="handlerJump('/member/company/points',1)">
          <div class="ico ic1"></div>
          <div class="txt">我的{{ $store.state.config.points_byname }}</div>
        </div>
        <div
          class="item"
          @click="handlerJump('/member/order/add/common?type=service',1)"
        >
          <div class="ico ic2"></div>
          <div class="txt">增值服务</div>
        </div>
        <div
          class="item"
          @click="handlerJump('/member/order/add/common?type=setmeal',1)"
        >
          <div class="ico ic3"></div>
          <div class="txt">我的套餐</div>
        </div>
        <div class="item" @click="handlerJump('/member/company/coupon',1)">
          <div class="ico ic4"></div>
          <div class="txt">优惠券</div>
        </div>
        <div class="item" @click="handlerJump('/member/order/list',1)">
          <div class="ico ic5"></div>
          <div class="txt">我的订单</div>
        </div>
      </div>
      <div class="box_7">
        <div class="btn" @click="handlerJump('/member/company/mysetmeal',1)">
          <div class="btn_content">
            <span class="text">了解我的招聘特权</span>
          </div>
        </div>
      </div>
      <Ad
        v-if="ad_dataset_banner.items.length > 0"
        :dataset="ad_dataset_banner"
      ></Ad>
      <div class="form_split_10"></div>
      <div class="box_9">
        <div
          class="handle_line l1"
          @click="handlerJump('/member/company/manage',1)"
        >
          企业管理
        </div>
        <div
          class="handle_line l6"
          @click="handlerJump('/member/company/recommend',1)"
        >
          智能匹配
          <div class="tip_go"></div>
        </div>
        <div class="handle_line l2" @click="handlerJump('/im/imList',1)">
          我的职聊
          <span v-show="imUnreaded" class="point"></span>
        </div>
        <div
          class="handle_line l3"
          @click="$router.push('/member/company/customer_service')"
        >
          专属客服
        </div>
        <div
          class="handle_line l4"
          @click="$router.push('/member/company/account')"
        >
          账号管理
        </div>
      </div>
      <div class="form_split_10"></div>
      <div class="box_9 no_mb">
        <div class="handle_line l5" @click="showSwitchType = true">
          我要求职
        </div>
      </div>
      <div class="form_split_18"></div>
    </van-pull-refresh>
    <BottomNav></BottomNav>
    <van-popup
      v-model="showSwitchType"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <SwitchType
        @closeSwitchType="showSwitchType = false"
        :target_utype="2"
      ></SwitchType>
    </van-popup>
    <van-dialog v-model="showWeixinQrcode" title="微信认证" confirm-button-text="知道了">
      <div class="dialog_wx_qr">
        <div class="tip orange">截屏保存二维码图片，关注公众号</div>
        <div class="qr_img"><img :src="$store.state.config.wechat_qrcode"/></div>
        <div class="tip_text">微信内使用“扫一扫”打开图片，识别关注公众号完成绑定操作。绑定后即可随时随地接收最新消息通知，还可使用微信快速登录网站。</div>
      </div>
    </van-dialog>

    <!-- 微信二维码弹窗 start -->
    <WeChatQrcode ref="weChatQrcodeRef"></WeChatQrcode>
    <!-- 微信二维码弹窗 end -->
  </div>
</template>

<script>
import SwitchType from '@/components/account/SwitchType'
import {handlerHttpError} from '@/utils/error'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import Ad from '@/components/Ad'
import {mapState} from 'vuex'
import WeChatQrcode from '@/components/WeChatQrcode'
export default {
  name: 'CompanyIndex',
  components: {
    SwitchType,
    Ad,
    WeChatQrcode
  },
  filters: {
    timeFilter (timestamp) {
      return timestamp ? parseTime(timestamp, '{y}-{m}-{d}') : ''
    }
  },
  data () {
    return {
      pullLoading: false,
      top_loading: true,
      companyinfo: {},
      manage: {
        job_apply: {
          red_point: 0,
          number: 0
        },
        down_resume: {
          red_point: 0,
          number: 0
        },
        interview: {
          red_point: 0,
          number: 0
        },
        fav: {
          red_point: 0,
          number: 0
        },
        view: {
          red_point: 0,
          number: 0
        }
      },
      setmeal: {},
      showSwitchType: false,
      message_list: [],
      animateUp: false,
      timer: null,
      ad_dataset_banner: { alias: 'QS_member_company_banner', items: [] },
      empty_info: false,
      showWeixinQrcode: false,
      chatList: []
    }
  },
  computed: {
    ...mapState({
      imUnreaded: state => state.imUnreaded,
      imToken: state => state.imToken
    })
  },
  mounted () {
    this.timer = setInterval(this.scrollAnimate, 3000)
  },
  destroyed () {
    clearInterval(this.timer)
  },
  created () {
    this.fetchData()
    this.fetchAd()
    // if (this.imToken != '') {
    //   this.imWindowGlobal()
    // }
  },
  watch: {
    imToken (val) {
      this.imWindowGlobal()
    },
    chatList: {
      // 数据变化时执行的逻辑代码
      handler (val) {
        this.$store.state.imUnreaded = false
        val.forEach(item => {
          if (item.new > 0) {
            this.$store.state.imUnreaded = true
          }
        })
      },
      // 开启深度监听
      deep: true
    }
  },
  methods: {
    getChatList () {
      http.post(api.chatList, {token: this.imToken}).then((res) => {
        this.chatList = res.data.items
      })
    },
    /**
     * 全局检测聊天是否开启
     */
    imWindowGlobal () {
      http.post(api.im_window_global).then((res) => {
        if (res.data.next == '') {
          this.getChatList()
        }
      })
    },
    handlerBindWeixin () {
      if (this.companyinfo.bind_weixin === 1) {
        this.$dialog
          .confirm({
            title: '系统提示',
            message:
              '解绑后无法使用微信快速登录网站，是否确定解绑？'
          })
          .then(() => {
            http
              .post(api.unbind, {type: 'weixin'})
              .then(() => {
                this.fetchData()
                this.$notify({type: 'success', message: '解绑成功'})
              })
              .catch(() => {})
          })
          .catch(() => {
            // on cancel
          })
      } else {
        this.showWeixinQrcode = true
      }
    },
    handlerJump (path, checkAuth) {
      if (this.empty_info === true) {
        handlerHttpError({code: 50003, message: '请先完善企业资料'})
      } else {
        if (checkAuth === 1 && this.companyinfo.company_audit !== 1 && this.companyinfo.need_audit === 1) {
          handlerHttpError({code: 50004, message: '请先通过企业认证'})
        } else {
          var pathAry = path.split('/')
          if (pathAry[1] == 'im') {
            this.$store.state.imUnreaded = false
          }
          this.$router.push(path)
        }
      }
    },
    // 公告滚动
    scrollAnimate () {
      this.animateUp = true
      setTimeout(() => {
        this.message_list.push(this.message_list[0])
        this.message_list.shift()
        this.animateUp = false
      }, 500)
    },
    handlerPullRefresh () {
      this.top_loading = true
      this.fetchData(true)
    },
    fetchAd () {
      http
        .post(api.ad_list, {
          alias: ['QS_member_company_banner@mobile']
        })
        .then(res => {
          this.ad_dataset_banner.items =
            res.data.items['QS_member_company_banner@mobile']
        })
        .catch(() => {})
    },
    fetchData (is_refresh = false) {
      http
        .post(api.company_index, {})
        .then(res => {
          const { companyinfo, manage, setmeal, message_list } = {
            ...res.data
          }
          this.companyinfo = companyinfo
          this.manage = manage
          this.setmeal = setmeal
          this.message_list = message_list
          this.top_loading = false
          this.pullLoading = false
          if (is_refresh) {
            this.$toast('页面刷新成功')
          }
          if (companyinfo.district1 === 0) {
            this.empty_info = true
          } else {
            this.empty_info = false
          }
          if (companyinfo.notice_auth_complete == 1) {
            this.$dialog
              .confirm({title: '系统提示', message: '根据相关法律法规要求，需要您完善企业认证信息', confirmButtonText: '去完善', messageAlign: 'left'})
              .then(() => {
                this.$router.push('/member/company/auth')
              })
              .catch(() => {
                // on cancel
              })
          } else {
            /**
             * 【ID1000719】
             * 【新增】公众号引导弹窗场景（登录）
             * cy 2023-7-19
             */
            this.popupWechatQrcodeWindow('company_m_login', 1)
          }
        })
        .catch(() => {})
    },
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
    handlerRefreshBatch () {
      this.$dialog
        .confirm({
          title: '系统提示',
          message: '确定刷新所有发布中的职位吗？'
        })
        .then(() => {
          http
            .post(api.company_job_refresh_batch, {})
            .then(res => {
              if (res.data.done === 0) {
                this.$dialog
                  .confirm({
                    title: '系统提示',
                    message: res.message
                  })
                  .then(() => {
                    this.$router.push('/member/company/joblist')
                  })
                  .catch(() => {
                    // on cancel
                  })
                return false
              } else {
                this.$notify({ type: 'success', message: res.message })
                /**
                 * 【ID1000719】
                 * 【新增】公众号引导弹窗场景（刷新职位）
                 * cy 2023-7-19
                 */
                this.popupWechatQrcodeWindow('company_m_refresh_job', 3)
              }
            })
            .catch(() => {})
        })
        .catch(() => {
          // on cancel
        })
    },
    // 弹出微信二维码弹框
    popupWechatQrcodeWindow(val, type) {
      this.$refs.weChatQrcodeRef.handleOpen(val, type)
    }
  }
}
</script>

<style lang="scss" scoped>
.handle_line {
  position: relative;
  &.l7 {
    background: #ffffff url("../../assets/images/company_index_l7.png") 20px
    center no-repeat;
    background-size: 18px 18px;
  }
  &.l6 {
    background: #ffffff url("../../assets/images/company_index_l6.png") 23px
      center no-repeat;
    background-size: 10px 16px;
  }
  &.l5 {
    background: #ffffff url("../../assets/images/company_index_l5.png") 20px
      center no-repeat;
    background-size: 18px;
  }
  &.l4 {
    background: #ffffff url("../../assets/images/company_index_l4.png") 20px
      center no-repeat;
    background-size: 18px;
  }
  &.l3 {
    background: #ffffff url("../../assets/images/company_index_l3.png") 20px
      center no-repeat;
    background-size: 20px 18.5px;
  }
  &.l2 {
    background: #ffffff url("../../assets/images/company_index_l2.png") 20px
      center no-repeat;
    background-size: 17.5px;
  }
  &.l1 {
    background: #ffffff url("../../assets/images/company_index_l1.png") 20px
      center no-repeat;
    background-size: 18px 18.5px;
  }
  font-size: 16px;
  color: #333333;
  padding: 16.5px 0 16.5px 52px;
  position: relative;
  .tip_go {
    position: absolute;
    left: 124px;
    top: 50%;
    transform: translate(0, -50%);
    width: 21.5px;
    height: 14px;
    background: url("../../assets/images/personal_index_app_go.png") 0 no-repeat;
    background-size: 21.5px 14px;
  }
  .right_txt {
    position: absolute;
    right: 34px;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 14px;
    color: #c9c9c9;
  }
  &::before {
    content: " ";
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translate(0, -50%);
    width: 16px;
    height: 16px;
    background: url("../../assets/images/form_choose_arrow.svg") 0 no-repeat;
    background-size: 16px;
  }
  &:not(:last-child)::after {
    content: " ";
    position: absolute;
    pointer-events: none;
    right: 0;
    bottom: 0;
    left: 52px;
    border-bottom: 1px solid #f3f3f3;
  }
  .point{
    position: absolute;
    left: 115px;
    top: 13px;
    display: block;
    width: 7px;
    height: 7px;
    background: red;
    border-radius: 50%;
  }
}
.box_9 {
  width: 100%;
  background-color: #ffffff;
  &.no_mb {
    margin-bottom: 0;
  }
}
.box_7 {
  .btn {
    .btn_content {
      font-size: 12px;
      color: #ff813c;
      padding: 11.5px 0 11.5px 20px;
      //width: 100%;
      text-align: center;
      margin: 0 36px 0;
      .text{
        padding:0 15px;
        background: url("../../assets/images/company_index_box7_ic1.png") 0 center no-repeat;
        background-size: 12px 11.5px;

      }
    }
    width: 340px;
    margin: auto;
    background-color: #fff6f0;
    text-align: center;
    border-radius: 35px;
  }
  width: 100%;
  background-color: #ffffff;
  padding-bottom: 25px;
}
.box_6 {
  display: flex;
  width: 100%;
  background-color: #ffffff;
  padding: 5px 0 20px;
  .item {
    .txt {
      padding-top: 4.5px;
      font-size: 12px;
      color: #333333;
    }
    .ico {
      &.ic5 {
        background: url("../../assets/images/company_index_box6_ic5.png") center
          0 no-repeat;
        background-size: 20.5px;
      }
      &.ic4 {
        background: url("../../assets/images/company_index_box6_ic4.png") center
          1.5px no-repeat;
        background-size: 22px 17.5px;
      }
      &.ic3 {
        background: url("../../assets/images/company_index_box6_ic3.png") center
          1px no-repeat;
        background-size: 22.5px 19px;
      }
      &.ic2 {
        background: url("../../assets/images/company_index_box6_ic2.png") center
          1px no-repeat;
        background-size: 20px;
      }
      &.ic1 {
        background: url("../../assets/images/company_index_box6_ic1.png") center
          0 no-repeat;
        background-size: 22px 20.5px;
      }
      display: inline-block;
      width: 22.5px;
      height: 20.5px;
    }
    flex: 1;
    text-align: center;
  }
}
.box_5 {
  .item {
    .txt {
      padding-top: 12.5px;
      font-size: 12px;
      color: #999999;
    }
    .num {
      &.new::after {
        content: " ";
        position: absolute;
        right: -9px;
        top: -3px;
        width: 7px;
        height: 7px;
        border-radius: 100%;
        background-color: red;
      }
      position: relative;
      font-size: 18px;
      color: #333333;
      max-width: 36px;
      margin: 0 auto;
    }
    flex: 1;
    text-align: center;
  }
  display: flex;
  background-color: #ffffff;
  padding: 5px 0 15px;
  border-bottom: 1px solid #f5f5f5;
}
.box_title {
  .after_txt {
    font-size: 12px;
    color: #999999;
  }
  width: 100%;
  background-color: #ffffff;
  position: relative;
  padding: 17.5px 0 17.5px 18px;
  font-size: 18px;
  font-weight: bold;
  color: #333333;
  &::after {
    position: absolute;
    right: 16px;
    top: 27px;
    width: 6px;
    height: 6px;
    border-top: 1px solid #1787fb;
    border-right: 1px solid #1787fb;
    transform: rotate(45deg);
    content: " ";
  }
  .right_txt {
    .for_link {
      color: #1787fb;
    }
    position: absolute;
    right: 25px;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 12px;
    color: #999999;
  }
}
.box_4 {
  display: flex;
  width: 100%;
  background-color: #ffffff;
  padding-bottom: 17.5px;
  .item {
    .txt {
      font-size: 12px;
      color: #292b32;
    }
    .ico {
      &.ic6 {
        background: url("../../assets/images/shortvideo/icon_shortvideo.png") center
          0 no-repeat;
        background-size: 40px;
      }
      &.ic4 {
        background: url("../../assets/images/company_index_box4_ic4.png") 0
          no-repeat;
        background-size: 40px;
      }
      &.ic3 {
        background: url("../../assets/images/company_index_box4_ic3.png") 0
          no-repeat;
        background-size: 40px;
      }
      &.ic2 {
        background: url("../../assets/images/company_index_box4_ic2.png") 0
          no-repeat;
        background-size: 40px;
      }
      &.ic1 {
        background: url("../../assets/images/company_index_box4_ic1.png") 0
          no-repeat;
        background-size: 40px;
      }
      display: inline-block;
      width: 40px;
      height: 40px;
    }
    flex: 1;
    text-align: center;
  }
}
.box_1 {
  .box_info {
    &.wait {
      padding: 10px 15px 10px 15px;
    }
    .auth {
      .right_txt {
        position: absolute;
        right: 0;
        top: 0;
        font-size: 12px;
        color: #1787fb;
      }
      &.not {
        background: url("../../assets/images/wx_not_auth_ico.svg") 0 center
          no-repeat;
        background-size: 12px;
      }
      position: relative;
      padding-left: 18px;
      font-size: 12px;
      color: #666666;
      background: url("../../assets/images/wx_auth_ico.svg") 0 center no-repeat;
      background-size: 12px;
    }
    .some {
      font-size: 12px;
      color: #666666;
      padding: 11.5px 0;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .name {
      font-size: 17px;
      color: #333333;
      font-weight: bold;
      padding-top: 18.5px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .avatar_box {
      position: absolute;
      left: 16px;
      top: 19px;
      width: 70px;
      height: 70px;
      border: 1px solid #ededed;
      background-color: #ffffff;
      img {
        width: 68px;
        height: 68px;
        border: 0;
      }
    }
    position: relative;
    width: 340px;
    margin: 0 auto;
    border-radius: 7px;
    box-shadow: 0 0 5px #c2c2c2;
    background: #ffffff url("../../assets/images/company_box_info_bg.png") 0
      bottom no-repeat;
    padding: 0 15px 17.5px 100px;
    background-size: 340px 36px;
  }
  position: relative;
  width: 100%;
  padding-bottom: 20px;
}
.box_3 {
  width: 100%;
  background-color: #ffffff;
  position: relative;
  padding-left: 75px;
  height: 50px;
  overflow: hidden;
  &::after {
    position: absolute;
    right: 17px;
    top: 20.5px;
    width: 7px;
    height: 7px;
    border-top: 1px solid #999999;
    border-right: 1px solid #999999;
    transform: rotate(45deg);
    content: " ";
  }
  .title {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 11px;
    color: #ff6600;
    padding: 2px 10px;
    background-color: #fff5e8;
    border: 1px solid #ffaf6e;
    border-radius: 21px;
  }
  li {
    font-size: 13px;
    color: #333333;
    padding: 13.5px 22px 13.5px 0;
    width: 290px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  .animate-up {
    transition: all 0.5s ease-in-out;
    transform: translateY(-50px);
  }
}
.box_2 {
  width: 100%;
  font-size: 12px;
  color: #ff6600;
  position: relative;
  padding: 11px 0 11px 33px;
  background: #fffbeb url("../../assets/images/remind_ico.svg") 13px center
    no-repeat;
  background-size: 15px;
  .right_txt {
    position: absolute;
    right: 0;
    top: 50%;transform: translate(0,-50%);
    font-size: 12px;
    color: #1787fb;
    padding: 13px 17px;
  }
}
.my_app {
  background: #ffffff url("../../assets/images/company_index_top_bg.png") center
    0 no-repeat;
  background-size: 375px 148px;
}
</style>
