<template>
  <div>
    <div class="b1">
      <div class="title">
        热门职位
        <div class="more" @click="goto('/joblist')">更多</div>
      </div>
      <div class="content">
        <div class="item" v-for="(item,index) in dataset" :key="index" @click="jumpTo(item.word)">
          {{ item.word }}
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HotThree',
  props: ['dataset'],
  created () {},
  methods: {
    jumpTo (keyword) {
      this.$emit('jumpTo', { keyword })
    },
    goto (route) {
      this.$router.push(route)
    }
  }
}
</script>

<style lang="scss" scoped>
.b1 {
  .content {
    .item {
      &:nth-of-type(4n) {
        margin-right: 0;
      }
      float: left;
      width: 78px;
      padding: 7px 5px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      font-size: 13px;
      color: #666666;
      text-align: center;
      margin: 0 10px 10px 0;
    }
    width: 100%;
    padding: 0 15px 0;
  }
  .title {
    .more {
      &::after {
        position: absolute;
        right: 2px;
        top: 50%;
        width: 6px;
        height: 6px;
        margin-top: -3px;
        border-top: 1px solid #999999;
        border-right: 1px solid #999999;
        transform: rotate(45deg);
        content: " ";
      }
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translate(0, -50%);
      font-size: 12px;
      color: #999999;
      line-height:normal;
      padding: 1px 11px 1px 0;
    }
    position: relative;
    font-size: 17px;
    color: #333333;
    padding: 20px 15px;
  }
  width: 100%;
  background-color: #ffffff;
  margin-bottom: 9px;
}
</style>
