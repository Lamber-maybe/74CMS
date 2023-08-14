<template>
  <div id="app">
    <Head>优惠券</Head>
    <div class="list_wrapper">
      <van-empty
        image="search"
        description="没有可用的优惠券"
        style="background-color:#fff"
        v-if="dataset.length===0"
      />
      <div
        class="list"
        v-for="(item, index) in dataset"
        :key="index"
      >
        <div class="price_box">
          <div class="num">
            <span>￥</span>
            {{ item.coupon_face_value }}
          </div>
          <div class="des">仅限{{ item.setmeal_name }}</div>
        </div>
        <div class="tx1" :class="item.overtime_soon == 1 ? 'over' : ''">
          <van-tag round type="danger" v-if="item.overtime_soon == 1">
            快过期
          </van-tag>
          {{ item.coupon_name }}
        </div>
        <div class="tx2">券编号：{{ item.number }}</div>
        <div class="tx2">
          有效期：{{ item.addtime | timeFilter }}-{{
            item.deadline | timeFilter
          }}
        </div>
        <div
          class="tx3"
          @click="
            $router.push(
              '/member/order/add/common?type=setmeal&setmeal_id=' +
                item.coupon_bind_setmeal_id +
                '&coupon_id=' +
                item.id
            )
          "
        >
          立即使用>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'Coupon',
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{m}.{d}')
    }
  },
  data () {
    return { dataset: [] }
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      http
        .get(api.coupon_list, {})
        .then(res => {
          this.dataset = [...res.data.items]
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.list_wrapper {
  .list {
    .price_box {
      .des {
        font-size: 13px;
      }
      .num {
        span {
          font-size: 18px;
        }
        font-size: 32px;
        padding-bottom: 10px;
      }
      position: absolute;
      left: 0;
      top: 0;
      background-image: linear-gradient(160deg, #f9d598 20%, #dab577 80%);
      width: 115px;
      height: 100%;
      padding-top: 28px;
      text-align: center;
      color: #ffffff;
    }
    .tx3 {
      text-align: right;
      font-size: 12px;
      color: #92672c;
    }
    .tx2 {
      font-size: 12px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      padding-bottom: 9px;
    }
    .tx1 {
      position: relative;
      padding: 17px 0 18px;
      font-size: 14px;
      font-weight: bold;
      color: #333333;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      &.over {
        .van-tag {
          position: absolute;
          left: 0;
          top: 13px;
        }
        padding-left: 53px;
      }
    }
    overflow: hidden;
    border-radius: 8px;
    margin-top: 17px;
    position: relative;
    background-color: #ffffff;
    padding: 0 10px 15px 130px;
    border: 1px solid #e9e9e9;
    border-left: 0;
    &::after {
      content: " ";
      position: absolute;
      left: -11px;
      top: 50%;
      transform: translate(0, -50%);
      z-index: 3;
      width: 20px;
      height: 20px;
      background-color: #ffffff;
      border-radius: 100%;
    }
  }
  width: 100%;
  padding: 0 17px;
}
</style>
