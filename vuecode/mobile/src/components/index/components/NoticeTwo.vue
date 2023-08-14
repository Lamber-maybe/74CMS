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
    </div>
  </div>
</template>

<script>
export default {
  name: 'NoticeTwo',
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
  .item {
    &:nth-of-type(2n) {
      padding: 2.5px 0 10.5px 0;
    }
    width: 100%;
    font-size: 13px;
    color: #333333;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    padding: 10.5px 0 2.5px 0;
  }
  &::after {
    content: " ";
    position: absolute;
    left: 88px;
    top: 50%;
    transform: translate(0, -50%);
    height: 35px;
    border-right: 1px solid #e2e2e2;
  }
  width: 100%;
  position: relative;
  padding: 0 16px 0 105px;
  height: 72px;
  overflow: hidden;
  background: #ffffff url("../../../assets/images/index/index_notice_ic1.png")
  10px 20px no-repeat;
  background-size: 63px 44px;
}
</style>
