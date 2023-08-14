<template>
  <div id="app" class="my_app">
    <Head :bg="'tran'">会员中心</Head>
    <van-pull-refresh v-model="pullLoading" @refresh="handlerPullRefresh">
      <template #pulling="props">
        <span :style="{'color':'#FFFFFF'}">下拉即可刷新...</span>
      </template>
      <!-- 释放提示 -->
      <template #loosing>
        <span :style="{'color':'#FFFFFF'}">释放即可刷新...</span>
      </template>
      <!-- 加载提示 -->
      <template #loading>
        <van-loading type="spinner" color="#FFFFFF" size="24px"><span :style="{'color':'#FFFFFF'}">加载中...</span></van-loading>
      </template>
    <div class="top_box">
      <div class="avatar_wrapper">
        <img :src="(basic && basic.photo_img_src)?basic.photo_img_src:require('@/assets/images/empty_photo.png')" />
      </div>
      <div class="user_name">
        {{ (basic && basic.fullname)?basic.fullname:'未注册' }}
        <div class="percent">{{ (basic && basic.complete_percent)?basic.complete_percent:0 }}%</div>
      </div>
      <div class="current_txt">{{ basic ? basic.current_text : '' }}</div>
    </div>
    <div class="box_1">
      <div class="box_item" @click="refreshResume">
        <div class="item t1">刷新简历</div>
      </div>
      <div class="box_item" @click="handlerJump('/member/personal/preview')">
        <div class="item t2">预览简历</div>
      </div>
      <div class="box_item" @click="handlerJump('/member/personal/resume')">
        <div class="item t3">编辑简历</div>
      </div>
    </div>
    <div class="form_split_10"></div>
    <div class="box_2" v-if="basic && parseInt(basic.is_display) === 0">
      您的简历当前不公开，企业无法搜到到您的简历哦！
      <router-link class="right_txt" to="/member/personal/privacy">
        去开启
      </router-link>
    </div>
    <div class="form_split_10" v-if="basic && parseInt(basic.is_display) === 0"></div>
    <div class="box_3" @click="$router.push('/member/personal/msglist')">
      <div class="title">通知</div>
      <ul :class="{ 'animate-up': animateUp }">
        <li v-for="(item, index) in message_list" :key="index">
          {{ item.content }}
        </li>
      </ul>
    </div>
    <div class="form_split_10"></div>
    <div class="box_title">
      求职管理
      <div class="right_txt">完整度高的简历更容易获得企业青睐</div>
    </div>
    <div class="box_4">
      <div class="box_item" @click="handlerJump('/member/personal/resume')">
        <div class="ico_box b1"></div>
        <div>我的简历</div>
      </div>
      <div class="box_item" @click="handlerJump('/member/personal/jobapply')">
        <div class="ico_box b2"></div>
        <div>已申请</div>
      </div>
      <div class="box_item" @click="handlerJump('/member/personal/attention_me')">
        <div class="ico_box b3"></div>
        <div>被关注</div>
      </div>
      <div class="box_item" @click="handlerJump('/member/personal/interview')">
        <div class="ico_box b4"></div>
        <div>面试邀请</div>
      </div>
      <div class="box_item" @click="handlerJump('/member/personal/interview_video')">
        <div class="ico_box b5"></div>
        <div>视频面试</div>
      </div>
      <div class="box_item" @click="handlerJump('/member/personal/favjob')">
        <div class="ico_box b6"></div>
        <div>我的收藏</div>
      </div>
      <div class="box_item" @click="handlerJump('/member/personal/viewjob')">
        <div class="ico_box b7"></div>
        <div>浏览足迹</div>
      </div>
      <div class="box_item" @click="handlerJump('/member/personal/attention_company')">
        <div class="ico_box b8"></div>
        <div>关注公司</div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="video" @click="$router.push('/shortvideo/videoManage')" v-if="$store.state.config.shortvideo_enable === '1'">
      <div class="title">太原人才网现已支持发布视频简历了！</div>
      <div class="tisp">点击去发布，<span>求职期望、工作经验、个人特长</span>都可以哦~</div>
    </div>
    <Ad
      v-if="ad_dataset_banner.items.length > 0"
      :dataset="ad_dataset_banner"
    ></Ad>
    <div class="form_split_10"></div>
    <div class="box_title">
      增值服务
      <div class="right_txt">求职好法宝，我就是不一样的烟火</div>
    </div>
    <div class="box_6">
      <div
        class="box_item t1"
         @click="handlerJumpService('/member/order/add/stick','stick')"
      >
        <div class="tx1">简历置顶</div>
        <div class="tx2">锁定C位，提高5倍曝光量</div>
      </div>
      <div
        class="box_item t2"
         @click="handlerJumpService('/member/order/add/tag','tag')"
      >
        <div class="tx1">醒目标签</div>
        <div class="tx2">即刻脱颖而出，秀出自我</div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="form_split_10"></div>
    <div class="box_7">
      <div
        class="handle_line l1"
         @click="handlerJump('/member/personal/service')"
      >
        会员服务
        <div class="right_txt">
          免费获取{{ $store.state.config.points_byname }}、兑换增值服务
        </div>
      </div>
      <div class="handle_line l2" @click="handlerJump('/member/personal/recommend')">
        智能匹配
        <div class="tip_go"></div>
        <div class="right_txt">精准推荐职位、订阅最新职位</div>
      </div>
      <div class="handle_line l3" @click="handlerJump('/member/personal/privacy')">
        隐私设置
        <div class="right_txt">简历隐私开关、屏蔽企业设置</div>
      </div>
      <div class="handle_line l4" @click="handlerJump('/im/imlist')">
        我的职聊
        <div class="right_txt">求职新方式，与企业在线职聊</div>
      </div>
      <router-link class="handle_line l5" to="/member/personal/account">
        账号管理
        <div class="right_txt">用户名密码、第三方绑定设置</div>
      </router-link>
    </div>
    <div class="form_split_10"></div>
    <div class="box_7 no_mb">
      <div class="handle_line l6" @click="showSwitchType = true">
        我要招聘
        <div class="right_txt">我是企业/个体户，我要招人才</div>
      </div>
    </div>
    <div class="form_split_18"></div>
    </van-pull-refresh>
    <!--简历完整度过低，弹窗提示-->
    <van-popup v-model="showLowPop">
      <div class="box_8">
        <div class="tx1">好工作，从好简历开始</div>
        <div class="tx2">完善的简历有助于您的岗位匹配</div>
        <div class="tx2">是否前往完善？</div>
        <div class="btn_wrapper">
          <div class="btn_left" @click="showLowPop=false">取消</div>
          <div class="btn_right" @click="handlerJump('/member/personal/resume')">继续完善</div>
          <div class="clear"></div>
        </div>
      </div>
    </van-popup>
    <!--简历完整度过低，弹窗提示-->
    <van-popup v-model="showRefreshPop">
      <div class="box_9">
        <div class="tx1">刷新简历</div>
        <div class="tx2">不让简历石沉大海，每天从刷新简历开始</div>
        <div class="btn_wrapper"><div class="btn_right" @click="refreshResume">刷新简历</div></div>
      </div>
    </van-popup>
    <BottomNav></BottomNav>
    <van-popup
      v-model="showSwitchType"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <SwitchType
        @closeSwitchType="showSwitchType = false"
        :target_utype="1"
      ></SwitchType>
    </van-popup>
  </div>
