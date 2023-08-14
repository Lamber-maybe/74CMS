<template>
  <div class="filter_category_wrapper for_filter_pop" :style="showSecondPop || showLowestPop ? 'overflow-x: hidden;' : ''">
    <div class="filter_item">
      <div :class="item.select ? 'item select' : 'item'" v-for="(item, index) in topList"
            :key="index" @click="makeSecondElement(item)">
        {{ item.text }}<div class="arrow" v-if="item.children.length"></div>
      </div>
    </div>
    <van-overlay :show="showSecondPop" @click="handleSecondOverlay" :class-name="second_pop_lay" />
    <van-popup :lock-scroll="false" v-model="showSecondPop" position="right" :style="{ width: '75%', top: `${offTop}px` }"
               :overlay="false" ref="filterCategoryPop">
      <div :style="`height:${layHeight}px`" class="second_wrapper">
        <div class="filter_item" v-if="secondList.length">
          <div :class="item.select ? 'item select' : 'item'" v-for="(item, index) in secondList"
               :key="index" @click="makeLowestElement(item)">
            {{ item.text }}<div class="arrow" v-if="item.children.length"></div></div>
        </div>
      </div>
    </van-popup>
    <van-overlay :show="showLowestPop" @click="showLowestPop = false" :class-name="lowest_pop_lay" :style="{'zIndex': zIndex}" />
    <van-popup :lock-scroll="false" v-model="showLowestPop" position="right" :style="{ width: '55%', top: `${offTop}px` }"
               :overlay="false">
      <div :style="`height:${layHeight}px`" class="second_wrapper">
        <div class="filter_item" v-if="lowestList.length">
          <div :class="item.select ? 'item select' : 'item'" v-for="(item, index) in lowestList"
               :key="index" @click="handleSearch(item)">{{ item.text }}</div>
        </div>
      </div>
    </van-popup>
  </div>
</template>

<script>
import Vue from 'vue'
import { Overlay } from 'vant'

