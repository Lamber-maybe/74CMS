<template>
  <div class="im_wrapper" @click="handleWrapperClick">
    <Head @gobackCustomMethod="handleBack">{{headerTitle}}</Head>
    <!-- 头部提示滚动开始 -->
    <van-notice-bar v-if="alert_warning == true"  mode="closeable" left-icon="volume-o" :text="im_notice" @close="handleNotice"/>
    <!-- 头部提示滚动结束 -->
    <!-- 交换电话 交换微信 投递简历 查看企业 开始 -->
    <div class="top-handle-box">
      <div class="item mobile" @click="handleExchangeMobile">交换电话</div>
      <div class="item weixin" @click="handleOpenChangeWechat">交换微信</div>
      <div class="item delivery" v-if="LoginType == 2" @click="handleOpenDelivery">投递简历</div>
      <div class="item seeCompany" v-if="LoginType == 2" @click="handleToCompany">查看企业</div>

      <div class="item interview" v-if="LoginType == 1" @click="handleInvitation">面试邀请</div>
      <div class="item seeResume" v-if="LoginType == 1" @click="handleToResume">查看简历</div>
    </div>
    <!-- 交换电话 交换微信 投递简历 查看企业 结束 -->
    <!-- 沟通职位开始 -->
    <div class="current-job" v-if="LoginType == 1">
      <div class="label">沟通职位：</div>
      <div class="text">{{changJobName}}</div>
      <div class="btn" @click="selectJobShow=true">
        <span>切换</span>
      </div>
    </div>
    <!-- 沟通职位结束 -->
    <div class="im-list-overflow">
        <!-- 聊天列表开始 -->
        <div :class="imClass" ref="imListBox">
          <MessageList
            ref="MessageList"
            @handleMobileRefuse="handleMobileRefuse"
            @handleMobileAgree="handleMobileAgree"
            @handleWechatRefuse="handleWechatRefuse"
            @handleWechatAgree="handleWechatAgree"
            @sendReturnReceiptAll="sendReturnReceiptAll"
            :imToken="imToken"
          >
          </MessageList>
        </div>
        <!-- 聊天列表结束 -->
        <!-- 底部输入框开始 -->
        <div :class="isBottomMoreClass ? 'handle-bottom-box handle-bottom-box-ac' : 'handle-bottom-box' ">
          <div class="transition-box">
            <div class="bottom-ipt-box">
              <div class="shortcut" @click="handleOpenCommonList()"></div>
              <div class="ipt">
