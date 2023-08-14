<template>
    <div class="im-item-box">
        <!-- 时间开始 -->
        <div class="im-time">
        {{itemWechat.datetime}}
        </div>
        <!-- 时间结束 -->
        <div :class="itemWechat.self_side ? 'im-item im-right' : 'im-item im-left'">
        <div class="photo">
            <img :src="itemWechat.avatar" alt="">
        </div>
        <div class="weixin-box">
            <div class="weixin-top">
            <div class="weixin-img">
                <img src="../../../../assets/images/im/switch-weixin.png" alt="">
            </div>
            <div class="weixin-text">
                <div v-if="itemWechat.message.status != 1 ">我想和您交换微信号， 您是否同意</div>
                <div v-else class="switch-weixin-success">
                  <div class="success-txt1">{{itemWechat.message.contact}}的微信号：</div>
                  <div class="success-txt2">{{itemWechat.message.wechat}}</div>
                </div>
            </div>
            </div>
            <div class="weixin-bottom">
            <div v-if="itemWechat.message.status == 0" class="btn-weixin-default">
                <div class="btn refuse" @click="handleRefuse">拒绝</div>
                <div class="btn agree" @click="handleAgree">同意</div>
            </div>
            <div @click.stop="handleCopyWechat(itemWechat.message.wechat)" v-if="itemWechat.message.status == 1" class="btn-weixin-success">
                复制微信号
            </div>
            <div v-if="itemWechat.message.status == 2" class="btn-weixin-error">
                已拒绝
            </div>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
import {parseTime, formatTime} from '@/utils/index.js'
export default {
  props: ['itemWechat'],
  filters: {
    timeFilter (timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d}', true)
    },
    interviewTimeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  methods: {
    /**
     * 拒绝交换微信号
     */
    handleRefuse () {
      this.$emit('handleExchangeWechatRefuse', this.itemWechat)
    },
    /**
     * 同意交换微信号
     */
    handleAgree () {
      this.$emit('handleExchangeWechatAgree', this.itemWechat)
    },
    /**
     * 复制微信号
     */
    handleCopyWechat (wechat) {
      var oInput = document.createElement('input')
      oInput.setAttribute('readonly', 'readonly')
      oInput.value = wechat
      document.body.appendChild(oInput)
      oInput.select() // 选择对象
      document.execCommand('Copy') // 执行浏览器复制命令
      oInput.className = 'oInput'
      oInput.style.display = 'none'
      this.$toast('复制成功')
    }
  }
}
</script>
<style lang="scss" scoped>
// 交换手机号微信号开始
.phone-box,
.weixin-box{
  width: 240px;
  height: auto;
  overflow: hidden;
  background: #FFFFFF;
  border-radius: 15px;
  .phone-top,
  .weixin-top{
    display: flex;
    padding: 21px 25px 20px 25px;
    .phone-img,
    .weixin-img{
      width:34px;
      height: 34px;
      margin-right: 15px;
      flex-shrink: 0;
      img{
        width: 100%;
        height: 100%;
      }
    }
    .phone-text,
    .weixin-text{
      flex: 3;
      color: #666666;
      font-size: 13px;

      .switch-phone-success{
        .success-txt2{
          color: #FF6524;
        }
      }
      .switch-weixin-success{
        .success-txt2{
          color: #1CB56E;
          word-break:break-all;
          word-wrap:break-word;
        }
      }
    }
  }
  .phone-bottom,
  .weixin-bottom{
    .btn-weixin-default,
    .btn-phone-default{
      display: flex;
      justify-content: center;
      padding: 0px 0 10px;
      .btn{
        width: 60px;
        height: 25px;
        text-align: center;
        line-height: 21px;
        border-radius: 5px;
        &.agree{
          background: #5792FF;
          border: 1px solid #5792FF;
          color: #fff;
          margin-left:26px ;
        }
        &.refuse{
          background: #FFFFFF;
          border: 1px solid #94BAFF;
          color: #5792FF;
        }
      }
    }
    .btn-phone-success{
      display: flex;
      border-top: 1px solid #e8e8e8;
      .btn{
        flex: 1;
        text-align: center;
        line-height: 42px;
        position: relative;
        font-size: 13px;
        &::before{
          content: '';
          position: absolute;
          right: 0;
          width: 1px;
          height: 21px;
          background: #e8e8e8;
          top: 50%;
          transform: translateY(-50%);
        }
        &:last-child::before{
          display: none;
        }
        &.copy{
          color: #5792FF;
        }
        &.dial{
          color: #666666;
        }
      }
    }
    .btn-weixin-success{
      text-align: center;
      line-height: 42px;
      color: #2FBD6E;
      font-size:13px ;
      border-top: 1px solid #e8e8e8;
    }
    .btn-phone-error,
    .btn-weixin-error{
      border-top: 1px solid #e8e8e8;
      text-align: center;
      height: 42px;
      line-height: 42px;
      color: #999999;
      font-size: 13px;
    }
  }
}
// 交换手机号微信号结束
</style>
