<template>
  <div id="app">
    <Head>{{ title }}</Head>

    <div class="chat_wrapper">
      <van-pull-refresh
        v-model="loading"
        @refresh="onLoad"
        :success-text="success_text"
        :success-duration="success_duration"
      >
        <div
          class="chat_list "
          :class="item.mine == 1 ? 'mine' : ''"
          v-for="(item, index) in messagelist"
          :key="index"
        >
          <div class="chat_date" v-if="item.datetime != ''">
            {{ item.datetime }}
          </div>
          <div class="con_wrapper">
            <img
              :src="
                item.sender_info.avatar == ''
                  ? require('../../assets/images/empty_photo.png')
                  : item.sender_info.avatar
              "
              :alt="item.sender_info.nickname"
              class="avatar"
            />
            <div
              class="chat_content"
              :class="
                (item.message_type == 'img' ? 'img_msg' : '') +
                  ' ' +
                  (resize != false ? 'enable_scroll' : '')
              "
              v-scroll
            >
              <span v-if="item.message_type == 'text'">{{ item.message }}</span>
              <img
                v-else
                :src="item.message"
                alt="[图片]"
                @click="onHandlerImg(item.message)"
              />
            </div>
          </div>
        </div>
      </van-pull-refresh>
    </div>
    <div class="chat_bar">
      <div class="bar_fill">
        <van-uploader
          :preview-image="false"
          :after-read="sendImg"
          class="for_upload"
          :accept="'image/*'"
        >
          <div class="send_img"></div>
        </van-uploader>
        <div class="send_quick" @click="showQuick = !showQuick"></div>
        <van-action-sheet
          v-model="showQuick"
          :actions="quick_msglist"
          @select="onSelect"
          :close-on-click-action="true"
          :round="false"
          cancel-text="取消"
        />
        <div class="send_input_block">
          <label>
            <input type="text" v-model="message_content" class="send_input" />
          </label>
        </div>
        <div class="send_btn" @click="websocketsend">发送</div>
        <div class="clear"></div>
      </div>
    </div>
    <van-overlay :show="uploading"><van-loading type="spinner" size="24px">正在上传...</van-loading></van-overlay>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import { formatTime, parseTime } from '@/utils/index'
