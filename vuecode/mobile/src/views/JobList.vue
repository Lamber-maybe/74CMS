<template>
  <div id="app">
    <Meta pagealias="joblist" :query_data="$route.query" />
    <Head>找工作</Head>
    <div class="form_top_border"></div>
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
        type="job"
        @hideSearch="toggleSearch"
        @doSearch="doSearchByKeyword"
      ></TopSearch>
    </van-popup>
    <div class="box_2">
      <van-dropdown-menu class="filter_menu">
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
    <van-empty
      image="search"
      description="没有找到对应的数据"
      style="background-color:#fff"
      v-if="show_empty === true && showLayer === false"
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
            <div class="top" v-if="item.stick == 1">置顶</div>
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
            <div class="tag_wrapper clearfix" v-if="item.tag.length>0">
              <div class="tag_cell" v-for="(t, key) in item.tag" :key="key">{{ t }}</div>
            </div>
            <div class="company">
              <div class="name">{{ item.companyname }}</div>
              <div class="auth_ico" v-if="item.company_audit == 1"></div>
              <div class="crw_ico" v-if="item.setmeal_icon != ''">
                <img :src="item.setmeal_icon" />
              </div>
              <div class="clear"></div>
            </div>
          </div>
          <div class="form_split_10"></div>
        </div>
      </div>
    </van-list>
    <div class="login_layer" v-if="showLayer">
      <div class="ll_tip">30秒快速注册简历，海量职位任意投</div>
      <div class="ll_tip_more">登录或注册简历后可以查看更多职位</div>
      <div class="ll_qr_box"><img :src="$store.state.config.wechat_qrcode" alt="" class="ll_qr"></div>
      <div class="ll_tip_more">微信扫一扫，求职更轻松</div>
      <div class="ll_tip_bth">
        <router-link to="/member/login" class="a_btn">登录</router-link>
        <router-link to="/member/reg/personal" class="a_btn blue">注册</router-link>
      </div>
      <div class="ll_tip_tel" v-if="$store.state.config.contact_tel">联系客服：{{ $store.state.config.contact_tel }}</div>
    </div>
    <BottomNav></BottomNav>
  </div>
</template>

<script>
import wxshare from '@/assets/js/share.js'
import { obj2Param } from '@/utils/index'
import http from '@/utils/http'
import api from '@/api'
import DistrictFilter from '@/components/DistrictFilter'
import JobCategoryFilter from '@/components/JobCategoryFilter'
export default {
  name: 'JobList',
  components: {
    DistrictFilter,
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
        search_type: 'list',
        keyword: '',
        district1: '',
        district2: '',
        district3: '',
        category1: '',
        category2: '',
        category3: '',
        experience: '',
        minwage: '',
        maxwage: '',
        filter_apply: '',
        nature: '',
        education: '',
        tag: '',
        settr: ''
      },
      page: 1,
      pagesize: 15,
      districtTitle: '地区',
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
      optionWage: '',
      optionEducation: [],
      optionExperience: [],
      optionJobTag: [],
      selectJobTag: [],
      // 未登录引导
      showLayer: false
    }
  },
  watch: {
    $route (to, from) {
      // 对路由变化作出响应...
      if (from.name !== 'jobShow' && to.name === 'jobList') {
        // 将地址栏中的url参数初始化到参数对象中
        this.initQuery(to.query)
        this.fetchData(true)
        // 恢复筛选条件
        this.restoreFilter()
      }
    }
  },
  beforeRouteLeave (to, from, next) {
    if (to.name === 'jobShow') {
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
    wxshare({}, 'joblist', location.href)
  },
  mounted () {
    // 更新时间
    this.optionSettr = this.restructureData(this.optionSettr, '', 'settr')
    // 恢复筛选条件
    this.restoreFilter()
  },
  methods: {
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
    initQuery (query) {
      for (const key in this.params) {
        if (query.hasOwnProperty(key)) {
          this.params[key] = query[key]
        } else {
          this.params[key] = ''
        }
      }
    },
    // 职位分类筛选打开之后给筛选组件赋值
    openedCategory () {
      this.$refs.dropCategory.$children[0].$children[0].initData()
      setTimeout(() => {
        this.setComponentAttribute(this.$refs.dropCategory)
      }, 300)
    },
    // 地区筛选打开之后给筛选组件赋值
    openedDistrict () {
      this.$refs.dropDistrict.$children[0].$children[0].initData()
      setTimeout(() => {
        this.setComponentAttribute(this.$refs.dropDistrict)
      }, 300)
    },
    closedCategory () {
      this.$refs.dropCategory.$children[0].$children[0].handleSecondOverlay()
    },
    closedDistrict () {
      this.$refs.dropDistrict.$children[0].$children[0].handleCityOverlay()
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
    // 恢复地区筛选
    restoreDistrict () {
      let queryData = this.$route.query
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
    restoreFilter (type) {
      let queryData = this.$route.query
      this.restoreDistrict()
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
      // 福利待遇
      this.restoreJobTag()
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
      let existSub = restoreArray.findIndex(v => v.id === '')
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
      } else if (type === 'tag') {
        // 福利待遇
        this.params.tag = id
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
    // 请求列表数据，init为true时直接更改dataset值，false时代表上拉加载回的数据追加进dataset
    fetchData (init) {
      this.show_empty = false

      let conditions = { ...this.params }
      if (init === true) {
        this.page = 1
        this.finished_text = ''
        this.finished = false
      }
      conditions.page = this.page
      conditions.pagesize = this.pagesize
      http
        .get(api.joblist, conditions)
        .then(res => {
          if (init === true) {
            this.dataset = [...res.data.items]
            this.showLayer = parseInt(res.data.show_mask) === 1
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
      this.$router.push('/joblist' + obj2Param(this.params, '?'))
    },
    doSearchByKeyword (data) {
      for (const key in this.params) {
        this.params[key] = ''
      }
      this.params.keyword = data.keyword
      this.page = 1
      this.$router.push('/joblist' + obj2Param(this.params, '?'))
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
.login_layer {
  width: 100%;padding: 40px 0;text-align: center;background: url("../assets/images/login_layer_job_bg.jpg") 0 no-repeat;
  background-size: 100%;
  .ll_tip_tel {
    font-size: 14px;color: #666;padding-top: 10px;
  }
  .ll_tip_bth {
    .a_btn {
      &.blue { background: #128bed; }
      display: inline-block;padding: 6px 25px;background: #e33244;color: #fff;border-radius: 3px;margin: 0 10px;
      font-size: 14px;
    }
  }
  .ll_qr_box {
    .ll_qr { width: 100px;height: 100px;border: 0; }
    width: 100px;height: 100px;margin: 10px auto 5px;
  }
  .ll_tip {
    font-size: 16px;font-weight: bold;margin-bottom: 5px;color: #333;
  }
  .ll_tip_more {
    font-size: 14px;color: #666;margin-bottom: 10px;
  }
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
        max-width: 300px;
        font-size: 13px;
        color: #999999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      width: 100%;
      border-top: 1px solid #f3f3f3;
      padding: 12.5px 0;
    }
    .tag_wrapper {
      .van-tag {
        margin-right: 5px;
        border-radius: 3px;
      }
      .tag_cell {
        float: left;
        padding: 2px 5px;
        margin: 0 5px 5px 0;
        font-size: 10px;
        background-color: #e9f8ff;
        color: #8096a3;
      }
      width: 100%;
      padding-bottom: 8px;
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
      &.has {
        color: #333;
      }
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
