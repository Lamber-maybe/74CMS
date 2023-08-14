<template>
  <div>
    <Head>我的职聊</Head>
    <!-- 列表头部导航开始 -->
    <div class="im-top-nav">
      <div :class="['nav-item',item.imgClass]" v-for="(item,index) in isTopNav" :key="index" @click="handleJump(item.url)">
        {{item.val}}
      </div>
    </div>
    <!-- 列表头部导航结束 -->
    <div class="form_split_10"></div>
    <!-- 聊天用户列表开始 -->
    <div class="h100">
      <div class="list-tab-wrapper">
        <div :class="isFixedNav ? 'tab-btn-con tab-btn-con-ac' : 'tab-btn-con'" ref="tabBtnCon">
          <div ref="btnItem0" :class="listTabIndex == 0 ? 'btn-item btn-ac' : 'btn-item'" @click="handleTabSwitch(0,$event)">全部</div>
          <div ref="btnItem1" :class="listTabIndex == 1 ? 'btn-item btn-ac' : 'btn-item'" @click="handleTabSwitch(1,$event)">已读</div>
          <div ref="btnLine" class="btn-line"></div>
          <div class="btn-set-up" @click="SetUpListPopup=true"></div>
        </div>
        <div class="tab-list-con" @touchstart="handleTouchStart($event)" @touchmove="handleTouchMove($event)" @touchend="handleTouchEnd">
          <div ref="tabListMoveCon" class="tab-list-move-con">
            <div class="list-con list-con-ac">

              <ul class="list" v-if="chatListAryan.length>0">
                <li :class="item.stick == 1 ? 'list-item list-item-stick':'list-item'" v-for="(item) in chatListAryan" :key="item.chat_id" @click="handleJumpImShow(item)">
                  <!-- :before-close="beforeClose" -->
                  <!-- @open="handleCancelTop(item)" -->
                  <van-swipe-cell :disabled="item.stick == 1 ? false : true">
                    <div class="chat-item">
                      <div class="img">
                        <img :src="item.avatar">
                        <div class="badge" v-if="item.new > 0"></div>
                      </div>
                      <div class="info">
                        <div class="info-txt-1 substring"  v-if="LoginType == 2">{{ item.detail}}</div>
                        <div class="info-txt-1 substring"  v-if="LoginType == 1">{{ item.nickname}}</div>
                        <div v-if="LoginType == 2" class="info-txt-2 substring">
                          <span class="txt-color-1">{{item.nickname}}</span>
                          <span class="txt-color-2">正在与您沟通</span>
                          <span class="txt-color-3">{{item.jobname}}</span>
                        </div>
                        <div v-if="LoginType == 1" class="info-txt-2">{{item.detail}}</div>
                      </div>
                      <div class="time">{{item.updatetime}}</div>
                    </div>
                    <template #right>
                      <!--  -->
                      <div class="cancel-btn" @click="handleCancelClick(item)">
                        取消置顶
                      </div>
                    </template>
                  </van-swipe-cell>
                </li>
              </ul>
              <van-empty v-else description="暂无会话列表" />
            </div>
            <div class="list-con">
              <ul class="list" v-if="readAryList.length>0">
                <li :class="item.stick ==1 ? 'list-item list-item-stick':'list-item'" v-for="(item) in readAryList" :key="item.chat_id" @click="handleJumpImShow(item)">
                  <van-swipe-cell :disabled="item.stick == 1 ? false : true">
                    <div class="chat-item">
                      <div class="img">
                        <img :src="item.avatar">
                        <div class="badge" v-if="item.new > 0"></div>
                      </div>
                      <div class="info">
                        <div class="info-txt-1 substring">{{item.nickname}}</div>
                        <div v-if="LoginType == 2" class="info-txt-2 substring">
                          <span class="txt-color-1">{{item.nickname}}</span>
                          <span class="txt-color-2">正在与您沟通</span>
                          <span class="txt-color-3">{{item.jobname}}</span>
                        </div>
                        <div v-if="LoginType == 1" class="info-txt-2">{{item.detail}}</div>
                      </div>
                      <div class="time">{{item.updatetime}}</div>
                    </div>
                    <template #right>
                      <div class="cancel-btn" @click="handleCancelClick(item)">
                        取消置顶
                      </div>
                    </template>
                  </van-swipe-cell>
                </li>
              </ul>
              <van-empty v-else description="暂无已读列表" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- 聊天用户列表结束 -->
    <van-popup v-model="SetUpListPopup" position="right" :style="{ width:'100%', height: '100%' ,overflow: 'hidden'}">
      <div class="setup-list-wrapper">
        <Head :goback_custom="true" @gobackCustomMethod="gobackSetUpListClose">职聊设置</Head>
        <div class="setup-list">
          <div class="setup-list-item" @click="handleOpenShiedList">
            <div>已屏蔽聊天管理</div>
            <van-icon name="arrow"/>
          </div>
          <div class="setup-list-item" @click="handleOpenCommone">
            <div>常用语管理</div>
            <van-icon name="arrow"/>
          </div>
          <div class="setup-list-item" @click="handleOpenGreet">
            <div>打招呼管理</div>
            <van-icon name="arrow"/>
          </div>
        </div>
      </div>
    </van-popup>
    <!-- 常用语列表开始 -->
    <CommonIndex ref="CommonIndex"></CommonIndex>
    <!-- 常用语列表结束 -->
    <!-- 已屏蔽企业开始 -->
    <ShieldList ref="ShieldList"></ShieldList>
    <!-- 已屏蔽企业结束 -->
    <!-- 招呼语开始 -->
    <Greet ref="Greet"></Greet>
    <!-- 招呼语结束 -->

    <!-- 绑定微信开始 -->
    <van-dialog v-model="bindWeixinShow" title="系统提示" :show-cancel-button="false" :show-confirm-button="true" @confirm="handleImCheckBind">
      <div class="bind-weixin-box">
        <div class="title-1">您当前未绑定微信，绑定后可发起聊天。</div>
        <div class="img">
          <img :src="scanQrcodeImg" alt="">
        </div>
        <div class="title-2">使用微信扫一扫，按提示快速绑定</div>
      </div>
      <img src="" />
    </van-dialog>
    <!-- 绑定微信结束 -->

    <!-- 切换职位开始 -->
    <van-overlay :show="selectJobShow" z-index="15">
      <SelectJob @handleSelectJob="handleSelectJob" @handleCloseSelectJob="handleCloseSelectJob" :chatid="imChatid" :companyId="companyId" :isSelectJob="true"></SelectJob>
    </van-overlay>
    <!-- 切花职位结束 -->
  </div>
