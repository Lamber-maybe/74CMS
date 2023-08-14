<template>
  <div>
    <div class="b1">
      <div
        class="item"
        v-for="(item,index) in dataset" :key="index"
        @click="jumpTo(item.alias, item.link_url)"
      >
        <img
          :src="
            item.icon == ''
              ? require('../../../assets/images/index/ap1/' +
                  item.alias +
                  '.png')
              : item.icon
          "
          alt=""
        />
        <div class="des">{{ item.title }}</div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MenuOne',
  props: ['dataset'],
  data () {
    return {}
  },
  created () {},
  methods: {
    jumpTo (alias, url) {
      this.$emit('jumpTo', { alias, url })
    }
  }
}
</script>

<style lang="scss" scoped>
.b1 {
  .item {
    img {
      display: block;
      width: 40px;
      height: 40px;
      margin: 0 auto;
    }
    .des {
      font-size: 11px;
      color: #333333;
      padding-top: 6.5px;
    }
    float: left;
    width: 75px;
    text-align: center;
    margin-bottom: 25px;
  }
  width: 100%;
  background-color: #ffffff;
  padding-top: 25px;
}
</style>
