<template>
  <div id="app">
    <Meta pagealias="companylist" :query_data="$route.query" />
    <Head>企业列表</Head>
    <div class="box_1">
      <div class="content" @click="toggleSearch">
        <div :class="params.keyword === '' ? 'search_ico' : 'search_ico has'">
          {{ params.keyword === "" ? "请输入关键字" : params.keyword }}
        </div>
      </div>
    </div>
    <van-popup
      v-model="show"
      position="top"
      :overlay="true"
      :style="{ height: '90%', width: '100%' }"
    >
      <TopSearch
        type="company"
        @hideSearch="toggleSearch"
        @doSearch="doSearchByKeyword"
      ></TopSearch>
    </van-popup>
    <div class="box_2">
      <van-dropdown-menu>
        <van-dropdown-item
          :title="districtTitle"
          ref="dropDistrict"
          @opened="openedDistrict"
          @closed="closedDistrict"
        >
          <DistrictFilter
            :districts="[params.district1, params.district2, params.district3]"
            :type="true"
            @doSearch="doSearchByDistrict"
          ></DistrictFilter>
        </van-dropdown-item>
        <van-dropdown-item
          :title="tradeTitle"
          v-model="params.trade"
          :options="optionTrade"
          @change="handleTrade"
        />
        <van-dropdown-item
          :title="scaleTitle"
          v-model="params.scale"
          :options="optionScale"
          @change="handleScale"
        />
        <van-dropdown-item
          :title="natureTitle"
          v-model="params.nature"
          :options="optionNature"
          @change="handleNature"
        />
      </van-dropdown-menu>
    </div>
    <div class="form_split_10"></div>
    <van-empty
      image="search"
      description="没有找到对应的数据"
      style="background-color:#fff"
      v-if="show_empty === true"
    />
    <van-list
      v-if="dataset.length > 0"
      v-model="loading"
      :finished="finished"
      :finished-text="finished_text"
      @load="onLoad"
      :immediate-check="true"
    >
      <div class="box_3">
        <div v-for="(item,index) in dataset" :key="index" @click="toDetail(item.id)">
          <div class="list">
            <div class="up">
              <div class="logo_box">
                <img :src="item.logo_src" :alt="item.companyname" />
              </div>
              <div class="tx1">
                <div class="name">{{ item.companyname }}</div>
                <div class="auth_ico" v-if="item.company_audit == 1"></div>
                <div class="crw_ico" v-if="item.setmeal_icon != ''">
                  <img :src="item.setmeal_icon" />
                </div>
                <div class="clear"></div>
              </div>
              <div class="tx2">{{ item.district_text }}</div>
              <div class="tx3">
                {{ item.nature_text }} · {{ item.scale_text }} ·
                {{ item.trade_text }}
              </div>
            </div>
            <div class="down" v-if="item.jobnum>0">
              热招：
              <span class="link">{{ item.first_jobname }}</span>
              等{{ item.jobnum }}个岗位
              <div class="more"></div>
            </div>
            <div class="down" v-else>
              暂无招聘岗位
              <div class="more"></div>
            </div>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
    </van-list>
    <BottomNav></BottomNav>
  </div>
</template>

