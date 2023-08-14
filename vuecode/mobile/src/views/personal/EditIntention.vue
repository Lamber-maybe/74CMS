<template>
  <div id="app">
    <Head>求职意向</Head>
    <van-field
      readonly
      clickable
      required
      :value="natureName"
      label="工作性质"
      placeholder="请选择"
      @click="showPickerNature = !showPickerNature"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerNature" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsNature"
        :default-index="natureDefaultIndex"
        @confirm="onConfirmNature"
        @cancel="showPickerNature = false"
      />
    </van-popup>
    <van-field
      readonly
      clickable
      required
      :value="categoryName"
      label="期望职位"
      placeholder="请选择"
      @click="showPickerJobCategory = !showPickerJobCategory"
      class="form_choose reset_after"
    />
    <van-popup
      v-model="showPickerJobCategory"
      :lazy-render="false"
      position="bottom"
      :style="{ 'max-height': '70%' }"
      ref="dropCategory"
      :lock-scroll="false"
      @click-overlay="handleCategoryOverlay"
      @opened="openedCategory"
    >
      <JobCategoryFilter
        :categories="[category1, category2, category3]"
        :type="false"
        @doSelect="doSelectCategory"
        :all="false"
      ></JobCategoryFilter>
    </van-popup>
    <van-field
      readonly
      clickable
      required
      :value="districtName"
      label="期望地区"
      placeholder="请选择"
      @click="showPickerDistrict = !showPickerDistrict"
      class="form_choose reset_after"
    />
<!--    【优化】 求职登录地区样式问题-->
<!--    zdq 2022.08.11-->
<!--    【变更前】-->
<!--    :style="{ 'max-height': '70%' }"-->
<!--    【变更后】-->
<!--    :style="{ 'height': '70%' }"-->
    <van-popup v-model="showPickerDistrict"
               position="bottom"
               :style="{ 'height': '70%' }"
               ref="dropDistrict"
               :lock-scroll="false"
               @click-overlay="handleDistrictOverlay"
               @opened="openedDistrict"
    >
      <DistrictFilter
        :districts="[district1, district2, district3]"
        :type="false"
        @doSelect="doSelectDistrict"
      ></DistrictFilter>
    </van-popup>
    <van-field
      readonly
      clickable
      required
      name="wage"
      :value="wageName"
      label="期望薪资"
      placeholder="请选择"
      @click="showPickerWage = !showPickerWage"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerWage" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsWage"
        @confirm="onConfirmWage"
        @change="onChangeWage"
        @cancel="showPickerWage = false"
      />
    </van-popup>
    <van-field
      :required="fieldStore.intention.trade.is_require === 1"
      v-if="fieldStore.intention.trade.is_display"
      readonly
      clickable
      :value="tradeName"
      :label="fieldStore.intention.trade.field_cn"
      placeholder="请选择"
      @click="showPickerTrade = !showPickerTrade"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerTrade" position="bottom">
      <van-picker
        show-toolbar
        :columns="columnsTrade"
        :default-index="tradeDefaultIndex"
        @confirm="onConfirmTrade"
        @cancel="showPickerTrade = false"
      />
    </van-popup>
    <div style="margin: 16px 16px 0;">
