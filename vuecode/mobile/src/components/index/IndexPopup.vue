<template>
  <div>
    <van-popup v-model="show">
      <div class="popup_box">
        <div class="poster_wrapper">
          <img :src="list.items[0].image_src" @click="handlerClick(list.items[0])">
        </div>
        <div class="close">
          <van-circle v-model="currentRate" :stroke-width="90" size="44px" :rate="100" :speed="100" layer-color="#ebedf0" color="#1787fb" @click.native="closePopup">
            <!-- <van-icon name="cross" /> -->
            <i class="van-icon van-icon-cross"></i>
          </van-circle>
        </div>
      </div>
    </van-popup>
  </div>
</template>

<script>
export default{
  props: ['list'],
  data () {
    return {
      show: false,
      currentRate: 100,
      timer: null
    }
  },
  created () {
    this.show = true
    // 弹窗定时关闭
    this.timer = setInterval(() => {
      this.currentRate -= 2
      if (this.currentRate === 0) {
        clearInterval(this.timer)
        this.show = false
        this.currentRate = 0
      }
    }, 150)
  },
  methods: {
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
            path = '/index'
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
          if (path == this.$route.path) {
            location.reload()
          } else {
            this.$router.push(path)
          }
        }
      }
    },
    closePopup () {
      clearInterval(this.timer)
      this.show = false
    }
  }
}
</script>

<style lang="scss" scoped>
// 弹窗
.popup_box{
  position: relative;
  .poster_wrapper{
    width:320px;
    height:480px;
    border-radius: 10px;
    img{
      width: 100%;
      height: 100%;
      border-radius: 10px;
      display: block;
    }
  }
  .close{
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 30px;
    .van-circle{
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .van-icon{
      font-size: 20px;
      font-weight: 600;
      color: #fff;
    }
  }
}
>>>.van-popup{
  background: transparent;
}
</style>