import axios from 'axios'
import Vue from 'vue'
import { ImagePreview } from 'vant'
Vue.use(ImagePreview)
export default {
  name: 'ImShow',
  data () {
    return {
      uploading: false,
      resize: true,
      loading: false,
      finished: false,
      success_text: '加载完成',
      success_duration: 500,
      ws: null,
      timer: null,
      target_userid: '',
      page: 1,
      pagesize: 5,
      messagelist: [],
      title: '',
      message_content: '',
      message_type: 'text',
      self_info: {},
      showQuick: false,
      quick_msglist: []
    }
  },
  directives: {
    scroll: {
      inserted (el, binding, vnode, oldVnode) {
        if (vnode.data.class.includes('enable_scroll') != false) {
          el.scrollIntoView()
        }
      }
    }
  },
  computed: {
    config () {
      return this.$store.state.config
    }
  },
  mounted () {
    this.timer = setInterval(this.ping, 30000)
  },
  beforeDestroy () {
    clearInterval(this.timer)
  },
  created () {
    this.target_userid = this.$route.params.userid
    this.getQuickMsglist()
    this.initWebSocket()
  },
  destroyed () {
    this.ws.close() // 离开路由之后断开websocket连接
  },
  methods: {
    getQuickMsglist () {
      http
        .get(api.im_quick_msglist, { utype: this.$store.state.LoginType })
        .then(res => {
          this.quick_msglist = [...res.data.items]
          if (this.quick_msglist.length > 0) {
            for (const key in this.quick_msglist) {
              this.quick_msglist[key].name = this.quick_msglist[key].content
            }
          }
        })
        .catch(() => {})
    },
    // 选择常用语
    onSelect (item, index) {
      this.message_content = item.name
      this.websocketsend()
    },
    // 发送图片消息
    sendImg (file) {
      let fileRaw = file.file
      let filetypeArr = (fileRaw.type || '').split('/')
      let filetype = filetypeArr[1]
      let tyepArr = ['png', 'jpg', 'jpeg', 'bmp', 'gif']
      if (tyepArr.indexOf(filetype) == -1) {
        this.$notify('文件类型不支持')
        return false
      }
      if (fileRaw.size > this.$store.state.config.fileupload_size * 1024) {
        let size = this.$store.state.config.fileupload_size / 1024
        size = size.toFixed(1)
        this.$notify('文件大小超出限制，最大' + size + 'mb')
        return false
      }
      this.uploading = true
      let url = this.config.im_server + '/upload'
      axios({
        headers: {
          'Content-Type': 'multipart/form-data' // ;boundary=----WebKitFormBoundaryQ6d2Qh69dv9wad2u,
        },
        transformRequest: [
          function (data) {
            // 在请求之前对data传参进行格式转换
            const formData = new FormData()
            Object.keys(data).forEach(key => {
              formData.append(key, data[key])
            })
            return formData
          }
        ],
        url: url,
        method: 'post',
        data: {
          token: this.$store.state.userIminfo.user_token,
          receiver_uid: this.target_userid,
          img: file.file
        }
      })
        .then(res => {
          this.uploading = false
          let addObj = {
            addtime: Date.parse(new Date()) / 1000,
            message: file.content,
            message_type: 'img',
            mine: 1,
            receiver_info: {
              nickname: this.target_userinfo.nickname,
              avatar: this.target_userinfo.avatar,
              uid: this.target_userinfo.uid
            },
            sender_info: {
              nickname: this.self_info.nickname,
              avatar: this.self_info.avatar,
              uid: this.self_info.uid
            }
          }
          this.messagelist.push(addObj)
          this.mapMsgTime()
          this.resize = true
        })
        .catch(err => {
          this.uploading = false
          console.log(err)
        })
    },
    isJSON (str) {
      if (typeof str === 'string') {
        try {
          var obj = JSON.parse(str)
          if (typeof obj === 'object' && obj) {
            return true
          } else {
            return false
          }
        } catch (e) {
          return false
        }
      }
    },
    initWebSocket () {
      // 初始化weosocket
      this.ws = new WebSocket(this.config.im_websocket)
      this.ws.onopen = this.websocketonopen
      this.ws.onmessage = this.websocketonmessage
      this.ws.onerror = this.websocketonerror
      this.ws.onclose = this.websocketclose
    },
    websocketonopen () {
      let msgObj = {
        controller: 'Index',
        action: 'connect',
        args: {
          token: this.$store.state.userIminfo.user_token
        }
      }
      let msgStr = JSON.stringify(msgObj)
      this.ws.send(msgStr)
      this.getMessageList(true)
    },
    websocketonerror () {
      // 连接建立失败重连
      //   this.initWebSocket()
    },
    websocketonmessage (e) {
      console.log(e)
      if (this.isJSON(e.data)) {
        // 判别是否是json格式，如果不是json格式，判定为服务器通知消息，否则是正常的消息体
        let data = JSON.parse(e.data)
        let msg_type = data.content.msg_type
        let msg_type_arr = ['text', 'img']
        if (msg_type == 'system') {
          // 系统消息，如被挤下线等
          console.log(data.content.msg)
          if (data.content.action == 'logout') {
            // 被挤下线，关闭连接
            this.ws.close()
          }
          return false
        } else if (msg_type == 'typing') {
          // 对方正在输入的通知消息
          console.log('title中显示：' + data.content.msg)
          return false
        } else if (msg_type_arr.indexOf(msg_type) > -1) {
          let addObj = {
            addtime: Date.parse(new Date()) / 1000,
            message: data.content.msg,
            message_type: data.content.msg_type,
            mine: 0,
            receiver_info: {
              nickname: this.self_info.nickname,
              avatar: this.self_info.avatar,
              uid: this.self_info.uid
            },
            sender_info: {
              nickname: data.sender.nickname,
              avatar: data.sender.avatar,
              uid: data.sender.uid
            }
          }
          this.messagelist.push(addObj)
          this.mapMsgTime()
          this.resize = true
        }
      }
    },
    websocketsend () {
      if (this.message_content === '') {
        return false
      }
      let msgObj = {
        controller: 'Index',
        action: 'sendmsg',
        args: {
          token: this.$store.state.userIminfo.user_token,
          receiver: {
            uid: this.target_userid // 目标用户在cms系统中的用户标识
          },
          content: {
            msg: this.message_content,
            msg_type: 'text'
          }
        }
      }
      let msgStr = JSON.stringify(msgObj)
      this.ws.send(msgStr)
      let addObj = {
        addtime: Date.parse(new Date()) / 1000,
        message: this.message_content,
        message_type: 'text',
        mine: 1,
        receiver_info: {
          nickname: this.target_userinfo.nickname,
          avatar: this.target_userinfo.avatar,
          uid: this.target_userinfo.uid
        },
        sender_info: {
          nickname: this.self_info.nickname,
          avatar: this.self_info.avatar,
          uid: this.self_info.uid
        }
      }
      this.messagelist.push(addObj)
      this.mapMsgTime()
      this.message_content = ''
      this.resize = true
    },
    websocketclose (e) {
      console.log('client：关闭连接')
    },
    ping () {
      let msgObj = {
        controller: 'Ping',
        action: 'index',
        args: {}
      }
      let msgStr = JSON.stringify(msgObj)
      this.ws.send(msgStr)
    },
    onLoad () {
      this.resize = false
      this.getMessageList(false)
    },
    getMessageList (init) {
      if (init === true) {
        this.page = 1
        this.finished = false
        this.finished_text = ''
      } else {
        this.pagesize = 10
      }
      let url = this.config.im_server + '/messagelist'
      axios
        .get(url, {
          params: {
            token: this.$store.state.userIminfo.user_token,
            target_uid: this.target_userid,
            page: this.page,
            pagesize: this.pagesize
          }
        })
        .then(res => {
          if (res.data.code == 200) {
            if (init === true) {
              this.self_info = { ...res.data.result.self_info }
              this.target_userinfo = { ...res.data.result.target_userinfo }
              this.title = this.target_userinfo.nickname
              this.messagelist = [...res.data.result.items]
              this.page = 2
            } else {
              this.messagelist = [
                ...res.data.result.items,
                ...this.messagelist
              ]
              this.page++
            }
            // 加载状态结束
            this.loading = false

            // 数据全部加载完成
            if (res.data.result.items.length == 0) {
              this.finished = true
              this.success_text = '没有更多记录了'
              this.success_duration = 3000
            }
            this.mapMsgTime()
          } else {
            console.log(res.data.msg)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    onHandlerImg (img) {
      ImagePreview({
        images: [img]
      })
    },
    mapMsgTime () {
      let date = new Date()
      let today = date.toLocaleDateString()
      let mark = []

      // 把消息按天分组
      this.messagelist.forEach(element => {
        let item_date = parseTime(element.addtime, '{y}/{m}/{d}') // 取出每个addtime的格式化日期
        if (Date.parse(item_date) == Date.parse(today)) {
          // 对比消息的时间戳和今天的时间戳，如果是相等的，放入标记（今天的每个日期都要赋值，后面要用来判断时间）
          mark.push(item_date)
          element.split_datetime = item_date
        } else {
          // 不是今天的消息，就按天分组
          if (mark.indexOf(item_date) == -1) {
            mark.push(item_date)
            element.split_datetime = item_date
          } else {
            element.split_datetime = ''
          }
        }
      })

      let last_time = 0
      // 细分处理每一天内的消息时间
      this.messagelist.forEach(element => {
        // 分组日期不为空，代表需要在消息循环时显示时间
        if (element.split_datetime != '') {
          // 如果是今天，就细分，否则不处理
          if (Date.parse(element.split_datetime) == Date.parse(today)) {
            // 对比分组时间戳和今天的时间戳，是今天的消息才处理，否则不处理
            // 超过3分钟就独立标记
            let cha = (element.addtime - last_time) / 60
            if (cha > 3) {
              // 如果超过3分钟，就标记时分，否则把时间置空
              element.datetime = formatTime(element.addtime, '{h}:{i}')
            } else {
              element.datetime = ''
            }
            // 把本条消息的addtime记录下来，方便下次循环时对比
            last_time = element.addtime
          }
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.chat_bar {
  .bar_fill {
    .for_upload {
      float: left;
    }
    .send_btn {
      float: right;
      width: 56px;
      height: 53px;
      line-height: 53px;
      text-align: center;
      font-size: 14px;
      color: #1787fb;
    }
    .send_input_block {
      .send_input {
        position: absolute;
        width: 100%;
        height: 17px;
        left: 0;
        top: 50%;
        transform: translate(0, -50%);
        border: 0;
        background-color: transparent;
        font-size: 13px;
        line-height: 17px;
        margin: 0;
        padding: 0 8px;
      }
      position: relative;
      float: left;
      width: 215px;
      height: 31px;
      margin-top: 11px;
      border: 1px solid #e2e2e2;
    }
    .send_quick {
      &::after {
        content: "";
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translate(0, -50%);
        width: 18px;
        height: 18px;
        background: url("../../assets/images/chat_send_msg_ico.svg") center
          no-repeat;
        background-size: 18px;
      }
      &::before {
        content: "";
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translate(0, -50%);
        width: 29px;
        height: 29px;
        border-radius: 100%;
        background-color: #6fb6ff;
      }
      float: left;
      width: 52px;
      height: 100%;
      position: relative;
    }
    .send_img {
      float: left;
      width: 52px;
      height: 53px;
      background: url("../../assets/images/chat_send_img_ico.svg") 16px center
        no-repeat;
      background-size: 28px;
      position: relative;
    }
    position: relative;
    width: 100%;
    height: 100%;
  }
  position: fixed;
  left: 0;
  bottom: 0;
  right: 0;
  width: 100%;
  background-color: #ffffff;
  height: 53px;
  z-index: 3;
  border-top: 1px solid #f3f3f3;
}
.chat_wrapper {
  border-top: 1px solid #f3f3f3;
  .chat_list {
    &.mine {
      .con_wrapper {
        .chat_content {
          background-color: #6fb6ff;
          color: #ffffff;
          border-radius: 9px 9px 0 9px;
        }
        .avatar {
          right: 15px;
          left: unset;
        }
        padding-left: 0;
        padding-right: 68px;
        text-align: right;
      }
    }
    .con_wrapper {
      position: relative;
      padding-left: 68px;
      .avatar {
        position: absolute;
        top: 0;
        left: 15px;
        width: 45px;
        height: 45px;
        border-radius: 100%;
        border: 0;
        background-color: #1a8afa;
      }
      .chat_content {
        max-width: 245px;
        background-color: #f3f3f3;
        border-radius: 9px 9px 9px 0;
        padding: 7px 20px;
        line-height: 1.8;
        font-size: 14px;
        color: #333333;
        display: inline-block;
        text-align: left;
        &.img_msg {
          img {
            max-width: 245px;
            display: block;
          }
          padding: 0;
          line-height: 1;
          background-color: transparent;
        }
      }
    }
    .chat_date {
      text-align: center;
      font-size: 12px;
      color: #999999;
      width: 100%;
      line-height: 26px;
    }
    margin-top: 20px;
  }
  padding-bottom: 73px;
  overflow: hidden;
}
.van-overlay{
  text-align:center;
  z-index:2;
}
.van-loading{
  top:36%;
}
.van-loading__text{
  color:#c3c3c3;
}
</style>
