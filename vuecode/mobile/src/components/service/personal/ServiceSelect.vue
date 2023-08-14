<template>
  <div id="app">
    <Head>选择服务</Head>
    <div class="form_split_10"></div>
    <div class="notice_bar">
      {{ type_tip }}
    </div>
    <div class="form_split_10"></div>
    <div class="box_1">
      <van-radio-group v-model="service_id">
        <div class="item" @click="handleLine(item.id)" v-for="(item, index) in service_list" :key="index">
          <div class="order_ico" :class="type"></div>
          <div class="name">
            <div class="name_psi"><span class="service_name">{{ item.name }}</span><img v-if="item.recommend==1" class="recommend" :src="require('@/assets/images/tuijian2.png')" /></div>
            <div class="name_tj" v-if="item.recommend === 1"></div>
            <div class="clear"></div>
            <van-radio
              class="self_check"
              :name="item.id"
              icon-size="15px"
            ></van-radio>
          </div>
          <div class="des">专享简历推荐特权</div>
          <div class="pay_num">
            <span class="num_ico">￥</span>
            <span class="num">{{ item.expense }}</span>
            <span v-if="item.enable_points_deduct == 1"
              >(可使用{{ $store.state.config.points_byname }}抵扣)</span
            >
          </div>
        </div>
      </van-radio-group>
    </div>
    <div class="box_2">
      <van-button type="info" round block @click="handlerSelected"
        >选择服务</van-button
      >
    </div>
  </div>
</template>

<script>
export default {
  name: 'ServiceSelect',
  props: ['type', 'service_list', 'type_tip'],
  data () {
    return {
      service_id: 0
    }
  },
  methods: {
    handleLine (value) {
      this.service_id = value
    },
    handlerSelected () {
      for (const key in this.service_list) {
        if (this.service_list[key].id == this.service_id) {
          this.$emit('selFinish', this.service_list[key])
          break
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.box_2 {
  position: fixed;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  background-color: #ffffff;
  padding: 13px 37px;
}
.box_1 {
  .item {
    .pay_num {
      .num {
        font-size: 15px;
        color: #ff6600;
      }
      .num_ico {
        font-size: 9px;
        color: #ff6600;
      }
      font-size: 11px;
      color: #999999;
    }
    .order_ico {
      &.stick {
        background: url("../../../assets/images/order_ico_top.png") 0 no-repeat;
        background-size: 80px 50px;
      }
      &.tag {
        background: url("../../../assets/images/order_ico_tag.png") 0 no-repeat;
        background-size: 80px 50px;
      }
      position: absolute;
      left: 13px;
      top: 18px;
      width: 80px;
      height: 50px;
      border-radius: 7px;
    }
    .des {
      margin-bottom: 5px;
      padding: 0 50px 0 0;
      font-size: 13px;
      color: #333333;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .name {
      margin-bottom: 4px;
      .self_check {
        position: absolute;
        right: 15px;
        top: 4px;
      }
      .name_tj {
        &.tj {
          &::after {
            content: "";
          }
        }
        float: left;
        margin-left: 3px;
        width: 18px;
        height: 11px;
        position: relative;
        &::after {
          position: absolute;
          left: 0;
          top: -3px;
          width: 18px;
          height: 11px;
          background: url("../../../assets/images/order_select_tj_ico.png") 0
            no-repeat;
          background-size: 18px 11px;
        }
      }
      .name_psi {
        float: left;
        position:relative;
        font-size: 0;
        .service_name{
          font-size: 15px;
          max-width: 160px;
          display:inline-block;
          overflow: hidden;
          white-space: nowrap;
          text-overflow: ellipsis;
        }
        .recommend{
          position:absolute;
          top:-4px;
          right:-30px;
          width:26px;
        }
      }
      font-size: 15px;
      color: #666666;
      font-weight: bold;
      position: relative;
    }
    width: 100%;
    background-color: #ffffff;
    border-radius: 5px;
    padding: 13px 0 10px 103px;
    position: relative;
    border-bottom: 1px solid #f3f3f3;
  }
  width: 100%;
  padding: 0 15px 25px;
}
.notice_bar {
  width: 100%;
  padding: 11px 0 11px 35px;
  font-size: 12px;
  color: #ff6600;
  background: #fffbeb url("../../../assets/images/remind_ico.svg") 16px center
    no-repeat;
  background-size: 16px;
}
</style>
