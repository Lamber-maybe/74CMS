<template>
  <div class="clearfix">
    <div class="datetime">{{ item.datetime }}</div>
    <div class="out">
      <div class="photo" :class="item.self_side == 1 ? 'mine' : 'other'">
        <img class="image" :src="item.avatar">
      </div>
      <div
        :class="(item.message.status==1?'wechatcard':'wechatcard2')+' '+(item.self_side == 1 ? 'mine' : 'other')"
      >
        <div class="inner" :class="item.self_side == 1 ? 'mine-name' : 'other-name'">
          <span class="type sliceText substring" :class="item.self_side == 1 ? 'mine' : 'other'">{{ item.name }}</span>
          <span class="type words" :class="item.self_side == 1 ? 'mine' : 'other'">
            <a v-if="item.is_delete == 1" class="gray" href="javascript:;">撤回</a>
            <a v-if="item.is_delete == 0" class="back" href="javascript:;" @click="backmsg(item)">撤回</a>|
            <a v-if="item.disable_im == 1" href="javascript:;" class="gray">禁聊</a>
            <a v-if="item.disable_im == 0" href="javascript:;" class="forbid" @click="forbidmsg(item.uid)">禁聊</a>
          </span>
        </div>
        <div class="clearfix">
          <div class="ico" />
          <div v-if="item.message.status==1" class="info">
            <div class="i1">{{ item.message.contact }}的微信号</div>
            <div class="i2 substring">{{ item.message.wechat }}</div>
          </div>
          <div v-if="item.message.status!=1" class="info clearfix">
            <div class="i1">我想和您交换微信号，</div>
            <div class="i2 substring">您是否同意</div>
          </div>
        </div>
        <div
          v-if="item.is_delete == 1"
          class="status"
          :class="item.self_side==1?'mine':'other'"
        >
          该消息已撤回
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { messageBack } from '@/api/im'
import { memberIm } from '@/api/member'
export default {
  name: 'ItemWechat',
  props: ['item'],
  data(){
    return {
      input_wechat: '',
      visibleWechat: false
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
    copy (ele) {
      var oInput = document.createElement('input')
      oInput.value = ele
      document.body.appendChild(oInput)
      oInput.select() // 选择对象
      document.execCommand('Copy') // 执行浏览器复制命令
      oInput.className = 'oInput'
      oInput.style.display = 'none'
      this.$message({ type: 'success', message: '复制成功' })
    },
    refuse(item){
      this.$emit('refuse', item)
    },
    agree(item){
      item.message.wechat = this.input_wechat
      this.$emit('agree', item)
    }
  }
}
</script>

<style lang="scss" scoped>

.status {
  color: #c9c9c9;
  font-size: 12px;
  position:absolute;
  top:98px;
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
.wechatcard {
  width: 280px;
  background-color: #fff;
  margin: 0 14px;
  border: 1px solid rgba(184, 185, 185, 0.3);
  box-shadow: 0px 0px 24px 0px rgba(219, 219, 219, 0.72);
  border-radius: 5px;
  line-height: 28px;
  padding: 24px 0 0;
  position:relative;
  height: 98px;
  margin-top: 20px;
  .inner{
    position: absolute;
    font-size: 14px;
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
  .ico {
    float: left;
    width: 48px;
    height: 47px;
    background-repeat: no-repeat;
    background-position: center;
    background-size: 48px 47px;
    margin-left: 20px;
    background-image: url("../../../../../../assets/images/im/wx01.png");
  }
  .info {
    float: left;
    margin-left: 16px;
    line-height: 22px;
    height: 64px;
    width: 170px;
    .i1 {
      color: #666666;
      font-size: 14px;
    }
    .i2 {
      color: #02bf57;
      font-size: 14px;
    }
  }
  .copy {
    height: 48px;
    line-height: 47px;
    font-size: 14px;
    color: #02bf57;
    border-top: 1px solid #ededed;
    text-align: center;
    cursor: pointer;
    font-weight:bold;
  }
}
.wechatcard2 {
  width: 280px;
  background-color: #fff;
  margin: 0 14px;
  border: 1px solid rgba(184, 185, 185, 0.3);
  box-shadow: 0px 0px 24px 0px rgba(219, 219, 219, 0.72);
  border-radius: 5px;
  line-height: 28px;
  padding: 24px 0 0;
  height: 138px;
  position:relative;
  height: 98px;
  margin-top: 20px;
  .inner{
    position: absolute;
    font-size: 14px;
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
  .words{
    display: none;
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
  .ico {
    float: left;
    width: 48px;
    height: 47px;
    background-repeat: no-repeat;
    background-position: center;
    background-size: 48px 47px;
    margin-left: 20px;
    background-image: url("../../../../../../assets/images/im/wx01.png");
  }
  .info {
    float: left;
    margin-left: 16px;
    line-height: 22px;
    height: 64px;
    width:170px;
    .i1 {
      color: #666666;
      font-size: 14px;
    }
    .i2 {
      color: #666666;
      font-size: 14px;
    }
  }
  .btn {
    .refuse {
      float: left;
      width: 70px;
      height: 29px;
      background: #ffffff;
      color: #5792ff;
      border: 1px solid #5792ff;
      border-radius: 5px;
      cursor: pointer;
      margin-right: 24px;
    }
    .agree {
      float: left;
      width: 70px;
      height: 29px;
      color: #fff;
      background: #5792ff;
      border: 1px solid #5792ff;
      border-radius: 5px;
      cursor: pointer;
    }
    padding: 0 56px;
    height: 52px;
    font-size: 16px;
    text-align: center;
  }
  .copy {
    height: 48px;
    line-height: 47px;
    font-size: 14px;
    color: #b2b2b2;
    border-top: 1px solid #ededed;
    text-align: center;
  }
}
</style>
