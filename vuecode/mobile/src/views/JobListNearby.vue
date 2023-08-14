<template>
  <div id="app">
    <Head>附近职位</Head>
    <div class="box_1">
      <div class="content" @click="toggleSearch">
        <div class="search_ico">
          {{ params.keyword == "" ? "请输入关键字" : params.keyword }}
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
        type="job"
        @hideSearch="toggleSearch"
        @doSearch="doSearchByKeyword"
      ></TopSearch>
    </van-popup>
    <div class="box_2">
      <van-dropdown-menu class="filter_menu">
        <van-dropdown-item
          :title="rangeTitle"
          v-model="params.range"
          :options="optionRange"
          @change="handleRange"
          @opened="openedRange"
        />
        <van-dropdown-item
          :title="categoryTitle"
          ref="dropCategory"
          @opened="openedCategory"
          @closed="closedCategory"
        >
          <JobCategoryFilter
            :categories="[params.category1, params.category2, params.category3]"
            :type="true"
            @doSearch="doSearchByCategory"
          ></JobCategoryFilter>
        </van-dropdown-item>
        <van-dropdown-item
          :title="wageTitle"
          v-model="optionWage"
          :options="optionFilterWage"
          @change="handleWage"
        />
        <van-dropdown-item :title="otherTitle" ref="dropMore">
          <div class="more_box">
            <div class="item_wrapper">
              <div class="item_title">学历要求</div>
              <div class="item_group">
                <div
                  :class="item.select ? 'item selected' : 'item'"
                  v-for="(item, index) in optionEducation"
                  :key="index"
                  @click="
                    optionEducation = restructureData(
                      optionEducation,
                      item.id,
                      'education'
                    )
                  "
                >
                  {{ item.text }}
                </div>
                <div class="clear"></div>
              </div>
              <div class="item_title">工作经验</div>
              <div class="item_group">
                <div
                  :class="item.select ? 'item selected' : 'item'"
                  v-for="(item, index) in optionExperience"
                  :key="index"
                  @click="
                    optionExperience = restructureData(
                      optionExperience,
                      item.id,
                      'experience'
                    )
                  "
                >
                  {{ item.text }}
                </div>
                <div class="clear"></div>
              </div>
              <div class="item_title">福利待遇</div>
              <div class="item_group">
                <div
                  :class="item.select ? 'item selected' : 'item'"
                  v-for="(item, index) in optionJobTag"
                  :key="index"
                  @click="handleCheckJobTag(item)"
                >
                  {{ item.text }}
                </div>
                <div class="clear"></div>
              </div>
              <div class="item_title">更新时间</div>
              <div class="item_group">
                <div
                  :class="item.select ? 'item selected' : 'item'"
                  v-for="(item, index) in optionSettr"
                  :key="index"
                  @click="
                    optionSettr = restructureData(optionSettr, item.id, 'settr')
                  "
                >
                  {{ item.text }}
                </div>
                <div class="clear"></div>
              </div>
              <div class="wrapper_bottom">
                <div class="btn_c" @click="handleClearMore">清空</div>
                <div class="btn_c blue" @click="handleSelectMore">确定</div>
              </div>
            </div>
          </div>
        </van-dropdown-item>
      </van-dropdown-menu>
    </div>
    <div class="form_split_10"></div>
    <div class="location_box">
      <div class="text">当前位置：{{ location_text }}</div>
      <div class="refresh" @click="setlocation"></div>
      <div class="location" @click="$router.push('/jobmap')"></div>
      <div class="clear"></div>
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
            <div class="tx1">
              <div class="name">{{ item.jobname }}</div>
              <div class="worry_ico" v-if="item.emergency == 1">急</div>
              <div class="clear"></div>
              <div class="wage">{{ item.wage_text }}</div>
            </div>
            <div class="tx2">
              {{ item.education_text }} · {{ item.experience_text }} ·
              {{ item.district_text }}
              <div class="time">{{ item.refreshtime }}</div>
            </div>
            <div class="tag_wrapper">
              <van-tag
                color="#e9f8ff"
                text-color="#8096a3"
                v-for="(tag, key) in item.tag_text_arr"
                :key="key"
              >
                {{ tag }}
              </van-tag>
            </div>
            <div class="company">
              <div class="name">{{ item.companyname }}</div>
              <div class="auth_ico" v-if="item.company_audit == 1"></div>
              <div class="crw_ico" v-if="item.setmeal_icon != ''"><img :src="item.setmeal_icon" /></div>
              <div class="clear"></div>
              <div class="distance">
                {{ item.distance }}
              </div>
            </div>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
    </van-list>
    <BottomNav></BottomNav>
    <baidu-map
      class="bm-view"
      :ak="$store.state.config.map_ak"
      @ready="handlerMap"
    ></baidu-map>
  </div>
