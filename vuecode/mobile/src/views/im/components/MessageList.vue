<template>
    <!-- <div> -->
        <van-pull-refresh :pulling-text="'下拉加载更多'" v-model="imIsLoading" @refresh="handleImRefresh" :loading-text="refreshText">
            <div class="im-list" id="im-list">
                <MessageItem
                  :messagelist="messagelist"
                  @handleExchangeMobileRefuse="handleExchangeMobileRefuse"
                  @handleExchangeMobileAgree="handleExchangeMobileAgree"
                  @handleExchangeWechatRefuse="handleExchangeWechatRefuse"
                  @handleExchangeWechatAgree="handleExchangeWechatAgree"
                >
                </MessageItem>
            </div>
        </van-pull-refresh>
    <!-- </div> -->
</template>

<script>
import MessageItem from './MessageItem.vue'
import {parseTime, formatTime} from '@/utils/index.js'
import http from '@/utils/http'
import api from '@/api'
import {mapState, mapMutations} from 'vuex'
export default {
  data () {
    return {
      imIsLoading: false,
      chat_id: '',
      MessageListPage: 1,
      refreshText: ''
    }
  },
  components: {
    MessageItem
  },
  computed: {
    ...mapState({
      messagelist: state => state.messagelist,
      imToken: state => state.imToken,
      sendScrollState: state => state.sendScrollState,
      imUnreaded: state => state.imUnreaded
    })
  },
  watch: {
    messagelist () {
      this.mapMsgTime()
      this.scrollToBottom()
    }
  },
  created () {
    this.chat_id = this.$route.params.userid
    this.getMessageList(true)
  },
  methods: {
    ...mapMutations(['setMessagelist', 'setScrollState']),
    /**
     * 处理下拉刷新
     */
    handleImRefresh () {
      this.imIsLoading = false
      this.setScrollState(false)
      this.MessageListPage++
      this.getMessageList(false)
    },
    /**
     * 滚动条定位
     * */
    scrollToBottom: function () {
      var imListBox = this.$parent.$refs.imListBox
      var oldHeight = imListBox.scrollHeight
      this.$nextTick(function () {
        if (!this.sendScrollState) {
          if (this.messagelist == 10) {
            imListBox.scrollTop = (imListBox.scrollHeight - oldHeight)
          } else {
            imListBox.scrollTop = (imListBox.scrollHeight - oldHeight) - 5
          }
        } else {
          imListBox.scrollTop = imListBox.scrollHeight
        }
      })
    },
    /**
     * 获取聊天记录
     * @init Boolen true首次进来加载数据 false上拉加载数据
     * */
    getMessageList (init) {
      http.post(api.messageList, {chat_id: this.chat_id, page: this.MessageListPage, token: this.imToken}).then((res) => {
        if (init) {
          this.setMessagelist(res.data.items)
        } else {
          this.refreshText = res.data.items.length <= 0 ? '暂无更多数据' : ''
          if (this.refreshText != '') {
            this.$toast('暂无更多数据')
          }
          var messageAry = res.data.items.concat(this.messagelist)
          this.setMessagelist(messageAry)
          setTimeout(() => {
            this.imIsLoading = false
          }, 500)
        }
        this.$emit('sendReturnReceiptAll')
      })
    },
    /**
     * 交换手机号拒绝
     */
    handleExchangeMobileRefuse (data) {
      this.$emit('handleMobileRefuse', data)
    },
    /**
     * 交换手机号同意
     */
    handleExchangeMobileAgree (data) {
      this.$emit('handleMobileAgree', data)
    },
    /**
     * 交换微信号拒绝
     */
    handleExchangeWechatRefuse (WechatData) {
      this.$emit('handleWechatRefuse', WechatData)
    },
    /**
     * 交换微信号同意
     */
    handleExchangeWechatAgree (WechatData) {
      this.$emit('handleWechatAgree', WechatData)
    },
    mapMsgTime () {
      var date = new Date()
      var today = date.toLocaleDateString()
      var mark = []

      // 把消息按天分组
      this.messagelist.forEach(element => {
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
      this.messagelist.forEach(element => {
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

</style>
