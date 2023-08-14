<template>
  <div>
    <div class="ts_b1">
      <div class="put_away" @click="backList"></div>
      <div class="content">
        <div class="input_box">
          <input
            class="input"
            type="text"
            v-model="keyword"
            :placeholder="placeholder"
          />
        </div>
        <div class="clear"></div>
      </div>
      <div class="ts_btn" @click="doSearch">搜索</div>
    </div>
    <div class="ts_b2">
      <div class="ts_pt">
        搜索历史
        <div class="del" @click="clearHistory"></div>
      </div>
      <div class="content">
        <div
          class="item"
          v-for="(item,index) in history"
          :key="index"
          @click="doSearchByKeyword(item)"
        >
          {{ item }}
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <div class="ts_sp" v-if="type==='job'"></div>
    <div class="ts_b2" v-if="type==='job'">
      <div class="ts_pt">热门搜索</div>
      <div class="content">
        <div
          v-for="(item, index) in hotwordlist"
          :key="index"
          :class="'item' + (index < 3 ? ' leader' : '')"
          @click="doSearchByKeyword(item.word)"
        >
          <div v-if="index < 3" :class="'num n' + (index + 1)">
            {{ index + 1 }}
          </div>
          {{ item.word }}
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'TopSearch',
  props: ['type'],
  data () {
    return {
      placeholder: '请输入职位名称/企业名称',
      history_key: '',
      keyword: '',
      history: [],
      hotwordlist: []
    }
  },
  created () {
    this.keyword = this.$route.query.keyword
    this.history_key = 'serach_history_' + this.type
    this.initHistory()
    if (this.type === 'job') {
      this.initHotword()
    }
    if (this.type === 'resume') {
      this.placeholder = '请输入关键词'
    } else if (this.type === 'company') {
      this.placeholder = '请输入企业名称'
    }
  },
  methods: {
    doSearch () {
      this.$emit('doSearch', { keyword: this.keyword })
      this.updateHistory(this.keyword)
      this.backList()
    },
    doSearchByKeyword (keyword) {
      this.keyword = keyword
      this.$emit('doSearch', { keyword: this.keyword })
      this.updateHistory(this.keyword)
      this.backList()
    },
    backList () {
      this.$emit('hideSearch')
    },
    initHistory () {
      let list = localStorage.getItem(this.history_key)
      list = JSON.parse(list)
      if (list === null) {
        this.history = []
      } else {
        this.history = [...list]
      }
    },
    updateHistory (keyword) {
      if (keyword === '') {
        return
      }
      let arr = []
      let list = localStorage.getItem(this.history_key)
      arr = JSON.parse(list)
      arr = arr === null ? [] : arr

      arr.unshift(keyword)

      let uniqueSet = new Set(arr)
      let historyArr = [...uniqueSet]
      if (historyArr.length > 15) {
        historyArr.pop()
      }
      localStorage.setItem(this.history_key, JSON.stringify(historyArr))
      this.history = historyArr
    },
    clearHistory () {
      this.$dialog
        .confirm({
          message: '确定清空搜索记录吗？'
        })
        .then(() => {
          localStorage.setItem(this.history_key, null)
          this.history = []
        })
        .catch(() => {
          // on cancel
        })
    },
    initHotword () {
      http
        .get(api.hotwordlist, { limit: 30 })
        .then(res => {
          this.hotwordlist = [...res.data.items]
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.ts_sp {
  width: 100%;
  height: 5px;
  background-color: #f3f3f3;
}
.ts_b2 {
  .content {
    .item {
      &.leader {
        .num {
          &.n3 {
            background-color: #1787fb;
          }
          &.n2 {
            background-color: #fdba6a;
          }
          &.n1 {
            background-color: #ff5374;
          }
          position: absolute;
          left: 15px;
          font-size: 10px;
          color: #ffffff;
          padding: 2px 4px;
          border-radius: 4px;
          top: 50%;
          transform: translate(0, -50%);
        }
        padding-left: 33px;
        position: relative;
      }
      float: left;
      background-color: #fafafa;
      padding: 7px 15px;
      font-size: 14px;
      color: #333333;
      border-radius: 28px;
      margin: 0 10px 10px 0;
    }
    padding: 15px 0 10px 16px;
  }
  margin-top: 20px;
}
.ts_pt {
  .del {
    position: absolute;
    right: 0;
    top: 0;
    width: 45px;
    height: 100%;
    background: url('../assets/images/del_ico.svg') center no-repeat;
    background-size: 16px;
  }
  position: relative;
  padding-left: 16px;
  font-size: 18px;
  font-weight: bold;
  color: #333333;
}
.ts_b1 {
  .ts_btn {
    position: absolute;
    right: 0;
    top: 2px;
    padding: 18px 0;
    text-align: center;
    width: 56px;
    font-size: 14px;
    color: #333333;
  }
  .content {
    .input_box {
      .input {
        &::placeholder {
          color: #c9c9c9;
        }
        position: absolute;
        left: 20px;
        width: 190px;
        padding: 7px 0;
        font-size: 12px;
        color: #666666;
        top: 50%;
        transform: translate(0, -50%);
        border: 0;
        line-height: 1;
        background-color: transparent;
      }
      position: relative;
      width: 200px;
      height: 36px;
      float: left;
    }
    .drop {
      .text {
        &::after {
          content: ' ';
          position: absolute;
          width: 0;
          height: 0;
          z-index: 3;
          border: 4px solid transparent;
          border-top-color: #999999;
          right: -5px;
          top: 16px;
          transform: translate(-50%, 0);
        }
        padding: 9px 12px 9px 0;
        font-size: 12px;
        color: #666666;
        position: relative;
        width: 36px;
        margin: 0 auto;
      }
      &::after {
        content: ' ';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        height: 15px;
        border-right: 1px solid #e0e0e0;
      }
      float: left;
      width: 58px;
      text-align: center;
      position: relative;
    }
    background-color: #f8f8f8;
    width: 270px;
    position: relative;
    border-radius: 36px;
  }
  .put_away {
    &::after {
      position: absolute;
      left: 50%;
      top: 21px;
      width: 10px;
      height: 10px;
      border-top: 1px solid #666666;
      border-left: 1px solid #666666;
      transform: rotate(-45deg);
      content: ' ';
    }
    position: absolute;
    left: 0;
    top: 0;
    width: 47px;
    height: 100%;
  }
  position: relative;
  padding: 9px 55px 9px 47px;
}
</style>
