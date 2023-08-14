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
              ? require('../../../assets/images/index/ap2/' +
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
  name: 'MenuFour',
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
      width: 35px;
      height: 39px;
      margin: 0 auto;
    }
    .des {
      font-size: 11px;
      color: #333333;
      padding-top: 9px;
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
