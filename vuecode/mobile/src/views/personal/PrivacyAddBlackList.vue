<template>
  <div id="app">
    <Head>屏蔽企业</Head>
    <div class="box_1">
      <van-search v-model="key" @search="onSearch" shape="round" placeholder="请输入关键字" />
    </div>
    <div class="search_tip" v-if="searchTip">
      <div class="tx1">通过以下方式搜索公司</div>
      <div class="tx2">公司全称：如“百度（中国）有限公司”</div>
      <div class="tx2">公司简称：如“百度”</div>
      <div class="tx2">屏蔽企业后，该企业将无法查看您的简历</div>
    </div>
    <div class="search_empty" v-if="num <= 0 && !searchTip">
      没有搜到您搜索的公司
    </div>
    <div class="box_2" v-if="num > 0">
      <div class="result_title">
        与“{{ aboutText }}”有关的公司，共{{ num }}个
      </div>
      <div class="list_wrapper">
        <van-checkbox-group v-model="selected">
          <label>
            <div
              :class="parseInt(item.in_blacklist) > 0 ? 'list shield' : 'list'"
              v-for="(item, index) in blackList"
              :key="index"
              @click="handleCheck(item.id,item.in_blacklist)"
            >
              <van-checkbox
                v-if="parseInt(item.in_blacklist) === 0"
                class="self_checkbox"
                :name="item.id"
                @click="handleSelected"
                :ref="`check${item.id}`"
                icon-size="16px"
              ></van-checkbox>
              <div class="tx1">{{ item.companyname }}</div>
              <div class="tx2">
                {{ item.scale_text }} · {{ item.trade_text }} ·
                {{ item.nature_text }}
              </div>
              <div class="tx3" v-if="parseInt(item.in_blacklist) > 0">
                已屏蔽
              </div>
            </div>
          </label>
        </van-checkbox-group>
      </div>
    </div>
    <div class="box_3">
      <van-button
        class="btn_left"
        plain
        round
        type="info"
        @click="handleAllSelect"
      >
        {{ allSelectedText }}
      </van-button>
      <van-button class="btn_right" round type="info" @click="handleShield">
        屏蔽所选企业 ({{ selected.length }})
      </van-button>
      <div class="clear"></div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
export default {
  name: 'PrivacyAddBlackList',
  data () {
    return {
      searchTip: true,
      key: '',
      num: 0,
      selectedNum: 0,
      aboutText: '',
      blackList: [],
      selected: [],
      effectiveList: [],
      allSelected: false,
      allSelectedText: '全选'
    }
  },
  created () {},
  methods: {
    handleCheck (id, in_blacklist) {
      if (in_blacklist == 1) {
        return false
      }
      this.$refs[`check${id}`][0].toggle()
      let _this = this
      setTimeout(function () {
        _this.syncAllSelected()
      }, 100)
    },
    // 搜索
    onSearch () {
      if (!this.key.length) {
        this.$notify('请输入关键字')
        return false
      }
      this.searchTip = false
      this.aboutText = this.key
      http
        .get(api.resume_privates_searchCompany, {keyword: this.aboutText})
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.num = res.data.items.length
            if (this.num) {
              this.blackList = res.data.items
              this.effectiveList = this.blackList.filter(
                item => parseInt(item.in_blacklist) === 0
              )
            }
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    // 选中
    handleSelected () {
      this.syncAllSelected()
    },
    // 判断是否全部选中
    syncAllSelected () {
      this.allSelected = this.selected.length === this.effectiveList.length
      this.allSelected
        ? (this.allSelectedText = '取消全选')
        : (this.allSelectedText = '全选')
    },
    // 屏蔽所选公司
    handleShield () {
      if (!this.selected.length) {
        this.$notify('请选择要屏蔽的企业')
        return false
      }
      http
        .post(api.resume_privates_add_blacklist, {
          id: this.selected
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            this.$router.push({ path: '/member/personal/privacy' })
          } else {
            this.$notify(res.message)
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    // 全选、取消全选
    handleAllSelect () {
      if (this.allSelected) {
        // 取消全选
        this.selected.splice(0, this.selected.length)
      } else {
        // 全选
        this.selected = this.effectiveList.map(function (item) {
          return item.id
        })
      }
      this.syncAllSelected()
    }
  }
}
</script>

<style lang="scss" scoped>
.box_3 {
  width: 100%;
  padding: 18px 19px 18px 26px;
  position: fixed;
  left: 0;
  bottom: 0;
  right: 0;
  border-top: 1px solid #f3f3f3;
  .btn_left {
    float: left;
  }
  .btn_right {
    float: right;
  }
}
.box_2 {
  .list_wrapper {
    width: 100%;
    padding: 0 19px 0 26px;
    .list {
      &.shield {
        .tx3 {
          display: block;
        }
        .tx1,
        .tx2 {
          color: #999999;
        }
        .self_checkbox {
          display: none;
        }
      }
      .tx3 {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 12px;
        color: #999999;
        display: none;
      }
      .self_checkbox {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translate(0, -50%);
      }
      .tx2 {
        font-size: 12px;
        color: #666666;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        padding: 0 30px 15px 28px;
      }
      .tx1 {
        font-size: 15px;
        color: #333333;
        font-weight: bold;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        padding: 20px 30px 15px 28px;
      }
      position: relative;
      &:not(:last-child) {
        border-bottom: 1px solid #f3f3f3;
      }
    }
  }
  .result_title {
    font-size: 12px;
    color: #999999;
    padding: 10px 24px 0 24px;
  }
  width: 100%;
  background-color: #ffffff;
  margin-bottom: 90px;
}
.search_empty {
  width: 100%;
  padding-top: 243px;
  text-align: center;
  font-size: 15px;
  color: #999999;
  background: url("../../assets/images/search_empty_bg.png") center 125px
    no-repeat;
  background-size: 142px 100px;
}
.search_tip {
  .tx2 {
    font-size: 15px;
    color: #666666;
    &:not(:last-child) {
      padding-bottom: 7.5px;
    }
  }
  .tx1 {
    font-size: 18px;
    color: #333333;
    font-weight: bold;
    padding: 22.5px 0 20.5px;
  }
  padding: 0 25px;
  background-color: #ffffff;
  width: 100%;
}
</style>
