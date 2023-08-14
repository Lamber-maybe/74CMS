<template>
  <div>
    <div class="b1">
      <div class="title">
        新闻资讯
        <div class="more" @click="goto('/newslist')">更多</div>
      </div>
      <div class="content">
        <div
          class="item"
          v-for="(item,index) in dataset"
          :key="index"
          @click="jumpTo(item.id, item.link_url)"
        >
          <img :src="item.thumb" :alt="item.title" class="preview" />
          <div class="tx1">
            {{ item.title }}
          </div>
          <div class="tx2">
            <!-- <div class="tip yc">原创</div> -->
            <div class="tip zz">转载</div>
            <div class="time">{{ item.addtime | timeFilter }}</div>
            <div class="see">{{ item.click }}</div>
            <div class="clear"></div>
          </div>
        </div>
      </div>
      <div class="form_split_18"></div>
    </div>
  </div>
</template>

<script>
import { parseTime } from '@/utils/index'
export default {
  name: 'NewsOne',
  props: ['dataset'],
  filters: {
    timeFilter (timestamp) {
      return parseTime(timestamp, '{y}-{m}-{d}')
    }
  },
  data () {
    return {}
  },
  created () {},
  methods: {
    jumpTo (id, url) {
      this.$emit('jumpTo', { id, url })
    },
    goto (route) {
      this.$router.push(route)
    }
  }
}
</script>

<style lang="scss" scoped>
.b1 {
  .content {
    .item {
      .preview {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translate(0, -50%);
        width: 88px;
        height: 63px;
        border-radius: 4px;
      }
      .tx2 {
        .tip {
          &.yc {
            background-color: #e7f3ff;
            color: #1787fb;
          }
          &.zz {
            background-color: #fff5e8;
            color: #ff6600;
          }
          position: absolute;
          left: 0;
          top: 9px;
          width:34px;text-align:center;
          padding: 0;
          border-radius: 3px;
          font-size: 11px;
        }
        .see {
          float: left;
          padding: 3px 0 3px 16px;
          font-size: 12px;
          color: #999999;
          background: url('../../../assets/images/index/index_see_ico.svg') 0
            center no-repeat;
          background-size: 13px;
        }
        .time {
          float: left;
          padding: 3px 0 3px 16px;
          font-size: 12px;
          color: #999999;
          margin-right: 15px;
          background: url('../../../assets/images/index/index_time_ico.svg') 0
          5px no-repeat;
          background-size: 12px;
        }
        padding: 5.5px 0 7.5px 40px;
        position: relative;
      }
      .tx1 {
        padding-top: 6px;
        font-size: 15px;
        color: #333333;
        height: 47px;line-height: 1.5;
        word-break: break-all;
        overflow:hidden;
      }
      &:not(:last-child) {
        border-bottom: 1px solid #f3f3f3;
      }
      position: relative;
      padding-left: 105px;
    }
    width: 100%;
    padding: 0 15px;
  }
  .title {
    .more {
      &::after {
        position: absolute;
        right: 2px;
        top: 50%;
        width: 6px;
        height: 6px;
        margin-top: -3px;
        border-top: 1px solid #999999;
        border-right: 1px solid #999999;
        transform: rotate(45deg);
        content: " ";
      }
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translate(0, -50%);
      font-size: 12px;
      color: #999999;
      line-height:normal;
      padding: 1px 11px 1px 0;
    }
    position: relative;
    font-size: 17px;
    color: #333333;
    padding: 17.5px 15px;
  }
  width: 100%;
  background-color: #ffffff;
}
</style>
