<template>
  <div class="itemvideo" @click="clickitem(item.id)">
    <div class="centen">
      <div class="imgtwodiv">
        <img :src="item.video_src + '?vframe/jpg/offset/0'" class="imgtwo" />
      </div>
      <!-- <div class="hot" v-if="item.hot=='1'">热门</div>
        <div class="new" v-if="item.newer=='1'">新上</div> -->
      <div class="play"></div>
      <div class="time">{{ item.addtime_fmt }}</div>
    </div>
    <!-- <div class="info">
        <img :src="type=='1'?item.logo_src:item.avatar_src" class="logo">
        <div class="mame">{{type=='1'?item.companyname:item.fullname}}</div>
      </div> -->
    <div class="title">{{ item.title }}</div>
    <!-- <div class="address" v-if="item.address">{{item.address}}</div> -->
  </div>
</template>

<script>
export default {
  props: ["item", "type", "gointype", "listid", "index"],
  methods: {
    clickitem(id) {
      this.$router.push({
        path: "/shortvideo/videoplay",
        query: {
          id: id,
          gointype: this.gointype,
          listid: this.listid,
          videotype: this.type,
        },
      });
    },
  },
};
</script>

<style lang="scss" scoped>
@-webkit-keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
.itemvideo {
  transition: all 0.6s ease;
  animation: fadeIn 0.6s linear 0s backwards;
  width: calc(50vw - 20px);
  border-radius: 5px;
  background-color: #ffffff;
  padding-bottom: 15px;
  margin-bottom: 10px;
  box-shadow: 0px 0px 7px rgba($color: #999999, $alpha: 0.6);
  .centen {
    width: 100%;
    position: relative;
    .imgtwodiv {
      width: 100%;
      min-height: 180px;
      background-color: #000;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .imgtwo {
      width: 100%;
    }
    .hot {
      width: 33px;
      height: 22px;
      position: absolute;
      top: -1px;
      left: -1px;
      background-image: linear-gradient(to right, #ff4c08, #ff2020);
      color: #ffffff;
      font-size: 12px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      border-top-left-radius: 8px;
      border-bottom-right-radius: 8px;
    }
    .new {
      width: 33px;
      height: 22px;
      position: absolute;
      top: -1px;
      left: -1px;
      background-image: linear-gradient(to right, #0fb681, #0d9b57);
      color: #ffffff;
      font-size: 12px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      border-top-left-radius: 8px;
      border-bottom-right-radius: 8px;
    }
    .play {
      position: absolute;
      top: 0px;
      left: 0px;
      bottom: 0px;
      right: 0px;
      margin: auto;
      width: 35px;
      height: 35px;
      background: url("../../../assets/images/shortvideo/stop.png") center
        center no-repeat;
      background-size: 35px 35px;
    }
    .time {
      position: absolute;
      top: 0;
      right: 0;
      color: #fff;
      font-size: 12px;
      padding: 8px 10px;
      background-color: rgba(0, 0, 0, 0.38);
      border-top-right-radius: 5px;
      border-bottom-left-radius: 15px;
    }
  }
  .info {
    height: 18px;
    position: relative;
    display: flex;
    .logo {
      width: 25px;
      height: 25px;
      position: absolute;
      top: -5px;
      left: 8px;
      border-radius: 50px;
      border: 2px solid #ffffff;
      background-color: #000000;
    }
    .mame {
      position: absolute;
      bottom: 0px;
      left: 40px;
      font-size: 12px;
      color: #777777;
      width: calc(50vw - 25px - 40px);
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
  }
  .title {
    margin-top: 10px;
    margin-left: 10px;
    margin-right: 10px;
    line-height: 1.5;
    font-size: 13px;
    color: #333333;
    font-weight: bold;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    overflow: hidden;
  }
  .address {
    height: 12px;
    margin-top: 8px;
    margin-left: 10px;
    margin-right: 10px;
    background: url("../../../assets/images/shortvideo/icon_address.png") 0
      center no-repeat;
    background-size: 8px 10px;
    font-size: 11px;
    color: #c2c2c2;
    padding-left: 15px;
    width: calc(50vw - 13px - 6px - 20px);
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
}
</style>