</template>

<script>
import { formatTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import {mapState, mapMutations} from 'vuex'
import CommonIndex from './components/CommonIndex.vue'
import ShieldList from './components/ShieldList.vue'
import Greet from './components/Greet.vue'
import SelectJob from './components/SelectJob.vue'
export default {
  name: 'ImList',
  filters: {
    timeFilter (timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}', true)
    }
  },
  data () {
    return {
      // 企业头部导航列表
      companyImListTopNav: [
        {
          val: '收到投递',
          imgClass: 'get-delivery',
          url: '/member/company/jobapply'
        },
        {
          val: '我的下载',
          imgClass: 'download',
          url: '/member/company/download'
        },
        {
          val: '我的收藏',
          imgClass: 'collection',
          url: '/member/company/fav'
        },
        {
          val: '面试邀请',
          imgClass: 'interview',
          url: '/member/company/interview'
        }
      ],
      // 个人头部导航列表
      personalImListTopNav: [
        {
          val: '谁看过我',
          imgClass: 'see',
          url: '/member/personal/attention_me'
        },
        {
          val: '我的投递',
          imgClass: 'delivery',
          url: '/member/personal/jobapply'
        },
        {
          val: '我的收藏',
          imgClass: 'collection',
          url: '/member/personal/favjob'
        },
        {
          val: '面试通知',
          imgClass: 'interview',
          url: '/member/personal/interview'
        }
      ],
      // 全部 新招呼 沟通中 切换下标
      listTabIndex: 0,
      // 全部 新招呼 沟通中 导航是否固定在顶部
      isFixedNav: false,
      // 手指按下起始位置
      startX: 0,
      startY: 0,
      // 手指在屏幕上移动的位置
      moveX: 0,
      moveY: 0,
      endX: 0,
      endY: 0,
      // 会话列表
      chatListAryan: [],
      // 设置列表弹窗状态
      SetUpListPopup: false,
      // 绑定微信
      bindWeixinShow: false,
      // 选择职位
      selectJobShow: false,
      // 选择聊天用户id
      imChatid: '',
      // 企业id
      companyId: '',
      // 已读
      readAryList: [],
      // 二维码图片
      scanQrcodeImg: ''
    }
  },
  watch: {
    messagelist (newval, oldval) {
      this.getUserList()
    },
    IsUpdataChatList (newval, oldval) {
      if (newval != 0) {
        this.getUserList()
      }
    }
  },
  computed: {
    ...mapState({
      imToken: state => state.imToken,
      LoginType: state => state.LoginType, // 用户类型-1为企业 2位个人
      messagelist: state => state.messagelist,
      IsUpdataChatList: state => state.IsUpdataChatList
    }),
    /**
     * 通过type区分 企业/个人 用于展示不同的头部列表数据
     */
    isTopNav () {
      if (this.LoginType == 1) {
        return this.companyImListTopNav
      } else if (this.LoginType == 2) {
        return this.personalImListTopNav
      }
    }
  },
  mounted () {
    window.addEventListener('scroll', this.scrollToTop)
  },
  beforeDestroy () {
    window.removeEventListener('scroll', this.scrollToTop)
  },
  created () {
    this.imWindowGlobal()
    this.getScanQrcodeImg()
  },
  destroyed () {
  },
  components: {
    CommonIndex,
    ShieldList,
    Greet,
    SelectJob
  },
  methods: {
    ...mapMutations(['setImShowParams', 'setimChatid', 'setBlackObj']),
    /**
     * 二维码图片
     */
    getScanQrcodeImg () {
      http.get(api.get_qrcode, {type: 'bind_weixin'}).then(res => {
        this.scanQrcodeImg = res.data
      })
    },
    /**
     * 是否绑定微信公众号
     */
    handleImCheckBind () {
      http.get(api.imCheckBind).then(res => {
        if (res.data != 0) {
          location.reload(true)
        } else {
          window.history.length > 1
            ? this.$router.go(-1)
            : this.$router.push('/')
        }
      })
    },
    /**
     * 即时通讯进入界面时全局检测
     * disabled 不能使用功能
     * bind_weixin绑定微信
     * complete_resume完善简历
     * 空字符串 正常使用
     * choose_job选择职位
     * pay需要购买增值服务，触屏是快捷支付
     */
    imWindowGlobal () {
      http.get(api.im_window_global).then((res) => {
        if (parseInt(res.code) == 200) {
          // next 为空可以使用聊天
          if (res.data.next == '') {
            // 获取用户详情
            // this.getUserInfo()
            // 获取会话列表
            // this.getChatList()
            // 检测是否在黑名单中
            // this.handleIsBlackList()
            // 获取简历信息
            // this.getResumeInfo()

            this.getUserList()
          }
          if (res.data.next == 'bind_weixin') { // 绑定微信
            this.bindWeixinShow = true
            return false
          }
          if (res.data.next == 'complete_resume') { // 编辑简历
            this.$router.push('/member/personal/resume')
            return false
          }
          if (res.data.next == 'disabled') { // 不能使用功能
            // this.$notify({type: 'danger', message: res.message})
            this.$dialog({
              title: '系统提示',
              message: res.message,
              showConfirmButton: true
            }).then(() => {
              // 用户类型-1为企业 2位个人
              if (this.LoginType == 1) {
                this.$router.push({path: '/member/company/index'})
              } else if (this.LoginType == 2) {
                this.$router.push({path: '/member/personal/index'})
              }
            })
            return false
          }
        } else {
          this.$notify({type: 'danger', message: res.message})
        }
      })
    },
    /**
     * tab切换
     * @index Number 当前点击下标
     * @e Object 当前点击元素event
     */
    handleTabSwitch (index, e) {
      this.listTabIndex = index
      this.handleTouchDomHeight()
      this.$refs.tabListMoveCon.style.transform = 'translate3d(-' + (index * 100) + '% , 0px , 0px)'
      let currentLeft = e.target.getBoundingClientRect().left
      this.$refs.btnLine.style.transform = 'translate3d(' + parseInt(currentLeft) + 'px , 0px , 0px)'
    },
    /**
     *  是否固定 全部 新招呼 沟通中 导航函数
    */
    scrollToTop () {
      // 当前滚动距离 >= 元素距离顶部的时候固定导航
      if (this.getScrollTop() >= this.$refs.tabBtnCon.offsetTop) {
        this.isFixedNav = true
      } else {
        this.isFixedNav = false
      }
    },
    /**
     * 当前距离顶部的滚动值
     * */
    getScrollTop () {
      let scrollTop = 0
      if (document.documentElement && document.documentElement.scrollTop) {
        scrollTop = document.documentElement.scrollTop
      } else if (document.body) {
        scrollTop = document.body.scrollTop
      }
      return scrollTop
    },
    /**
     * 滑动事件 手指放到屏幕时触发
     * @e Object event对象
    */
    handleTouchStart (e) {
      this.startX = e.touches[0].clientX
      this.startY = e.touches[0].clientY
    },
    /**
     * 滑动事件 手指在屏幕上滑动时触发
     * @e Object event对象
     */
    handleTouchMove (e) {
      let moveX = e.changedTouches[0].clientX
      let moveY = e.changedTouches[0].clientY

      this.moveX = moveX - this.startX
      this.moveY = moveY - this.startY
      if (Math.abs(this.moveX) > Math.abs(this.moveY)) {
        e.preventDefault()
      }
    },
    /**
     * 滑动事件 手指在离开屏幕时触发
     */
    handleTouchEnd (e) {
      this.endX = e.changedTouches[0].clientX
      this.endY = e.changedTouches[0].clientY
      let disX = Math.abs(this.endX - this.startX)
      let disY = Math.abs(this.endY - this.startY)
      if (this.endX - this.startX > 120 && this.swipeDirection(disX, disY)) {
        this.listTabIndex <= 0 ? this.listTabIndex = 0 : this.listTabIndex--
        this.handleTouchDomHeight()
        this.$refs.tabListMoveCon.style.transform = 'translate3d(-' + (this.listTabIndex * 100) + '% , 0px , 0px)'
        let currentBtnItem = 'btnItem' + this.listTabIndex
        let currentLeft = this.$refs[currentBtnItem].getBoundingClientRect().left
        this.$refs.btnLine.style.transform = 'translate3d(' + parseInt(currentLeft) + 'px , 0px , 0px)'
      }
      if (this.endX - this.startX < -120 && this.swipeDirection(disX, disY)) {
        this.listTabIndex >= 1 ? this.listTabIndex = this.listTabIndex : this.listTabIndex++
        this.handleTouchDomHeight()
        this.$refs.tabListMoveCon.style.transform = 'translate3d(-' + (this.listTabIndex * 100) + '% , 0px , 0px)'
        let currentBtnItem = 'btnItem' + this.listTabIndex
        let currentLeft = this.$refs[currentBtnItem].getBoundingClientRect().left
        this.$refs.btnLine.style.transform = 'translate3d(' + parseInt(currentLeft) + 'px , 0px , 0px)'
      }
    },
    swipeDirection (disX, disY) {
      let angle = (Math.atan2(disX, disY) * 180) / Math.PI
      if (angle > 40) {
        // 水平方向
        return true
      }
      if (angle < 40) {
        // 垂直方向
        return false
      }
    },
    /***
     * 处理在 (全部 新招呼 沟通中) 切换时 高度问题 导致在第二项可以滚动
     */
    handleTouchDomHeight () {
      var allListCon = document.querySelectorAll('.tab-list-move-con .list-con')
      for (let index = 0; index < allListCon.length; index++) {
        const item = allListCon[index]
        if (index != this.listTabIndex) {
          item.style.height = '0'
        } else {
          item.style.height = 'auto'
        }
      }
    },
    /***
     * 聊天用户列表
     */
    getUserList () {
      http.post(api.chatList, {token: this.imToken}).then((res) => {
        this.chatListAryan = res.data.items
        this.readAryList = this.chatListAryan.filter((res) => {
          return res.new != 1
        })
      })
    },
    /**
     * 跳转聊天页面
     * @item Object 当前点击会话列表用户
     */
    handleJumpImShow (chatItem) {
      // 跳转会话详情时先判断是否在黑名单中
      this.chatListAryan.forEach(item => {
        if (item.chat_id == chatItem.chat_id) {
          item.new = 0
        }
      })
      this.handleIsBlackList(chatItem)
    },
    /**
     * 处理跳转会话详情时是选择职位还是 直接跳转
     * @item 当前点击会话项
     */
    handleIsJump (item) {
      http.post(api.im_job_detail, {jobid: item.jobid}).then((res) => {
        if (res.data.next == 'choose_job') {
          // 选择职位
          this.selectJobShow = true
          this.imChatid = item.chat_id
          this.companyId = item.companyid
        } else {
          // 跳转会话详情
          this.jumpImShow(item)
        }
      })
    },
    /**
     * 检测是否在黑名单中
     * @blackItem 当前点击会话项
     */
    handleIsBlackList (blackItem) {
      http.post(api.check_blacklist, {token: this.imToken, chatid: blackItem.chat_id}).then((res) => {
        if (parseInt(res.code) === 200) {
          // 0 没有屏蔽
          // 1 您已屏蔽对方，如需继续聊天请先解除屏蔽
          // 2 您已被对方加入黑名单，无法继续与TA聊天哦
          if (res.data == 1) {
            this.$dialog({
              title: '系统提示',
              message: res.message,
              confirmButtonText: '解除屏蔽',
              showCancelButton: true
            }).then(() => {
              http.post(api.del_blacklist, {token: this.imToken, chatid: blackItem.chat_id}).then(res => {
                this.setBlackObj({
                  chatid: '',
                  cancel_enable: '2',
                  message: ''
                })
                this.handleIsJump(blackItem)
              })
            })
          } else if (res.data == 2) {
            this.$dialog({
              title: '系统提示',
              message: res.message,
              showCancelButton: true
            }).then(() => {
              // this.setBlackObj({
              //   chatid: '',
              //   cancel_enable: '2',
              //   message: ''
              // })
            })
          } else {
            this.handleIsJump(blackItem)
          }
        }
      })
    },
    /**
     * 关闭职聊设置列表弹窗
     */
    gobackSetUpListClose () {
      this.SetUpListPopup = false
    },
    /**
     * 打开常用语列表面板
     */
    handleOpenCommone () {
      this.SetUpListPopup = false
      this.$refs.CommonIndex.handleOpenCommonList()
    },
    /**
     * 打开已屏蔽列表
     */
    handleOpenShiedList () {
      this.SetUpListPopup = false
      this.$refs.ShieldList.handleOpenShieldPopup()
    },
    /**
     * 打开招呼语弹窗
     */
    handleOpenGreet () {
      this.SetUpListPopup = false
      this.$refs.Greet.openGreet()
    },
    /**
     * 跳转
     */
    handleJump (url) {
      this.$router.push(url)
    },
    /**
     * 选择职位弹窗关闭
     * 组件中 isSelectJob 决定返回的值 true 说明是切换职位 false 说明是选择沟通职位
     * @isSelectJob Boolen 是否完成职位切换  true 切换职位完成 false没有切换职位直接关闭窗口
     * @isSelectJob Object 当前选择职位的信息
     */
    handleSelectJob (isSelectJob) {
      if (isSelectJob) {
        // 职位选择完成后更新会话列表
        this.getUserList()
        // 通过id获取到点击项
        let currentChat = this.chatListAryan.filter(item => {
          return this.imChatid == item.chat_id
        })[0]
        this.jumpImShow(currentChat)
      }
      this.selectJobShow = false
    },
    /**
     * 跳转会话详情
     * @item 当前点击会话项
     * */
    jumpImShow (item) {
      let name = ''
      if (this.LoginType == 1) { // 企业
        name = item.nickname
      } else if (this.LoginType == 2) { // 个人
        name = item.detail
      }
      this.setImShowParams({jobname: item.jobname, name: name, resumeid: item.resumeid, jobid: item.jobid, companyId: item.companyid, stick: item.stick})
      this.setimChatid(item.chat_id)
      this.$router.push({path: '/im/' + item.chat_id})
    },
    /**
     * 取消置顶
     * @cancelItem 取消置顶项obj
     */
    handleCancelClick (item) {
      http.post(api.chatStick, {token: this.imToken, chat_id: item.chat_id, stick: 0}).then(res => {
        if (parseInt(res.code) == 200) {
          this.$notify({
            type: 'success',
            message: res.message
          })
          this.getUserList()
        } else {
          this.$notify({
            type: 'error',
            message: res.message
          })
        }
      })
    },
    /**
     * 选择职位弹窗关闭
     */
    handleCloseSelectJob () {
      this.selectJobShow = false
    }
  }
}
</script>

