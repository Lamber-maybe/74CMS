<template>
  <div>
    <div class="b1" v-if="dataset.length > 5">
      <swiper :options="swiperOption" @click-slide="beforeJump" v-if="$store.state.swiperLoaded">
        <swiper-slide v-for="(item,index) in dataset" :key="index">
          <div class="item">
            <img :src="item.icon == '' ? require('../../../assets/images/index/ap2/' + item.alias + '.png') : item.icon" alt="" />
            <div class="des">{{ item.title }}</div>
          </div>
        </swiper-slide>
        <div class="swiper-pagination" slot="pagination"></div>
      </swiper>
    </div>
    <div class="b2 clearfix" v-else>
<!--      后台设置触屏个性化，如果设置为单行五个并且在菜单设置中，将菜单隐藏只剩下五个的情况下，触屏首页的按钮点击没反应-->
      <div class="item" v-for="(item,index) in dataset" :key="index"  @click="beforeJump(index)">
        <img :src="item.icon == '' ? require('../../../assets/images/index/ap2/' + item.alias + '.png') : item.icon" alt="" />
        <div class="des">{{ item.title }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MenuTwo',
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
              ' index-app-three-swiper-span"></span>'
            )
          },
          bulletActiveClass: 'index-app-three-swiper-bullet-active'
        },
        initialSlide: 0,
        slidesPerView: 5,
        slidesPerGroup: 5,
        speed: 800
      }
    }
  },
  created () {
  },
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
.index-app-three-swiper-span {
  width: 6px;
  height: 6px;
  border-radius: 100%;
}
.index-app-three-swiper-bullet-active {
  width: 10px;
  background-color: #1787fb;
  border-radius: 6px;
  opacity: 1;
}
</style>

<style lang="scss" scoped>
  .b2 {
    width: 100%;padding: 20px 0 0;background-color: #ffffff;
    .item {
      img { display: block;width: 35px;height: 39px;margin: 0 auto; }
      .des { font-size: 11px;color: #333333;padding: 6.5px 0 15px; }
      text-align: center;float: left;width: 20%;
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
      width: 39px;  // 修改触屏首页单行5图标长度 zch 2022.9.20
      height: 39px;
      margin: 0 auto;
    }
    .des {
      font-size: 11px;
      color: #333333;
      padding: 6.5px 0 32.5px;
    }
    flex: 1;
    text-align: center;
  }
  width: 100%;
  background-color: #ffffff;
  padding: 20px 0 0;
  display: flex;
}
</style>
