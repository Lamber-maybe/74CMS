<template>
  <div class="im-content">
    <div class="im-frame">
      <div class="main-empty" v-show="noChat===true">
        <div class="emp_text">与您沟通过的信息都会在左侧列表中显示</div>
      </div>
      <div class="clear"></div>
      <div style="width:1190px;display: flex" v-show="noChat===false">
        <!--左侧会话列表-->
        <ChatList :websocketOk="websocketOk" ref="chatlist" @showPhrase="showPhrase=true" @setTatgetinfo="setTatgetinfo" @changeNoChat="changeNoChat" />
        <!--右侧空信息-->
        <div class="right-empty" v-if="target_userinfo.nickname==''">
          <div class="emp_text">与您沟通过的信息都会在左侧列表中显示</div>
        </div>
        <!--右侧聊天记录列表-->
        <div class="right" v-show="target_userinfo.nickname!=''">
          <div class="r-top">
            <!--企业会员时显示tab标签-->
            <div class="tablist" v-if="$store.state.LoginType==1">
              <div class="tab select">聊天</div>
              <div class="tab" @click="locationToResume">在线简历</div>
              <div class="clear"></div>
            </div>
            <!--个人会员时显示企业联系人和企业名称-->
            <div class="cominfo" v-else>
              <div class="contact">{{ target_userinfo.nickname }}</div>
              <div class="companyname">{{ target_userinfo.detail }}</div>
              <div class="clear"></div>
            </div>
            <div class="right-op">
              <div class="op-stick" v-if="target_userinfo.stick==0" @click="setStick(1)">置顶</div>
              <div class="op-stick" v-else @click="setStick(0)">取消置顶</div>
              <el-popover
                placement="top-end"
                trigger="click"
                @show="getInterviewInfo()"
              >
                <div slot="reference" class="op-interview">面试安排</div>
                <div class="interview-box" v-if="interviewInfoList.length>0" v-loading="interviewInfoLoading">
                  <div class="tit">面试安排</div>
                  <el-carousel trigger="click" :arrow="interviewInfoList.length>1?'always':'never'">
                    <el-carousel-item v-for="(item,index) in interviewInfoList" :key="index">
                      <div class="info">
                        <div class="line"><div class="label">面试职位：</div><div class="val">{{item.jobname}}</div><div class="clear"></div></div>
                        <div class="line"><div class="label">公司名称：</div><div class="val">{{item.companyname}}</div><div class="clear"></div></div>
                        <div class="line"><div class="label">面试时间：</div><div class="val">{{item.interview_time}}</div><div class="clear"></div></div>
                        <div class="line"><div class="label">面试地址：</div><div class="val">{{item.address}}</div><div class="clear"></div></div>
                        <div class="line"><div class="label">联系电话：</div><div class="val">{{item.tel}}（{{item.contact}}）</div><div class="clear"></div></div>
                        <div class="line" v-if="item.note!=''">
                          <div class="label">联系备注：</div>
                          <div class="val" v-if="item.note_.length>14">
                            {{item.note}}
                            <el-popover
                              placement="top"
                              title=""
                              width="300"
                              trigger="hover"
                              :content="item.note_">
                              <span style="color:#FF5F27" slot="reference">[查看]</span>
                            </el-popover>
                          </div>
                          <div class="val" v-else>{{item.note}}</div>
                          <div class="clear"></div>
                        </div>
                      </div>
                    </el-carousel-item>
                  </el-carousel>
                </div>
                <div class="interview-box empty" v-else v-loading="interviewInfoLoading">
                  <div class="tip">{{this.$store.state.LoginType==1?'您还没有向该求职者发出面试邀请':'该企业未向您发出面试邀请'}}</div>
                </div>
              </el-popover>
              <div class="clear"></div>
            </div>
          </div>
          <!--聊天主界面-->
          <div class="main-container">
            <!--企业会员时显示对方简历详情-->
            <div class="resumeinfo" v-if="$store.state.LoginType==1">
              <div v-if="resumeinfo.fullname">
                <div class="fullname">{{ resumeinfo.fullname }}</div>
                <div class="detail">{{ resumeinfo.sex_text }} · {{ resumeinfo.age }}岁 · {{ resumeinfo.education_text }} · {{ resumeinfo.experience_text }}</div>
                <div class="clear"></div>
                <div class="intention"><span class="label">期望职位</span><span class="item">{{ resumeinfo.intention_category }}</span><span class="item">{{ resumeinfo.intention_district }}</span><span class="wage">{{ resumeinfo.intention_wage }}</span></div>
              </div>
              <div v-else style="line-height: 50px;color: #999;">正在加载简历信息...</div>
              <div class="floatinfo">
                <div class="jobname">沟通职位：{{jobinfo.jobname}}<span class="change" @click="selectJob()"></span></div>
                <div class="starttime">聊天开始于 {{ chat_starttime }}</div>
              </div>
            </div>
            <!--个人会员时显示职位详情-->
            <div class="jobinfo" v-else>
              沟通职位：<span class="jobname" @click="toJobDetail">{{jobinfo.jobname?jobinfo.jobname:'--'}}</span
              ><span class="wage">{{jobinfo.wage_text?jobinfo.wage_text:''}}</span
              ><span class="starttime">聊天开始于 {{ chat_starttime }}</span>
            </div>
              <el-alert
                v-if="alert_warning===true"
                :title="$store.state.config.im_notice"
                type="warning"
                @close="alert_warning=false"
              >
              </el-alert>
            <!--聊天记录列表-->
            <MessageList @sendReturnReceiptAll="sendReturnReceiptAll" @showHellomsg="showHellomsg" :chat_starttime="chat_starttime" :messagelist_height="messagelist_height" @applyJob="applyJob" @agreeWechat="agreeWechat" @refuseWechat="refuseWechat" @agreeMobile="agreeMobile" @refuseMobile="refuseMobile" ref="messagelist" />

            <!--被屏蔽时-->
            <div class="disabled-opzone" v-if="disabled!=0">
              <div class="msg">{{disabled_msg}}</div>
              <el-button class="btn" type="primary" round v-if="disabled==1" @click="cancelBlacklist">解除屏蔽</el-button>
              <el-button class="btn" type="primary" round v-else-if="$store.state.LoginType==1" @click="jumpTo('resumelist')">搜索简历</el-button>
              <el-button class="btn" type="primary" round v-else-if="$store.state.LoginType==2" @click="jumpTo('joblist')">搜索职位</el-button>
            </div>
            <!--正常聊天操作界面-->
            <div class="opzone" v-else>
              <div class="optop">
                <div class="blacklist-add" @click="addBlacklist">屏蔽聊天</div>
                <!--表情-->
                <el-popover
                  placement="top-start"
                  width="430"
                  trigger="click"
                >
                  <el-tooltip slot="reference" effect="dark" content="表情" placement="top">
                    <div
                      class="i face"
                    ></div>
                  </el-tooltip>
                  <Emoji @chooseEmojiDefault="chooseEmojiDefault"></Emoji>
                </el-popover>
                <!--常用语-->
                <el-popover
                  placement="top-start"
                  width="400"
                  trigger="click">
                  <div class="quickbox">
                    <div class="quicktitle">
                      <div class="tit">常用语</div>
                      <div class="conf" @click="showPhrase=true">设置</div>
                    </div>
                    <div class="quicklist">
                      <div class="quickitem" v-for="(item,index) in phrase_list" :key="index" @click="message=item.content">{{item.content}}</div>
                    </div>
                  </div>
                  <el-tooltip slot="reference" effect="dark" content="常用语" placement="top">
                    <div class="i phrase"></div>
                  </el-tooltip>
                </el-popover>
                <!--发送位置-->
                <el-tooltip v-if="$store.state.LoginType==3" slot="reference" effect="dark" content="发送位置" placement="top">
                  <div class="i location" @click="showMap=true"></div>
                </el-tooltip>
                <!--发送简历-->
                <el-tooltip v-if="$store.state.LoginType==2" slot="reference" effect="dark" content="发送简历" placement="top">
                  <div class="i card" @click="sendResumeCard"></div>
                </el-tooltip>
                <!--邀请投递-->
                <el-popover
                  placement="top"
                  width="200"
                  v-model="visibleInvite"
                   v-if="$store.state.LoginType==1"
                >
                  <p style="margin-top:10px;">确定邀请对方投递简历吗？</p>
                  <div style="text-align: center; margin: 10px 0">
                    <el-button size="mini" @click="visibleInvite=false">取消</el-button>
                    <el-button type="primary" size="mini" @click="sendInviteCard">确定</el-button>
                  </div>
                  <el-tooltip slot="reference" effect="dark" content="邀请投递" placement="top">
                    <div class="i card"></div>
                  </el-tooltip>
                </el-popover>
                <!--交换手机-->
                <el-popover
                  placement="top"
                  width="200"
                  v-model="visibleMobile"
                >
                  <p style="margin-top:10px;">确定与对方交换手机号吗？</p>
                  <div style="text-align: center; margin: 10px 0">
                    <el-button size="mini" @click="visibleMobile=false">取消</el-button>
                    <el-button type="primary" size="mini" @click="sendMobileApply">确定</el-button>
                  </div>
                  <el-tooltip slot="reference" effect="dark" content="交换电话" placement="top">
                    <div class="i mobile"></div>
                  </el-tooltip>
                </el-popover>
                <!--交换微信-->
                <el-popover
                  placement="top"
                  width="240"
                  v-model="visibleWechat"
                >
                  <p style="margin:10px 0;">确定与对方交换微信吗？</p>
                  <el-input v-model.trim="input_wechat" size="small" placeholder="请输入您的微信号"></el-input>
                  <div style="text-align: center; margin: 14px 0 10px">
                    <el-button size="small" @click="visibleWechat=false">取消</el-button>
                    <el-button type="primary" size="small" @click="sendWechatApply">确定</el-button>
                  </div>
                  <el-tooltip slot="reference" effect="dark" content="交换微信" placement="top">
                    <div class="i wechat"></div>
                  </el-tooltip>
                </el-popover>
                <div class="clear"></div>
              </div>
              <!--输入内容区域-->
              <div class="content">
                <!-- <input type="file" name="img"  @change="beforeAvatarUpload($event)"  accept="image/*" ref="fileinput"> -->
                <el-input
                  @keydown.enter.native="keyDown"
                  class="input"
                  type="textarea"
                  placeholder="说点什么...（为了您的个人隐私安全，请不要轻易向对方泄露您的联系方式）"
                  v-model.trim="message"
                >
                </el-input>
                <div class="btn">
                  <div
                    class="submit"
                    :class="enableSubmit === true ? '' : 'disabled'"
                    @click="keywordReplace"
                  >
                    发送
                  </div>
                  <div class="tip">按Enter键发送，按Ctrl+Enter键换行</div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <!--发送位置弹窗-->
    <el-dialog title="发送位置" width="837px" :visible.sync="showMap" v-if="showMap" :close-on-click-modal="false">
      <Mapset @comfirmMapItem="sendMap"></Mapset>
    </el-dialog>
    <!--常用语弹窗-->
    <el-dialog title="常用语管理" width="800px" :visible.sync="showPhrase" v-if="showPhrase" :close-on-click-modal="false">
      <PhraseConf :dataset="phrase_list"></PhraseConf>
    </el-dialog>
    <!--切换职位弹窗-->
    <el-dialog title="切换职位" width="576px" :visible.sync="showSelectJob" v-if="showSelectJob" :close-on-click-modal="false">
      <SelectJob @changeJob="changeJob" :dataset="joblist"></SelectJob>
    </el-dialog>
    <el-dialog title="系统提示" :visible.sync="showBindWeixin" width="450px" :center="true" :before-close="handlerCloseBindWeixin" :close-on-press-escape="false" :close-on-click-modal="false">
       <BindWeixin></BindWeixin>
    </el-dialog>
  </div>
