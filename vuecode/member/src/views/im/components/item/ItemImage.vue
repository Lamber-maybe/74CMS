<template>
<div class="text-item" :style="(item.type=='hello' && item.self_side==1)?'margin-bottom: 40px;':''">
  <div class="datetime">{{ item.datetime }}</div>
  <div class="photo" :class="item.self_side == 1 ? 'mine' : 'other'">
    <img class="image" :src="item.avatar" />
  </div>
  <el-image 
    class="content"
    :class="item.self_side == 1 ? 'mine' : 'other'"
    :src="item.message" 
    :preview-src-list="[item.message]">
  </el-image>
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
  name: 'ItemImage',
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
  cursor:pointer;
  color: #fff;
  margin: 0 14px;
  &.mine {
    float: right;
    border-radius: 5px 0px 5px 5px;
  }
  &.other {
    float: left;
    border-radius: 0px 5px 5px 5px;
  }
}
::v-deep .el-image__inner{
  max-width: 300px;
  max-height:300px;
  width:auto;
  height:auto;
}
</style>
