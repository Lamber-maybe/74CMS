<template>
  <div>
    <div
      v-for="(item, index) in list"
      :key="index"
      class="message-item "
    >
      <!--==================系统消息=================-->
      <ItemSystem v-if="item.type == 'system'" :item="item" />
      <!--======================发职位========================================-->
      <ItemJob v-else-if="item.type == 'job'" :item="item" :chat_starttime="chat_starttime" />
      <!--======================发简历========================================-->
      <ItemResume v-else-if="item.type == 'resume'" :item="item" />
      <!--==================发送投递邀请=================-->
      <ItemInvite v-else-if="item.type == 'invite'" :item="item" @apply="applyJob" />
      <!--==================请求交换手机号=================-->
      <ItemMobile v-else-if="item.type == 'mobile'" :item="item" @agree="agreeMobile" @refuse="refuseMobile" />
      <!--==================请求交换微信号=================-->
      <ItemWechat v-else-if="item.type == 'wechat'" :item="item" @agree="agreeWechat" @refuse="refuseWechat" />
      <!--======================图片========================================-->
      <ItemImage v-else-if="item.type == 'image'" :item="item" />
      <!--======================地图========================================-->
      <ItemMap v-else-if="item.type == 'map'" :item="item" />
      <!--======================文字信息========================================-->
      <ItemText v-else :item="item" @showHellomsg="showHellomsg" />
    </div>
  </div>
</template>

<script>
import ItemInvite from './item/ItemInvite'
import ItemSystem from './item/ItemSystem'
import ItemJob from './item/ItemJob'
import ItemResume from './item/ItemResume'
import ItemMobile from './item/ItemMobile'
import ItemWechat from './item/ItemWechat'
import ItemMap from './item/ItemMap'
import ItemImage from './item/ItemImage'
import ItemText from './item/ItemText'
export default {
  name: 'MessageItem',
  components: {
    ItemInvite,
    ItemSystem,
    ItemJob,
    ItemResume,
    ItemMobile,
    ItemWechat,
    ItemMap,
    ItemImage,
    ItemText
  },
  props: ['dataset', 'chat_starttime'],
  data(){
    return {
      list: []
    }
  },
  watch: {
    dataset: {
      handler(val) {
        this.list = val
      },
      // 深度观察监听
      deep: true
    }
  },
  created(){

  },
  mounted() {

  },
  beforeDestroy() {
  },
  methods: {
    showHellomsg(){
      this.$emit('showHellomsg')
    },
    refuseWechat(item){
      this.$emit('refuseWechat', item)
    },
    agreeWechat(item){
      this.$emit('agreeWechat', item)
    },
    refuseMobile(item){
      this.$emit('refuseMobile', item)
    },
    agreeMobile(item){
      this.$emit('agreeMobile', item)
    },
    applyJob(item){
      this.$emit('applyJob', item)
    }
  }
}
</script>

<style lang="scss" scoped>
.message-item {
  margin-top: 29px;
}
</style>
