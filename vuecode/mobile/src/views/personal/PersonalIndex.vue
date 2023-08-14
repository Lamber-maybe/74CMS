<template>
  <div id="app" class="my_app">
    <Head >会员中心</Head>
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
      <div class="personal_index_wrapper">
      <div class="top_box clearfix">
        <div class="avatar_wrapper">
          <img :src="(basic && basic.photo_img_src)?basic.photo_img_src:require('@/assets/images/empty_photo.png')" />
        </div>
        <div class="user_info">
          <div class="user_name">
            {{ (basic && basic.fullname)?basic.fullname:'未注册' }}
          </div>
          <div class="current_txt" @click="handlerJump('/member/personal/resume')">
            编辑我的简历
          </div>
          <div class="refresh_resume" @click="refreshResume">
            刷新简历
          </div>
        </div>
      </div>
      <div class="personal_menu">
        <div class="lack_resume" v-if="resumeisDisplay == 1 && defectResumeAry.length != 0">
          <div class="lack_text" >您的简历缺少 {{defectResumeAry[0].remarks}}，可能错过高薪工作哦~</div>
          <div class="lack_btn" @click="handleDefectResumeJump(defectResumeAry[0])">去完善</div>
        </div>
        <div class="lack_resume bg" v-if="resumeisDisplay == 0">
          <div class="lack_text" >您的简历已隐藏，企业无法搜到到您的简历哦！</div>
          <div class="lack_btn" @click="HandleOpenResume">
            立即开启
          </div>
        </div>
        <div class="box_3" @click="$router.push('/member/personal/msglist')">
          <ul :class="{ 'animate-up': animateUp }">
            <li v-for="(item, index) in message_list" :key="index">
              {{ item.content }}
            </li>
          </ul>
        </div>
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
            <div class="ico_box b4" :class="this.is_look_interview===1?'new_red':''"></div>
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
      </div>
      <div class="video" @click="$router.push('/shortvideo/videoManage')" v-if="$store.state.config.shortvideo_enable === '1'">
        <div class="title">一份<span>简历视频</span> 让你更胜一筹</div>
        <div class="tisp">快来发布求职期望、工作经验、个人特长</div>
      </div>
      <Ad
        img_border_radius="15px"
        :no_top_border="true"
        v-if="ad_dataset_banner.items.length > 0"
        :dataset="ad_dataset_banner"
      ></Ad>
      <div class="box_6">
        <div class="box_title">
          增值服务
          <div class="right_txt">求职好法宝，我就是不一样的烟火</div>
        </div>
        <div
          class="box_item t1"
           @click="handlerJumpService('/member/order/add/stick','stick')"
        >
          <div class="tx1">简历置顶</div>
          <div class="tx2">提高5倍曝光量</div>
        </div>
        <div
          class="box_item t2"
           @click="handlerJumpService('/member/order/add/tag','tag')"
        >
          <div class="tx1">醒目标签</div>
          <div class="tx2">即刻脱颖而出</div>
        </div>
        <div class="clear"></div>
      </div>
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
          <span v-show="imUnreaded" class="point"></span>
          <div class="right_txt">求职新方式，与企业在线职聊</div>
        </div>
        <router-link class="handle_line l5" to="/member/personal/account">
          账号管理
          <div class="right_txt">用户名密码、第三方绑定设置</div>
        </router-link>
        <div class="handle_line l6" @click="showSwitchType = true">
          我要招聘
          <div class="right_txt">我是企业/个体户，我要招人才</div>
        </div>
      </div>
      </div>
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
import {mapState} from 'vuex'
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
      ad_dataset_banner: { alias: 'QS_member_personal_banner', items: [] },
      chatList: [],
      defectResumeAry: [],
      resumeisDisplay:'',
      is_look_interview: 0
    }
  },
  computed: {
    ...mapState({
      imUnreaded: state => state.imUnreaded,
      imToken: state => state.imToken
    }),
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
    },
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
  mounted () {
    this.timer = setInterval(this.scrollAnimate, 3000)
  },
  destroyed () {
    clearInterval(this.timer)
  },
  created () {
    http.get(api.isLookApply).then(res => {
      this.is_look_interview = res.data.is_look_interview
    }).catch(() => {})
    this.getMessageList()
    this.initInfo()
    this.fetchAd()
    this.getDefectResumeInfo()
    // if (this.imToken != '') {
    //   this.imWindowGlobal()
    // }
  },
  methods: {
    //关闭简历隐藏
    HandleOpenResume(){
      http
        .post(api.resume_privates_set_display, {
          display: 1
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            this.getDefectResumeInfo()
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    // 获取优化简历项
    getDefectResumeInfo () {
      http.get(api.defectResumeInfo).then((res) => {
        if (res.code == 200) {
          this.defectResumeAry = res.data.no_complete_array
          this.resumeisDisplay = res.data.is_display
        }
      })
    },
    // 优化简历跳转
    handleDefectResumeJump (item) {
      let routerObj = {
        basic: '/member/personal/resume/basic',
        intention: '/member/personal/resume/intention_edit/0',
        specialty: '/member/personal/resume/specialty',
        education: '/member/personal/resume/education_edit/0',
        work: '/member/personal/resume/work_edit/0',
        training: '/member/personal/resume/train_edit/0',
        project: '/member/personal/resume/project_edit/0',
        certificate: '/member/personal/resume/certificate_edit/0',
        language: '/member/personal/resume/language_edit/0',
        tag: '/member/personal/resume/tag',
        img: '/member/personal/resume'
      }
      this.$router.push(routerObj[item.field])
    },
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
    handlerJump (path) {
      if (this.empty_resume === true) {
        handlerHttpError({code: 50007, message: '请先添加一份简历'})
      } else {
        var pathAry = path.split('/')
        if (pathAry[1] == 'im') {
          this.$store.state.imUnreaded = false
        }
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
  .personal_index_wrapper{
    background: transparent;
    padding: 0 15px;
  }
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
  padding: 16.5px 0 16.5px 40px;
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
    left: 105px;
    top: 50%;
    transform: translate(0, -50%);
    //【ID1000515】【优化】触屏端个人会员中心智能匹配图标
    // yx-2023.02.01
    // 注释代码：{z-index: 10;}
    width: 20px;
    height: 14px;
    background: url("../../assets/images/personal_index_app_go.png") 0 no-repeat;
    background-size: 100% 100%;
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
    background: url("../../assets/images/personal_index_l1.png") 13px
      center no-repeat;
    background-size: 18.5px 16px;
  }
  &.l2 {
    background: url("../../assets/images/personal_index_l2.png") 15px
      center no-repeat;
    background-size: 10px 16px;
  }
  &.l3 {
    background: url("../../assets/images/personal_index_l3.png") 15px
      center no-repeat;
    background-size: 14px 15.5px;
  }
  &.l4 {
    background: url("../../assets/images/personal_index_l4.png") 15px
      center no-repeat;
    background-size: 17.5px 17.5px;
  }
  &.l5 {
    background: url("../../assets/images/personal_index_l5.png") 15px
      center no-repeat;
    background-size: 17px 17px;
  }
  &.l6 {
    background: url("../../assets/images/personal_index_l6.png") 15px
      center no-repeat;
    background-size: 18.5px 18px;
  }
  .point{
    position: absolute;
    left: 106px;
    top: 15px;
    display: block;
    width: 7px;
    height: 7px;
    background: red;
    border-radius: 50%;
  }
}
.box_7 {
  width: 100%;
  background-color: #ffffff;
  border-radius: 15px;
  margin-top: 20px;
  &.no_mb {
    margin-bottom: 0;
  }
}
.box_6 {
  width: 100%;
  background-color: #ffffff;
  padding: 0 15px 21px 15px;
  margin-top: 15px;
  border-radius: 15px;
  .box_item {
    float: left;
    width: 150px;
    height: 72px;
    padding-left: 15px;
    border-radius: 6px;
    margin-top: 7px;
    &.t1 {
      background: #f4ebff url("../../assets/images/personal_index_top.png") right top no-repeat;
      color: #9b41ff;
      background-size: 35px;
    }
    &.t2 {
      background: #ffedeb url("../../assets/images/personal_index_tag.png") right top no-repeat;
      background-size: 35px;
      float: right;
      color: #f05949;
    }
    .tx1 {
      padding: 14px 0 10px;
      font-size: 14px;
      font-weight: bold;
    }
    .tx2 {
      font-size: 11px;
    }
  }
}
.box_4 {
  width: 100%;
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
      &.new_red::after {
        content: " ";
        position: absolute;
        width: 7px;
        height: 7px;
        top: -6px;
        right: -11px;
        background-color: #ff5050;
        border-radius: 50%;
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
  background:#fff url("../../assets/images/video.png") right center no-repeat;
  background-size: 93px 53px;
  margin-top: 17px;
  margin-bottom: 15px;
  border-radius: 15px;
  padding: 24px 13px;
  box-sizing: border-box;
  .title {
    color: #2d2d2d;
    font-size: 16px;
    font-weight: bold;
    span {
      color: #ff661b;
    }
  }
  .tisp {
    margin-top: 10px;
    color: #666666;
    font-size: 12px;
  }
}
.box_title {
  width: 100%;
  position: relative;
  padding: 23px 0 23px 18px;
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
  width: 324px;
  margin: 15px auto 0;
  position:  relative;
  padding-left: 43px;
  height: 32px;
  overflow: hidden;
  background: #fbfbfb url("../../assets/images/personal_notice.png") no-repeat 9px center / 22.5px 22.5px;
  border-radius: 15px;
  &::after {
    position: absolute;
    right: 17px;
    top: 50%;
    width: 7px;
    height: 7px;
    border-top: 1px solid #999999;
    border-right: 1px solid #999999;
    transform: translateY(-50%) rotate(45deg);
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
    padding: 4px 22px 4px 0;
    width: 265px;
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
  background: #fffbeb url("../../assets/images/remind_ico.svg") 13px center no-repeat;
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
.personal_menu{
  background: #fff;
  border-radius: 15px;
  padding-top: 2px;//触屏会员中心简历样式修改
}
.lack_resume{
  background: linear-gradient(to right, #3c5083, #253165);
  height: 43px;
  color: #dbe8f7;
  font-size: 12px;
  position: relative;
  padding: 15px 13px;
  border-radius: 15px 15px 0 0;
  &.bg{
    background: #4a7dff;
  }
  .lack_text{
    width: 260px;
  }
  .lack_btn{
    padding: 4px 10px;
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border-radius: 15px;
    color: #253165;
    font-size: 12px;
    background: #fce6c6;
    a{
      color: #253165;
      font-size: 12px;
    }
  }
}
.box_1 {
  width: 100%;
  display: flex;
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
  padding: 20px 0 30px;
  .avatar_wrapper {
    width: 63px;
    height: 63px;
    border-radius: 63px;
    box-shadow: 0 1px 5px #c2c2c2;
    background-color: #ffffff;
    float: left;
    img {
      width: 63px;
      height: 63px;
      border-radius: 63px;
      border: 0;
    }
  }
  .user_info{
    float: left;
    width: 260px;
    margin-left: 20px;
  }
  .user_name {
    position: relative;
    font-size: 23px;
    color: #333333;
    font-weight: bold;
    width: 172px;
  }
  .current_txt {
    background: url("../../assets/images/personal_edit_resume.png") no-repeat left center / 12px 10px;
    padding: 10px 0 10px 15px;
    font-size: 12px;
    color: #333333;
    position: relative;
  }
  .refresh_resume{
    position: absolute;
    right: 0;
    top: 40px;
    color: #666666;
    font-size: 12px;
    padding: 6px 10px 4px 23px;
    border-radius: 15px;
    background: #fff url("../../assets/images/refresh.png") no-repeat 8px center / 12px 12px;
  }
}
.my_app {
  /*#fbfbfb*/
  background: #fbfbfb url("../../assets/images/personal_index_top_bg.png")
    center 0 no-repeat;
  background-size: 375px 113.5px;
}
</style>