<script>
import { obj2Param } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import DistrictFilter from '@/components/DistrictFilter'
export default {
  name: 'CompanyList',
  components: {
    DistrictFilter
  },
  data () {
    return {
      dataset: [],
      loading: false,
      finished: false,
      finished_text: '',
      show_empty: false,
      params: {
        keyword: '',
        district1: '',
        district2: '',
        district3: '',
        trade: '',
        scale: '',
        nature: ''
      },
      page: 1,
      pagesize: 15,
      districtTitle: '地区',
      tradeTitle: '行业',
      scaleTitle: '规模',
      natureTitle: '性质',
      optionTrade: [],
      optionScale: [],
      optionNature: [],
      show: false
    }
  },
  watch: {
    $route (to, from) {
      // 对路由变化作出响应...
      if (from.name != 'companyShow' && to.name == 'companyList') {
        // 将地址栏中的url参数初始化到参数对象中
        this.initQuery(to.query)
        this.fetchData(true)
        this.restoreFilter()
      }
    }
  },
  beforeRouteLeave (to, from, next) {
    if (to.name == 'companyShow') {
      if (!from.meta.keepAlive) {
        from.meta.keepAlive = true
      }
      next()
    } else {
      from.meta.keepAlive = false
      next()
    }
  },
  created () {
    // 请求列表数据
    this.initQuery(this.$route.query)
    this.fetchData(true)
    this.$store.dispatch('getClassify', 'citycategory')
    this.$store.dispatch('getClassify', 'trade').then(() => {
      // 重构筛选项数据格式
      let storeTrade = this.parseData(this.$store.state.classifyTrade)
      storeTrade.unshift({ id: '', text: '不限' })
      this.optionTrade = storeTrade.map(function (item) {
        return { text: item.text, value: item.id }
      })
      this.restoreFilter()
    })
    this.$store.dispatch('getClassify', 'companyScale').then(() => {
      let storeScale = this.parseData(this.$store.state.classifyCompanyScale)
      storeScale.unshift({ id: '', text: '不限' })
      this.optionScale = storeScale.map(function (item) {
        return { text: item.text, value: item.id }
      })
      this.restoreFilter()
    })
    this.$store.dispatch('getClassify', 'companyNature').then(() => {
      let storeNature = this.parseData(this.$store.state.classifyCompanyNature)
      storeNature.unshift({ id: '', text: '不限' })
      this.optionNature = storeNature.map(function (item) {
        return { text: item.text, value: item.id }
      })
      this.restoreFilter()
    })
  },
  mounted () {
    this.restoreFilter()
  },
  methods: {
    parseData (data) {
      return JSON.parse(JSON.stringify(data))
    },
    initQuery (query) {
      for (const key in this.params) {
        if (query.hasOwnProperty(key)) {
          this.params[key] = query[key]
        } else {
          this.params[key] = ''
        }
      }
    },
    // 地区筛选打开之后给筛选组件赋值
    openedDistrict () {
      this.$refs.dropDistrict.$children[0].$children[0].initData()
      setTimeout(() => {
        this.dynamicAssignFun(this.$refs.dropDistrict)
      }, 300)
    },
    closedDistrict () {
      this.$refs.dropDistrict.$children[0].$children[0].handleCityOverlay()
    },
    // 动态赋值筛选组件公用方法
    dynamicAssignFun (obj) {
      if (obj.$children[0]) {
        let thisHeight = obj.$children[0].$el.clientHeight
        obj.$children[0].$children[0].layHeight = thisHeight
        let offTop = obj.$el.offsetTop
        obj.$children[0].$children[0].offTop = parseInt(
          parseInt(offTop) + parseInt(thisHeight) / 2
        )
      }
    },
    // 恢复选中
    restoreFilter () {
      let queryData = this.$route.query
      // 恢复地区
      if (queryData['district1']) {
        this.params.district1 = queryData['district1']
        this.params.district2 = queryData['district2']
        this.params.district3 = queryData['district3']
        let storeCity = this.$store.state.classifyCityOriginal
        let selectText = []
        let topItem = storeCity.filter(
          item => parseInt(item.value) === parseInt(this.params.district1)
        )[0]
        selectText.push(topItem.label)
        if (topItem.children.length) {
          if (parseInt(this.params.district2)) {
            let secondItem = topItem.children.filter(
              item => parseInt(item.value) === parseInt(this.params.district2)
            )[0]
            selectText.push(secondItem.label)
            if (secondItem.children.length) {
              if (parseInt(this.params.district3)) {
                let lowestItem = secondItem.children.filter(
                  item =>
                    parseInt(item.value) === parseInt(this.params.district3)
                )[0]
                selectText.push(lowestItem.label)
              } else {
                selectText.push(`全${selectText[selectText.length - 1]}`)
              }
            }
          } else {
            selectText.push(`全${selectText[selectText.length - 1]}`)
          }
        }
        this.districtTitle = selectText[selectText.length - 1]
      } else {
        this.params.district1 = ''
        this.params.district2 = ''
        this.params.district3 = ''
        this.districtTitle = '地区'
      }
      // 恢复行业
      if (queryData['trade'] && this.optionTrade) {
        let thisTrade = this.optionTrade.filter(
          item => parseInt(item.value) === parseInt(queryData['trade'])
        )
        this.tradeTitle = thisTrade[0].text
        this.params.trade = thisTrade[0].value
      } else {
        this.tradeTitle = '行业'
        this.params.trade = ''
      }
      // 恢复规模
      if (queryData['scale'] && this.optionScale) {
        let thisScale = this.optionScale.filter(
          item => parseInt(item.value) === parseInt(queryData['scale'])
        )
        this.scaleTitle = thisScale[0].text
        this.params.scale = thisScale[0].value
      } else {
        this.scaleTitle = '规模'
        this.params.scale = ''
      }
      // 恢复性质
      if (queryData['nature'] && this.optionNature) {
        let thisNature = this.optionNature.filter(
          item => parseInt(item.value) === parseInt(queryData['nature'])
        )
        this.natureTitle = thisNature[0].text
        this.params.nature = thisNature[0].value
      } else {
        this.natureTitle = '性质'
        this.params.nature = ''
      }
    },
    // 地区筛选
    doSearchByDistrict (data) {
      this.doSearch({
        district1: data.district1,
        district2: data.district2,
        district3: data.district3
      })
      this.districtTitle = data.districtName
      this.$refs.dropDistrict.toggle()
    },
    // 筛选性质
    handleNature (value) {
      if (value) {
        let thisNature = this.optionNature.filter(
          item => parseInt(item.value) === parseInt(value)
        )
        this.natureTitle = thisNature[0].text
      } else {
        this.natureTitle = '性质'
      }
      this.doSearch({ nature: value })
    },
    // 筛选类别
    handleScale (value) {
      if (value) {
        let thisScale = this.optionScale.filter(
          item => parseInt(item.value) === parseInt(value)
        )
        this.scaleTitle = thisScale[0].text
      } else {
        this.scaleTitle = '类别'
      }
      this.doSearch({ scale: value })
    },
    // 筛选行业
    handleTrade (value) {
      if (value) {
        let thisTrade = this.optionTrade.filter(
          item => parseInt(item.value) === value
        )
        this.tradeTitle = thisTrade[0].text
      } else {
        this.tradeTitle = '行业'
      }
      this.doSearch({ trade: value })
    },
    // 请求列表数据，init为true时直接更改dataset值，false时代表上拉加载回的数据追加进dataset
    fetchData (init) {
      this.show_empty = false
      // 将地址栏中的url参数初始化到参数对象中

      let conditions = { ...this.params }
      if (init === true) {
        this.page = 1
        this.finished_text = ''
        this.finished = false
      }
      conditions.page = this.page
      conditions.pagesize = this.pagesize
      http
        .get(api.companylist, conditions)
        .then(res => {
          if (init === true) {
            this.dataset = [...res.data.items]
          } else {
            this.dataset = this.dataset.concat(res.data.items)
          }
          // 加载状态结束
          this.loading = false

          // 数据全部加载完成
          if (res.data.items.length < this.pagesize) {
            this.finished = true
            if (init === false) {
              this.finished_text = '没有更多了'
            } else if (res.data.items.length == 0) {
              this.show_empty = true
            }
          }
        })
        .catch(() => {})
    },
    onLoad () {
      this.page++
      this.fetchData(false)
    },
    toDetail (id) {
      this.$router.push('/company/' + id)
    },
    // 搜索通用函数
    doSearch (data) {
      for (const key in data) {
        if (this.params.hasOwnProperty(key)) {
          this.params[key] = data[key]
        }
      }
      this.page = 1
      this.$router.push('/companylist' + obj2Param(this.params, '?'))
    },
    doSearchByKeyword (data) {
      for (const key in this.params) {
        this.params[key] = ''
      }
      this.params.keyword = data.keyword
      this.page = 1
      this.$router.push('/companylist' + obj2Param(this.params, '?'))
    },
    toggleSearch () {
      this.show = !this.show
    }
  }
}
</script>

