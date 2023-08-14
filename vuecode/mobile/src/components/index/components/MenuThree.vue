<template>
  <div>
    <div class="b1" v-if="dataset.length > 4">
      <swiper :options="swiperOption" @click-slide="beforeJump" v-if="$store.state.swiperLoaded">
        <swiper-slide v-for="(item,index) in dataset" :key="index">
          <div class="item">
            <img :src="item.icon == '' ? require('../../../assets/images/index/ap3/' + item.alias + '.png') : item.icon" alt="" />
            <div class="des">{{ item.title }}</div>
          </div>
        </swiper-slide>
        <div class="swiper-pagination" slot="pagination"></div>
      </swiper>
    </div>
    <div class="b2 clearfix" v-else>
      <div class="item" v-for="(item,index) in dataset" :key="index" @click="beforeJump(index)">
        <img :src="item.icon == '' ? require('../../../assets/images/index/ap3/' + item.alias + '.png') : item.icon" alt="" />
        <div class="des">{{ item.title }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MenuThree',
  props: ['dataset'],
  data () {
    return {
      swiperOption: {
        pagination: {
          el: '.swiper-pagination',
          renderBullet: function (index, className) {
            return (
              '<span class="' +
              className +
              ' index-app-four-swiper-span"></span>'
            )
          },
          bulletActiveClass: 'index-app-four-swiper-bullet-active'
        },
        initialSlide: 0,
        slidesPerView: 4,
        slidesPerGroup: 4,
        speed: 800
      }
    }
  },
  created () {},
  methods: {
    beforeJump (index) {
      this.jumpTo(this.dataset[index])
    },
    jumpTo (item) {
      let alias = item.alias
      let url = item.url ? item.url : ''
      this.$emit('jumpTo', { alias, url })
    }
  }
}
</script>

<style lang="scss">
.index-app-four-swiper-span {
  width: 6px;
  height: 6px;
  border-radius: 100%;
}
.index-app-four-swiper-bullet-active {
  width: 10px;
  background-color: #1787fb;
  border-radius: 6px;
  opacity: 1;
}
</style>

<style lang="scss" scoped>
  .b2 {
    width: 100%;padding: 10px 0 0;background-color: #ffffff;
    .item {
      img { display: block;width: 60px;height: 60px;margin: 0 auto; }
      .des { font-size: 11px;color: #333333;padding: 3px 0 20px; }
      text-align: center;float: left;width: 25%;
    }
  }
.b1 {
  .swiper-pagination {
    bottom: 5px;
  }
  .line {
    display: flex;
  }
  .item {
    img {
      display: block;
      width: 60px;
      height: 60px;
      margin: 0 auto;
    }
    .des {
      font-size: 11px;
      color: #333333;
      padding: 3px 0 35px;
    }
    flex: 1;
    text-align: center;
  }
  width: 100%;
  background-color: #ffffff;
  padding: 10px 0 0;
  display: flex;
}
</style>