Vue.use(Overlay)
export default {
  name: 'JobCategoryFilter',
  props: ['categories', 'type', 'all'],
  data () {
    return {
      categorySource: [],
      topList: [],
      secondList: [],
      lowestList: [],
      category1: '',
      category2: '',
      category3: '',
      categoryName: '',
      showSecondPop: false,
      showLowestPop: false,
      layHeight: 0,
      offTop: 0,
      second_pop_lay: 'second_pop_lay',
      lowest_pop_lay: 'lowest_pop_lay',
      zIndex: 1,
      showAll: true
    }
  },
  mounted () {
    this.$store.dispatch('getClassify', 'jobcategory').then(() => {
      // 处理职位分类格式
      this.categorySource = this.$store.state.classifyJobCategory
      let storeJobCategory = (JSON.parse(JSON.stringify(this.categorySource)))
      if (this.type) {
        storeJobCategory.unshift({ id: '', text: '不限', children: [] })
      }
      this.categorySource = storeJobCategory.map(function (item) {
        return { id: item.id, text: item.text, children: item.children }
      })
      this.initData()
    })
  },
  methods: {
    // 初始化数据
    initData () {
      if (this.all === false) {
        this.showAll = false
      }
      if (this.categories[0]) {
        // 需要恢复选中
        this.category1 = this.categories[0]
        this.category2 = this.categories[1]
        this.category3 = this.categories[2]
        this.makeElement(this.category1, this.categorySource, 2)
        if (this.secondList.length) {
          this.showSecondPop = true
        }
        if (parseInt(this.category2) !== 0) {
          this.makeElement(this.category2, this.secondList, 3)
          if (this.lowestList.length) {
            let _this = this
            setTimeout(function () {
              _this.zIndex = parseInt(_this.$refs.filterCategoryPop.$el.style.zIndex)
            }, 100)
            this.showLowestPop = true
          }
        }
      }
      this.topList = this.categorySource.map(function (item) {
        return { id: item.id, text: item.text, children: item.children, select: false }
      })
      this.syncSelect()
    },
    // 点击遮罩层
    handleSecondOverlay () {
      this.showLowestPop = false
      this.showSecondPop = false
    },
    // 同步选中状态
    syncSelect () {
      let _this = this
      this.topList = this.topList.map(function (item) {
        return { id: item.id, text: item.text, children: item.children, select: parseInt(item.id) === parseInt(_this.category1) }
      })
      this.secondList = this.secondList.map(function (item) {
        return { id: item.id, text: item.text, children: item.children, select: parseInt(item.id) === parseInt(_this.category2) }
      })
      this.lowestList = this.lowestList.map(function (item) {
        return { id: item.id, text: item.text, select: parseInt(item.id) === parseInt(_this.category3) }
      })
    },
    /**
       * 生成下级列表
       * @param id 上级id
       * @param data 数据
       * @param level 级别
       */
    makeElement (id, data, level) {
      let categoryList = data.filter(item => parseInt(item.id) === parseInt(id))
      let currentCategory = categoryList[0].children
      if (currentCategory.length) {
        if (parseInt(level) === 2) {
          // 生成次级
          this.secondList = currentCategory.map(function (item) {
            return { id: item.id, text: item.text, children: item.children, select: false }
          })
          if (this.showAll) {
            this.secondList.unshift({ id: 0, text: `全${categoryList[0].text}`, children: [], select: false })
          }
        } else {
          // 生成最后一级
          this.lowestList = currentCategory.map(function (item) {
            return { id: item.id, text: item.text, select: false }
          })
          if (this.showAll) {
            this.lowestList.unshift({ id: 0, text: `全${categoryList[0].text}`, select: false })
          }
        }
      }
    },
    /**
       * 生成次级列表
       * @param item 选中项
       */
    makeSecondElement (item) {
      let id = item.id
      if (id) {
        this.category1 = id
        if (!this.category2) { this.category2 = 0 }
        if (!this.category3) { this.category3 = '' }
        this.secondList = []
        this.lowestList = []
        this.makeElement(id, this.categorySource, 2)
        if (!this.secondList.length) {
          // 没有次级，跳转
          this.category2 = 0
          this.category3 = 0
          this.categoryName = item.text
          this.toSearch()
        } else {
          this.showSecondPop = !this.showSecondPop
        }
      } else {
        // 不限
        this.category1 = ''
        this.category2 = ''
        this.category3 = ''
        this.categoryName = '职位'
        this.toSearch()
      }
      this.syncSelect()
    },
    /**
       * 生成最后一级列表
       * @param item 选中项
       */
    makeLowestElement (item) {
      let id = item.id
      this.category2 = id
      this.lowestList = []
      if (parseInt(id) === 0) {
        // 选择全部
        this.category3 = 0
        this.categoryName = item.text
        this.toSearch()
      } else {
        this.makeElement(id, this.secondList, 3)
        if (!this.lowestList.length) {
          this.category3 = 0
          this.categoryName = item.text
          this.toSearch()
        } else {
          this.zIndex = parseInt(this.$refs.filterCategoryPop.$el.style.zIndex) + 1
          this.showLowestPop = !this.showLowestPop
        }
      }
      this.syncSelect()
    },
    /**
       * 最后一级选择
       * @param item 选中项
       */
    handleSearch (item) {
      this.category3 = item.id
      this.categoryName = item.text
      this.toSearch()
      this.syncSelect()
    },
    toSearch () {
      this.handleSecondOverlay()
      if (this.type) {
        // 筛选
        this.$emit('doSearch', { category1: this.category1, category2: this.category2, category3: this.category3, categoryName: this.categoryName })
      } else {
        // 选择
        this.$emit('doSelect', [ this.category1, this.category2, this.category3, this.categoryName ])
      }
    }
  }
}
</script>

<style lang="scss" scoped>
  .second_pop_lay {
    background-color: rgba(0,0,0,.4); position: absolute;
  }
  .lowest_pop_lay {
    background-color: rgba(0,0,0,.2); position: absolute; left: 25%; z-index: 6;
  }
  .filter_category_wrapper {
    display: flex; position: relative;
    .second_wrapper {
      .item {}
      max-height: 100%; overflow-y: auto; background-color: #ffffff; display: flex;
    }
    .filter_item {
      flex: 1; height: 100%;
      .item {
        .arrow {
          position: absolute; right: 15px; top: 21px; width: 7px; height: 7px; border-top: 1px solid #999999;
          border-right: 1px solid #999999; transform: rotate(45deg);
        }
        &::before {
          position: absolute; box-sizing: border-box; content: ' '; pointer-events: none; right: 0; bottom: 0; left: 20px;
          border-bottom: 0.026667rem solid #ebedf0; -webkit-transform: scaleY(.5); transform: scaleY(.5);
        }
        position: relative; padding: 0 30px; height: 50px; line-height: 50px; font-size: 14px; color: #323233;
        &.select { color: #1a8afa }
      }
    }
    span {
      &.select { color: #1a8afa }
      margin: 5px;
    }
    font-size: 16px;
  }
</style>