<style lang="scss" scoped>
.h100{
  height: calc(100vh - 126px - 42px);
  overflow-y:auto;
  overflow-x: hidden;
}
// 列表头部导航开始
.im-top-nav{
  display: flex;
  max-height: 126px;
  font-size: 14px;
  color: #000000;
  padding: 15px 10px;
  border-top: 1px solid #e8e8e8;
  .nav-item{
    flex: 1;
    text-align: center;
    padding: 58px 0 0;
    &.get-delivery{
      background: url('../../assets/images/im/im-list-get-delivery.png') no-repeat top center / 48px 48px;
    }
    &.download{
      background: url('../../assets/images/im/im-list-download.png') no-repeat top center / 48px 48px;
    }
    &.collection{
      background: url('../../assets/images/im/im-list-collection.png')no-repeat top center / 48px 48px;
    }
    &.interview{
      background: url('../../assets/images/im/im-list-interview.png')no-repeat top center / 48px 48px;
    }
    &.see{
      background: url('../../assets/images/im/im-list-see.png')no-repeat top center / 48px 48px;
    }
    &.delivery{
      background: url('../../assets/images/im/im-list-delivery.png')no-repeat top center / 48px 48px;
    }
  }
}
// 列表头部导航结束

.list-tab-wrapper{
  height: 100%;
  .tab-btn-con{
    display: flex;
    padding: 10px 0px ;
    font-size: 14px;
    height: 66px;
    line-height: 46px;
    position: relative;
    overflow:hidden ;
    background: #fff;
    &.tab-btn-con-ac{
      position: fixed;
      left: 0;
      right: 0;
      top: 48px;
      box-shadow: 0px 4px 8px rgb(0 0 0 / 4%);
      z-index: 999;
    }
    .btn-item{
      padding: 0 14px;
      color: #000000;
      font-size: 15px;
    }
    .btn-set-up{
      width: 18px;
      height: 18px;
      background: url('../../assets/images/im/im-list-set-up.png') no-repeat center center / 18px 18px;
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
    }
    .btn-line{
      position: absolute;
      bottom: 10px;
      left: 18px;
      width: 20px;
      height: 3px;
      background: #1787FB;
      border-radius: 2px;
      transition:transform .3s;
    }
  }
  .tab-list-con{
    height: 100%;
    .tab-list-move-con{
      transition-duration: 0.3s;
      display: flex;
      width: 100%;
      height: 100%;
    }
    .list-con{
      flex-shrink: 0;
      width: 100%;
      height: 0;

      &.list-con-ac{
        height: auto;
      }
      .list{
        .list-item{
          width: 100%;
          // display: flex;
          line-height: normal;
          height: 85px;
          // padding: 20px 17px;
          border-bottom: 1px solid #e8e8e8;
          // position: relative;
          &.list-item-stick{
            background: #f9f9f9;
          }
          .chat-item{
            display: flex;
            padding: 20px 17px;
            height: 100%;
          }
          .cancel-btn{
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1787fb;
            padding: 0 15px;
            height: calc(100% - 2px);
            // height: 100%;
            color: #fff;
          }
          .img{
            width: 45px;
            height: 45px;
            border-radius:2px ;
            margin-right: 15px;
            position: relative;
            img{
              width: 100%;
              height: 100%;
              border-radius:2px ;
            }
            .badge{
              position: absolute;
              right: -3px;
              top: -3px;
              width:10px;
              height:10px ;
              background: #ff4c56;
              color: #fff;
              font-size: 10px;
              border-radius: 50%;
              display: flex;
              align-items: center;
              justify-content: center;
            }
          }
          .info{
            width: 245px;
            font-size: 14px;
            .info-txt-1{
              color:#08050A;
              font-size: 16px;
              font-weight: 600;
              margin-bottom: 7px;
            }
            .info-txt-2{
              .txt-color-1{
                color: #333333;
              }
              .txt-color-2{
                color:#999999 ;
              }
              .txt-color-3{
                color:#FF7725 ;
              }
            }
          }
          .time{
            flex: 1;
            font-size: 14px;
            color:#999999 ;
            font-size: 12px;
          }
        }
      }
    }
  }
}
// 设置列表开始
.setup-list-wrapper{
  background: #FAFAFA;
  height: 100%;
  .setup-list{
    .setup-list-item{
      background: #fff;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 14px;
      color: #333333;
      height: 50px;
      padding: 0 12px 0 18px ;
    }
  }
}
// 设置列表结束

 // 绑定微信开始
.bind-weixin-box{
  display: flex;
  flex-direction: column;
  text-align: center;
  padding: 10px 0;
  .title-1{
    color: #646566;
    font-size:14px ;
  }
  .img{
    width:111px;
    height: 111px;
    margin: 13px auto 10px;
    img{
      width: 100%;
      height: 100%;
    }
  }
  .title-2{
    color: #999999;
    font-size:13px ;
  }
}
//绑定微信结束
</style>
