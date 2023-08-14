<template>
  <div>
    <div class="b1" v-if="dataset.length<=10">
      <div
        class="item item_2"
        v-for="(item,index) in dataset" :key="index"
        @click="jumpTo(item)"
      >
        <img
          :src="
            item.icon == ''
              ? require('../../../assets/images/index/ap4/' +
                  item.alias + '.png')
              : item.icon
          "
          alt=""
        />
        <div class="des">{{ item.title }}</div>
      </div>
      <div class="clear"></div>
    </div>

    <div class="b1" v-else>
      <swiper :options="swiperOption" @click-slide="beforeJump" v-if="$store.state.swiperLoaded">
        <swiper-slide
          v-for="(item,index) in dataset"
          :key="index"
        >
          <div class="item item_1" @click="jumpTo(item)">
            <!--              【bug】图标修改  zch 2022.9.20-->
            <img
              :src="item.icon == '' ? require('../../../assets/images/index/ap4/' + item.alias + '.png') : item.icon
              "
              alt=""
            />
            <div class="des">{{ item.title }}</div>
          </div>
        </swiper-slide>
        <div class="swiper-pagination" slot="pagination"></div>
      </swiper>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MenuFour',
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
        slidesPerView: 5,
        slidesPerGroup: 5,
        slidesPerColumn: 2,
        slidesPerColumnFill: 'row',
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
.b1 {
  .swiper-pagination {
    bottom: -10px;
  }
  .swiper-wrapper{
    .swiper-slide{
      height: 80px;
    }
  }
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
  }
  .item_1 {
    margin-bottom: 10px;
  }
  .item_2 {
    margin-bottom: 5px;
  }
  width: 100%;
  background-color: #ffffff;
  padding-top: 25px;
  padding-bottom: 15px;
}
</style>
