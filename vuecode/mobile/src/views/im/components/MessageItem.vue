<template>
  <div class="im-list-inner" ref="imListInner">
    <div v-for="(item,index) in list" :key="index">
          <!-- 文字消息 -->
          <ItemText :itemText="item" v-if="item.type == 'text' || item.type == 'hello'"></ItemText>
          <!-- 文字消息 -->

          <!-- 发送图片 -->
          <ItemImg :itemImg="item" v-if="item.type == 'image'"></ItemImg>
          <!-- 发送图片 -->

          <!-- 发送简历开始 -->
          <ItemResume :itemResume="item" v-if="item.type == 'resume'"></ItemResume>
          <!-- 发送简历结束 -->

          <!-- 发送职位开始 -->
          <ItemJob :itemJob="item" v-if="item.type == 'job'"></ItemJob>
          <!-- 发送职位结束 -->

          <!-- 企业邀请投递简历开始 -->
          <ItemInvite :itemInvite="item" v-if="item.type == 'invite'"></ItemInvite>
          <!-- 企业邀请投递简历结束 -->

          <!-- 发送位置开始 -->
          <ItemMap :itemMap="item" v-if="item.type == 'map'"></ItemMap>
          <!-- 发送位置结束 -->

          <!-- 交换手机号开始 -->
          <ItemMobile :itemMobile="item" v-if="item.type == 'mobile'" @handleExchangeMobileRefuse="handleExchangeMobileRefuse" @handleExchangeMobileAgree="handleExchangeMobileAgree"></ItemMobile>
          <!-- 交换手机号结束 -->

          <!-- 交换微信号开始 -->
          <ItemWechat :itemWechat="item" v-if="item.type == 'wechat'" @handleExchangeWechatRefuse="handleExchangeWechatRefuse" @handleExchangeWechatAgree="handleExchangeWechatAgree"></ItemWechat>
          <!-- 交换微信号结束 -->

          <!-- 系统消息开始 -->
          <ItemSystem :itemSystem="item" v-if="item.type == 'system'"></ItemSystem>
          <!-- 系统消息结束 -->
      </div>
  </div>
</template>
<script>
import ItemText from './item/ItemText.vue'
import ItemImg from './item/ItemImg.vue'
import ItemMap from './item/ItemMap.vue'
import ItemMobile from './item/ItemMobile.vue'
import ItemWechat from './item/ItemWechat.vue'
import ItemResume from './item/ItemResume.vue'
import ItemJob from './item/ItemJob.vue'
import ItemInvite from './item/ItemInvite.vue'
import ItemSystem from './item/ItemSystem.vue'
export default {
  props: ['messagelist'],
  data () {
    return {
      list: []
    }
  },
  components: {
    ItemText,
    ItemImg,
    ItemMap,
    ItemMobile,
    ItemWechat,
    ItemResume,
    ItemJob,
    ItemInvite,
    ItemSystem
  },
  watch: {
    messagelist (val) {
      this.list = val
    }
  },
  created () {
  },
  methods: {
    /**
     * 交换手机号拒绝
     */
    handleExchangeMobileRefuse (data) {
      this.$emit('handleExchangeMobileRefuse', data)
    },
    /**
     * 交换手机号同意
     */
    handleExchangeMobileAgree (data) {
      this.$emit('handleExchangeMobileAgree', data)
    },
    /**
     * 交换微信号拒绝
     */
    handleExchangeWechatRefuse (data) {
      this.$emit('handleExchangeWechatRefuse', data)
    },
    /**
     * 交换微信号同意
     */
    handleExchangeWechatAgree (data) {
      this.$emit('handleExchangeWechatAgree', data)
    }
  }
}
</script>
<style lang="scss">
//聊天记录开始
.im-list{
  .im-time{
    text-align: center;
    margin: 23px 0 14px 0;
    color:#999999 ;
    font-size: 12px;
  }
  .im-left{
    flex-direction: row;
    margin-left: 15px;
    .photo{
      margin-right: 13px;
    }
    .is-read{
      margin-left: 10px;
    }
  }
  .im-right{
    margin-right: 15px;
    flex-direction: row-reverse;
    .photo{
      margin-left: 13px;
    }
    .is-read{
      margin-right: 10px;
    }
  }
  .im-item-box{
    margin-bottom: 30px;
    .im-item{
      display: flex;
      font-size: 14px;
      height: 100%;
      .photo{
        width: 44px;
        height: 44px;
        border-radius: 50%;
        img{
          width: 100%;
          height: 100%;
          border-radius: 50%;
        }
      }
    }
  }
}
//聊天记录结束
</style>
