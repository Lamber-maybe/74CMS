<template>
  <div>
    <div
      class="message-item "
      v-for="(item, index) in list"
      :key="index"
    >
    <!--==================系统消息=================-->
    <ItemSystem :item="item" v-if="item.type == 'system'"></ItemSystem>
    <!--======================发职位========================================-->
    <ItemJob :item="item" :chat_starttime="chat_starttime" v-else-if="item.type == 'job'"></ItemJob>
    <!--======================发简历========================================-->
    <ItemResume :item="item" v-else-if="item.type == 'resume'"></ItemResume>
    <!--==================发送投递邀请=================-->
    <ItemInvite @apply="applyJob" :item="item" v-else-if="item.type == 'invite'"></ItemInvite>
    <!--==================请求交换手机号=================-->
    <ItemMobile @agree="agreeMobile" @refuse="refuseMobile" :item="item" v-else-if="item.type == 'mobile'"></ItemMobile>
    <!--==================请求交换微信号=================-->
    <ItemWechat @agree="agreeWechat" @refuse="refuseWechat" :item="item" v-else-if="item.type == 'wechat'"></ItemWechat>
    <!--======================图片========================================-->
    <ItemImage :item="item" v-else-if="item.type == 'image'"></ItemImage>
    <!--======================地图========================================-->
    <ItemMap :item="item" v-else-if="item.type == 'map'"></ItemMap>
    <!--======================文字信息========================================-->
    <ItemText :item="item" v-else @showHellomsg="showHellomsg"></ItemText>
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
  props:['dataset','chat_starttime'],
  data(){
    return {
      list:[]
    }
  },
  created(){
    
  },
  mounted() {
    
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
  methods:{
    showHellomsg(){
      this.$emit('showHellomsg')
    },
    refuseWechat(item){
      this.$emit('refuseWechat',item)
    },
    agreeWechat(item){
      this.$emit('agreeWechat',item)
    },
    refuseMobile(item){
      this.$emit('refuseMobile',item)
    },
    agreeMobile(item){
      this.$emit('agreeMobile',item)
    },
    applyJob(item){
      this.$emit('applyJob',item)
    }
  },
  beforeDestroy() {
  }
}
</script>

<style lang="scss" scoped>
.message-item {
  margin-top: 29px;
}
</style>
