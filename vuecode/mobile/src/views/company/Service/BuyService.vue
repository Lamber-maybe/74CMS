<template>
  <div id="app">
    <Head>增值服务</Head>
    <div class="box_1">
      <van-skeleton :row="2" :loading="top_loading">
        <div class="content">
          <div class="tx1">我的套餐：{{ mySetmeal.name }}</div>
          <div class="tx2" v-if="mySetmeal.deadline == 0">有效期至：无限期</div>
          <div class="tx2" v-else>
            有效期至：{{ mySetmeal.deadline | timeFilter }}
          </div>
          <div class="tx3"></div>
          <div class="tx4" @click="$router.push('/member/company/mysetmeal')">
            我的特权
          </div>
        </div>
      </van-skeleton>
    </div>
    <div class="box_2">
      <div
        class="item"
        :class="{ active: type == 'setmeal' }"
        @click="$router.push('/member/order/add/common?type=setmeal')"
      >
        套餐升级
      </div>
      <div
        class="item"
        :class="{ active: type == 'service' }"
        @click="$router.push('/member/order/add/common?type=service')"
      >
        增值服务
      </div>
    </div>
    <keep-alive>
      <BuySetmeal v-if="type == 'setmeal'"></BuySetmeal>
      <BuyIncrement
        v-if="type == 'service'"
        :mySetmeal="mySetmeal"
      ></BuyIncrement>
    </keep-alive>
  </div>
</template>

<script>
import BuySetmeal from '@/components/service/company/BuySetmeal'
import BuyIncrement from '@/components/service/company/BuyIncrement'
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'BuyService',
  components: {
    BuySetmeal,
    BuyIncrement
  },
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d}')
    }
  },
  data () {
    return {
      type: '',
      top_loading: true,
      mySetmeal: {}
    }
  },
  watch: {
    $route: function (to, from) {
      this.type = to.query.type === undefined ? 'setmeal' : to.query.type
    }
  },
  created () {
    this.type = this.$route.query.type === undefined ? 'setmeal' : this.$route.query.type
    this.fetchSetmeal()
  },
  methods: {
    fetchSetmeal () {
      http
        .get(api.member_setmeal, {})
        .then(res => {
          this.mySetmeal = res.data.info
          this.top_loading = false
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.box_2 {
  .item {
    &.active {
      &::after {
        position: absolute;
        left: 50%;
        bottom: 13px;
        width: 15px;
        height: 4px;
        border-radius: 4px;
        background-color: #333333;
        transform: translate(-50%, 0);
        content: " ";
      }
      position: relative;
      font-weight: bold;
      font-size: 18px;
      padding: 16px 0 22px;
    }
    flex: 1;
    text-align: center;
    padding: 17px 0 23px;
    font-size: 15px;
    color: #333333;
  }
  width: 100%;
  background-color: #ffffff;
  border-radius: 18px 18px 0 0;
  display: flex;
}
.box_1 {
  .content {
    .tx4 {
      position: absolute;
      right: 8px;
      top: 13px;
      padding-right: 17px;
      font-size: 12px;
      color: #522809;
      z-index: 1;
      background: url("../../../assets/images/meal_self_arrow.svg") right center
        no-repeat;
      background-size: 18px;
    }
    .tx3 {
      position: absolute;
      right: 0;
      top: 10px;
      background: url("../../../assets/images/crown.png") 0 no-repeat;
      background-size: 79px 88.5px;
      width: 79px;
      height: 88.5px;
      z-index: 1;
      opacity: 0.15;
    }
    .tx2 {
      font-size: 12px;
      color: #92672c;
      padding-top: 5px;
    }
    .tx1 {
      font-size: 14px;
      font-weight: bold;
      color: #522809;
      padding-right: 70px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    border-radius: 10px 10px 0 0;
    width: 100%;
    background-image: linear-gradient(160deg, #f9d598 20%, #dab577 80%);
    position: relative;
    overflow: hidden;
    padding: 12px 15px;
  }
  padding: 16px 16px 0;
}
</style>
