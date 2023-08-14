<template>
  <div class="clearfix">
    <div class="datetime">{{ item.datetime }}</div>
    <div class="out">
      <div class="photo" :class="item.self_side == 1 ? 'mine' : 'other'">
        <img class="image" :src="item.avatar">
      </div>
      <div
        class="mapcard"
        :class="item.self_side == 1 ? 'mine' : 'other'"
        @click="locationToBaidu"
      ><div class="inner" :class="item.self_side == 1 ? 'mine-name' : 'other-name'">
         <span class="type sliceText substring" :class="item.self_side == 1 ? 'mine' : 'other'">{{ item.name }}</span>
         <span class="type words" :class="item.self_side == 1 ? 'mine' : 'other'">
           <a v-if="item.is_delete == 1" class="gray" href="javascript:;">撤回</a>
           <a v-if="item.is_delete == 0" class="back" href="javascript:;" @click="backmsg(item)">撤回</a>|
           <a v-if="item.disable_im == 1" href="javascript:;" class="gray">禁聊</a>
           <a v-if="item.disable_im == 0" href="javascript:;" class="forbid" @click="forbidmsg(item.uid)">禁聊</a>
         </span>
       </div>
        <div class="title">{{ item.message.title }}</div>
        <div class="address">{{ item.message.address }}</div>
        <img style="width: 294px;" src="../../../../../../assets/images/im/map.png">
        <div
          v-if="item.is_delete == 1"
          class="status"
          :class="item.self_side==1?'mine':'other'"
        >
          该消息已撤回
        </div>
      </div>
    </div>
    <!-- <div class="clear" /> -->
  </div>
</template>

<script>
import { messageBack } from '@/api/im'
import { memberIm } from '@/api/member'
export default {
  name: 'ItemMap',
  props: ['item'],
  data(){
    return {
    }
  },
  created(){

  },
  mounted() {

  },
  beforeDestroy() {
  },
  methods: {
    backmsg (item) {
      const tips = '确定撤回该消息吗？'
      this
        .$confirm(tips, '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            messageid: item.messageid
          }
          messageBack(param).then(response => {
            this.$message.success(response.message)
            item.is_delete = 1
            return true
          })
        })
        .catch(() => { })
    },
    forbidmsg (uid) {
      const tips = '确定禁聊该用户吗？'
      const disable_im = 1
      this
        .$confirm(tips, '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
        .then(() => {
          const param = {
            uid: uid,
            disable_im
          }
          memberIm(param).then(response => {
            this.$message.success(response.message)
            return true
          })
        })
        .catch(() => { })
    },
    locationToBaidu(){
      const url =
        'http://api.map.baidu.com/marker?location=' +
        this.item.message.lat +
        ',' +
        this.item.message.lng +
        '&title=' +
        this.item.message.title +
        '&content=' +
        this.item.message.address +
        '&output=html'
      window.open(url)
    }
  }
}
</script>

<style lang="scss" scoped>
.status {
  color: #c9c9c9;
  font-size: 12px;
  position:absolute;
  top:176px;
  width:74px;
  &.mine{
    right:0;
  }
  &.other{
    left:0;
  }
}
.datetime {
  text-align: center;
  font-size: 12px;
  color: #999999;
  margin-bottom: 18px;
}
.out{
  border-top:1px solid transparent
}
.photo {
  &.mine {
    float: right;
  }
  &.other {
    float: left;
  }
  .image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }
}
.mapcard {
  cursor:pointer;
  width: 295px;
  height: 176px;
  background-color: #fff;
  margin: 0 14px;
  border: 1px solid rgba(184, 185, 185, 0.3);
  box-shadow: 0px 0px 24px 0px rgba(219, 219, 219, 0.72);
  border-radius: 5px;
  line-height: 24px;
  position:relative;
  margin-top: 20px;
  .inner{
    font-size: 14px;
    position: absolute;
    top: -30px;
    z-index: 1000000;
    color: #999999;
    min-width: 325px;
    &.other-name{
      left: 0;
      right: auto;
    }
    &.mine-name{
      right: 0;
      left: auto;
    }

    &:hover .words{
      display: inline-block;
      a{
        margin: 0 4px;
        font-weight: 500;
      }
      .back{
        color: #f79317;
      }
      .gray{
        color:gray;
      }
      .forbid{
        color: #02bf57;
      }
    }
  }
  .words{
    display:none;
    a{
      margin: 0 4px;
      font-weight: 500;
    }
    .back{
      color: #f79317;
    }
    .forbid{
      color: #02bf57;
    }
  }
  .type{
     &.mine{
      float: right;
      margin-left: 15px;
    }
    &.other{
      float: left;
      margin-right: 15px;
    }
    &.sliceText{
      display: inline-block;
      max-width: 200px;
    }
  }
  &.mine {
    float: right;
  }
  &.other {
    float: left;
  }
  .title {
    margin: 8px 20px 0;
    font-size: 16px;
    color: #333333;
    max-width: 280px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .address {
    padding: 2px 20px 6px;
    font-size: 14px;
    color: #999999;
    max-width: 280px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
}
</style>