</template>

<script>
import wxshare from '@/assets/js/share.js'
import { obj2Param } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import BaiduMap from 'vue-baidu-map/components/map/Map.vue'
import JobCategoryFilter from '@/components/JobCategoryFilter'
export default {
  name: 'JobListNearby',
  filters: {},
  components: {
    BaiduMap,
    JobCategoryFilter
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
        experience: '',
        minwage: '',
        maxwage: '',
        filter_apply: '',
        nature: '',
        education: '',
        tag: '',
        settr: '',
        range: ''
      },
      def_range: 30,
      lat: '',
      lng: '',
      location_text: '正在获取位置信息...',
      page: 1,
      pagesize: 15,
      rangeTitle: '距离',
      categoryTitle: '职位',
      wageTitle: '薪资',
      otherTitle: '其他',
      show: false,
      optionFilterWage: [
        { text: '不限', value: 0, min: '', max: '' },
        { text: '1000元以下', value: 1, min: 0, max: 1000 },
        { text: '1000-2000元', value: 2, min: 1000, max: 2000 },
        { text: '2000-3000元', value: 3, min: 2000, max: 3000 },
        { text: '3000-5000元', value: 4, min: 3000, max: 5000 },
        { text: '5000-8000元', value: 5, min: 5000, max: 8000 },
        { text: '8000-12000元', value: 6, min: 8000, max: 12000 },
        { text: '12000-15000元', value: 7, min: 12000, max: 15000 },
        { text: '15000元以上', value: 8, min: 15000, max: '' }
      ],
      optionSettr: [
        { text: '3天内', id: 3 },
        { text: '7天内', id: 7 },
        { text: '15天内', id: 15 },
        { text: '30天内', id: 30 }
      ],
      optionRange: [
        { text: '不限', value: '' },
        { text: '5公里', value: 5 },
        { text: '10公里', value: 10 },
        { text: '15公里', value: 15 },
        { text: '20公里', value: 20 },
        { text: '30公里', value: 30 }
      ],
      optionWage: '',
      optionEducation: [],
      optionExperience: [],
      optionJobTag: [],
      selectJobTag: [],
      BMap: {}
    }
  },
  watch: {
    $route (to, from) {
      // 对路由变化作出响应...
      if (from.name != 'jobShow' && to.name == 'jobListNearby') {
        // 将地址栏中的url参数初始化到参数对象中
        this.initQuery(to.query)
        this.fetchData(true)
        // 恢复筛选条件
        this.restoreFilter()
      }
    }
  },
  beforeRouteLeave (to, from, next) {
    if (to.name == 'jobShow') {
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
    this.$store.dispatch('getClassify', 'citycategory')
    this.$store.dispatch('getClassify', 'jobcategory')
    this.$store.dispatch('getClassify', 'education').then(() => {
      this.optionEducation = this.restructureData(
        this.$store.state.classifyEdu,
        '',
        'education'
      )
      this.restoreFilter()
    })
    this.$store.dispatch('getClassify', 'experience').then(() => {
      this.optionExperience = this.restructureData(
        this.$store.state.classifyExperience,
        '',
        'experience'
      )
      this.restoreFilter()
    })
    this.$store.dispatch('getClassify', 'jobTag').then(() => {
      // 重构福利待遇
      let storeJobTag = JSON.parse(
        JSON.stringify(this.$store.state.classifyJobTag)
      )
      storeJobTag = storeJobTag.map(function (item) {
        return { id: item.id, text: item.text, select: false }
      })
      storeJobTag.unshift({ id: '', text: '全部', select: true })
      this.optionJobTag = storeJobTag
      this.restoreFilter()
    })
  },
  mounted () {
    // 更新时间
    this.optionSettr = this.restructureData(this.optionSettr, '', 'settr')
    // 恢复筛选条件
    this.restoreFilter()
  },
  methods: {
    openedRange () {
      this.params.range = parseInt(this.params.range)
    },
    // 筛选距离
    handleRange (value) {
      if (value) {
        let thisRange = this.optionRange.filter(
          item => parseInt(item.value) === parseInt(value)
        )
        this.rangeTitle = thisRange[0].text
        this.params.range = value
      } else {
        this.rangeTitle = '距离'
        this.params.range = ''
      }
      this.doSearch({ range: value })
    },
    // 清空
    handleClearMore () {
      this.params.education = ''
      this.params.experience = ''
      this.params.tag = ''
      this.params.settr = ''
      this.handleSelectMore()
      this.optionEducation = this.restructureData(
        this.$store.state.classifyEdu,
        '',
        'education'
      )
      this.optionExperience = this.restructureData(
        this.$store.state.classifyExperience,
        '',
        'experience'
      )
      this.optionJobTag = this.optionJobTag.map(function (item, index) {
        return { id: item.id, text: item.text, select: index === 0 }
      })
      this.optionSettr = this.optionSettr.map(function (item, index) {
        return { id: item.id, text: item.text, select: index === 0 }
      })
    },
    // 恢复福利待遇
    restoreJobTag () {
      let queryData = this.$route.query
      if (queryData['tag'] && this.optionJobTag) {
        this.selectJobTag = queryData['tag'].split(',')
        this.selectJobTag = this.selectJobTag.map(function (item) {
          return parseInt(item)
        })
        let _this = this
        this.optionJobTag = this.optionJobTag.map(function (item) {
          return {
            id: item.id,
            text: item.text,
            select: _this.selectJobTag.includes(item.id)
          }
        })
      } else {
        this.optionJobTag = this.optionJobTag.map(function (item, index) {
          return { id: item.id, text: item.text, select: index === 0 }
        })
      }
    },
    // 福利待遇多选
    handleCheckJobTag (item) {
      if (item.id) {
        if (this.selectJobTag.includes(item.id)) {
          this.selectJobTag.splice(
            this.selectJobTag.findIndex(
              v => parseInt(v.id) === parseInt(item.id)
            ),
            1
          )
        } else {
          if (this.selectJobTag.length >= 5) {
            this.$toast('福利待遇最多可选5个')
          } else {
            this.selectJobTag.push(item.id)
          }
        }
        let _this = this
        this.optionJobTag = this.optionJobTag.map(function (item) {
          return {
            id: item.id,
            text: item.text,
            select: _this.selectJobTag.includes(item.id)
          }
        })
      } else {
        // 全部
        this.selectJobTag = []
        this.optionJobTag = this.optionJobTag.map(function (item, index) {
          return { id: item.id, text: item.text, select: index === 0 }
        })
      }
      this.params.tag = this.selectJobTag.join(',')
    },
    // 职位分类筛选打开之后给筛选组件赋值
    openedCategory () {
      this.$refs.dropCategory.$children[0].$children[0].initData()
      this.setComponentAttribute(this.$refs.dropCategory)
    },
    closedCategory () {
      this.$refs.dropCategory.$children[0].$children[0].handleSecondOverlay()
    },
    // 动态赋值筛选组件公用方法
    setComponentAttribute (component) {
      if (component.$children[0]) {
        let thisHeight = component.$children[0].$el.clientHeight
        component.$children[0].$children[0].layHeight = thisHeight
        let offTop = component.$el.offsetTop
        component.$children[0].$children[0].offTop = parseInt(
          parseInt(offTop) + parseInt(thisHeight) / 2
        )
      }
    },
    // 恢复职位分类筛选
    restoreJobCategory () {
      let queryData = this.$route.query
      if (queryData['category1']) {
        this.params.category1 = queryData['category1']
        this.params.category2 = queryData['category2']
        this.params.category3 = queryData['category3']
        // 恢复选中项对应的汉字
        let storeCategory = this.$store.state.classifyJobCategory
        let selectText = []
        let topItem = storeCategory.filter(
          item => parseInt(item.id) === parseInt(this.params.category1)
        )[0]
        selectText.push(topItem.text)
        if (topItem.children.length) {
          if (parseInt(this.params.category2)) {
            let secondItem = topItem.children.filter(
              item => parseInt(item.id) === parseInt(this.params.category2)
            )[0]
            selectText.push(secondItem.text)
            if (secondItem.children.length) {
              if (parseInt(this.params.category3)) {
                let lowestItem = secondItem.children.filter(
                  item => parseInt(item.id) === parseInt(this.params.category3)
                )[0]
                selectText.push(lowestItem.text)
              } else {
                selectText.push(`全${selectText[selectText.length - 1]}`)
              }
            }
          } else {
            // 相当于不限
            selectText.push(`全${selectText[selectText.length - 1]}`)
          }
        }
        this.categoryTitle = selectText[selectText.length - 1]
      } else {
        this.params.category1 = ''
        this.params.category2 = ''
        this.params.category3 = ''
        this.categoryTitle = '职位'
      }
    },
    // 职位分类筛选
    doSearchByCategory (data) {
      this.doSearch({
        category1: data.category1,
        category2: data.category2,
        category3: data.category3
      })
      this.categoryTitle = data.categoryName
      this.$refs.dropCategory.toggle()
    },
    // 更多选择确定
    handleSelectMore () {
      this.doSearch({
        education: this.params.education,
        experience: this.params.experience,
        tag: this.params.tag,
        settr: this.params.settr
      })
      this.$refs.dropMore.toggle()
    },
    // 恢复更多选中
    restoreFilter () {
      let queryData = this.$route.query
      // 恢复经验
      if (queryData['range'] && this.optionRange) {
        let thisRange = this.optionRange.filter(
          item => parseInt(item.value) === parseInt(queryData['range'])
        )
        this.rangeTitle = thisRange[0].text
        this.params.range = parseInt(thisRange[0].value)
      } else {
        this.rangeTitle = '距离'
        this.params.range = ''
      }
      this.restoreJobCategory()
      this.restoreWage()
      let resetEdu = ''
      if (queryData['education'] && this.optionEducation) {
        resetEdu = queryData['education']
      }
      this.optionEducation = this.restructureData(
        this.$store.state.classifyEdu,
        resetEdu,
        'education'
      )
      let resetExp = ''
      if (queryData['experience'] && this.optionExperience) {
        resetExp = queryData['experience']
      }
      this.optionExperience = this.restructureData(
        this.$store.state.classifyExperience,
        resetExp,
        'experience'
      )
      this.restoreJobTag()
      // 福利待遇
      let resetSettr = ''
      if (queryData['settr']) {
        resetSettr = queryData['settr']
      }
      this.optionSettr = this.restructureData(
        this.optionSettr,
        resetSettr,
        'settr'
      )
    },
    /**
     * 重构数据
     * @param data 需要重构的数据
     * @param id 标记是否选中
     * @param type 筛选条件
     * @returns {*}
     */
    restructureData (data, id, type) {
      let restoreArray = data
      let existSub = restoreArray.findIndex(v => {
        return v.id === ''
      })
      if (existSub === -1) {
        // 防止重复添加
        restoreArray.unshift({ id: '', text: '全部' })
      }
      if (type === 'education') {
        // 学历要求
        this.params.education = id
      } else if (type === 'experience') {
        // 工作经验
        this.params.experience = id
      } else if (type === 'settr') {
        // 更新时间
        this.params.settr = id
      }
      restoreArray = restoreArray.map(function (item, index) {
        let iSelect = false
        if (id) {
          // 标记选中项
          iSelect = parseInt(item.id) === parseInt(id)
        } else {
          // 无选中项，选中全部
          iSelect = index === 0
        }
        return { id: item.id, text: item.text, select: iSelect }
      })
      return restoreArray
    },
    // 恢复薪资选中
    restoreWage () {
      let queryData = this.$route.query
      if (queryData['minwage']) {
        let thisWage = this.optionFilterWage.filter(
          item => parseInt(item.min) === parseInt(queryData['minwage'])
        )
        this.optionWage = thisWage[0].value
        this.wageTitle = thisWage[0].text
      } else {
        this.optionWage = ''
        this.wageTitle = '薪资'
      }
    },
    // 选择薪资范围
    handleWage (value) {
      let thisWage = this.optionFilterWage.filter(
        item => parseInt(item.value) === value
      )
      this.wageTitle = thisWage[0].text
      this.params.minwage = thisWage[0].min
      this.params.maxwage = thisWage[0].max
      this.doSearch({
        minwage: this.params.minwage,
        maxwage: this.params.maxwage
      })
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
    setlocation () {
      let that = this
      let old_location_text = that.location_text
      that.location_text = '正在获取位置信息...'
      let BMap = that.BMap
      var geolocation = new BMap.Geolocation()
      geolocation.getCurrentPosition(
        function (r) {
          if (this.getStatus() == BMAP_STATUS_SUCCESS) {
            that.location_text = r.address.province + r.address.city
            that.lat = r.point.lat
            that.lng = r.point.lng
            let wechatShareInfo = {
              district: that.location_text
            }
            wxshare(wechatShareInfo, 'jobnearby', location.href)
            if (old_location_text != that.location_text) {
              that.initQuery(that.$route.query)
              that.fetchData(true)
            }
          } else {
            that.location_text = '获取位置信息失败'
          }
        },
        { enableHighAccuracy: true }
      )
    },
    handlerMap ({ BMap, map }) {
      this.BMap = BMap
      this.setlocation()
    },
    // 请求列表数据，init为true时直接更改dataset值，false时代表上拉加载回的数据追加进dataset
    fetchData (init) {
      this.show_empty = false

      if (init === true) {
        this.page = 1
        this.finished_text = ''
        this.finished = false
      }
      let conditions = { ...this.params }
      conditions.range = conditions.range ? conditions.range : this.def_range
      conditions.lat = this.lat
      conditions.lng = this.lng
      conditions.page = this.page
      conditions.pagesize = this.pagesize
      http
        .get(api.joblist, conditions)
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
            } else if (res.data.items.length === 0) {
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
      this.$router.push('/job/' + id)
    },
    // 搜索通用函数
    doSearch (data) {
      for (const key in data) {
        if (this.params.hasOwnProperty(key)) {
          this.params[key] = data[key]
        }
      }
      this.page = 1
      this.$router.push('/jobnearby' + obj2Param(this.params, '?'))
    },
    doSearchByKeyword (data) {
      for (const key in this.params) {
        this.params[key] = ''
      }
      this.params.keyword = data.keyword
      this.page = 1
      this.$router.push('/jobnearby' + obj2Param(this.params, '?'))
    },
    toggleSearch () {
      this.show = !this.show
    }
  }
}
</script>

<style lang="scss" scoped>
.more_box {
  .item_wrapper {
    .item_group {
      .item {
        &.selected {
          background-color: #e5f1ff;
          color: #5da9fc;
        }
        display: block;
        float: left;
        width: 81px;
        margin: 0 7px 10px 0;
        text-align: center;
        background-color: #f4f4f4;
        font-size: 13px;
        padding: 6.5px 0;
        &:nth-of-type(4n) {
          margin-right: 0;
        }
      }
    }
    .item_title {
      padding: 15px 0;
      font-size: 15px;
      color: #333333;
    }
    padding: 0 15px;
    position: relative;
    .wrapper_bottom {
      .btn_c {
        &.blue {
          background-color: #1787fb;
          color: #ffffff;
        }
        flex: 1;
        background-color: #f4f4f4;
        color: #333333;
        font-size: 15px;
        text-align: center;
        padding: 10.5px 0;
      }
      position: fixed;
      left: 0;
      top: 79%;
      display: flex;
      width: 100%;
      z-index: 3;
    }
  }
  position: relative;
  padding-bottom: 41px;
}
.location_box {
  .location {
    float: right;
    width: 50px;
    height: 45px;
    background: url("../assets/images/location_ico_orange.svg") center no-repeat;
    background-size: 17px;
  }
  .refresh {
    float: left;
    width: 28px;
    height: 45px;
    background: url("../assets/images/refresh_ico_blue.svg") center no-repeat;
    background-size: 14px;
  }
  .text {
    float: left;
    font-size: 14px;
    color: #333333;
    max-width: 285px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    padding: 12.5px 0 12.5px 17px;
  }
  position: relative;
  width: 100%;
  background-color: #ffffff;
}
.box_3 {
  .list {
    .top {
      position: absolute;
      right: -25px;
      top: -25px;
      width: 50px;
      height: 50px;
      background-color: #feae41;
      color: #ffffff;
      font-size: 10px;
      font-weight: bold;
      text-align: center;
      transform: rotateZ(45deg);
      padding-top: 36px;
    }
    .company {
      .crw_ico {
        float: left;
        margin-left: 6px;
        width: 14px;
        height: 18px;
        position: relative;
        img {
          width: 100%;
          height: 13px;
          position: absolute;
          top: 50%;
          left: 0;
          border: 0;
          transform: translate(0, -50%);
        }
      }
      .auth_ico {
        float: left;
        margin-left: 6px;
        width: 15px;
        height: 18px;
        background: url("../assets/images/jobs_list_auth_ico.png") 0 center
        no-repeat;
        background-size: 15px 11px;
      }
      .name {
        float: left;
        max-width: 250px;
        font-size: 13px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .distance{
        position: absolute;right: 0;top: 50%;transform: translate(0,-50%);font-size: 13px;color: #999;
      }
      position: relative;
      width: 100%;
      border-top: 1px solid #f3f3f3;
      padding: 12.5px 0;
    }
    .tag_wrapper {
      .van-tag {
        margin-right: 5px;
        border-radius: 3px;
      }
      width: 100%;
      padding-bottom: 13px;
    }
    .tx2 {
      .time {
        position: absolute;
        right: 0;
        top: 12.5px;
        font-size: 13px;
        color: #999999;
      }
      font-size: 14px;
      color: #666666;
      padding: 11.5px 60px 8.5px 0;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      position: relative;
    }
    .tx1 {
      .wage {
        position: absolute;
        right: 0;
        top: 19.5px;
        font-size: 14px;
        color: #ff5d24;
        font-weight: bold;
      }
      .worry_ico {
        font-size: 10px;
        color: #ffffff;
        padding: 3px;
        border-radius: 3px;
        background-color: #ff8b82;
        float: left;
      }
      .name {
        font-size: 16px;
        color: #333333;
        font-weight: bold;
        max-width: 240px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        float: left;
        margin-right: 6px;
      }
      position: relative;
      padding-top: 17.5px;
    }
    position: relative;
    width: 100%;
    background-color: #ffffff;
    padding: 0 17px;
    overflow: hidden;
  }
  width: 100%;
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