<!--                【新增】职聊关键字屏蔽功能 zch 2022.10.18-->
                <van-field v-model.trim="msgText" @keydown.enter.native="keywordReplace" class="ipt-inner"  @focus.stop="handleMsgInput" placeholder="请输入消息内容" />
              </div>
              <div class="emoji" @click.stop="handleBottmeMore('emoji')"></div>
              <div :class="moreClass" @click.stop="handleBottmeMore('more')"></div>
              <div :class="isSend ? 'send' : 'send none-send'" @click="keywordReplace">发送</div>
            </div>
            <div class="bottom-more-wrapper">
              <ul v-show="isBottomText == 'more'" class="bottom-more-box">
                <!-- 个人底部更多 发送图片 发送简历 视频面试 置顶聊天 面试安排 屏蔽聊天  -->
                <!-- 企业底部更多 发送图片 发送位置 切换职位 视频面试 置顶聊天 邀请投递 屏蔽聊天  -->
                <!-- <li class="more-item" @click="handleClickStopPropagation"> -->
                  <!-- <van-uploader  :preview-image="false" :max-size="2097152" @oversize="handleOversize" :after-read="handleSendImg" :accept="'image/*'" >
                    <div>
                      <div class="more-img bottom-more-img"></div>
                      <div class="more-text">发送图片</div>
                    </div>
                  </van-uploader> -->
                <!-- </li> -->
                <li class="more-item" @click="handleOpenMap" v-if="LoginType == 1">
                  <div class="more-img bottom-more-position"></div>
                  <div class="more-text">发送位置</div>
                </li>
                <li class="more-item" @click="handleSendResume" v-if="LoginType == 2">
                  <div class="more-img bottom-more-resume"></div>
                  <div class="more-text">发送简历</div>
                </li>
                <li class="more-item" v-if="LoginType == 1" @click="selectJobShow=true">
                  <div class="more-img bottom-more-switch" ></div>
                  <div class="more-text">切换职位</div>
                </li>

                <li class="more-item" @click="handleTopping">
                  <div class="more-img bottom-more-topping"></div>
                  <div class="more-text">{{stick == 1 ? '取消置顶' : '置顶聊天'}}</div>
                </li>
                <li class="more-item" v-if="LoginType == 2" @click="handleInterview">
                  <div class="more-img bottom-more-interview"></div>
                  <div class="more-text">面试安排</div>
                </li>
                <li class="more-item" v-if="LoginType == 1" @click="handleInvitationDelivery">
                  <div class="more-img bottom-more-deliver"></div>
                  <div class="more-text">邀请投递</div>
                </li>
                <li class="more-item" @click="handleSendVideoInterview">
                  <div class="more-img bottom-more-video"></div>
                  <div class="more-text">视频面试</div>
                </li>
                <li class="more-item" @click.stop="handleBlackList">
                  <div class="more-img bottom-more-shield"></div>
                  <div class="more-text">屏蔽聊天</div>
                </li>
              </ul>
              <ul v-show="isBottomText == 'emoji'" class="bottom-emoji-box">
                <li v-for="(emojiItem,index) in emojiAry" :key="index" @click.stop="handleEmojiClick(emojiItem)">{{emojiItem}}</li>
              </ul>
            </div>
          </div>
        </div>
        <!-- 底部输入框结束 -->
    </div>
    <!-- 底部常用语弹窗开始 -->
    <van-popup v-model="commonPopup" position="bottom" :style="{ height: '370px' }">
      <CommonWords
        ref="CommonWords"
        @handleSetMessageText="handleSetMessageText"
        @handleCommonClose="handleCommonClose"
        @handleOpenSetCommonPopup="handleOpenSetCommonPopup"
      >
      </CommonWords>
    </van-popup>
    <!-- 底部常用语弹窗结束 -->

    <!-- 常用语设置弹窗开始 -->
    <CommonIndex ref="CommonIndex" @updataCommonList="updataCommonList"></CommonIndex>
    <!-- 常用语设置弹窗结束 -->

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

    <!-- 发送位置开始 -->
    <van-popup v-model="mapPopup" position="right" :style="{ width:'100%', height: '100%' ,overflow: 'hidden'}">
      <SetMap @handleMapBack="mapPopup=false" @handleSendMap="handleSendMap"></SetMap>
    </van-popup>
    <!-- 发送位置结束 -->

    <!-- 切换职位开始 -->
    <van-overlay :show="selectJobShow" @click="show = false" z-index="3" :lock-scroll="false">
      <SelectJob @handleSelectJob="handleSelectJob" @handleCloseSelectJob="handleCloseSelectJob" @getJobName="getJobName" :isSelectJob="true" :chatid="imChatid" :companyId="imShowParams.companyId"></SelectJob>
    </van-overlay>
    <!-- 切花职位结束 -->

    <!-- 面试安排开始 -->
    <InterviewArrange ref="InterviewArrange"></InterviewArrange>
    <!-- 面试安排结束 -->

    <!-- 投递简历开始 -->
    <!-- <DeliveryResume ref="DeliveryResume"></DeliveryResume> -->
    <!-- 投递简历结束 -->

    <!-- 面试邀请开始 -->
    <van-popup
      :lazy-render="false"
      v-model="showInvite"
      position="right"
      :overlay="false"
      style="width:100%;height:100%"
    >
      <AddInvitation
        ref="child"
        from="detail"
        :apply_fullname="imShowParams.name"
        :resume_id="resumeId"
        @closePopup="showInvite = false"
      ></AddInvitation>
    </van-popup>
    <!-- 面试邀请结束 -->
    <!-- 交换微信号输入框开始 -->
    <van-dialog v-model="isWechat" title="交换微信" @confirm="handleExchangeWechat" :show-cancel-button="true" @cancel="isWechat = false">
      <van-field type="text" label="" placeholder="请输入您的微信号" v-model.trim="wechatText"></van-field>
    </van-dialog>
    <!-- 交换微信号输入框结束 -->
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import {data} from '../../assets/js/emoji.json'
import {mapState, mapActions, mapMutations} from 'vuex'
import MessageList from './components/MessageList.vue'
import SetMap from './components/SetMap.vue'
import SelectJob from './components/SelectJob.vue'
import CommonWords from './components/CommonWords.vue'
import CommonIndex from './components/CommonIndex.vue'
import InterviewArrange from './components/InterviewArrange.vue'
// import DeliveryResume from './components/DeliveryResume.vue'
import AddInvitation from '@/components/AddInvitation.vue'
export default {
  name: 'ImShow',
  data () {
    return {
      // 头部提示关闭后
      isImListClass: false,
      // 底部更多操作开启
      isBottomMoreClass: false,
      // 底部操作 区分点击的是标签还是更多 emoji(表情)/more(更多)
      isBottomText: 'more',
      // 表情数组
      emojiAry: data.split(','),
      // input 框输入内容
      msgText: '',
      // 底部更多按钮与发送按钮切换
      isSend: false,
      // 下拉加载状态
      imIsLoading: false,
      // 常用语弹窗
      commonPopup: false,
      // 心跳定时器
      timer: '',
      // 当前聊天id
      imChatid: '',
      // 绑定微信弹窗状态
      bindWeixinShow: false,
      // 选择位置弹窗状态
      mapPopup: false,
      // 切换职位弹窗
      selectJobShow: false,
      // 当前沟通职位id
      changJobName: '',
      // 编辑/添加常用语
      curtCommonItem: {},
      // 面试邀请弹窗状态
      showInvite: false,
      // 置顶/取消置顶
      stick: 1,
      // 交换微信号输入框
      isWechat: false,
      // 交换微信号输入框双向绑定值
      wechatText: '',
      // 同意交换微信号数据
      WechatAgreeData: {},
      // 取消/设置黑名单
      isBlackList: false,
      // 公告是否展示
      alert_warning: true,
      // 二维码图片
      scanQrcodeImg: ''
    }
  },
  components: {
    MessageList,
    SetMap,
    SelectJob,
    CommonWords,
    CommonIndex,
    InterviewArrange,
    AddInvitation
    // DeliveryResume
  },
  computed: {
    // 动态计算底部更多按钮class 类名
    moreClass () {
      var moreClass = 'bottom-more'
      // isSend 为true 展示发送按钮 更多按钮消失
      if (this.isSend) {
        moreClass = 'bottom-more none-more'
      } else {
        moreClass = 'bottom-more'
      }
      return moreClass
    },
    // 列表类名
    imClass () {
      var listClass = 'im-list-box-1'
      if (this.alert_warning || this.im_notice == '') {
        if (this.LoginType == 1) {
          listClass = 'im-list-box-no-notice-1'
        } else if (this.LoginType == 2) {
          listClass = 'im-list-box-no-notice-2'
        }
      } else {
        if (this.LoginType == 1) {
          listClass = 'im-list-box-no-notice-1'
        } else if (this.LoginType == 2) {
          listClass = 'im-list-box-no-notice-2'
        }
      }
      if (!this.isImListClass && this.alert_warning) {
        if (this.LoginType == 1) {
          listClass = 'im-list-box-1'
        } else if (this.LoginType == 2) {
          listClass = 'im-list-box-2'
        }
      } else {
        if (this.LoginType == 1) {
          listClass = 'im-list-box-no-notice-1'
        } else if (this.LoginType == 2) {
          listClass = 'im-list-box-no-notice-2'
        }
      }

      if (this.isBottomMoreClass) {
        listClass += ' im-list-box-ac'
      }
      return listClass
    },
    ...mapState({
      imToken: state => state.imToken,
      LoginType: state => state.LoginType,
      newChatList: state => state.newChatList,
      jobname: state => state.imShowParams.jobname,
      headerTitle: state => state.imShowParams.name,
      resumeId: state => state.imShowParams.resumeid,
      jobid: state => state.imShowParams.jobid,
      imShowParams: state => state.imShowParams,
      isInBlackObj: state => state.isInBlackObj,
      im_notice: state => state.config.im_notice
    })
  },
  watch: {
    msgText (val) {
      if (val != '') {
        this.isSend = true
      } else {
        this.isSend = false
      }
    },
    newChatList (val) {
      if (val.type == 'image') {
        this.isBottomMoreClass = false
      }
    },
    isInBlackObj: {
      handler (newVal) {
        // 1 "您已屏蔽对方，如需继续聊天请先解除屏蔽。"
        // 0  "您已被对方加入黑名单，无法继续与TA聊天哦。"
        if (newVal.cancel_enable == 1) {
          this.$dialog({
            title: '系统提示',
            message: newVal.message,
            confirmButtonText: '解除屏蔽',
            showCancelButton: true
          }).then(() => {
            http.post(api.del_blacklist, {token: this.imToken, chatid: newVal.chatid}).then(res => {
              this.setBlackObj({
                chatid: '',
                cancel_enable: '2',
                message: ''
              })
              this.isBlackList = false
            })
          })
        } else if (newVal.cancel_enable == 0) {
          this.$dialog({
            title: '系统提示',
            message: newVal.message,
            showCancelButton: true
          }).then(() => {
            this.setBlackObj({
              chatid: '',
              cancel_enable: '2',
              message: ''
            })
          })
        }
      },
      immediate: true,
      deep: true
    }
  },
  created () {
    this.alert_warning = this.$store.state.config.im_notice != ''
    this.imChatid = this.$route.params.userid
    this.setimChatid(this.$route.params.userid)
    this.imWindowGlobal()
    this.getScanQrcodeImg()
  },
  methods: {
    ...mapMutations(['setImSelfAvatar', 'setImTargetUserinfo', 'setImShowParams', 'setimChatid', 'setBlackObj']),
    ...mapActions(['initWebSocket', 'webSocket_send']),
    /**
     * 获取聊天列表
     */
    getUserChatList () {
      http.post(api.chatList, {token: this.imToken}).then((res) => {
        res.data.items.forEach(item => {
          if (item.chat_id == this.imChatid) {
            this.stick = item.stick
          }
        })
      })
    },
    /**
   * 绑定微信二维码
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
            this.getUserInfo()
            // 获取会话列表
            this.getChatList()
            // 检测是否在黑名单中
            this.handleIsBlackList()
            this.getUserChatList()
          }
          if (res.data.next == 'disabled') { // 不能使用功能
            // this.$notify({type: 'danger', message: res.message})
            this.$dialog({
              title: '系统提示',
              message: res.message,
              showConfirmButton: true
            }).then(() => {
              this.$router.push({path: '/index'})
            })
            return false
          }
          if (res.data.next == 'complete_resume') { // 编辑简历
            this.$router.push('/member/personal/resume')
            return false
          }
          if (res.data.next == 'bind_weixin') { // 绑定微信
            this.bindWeixinShow = true
            return false
          }

          // if (res.data.next == 'choose_job') { // 选择职位
          //   this.selectJobShow = true
          //   return false
          // }
        } else {
          this.$notify({type: 'danger', message: res.message})
        }
      })
    },
    /**
     * 超出最大大小
     */
    handleOversize (file) {
      // this.$notify('图片最大大小为2MB')
      this.$toast('图片最大大小为2MB')
      this.isBottomMoreClass = false
    },
    /**
     * 获取用户信息
     */
    getUserInfo () {
      http.get(api.im_userinfo).then(res => {
        this.setImSelfAvatar(res.data.avatar)
      })
    },
    /**
     * 会话列表 查找当前与那个用户聊天
     */
    getChatList () {
      http.post(api.chatList, {token: this.imToken}).then(res => {
        let chatList = res.data.items
        let currentUser = chatList.filter(res => {
          return res.chat_id == this.imChatid
        })[0]
        this.setImTargetUserinfo(currentUser)
      })
    },

    /**
     * 头部提示关闭
     */
    handleNotice () {
      this.isImListClass = true
    },
    /**
     * 底部更多展示
     * @type String emoji(表情)/more(更多)
     */
    handleBottmeMore (type) {
      if (!this.isBottomMoreClass) {
        this.isBottomMoreClass = !this.isBottomMoreClass
      }
      this.isBottomText = type
    },
    /**
     * 点击屏幕时底部操作面板收起
     */
    handleWrapperClick () {
      this.isBottomMoreClass = false
    },
    /**
     * 表情点击
     * @emojiItem String  当前点击的表情项
     */
    handleEmojiClick (emojiItem) {
      this.msgText += emojiItem
    },
    /**
     * 底部input框获得焦点事件
     */
    handleMsgInput () {
      // 更多面板/表情面板开启 在输入时则关闭面板
      if (this.isBottomMoreClass) {
        this.isBottomMoreClass = false
      }
    },
    /**
     * 顶部input框失去焦点
     */
    handleMsgInputBlur () {
      this.isSend = false
    },
    /**
     * 发送文字消息
     */
    handleSendText () {
      if (this.msgText === '') {
        return false
      }
      var msgObj = {
        controller: 'SendText',
        action: 'index',
        args: {
          token: this.imToken,
          chatid: this.imChatid,
          content: this.msgText
        }
      }
      // var msgStr = JSON.stringify(msgObj)
      this.webSocket_send(msgObj)
      this.msgText = ''
    },
    /**
     * 点击发送图片处理冒泡
     */
    handleClickStopPropagation () {
      event.stopPropagation()
    },
    /***
     * 发送图片
     * @file Object 文件信息
     */
    handleSendImg (file) {
      let base64 = file.content
      var msgObj = {
        controller: 'SendImage',
        action: 'index',
        args: {
          token: this.imToken,
          chatid: this.imChatid,
          content: base64
        }
      }
      this.webSocket_send(msgObj)
      this.isBottomMoreClass = false
    },
    /**
     * 加入黑名单
     */
    handleBlackList () {
      let utype_cn = this.LoginType == 1 ? '个人' : '企业'
      if (this.isBlackList == false) {
        this.$dialog.confirm({
          title: '系统提示',
          message: '屏蔽' + utype_cn + '后，该' + utype_cn + '将无法与您继续沟通，如需解除屏蔽，请在职聊设置里修改。'
        })
          .then(() => {
            http.post(api.add_blacklist, {token: this.imToken, chatid: this.imChatid, jobname: this.jobname}).then(res => {
              if (parseInt(res.code) === 200) {
                this.$notify({type: 'success ', message: res.message})
                this.isBlackList = true
              } else {
                this.$notify({type: 'error ', message: res.message})
                this.isBlackList = false
              }
            })
          })
          .catch(() => {})
      } else {
        this.$dialog.confirm({
          title: '系统提示',
          message: '已在黑名单中请勿重复添加'
        }).then(() => {

        }).catch()
      }
    },
    /**
     * 发送简历
     */
    handleSendResume () {
      var msgObj = {
        controller: 'SendResume',
        action: 'index',
        args: {
          token: this.imToken,
          chatid: this.imChatid
        }
      }
      this.webSocket_send(msgObj)
    },
    /**
     * 发送视频面试
     */
    handleSendVideoInterview () {
      this.$toast('功能正在开发中')
      // console.log('视频面试')
    },
    /**
     * 置顶聊天
     */
    handleTopping () {
      var messageText = ''
      // 默认true
      if (this.stick == 0) {
        this.stick = 1
        messageText = '确认把' + this.headerTitle + '设为置顶？'
      } else {
        this.stick = 0
        messageText = '确认把' + this.headerTitle + '取消置顶？'
      }
      this.$dialog.confirm({
        title: '系统提示',
        message: messageText
      })
        .then(() => {
          http.post(api.chatStick, {token: this.imToken, chat_id: this.imChatid, stick: this.stick}).then(res => {
            if (parseInt(res.code) == 200) {
              this.$notify({
                type: 'success',
                message: res.message
              })
            } else {
              this.$notify({
                type: 'error',
                message: res.message
              })
            }
          })
        })
        .catch(() => {})
        // 取消置顶
    },
    /**
     * 打开发送位置弹窗
     */
    handleOpenMap () {
      this.mapPopup = true
    },
    /**
     * 发送位置
     * @mapData 位置信息 (title,lat,lng,address)
     */
    handleSendMap (mapData) {
      var msgObj = {
        controller: 'SendMap',
        action: 'index',
        args: {
          token: this.imToken,
          chatid: this.imChatid,
          lat: mapData.lat,
          lng: mapData.lng,
          title: mapData.title,
          address: mapData.address
        }
      }
      this.webSocket_send(msgObj)
      this.mapPopup = false
    },
    /**
     * 交换电话
     */
    handleExchangeMobile () {
      this.$dialog.confirm({
        title: '交换手机号',
        message: '确认与对方交换电话吗？'
      }).then(res => {
        var msgObj = {
          controller: 'SendMobile',
          action: 'apply',
          args: {
            token: this.imToken,
            chatid: this.imChatid
          }
        }
        this.webSocket_send(msgObj)
      })
    },
    /**
     * 交换手机号拒绝
     */
    handleMobileRefuse (data) {
      var msgObj = {
        controller: 'SendMobile',
        action: 'refuse',
        args: {
          token: this.imToken,
          messageid: data.messageid
        }
      }
      this.webSocket_send(msgObj)
    },
    /**
     * 交换手机号同意
     */
    handleMobileAgree (data) {
      var msgObj = {
        controller: 'SendMobile',
        action: 'agree',
        args: {
          token: this.imToken,
          messageid: data.messageid
        }
      }
      this.webSocket_send(msgObj)
    },
    /**
     * 交换微信弹窗开启
     */
    handleOpenChangeWechat () {
      this.isWechat = true
      this.WechatAgreeData = {}
    },
    /**
     * 交换微信
     */
    handleExchangeWechat () {
      if (this.wechatText == '') {
        this.$notify('请输入微信号')
        return false
      }
      if (!this.WechatAgreeData.messageid) {
        let msgObj = {
          controller: 'SendWechat',
          action: 'apply',
          args: {
            token: this.imToken,
            chatid: this.imChatid,
            wechat: this.wechatText
          }
        }
        this.webSocket_send(msgObj)
      } else {
        let msgObj = {
          controller: 'SendWechat',
          action: 'agree',
          args: {
            token: this.imToken,
            messageid: this.WechatAgreeData.messageid,
            wechat: this.wechatText
          }
        }
        this.webSocket_send(msgObj)
      }
      this.wechatText = ''
    },
    /**
     * 交换微信号拒绝
     */
    handleWechatRefuse (data) {
      var msgObj = {
        controller: 'SendWechat',
        action: 'refuse',
        args: {
          token: this.imToken,
          chatid: this.chatid,
          messageid: data.messageid
        }
      }
      this.webSocket_send(msgObj)
    },
    /**
     * 交换微信号同意
     */
    handleWechatAgree (data) {
      this.isWechat = true
      this.WechatAgreeData = data
    },
    // 查看简历
    handleToResume () {
      this.$router.push('/resume/' + this.resumeId)
    },
    // 查看企业
    handleToCompany () {
      this.$router.push('/company/' + this.imShowParams.companyId)
    },
    // 邀请投递
    handleInvitationDelivery () {
      this.$dialog.confirm({
        showCancelButton: true,
        message: '确定邀请对方投递简历吗？',
        beforeClose: this.beforeInvitationDeliveryClose
      })
        .then(() => {

        })
        .catch(() => {
        })
    },
    /**
     * 邀请投递弹窗关闭前回调函数
     */
    beforeInvitationDeliveryClose (action, done) {
      if (action === 'confirm') {
        var msgObj = {
          controller: 'SendInvite',
          action: 'invite',
          args: {
            token: this.imToken,
            chatid: this.imChatid
          }
        }
        this.webSocket_send(msgObj)
        done()
      } else {
        done()
      }
    },
    /**
     * 组件中 isSelectJob 决定返回的值 true 说明是切换职位 false 说明是选择沟通职位
     * @isSelectJob Object 当前选择职位的信息
     */
    handleSelectJob (isSelectJob) {
      this.selectJobShow = false
      var msgObj = {
        controller: 'SendJob',
        action: 'index',
        args: {
          token: this.imToken,
          chatid: this.imChatid
        }
      }
      this.webSocket_send(msgObj)
    },
    /**
     * 选择职位弹窗关闭
     */
    handleCloseSelectJob () {
      this.selectJobShow = false
    },
    /**
     * 获取当前职位名称
     * @jobname 职位名称
     */
    getJobName (jobname) {
      this.changJobName = jobname
    },
    /**
     * 设置input内容为常用语
     * @msgText 常用语文字
     */
    handleSetMessageText (msgText) {
      this.msgText = msgText
      this.commonPopup = false
    },
    /**
     * 常用语列表开启
     */
    handleOpenCommonList () {
      this.commonPopup = true
      this.$nextTick(() => {
        this.$refs.CommonWords.getCommonList()
      })
    },
    /***
     * 常用语关闭
     */
    handleCommonClose () {
      this.commonPopup = false
    },
    /**
     * 更新常用语列表
     */
    updataCommonList () {
      this.$nextTick(() => {
        this.$refs.CommonWords.getCommonList()
      })
    },
    /**
     * 打开常用语设置面板
     */
    handleOpenSetCommonPopup () {
      // 关闭 常用语列表弹窗
      this.handleCommonClose()

      this.$refs.CommonIndex.handleOpenCommonList()
    },
    /**
     *面试安排
     */
    handleInterview () {
      this.$refs.InterviewArrange.handleOpenInterviewArrange()
    },
    /**
     * 打开投递简历弹窗
     */
    handleOpenDelivery () {
      // 投递简历弹窗
      // this.$refs.DeliveryResume.handleOpenDeliveryPopup()
      this.$dialog.confirm({
        title: '提示',
        message: '是否投递简历？'
      })
        .then(() => {
          http.post(api.jobApplyAdd, {
            jobid: this.jobid
          }).then(res => {
            if (res.code == 200) {
              this.$notify({ type: 'success', message: res.message })
            } else {
              this.$notify({ type: 'danger', message: res.message })
            }
          })
        })
        .catch(() => {})
    },
    /**
     * 面试邀请
     */
    handleInvitation () {
      if (this.resumeId <= 0) {
        return false
      }
      http.post(api.im_resumeInfo, {resumeid: this.resumeId}).then((res) => {
        // show_contact 1 下载过 可以面试邀请
        if (res.data.show_contact == 1) {
          this.showInvite = true
          this.$refs.child.initCB()
        } else {
          this.$dialog({
            title: '系统提示',
            message: '您需要下载简历才能发起发起面试邀请， 是否去简历详情下载简历？'
          }).then(() => {
            this.$router.push({path: '/resume/' + res.data.id})
          }).catch(() => {

          })
        }
      })
    },
    /**
     * 检测是否在黑名单中
     */
    handleIsBlackList () {
      http.post(api.check_blacklist, {token: this.imToken, chatid: this.imChatid}).then((res) => {
        if (parseInt(res.code) === 200) {
          // res.data  == 0没有屏蔽
          if (res.data != 0) {
            this.$dialog.confirm({
              title: '系统提示',
              message: res.message,
              showCancelButton: true,
              confirmButtonText: '解除屏蔽'
            }).then(() => {
              // res.data  == 1 已屏蔽 需要解除
              // res.data  == 1 被对方屏蔽
              if (res.data == 1) {
                http.post(api.del_blacklist, {token: this.imToken, chatid: this.imChatid}).then(res => {
                })
              }
            }).catch(() => {

            })
          }
        }
      })
    },
    /**
     * 点开会话发送回执
     */
    sendReturnReceiptAll () {
      var msgObj = {
        controller: 'SendReturnReceipt',
        action: 'all',
        args: {
          token: this.imToken,
          chatid: this.imChatid
        }
      }
      this.webSocket_send(msgObj)
    },
    /**
     * 头部返回
     */
    handleBack () {
      this.$store.state.imChatid = ''
      this.$store.state.messagelist = []
      window.history.length > 1
        ? this.$router.go(-1)
        : this.$router.push('/')
    },
    // 【新增】职聊关键字屏蔽功能 zch 2022.10.18
    keywordReplace () {
      http
        .get(api.im_keyword_replace, {content: this.msgText})
        .then(res => {
          this.msgText = res.data
          this.handleSendText()
        }).catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
>>> .van-pull-refresh{
  background: #f8f8f8;
}
.im_wrapper{
  height: calc(100vh - 53px) ;
}
// 交换电话 交换微信 投递简历 查看企业 开始
.top-handle-box{
  display: flex;
  justify-content: center;
  color: #333;
  font-size: 14px;
  height: 85px;
  .item{
    display: flex;
    justify-content: center;
    flex: 1;
    height: 100%;
    padding-top: 53px;
  }
  .mobile{
    background: url("../../assets/images/im/mobile.png") no-repeat center 21px  /16px 22.5px;
  }
  .weixin{
    background: url("../../assets/images/im/weixin.png") no-repeat center 21px  /26.5px 22.5px;
  }
  .delivery{
    background: url("../../assets/images/im/delivery.png") no-repeat center 21px  /19px 22.5px;
  }
  .seeCompany{
    background: url("../../assets/images/im/seeCompany.png") no-repeat center 21px  /25.5px 22.5px;
  }
  .seeResume{
    background: url("../../assets/images/im/delivery.png") no-repeat center 21px  /19px 22.5px;
  }
  .interview{
    background: url("../../assets/images/im/interview.png") no-repeat center 21px  /19px 22.5px;
  }
}
//沟通职位
.current-job{
  border-top: 1px solid #f8f8f8;
  height: 40px;
  line-height: 40px;
  display: flex;
  font-size: 14px;
  padding: 0 10px 0  8px;
  .label{
    color: #999999;
    flex: 1.2;
  }
  .text{
    flex: 3;
    color: #333333;
  }
  .btn{
    flex: 1;
    text-align: center;
    span{
      color: #1787FB;
      border: 1px solid #1787FB;
      border-radius: 30px;
      padding:5px 12px;
    }
  }
}
.im-list-overflow{
  overflow: hidden;
}
// 聊天记录 默认高度
.im-list-box-1{ // 企业
  background: #F8F8F8;
  height: calc(100vh - 85px - 53.5px - 53px - 40px - 40px);
  overflow-y: auto;
  transition: transform .3s;
  padding-bottom: 40px;
}
.im-list-box-2{ // 个人
  background: #F8F8F8;
  height: calc(100vh - 85px - 53.5px - 53px - 40px);
  overflow-y: auto;
  transition: transform .3s;
  padding-bottom: 40px;
}
// 聊天记录 提示关闭后高度
.im-list-box-no-notice-1{ // 企业
  background: #F8F8F8;
  height: calc(100vh - 85px - 53.5px - 53px - 40px);
  overflow-y: auto;
  transition: transform .3s;
   padding-bottom: 40px;
}
.im-list-box-no-notice-2{ // 个人
  background: #F8F8F8;
  height: calc(100vh - 85px - 53.5px - 53px );
  overflow-y: auto;
  transition: transform .3s;
   padding-bottom: 40px;
}
// 聊天记录 操作按钮展示后高度
.im-list-box-bottom-more{
  background: #F8F8F8;
  height: calc(100vh - 85px - 53.5px - 194px);
  overflow-y: auto;
  transition: transform .3s;
}
// 底部更多按钮打开后
.im-list-box-ac{
  transform: translateY(-240px);
}
// 底部操作开始
.handle-bottom-box{
  background: #F7F7F7;
  // height: 53px;
  position: fixed;
  // bottom: 127px;
  bottom: -1px;
  left: 0;
  right: 0;
  overflow:hidden ;
  box-shadow: 0px 7px 28px 0px rgba(63, 63, 63, 0.69);
  transform: translateY(264px);
  transition: transform .3s;
  // 底部输入框
  .bottom-ipt-box{
    display: flex;
    .shortcut{
      width: 25px;
      height: 25px;
      margin: 14px 9px;
      background: url("../../assets/images/im/bottom-shortcut.png") no-repeat center center;
      background-size: 25px 25px;
    }
    .ipt{
      flex: 1;
      .ipt-inner{
        background: #fff;
        margin: 8px 0;
        height: 37.5px;
        padding: 0;
        display: flex;
        align-items: center;
        padding-left: 10px;
      }
    }
    .emoji{
      width: 25px;
      height: 25px;
      margin: 14px 9px;
      background: url("../../assets/images/im/bottom-emoji.png") no-repeat center center;
      background-size: 25px 25px;
    }
    .bottom-more{
      width: 25px;
      height: 25px;
      margin: 14px 9px;
      background: url("../../assets/images/im/bottom-open.png") no-repeat center center;
      background-size: 25px 25px;
      transform: rotate(0deg);
      transition:transform,width .3s;
      opacity: 1;
      &.ac{
        transform: rotate(45deg);
      }
      &.none-more{
        width: 0;
        margin-left: 0;
        margin-right: 0;
        opacity: 0;
      }
    }
    .send{
      background: #1787fb;
      color: #fff;
      font-size: 14px;
      margin: 14px 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 3px;
      width: 50px;
      text-align: center;
      transition: all .3s;
      opacity: 1;
      white-space:nowrap;
      &.none-send{
        width: 0;
        margin-left:0 ;
        margin-right: 0;
        opacity: 0;
      }
    }
  }
  .bottom-more-wrapper{
    // height: 265px;
    // 底部更多
    .bottom-more-box{
      display: flex;
      flex-wrap: wrap;
      height: auto;
      padding: 20px 32px 0px 32px;
      .more-item{
        display: flex;
        flex-flow: column;
        margin-right: 15px;
        line-height: normal;
        &:nth-child(4n){
          margin-right: 0;
        }
        .more-img{
          width:66px;
          height:66px;
          &.bottom-more-img{
            background: url('../../assets/images/im/bottom-more-img.png') no-repeat center center /66px 66px;
          }
          &.bottom-more-position{
            background: url('../../assets/images/im/bottom-more-position.png') no-repeat center center /66px 66px;
          }
          &.bottom-more-resume{
            background: url('../../assets/images/im/bottom-more-resume.png') no-repeat center center /66px 66px;
          }
          &.bottom-more-switch{
            background: url('../../assets/images/im/bottom-more-switch.png') no-repeat center center /66px 66px;
          }
          &.bottom-more-video{
            background: url('../../assets/images/im/bottom-more-video.png') no-repeat center center /66px 66px;
          }
          &.bottom-more-topping{
            background: url('../../assets/images/im/bottom-more-topping.png') no-repeat center center /66px 66px;
          }
          &.bottom-more-interview{
            background: url('../../assets/images/im/bottom-more-interview.png') no-repeat center center /66px 66px;
          }
          &.bottom-more-deliver{
            background: url('../../assets/images/im/bottom-more-deliver.png') no-repeat center center /66px 66px;
          }
          &.bottom-more-shield{
            background: url('../../assets/images/im/bottom-more-shield.png') no-repeat center center /66px 66px;
          }
        }
        .more-text{
          color:#999999 ;
          font-size: 13px;
          height: 20px;
          text-align: center;
          margin-bottom: 31px;
          margin-top: 5px;
        }
      }
    }
    .bottom-emoji-box{
      height:264px;
      display:flex;
      flex-wrap: wrap;
      overflow-y:auto;
      padding: 0 15px;
      li{
        flex: 0 0 14.25%;
      }
    }
  }

}
.handle-bottom-box-ac{
  transform: translateY(0);
  // height: 330px;
}
// 底部操作结束

// 绑定微信开始
.bind-weixin-box{
  display: flex;
  flex-direction: column;
  text-align: center;
  padding: 10px;
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
.wxchat-tips{
  padding: 10px 0 10px 30px ;
  color: #C9C9C9;
  font-size:13px ;
}
</style>
