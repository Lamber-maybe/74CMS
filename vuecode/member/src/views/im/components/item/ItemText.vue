<template>
<div class="text-item" :style="(item.type=='hello' && item.self_side==1)?'margin-bottom: 40px;':''">
  <div class="datetime">{{ item.datetime }}</div>
  <div class="photo" :class="item.self_side == 1 ? 'mine' : 'other'">
    <img class="image" :src="item.avatar" />
  </div>
  <div class="content" :class="item.self_side == 1 ? 'mine' : 'other'">
    <span style="white-space: pre-line;word-break: break-word;">{{ item.message }}</span>
    <div class="conf" v-if="item.type=='hello' && item.self_side==1" @click="openHellomsg">设置招呼语</div>
  </div>
  <div
    class="status"
    :class="item.readed == 1 ? 'readed' : ''"
    v-if="item.self_side == 1"
  >
    {{ item.readed == 1 ? "已读" : "送达" }}
  </div>
  <div class="clear"></div>
</div>
</template>

<script>
export default {
  name: 'ItemText',
  props:['item'],
  data(){
    return {
    }
  },
  created(){

  },
  mounted() {

  },
  methods:{
    openHellomsg(){
      this.$emit('showHellomsg')
    }
  },
  beforeDestroy() {
  }
}
</script>

<style lang="scss" scoped>
.conf{
  position:absolute;
  bottom:-34px;
  right:1px;
  color:#1787FB;
  font-size:12px;
  cursor:pointer;
}
.status {
  color: #1787fb;
  font-size: 12px;
  float: right;
  &.readed {
    color: #c9c9c9;
  }
}
.datetime {
  text-align: center;
  font-size: 12px;
  color: #999999;
  margin-bottom: 18px;
}
.photo {
  &.mine {
    float: right;
  }
  &.other {
    float: left;
  }
  .image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }
}
.content {
  max-width: 530px;
  min-height: 44px;
  background: #5792ff;
  color: #fff;
  font-size: 14px;
  line-height: 30px;
  padding: 6px 14px;
  margin: 0 14px;
  position:relative;
  &.mine {
    float: right;
    border-radius: 5px 0px 5px 5px;
  }
  &.other {
    float: left;
    border-radius: 0px 5px 5px 5px;
  }
}
</style>
