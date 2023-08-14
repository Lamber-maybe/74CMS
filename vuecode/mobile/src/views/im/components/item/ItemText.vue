<template>
      <div class="im-item-box">
          <!-- 时间开始 -->
          <div class="im-time">
            {{itemText.datetime}}
          </div>
          <!-- 时间结束 -->
          <div :class="itemText.self_side ? 'im-item im-right' : 'im-item im-left'">
            <div class="photo">
                <img :src="itemText.avatar" alt="">
            </div>
            <div class="msg msg-bg-2">{{itemText.message}}</div>
            <div v-if="itemText.self_side == 1" :class="itemText.readed == 1 ?  'is-read read' : 'is-read service'">{{itemText.readed == 1 ? '已读' : '送达'}}</div>
          </div>
          <!-- <div class="greet-set-up-box" v-show="itemText.type=='hello' && itemText.self_side==1">
            <div class="greet-set-up">打招呼设置></div>
          </div> -->
      </div>
</template>
<script>
import {parseTime, formatTime} from '@/utils/index.js'
export default {
  name: 'ItemText',
  props: ['itemText'],
  data () {
    return {

    }
  },
  filters: {
    timeFilter (timestamp) {
      return formatTime(timestamp, '{y}-{m}-{d}', true)
    },
    interviewTimeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d} {h}:{i}')
    }
  },
  created () {
  }
}
</script>
<style lang="scss" scoped>
//聊天记录开始
.im-list{
  .im-time{
    text-align: center;
    margin: 23px 0 14px 0;
    color:#999999 ;
    font-size: 12px;
  }
  .im-left{
    flex-direction: row;
    margin-left: 15px;
    .photo{
      margin-right: 13px;
    }
    .is-read{
      margin-left: 10px;
    }
  }
  .im-right{
    margin-right: 15px;
    flex-direction: row-reverse;
    .photo{
      margin-left: 13px;
    }
    .is-read{
      margin-right: 10px;
    }
  }
  .im-item-box{
    margin-bottom: 30px;
    .im-item{
      display: flex;
      font-size: 14px;
      height: 100%;
      .photo{
        width: 44px;
        height: 44px;
        border-radius: 50%;
        img{
          width: 100%;
          height: 100%;
          border-radius: 50%;
        }
      }
      .msg{
        max-width: 225px;
        padding: 12px 15px;
        word-wrap:break-word;
      }
      .msg-bg-1{
        background: #5691FF;
        color: #fff;
        border-radius: 10px 0px 10px 10px;
      }
      .msg-bg-2{
        background: #fff;
        color: #333333;
        border-radius: 0px 10px 10px 10px;
      }
      .msg-img{
        width: 120px;
        img{
          width: 100%;
          height: 100%;
        }
      }
      .is-read{
        font-size: 13px;
        &.read{ // 已读
          color: #C8C8C8;
        }
        &.service{ //送达
          color:#1887FB ;
        }
      }
    }
    .greet-set-up-box{
      text-align: right;
      font-size: 0;
      .greet-set-up{ // 打招呼设置
        width: 100px;
        height: 30px;
        background: #E9E9E9;
        border-radius: 3px;
        color: #5691FF;
        font-size: 14px;
        text-align: center;
        line-height: 30px;
        display: inline-block;
        margin-top: 11px;
        margin-right: 70px;
      }
    }
  }
}
//聊天记录结束
</style>
