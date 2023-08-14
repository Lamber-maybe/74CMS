<template>
  <div class="text-item clearfix" :style="(item.type=='hello' && item.self_side==1)?'margin-bottom: 40px;':''">
    <div class="datetime">{{ item.datetime }}</div>
    <div class="out">
      <div class="photo" :class="item.self_side == 1 ? 'mine' : 'other'">
        <img class="image" :src="item.avatar">
      </div>
      <div class="content" :class="item.self_side == 1 ? 'mine' : 'other'">
        <div class="inner" :class="item.self_side == 1 ? 'mine-name' : 'other-name'">
          <span class="type sliceText substring" :class="item.self_side == 1 ? 'mine' : 'other'">{{ item.name }}</span>
          <span class="type words" :class="item.self_side == 1 ? 'mine' : 'other'">
            <a v-if="item.is_delete == 1" class="gray" href="javascript:;">撤回</a>
            <a v-if="item.is_delete == 0" class="back" href="javascript:;" @click="backmsg(item)">撤回</a>|
            <a v-if="item.disable_im == 1" href="javascript:;" class="gray">禁聊</a>
            <a v-if="item.disable_im == 0" href="javascript:;" class="forbid" @click="forbidmsg(item.uid)">禁聊</a>
          </span>
        </div>
        <span style="white-space: pre-line;word-break: break-word;">{{ item.message }}</span>
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
  name: 'ItemText',
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
    openHellomsg(){
      this.$emit('showHellomsg')
    }
  }
}
</script>

<style lang="scss" scoped>
.conf{
  position:absolute;
  bottom:-34px;
  right:1px;
  color:#1787FB;
  font-size:12px;
  cursor:pointer;
}
.status {
  color: #c9c9c9;
  font-size: 12px;
  position:absolute;
  bottom: -30px;
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
.out{
  border-top:1px solid transparent
}
.content {
  max-width: 350px;
  min-height: 44px;
  background: #5792ff;
  color: #fff;
  font-size: 14px;
  line-height: 30px;
  padding: 6px 14px;
  margin: 0 14px;
  position:relative;
  margin-top: 20px;
  .inner{
    font-size: 14px;
    position: absolute;
    top: -30px;
    z-index: 1000000;
    color: #999999;
    min-width: 325px;
    cursor: pointer;
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
    display: none;
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
    border-radius: 5px 0px 5px 5px;
  }
  &.other {
    float: left;
    border-radius: 0px 5px 5px 5px;
  }
}
</style>
