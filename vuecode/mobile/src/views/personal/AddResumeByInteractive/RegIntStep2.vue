<template>
  <div id="app">
    <Head>创建简历2/4</Head>
    <div class="form_active_title">“告诉我，你想找什么样的工作？”</div>
    <van-field
      required
      readonly
      clickable
      :value="districtName"
      label="期望地区"
      placeholder="点击选择期望地区"
      @click="showPickerDistrict = true"
      class="form_choose reset_after"
    />
    <van-popup v-model="showPickerDistrict"
               position="bottom"
               :style="{ 'max-height': '70%' }"
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
      required
      readonly
      clickable
      :value="categoryName"
      label="期望职位"
      placeholder="点击选择期望职位"
      @click="showPickerJobCategory = !showPickerJobCategory"
      class="form_choose reset_after"
    />
    <van-popup
      v-model="showPickerJobCategory"
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
      :value="wageName"
      label="期望薪资"
      placeholder="点击选择薪资"
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
    <div style="margin: 16px;">
      <van-button round block type="info" @click="handleSubmit">
        下一步
      </van-button>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import api from '@/api'
import JobCategoryFilter from '@/components/JobCategoryFilter'
import DistrictFilter from '@/components/DistrictFilter'
export default {
  name: 'RegIntStep2',
  components: {
    JobCategoryFilter,
    DistrictFilter
  },
  created () {
    this.$store.dispatch('getClassify', 'citycategory')
    this.$store.dispatch('getClassify', 'jobcategory')
    this.$store.dispatch('getClassifyWage')
  },
  computed: {
    columnsWage () {
      return [
        { values: Object.keys(this.$store.state.classifyWage) },
        { values: this.$store.state.classifyWage[500] }
      ]
    }
  },
  data () {
    return {
      category1: '',
      category2: '',
      category3: '',
      categoryName: '',
      minwage: '',
      maxwage: '',
      wageName: '',
      district1: '',
      district2: '',
      district3: '',
      districtName: '',
      showPickerDistrict: false,
      showPickerJobCategory: false,
      showPickerWage: false
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
    onConfirmWage (values) {
      this.minwage = parseInt(values[0])
      this.maxwage = parseInt(values[1])
      this.wageName = `${parseInt(values[0])}-${parseInt(values[1])}元`
      this.showPickerWage = !this.showPickerWage
    },
    onChangeWage (picker, values) {
      picker.setColumnValues(1, this.$store.state.classifyWage[values[0]])
    },
    onConfirmJobCategory (values, index) {
      let jobCategory = this.$store.state.classifyJobCategory
      this.category1 = jobCategory[index[0]].id
      this.category2 = jobCategory[index[0]].children[index[1]].id
      this.category3 =
        jobCategory[index[0]].children[index[1]].children[index[2]].id
      this.categoryName = `${values}`
      this.showPickerJobCategory = !this.showPickerJobCategory
    },
    onConfirmDistrict (value) {
      this.district1 = value[0].code
      this.district2 = value[1].code
      this.district3 = value[2].code
      this.districtName = `${value[0].name}/${value[1].name}/${value[2].name}`
      this.showPickerDistrict = !this.showPickerDistrict
    },
    handleSubmit () {
      if (!this.district1) {
        this.$notify('请选择期望地区')
        return false
      }
      if (!this.category1) {
        this.$notify('请选择期望职位')
        return false
      }
      if (!this.minwage || !this.maxwage) {
        this.$notify('请选择期望薪资')
        return false
      }
      http.post(api.reg_resume_int_step2, {
        category1: this.category1,
        category2: this.category2,
        category3: this.category3,
        minwage: this.minwage,
        maxwage: this.maxwage,
        district1: this.district1,
        district2: this.district2,
        district3: this.district3
      })
        .then(res => {
          this.$router.push({ path: '/member/personal/resume_add_int/step3' })
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
}
</script>

<style scoped></style>