<!--      修改求职意向重复保存  zch 2022/9/16    -->
      <van-button round block type="info" @click="handleSubmit" :disabled="issubmit">
        保存
      </van-button>
    </div>
    <div class="box_1" v-if="parseInt(id) > 0" @click="deleteCurrentIntention">
      删除此求职意向
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import JobCategoryFilter from '@/components/JobCategoryFilter'
import DistrictFilter from '@/components/DistrictFilter'
export default {
  name: 'EditIntention',
  components: {
    JobCategoryFilter,
    DistrictFilter
  },
  data () {
    return {
      tradeDefaultIndex: 0,
      wageStart: this.$store.state.minWage,
      wageDefaultIndex1: 0,
      wageDefaultIndex2: 0,
      pickerWage: '',
      natureDefaultIndex: '',
      showPickerTrade: false,
      showPickerWage: false,
      showPickerDistrict: false,
      showPickerJobCategory: false,
      showPickerNature: false,
      id: 0,
      district1: '',
      district2: '',
      district3: '',
      district: '',
      districtName: '',
      category1: '',
      category2: '',
      category3: '',
      categoryName: '',
      minwage: '',
      maxwage: '',
      wageName: '',
      nature: '',
      natureName: '',
      trade: '',
      tradeName: '',
      issubmit: false // 修改求职意向重复保存  zch 2022/9/16
    }
  },
  created () {
    this.$store.dispatch('getClassify', 'resumeNature')
    this.$store.dispatch('getClassify', 'jobcategory')
    this.$store.dispatch('getClassify', 'citycategory')
    this.$store.dispatch('getClassifyWage')
    this.$store.dispatch('getClassify', 'trade')
    // 获取路由中的id
    this.id = this.$route.params.id
    if (this.id > 0) {
      // 如果有id，根据id取出对应的求职意向
      let thisIntention = this.intentionStore.filter(
        item => parseInt(item.id) === parseInt(this.id)
      )
      this.initData(thisIntention[0])
      // 恢复工作性质
      this.natureDefaultIndex = this.columnsNature.findIndex(
        item => parseInt(item.id) === parseInt(this.nature)
      )
      let _this = this
      // 恢复薪资
      let pickerWage = this.$store.state.classifyWage
      Object.keys(pickerWage).forEach(function (key, index) {
        if (parseInt(key) === parseInt(_this.minwage)) {
          _this.wageDefaultIndex1 = index
          _this.wageStart = _this.minwage
          _this.wageDefaultIndex2 = pickerWage[key].findIndex(
            item => parseInt(item) === _this.maxwage
          )
        }
      })
      // 恢复期望行业
      this.tradeDefaultIndex = this.columnsTrade.findIndex(
        item => parseInt(item.id) === parseInt(this.trade)
      )
    }
  },
  computed: {
    columnsNature () {
      return this.$store.state.classifyResumeNature
    },
    areaDistrict () {
      return this.$store.state.classifyCityCategory
    },
    columnsWage () {
      return [
        {
          values: Object.keys(this.$store.state.classifyWage),
          defaultIndex: this.wageDefaultIndex1
        },
        {
          values: this.$store.state.classifyWage[this.wageStart],
          defaultIndex: this.wageDefaultIndex2
        }
      ]
    },
    columnsTrade () {
      return this.$store.state.classifyTrade
    },
    intentionStore () {
      return this.$store.state.resume.intention_list
    },
    fieldStore () {
      return this.$store.state.resume.field_rule
    }
  },
  methods: {
    doSelectCategory (data) {
      this.category1 = data[0]
      this.category2 = data[1]
      this.category3 = data[2]
      this.categoryName = data[3]
      this.showPickerJobCategory = !this.showPickerJobCategory
    },
    doSelectDistrict (data) {
      this.district1 = data[0]
      this.district2 = data[1]
      this.district3 = data[2]
      this.districtName = data[3]
      this.showPickerDistrict = !this.showPickerDistrict
    },
    handleCategoryOverlay () {
      this.$refs.dropCategory.$children[0].handleSecondOverlay()
    },
    handleDistrictOverlay () {
      this.$refs.dropDistrict.$children[0].handleCityOverlay()
    },
    // 职位分类筛选打开之后给筛选组件赋值
    openedCategory () {
      this.$refs.dropCategory.$children[0].initData()
      setTimeout(() => {
        this.dynamicAssignFun(this.$refs.dropCategory)
      }, 300)
    },
    // 地区筛选打开之后给筛选组件赋值
    openedDistrict () {
      this.$refs.dropDistrict.$children[0].initData()
      setTimeout(() => {
        this.dynamicAssignFun(this.$refs.dropDistrict)
      }, 300)
    },
    // 动态赋值筛选组件公用方法
    dynamicAssignFun (obj) {
      let popHeight = obj.$el.clientHeight
      if (obj.$children[0]) {
        obj.$children[0].layHeight = popHeight
        let offTop = obj.$el.offsetTop
        obj.$children[0].offTop = parseInt(parseInt(offTop) + parseInt(popHeight) / 2)
      }
    },
    // 初始化接口返回数据
    initData (interfaceIntention) {
      this.nature = interfaceIntention.nature
      this.natureName = interfaceIntention.nature_text
      this.category1 = interfaceIntention.category1
      this.category2 = interfaceIntention.category2
      this.category3 = interfaceIntention.category3
      this.categoryName = interfaceIntention.category_text
      this.district1 = interfaceIntention.district1
      this.district2 = interfaceIntention.district2
      this.district3 = interfaceIntention.district3
      this.district = interfaceIntention.district
      this.districtName = interfaceIntention.district_text
      this.minwage = interfaceIntention.minwage
      this.maxwage = interfaceIntention.maxwage
      this.wageName = `${parseInt(this.minwage)}-${parseInt(this.maxwage)}元`
      this.trade = interfaceIntention.trade
      this.tradeName = interfaceIntention.trade_text
    },
    // 选择工作性质
    onConfirmNature (value) {
      this.nature = value.id
      this.natureName = value.text
      this.showPickerNature = !this.showPickerNature
    },
    // 选择期望地区
    onConfirmDistrict (value) {
      this.district1 = value[0].code
      this.district2 = value[1] ? value[1].code : ''
      this.district3 = value[2] ? value[2].code : ''
      this.districtName = `${value[0].name}${value[1] ? '/' + value[1].name : ''}${value[2] ? '/' + value[2].name : ''}`
      this.showPickerDistrict = !this.showPickerDistrict
    },
    // 选择期望薪资
    onConfirmWage (values) {
      this.minwage = parseInt(values[0])
      this.maxwage = parseInt(values[1])
      this.wageName = `${parseInt(values[0])}-${parseInt(values[1])}元`
      this.showPickerWage = !this.showPickerWage
    },
    // 薪资级联
    onChangeWage (picker, values) {
      picker.setColumnValues(1, this.$store.state.classifyWage[values[0]])
    },
    // 选择期望行业
    onConfirmTrade (value) {
      this.trade = value.id
      this.tradeName = value.text
      this.showPickerTrade = !this.showPickerTrade
    },
    // 保存
    handleSubmit () {
      if (!this.nature) {
        this.$notify('请选择工作性质')
        return false
      }
      if (!this.category1) {
        this.$notify('请选择期望职位')
        return false
      }
      if (!this.district1) {
        this.$notify('请选择期望地区')
        return false
      }
      if (!this.minwage || !this.maxwage) {
        this.$notify('请选择薪资')
        return false
      }
      if (this.fieldStore.intention.trade.is_require) {
        if (!this.trade) {
          this.$notify(`请选择${this.fieldStore.intention.trade.field_cn}`)
          return false
        }
      }
      // 修改求职意向重复保存  zch 2022/9/16
      this.issubmit = true
      http
        .post(api.resume_intention_save, {
          id: this.id,
          district1: this.district1,
          district2: this.district2,
          district3: this.district3,
          category1: this.category1,
          category2: this.category2,
          category3: this.category3,
          minwage: this.minwage,
          maxwage: this.maxwage,
          nature: this.nature,
          trade: this.trade
        })
        .then(res => {
          if (parseInt(res.code) === 200) {
            this.$notify({ type: 'success', message: res.message })
            this.$router.go(-1)
          } else {
            // 修改求职意向重复保存  zch 2022/9/16
            this.issubmit = false
            this.$notify(res.message)
          }
        })
        .catch(err => {
          // 修改求职意向重复保存  zch 2022/9/16
          this.issubmit = false
          console.log(err)
        })
    },
    // 删除此条求职意向
    deleteCurrentIntention () {
      this.$dialog
        .confirm({
          title: '提示',
          message: '确定删除该条求职意向吗'
        })
        .then(() => {
          // 确定按钮
          http
            .post(api.resume_intention_delete, {
              id: this.id
            })
            .then(res => {
              if (parseInt(res.code) === 200) {
                this.$notify({ type: 'success', message: res.message })
                this.$router.go(-1)
              } else {
                this.$notify(res.message)
              }
            })
            .catch(err => {
              console.log(err)
            })
        })
        .catch(() => {
          // 取消按钮
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.box_1 {
  font-size: 13px;
  color: #999999;
  text-align: center;
  width: 100%;
  padding: 15px 0;
}
</style>
