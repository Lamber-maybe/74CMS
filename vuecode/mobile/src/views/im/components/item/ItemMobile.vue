<template>
  <div class="im-item-box">
    <!-- 时间开始 -->
    <div class="im-time">{{itemMobile.datetime}}</div>
    <!-- 时间结束 -->
    <div :class="itemMobile.self_side ? 'im-item im-right' : 'im-item im-left'">
      <div class="photo">
        <img
          :src="itemMobile.avatar"
          alt=""
        />
      </div>
      <div class="phone-box">
        <div class="phone-top">
          <div class="phone-img">
            <img src="../../../../assets/images/im/switch-phone.png" alt="" />
          </div>
          <div class="phone-text">
            <div v-if="itemMobile.message.status != 1">我想和您交换手机号， 您是否同意</div>
            <div v-else-if="itemMobile.message.status == 1" class="switch-phone-success">
              <div class="success-txt1">{{itemMobile.message.contact}}的手机号：</div>
              <div class="success-txt2">{{itemMobile.message.mobile}} </div>
            </div>
          </div>
        </div>
        <div class="phone-bottom">
          <!-- 0申请中 1同意 2拒绝 -->
          <div v-if="itemMobile.message.status == 0" class="btn-phone-default">
              <div class="btn refuse" @click="handleRefuse">拒绝</div>
              <div class="btn agree" @click="handleAgree">同意</div>
          </div>
          <div v-else-if="itemMobile.message.status == 1" class="btn-phone-success">
              <div class="btn dial" @click="handleDial">拨号</div>
              <div class="btn copy" @click="handleCopy(itemMobile.message.mobile)">复制</div>
          </div>
          <div v-else-if="itemMobile.message.status == 2" class="btn-phone-error">已拒绝</div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {parseTime, formatTime} from '@/utils/index.js'
export default {
  props: ['itemMobile'],
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
     * 拒绝
    */
    handleRefuse () {
      this.$emit('handleExchangeMobileRefuse', this.itemMobile)
    },
    /**
     * 同意
     */
    handleAgree () {
      this.$emit('handleExchangeMobileAgree', this.itemMobile)
    },
    /**
     * 拨打电话
     */
    handleDial () {
      window.location.href = 'tel:' + this.itemMobile.message.mobile
    },
    /**
     * 复制电话
     */
    handleCopy (mobile) {
      var oInput = document.createElement('input')
      oInput.setAttribute('readonly', 'readonly')
      oInput.value = mobile
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
.weixin-box {
  width: 240px;
  height: 120px;
  background: #ffffff;
  border-radius: 15px;
  .phone-top,
  .weixin-top {
    display: flex;
    padding: 21px 25px 20px 25px;
    .phone-img,
    .weixin-img {
      width: 34px;
      height: 34px;
      margin-right: 15px;
      img {
        width: 100%;
        height: 100%;
      }
    }
    .phone-text,
    .weixin-text {
      flex: 3;
      color: #666666;
      font-size: 13px;
      .switch-phone-success {
        .success-txt2 {
          color: #ff6524;
        }
      }
      .switch-weixin-success {
        .success-txt2 {
          color: #1cb56e;
          word-break:break-all;
          word-wrap:break-word;
        }
      }
    }
  }
  .phone-bottom,
  .weixin-bottom {
    .btn-weixin-default,
    .btn-phone-default {
      display: flex;
      justify-content: center;
      .btn {
        width: 60px;
        height: 25px;
        text-align: center;
        line-height: 21px;
        border-radius: 5px;
        &.agree {
          background: #5792ff;
          border: 1px solid #5792ff;
          color: #fff;
          margin-left: 26px;
        }
        &.refuse {
          background: #ffffff;
          border: 1px solid #94baff;
          color: #5792ff;
        }
      }
    }
    .btn-phone-success {
      display: flex;
      border-top: 1px solid #e8e8e8;
      .btn {
        flex: 1;
        text-align: center;
        line-height: 42px;
        position: relative;
        font-size: 13px;
        &::before {
          content: "";
          position: absolute;
          right: 0;
          width: 1px;
          height: 21px;
          background: #e8e8e8;
          top: 50%;
          transform: translateY(-50%);
        }
        &:last-child::before {
          display: none;
        }
        &.copy {
          color: #5792ff;
        }
        &.dial {
          color: #666666;
        }
      }
    }
    .btn-weixin-success {
      text-align: center;
      line-height: 42px;
      color: #2fbd6e;
      font-size: 13px;
      border-top: 1px solid #e8e8e8;
    }
    .btn-phone-error,
    .btn-weixin-error {
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
