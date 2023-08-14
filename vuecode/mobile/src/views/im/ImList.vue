<template>
  <div id="app">
    <Head>我的职聊</Head>
    <div class="form_split_10"></div>
    <div class="item_wrapper">
      <van-empty
        description="没有聊天记录"
        style="background-color:#fff"
        v-if="show_empty === true"
      />
      <div
        class="item"
        @click="$router.push('/im/imshow/' + item.receiver_info.uid)"
        v-for="(item, index) in chatlist"
        :key="index"
      >
        <img
          :src="
            item.receiver_info.avatar == ''
              ? require('../../assets/images/empty_photo.png')
              : item.receiver_info.avatar
          "
          :alt="item.receiver_info.nickname"
          class="avatar"
        />
        <div class="text_content">
          <div class="name">
            {{ item.receiver_info.nickname }}
            <div class="right_txt">{{ item.updatetime | timeFilter }}</div>
          </div>
          <div class="simple_chat">{{ item.last_message }}</div>
        </div>
        <div class="delete" @click.stop="doDel(index, item)"></div>
      </div>
    </div>
  </div>
</template>

<script>
import { formatTime } from '@/utils/index'
import axios from 'axios'
export default {
  name: 'ImList',
  filters: {
    timeFilter (timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d} {h}:{i}', true)
    }
  },
  data () {
    return {
      ws: null,
      timer: null,
      chatlist: [],
      show_empty: false
    }
  },
  computed: {
    config () {
      return this.$store.state.config
    }
  },
  //   watch: {
  //     config(val) {
  //       console.log(this.$store.state)
  //       this.initWebSocket()
  //     },
  //   },
  mounted () {
    this.timer = setInterval(this.ping, 30000)
  },
  beforeDestroy () {
    clearInterval(this.timer)
  },
  created () {
    this.initWebSocket()
  },
  destroyed () {
    this.ws.close() // 离开路由之后断开websocket连接
  },
  methods: {
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
      this.getChatList()
    },
    websocketonerror () {
      // 连接建立失败重连
      //   this.initWebSocket()
    },
    websocketonmessage (e) {
      if (this.isJSON(e.data)) {
        // 判别是否是json格式，如果不是json格式，判定为服务器通知消息，否则是正常的消息体
        let data = JSON.parse(e.data)
        let msg_type = data.content.msg_type
        let msg_type_arr = ['text', 'img']
        if (msg_type === 'system') {
          // 系统消息，如被挤下线等
          console.log(data.content.msg)
          if (data.content.action === 'logout') {
            // 被挤下线，关闭连接
            this.ws.close()
          }
          return false
        } else if (msg_type_arr.indexOf(msg_type) > -1) {
          this.getChatList()
        }
      } else {
        console.log(e.data)
        return false
      }
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
    getChatList () {
      let url = this.config.im_server + '/chatlist'
      axios
        .get(url, {
          params: {
            token: this.$store.state.userIminfo.user_token,
            limit: 10
          }
        })
        .then(res => {
          if (res.data.code === 200) {
            this.chatlist = [...res.data.result.items]
            if (this.chatlist.length === 0) {
              this.show_empty = true
            }
          } else {
            console.log(res.data.msg)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    doDel (index, item) {
      this.$dialog
        .confirm({
          title: '提示',
          message: '确定删除此会话吗？',
          confirmButtonText: '确定'
        })
        .then(() => {
          let url = this.config.im_server + '/chatdel'
          return axios.get(url, {
            params: {
              token: this.$store.state.userIminfo.user_token,
              id: item.chat_id
            }
          })
        })
        .then(res => {
          if (res.data.code === 200) {
            this.chatlist.splice(index, 1)
            if (this.chatlist.length === 0) {
              this.show_empty = true
            }
          } else {
            console.log(res.data.msg)
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
.item_wrapper {
  .item {
    .avatar {
      position: absolute;
      top: 15px;
      left: 15px;
      width: 45px;
      height: 45px;
      border-radius: 100%;
      border: 0;
    }
    .delete {
      position: absolute;
      right: 7px;
      bottom: 6px;
      width: 32px;
      height: 32px;
      background: url("../../assets/images/im_delete_ico.svg") center no-repeat;
      background-size: 16px;
    }
    .text_content {
      padding: 0 15px 0 20px;
      .simple_chat {
        font-size: 13px;
        color: #666666;
        padding: 0 50px 12px 0;
      }
      .name {
        position: relative;
        padding: 12px 50px 0 0;
        font-weight: bold;
        font-size: 16px;
        color: #333333;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        margin-bottom: 7px;
        .right_txt {
          position: absolute;
          right: 0;
          top: 15px;
          font-size: 12px;
          color: #999999;
        }
      }
    }
    &:not(:last-child) {
      .text_content {
        border-bottom: 1px solid #f3f3f3;
      }
    }
    position: relative;
    width: 100%;
    background-color: #ffffff;
    padding-left: 55px;
    display: block;
    border-bottom: 1px solid #f3f3f3;
  }
}
</style>