</template>

<script>

import { isJSON } from '@/utils/index'
import Emoji from "./components/emoji.vue";
import SelectJob from './components/SelectJob'
import Mapset from './components/Mapset'
import PhraseConf from './components/PhraseConf'
import ChatList from './components/ChatList'
import MessageList from './components/MessageList'
import BindWeixin from './components/BindWeixin'
import http from "@/utils/http";
import api from "@/api";
export default {
  name: "Im",
  components: {
    Emoji,
    ChatList,
    MessageList,
    Mapset,
    PhraseConf,
    SelectJob,
    BindWeixin
  },
  data() {
    return {
      noChat:true,
      bind_timer:'',
      showBindWeixin:false,
      interviewInfoLoading:false,
      reConnectTime:0,
      websocketOk:false,//ws是否初始化完成
      selfAvatar:'',
      showPhrase:false,
      showMap:false,
      showSelectJob:false,
      alert_warning:true,
      visibleInvite:false,
      visibleMobile:false,
      visibleWechat:false,
      enableSubmit: false,
      messagelist_height:'',
      input_wechat:'',
      ws: null,
      timer: null,
      joblist:[],
      phrase_list:[],
      interviewInfoList:[],
      jobinfo:{},
      resumeinfo:{},
      message: "",
      chat_starttime: "",
      target_userinfo: {
        nickname: "",
        avatar: "",
        detail: "",
        stick:0
      },
      disabled:0,
      disabled_msg:''
    };
  },
  watch: {
    $route: {
      handler(val) {
        this.$store.state.imChatid = val.query.chat===undefined?'':val.query.chat
        this.message = ''
      },
      // 深度观察监听
      deep: true
    },
    message(val) {
      if (val == "") {
        this.enableSubmit = false;
      } else {
        this.enableSubmit = true;
      }
    },
    alert_warning(){
      this.initMessageListHeight()
    }
  },
  mounted() {
    this.initMessageListHeight()
    this.timer = setInterval(this.ping, 30000);
  },
  beforeDestroy() {
    clearInterval(this.timer);
    clearInterval(this.bind_timer);
  },
  created() {
    this.alert_warning = this.$store.state.config.im_notice==''?false:true
    this.imWindowGlobal()
  },
  destroyed() {
    window.ws.close(); // 离开路由之后断开websocket连接
  },
  methods: {
    changeNoChat(val){
      this.noChat = val
    },
    loopCheckBind(){
      http
        .get(api.im_check_bind, {})
        .then(res => {
          if(res.code==200){
            if(res.data==1){
              this.showBindWeixin = false
              clearInterval(this.bind_timer);
              this.imWindowGlobal()
            }
          }
        })
    },
    toJobDetail(){
      let url = this.$store.state.config.link_url_web.jobshow
      url = url.replace("_id_",this.$store.state.imJobid)
      window.open(url)
    },
    imWindowGlobal(){
      http
        .get(api.im_window_global, {})
        .then(res => {
          if(res.code==200){
            //检测通过
            if(res.data.next==''){
              this.$store.state.imChatid = this.$route.query.chat
              this.getSelfUserinfo()
              this.initWebSocket();
            }else{
              if(res.data.next=='disabled'){
                this.$alert(res.message, '系统提示', {
                  showClose: false,
                  showCancelButton:false,
                  type: 'warning',
                  callback: () => {
                    this.$router.push('/')
                  }
                });
                return false;
              }
              if(res.data.next=='complete_resume'){
                this.$alert(res.message, '系统提示', {
                  showClose: false,
                  showCancelButton:false,
                  confirmButtonText:'去完善简历',
                  type: 'warning',
                  callback: () => {
                    this.$router.push('/personal/resume')
                  }
                });
                return false;
              }
              if(res.data.next=='bind_weixin'){
                this.showBindWeixin = true
                this.bind_timer = setInterval(this.loopCheckBind, 3000);
                return false;
              }
            }
          }else{
            this.$message.error(res.message)
          }
        })
        .catch(() => {});
    },
    handlerCloseBindWeixin(){
      this.$router.push('/')
    },
    jumpTo(alias){
      let url = this.$store.state.config.link_url_web.resumelist
      if(alias=='joblist'){
        url = this.$store.state.config.link_url_web.joblist
      }
      window.open(url)
    },
    showHellomsg(){
      this.$refs.chatlist.openHellomsg()
    },
    locationToResume(){
      let url = this.$store.state.config.link_url_web.resumeshow
      url = url.replace("_id_",this.resumeinfo.id)
      window.open(url)
    },
    setStick(val){
      http
        .post(api.im_chat_stick, { token:this.$store.state.imToken,chat_id:this.$store.state.imChatid,stick:val })
        .then(res => {
          if(res.code==200){
            this.$message({type:'success',message:res.message})
            this.$refs.chatlist.getChatList(false);
            this.target_userinfo.stick = val
          }else{
            this.$message.error(res.message)
          }
        })
        .catch(() => {});
    },
    changeJob(item){
      this.showSelectJob = false
      this.$store.state.imJobid = item.id
      this.jobinfo = {
        jobname:item.jobname,
        wage_text:item.wage_text
      }
      this.sendJobCard()
    },
    selectJob(){
      this.showSelectJob = true
      http
        .get(api.im_joblist, {})
        .then(res => {
          this.joblist = [...res.data.items]
        })
        .catch(() => {})
    },
    initMessageListHeight(){
      if(this.$store.state.LoginType==1){
        if(this.alert_warning===true){
          this.messagelist_height = '422px'
        }else{
          this.messagelist_height = '474px'
        }
      }else{
        if(this.alert_warning===true){
          this.messagelist_height = '466px'
        }else{
          this.messagelist_height = '518px'
        }
      }
    },
    getPhraselist () {
      http
        .post(api.im_phrase_list, {token:this.$store.state.imToken})
        .then(res => {
          this.phrase_list = [...res.data.items]
          if (this.phrase_list.length > 0) {
            for (const key in this.phrase_list) {
              this.phrase_list[key].edit = false
            }
          }
        })
        .catch(() => {})
    },
    getSelfUserinfo(){
      http
        .get(api.im_userinfo, {})
        .then(res => {
          this.selfAvatar = res.data.avatar
        }).catch(()=>{})
    },
    keyDown(e) {
      if (e.ctrlKey && e.keyCode == 13) {
        //ctrl+enter
        this.message += "\n";
      } else {
        // 【新增】职聊关键字屏蔽功能 zch 2022.10.18
        this.keywordReplace();
      }
    },
    initWebSocket() {
      http
        .get(api.im_gettoken, {})
        .then(res => {
          this.$store.state.imToken = res.data
          this.websocketOk = true
          window.ws = new WebSocket('wss://imserv.v2.74cms.com');
          window.ws.onopen = this.websocketonopen;
          window.ws.onmessage = this.websocketonmessage;
          window.ws.onerror = this.websocketonerror;
          window.ws.onclose = this.websocketclose;
        }).catch(()=>{})
    },
    websocketonopen() {
      var msgObj = {
        controller: "Connect",
        action: "index",
        args: {
          token:this.$store.state.imToken
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
      this.getPhraselist()
    },
    websocketonerror() {
      // 连接建立失败重连
      this.reConnectTime++
      console.log('client：重新连接websocket，正在尝试第'+this.reConnectTime+'次')
      if(this.reConnectTime>=5){
        console.log('client：连接websocket失败，请刷新页面重试')
        return false;
      }
      this.initWebSocket()
    },
    scrollBottom(){
      this.$nextTick(() => {
          document.getElementById("J_message_list").scrollTop = document.getElementById("J_message_list").scrollHeight
      })
    },
    websocketonmessage(e) {
      if (isJSON(e.data)) {
        var data = JSON.parse(e.data);
        //当消息返回错误信息时
        if(data.error!==undefined){
          this.$message({
            message: data.error,
            type: 'error'
          });
          return false
        }
        var message = isJSON(data.content)?JSON.parse(data.content):data.content
        //当消息返回黑名单限制时
        if(data.type=='isInBlacklist'){
          if(data.cancel_enable==1){
            this.$confirm(message, '提示', {
              confirmButtonText: '确定解除',
              cancelButtonText: '取消',
              type: 'warning'
            }).then(() => {
              this.cancelBlacklist()
            }).catch(() => {

            });
          }else{
            this.$confirm(message, '提示', {
              confirmButtonText: '确定',
              cancelButtonText: '取消',
              type: 'warning'
            }).then(() => {

            }).catch(() => {

            });
          }

          return false;
        }
        //当前打开的聊天对象与收到的消息是同一个人
        if(this.$store.state.imChatid==data.chatid){
          if(data.type=='return_receipt_one'){
            this.$refs.messagelist.messageList.forEach((element,index) => {
              if(element.messageid==message.messageid){
                this.$refs.messagelist.messageList[index].readed = 1
              }
            });
          }else if(data.type=='return_receipt_all'){
            this.$refs.messagelist.messageList.forEach((element,index) => {
              this.$refs.messagelist.messageList[index].readed = 1
            });
          }else{
            var addObj = {
              self_side:data.self_side,
              avatar:data.self_side==1?this.selfAvatar:this.target_userinfo.avatar,
              type:data.type,
              message:message,
              addtime:data.addtime,
              readed:0,
              messageid:data.messageid
            }
            if(data.replace==1){
              this.$refs.messagelist.messageList.forEach((element,index) => {
                if(element.messageid==data.messageid){
                  this.$refs.messagelist.messageList[index].message = message
                }
              });
            }else{
              this.$refs.messagelist.messageList.push(addObj)
              if(data.self_side==1){
                this.scrollBottom()
              }else{
                /**
                 * 发送回执
                 */
                this.sendReturnReceiptOne(data)
                if(document.getElementById("J_message_list").offsetHeight+document.getElementById("J_message_list").scrollTop == document.getElementById("J_message_list").scrollHeight){
                  this.scrollBottom()
                }
              }
            }
          }
        }else{
          //当前打开的聊天对象与收到的消息不是同一个人，加未读红点标记
          this.$refs.chatlist.getChatList(false)
          // this.$refs.chatlist.addRedPoint(data.chatid)
        }
      }else{
        console.log('client：'+e.data);
      }
    },
    sendReturnReceiptOne(item){
      var msgObj = {
        controller: "SendReturnReceipt",
        action: "one",
        args: {
          token: this.$store.state.imToken,
          messageid:item.messageid
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
    },
    sendReturnReceiptAll(){
      var msgObj = {
        controller: "SendReturnReceipt",
        action: "all",
        args: {
          token: this.$store.state.imToken,
          chatid:this.$store.state.imChatid
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
    },
    agreeMobile(item){
      var msgObj = {
        controller: "SendMobile",
        action: "agree",
        args: {
          token: this.$store.state.imToken,
          messageid:item.messageid
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
    },
    refuseMobile(item){
      var msgObj = {
        controller: "SendMobile",
        action: "refuse",
        args: {
          token: this.$store.state.imToken,
          messageid:item.messageid
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
    },
    sendMobileApply(){
      var msgObj = {
        controller: "SendMobile",
        action: "apply",
        args: {
          token: this.$store.state.imToken,
          chatid: this.$store.state.imChatid
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
      this.visibleMobile = false
    },
    addBlacklist(){
      let utype_cn = this.$store.state.LoginType==1?'个人':'企业'
      this.$confirm('屏蔽'+utype_cn+'后，该'+utype_cn+'将无法与您继续沟通，如需解除屏蔽，请在职聊设置里修改。', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        http
        .post(api.im_blacklist_add, {token:this.$store.state.imToken,chatid:this.$store.state.imChatid,jobname:this.jobinfo.jobname})
        .then(res => {
          if(res.code==200){
            this.$message({type:'success',message:res.message})
          }
        })
        .catch(() => {});
      }).catch(() => {

      });
    },
    sendWechatApply(){
      if(this.input_wechat==''){
        this.$message({
          message: '请输入您的微信号',
          type: 'warning'
        });
        return false
      }
      var msgObj = {
        controller: "SendWechat",
        action: "apply",
        args: {
          token: this.$store.state.imToken,
          chatid: this.$store.state.imChatid,
          wechat:this.input_wechat
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
      this.visibleWechat = false
    },
    agreeWechat(item){
      var msgObj = {
        controller: "SendWechat",
        action: "agree",
        args: {
          token: this.$store.state.imToken,
          messageid:item.messageid,
          wechat:item.message.wechat
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
    },
    refuseWechat(item){
      var msgObj = {
        controller: "SendWechat",
        action: "refuse",
        args: {
          token: this.$store.state.imToken,
          chatid: this.$store.state.imChatid,
          messageid:item.messageid
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
    },
    sendMap(item){
      var msgObj = {
        controller: "SendMap",
        action: "index",
        args: {
          token: this.$store.state.imToken,
          chatid: this.$store.state.imChatid,
          lat:item.lat,
          lng:item.lng,
          title:item.title,
          address:item.address
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
      this.showMap = false
    },
    sendJobCard(){
      var msgObj = {
        controller: "SendJob",
        action: "index",
        args: {
          token: this.$store.state.imToken,
          chatid: this.$store.state.imChatid
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
    },
    sendResumeCard(){
      var msgObj = {
        controller: "SendResume",
        action: "index",
        args: {
          token: this.$store.state.imToken,
          chatid: this.$store.state.imChatid
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
    },
    sendInviteCard(){
      var msgObj = {
        controller: "SendInvite",
        action: "invite",
        args: {
          token: this.$store.state.imToken,
          chatid: this.$store.state.imChatid
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
    },
    applyJob(item){
      var msgObj = {
        controller: "SendInvite",
        action: "applyjob",
        args: {
          token: this.$store.state.imToken,
          messageid:item.messageid
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
    },
    // beforeAvatarUpload() {
    //   let that = this
    //   let file = this.$refs.fileinput.files[0]
    //   const isJPG = file.type === 'image/jpeg';
    //   const isLt2M = file.size / 1024 / 1024 < 2;

    //   if (!isJPG) {
    //     this.$message.error('上传头像图片只能是 JPG 格式!');
    //     return false;
    //   }
    //   if (!isLt2M) {
    //     this.$message.error('上传头像图片大小不能超过 2MB!');
    //     return false;
    //   }
    //   var reader = new FileReader();
    //       reader.readAsDataURL(file);//这一步是关键
    //       reader.onload = function (e){
    //           var base64=e.target.result;//这一步是关键
    //           var msgObj = {
    //             controller: "SendImage",
    //             action: "index",
    //             args: {
    //               token: that.$store.state.imToken,
    //               chatid: that.$store.state.imChatid,
    //               content: base64
    //             }
    //           };
    //           var msgStr = JSON.stringify(msgObj);
    //           window.ws.send(msgStr);

    //       }

    // },
    sendText() {
      if (this.enableSubmit === false) {
        return false;
      }
      if (this.message === "") {
        return false;
      }
      var msgObj = {
        controller: "SendText",
        action: "index",
        args: {
          token: this.$store.state.imToken,
          chatid: this.$store.state.imChatid,
          content: this.message
        }
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
      this.message = "";
    },
    websocketclose() {
      console.log("client：关闭连接");
    },
    ping() {
      var msgObj = {
        controller: "Ping",
        action: "index",
        args: {}
      };
      var msgStr = JSON.stringify(msgObj);
      window.ws.send(msgStr);
    },
    getResumeinfo(){
      if(this.$store.state.imResumeid==0){
        return false;
      }
      http
      .post(api.im_resume_detail, { resumeid:this.$store.state.imResumeid })
      .then(res => {
        this.resumeinfo = res.data
      }).catch(() => {});
    },
    getJobinfo(){
      http
      .post(api.im_job_detail, { jobid:this.$store.state.imJobid })
      .then(res => {
        if(res.data.next=='choose_job' && this.$store.state.LoginType==1){
          this.selectJob()
        }else{
          this.jobinfo = res.data
        }
      }).catch(() => {});
    },
    setTatgetinfo(item) {
      this.$store.state.imJobid = item.jobid
      this.$store.state.imResumeid = item.resumeid
      this.target_userinfo = {
        nickname: item.nickname,
        avatar: item.avatar,
        detail: item.detail,
        stick:item.stick
      };
      if(item.nickname!=''){
        this.getJobinfo()
        this.chat_starttime = item.addtime;
        this.$refs.messagelist.loadInit(item.chat_id);
        this.$router.push({ query: { chat:item.chat_id } });
        this.checkBlacklist(item.chat_id)
        this.getResumeinfo()
      }else{
        this.$refs.messagelist.loadInit('');
        this.$router.push({ query: { chat:'' } });
      }
    },
    getInterviewInfo(){
      this.interviewInfoLoading = true
      this.interviewInfoList = []
      http
      .post(api.im_interview_info, {resumeid:this.$store.state.imResumeid,jobid:this.$store.state.imJobid })
      .then(res => {
        this.interviewInfoList = res.data.items
        this.interviewInfoLoading = false
      }).catch(() => {});
    },
    checkBlacklist(chatid){
      http
      .post(api.im_blacklist_check, { token:this.$store.state.imToken,chatid:chatid })
      .then(res => {
        this.disabled = res.data
        this.disabled_msg = res.message
      }).catch(() => {});
    },
    cancelBlacklist(){
      http
        .post(api.im_blacklist_del, { token:this.$store.state.imToken,chatid:this.$store.state.imChatid })
        .then(res => {
          if(res.code==200){
            this.disabled = 0
            this.disabled_msg = ''
            this.$message({type:'success',message:res.message})
          }else{
            this.$message.error(res.message)
          }
        })
        .catch(() => {});
    },
    chooseEmojiDefault(e) {
      this.message += e;
    },
    // 【新增】职聊关键字屏蔽功能 zch 2022.10.18
    keywordReplace() {
      http
        .get(api.im_keyword_replace, {content:this.message})
        .then(res => {
          this.message = res.data
          this.sendText()
        }).catch(()=>{})
    }
  }
};
</script>

<style lang="scss" scoped>
.im-content {
  padding: 5px;
  box-sizing: border-box;
  margin-top: 14px;
}
.main-empty{
  flex:1;
  max-width: 1190px;
  min-width:678px;
  height: 762px;
  float: left;
  background: #ffffff url(../../assets/images/im/default_list_ico.png) center center no-repeat;
  border-bottom: 1px solid #f3f3f3;
  position: relative;
  .emp_text {
    color: #666;
    text-align: center;
    position: absolute;
    width: 100%;
    line-height: 22px;
    left: 0;
    bottom: 300px;
  }
}
.im-frame {
  max-width: 1190px;
  min-width:961px;
  margin:0 auto;
  height: 762px;
  background-color: #fff;
  border: 1px solid #f3f3f3;
  box-shadow: 0px 0px 9px 0px rgba(0, 0, 0, 0.05);
  border-radius: 5px;
  display:flex;

  .mid-line {
    float: left;
    width: 1px;
    background-color: #ededed;
    height: 762px;
  }
  .right-empty{
    flex:1;
    max-width: 907px;
    min-width:678px;
    height: 762px;
    float: left;
    background: #ffffff url(../../assets/images/im/default_list_ico.png) center center no-repeat;
    border-bottom: 1px solid #f3f3f3;
    position: relative;
    .emp_text {
      color: #666;
      text-align: center;
      position: absolute;
      width: 100%;
      line-height: 22px;
      left: 0;
      bottom: 300px;
    }
  }
  .right {
    flex:1;
    max-width: 907px;
    min-width:678px;
    width: 100%;
    height: 762px;
    float: left;
    .r-top {
      width: 100%;
      height: 50px;
      line-height: 50px;
      background-color: #f9f9f9;
      padding: 0 20px;
      border-bottom: 1px solid #ededed;
      position:relative;
      .right-op{
        position:absolute;
        top:0;
        right:26px;
        .op-interview {
          // position: absolute;
          background-image: url("../../assets/images/im/opinterview.png");
          background-repeat: no-repeat;
          background-position: left;
          background-size:16px;
          padding-left: 24px;
          cursor:pointer;
          float:right;
          margin-right:20px;
        }
        .op-stick {
          // position: absolute;
          background-image: url("../../assets/images/im/opstick.png");
          background-repeat: no-repeat;
          background-position: left;
          background-size:16px;
          padding-left: 24px;
          cursor:pointer;
          float:right;
        }
      }
      .tablist{
        height: 40px;
        padding-top: 10px;
        .tab{
          float:left;
          width: 100px;
          text-align: center;
          line-height: 40px;
          cursor:pointer;
          &.select{
            background:url("../../assets/images/im/tab.png");
            background-repeat:no-repeat;
            background-position:bottom;
          }
        }
      }
      .cominfo {
        position: relative;
        height: 50px;
        .contact {
          font-size: 16px;
          color: #333;
          margin-right: 10px;
          float: left;
          max-width: 120px;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
        }
        .companyname {
          font-size: 12px;
          color: #999;
          float: left;
          width: 300px;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
        }
      }

    }
  }
}
.main-container {
  position: relative;
  height: 712px;
  .resumeinfo{
    height:76px;
    padding: 14px 30px;
    border-bottom: 1px solid #ededed;
    position:relative;
    .fullname{
      float:left;
      font-size:18px;
      color:#333;
      height: 22px;
      line-height: 22px;
      font-weight:bold;
    }
    .detail{
      font-size:14px;
      color:#666666;
      float:left;
      height: 22px;
      line-height: 22px;
      margin-left:20px;
    }
    .intention{
      font-size:14px;
      color:#666666;
      float:left;
      margin-top: 8px;
      .label{
        color:#999999;
        margin-right:10px;
      }
      .item{
        margin-right:20px;
        color:#555555;
      }
      .wage{
        color:#FF3535;
      }
    }
    .floatinfo{
      position: absolute;
      right: 30px;
      top: 0px;
      text-align: right;
      font-size: 13px;
      height: 70px;
      padding: 12px 0;
      line-height: 26px;
      .starttime{
        color:#999999;
      }
      .jobname{
        height:22px;
        line-height:22px;
        display: flex;
        align-items: center;
        margin-bottom:2px;
        .change{
          background:url("../../assets/images/im/change.png");
          background-repeat:no-repeat;
          background-position:center;
          background-size:18px;
          width:18px;
          height:18px;
          display: inline-block;
          margin-left: 10px;
          cursor:pointer;
        }
      }
    }
  }
  .jobinfo {
    background-color: #f9f9f9;
    font-size: 14px;
    color: #999;
    height: 40px;
    line-height: 40px;
    padding: 0 20px;
    position: relative;
    border-bottom: 1px solid #ededed;
    .jobname {
      color: #1787fb;
      margin-right: 20px;
      cursor:pointer;
      &:hover{
        text-decoration: underline;
      }
    }
    .wage {
      color: #555555;
    }
    .starttime {
      position: absolute;
      right: 26px;
      color: #999999;
      font-size: 12px;
    }
  }

  .opzone {
    width:100%;
    border-top: 1px solid #ededed;
    position:absolute;
    bottom:14px;
    .content {
      .input {
        height: 66px;
        max-width: 875px;
        margin: 0 6px;
        color: #666;
        font-size: 14px;
        resize: none;
      }
      .btn {
        padding: 0 16px;
        .submit {
          width: 60px;
          height: 32px;
          background: #5792ff;
          border-radius: 3px;
          font-size: 13px;
          color: #fff;
          text-align: center;
          line-height: 32px;
          float: right;
          cursor: pointer;
          &.disabled {
            background: #f9fafb;
            color: #b5b4b4;
            cursor: not-allowed;
          }
        }
        .tip {
          float: right;
          height: 32px;
          line-height: 32px;
          font-size: 13px;
          color: #d8d8d8;
          margin-right: 10px;
        }
      }
    }
    .optop {
      padding: 14px 22px 6px;
      position:relative;
      .blacklist-add{
        position:absolute;
        background-image:url('../../assets/images/im/blacklistadd.png');
        background-repeat:no-repeat;
        background-size:15px;
        background-position:center;
        right: 20px;
        padding-left: 80px;
        cursor:pointer;
        color:#D8D8D8;
      }
      .i {
        background-repeat: no-repeat;
        background-position: center;
        float: left;
        margin-right: 22px;
        cursor: pointer;
      }
      .face {
        width: 22px;
        height: 22px;
        background-image: url("../../assets/images/im/face1.png");
        background-size: 22px;
        &:hover {
          background-image: url("../../assets/images/im/face2.png");
        }
      }
      .phrase {
        width: 22px;
        height: 22px;
        background-image: url("../../assets/images/im/phrase1.png");
        background-size: 22px;
        &:hover {
          background-image: url("../../assets/images/im/phrase2.png");
        }
      }
      .location {
        width: 19px;
        height: 20px;
        background-image: url("../../assets/images/im/location1.jpg");
        background-size: 19px 20px;
        &:hover {
          background-image: url("../../assets/images/im/location2.jpg");
        }
      }
      .card {
        width: 22px;
        height: 22px;
        background-image: url("../../assets/images/im/card1.png");
        background-size: 22px;
        &:hover {
          background-image: url("../../assets/images/im/card2.png");
        }
      }
      .mobile {
        width: 22px;
        height: 22px;
        background-image: url("../../assets/images/im/mobile1.png");
        background-size: 22px;
        &:hover {
          background-image: url("../../assets/images/im/mobile2.png");
        }
      }
      .wechat {
        width: 22px;
        height: 22px;
        background-image: url("../../assets/images/im/wechat1.png");
        background-size: 22px;
        &:hover {
          background-image: url("../../assets/images/im/wechat2.png");
        }
      }
    }
  }
}
.quickbox{
  margin:-12px;
  height:280px;
}
.quicklist{
  height:235px;
  overflow-y:auto;
}
.quickitem{
  height:45px;
  line-height:45px;
  border-bottom:1px solid #ededed;
  padding:0 14px;
  width:380px;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
  cursor:pointer;
  margin:0 auto;
}
.quicktitle{
  border-bottom:1px solid #ededed;
  height:45px;
  line-height:45px;
  padding:0 16px;
  position:relative;
  .tit{
    font-size:16px;
    font-weight:bold;
    color:#9FA3B0;
  }
  .conf{
    font-size: 14px;
    color: #1787FB;
    right: 20px;
    top: 0px;
    position: absolute;
    cursor:pointer;
  }
}
::v-deep .input .el-textarea__inner {
  border: 0;
}
::v-deep .el-textarea__inner {
  font-family:"微软雅黑";
}
::-webkit-scrollbar{
  width: 8px;
  background: #fff;
}
::-webkit-scrollbar-thumb{
  width: 8px;
  height: 30px;
  border-radius: 10px;
  background: #e3e3e3;
}
.disabled-opzone{
  width: 908px;
  height: 151px;
  background: #f9f9f9;
  .msg{
    color:#999999;
    font-size:16px;
    text-align:center;
    padding-top:40px;
  }
  .btn{
    width: 143px;
    height: 38px;
    margin: 20px auto;
    display: block;
  }
}
.interview-box{
  width: 360px;
  position:relative;
  &.empty{
    height: 240px;
    background: #ffffff url(../../assets/images/im/default_list_ico.png) center 60px no-repeat;
    position: relative;
    .tip {
      color: #999;
      text-align: center;
      position: absolute;
      width: 100%;
      line-height: 22px;
      left: 0;
      bottom: 60px;
    }
  }
  .tit{
    width: 360px;
    height: 17px;
    font-size: 16px;
    font-family: Microsoft YaHei;
    font-weight: bold;
    color: #333333;
    line-height: 26px;
    text-align:center;
    margin:10px 0;
  }
  .info{
    font-size: 14px;
    font-family: Microsoft YaHei;
    font-weight: 400;
    color: #666666;
    line-height: 36px;
    padding:0 20px;
    margin:20px 0;
    float:left;
    .line{
      width:320px;
      .label{
        float:left;
        width:70px;
      }
      .val{
        float:left;
        width:250px;
      }
    }
  }
}
::v-deep .el-carousel__arrow--left{
  left:0;
  top:120px;
}
::v-deep .el-carousel__arrow--right{
  right:0;
  top:120px;
}
</style>