<style lang="scss" scoped>
.box_3 {
  .list {
    .down {
      &::after {
        position: absolute;
        right: 6px;
        top: 18px;
        width: 6px;
        height: 6px;
        border-top: 1px solid #666666;
        border-right: 1px solid #666666;
        transform: rotate(45deg);
        content: " ";
      }
      .link {
        color: #1787fb;
      }
      position: relative;
      padding: 12.5px 25px 12.5px 0;
      font-size: 13px;
      color: #666666;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      border-top: 1px dotted #f8f8f8;
    }
    .up {
      .tx3 {
        font-size: 14px;
        color: #666666;
        padding-bottom: 17.5px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .tx2 {
        font-size: 13px;
        color: #999999;
        padding: 11.5px 0 9.5px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .tx1 {
        .crw_ico {
          float: left;
          margin-left: 6px;
          width: 14px;
          height: 22px;
          position: relative;
          img {
            width: 100%;
            height: 12px;
            position: absolute;
            top: 5px;
            left: 0;
            border: 0;
          }
        }
        .auth_ico {
          float: left;
          margin-left: 6px;
          width: 15px;
          height: 22px;
          background: url("../assets/images/jobs_list_auth_ico.png") 0 center
            no-repeat;
          background-size: 15px 11px;
        }
        .name {
          float: left;
          font-size: 16px;
          font-weight: bold;
          color: #333333;
          max-width: 220px;
          overflow: hidden;
          white-space: nowrap;
          text-overflow: ellipsis;
        }
        padding-top: 17.5px;
      }
      .logo_box {
        position: absolute;
        left: 2px;
        top: 22px;
        width: 60px;
        height: 60px;
        img {
          width: 60px;
          height: 60px;
          border: 0;
        }
      }
      position: relative;
      padding-left: 75px;
    }
    width: 100%;
    background-color: #ffffff;
    position: relative;
    padding: 0 17px;
  }
}
.box_2 {
  .van-hairline--top-bottom {
    &::after {
      border: 0;
    }
  }
}
.box_1 {
  .content {
    .search_ico {
      font-size: 12px;
      color: #c9c9c9;
      padding: 10px 0 10px 23px;
      background: url("../assets/images/search_ico_gray.svg") 0 center no-repeat;
      background-size: 15px;
      position: absolute;
      top: 0;
      left: 50%;
      transform: translate(-50%, 0);
      line-height: normal;
    }
    position: relative;
    width: 340px;
    height: 37px;
    margin: 0 auto;
    background-color: #f8f8f8;
    text-align: center;
    border-radius: 36px;
  }
  width: 100%;
  background-color: #ffffff;
  padding-top: 11px;
}
</style>
