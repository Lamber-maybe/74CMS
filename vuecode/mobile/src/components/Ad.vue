<template>
  <div :class="dataset.alias">
    <div class="form_split_10" v-if="!no_top_border"></div>
    <img
      v-if="dataset.items.length == 1"
      :src="dataset.items[0].image_src"
      @click="handlerClick(dataset.items[0])"
    />
    <swiper v-else-if="$store.state.swiperLoaded" :options="swiperOption" @click-slide="handleClickSlide">
      <swiper-slide v-for="(item, index) in dataset.items" :key="index">
        <img :src="item.image_src" />
      </swiper-slide>
    </swiper>
  </div>
</template>

<script>
export default {
  name: 'Ad',
  props: ['dataset', 'no_top_border'],
  data () {
    return {
      swiperOption: {
        loop: true,
        initialSlide: 0,
        autoplay: {
          delay: 1500,
          stopOnLastSlide: false,
          disableOnInteraction: false
        },
        speed: 800
      }
    }
  },
  mounted () {},
  created () {},
  computed: {},
  methods: {
    handleClickSlide (index, reallyIndex) {
      this.handlerClick(this.dataset.items[reallyIndex])
    },
    handlerClick (item) {
      if (item.link_url) {
        window.location.href = item.link_url
      } else if (item.company_id > 0) {
        this.$router.push('/company/' + item.company_id)
      } else if (item.inner_link) {
        let path = ''
        let params_type = 'query'
        switch (item.inner_link) {
          case 'index':
            path = '/'
            break
          case 'joblist':
            path = '/joblist'
            break
          case 'jobshow':
            path = '/job'
            params_type = 'params'
            break
          case 'resumelist':
            path = '/resumelist'
            break
          case 'noticeshow':
            path = '/notice'
            params_type = 'params'
            break
          case 'articlelist':
            path = '/newslist'
            break
          case 'articleshow':
            path = '/news'
            params_type = 'params'
            break
          default:
            path = ''
            break
        }
        if (path != '') {
          if (item.inner_link_params > 0) {
            if (params_type == 'query') {
              path = path + '?id=' + item.inner_link_params
            } else {
              path = path + '/' + item.inner_link_params
            }
          }
          this.$router.push(path)
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.QS_index_banner_a,
.QS_index_banner_b {
  .swiper-slide {
    height: 65px;
  }
  img {
    width: 100%;
    height: 65px;
    border: 0;
  }
  height: 70px;
}
.QS_member_company_banner,
.QS_member_personal_banner {
  .swiper-slide {
    height: 77.5px;
  }
  img {
    width: 100%;
    height: 77.5px;
    border: 0;
  }
  height: 82.5px;
}
.QS_top_slide {
  .swiper-slide {
    height: 120px;
  }
  img {
    width: 100%;
    height: 120px;
    border: 0;
  }
  height: 125px;
}
</style>
