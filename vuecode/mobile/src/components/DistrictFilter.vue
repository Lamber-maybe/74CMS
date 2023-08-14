<template>
  <div class="filter_dis_wrapper for_filter_pop">
    <div class="filter_item" ref="filterTopPop">
      <div :class="item.select ? 'item select' : 'item'"
           v-for="(item, index) in provinceList"
           :key="index" @click="makeCityElement(item)">
        {{ item.text }}
        <div class="arrow" v-if="item.children.length"></div>
      </div>
    </div>
    <van-overlay :show="showCityPop"
                 @click="handleCityOverlay"
                 :class-name="city_pop_lay"/>
    <van-popup v-model="showCityPop"
               position="right"
               :style="{ width: '75%', top: `${offTop}px` }"
               :overlay="false" ref="filterCityPop">
      <div :style="`height:${layHeight}px`" class="second_wrapper">
        <div class="filter_item" v-if="cityList.length">
          <div :class="item.select ? 'item select' : 'item'"
               v-for="(item, index) in cityList"
               :key="index" @click="makeCountyElement(item)">
            {{ item.text }}
            <div class="arrow" v-if="item.children.length"></div></div>
        </div>
      </div>
    </van-popup>
    <van-overlay :show="showCountyPop"
                 @click="showCountyPop = false"
                 :class-name="count_pop_lay"
                 :style="{'zIndex': zIndex}" />
    <van-popup v-model="showCountyPop"
               position="right"
               :style="{ width: '50%', top: `${offTop}px` }"
               :overlay="false">
      <div :style="`height:${layHeight}px`" class="second_wrapper">
        <div class="filter_item" v-if="countyList.length">
          <div :class="item.select ? 'item select' : 'item'"
               v-for="(item, index) in countyList"
               :key="index"
               @click="handleSearch(item)">
            {{ item.text }}
          </div>
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
  name: 'DistrictFilter',
  props: ['districts', 'type', 'all'],
  data () {
    return {
      citySource: [],
      provinceList: [],
      cityList: [],
      countyList: [],
      district1: '',
      district2: '',
      district3: '',
      districtName: '',
      showCityPop: false,
      showCountyPop: false,
      layHeight: 0,
      offTop: 0,
      city_pop_lay: 'city_pop_lay',
      count_pop_lay: 'count_pop_lay',
      zIndex: 1,
      showAll: true
    }
  },
  mounted () {
    this.$store.dispatch('getClassify', 'citycategory').then(() => {
      // 处理地区格式
      this.citySource = this.$store.state.classifyCityOriginal
      let storeDistrict = (JSON.parse(JSON.stringify(this.citySource)))
      if (this.type) {
        storeDistrict.unshift({ value: '', label: '不限', children: [] })
      }
      this.citySource = storeDistrict.map(function (item) {
        return { id: item.value, text: item.label, children: item.children }
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
      if (this.districts[0]) {
        // 需要恢复选中
        this.district1 = this.districts[0]
        this.district2 = this.districts[1]
        this.district3 = this.districts[2]
        this.makeElement(this.district1, this.citySource, 2)
        if (this.cityList.length) {
          this.showCityPop = true
        }
        if (parseInt(this.district2) !== 0) {
          this.makeElement(this.district2, this.cityList, 3)
          if (this.countyList.length) {
            let _this = this
            setTimeout(function () {
              let elIndex = _this.$refs.filterCityPop.$el.style.zIndex
              _this.zIndex = parseInt(elIndex)
            }, 100)
            this.showCountyPop = true
          }
        }
      }
      this.provinceList = this.citySource.map(function (item) {
        return {
          id: item.id,
          text: item.text,
          children: item.children,
          select: false
        }
      })
      this.syncSelect()
    },
    // 点击遮罩层
    handleCityOverlay () {
      this.showCountyPop = false
      this.showCityPop = false
    },
    // 同步选中状态
    syncSelect () {
      let _this = this
      this.provinceList = this.provinceList.map(function (item) {
        return {
          id: item.id,
          text: item.text,
          children: item.children,
          select: parseInt(item.id) === parseInt(_this.district1)
        }
      })
      this.cityList = this.cityList.map(function (item) {
        return {
          id: item.id,
          text: item.text,
          children: item.children,
          select: parseInt(item.id) === parseInt(_this.district2)
        }
      })
      this.countyList = this.countyList.map(function (item) {
        return {
          id: item.id,
          text: item.text,
          select: parseInt(item.id) === parseInt(_this.district3)
        }
      })
    },
    /**
       * 生成下级列表
       * @param id 上级id
       * @param data 数据
       * @param level 级别
       */
    makeElement (id, data, level) {
      let disList = data.filter(item => parseInt(item.id) === parseInt(id))
      let currentDis = disList[0].children
      if (currentDis.length) {
        if (parseInt(level) === 2) {
          // 生成市级
          this.cityList = currentDis.map(function (item) {
            return {
              id: item.value,
              text: item.label,
              children: item.children,
              select: false
            }
          })
          if (this.showAll) {
            this.cityList.unshift(
              {
                id: 0,
                text: `全${disList[0].text}`,
                children: [],
                select: false
              }
            )
          }
        } else {
          // 生成区级
          this.countyList = currentDis.map(function (item) {
            return {
              id: item.value,
              text: item.label,
              select: false
            }
          })
          if (this.showAll) {
            this.countyList.unshift(
              {id: 0,
                text: `全${disList[0].text}`,
                select: false
              }
            )
          }
        }
      }
    },
    /**
       * 生成市级列表
       * @param item 选中项
       */
    makeCityElement (item) {
      let id = item.id
      if (id) {
        this.district1 = id
        if (!this.district2) { this.district2 = 0 }
        if (!this.district3) { this.district3 = '' }
        this.cityList = []
        this.countyList = []
        this.makeElement(id, this.citySource, 2)
        if (!this.cityList.length) {
          // 没有市级，跳转
          this.district2 = 0
          this.district3 = 0
          this.districtName = item.text
          this.toSearch()
        } else {
          this.showCityPop = !this.showCityPop
        }
      } else {
        // 不限
        this.district1 = ''
        this.district2 = ''
        this.district3 = ''
        this.districtName = '地区'
        this.toSearch()
      }
      this.syncSelect()
    },
    /**
       * 生成区级列表
       * @param item 选中项
       */
    makeCountyElement (item) {
      let id = item.id
      this.district2 = id
      this.countyList = []
      if (parseInt(id) === 0) {
        // 选择全部
        this.district3 = 0
        this.districtName = item.text
        this.toSearch()
      } else {
        this.makeElement(id, this.cityList, 3)
        if (!this.countyList.length) {
          this.district3 = 0
          this.districtName = item.text
          this.toSearch()
        } else {
          let elIndex = this.$refs.filterCityPop.$el.style.zIndex
          this.zIndex = parseInt(elIndex) + 1
          this.showCountyPop = !this.showCountyPop
        }
      }
      this.syncSelect()
    },
    /**
       * 区级地区选择
       * @param item 选中项
       */
    handleSearch (item) {
      this.district3 = item.id
      this.districtName = item.text
      this.toSearch()
      this.syncSelect()
    },
    toSearch () {
      this.handleCityOverlay()
      if (this.type) {
        // 筛选
        this.$emit('doSearch', {
          district1: this.district1,
          district2: this.district2,
          district3: this.district3,
          districtName: this.districtName
        })
      } else {
        // 选择
        this.$emit('doSelect', [
          this.district1,
          this.district2,
          this.district3,
          this.districtName
        ])
      }
    }
  }
}
</script>

<style lang="scss" scoped>
  .city_pop_lay {
    background-color: rgba(0,0,0,.4); position: absolute;
  }
  .count_pop_lay {
    background-color: rgba(0,0,0,.2); position: absolute; left: 25%;
    z-index: 6;
  }
  .filter_dis_wrapper {
    display: flex; position: relative; overflow-x: hidden;
    .second_wrapper {
      .item {}
      max-height: 100%; overflow-y: auto; background-color: #ffffff;
      display: flex;
    }
    .filter_item {
      flex: 1; display: block; height: 100%; overflow-x: hidden;
      overflow-y: auto;
      .item {
        .arrow {
          position: absolute; right: 15px; top: 21px; width: 7px;
          height: 7px; border-top: 1px solid #999999;
          border-right: 1px solid #999999; transform: rotate(45deg);
        }
        &::before {
          position: absolute; box-sizing: border-box; content: ' ';
          pointer-events: none; right: 0; bottom: 0; left: 20px;
          border-bottom: 0.026667rem solid #ebedf0;
          -webkit-transform: scaleY(.5); transform: scaleY(.5);
        }
        position: relative; padding: 0 30px; height: 50px;line-height: 50px;
        font-size: 14px; color: #323233;
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
