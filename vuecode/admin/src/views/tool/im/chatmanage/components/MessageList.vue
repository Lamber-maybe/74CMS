<template>
  <div id="J_message_list" class="message-list" :class="$store.state.LoginType==1?'com':''" :style="'height:'+messagelist_height">
    <div v-if="loading" class="loading">
      <div />
      <div />
      <div />
    </div>
    <div v-else-if="finish===false" class="more" @click="loadMore">查看更多消息</div>
    <MessageItem :chat_starttime="chat_starttime" :dataset="messageList" @showHellomsg="showHellomsg" @applyJob="applyJob" @refuseWechat="refuseWechat" @agreeWechat="agreeWechat" @refuseMobile="refuseMobile" @agreeMobile="agreeMobile" />
  </div>
</template>

<script>
import MessageItem from './MessageItem'
import { formatTime, parseTime } from '@/utils/index'
import { imChatmessageList } from '@/api/im'

export default {
  name: 'MessageList',
  components: {
    MessageItem
  },
  props: ['messagelist_height', 'chat_starttime'],
  data(){
    return {
      page: 1,
      chat_id: '',
      messageList: [],
      loading: true,
      finish: false
    }
  },
  watch: {
    messageList() {
      this.mapMsgTime()
    }
  },
  created(){
  },
  mounted() {
    document.getElementById('J_message_list').addEventListener('mousewheel', this.onScroll)
  },
  beforeDestroy() {
  },
  methods: {
    showHellomsg(){
      this.$emit('showHellomsg')
    },
    onScroll(ev){
      if (this.finish === true){
        return false
      }
      var is_down = ev.wheelDelta ? ev.wheelDelta < 0 : ev.detail > 0
      if (is_down === false && document.getElementById('J_message_list').scrollTop == 0){
        this.loadMore()
      }
    },
    agreeWechat(item){
      this.$emit('agreeWechat', item)
    },
    refuseWechat(item){
      this.$emit('refuseWechat', item)
    },
    agreeMobile(item){
      this.$emit('agreeMobile', item)
    },
    refuseMobile(item){
      this.$emit('refuseMobile', item)
    },
    applyJob(item){
      this.$emit('applyJob', item)
    },
    loadMore(){
      if (this.finish === true){
        return false
      }
      if (this.loading === true){
        return false
      }
      this.loading = true
      this.getMessageList(this.chat_id)
    },
    loadInit(chat_id){
      this.chat_id = chat_id
      this.loading = true
      this.messageList = []
      this.page = 1
      this.getMessageList(this.chat_id)
    },
    getMessageList(chat_id) {
      this.chat_id = chat_id
      const param = {
        chat_id: chat_id,
        page: this.page
      }
      imChatmessageList(param, 'post')
        .then(res => {
          if (res.data.items.length == 0) {
            this.finish = true
            this.loading = false
          } else {
            const res_list = res.data.items
            this.messageList = res_list.concat(this.messageList)
            this.page++
            this.loading = false
            const prevHeight = document.getElementById('J_message_list').scrollHeight
            this.$nextTick(() => {
              document.getElementById('J_message_list').scrollTop = document.getElementById('J_message_list').scrollHeight - prevHeight
            })
            this.$emit('sendReturnReceiptAll')
          }
        })
        .catch(() => {})
    },
    mapMsgTime() {
      var date = new Date()
      var today = date.toLocaleDateString()
      var mark = []

      // 把消息按天分组
      this.messageList.forEach(element => {
        var item_date = parseTime(element.addtime, '{y}/{m}/{d}') // 取出每个addtime的格式化日期
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

      var last_time = 0
      // 细分处理每一天内的消息时间
      this.messageList.forEach(element => {
        // 分组日期不为空，代表需要在消息循环时显示时间
        if (element.split_datetime != '') {
          // 如果是今天，就细分，否则不处理
          if (Date.parse(element.split_datetime) == Date.parse(today)) {
            // 对比分组时间戳和今天的时间戳，是今天的消息才处理，否则不处理
            // 超过3分钟就独立标记
            var cha = (element.addtime - last_time) / 60
            if (cha > 3) {
              // 如果超过3分钟，就标记时分，否则把时间置空
              element.datetime = formatTime(element.addtime, '{h}:{i}')
            } else {
              element.datetime = ''
            }
            // 把本条消息的addtime记录下来，方便下次循环时对比
            last_time = element.addtime
          } else {
            element.datetime = element.split_datetime
          }
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.loading{
  text-align:center;
}
.loading > div {
  background-color: #666;
  width: 6px;
  height: 6px;
  border-radius: 100%;
  margin: 2px;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
  display: inline-block;
  -webkit-animation: loading 0.7s 0s infinite linear;
  animation: loading 0.7s 0s infinite linear;
}
.loading > div:nth-child(2n-1) {
  -webkit-animation-delay: -0.35s !important;
  animation-delay: -0.35s !important;
}
@keyframes loading {
  50% {
    opacity: 0.2;
    -webkit-transform: scale(0.75);
    transform: scale(0.75);
  }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
    transform: scale(1);
  }
}
.more{
  text-align: center;
  color: #1787FB;
  margin-bottom: -14px;
  margin-top:10px;
  cursor:pointer;
  font-size:14px;
}
.message-list {
  // height: 510px;
  padding: 10px 30px 20px;
  overflow-y: auto;
  &.com{
    // height:480px;
  }
}
</style>
