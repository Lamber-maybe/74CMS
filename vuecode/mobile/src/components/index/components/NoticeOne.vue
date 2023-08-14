<template>
  <div>
    <div class="b1">
      <ul :class="{ 'animate-up': animateUp }">
        <li class="item" v-for="(item, index) in dataset"
            :key="index"
            @click="jumpTo(item.id, item.link_url)">
          {{ item.title }}
        </li>
      </ul>
      <div class="more" @click="$router.push('/noticelist')">更多</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NoticeOne',
  props: ['dataset'],
  data () {
    return {
      timer: null,
      animateUp: false
    }
  },
  mounted () {
    this.timer = setInterval(this.scrollAnimate, 3000)
  },
  methods: {
    jumpTo (id, url) {
      this.$emit('jumpTo', { id, url })
    },
    // 公告滚动
    scrollAnimate () {
      this.animateUp = true
      setTimeout(() => {
        this.dataset.push(this.dataset[0])
        this.dataset.shift()
        this.animateUp = false
      }, 500)
    }
  },
  destroyed () {
    clearInterval(this.timer)
  }
}
</script>

<style lang="scss" scoped>
.b1 {
  .animate-up {
    transition: all 0.5s ease-in-out;
    transform: translateY(-50px);
  }
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
  &::after {
    content: "";
    position: absolute;
    left: 77px;
    top: 50%;
    transform: translate(0, -50%);
    height: 16px;
    border-right: 1px solid #e2e2e2;
  }
  .item {
    padding: 13.5px 0;
    font-size: 13px;
    color: #333333;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  padding: 0 65px 0 87px;
  width: 100%;
  position: relative;
  height: 50px;
  overflow: hidden;
  background: #ffffff url("../../../assets/images/index/index_notice_ic2.png")
    20px center no-repeat;
  background-size: 44px 20px;
}
</style>