</template>

<script>
import SwitchType from '@/components/account/SwitchType'
import {handlerHttpError} from '@/utils/error'
import {dateCompare} from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import Ad from '@/components/Ad'
export default {
  name: 'PersonalIndex',
  components: {
    SwitchType,
    Ad
  },
  data () {
    return {
      empty_resume: false,
      pullLoading: false,
      showLowPop: false, // 是否显示简历完整度过低弹窗
      showRefreshPop: false, // 是否显示刷新简历弹窗
      showSwitchType: false,
      message_list: [],
      animateUp: false,
      timer: null,
      ad_dataset_banner: { alias: 'QS_member_personal_banner', items: [] }
    }
  },
  computed: {
    basic () {
      return this.$store.state.resume.basic
    }
  },
  watch: {
    basic: {
      deep: true,
      handler: function () {
        let basic = this.$store.state.resume.basic

        let dateInstance = new Date()
        let current_date = dateInstance.getFullYear() + '-' + (dateInstance.getMonth() + 1) + '-' + dateInstance.getDate()

        let resume_complete_notice_date = localStorage.getItem('mobile_resume_complete_notice_date')
        if (basic.complete_percent < this.$store.state.config.apply_job_min_percent && dateCompare(resume_complete_notice_date, current_date) != 'eq') {
          this.showLowPop = true
          localStorage.setItem('mobile_resume_complete_notice_date', current_date)
        }
        if (this.showLowPop === false && this.$store.state.config.resume_auto_refresh == 0) {
          let resume_refresh_notice_date = localStorage.getItem('mobile_resume_refresh_notice_date')
          if (dateCompare(resume_refresh_notice_date, current_date) != 'eq') {
            this.showRefreshPop = true
            localStorage.setItem('mobile_resume_refresh_notice_date', current_date)
          }
        }
      }
    }
  },
  mounted () {
    this.timer = setInterval(this.scrollAnimate, 3000)
  },
  destroyed () {
    clearInterval(this.timer)
  },
  created () {
    this.getMessageList()
    this.initInfo()
    this.fetchAd()
  },
  methods: {
    handlerJump (path) {
      if (this.empty_resume === true) {
        handlerHttpError({code: 50007, message: '请先添加一份简历'})
      } else {
        this.$router.push(path)
      }
    },
    handlerJumpService (path, type) {
      if (this.empty_resume === true) {
        handlerHttpError({code: 50007, message: '请先添加一份简历'})
      } else {
        if (type == 'stick' && this.basic.stick === 1) {
          this.$notify('该简历已经在推广状态，不能重复推广')
          return false
        }
        if (type == 'tag' && this.basic.service_tag !== '') {
          this.$notify('该简历已经在推广状态，不能重复推广')
          return false
        }
        this.$router.push(path)
      }
    },
    handlerPullRefresh () {
      this.initInfo(true)
    },
    getMessageList () {
      http
        .get(api.member_msglist, { page: 1, pagesize: 10 })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.message_list = [...res.data.items]
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
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
    fetchAd () {
      http
        .post(api.ad_list, {
          alias: ['QS_member_personal_banner@mobile']
        })
        .then(res => {
          this.ad_dataset_banner.items =
            res.data.items['QS_member_personal_banner@mobile']
        })
        .catch(() => {})
    },
    // 初始化数据
    initInfo (is_refresh = false) {
      http
        .get(api.editResume)
        .then(res => {
          this.pullLoading = false
          if (is_refresh) {
            this.$toast('页面刷新成功')
          }
          if (res.data === null) {
            this.empty_resume = true
            // 更新基本资料
            this.$store.dispatch('setBasicInfo', null)
          } else {
          // 更新基本资料
            this.$store.dispatch('setBasicInfo', res.data.basic)
            this.empty_resume = false
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    // 刷新简历
    refreshResume () {
      this.showRefreshPop = false
      http
        .post(api.resume_refresh)
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
}
</script>

<style lang="scss" scoped>
  .van-popup--center {
    background: transparent;
  }
.box_9 {
  width: 286px;
  background: #ffffff url("../../assets/images/pop_bg1.png") center 0 no-repeat;
  border-radius: 10px;
  background-size: 286px 180px;
  text-align: center;
  padding-bottom: 30px;
  .tx1 {
    font-size: 18px;
    color: #333333;
    padding: 199px 0 16px;
  }
  .tx2 {
    font-size: 13px;
    color: #999999;
    margin-bottom: 15px;
  }
  .btn_wrapper {
    padding-top: 20px;
    width: 245px;
    margin: 0 auto;
    .btn_right {
      display: inline-block;
      font-size: 13px;
      color: #ffffff;
      padding: 12px 0;
      width: 110px;
      border-radius: 27px;
      border: 1px solid #3388ff;
      background-color: #3388ff;
    }
  }
}
.box_8 {
  width: 286px;
  background: #ffffff url("../../assets/images/pop_bg2.png") center 0 no-repeat;
  border-radius: 10px;
  background-size: 286px 180px;
  text-align: center;
  padding-bottom: 30px;
  .tx1 {
    font-size: 18px;
    color: #333333;
    padding: 199px 0 16px;
  }
  .tx2 {
    font-size: 13px;
    color: #999999;
    margin-bottom: 8px;
  }
  .btn_wrapper {
    padding-top: 20px;
    width: 245px;
    margin: 0 auto;
    .btn_left {
      float: left;
      font-size: 13px;
      color: #999999;
      padding: 12px 0;
      width: 110px;
      border-radius: 27px;
      border: 1px solid #e2e2e2;
    }
    .btn_right {
      float: right;
      font-size: 13px;
      color: #ffffff;
      padding: 12px 0;
      width: 110px;
      border-radius: 27px;
      border: 1px solid #3388ff;
      background-color: #3388ff;
    }
  }
}
.handle_line {
  font-size: 16px;
  color: #333333;
  padding: 16.5px 0 16.5px 52px;
  position: relative;
  display: block;
  .right_txt {
    position: absolute;
    right: 34px;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 14px;
    color: #c9c9c9;
  }
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
  &::before {
    position: absolute;
    right: 17px;
    top: 24.5px;
    width: 6px;
    height: 6px;
    border-top: 1px solid #d9d9d9;
    border-right: 1px solid #d9d9d9;
    transform: rotate(45deg);
    content: " ";
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
  &.l1 {
    background: #ffffff url("../../assets/images/personal_index_l1.png") 19px
      center no-repeat;
    background-size: 18.5px 16px;
  }
  &.l2 {
    background: #ffffff url("../../assets/images/personal_index_l2.png") 23px
      center no-repeat;
    background-size: 10px 16px;
  }
  &.l3 {
    background: #ffffff url("../../assets/images/personal_index_l3.png") 20px
      center no-repeat;
    background-size: 14px 15.5px;
  }
  &.l4 {
    background: #ffffff url("../../assets/images/personal_index_l4.png") 18px
      center no-repeat;
    background-size: 17.5px 17.5px;
  }
  &.l5 {
    background: #ffffff url("../../assets/images/personal_index_l5.png") 18px
      center no-repeat;
    background-size: 17px 17px;
  }
  &.l6 {
    background: #ffffff url("../../assets/images/personal_index_l6.png") 18px
      center no-repeat;
    background-size: 18.5px 18px;
  }
}
.box_7 {
  width: 100%;
  background-color: #ffffff;
  &.no_mb {
    margin-bottom: 0;
  }
}
.box_6 {
  width: 100%;
  background-color: #ffffff;
  padding: 0 16px 25px 16px;
  .box_item {
    float: left;
    width: 166px;
    height: 75px;
    padding-left: 9px;
    border-radius: 6px;
    &.t1 {
      background: #fff9f5 url("../../assets/images/personal_index_top.png")
        125px center no-repeat;
      background-size: 35px;
    }
    &.t2 {
      background: #f7fbff url("../../assets/images/personal_index_tag.png")
        125px center no-repeat;
      background-size: 35px;
      float: right;
      .tx1 {
        color: #218ffc;
      }
    }
    .tx1 {
      padding: 16.5px 0 8.5px;
      font-size: 16px;
      color: #ff8a4a;
    }
    .tx2 {
      font-size: 10px;
      color: #999999;
    }
  }
}
.box_4 {
  width: 100%;
  background-color: #ffffff;
  .box_item {
    display: block;
    width: 25%;
    text-align: center;
    font-size: 12px;
    color: #666666;
    margin: 15px 0;
    float: left;
    .ico_box {
      height: 30px;
      width: 25px;
      margin: 0 auto;
      position: relative;
      &.new::after {
        content: " ";
        position: absolute;
        width: 18px;
        height: 11px;
        top: -6px;
        right: -16px;
        background: url("../../assets/images/personal_index_app_new.png") 0
          no-repeat;
        background-size: 18px 11px;
      }
      &.b1 {
        background: url("../../assets/images/personal_index_app1.png") center 0
          no-repeat;
        background-size: 19px;
      }
      &.b2 {
        background: url("../../assets/images/personal_index_app2.png") center 0
          no-repeat;
        background-size: 20px 19.5px;
      }
      &.b3 {
        background: url("../../assets/images/personal_index_app3.png") center 0
          no-repeat;
        background-size: 23.5px 19px;
      }
      &.b4 {
        background: url("../../assets/images/personal_index_app4.png") center 0
          no-repeat;
        background-size: 19.5px 20px;
      }
      &.b5 {
        background: url("../../assets/images/personal_index_app5.png") center 0
          no-repeat;
        background-size: 23px 16.5px;
      }
      &.b6 {
        background: url("../../assets/images/personal_index_app6.png") center 0
          no-repeat;
        background-size: 20px 19px;
      }
      &.b7 {
        background: url("../../assets/images/personal_index_app7.png") center 0
          no-repeat;
        background-size: 22.5px 20px;
      }
      &.b8 {
        background: url("../../assets/images/personal_index_app8.png") center 0
          no-repeat;
        background-size: 18px 19px;
      }
    }
  }
}
.video {
  background: url("../../assets/images/video.png") 0 center no-repeat #f5f5f5;
  background-size: 37px 19px;
  border-radius: 50px;
  margin-left: 16px;
  margin-right: 16px;
  margin-top: 11px;
  margin-bottom: 11px;
  padding-top: 8px;
  padding-bottom: 8px;
  padding-left: 42px;
  box-sizing: border-box;
  .title {
    color: #1f1f1f;
    font-size: 13px;
  }
  .tisp {
    margin-top: 4px;
    color: #666666;
    font-size: 11px;
    span {
      color: #ff7f3a;
    }
  }
}
.box_title {
  width: 100%;
  background-color: #ffffff;
  position: relative;
  padding: 17.5px 0 17.5px 18px;
  font-size: 18px;
  font-weight: bold;
  color: #333333;
  .right_txt {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translate(0, -50%);
    font-size: 12px;
    color: #999999;
  }
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
    top: 21px;
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
  padding: 11.5px 0 11.5px 33px;
  background: #fffbeb url("../../assets/images/remind_ico.svg") 13px center
    no-repeat;
  background-size: 15px;
  .right_txt {
    position: absolute;
    right: 0;
    top: 0;
    font-size: 12px;
    color: #1787fb;
    padding: 11.5px 17px;
  }
}
.box_1 {
  width: 100%;
  display: flex;
  background-color: #f7fbff;
  .box_item {
    flex: 1;
    text-align: center;
    position: relative;
    &:not(:last-child)::after {
      position: absolute;
      right: 0;
      top: 50%;
      transform: translate(0, -50%);
      height: 13px;
      border-right: 1px solid #f3f3f3;
      content: " ";
    }
    .item {
      font-size: 14px;
      color: #1787fb;
      padding: 12.5px 0 12.5px 23px;
      width: 80px;
      margin: 0 auto;
      &.t1 {
        background: url("../../assets/images/refresh.svg") 0 center no-repeat;
        background-size: 14px;
      }
      &.t2 {
        background: url("../../assets/images/preview.svg") 0 center no-repeat;
        background-size: 14px;
      }
      &.t3 {
        background: url("../../assets/images/edit.svg") 0 center no-repeat;
        background-size: 14px;
      }
    }
  }
}
.top_box {
  position: relative;
  width: 100%;
  padding-top: 4.5px;
  text-align: center;
  .avatar_wrapper {
    width: 70px;
    height: 70px;
    border-radius: 70px;
    margin: 0 auto 10px;
    box-shadow: 0 1px 5px #c2c2c2;
    background-color: #ffffff;
    img {
      width: 70px;
      height: 70px;
      border-radius: 70px;
      border: 0;
    }
  }
  .user_name {
    position: relative;
    font-size: 18px;
    color: #333333;
    font-weight: bold;
    width: 72px;
    text-align: center;
    margin: 0 auto;
    .percent {
      position: absolute;
      right: -40px;
      bottom: 15px;
      font-size: 10px;
      color: #ffffff;
      background-color: #ff9053;
      border-radius: 5px;
      padding: 2px 4px;
      &::after {
        content: " ";
        position: absolute;
        left: -7px;
        top: 50%;
        width: 0;
        height: 0;
        border-bottom: 8px solid #ff9053;
        border-left: 8px solid transparent;
        transform: translate(0, -50%);
      }
    }
  }
  .current_txt {
    padding: 10px 0;
    font-size: 12px;
    color: #999999;
  }
}
.my_app {
  background: #ffffff url("../../assets/images/personal_index_top_bg.png")
    center 0 no-repeat;
  background-size: 375px 113.5px;
}
</style>
